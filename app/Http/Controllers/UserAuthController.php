<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Mail\OneTimePasswordEmail;
use App\Models\OneTimePassword;
use App\Models\User;
use GoogleOneTap\Services\GoogleOneTapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 401);
        }

        User::create([
            'email' => $request->json('email'),
            'password' => Hash::make($request->json('password')),
        ]);

        return response()->json([
            'message' => 'User Created',
        ]);
    }

    private function loginWithCode(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'oneTimeCode' => 'required|string'
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'errors' => $credentials->errors(),
            ], 419);
        }

        $oneTimePassword = OneTimePassword::where(
            'column', $credentials->attributes()['email'],
        )->first();

        if ($oneTimePassword) {
            $oneTimePassword->increment('tries');
        } else {
            return response()->json([
                'errors' => [
                    'oneTimeCode' => ['Invalid oneTimeCode']
                ],
            ], 419);
        }

        if ($oneTimePassword->tries > 3) {
            $oneTimePassword->delete();

            return response()->json([
                'errors' => [
                    'code' => ['Too many tries']
                ],
            ], 419);
        }

        if ($oneTimePassword->code === $credentials->attributes()['code']) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Logged in successfully'
            ]);
        }

        return response()->json([
            'errors' => [
                'oneTimeCode' => ['Invalid oneTimeCode']
            ],
        ], 419);
    }

    public function login(Request $request)
    {
        if ($request->json('oneTimeCode')) {
            return $this->loginWithCode($request);
        }

        if ($request->json('code')) {
            return $this->loginWith($request);
        }

        $credentials = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'errors' => $credentials->errors(),
            ], 401);
        }

        if (Auth::attempt($credentials->validated())) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Logged in successfully'
            ]);
        }

        return response()->json([
            'message' => 'Sign in failed'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            "message" => "logged out successfully"
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function requireOneTimePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            sleep(3);
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $user = User::where('email', $validator->validated()['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'In case the email exists, a reset link will be sent to it.'
            ]);
        }

        $message = (new OneTimePasswordEmail($user))
            ->onConnection('sqs')
            ->onQueue('emails');

        Mail::to($user->email)
            ->cc('cc@domain.com')
            ->bcc('bcc@domain.com')
            ->send($message);


        return response()->json([
            'message' => 'In case the email exists, a reset link will be sent to it.'
        ]);
    }

    private function loginWith(Request $request)
    {
        $provider = $request->json('provider');
        $code = $request->json('code');
        if (!$code) {
            return response()->json([
                'errors' => ['token' => ['Token is required']]
            ], 401);
        }
        try {
            $data = Socialite::driver($provider)->stateless()->userFromToken($code);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => ['token' => ['Invalid token']]
            ], 401);
        }

        $userData = (object)$data->user;

        if (User::where('email', $data->email)->doesntExist()) {
            User::create([
                'email' => $data->getEmail(),
                'name' => $data->getName(),
                'surname' => isset($userData->family_name) ? $userData->family_name : null,
                'email_verified_at' => isset($userData->email_verified) && $userData->email_verified ? now() : null,
                $provider . '_id' => $data->getId(),
                $provider . '_token' => $data->token,
                'password' => Hash::make(Str::random(12)),
            ]);
            $user = User::where('email', $data->email)->first();
        } else {
            $user = User::where('email', $data->email)->first();
            $user->update([
                $provider . '_id' => $data->getId(),
                $provider . '_token' => $data->token,
            ]);
        }
        Auth::login($user);

        $request->session()->regenerateToken();

        return response()->json([
            'message' => "Logged in with {$provider} successfully"
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Mail\OneTimePasswordEmail;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

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
            'code' => 'required|string'
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
                    'code' => ['Invalid code']
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
                'code' => ['Invalid code']
            ],
        ], 419);
    }

    public function login(Request $request)
    {
        if ($request->json('code')) {
            return $this->loginWithCode($request);
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
}

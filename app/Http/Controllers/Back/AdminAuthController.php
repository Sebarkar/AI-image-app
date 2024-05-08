<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
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



    public function login(Request $request)
    {
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
            if (!$request->user()->hasRole('admin')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }
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
}

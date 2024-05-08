<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\IndexController;
use \App\Http\Controllers\GuestController;
use \App\Http\Controllers\UserAuthController;
use \App\Http\Controllers\Back\AdminAuthController;
use \Illuminate\Foundation\Auth\EmailVerificationRequest;
use \App\Http\Controllers\Back\UserController as BackUserController;
use App\Events\Auth\UserVerified;


Route::prefix('v1')->group(function () {
    Route::post('check-owner-access', [GuestController::class, 'isRegistered']);
    Route::post('get-guest-access', [GuestController::class, 'getAccess']);

    Route::post('user/tokens/create', function (Request $request) {
        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    });

    Route::post('guest/tokens/create', function (Request $request) {
        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    });

    //Admin routes
    Route::prefix('admin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AdminAuthController::class, 'login'])->name('login');
            Route::middleware(['auth:sanctum', 'role:owner,admin'])->group(function () {
                Route::get('user', [AdminAuthController::class, 'user']);
                Route::post('logout', [AdminAuthController::class, 'logout']);
            });
        });

        //Only for role owner
        Route::middleware(['auth:sanctum', 'role:owner,admin'])->group(function () {
            Route::controller(BackUserController::class)->group(function () {
                Route::post('/users', 'index');
            });
        });
    });

    //User routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('test', [IndexController::class, 'index']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('register', [UserAuthController::class, 'register']);
        Route::post('login', [UserAuthController::class, 'login'])->name('login');
        //Handle redirect Laravels default login route
        Route::get('login', (function () {
            return response()->json(['message' => 'Unauthorized'], 403);
        }));

        Route::post('one-time-password', [UserAuthController::class, 'requireOneTimePassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', [UserAuthController::class, 'user']);
            Route::post('logout', [UserAuthController::class, 'logout']);

            Route::post('email/verification-notification', function (Request $request) {
                $request->user()->sendEmailVerificationNotification();
                return response()->noContent();
            })->middleware('throttle:verification');

            Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
                $request->fulfill();

                UserVerified::dispatch($request->user());

                return response()->noContent();
            })->name('verification.verify');
        });
    });
});

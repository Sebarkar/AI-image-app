<?php

namespace App\Providers;

use App\Mail\VerificationEmail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Services\Auth\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new VerificationEmail($url))
                ->subject(__('mail.auth.Verify Email Address'))
                ->line(__('mail.auth.Gain access to our service'))
                ->line(__('mail.auth.click to verify email address'))
                ->action(__('mail.auth.Verify Email Address'), $url);
        });

        //Limiters
        RateLimiter::for('verification', function (Request $request) {
            return Limit::perMinute(1)->by($request->user()->id);
        });
    }
}

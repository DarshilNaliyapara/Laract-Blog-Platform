<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
        Gate::define('update-post', function (User $user, Blog $blog) {
            return $user->id === $blog->user_id;
        });
        Gate::define('isadmin', function ($user) {
            return $user->hasRole('super_admin') ? true : null;
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $otp = rand(100000, 999999);

            // Store OTP in cache with expiration time (e.g., 10 minutes)
            Cache::put('email_otp_' . $notifiable->id, $otp, now()->addMinutes(10));
        
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Here is Your OTP: ' . $otp)
                ->line('Or Click This Button')
                ->action('Verify Email',$url)
               ->line('Do Not share OTP with anyone!');
        });
    }
}

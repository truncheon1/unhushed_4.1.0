<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom login redirect for checkout flow
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                // Check if user is in checkout flow
                if (session('purchaser')) {
                    $path = session('path', 'educators');
                    return redirect("/{$path}/address");
                }
                
                // Default redirect to dashboard
                return redirect()->intended(config('fortify.home', '/dashboard'));
            }
        });

        // Custom register redirect for checkout flow
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                // Check if user is in checkout flow
                if (session('purchaser')) {
                    $path = session('path', 'educators');
                    return redirect("/{$path}/address");
                }
                
                // Default redirect to dashboard
                return redirect()->intended(config('fortify.home', '/dashboard'));
            }
        });

        Fortify::loginView(function(){
            return view('auth.login');
        });

        Fortify::registerView(function(){
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function(){
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function($request){
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function(){
            return view('auth.verify-email');
        });
    }
}

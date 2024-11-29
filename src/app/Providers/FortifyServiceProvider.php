<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                //管理者ユーザーチェック
                if ($request->is('admin/*')){
                    return redirect('/admin/login');
                }
                return redirect('/login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function(){
            return view('auth.register');
        });

        // カスタム認証ロジックの定義
        Fortify::authenticateUsing(function (Request $request) { 
            if ($request->is('admin/*')) {
                // 管理者用認証ロジック
                $admin = Admin::where('email', $request->email)->first();
                if ($admin && Hash::check($request->password, $admin->password)) {
                    return $admin;
                }
            } else {
                // 一般ユーザー用認証ロジック
                $user = User::where('email', $request->email)->first();
                if ($user && Hash::check($request->password, $user->password)) {
                    return $user;
                }
            }
            return null; 
        });

        Fortify::loginView(function(){
            return view('auth.login');
        });

        RateLimiter::for('login', function(Request $request){
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::verifyEmailView(function(){
            return view('auth.verify-email');
        });

    }
}

<?php
namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            try {
                $token = $request->header('TOKEN') ?: $request->input('_TOKEN');
                if (empty($token)) {
                    return null;
                }
                $token = base64_safe_decode($token);
                $user = Crypt::decrypt($token);
                if (Cache::get($user['uid']) !== $token) {
                    Cache::forget($user['uid']);
                    return null;
                }
                return $user;
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}

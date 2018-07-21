<?php
namespace App\Providers;

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
                return Crypt::decrypt(base64_safe_decode($token));
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}

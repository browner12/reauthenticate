<?php namespace browner12\reauthenticate;

use Illuminate\Support\ServiceProvider;

class ReauthenticateServiceProvider extends ServiceProvider
{
    /**
     * register the service provider
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        //publish configuration
        $this->publishes([
            __DIR__ . '/config/reauthenticate.php' => config_path('reauthenticate.php'),
        ], 'config');
    }
}

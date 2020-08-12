<?php

namespace NotificationChannels\Infobip;


use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class InfobipServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(InfobipConfig::class, function () {
            return new InfobipConfig($this->app['config']['services.infobip']);
        });

        Notification::extend('infobip', function ($app) {
            return new InfobipChannel();
        });
    }
}

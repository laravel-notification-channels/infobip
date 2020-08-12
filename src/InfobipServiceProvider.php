<?php

namespace NotificationChannels\Infobip;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Notification;
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

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('infobip', function ($app) {
                return $this->app->make(InfobipChannel::class);
            });
        });
    }
}

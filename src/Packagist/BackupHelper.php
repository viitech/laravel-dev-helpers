<?php

namespace VIITech\Helpers\Packagist;

class BackupHelper
{

    /**
     * Configure
     * @param \Laravel\Lumen\Application $app
     */
    public static function configure($app)
    {
        $app->configure('backup');
    }

    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(\Spatie\Backup\BackupServiceProvider::class);
        $app->register(\Illuminate\Notifications\NotificationServiceProvider::class);
    }
}
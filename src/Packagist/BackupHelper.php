<?php

namespace VIITech\Helpers\Packagist;

use Illuminate\Notifications\NotificationServiceProvider;
use Laravel\Lumen\Application;
use Spatie\Backup\BackupServiceProvider;

class BackupHelper
{

    /**
     * Configure
     * @param Application $app
     */
    public static function configure($app)
    {
        $app->configure('backup');
    }

    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(BackupServiceProvider::class);
        $app->register(NotificationServiceProvider::class);
    }
}
<?php

namespace VIITech\Helpers;

use Appzcoder\LumenRoutesList\RoutesCommandServiceProvider;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Notifications\NotificationServiceProvider;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Application;
use Laravel\Tinker\TinkerServiceProvider;
use Sentry\Laravel\ServiceProvider;
use Spatie\Backup\BackupServiceProvider;

class ApplicationHelper
{

    /**
     * Configure Sentry
     * @param Application $app
     */
    public static function configureSentry($app)
    {
        $app->register(ServiceProvider::class);
    }

    /**
     * Configure Tinker
     * @param Application $app
     */
    public static function configureTinker($app)
    {
        $app->register(TinkerServiceProvider::class);
    }

    /**
     * Configure Mail
     * @param Application $app
     */
    public static function configureMail($app)
    {
        // Config
        $app->configure('mail');

        // Register Service Provider
        $app->register(MailServiceProvider::class);

        // Create Singleton
        $app->singleton('mailer', function ($app) {
            /** @var Application $app */
            $app->configure('services');
            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
    }

    /**
     * Configure Backup
     * @param Application $app
     */
    public static function configureBackup($app)
    {

        // Configure
        $app->configure('backup');

        // Register Service Provider
        $app->register(BackupServiceProvider::class);
        $app->register(NotificationServiceProvider::class);
    }

    /**
     * Configure File System
     * @param Application $app
     */
    public static function configureFileSystems($app)
    {
        // Configure
        $app->configure('filesystems');

        // Create Alias
        class_exists('Storage') or class_alias(Storage::class, 'Storage');

        // Create Singleton
        $app->singleton(
            Factory::class,
            function ($app) {
                return new FilesystemManager($app);
            }
        );
        $app->bind(Factory::class, function ($app) {
            return $app['filesystem'];
        });

        // Register Service Provider
        $app->register(FilesystemServiceProvider::class);
    }

    /**
     * Configure Lumen Routes List
     * @param Application $app
     */
    public static function configureLumenRoutesList($app)
    {
        $app->register(RoutesCommandServiceProvider::class);
    }
}
<?php

namespace VIITech\Helpers;

class ApplicationHelper
{

    /**
     * Configure Sentry
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureSentry($app)
    {
        $app->register(\Sentry\SentryLaravel\SentryLumenServiceProvider::class);
    }

    /**
     * Configure Tinker
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureTinker($app)
    {
        $app->register(\Laravel\Tinker\TinkerServiceProvider::class);
    }

    /**
     * Configure Mail
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureMail($app)
    {
        // Config
        $app->configure('mail');

        // Register Service Provider
        $app->register(\Illuminate\Mail\MailServiceProvider::class);

        // Create Singleton
        $app->singleton('mailer', function ($app) {
            /** @var Laravel\Lumen\Application $app */
            $app->configure('services');
            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
    }

    /**
     * Configure Backup
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureBackup($app)
    {

        // Configure
        $app->configure('backup');

        // Register Service Provider
        $app->register(\Spatie\Backup\BackupServiceProvider::class);
        $app->register(\Illuminate\Notifications\NotificationServiceProvider::class);
    }

    /**
     * Configure File System
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureFileSystems($app)
    {
        // Configure
        $app->configure('filesystems');

        // Create Alias
        class_exists('Storage') or class_alias(\Illuminate\Support\Facades\Storage::class, 'Storage');

        // Create Singleton
        $app->singleton(
            \Illuminate\Contracts\Filesystem\Factory::class,
            function ($app) {
                return new \Illuminate\Filesystem\FilesystemManager($app);
            }
        );
        $app->bind(\Illuminate\Contracts\Filesystem\Factory::class, function ($app) {
            return $app['filesystem'];
        });

        // Register Service Provider
        $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }

    /**
     * Configure Lumen Routes List
     * @param \Laravel\Lumen\Application $app
     */
    public static function configureLumenRoutesList($app)
    {
        $app->register(\Appzcoder\LumenRoutesList\RoutesCommandServiceProvider::class);
    }
}
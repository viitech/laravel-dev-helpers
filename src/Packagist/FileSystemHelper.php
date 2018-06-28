<?php

namespace VIITech\Helpers\Packagist;

class FileSystemHelper
{

    /**
     * Configure
     * @param \Laravel\Lumen\Application $app
     */
    public static function configure($app)
    {
        $app->configure('filesystems');
    }

    /**
     * Create Class Alias
     */
    public static function createClassAlias()
    {
        class_exists('Storage') or class_alias(\Illuminate\Support\Facades\Storage::class, 'Storage');
    }

    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerContainerBinding($app)
    {
        $app->singleton(
            \Illuminate\Contracts\Filesystem\Factory::class,
            function ($app) {
                return new \Illuminate\Filesystem\FilesystemManager($app);
            }
        );
        $app->bind(\Illuminate\Contracts\Filesystem\Factory::class, function ($app) {
            return $app['filesystem'];
        });
    }

    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }
}
<?php

namespace VIITech\Helpers\Packagist;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Application;

class FileSystemHelper
{

    /**
     * Configure
     * @param Application $app
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
        class_exists('Storage') or class_alias(Storage::class, 'Storage');
    }

    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerContainerBinding($app)
    {
        $app->singleton(
            Factory::class,
            function ($app) {
                return new FilesystemManager($app);
            }
        );
        $app->bind(Factory::class, function ($app) {
            return $app['filesystem'];
        });
    }

    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(FilesystemServiceProvider::class);
    }
}
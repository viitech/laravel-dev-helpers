<?php

namespace VIITech\Helpers\Packagist;

use Laravel\Lumen\Application;
use Laravel\Tinker\TinkerServiceProvider;

class TinkerHelper
{
    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(TinkerServiceProvider::class);
    }
}
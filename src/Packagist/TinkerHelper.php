<?php

namespace VIITech\Helpers\Packagist;

class TinkerHelper
{
    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(\Laravel\Tinker\TinkerServiceProvider::class);
    }
}
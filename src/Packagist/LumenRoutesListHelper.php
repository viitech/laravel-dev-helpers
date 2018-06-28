<?php

namespace VIITech\Helpers\Packagist;

class LumenRoutesListHelper
{

    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(\Appzcoder\LumenRoutesList\RoutesCommandServiceProvider::class);
    }
}
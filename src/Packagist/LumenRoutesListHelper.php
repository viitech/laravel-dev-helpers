<?php

namespace VIITech\Helpers\Packagist;

use Appzcoder\LumenRoutesList\RoutesCommandServiceProvider;
use Laravel\Lumen\Application;

class LumenRoutesListHelper
{

    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(RoutesCommandServiceProvider::class);
    }
}
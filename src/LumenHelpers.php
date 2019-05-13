<?php

namespace VIITech\Helpers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use Laravel\Lumen\Application;

class LumenHelpers
{

    /**
     * Initialize ResponseFactory
     * @param Application $app
     * @usage Add in AppServiceProvider::register()
     */
    public static function initResponseFactory($app)
    {
        $app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function ($app) {
            return new ResponseFactory(
                $app[Factory::class],
                $app[Redirector::class]
            );
        });
    }
}
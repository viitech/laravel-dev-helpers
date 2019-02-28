<?php

namespace VIITech\Helpers;

class LumenHelpers
{

    /**
     * Initialize ResponseFactory
     * @param \Laravel\Lumen\Application $app
     * @usage Add in AppServiceProvider::register()
     */
    public static function initResponseFactory($app)
    {
        $app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function ($app) {
            return new \Illuminate\Routing\ResponseFactory(
                $app[\Illuminate\Contracts\View\Factory::class],
                $app[\Illuminate\Routing\Redirector::class]
            );
        });
    }
}
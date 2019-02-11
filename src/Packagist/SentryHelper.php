<?php

namespace VIITech\Helpers\Packagist;


use Illuminate\Foundation\Exceptions\Handler;
use VIITech\Helpers\Constants\EnvVariables;

class SentryHelper
{

    /**
     * Register Service Provider
     * @param \Laravel\Lumen\Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(\Sentry\SentryLaravel\SentryLumenServiceProvider::class);
    }

    /**
     * Capture Exception
     * @param Handler|\Laravel\Lumen\Exceptions\Handler $handler
     * @param $e
     */
    public static function capture($handler, $e)
    {
        if (app()->bound('sentry') && $handler->shouldReport($e) && env(EnvVariables::SENTRY_ENABLED, true)) {
            app('sentry')->captureException($e);
        }
    }
}
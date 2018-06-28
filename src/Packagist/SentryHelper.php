<?php

namespace VIITech\Helpers\Packagist;

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
     * @param \App\Exceptions\Handler $handler
     * @param $e
     */
    public static function capture($handler, $e)
    {
        if (app()->bound('sentry') && $handler->shouldReport($e) && env("SENTRY_ENABLED", false)) {
            app('sentry')->captureException($e);
        }
    }
}
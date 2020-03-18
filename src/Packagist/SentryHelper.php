<?php

namespace VIITech\Helpers\Packagist;

use Illuminate\Foundation\Exceptions\Handler;
use Laravel\Lumen\Application;
use Sentry\Laravel\ServiceProvider;
use VIITech\Helpers\Constants\EnvVariables;

/**
 * Class SentryHelper
 * @package VIITech\Helpers\Packagist
 */
class SentryHelper
{

    /**
     * Register Service Provider
     * @param Application $app
     */
    public static function registerServiceProvider($app)
    {
        $app->register(ServiceProvider::class);
    }

    /**
     * Capture Exception
     * @param Handler|\Laravel\Lumen\Exceptions\Handler $handler
     * @param $e
     */
    public static function capture($handler, $e)
    {
        try {
            if (app()->bound('sentry') && $handler->shouldReport($e) && env(EnvVariables::SENTRY_ENABLED, true)) {
                app('sentry')->captureException($e);
            }
        } catch (\Exception $e) {}
    }
}
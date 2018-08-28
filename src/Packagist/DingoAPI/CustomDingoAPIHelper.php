<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use VIITech\Helpers\GlobalHelpers;

class CustomDingoAPIHelper
{
    /**
     * Custom Exception Handler
     * @param string $exception_handler
     */
    public static function exceptionHandler($exception_handler)
    {
        try {
            app(\Dingo\Api\Exception\Handler::class)->register(function (\Exception $exception) use ($exception_handler) {
                if (class_exists(\MongoDB\Driver\Exception\Exception::class) &&
                    ($exception instanceof \MongoDB\Driver\Exception\AuthenticationException || $exception instanceof \MongoDB\Driver\Exception\ConnectionException)) {
                    return GlobalHelpers::returnResponse(false, "Unavailable Service!", [], [], GlobalHelpers::HTTP_STATUS_503_SERVICE_UNAVAILABLE);
                }
                return app($exception_handler)->report($exception);
            });
        } catch (\Exception $e) {}
    }
}
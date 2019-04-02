<?php

namespace VIITech\Helpers\Packagist\DingoAPI;

use Dingo\Api\Exception\Handler;
use Dingo\Api\Http\Request;
use Illuminate\Http\Response;
use VIITech\Helpers\GlobalHelpers;

class DingoAPIHelpers
{
    /**
     * Custom Exception Handler
     * @param string $exception_handler
     */
    public static function exceptionHandler($exception_handler)
    {
        try {
            app(Handler::class)->register(function (\Exception $exception) use ($exception_handler) {
                if (class_exists(\MongoDB\Driver\Exception\Exception::class) &&
                    ($exception instanceof \MongoDB\Driver\Exception\AuthenticationException || $exception instanceof \MongoDB\Driver\Exception\ConnectionException ||
                        $exception instanceof \MongoDB\Driver\Exception\ConnectionTimeoutException || $exception instanceof \MongoDB\Driver\Exception\ExecutionTimeoutException)) {
                    return GlobalHelpers::returnResponse(false, "Unavailable Service!", [], [], Response::HTTP_SERVICE_UNAVAILABLE);
                }
                return app($exception_handler)->report($exception);
            });
        } catch (\Exception $e) {}
    }

    /**
     * Create Request Object
     * @param array $data
     * @return Request
     */
    static function createRequestObject($data = [])
    {
        $request = new Request();
        $request->replace($data);
        return $request;
    }
}
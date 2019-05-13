<?php

namespace VIITech\Helpers\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VIITech\Helpers\Constants\EnvVariables;
use VIITech\Helpers\GlobalHelpers;

/**
 * Class CorsMiddleware
 * @package VIITech\Helpers\Middleware
 */
class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Intercepts OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            return GlobalHelpers::returnResponse(true, '', [], [], Response::HTTP_OK, [
                'Access-Control-Allow-Origin' => env(EnvVariables::CORS_ALLOW_ORIGIN, '*'),
                'Access-Control-Allow-Headers' => $request->header('Access-Control-Request-Headers'),
                'Access-Control-Allow-Methods' => 'HEAD, GET, POST, PUT, PATCH, DELETE'
            ]);
        }

        try {
            // Pass the request to the next middleware
            $response = $next($request);

            // when downloading file:
            if(class_basename($response) === "BinaryFileResponse"){
                return $response;
            }

            // Adds headers to the response
            $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE')
                ->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'))
                ->header('Access-Control-Allow-Origin', env('CORS_ALLOW_ORIGIN', '*'));
            return $response;
        } catch (Exception $e) {
            return $next($request);
        }
    }
}
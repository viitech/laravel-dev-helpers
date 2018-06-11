<?php

namespace VIITech\Helpers\Middleware;

use Closure;

// this will solve cross origin issue
//source: https://gist.github.com/danharper/06d2386f0b826b669552
class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Intercepts OPTIONS requests
        if($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            // Pass the request to the next middleware
            $response = $next($request);
        }

        // Adds headers to the response
        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->header('Access-Control-Allow-Origin', env('CORS_ALLOW_ORIGIN', '*'));

        // Sends it
        return $response;
    }
}
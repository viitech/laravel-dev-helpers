<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttpsMiddleware
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
        // only attempt to force HTTPS if the package has been configured to do so
        try {
            if (env("FORCE_HTTPS", false)) {
                // check how the absolute URL in the request looks
                $url = strtolower(url($request->server("REQUEST_URI")));
                $https = $request->server("HTTPS");
                if (empty($https) || strtolower($https) == "off") {
                    // take SSL termination behind a proxy into account
                    if (!starts_with($url, 'https:')) {
                        // replace the protocol and then return a redirect
                        return redirect(str_replace("http:", "https:", $url));
                    }
                }
            }
        } catch (\Exception $e) {}

        return $next($request);
    }
}

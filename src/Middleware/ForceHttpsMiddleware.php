<?php

namespace VIITech\Helpers\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VIITech\Helpers\Constants\DebuggerLevels;
use VIITech\Helpers\Constants\EnvVariables;
use VIITech\Helpers\GlobalHelpers;

class ForceHttpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // only attempt to force HTTPS if the package has been configured to do so
        try {
            if (env(EnvVariables::FORCE_HTTPS, false)) {
                // check how the absolute URL in the request looks
                $url = strtolower(url($request->server("REQUEST_URI")));
                $https = $request->server("HTTPS");
                if (empty($https) || strtolower($https) == "off") {
                    // take SSL termination behind a proxy into account
                    if (!Str::startsWith($url, 'https:')) {
                        // replace the protocol and then return a redirect
                        return redirect(str_replace("http:", "https:", $url));
                    }
                }
            }
        } catch (Exception $e) {
            GlobalHelpers::debugger($e, DebuggerLevels::ERROR);
        }

        return $next($request);
    }
}

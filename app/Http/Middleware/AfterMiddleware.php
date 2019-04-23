<?php

namespace App\Http\Middleware;

use Closure;
use Log;

/**
 * Writes response info to file
 *
 * @package App\Http\Middleware
 */
class AfterMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (env('VERBOSITY', false)) {
            Log::info('Response.', [
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'request_data' => $request->all(),
                'content' => $response->getContent()
            ]);
        }

        return $response;
    }

}

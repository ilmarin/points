<?php

namespace App\Http\Middleware;

use Closure;
use Log;

/**
 * Writes request info to file
 *
 * @package App\Http\Middleware
 */
class BeforeMiddleware
{

    public function handle($request, Closure $next)
    {
        if (env('VERBOSITY', false)) {
            Log::info('Incoming request.', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'data' => $request->all()
            ]);
        }

        return $next($request);
    }

}

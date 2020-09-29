<?php

namespace GeniusSystems\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServeCachedResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($response = Cache::get($request->getUri())) {
            return $response->withHeaders([
                'GS-Cache-Status'   => 'HIT',
            ]);
        }

        return $next($request);
    }
}

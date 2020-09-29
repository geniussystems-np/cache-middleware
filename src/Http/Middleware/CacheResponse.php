<?php

namespace GeniusSystems\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
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
        $response = $next($request);

        $ttl = $response->headers->getCacheControlDirective("max-age");
        $response->headers->set("GS-Cached-AT", $response->headers->get("date"));

        Cache::put($request->getUri(), $response, $ttl);

        return $response->withHeaders([
            "GS-Cached-Status"  => "MISS" 
        ]);
    }
}

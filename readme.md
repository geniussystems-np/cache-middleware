# Cache Middleware for Laravel/Lumen

We use two middleware, one for serving the cached response and another for generating the cache.

- ServeCachedResponse
  - The only job of this middleware is serving the response from cache if available
  - This middleware should be below the Authentication in the Middleware stack
- CacheReponse
  - This middleware should analyze the response for it’s cacheability by checking the cache headers
  - If the response is cacheable, it should cache the response in configured cache store(Redis)
  - Currently, We are using only `cache-control` directive

This package uses Laravel `Cache` to store and serve response. Any response having `cache-control` directive with `max-age` will be cached for duration defined in `max-age` parameter.

## Installing the package using composer

```
composer require geniussystems-np/cache-middleware
```

Once the package is installed, enable the Middleware in `app/Http/Kernel.php` and add to your required middleware group.

```php
protected $middleware = [
        \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        .
        .
        \GeniusSystems\Http\Middleware\ServeCachedResponse::class,
        .
        .
        \GeniusSystems\Http\Middleware\CacheResponse::class,
  ];
```

`ServeCachedResponse` should be placed after authentication middleware since it doesn’t pass the request to other middleware in the stack if there’s a response. Above code asumes you’re enabling `cache-middleware` for all the routes. For specific routes, you need to add the middleware to it’s respective group and enable it in the route.

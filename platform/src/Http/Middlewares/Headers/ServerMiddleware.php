<?php
namespace Lumia\Http\Middlewares\Headers;

use Closure;

class ServerMiddleware
{

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $version = app()->version();
        $name = env('APP_NAME', 'Lumen');
        $response->header('Server', sprintf('%s (%s)', $name, $version));
        $response->header('X-Powered-By', sprintf('%s (%s)', $name, $version));
        
        return $response;
    }
}
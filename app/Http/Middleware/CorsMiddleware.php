<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
  public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->header('Access-Control-Allow-Origin', '*')
             ->header('Access-Control-Allow-Methods', '*')
             ->header('Access-Control-Allow-Credentials', 'true')
             ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
             ->header('Accept', 'application/json');

    return $response;
}
}
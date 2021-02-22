<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DefaultResponseHeader
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->header('Content-Type', 'application/json');

        return $response;
    }
}

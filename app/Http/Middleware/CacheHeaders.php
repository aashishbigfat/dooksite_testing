<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cache only successful GET responses
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {

            $response->headers->set(
                'Cache-Control',
                'public, max-age=600, s-maxage=600'
            );

            $response->headers->set('Pragma', 'cache');
        }

        return $response;
    }
}
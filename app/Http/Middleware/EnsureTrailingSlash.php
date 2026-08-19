<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();
        $fullUrl = $request->fullUrl();
        $queryString = $request->getQueryString();

        // Exclude API routes and static assets
        if ($request->is('api/*') || preg_match('/\.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf|eot|mp4|pdf)$/', $path)) {
            return $next($request);
        }

        // Apply the rule ONLY for /blog and /blog/{post_slug}
        if (($path === '/blog' || preg_match('#^/blog/[^/]+$#', $path)) && !str_ends_with($path, '/')) {
            // Preserve query string
            $newUrl = rtrim($fullUrl, '/') . '/';
            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}

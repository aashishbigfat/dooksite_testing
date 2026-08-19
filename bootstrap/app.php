<?php
use App\Http\Middleware\EnsureTrailingSlash;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then:function() {
            Route::middleware(['web'])->namespace('App\Http\Controllers\Frontend')->name('frontend.')->group(__DIR__.'/../routes/web.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(EnsureTrailingSlash::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof HttpException) {
                $status = $e->getStatusCode();

                if (in_array($status, [400, 404, 500])) {
                    return redirect()->route('frontend.index');
                }
            }

            // Handle all unexpected errors too
            return redirect()->route('frontend.index');
        });
    })
    ->create();

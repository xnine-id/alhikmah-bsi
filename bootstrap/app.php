<?php

use App\Http\Middleware\CookieTokenAuthorization;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\OptionalAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->remove([
            ConvertEmptyStringsToNull::class,
            TrimStrings::class,
        ]);
        $middleware->api(
            prepend: [
                ForceJsonResponse::class,
                CookieTokenAuthorization::class,
                OptionalAuth::class,
            ],
            append: [
                'throttle:api',
            ],
            remove: [
                ConvertEmptyStringsToNull::class,
                TrimStrings::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            $endpoints = ['api/bsi/*', 'bsi/*'];

            logger()->info('request path is', [$request->path()]);

            if ($request->is($endpoints)) {
                $isPayment = $request->is('api/bsi/*/payment') || $request->is('bsi/*/payment');
                $isAuth = $request->is('api/bsi/*/auth') || $request->is('bsi/*/auth');
                
                if ($isAuth) {
                    $dbErrorCode = '5000099';
                    $generalErrorCode = '5000000';
                } elseif ($isPayment) {
                    $dbErrorCode = '5002599';
                    $generalErrorCode = '5002500';
                } else {
                    $dbErrorCode = '5002499';
                    $generalErrorCode = '5002400';
                }

                if ($e instanceof \Illuminate\Database\QueryException || $e instanceof \PDOException) {
                    return response()->json([
                        'responseCode' => $dbErrorCode,
                        'responseMessage' => 'DB Error'
                    ], 500);
                }

                $statusCode = 500;
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $statusCode = $e->getStatusCode();
                }

                if ($statusCode === 500) {
                    return response()->json([
                        'responseCode' => $generalErrorCode,
                        'responseMessage' => 'General Error'
                    ], 500);
                }
            }
        });
    })->create();

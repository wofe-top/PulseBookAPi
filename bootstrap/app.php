<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;


use App\Exceptions\BusinessException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

use Illuminate\Database\Eloquent\ModelNotFoundException;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Http\Middleware\LocalizationMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated or token expired.',
                'errors' => null
            ], 401);
        });
        $exceptions->render(function (RouteNotFoundException $e, $request) {
            if ($e->getMessage()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated. Please provide a valid token.',
                    'errors' => null
                ], 401);
            }
        });
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($e->getMessage() === 'Route [login] not defined.') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Model Not Found.',
                    'errors' => null
                ], 401);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {

            if ($request->is('api/*')) {


                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The Items or Route Not Found',
                        'errors' => null
                    ], 404);
                }


                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The given data was invalid.',
                        'errors' => $e->errors()
                    ], 422);
                }


                if (
                    $e instanceof AuthenticationException ||
                    ($e instanceof RouteNotFoundException && $e->getMessage() === 'Route [login] not defined.')
                ) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Unauthenticated. Please provide a valid token.'),
                        'errors' => null
                    ], 401);
                }




                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('The requested resource or route could not be found.'),
                        'errors' => null
                    ], 404);
                }


                if ($e instanceof BusinessException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'errors' => null
                    ], 400);
                }



                return response()->json([
                    'status' => 'error',
                    'message' => config('app.debug') ? $e->getMessage() : 'An internal server error occurred, please try again later.',
                    'errors' => config('app.debug') ? $e->getTrace() : null
                ], 500);
            }
        });
    })->create();

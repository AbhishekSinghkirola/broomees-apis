<?php

use App\Http\Middleware\TokenVerify;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['token.verify' => TokenVerify::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (UnauthorizedHttpException $e,Request $request) {
            Log::error('Unauthorized Exception occurred: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
            ], 401);
        });

        $exceptions->render(function (ConflictHttpException $e,Request $request) {
            Log::error('Conflict Exception occurred: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
            ], 409);
        });


        $exceptions->render(function (NotFoundHttpException $e,Request $request) {
            Log::error('Not Found Exception occurred: ' . $e->getMessage(), ['exception' => $e]);

            if ($request->is('api/users/*/hobbies')) {
                return response()->json([
                    'success' => false,
                    'message' => "Hobby not found",
                ], 404);
            }
            if ($request->is('api/users/*/relationships')) {
                return response()->json([
                    'success' => false,
                    'message' =>  "Relationship not found",
                ], 404);
            } elseif ($request->is('api/users/*')) {
                return response()->json([
                    'success' => false,
                    'message' =>  "User not found",
                ], 404);
            } else {
                return response()->json([
                    'success' => false,
                    'message' =>  "Resource not found",
                ], 404);
            }
        });

        $exceptions->render(function (
            ThrottleRequestsException $e,
            Request $request
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests'
            ], 429);
        });


        $exceptions->render(function (Throwable $e,Request $request) {

            Log::error('Exception occurred: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => "Internal Server Error : " . $e->getMessage(),
            ], 500);
        });
    })->create();

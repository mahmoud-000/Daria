<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Intervention\Image\Exception\NotFoundException;
use Modules\Locale\Http\Middleware\SetLocale;
use Modules\Setup\Http\Middleware\SetupCheckMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetupCheckMiddleware::class);
        $middleware->append(SetLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Entry not found'
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Entry for ' . str_replace('App', '', $e->getModel()) . ' not found',
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        });
    })->create();

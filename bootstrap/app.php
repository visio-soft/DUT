<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Spatie Media Library resim işleme hatalarını yakala
        $exceptions->render(function (\Spatie\Image\Exceptions\CouldNotLoadImage $e, $request) {
            \Illuminate\Support\Facades\Log::error('Media processing error: ' . $e->getMessage(), [
                'url' => $request->url(),
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 'guest',
                'ip' => $request->ip(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resim işlenirken bir hata oluştu. Lütfen farklı bir resim deneyin.',
                    'errors' => [
                        'image' => ['Resim dosyası işlenemedi. Desteklenen formatlardan birini kullanın: JPEG, PNG, GIF, WebP.']
                    ]
                ], 422);
            }
            
            return back()->withErrors([
                'image' => 'Resim dosyası işlenemedi. Lütfen farklı bir resim deneyin.'
            ])->withInput();
        });
    })->create();

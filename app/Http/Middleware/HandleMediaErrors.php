<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Image\Exceptions\CouldNotLoadImage;

class HandleMediaErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (CouldNotLoadImage $e) {
            Log::error('Media processing error: ' . $e->getMessage(), [
                'url' => $request->url(),
                'user_id' => Auth::id() ?? 'guest',
                'ip' => $request->ip(),
                'file_data' => $request->hasFile('image') ? [
                    'name' => $request->file('image')->getClientOriginalName(),
                    'size' => $request->file('image')->getSize(),
                    'mime' => $request->file('image')->getMimeType()
                ] : null
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
        }
    }
}

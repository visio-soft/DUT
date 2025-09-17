<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class HandleMediaErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            Log::error('File size too large: ' . $e->getMessage(), [
                'url' => $request->url(),
                'user_id' => Auth::id() ?? 'guest',
                'ip' => $request->ip(),
                'content_length' => $request->header('Content-Length')
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Yüklediğiniz dosya çok büyük.',
                    'errors' => [
                        'images' => ['Dosya çok büyük. Lütfen daha küçük bir dosya seçin.'],
                        'image' => ['Dosya çok büyük. Lütfen daha küçük bir dosya seçin.']
                    ]
                ], 422);
            }

            return back()->withErrors([
                'image' => 'Dosya boyutu çok büyük. Lütfen daha küçük bir dosya yükleyiniz.'
            ])->withInput();
        } catch (FileIsTooBig $e) {
            Log::error('Spatie Media Library file too big: ' . $e->getMessage(), [
                'url' => $request->url(),
                'user_id' => Auth::id() ?? 'guest',
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Yüklediğiniz dosya çok büyük.',
                    'errors' => [
                        'images' => ['Dosya çok büyük. Lütfen daha küçük bir dosya seçin.'],
                        'image' => ['Dosya çok büyük. Lütfen daha küçük bir dosya seçin.']
                    ]
                ], 422);
            }

            return back()->withErrors([
                'image' => 'Dosya boyutu çok büyük. Lütfen daha küçük bir dosya yükleyiniz.'
            ])->withInput();
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
                        'images' => ['Resim dosyası işlenemedi. Desteklenen formatlar: JPEG, JPG, PNG.'],
                        'image' => ['Resim dosyası işlenemedi. Desteklenen formatlar: JPEG, JPG, PNG.']
                    ]
                ], 422);
            }

            return back()->withErrors([
                'image' => 'Resim dosyası işlenemedi. Lütfen farklı bir resim deneyin.'
            ])->withInput();
        } catch (\Exception $e) {
            // Genel hata yakalama
            if (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'upload') || str_contains($e->getMessage(), 'size')) {
                Log::error('File upload error: ' . $e->getMessage(), [
                    'url' => $request->url(),
                    'user_id' => Auth::id() ?? 'guest',
                    'ip' => $request->ip()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Dosya yükleme hatası.',
                        'errors' => [
                            'images' => ['Dosya yüklenirken bir hata oluştu. Lütfen farklı bir resim deneyin.'],
                            'image' => ['Dosya yüklenirken bir hata oluştu. Lütfen farklı bir resim deneyin.']
                        ]
                    ], 422);
                }

                return back()->withErrors([
                    'image' => 'Dosya yüklenirken bir hata oluştu. Lütfen farklı bir resim deneyin.'
                ])->withInput();
            }

            throw $e; // Diğer hataları normal şekilde fırlat
        }
    }
}

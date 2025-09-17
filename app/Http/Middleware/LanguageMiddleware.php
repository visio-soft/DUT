<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from session, URL parameter, or use default
        $language = $request->get('lang') ?? session('locale') ?? config('app.locale');
        
        // Validate language is supported
        $supportedLocales = ['tr', 'en'];
        if (!in_array($language, $supportedLocales)) {
            $language = config('app.locale');
        }
        
        // Set application locale
        App::setLocale($language);
        
        // Store in session for persistence
        if ($request->has('lang')) {
            Session::put('locale', $language);
        }
        
        return $next($request);
    }
}
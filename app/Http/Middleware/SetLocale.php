<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'es', 'fr'];

        if (Auth::check() && Auth::user()->locale) {
            // 1. Logged-in user's saved database preference
            $locale = Auth::user()->locale;
        } elseif (Session::has('locale')) {
            // 2. Guest or login-page manual selection
            $locale = Session::get('locale');
        } else {
            // 3. Auto-detect from browser
            $locale = $request->getPreferredLanguage($supportedLocales) ?? 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
// to update the different languages:
// composer require kkomelin/laravel-translatable-string-exporter --dev
//php artisan translatable:export es,fr (add any more languages)




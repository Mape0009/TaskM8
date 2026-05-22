<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    private const SUPPORTED_LOCALES = ['da', 'en', 'es', 'fi', 'it', 'uk', 'ru'];

    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'da'));

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.fallback_locale', 'da');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
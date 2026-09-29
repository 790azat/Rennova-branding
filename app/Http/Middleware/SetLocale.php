<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = array_keys(config('rennova.locales'));
        $locale = $request->session()->get('locale');

        if (! in_array($locale, $locales, true)) {
            // Browser language when it is one of ours; otherwise the default (listed first, which Symfony falls back to).
            $default = config('app.locale');
            $locale = $request->getPreferredLanguage(array_unique([$default, ...$locales]));
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

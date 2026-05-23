<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale', 'en');

        // Check if query parameter lang is provided
        if ($request->has('lang') && in_array($request->query('lang'), ['en', 'ar'])) {
            $locale = $request->query('lang');
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
            }
        } elseif ($request->hasSession() && $request->session()->has('locale')) {
            // Check session
            $locale = $request->session()->get('locale');
        } elseif ($request->header('Accept-Language')) {
            // Check Accept-Language header (e.g. from API or browser)
            $header = $request->header('Accept-Language');
            $languages = array_map('trim', explode(',', $header));
            foreach ($languages as $lang) {
                $langCode = substr($lang, 0, 2);
                if (in_array($langCode, ['en', 'ar'])) {
                    $locale = $langCode;
                    break;
                }
            }
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

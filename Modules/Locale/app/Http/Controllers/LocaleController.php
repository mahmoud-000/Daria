<?php

namespace Modules\Locale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LocaleController extends Controller
{
    public function locales()
    {
        return response()->json([
            'locales' => collect(config('locale.locales'))->pluck('code')->toArray(),
            'locale' => app()->getLocale()
        ]);
    }

    public function setLocale(Request $request)
    {
        $locale = config('app.locale');
        if(in_array($request->locale, collect(config('locale.locales'))->pluck('code')->toArray())) {
            $locale = $request->locale;
        }

        session()->put('locale', $locale === 'en-US' ? 'en' : $locale);
       
        app()->setLocale(session()->get('locale'));

        return response()->json([
            'locale' => $locale
        ]);
    }
}

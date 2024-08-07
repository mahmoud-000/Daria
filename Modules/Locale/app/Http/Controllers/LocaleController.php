<?php

namespace Modules\Locale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Models\Setting;

class LocaleController extends Controller
{
    public function locales()
    {
        return response()->json([
            'locales' => collect(config('locale.locales'))->pluck('code')->toArray(),
            'locale' => usersettings('locale') ?? app()->getLocale()
        ]);
    }

    public function setLocale(Request $request)
    {
        $locale = config('app.locale');
        if (in_array($request->locale, collect(config('locale.locales'))->pluck('code')->toArray())) {
            $locale = $request->locale;
        }

        $localeRenamed = $locale === 'en-US' ? 'en' : $locale;

        session()->put('locale', $localeRenamed);

        app()->setLocale(session()->get('locale'));

        if (auth()->check()) {
            Setting::updateOrCreate(
                ['key' => 'locale', 'user_id' => auth()->id()],
                ['value' => $localeRenamed, 'user_id' => auth()->id()]
            );
        }

        return response()->json([
            'locale' => $locale
        ]);
    }
}

<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Modules\Setting\Models\Setting;

class ShowCompletePage
{
  public function __invoke(): View
  {
    Setting::create(['key' => 'system_setup_completed', 'value' => true]);
    // Cache::forget('systemsettings');
    return view('setup::setup.complete', ['title' => trans('setup::setup.complete')]);
  }
}

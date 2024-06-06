<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Setting\Models\Setting;

function setupCompleted()
{
    try {
        if (!!Cache::get('systemsettings')) {
            Cache::forget('systemsettings');
            $settings = Cache::rememberForever('systemsettings', function () {
                return Setting::with('media')->systemOnly()->get()->pluck('value', 'key');
            });

            return isset($settings['system_setup_completed']) && $settings['system_setup_completed'] === '1' ? true : false;
        }
        return systemsettings('system_setup_completed') === '1' ? true : false;
    } catch (PDOException $e) {
        Log::error($e->getMessage());
        return false;
    }
}

/**
 * Shorthand for the current user settings
 *
 * @param string $key
 * @return mixed
 */
function usersettings($key = '')
{
    if (!auth()->user()) {
        return null;
    }

    if (!empty($key)) {
        return auth()->user()->settings()->get($key);
    }

    // return auth()->user()->settings();
}

/**
 * Retrieve system settings
 *
 * @param string $key
 * @return null|Collection|string
 */
function systemsettings($key = '')
{
    $settings = Cache::rememberForever('systemsettings', function () {
        return Setting::with('media')->systemOnly()->get()->pluck('value', 'key');
    });

    if ($key === '') {
        return $settings;
    }

    return $settings[$key] ?? null;
}

/**
 * Output a correctly formatted date with the correct timezone
 *
 * @param Carbon $date
 * @param bool   $use_relational
 * @return string
 */
function formatDateTime(Carbon $date, bool $use_relational = false): string
{
    $timezone = config('app.timezone');

    if ($use_relational) {
        return $date->setTimezone($timezone)->diffForHumans();
    }

    $format = config('linkace.default.date_format');
    $format .= ' ' . config('linkace.default.time_format');

    $user_date_format = usersettings('date_format');
    $user_time_format = usersettings('time_format');

    if ($user_date_format && $user_time_format) {
        $format = $user_date_format . ' ' . $user_time_format;
    }

    return $date->setTimezone($timezone)->format($format);
}

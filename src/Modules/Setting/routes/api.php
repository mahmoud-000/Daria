<?php
use Modules\Setting\Http\Controllers\SystemSettingList;
use Modules\Setting\Http\Controllers\UpdateSystemSetting;
use Modules\Setting\Http\Controllers\UserSettingList;
use Modules\Setting\Http\Controllers\UpdateUserSetting;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('settings/system', SystemSettingList::class)->name('settings.system');
    Route::get('settings/user/{user?}', UserSettingList::class)->name('settings.user');
    Route::post('settings/system/{setting?}', UpdateSystemSetting::class)->name('settings.system_update');
    Route::post('settings/user/{setting?}', UpdateUserSetting::class)->name('settings.user_update');
});

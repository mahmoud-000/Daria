<?php

use Modules\Locale\Http\Controllers\LocaleController;

Route::prefix('v1')->group(function () {
    // Locale Controller
    Route::get('/locales', [LocaleController::class, 'locales']);
    Route::post('/locales', [LocaleController::class, 'setLocale']);

});
<?php

use Modules\Locale\Http\Controllers\LocaleController;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Locale Controller
    Route::get('/locales', [LocaleController::class, 'locales'])->name('locale.getLocale');
    Route::post('/locales', [LocaleController::class, 'setLocale'])->name('locale.setLocale');

});
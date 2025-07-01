<?php

use Modules\Delegate\Http\Controllers\DelegateBulkDestroy;
use Modules\Delegate\Http\Controllers\DelegateDestroy;
use Modules\Delegate\Http\Controllers\DelegateImportCsv;
use Modules\Delegate\Http\Controllers\DelegateRegister;
use Modules\Delegate\Http\Controllers\DelegateShow;
use Modules\Delegate\Http\Controllers\DelegatesList;
use Modules\Delegate\Http\Controllers\DelegateStore;
use Modules\Delegate\Http\Controllers\DelegateUpdate;
use Modules\Delegate\Http\Controllers\DelegateOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/delegates/options', DelegateOptions::class)->name('delegates.options');
    Route::get('/delegates', DelegatesList::class)->name('delegates.index');
    Route::post('/delegates', DelegateStore::class)->name('delegates.store');
    Route::put('/delegates/{delegate}', DelegateUpdate::class)->name('delegates.update');
    Route::get('/delegates/{delegate}', DelegateShow::class)->name('delegates.show');
    Route::delete('/delegates/{delegate}', DelegateDestroy::class)->name('delegates.destroy');
    Route::post('/delegates/bulk_destroy', DelegateBulkDestroy::class)->name('delegates.bulk_destroy');
    Route::post('/delegates/import_csv', DelegateImportCsv::class)->name('delegates.import_csv');
});

// Route::middleware('guest')->prefix('v1')->group(function () {
//     Route::post('/delegates/register', DelegateRegister::class)->name('delegates.register');
// });

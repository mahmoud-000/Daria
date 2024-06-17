<?php

use Modules\ِAccountType\Http\Controllers\ِAccountTypeBulkDestroy;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeDestroy;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeImportCsv;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeOptions;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeShow;
use Modules\ِAccountType\Http\Controllers\ِAccountTypesList;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeStore;
use Modules\ِAccountType\Http\Controllers\ِAccountTypeUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/ِaccountTypes/options', ِAccountTypeOptions::class)->name('ِaccountTypes.options');
    Route::get('/ِaccountTypes', ِAccountTypesList::class)->name('ِaccountTypes.index');
    Route::post('/ِaccountTypes', ِAccountTypeStore::class)->name('ِaccountTypes.store');
    Route::put('/ِaccountTypes/{ِaccountType}', ِAccountTypeUpdate::class)->name('ِaccountTypes.update');
    Route::get('/ِaccountTypes/{ِaccountType}', ِAccountTypeShow::class)->name('ِaccountTypes.show');
    Route::delete('/ِaccountTypes/{ِaccountType}', ِAccountTypeDestroy::class)->name('ِaccountTypes.destroy');
    Route::post('/ِaccountTypes/bulk_destroy', ِAccountTypeBulkDestroy::class)->name('ِaccountTypes.bulk_destroy');
    Route::post('/ِaccountTypes/import_csv', ِAccountTypeImportCsv::class)->name('ِaccountTypes.import_csv');
});

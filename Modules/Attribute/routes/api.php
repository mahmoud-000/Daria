<?php

use Modules\Attribute\Http\Controllers\AttributeBulkDestroy;
use Modules\Attribute\Http\Controllers\AttributeDestroy;
use Modules\Attribute\Http\Controllers\AttributeImportCsv;
use Modules\Attribute\Http\Controllers\AttributeOptions;
use Modules\Attribute\Http\Controllers\AttributeShow;
use Modules\Attribute\Http\Controllers\AttributesList;
use Modules\Attribute\Http\Controllers\AttributeStore;
use Modules\Attribute\Http\Controllers\AttributeUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/attributes/options', AttributeOptions::class)->name('attributes.options');
    Route::get('/attributes', AttributesList::class)->name('attributes.index');
    Route::post('/attributes', AttributeStore::class)->name('attributes.store');
    Route::put('/attributes/{attribute}', AttributeUpdate::class)->name('attributes.update');
    Route::get('/attributes/{attribute}', AttributeShow::class)->name('attributes.show');
    Route::delete('/attributes/{attribute}', AttributeDestroy::class)->name('attributes.destroy');
    Route::post('/attributes/bulk_destroy', AttributeBulkDestroy::class)->name('attributes.bulk_destroy');
    Route::post('/attributes/import_csv', AttributeImportCsv::class)->name('attributes.import_csv');
});

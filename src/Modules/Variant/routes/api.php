<?php

use Modules\Variant\Http\Controllers\VariantBulkDestroy;
use Modules\Variant\Http\Controllers\VariantDestroy;
use Modules\Variant\Http\Controllers\VariantImportCsv;
use Modules\Variant\Http\Controllers\VariantShow;
use Modules\Variant\Http\Controllers\VariantsList;
use Modules\Variant\Http\Controllers\VariantStore;
use Modules\Variant\Http\Controllers\VariantUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/variants', VariantsList::class)->name('variants.index');
    Route::post('/variants', VariantStore::class)->name('variants.store');
    Route::put('/variants/{variant?}', VariantUpdate::class)->name('variants.update');
    Route::get('/variants/{variant?}', VariantShow::class)->name('variants.show');
    Route::delete('/variants/{variant?}', VariantDestroy::class)->name('variants.destroy');
    Route::post('/variants/bulk_destroy', VariantBulkDestroy::class)->name('variants.bulk_destroy');
    Route::post('/variants/import_csv', VariantImportCsv::class)->name('variants.import_csv');
});

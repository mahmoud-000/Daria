<?php

use Modules\Brand\Http\Controllers\BrandBulkDestroy;
use Modules\Brand\Http\Controllers\BrandDestroy;
use Modules\Brand\Http\Controllers\BrandImportCsv;
use Modules\Brand\Http\Controllers\BrandOptions;
use Modules\Brand\Http\Controllers\BrandShow;
use Modules\Brand\Http\Controllers\BrandsList;
use Modules\Brand\Http\Controllers\BrandStore;
use Modules\Brand\Http\Controllers\BrandUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/brands/options', BrandOptions::class)->name('brands.options');
    Route::get('/brands', BrandsList::class)->name('brands.index');
    Route::post('/brands', BrandStore::class)->name('brands.store');
    Route::put('/brands/{brand}', BrandUpdate::class)->name('brands.update');
    Route::get('/brands/{brand}', BrandShow::class)->name('brands.show');
    Route::delete('/brands/{brand}', BrandDestroy::class)->name('brands.destroy');
    Route::post('/brands/bulk_destroy', BrandBulkDestroy::class)->name('brands.bulk_destroy');
    Route::post('/brands/import_csv', BrandImportCsv::class)->name('brands.import_csv');
});

<?php

use Modules\Sale\Http\Controllers\SaleBulkDestroy;
use Modules\Sale\Http\Controllers\SaleDestroy;
use Modules\Sale\Http\Controllers\SaleImportCsv;
use Modules\Sale\Http\Controllers\SaleShow;
use Modules\Sale\Http\Controllers\SalesList;
use Modules\Sale\Http\Controllers\SaleStore;
use Modules\Sale\Http\Controllers\SaleUpdate;
use Modules\Sale\Http\Controllers\SaleFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/sales/form_options', SaleFormOptions::class)->name('sales.form_options');
    Route::get('/sales', SalesList::class)->name('sales.index');
    Route::post('/sales', SaleStore::class)->name('sales.store');
    Route::put('/sales/{sale}', SaleUpdate::class)->name('sales.update');
    Route::get('/sales/{sale}', SaleShow::class)->name('sales.show');
    Route::delete('/sales/{sale}', SaleDestroy::class)->name('sales.destroy');
    Route::post('/sales/bulk_destroy', SaleBulkDestroy::class)->name('sales.bulk_destroy');
    Route::post('/sales/import_csv', SaleImportCsv::class)->name('sales.import_csv');
});

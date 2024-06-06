<?php

use Modules\Stock\Http\Controllers\StockBulkDestroy;
use Modules\Stock\Http\Controllers\StockByWarehouse;
use Modules\Stock\Http\Controllers\StockDestroy;
use Modules\Stock\Http\Controllers\StockImportCsv;
use Modules\Stock\Http\Controllers\StockShow;
use Modules\Stock\Http\Controllers\StocksList;
use Modules\Stock\Http\Controllers\StockStore;
use Modules\Stock\Http\Controllers\StockUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/stock/by_warehouse', StockByWarehouse::class)->name('stock.by_warehouse');
    Route::get('/stock', StocksList::class)->name('stock.index');
    Route::post('/stock', StockStore::class)->name('stock.store');
    Route::put('/stock/{stock?}', StockUpdate::class)->name('stock.update');
    Route::get('/stock/{stock?}', StockShow::class)->name('stock.show');
    Route::delete('/stock/{stock?}', StockDestroy::class)->name('stock.destroy');
    Route::post('/stock/bulk_destroy', StockBulkDestroy::class)->name('stock.bulk_destroy');
    Route::post('/stock/import_csv', StockImportCsv::class)->name('stock.import_csv');
});

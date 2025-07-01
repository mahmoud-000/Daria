<?php

use Modules\SaleReturn\Http\Controllers\SaleReturnBulkDestroy;
use Modules\SaleReturn\Http\Controllers\SaleReturnDestroy;
use Modules\SaleReturn\Http\Controllers\SaleReturnImportCsv;
use Modules\SaleReturn\Http\Controllers\SaleReturnShow;
use Modules\SaleReturn\Http\Controllers\SaleReturnsList;
use Modules\SaleReturn\Http\Controllers\SaleReturnStore;
use Modules\SaleReturn\Http\Controllers\SaleReturnUpdate;
use Modules\SaleReturn\Http\Controllers\SaleReturnFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/saleReturns/form_options', SaleReturnFormOptions::class)->name('saleReturns.form_options');
    Route::get('/saleReturns', SaleReturnsList::class)->name('saleReturns.index');
    Route::post('/saleReturns', SaleReturnStore::class)->name('saleReturns.store');
    Route::put('/saleReturns/{saleReturn}', SaleReturnUpdate::class)->name('saleReturns.update');
    Route::get('/saleReturns/{saleReturn}', SaleReturnShow::class)->name('saleReturns.show');
    Route::delete('/saleReturns/{saleReturn}', SaleReturnDestroy::class)->name('saleReturns.destroy');
    Route::post('/saleReturns/bulk_destroy', SaleReturnBulkDestroy::class)->name('saleReturns.bulk_destroy');
    Route::post('/saleReturns/import_csv', SaleReturnImportCsv::class)->name('saleReturns.import_csv');
});

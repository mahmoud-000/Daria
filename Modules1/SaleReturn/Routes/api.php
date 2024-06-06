<?php

use Modules\SaleReturn\Http\Controllers\SaleReturnBulkDestroy;
use Modules\SaleReturn\Http\Controllers\SaleReturnDestroy;
use Modules\SaleReturn\Http\Controllers\SaleReturnImportCsv;
use Modules\SaleReturn\Http\Controllers\SaleReturnShow;
use Modules\SaleReturn\Http\Controllers\SaleReturnsList;
use Modules\SaleReturn\Http\Controllers\SaleReturnStore;
use Modules\SaleReturn\Http\Controllers\SaleReturnUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/sale_returns', SaleReturnsList::class)->name('sale_returns.index');
    Route::post('/sale_returns', SaleReturnStore::class)->name('sale_returns.store');
    Route::put('/sale_returns/{sale_return}', SaleReturnUpdate::class)->name('sale_returns.update');
    Route::get('/sale_returns/{sale_return}', SaleReturnShow::class)->name('sale_returns.show');
    Route::delete('/sale_returns/{sale_return}', SaleReturnDestroy::class)->name('sale_returns.destroy');
    Route::post('/sale_returns/bulk_destroy', SaleReturnBulkDestroy::class)->name('sale_returns.bulk_destroy');
    Route::post('/sale_returns/import_csv', SaleReturnImportCsv::class)->name('sale_returns.import_csv');
});

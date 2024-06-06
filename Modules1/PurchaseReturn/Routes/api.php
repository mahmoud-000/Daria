<?php

use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnBulkDestroy;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnDestroy;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnImportCsv;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnShow;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnsList;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnStore;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/purchase_returns', PurchaseReturnsList::class)->name('purchase_returns.index');
    Route::post('/purchase_returns', PurchaseReturnStore::class)->name('purchase_returns.store');
    Route::put('/purchase_returns/{purchase_return}', PurchaseReturnUpdate::class)->name('purchase_returns.update');
    Route::get('/purchase_returns/{purchase_return}', PurchaseReturnShow::class)->name('purchase_returns.show');
    Route::delete('/purchase_returns/{purchase_return}', PurchaseReturnDestroy::class)->name('purchase_returns.destroy');
    Route::post('/purchase_returns/bulk_destroy', PurchaseReturnBulkDestroy::class)->name('purchase_returns.bulk_destroy');
    Route::post('/purchase_returns/import_csv', PurchaseReturnImportCsv::class)->name('purchase_returns.import_csv');
});

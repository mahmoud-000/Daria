<?php

use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnBulkDestroy;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnDestroy;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnImportCsv;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnShow;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnsList;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnStore;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnUpdate;
use Modules\PurchaseReturn\Http\Controllers\PurchaseReturnFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/purchaseReturns/form_options', PurchaseReturnFormOptions::class)->name('purchaseReturns.form_options');
    Route::get('/purchaseReturns', PurchaseReturnsList::class)->name('purchaseReturns.index');
    Route::post('/purchaseReturns', PurchaseReturnStore::class)->name('purchaseReturns.store');
    Route::put('/purchaseReturns/{purchaseReturn}', PurchaseReturnUpdate::class)->name('purchaseReturns.update');
    Route::get('/purchaseReturns/{purchaseReturn}', PurchaseReturnShow::class)->name('purchaseReturns.show');
    Route::delete('/purchaseReturns/{purchaseReturn}', PurchaseReturnDestroy::class)->name('purchaseReturns.destroy');
    Route::post('/purchaseReturns/bulk_destroy', PurchaseReturnBulkDestroy::class)->name('purchaseReturns.bulk_destroy');
    Route::post('/purchaseReturns/import_csv', PurchaseReturnImportCsv::class)->name('purchaseReturns.import_csv');
});

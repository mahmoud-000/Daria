<?php

use Modules\Transfer\Http\Controllers\TransferBulkDestroy;
use Modules\Transfer\Http\Controllers\TransferDestroy;
use Modules\Transfer\Http\Controllers\TransferImportCsv;
use Modules\Transfer\Http\Controllers\TransferShow;
use Modules\Transfer\Http\Controllers\TransfersList;
use Modules\Transfer\Http\Controllers\TransferStore;
use Modules\Transfer\Http\Controllers\TransferUpdate;
use Modules\Transfer\Http\Controllers\TransferFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/transfers/form_options', TransferFormOptions::class)->name('transfers.form_options');
    Route::get('/transfers', TransfersList::class)->name('transfers.index');
    Route::post('/transfers', TransferStore::class)->name('transfers.store');
    Route::put('/transfers/{transfer}', TransferUpdate::class)->name('transfers.update');
    Route::get('/transfers/{transfer}', TransferShow::class)->name('transfers.show');
    Route::delete('/transfers/{transfer}', TransferDestroy::class)->name('transfers.destroy');
    Route::post('/transfers/bulk_destroy', TransferBulkDestroy::class)->name('transfers.bulk_destroy');
    Route::post('/transfers/import_csv', TransferImportCsv::class)->name('transfers.import_csv');
});

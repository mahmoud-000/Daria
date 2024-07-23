<?php

use Modules\Adjustment\Http\Controllers\AdjustmentBulkDestroy;
use Modules\Adjustment\Http\Controllers\AdjustmentDestroy;
use Modules\Adjustment\Http\Controllers\AdjustmentImportCsv;
use Modules\Adjustment\Http\Controllers\AdjustmentShow;
use Modules\Adjustment\Http\Controllers\AdjustmentsList;
use Modules\Adjustment\Http\Controllers\AdjustmentStore;
use Modules\Adjustment\Http\Controllers\AdjustmentUpdate;
use Modules\Adjustment\Http\Controllers\AdjustmentFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/adjustments/form_options', AdjustmentFormOptions::class)->name('adjustments.form_options');
    Route::get('/adjustments', AdjustmentsList::class)->name('adjustments.index');
    Route::post('/adjustments', AdjustmentStore::class)->name('adjustments.store');
    Route::put('/adjustments/{adjustment}', AdjustmentUpdate::class)->name('adjustments.update');
    Route::get('/adjustments/{adjustment}', AdjustmentShow::class)->name('adjustments.show');
    Route::delete('/adjustments/{adjustment}', AdjustmentDestroy::class)->name('adjustments.destroy');
    Route::post('/adjustments/bulk_destroy', AdjustmentBulkDestroy::class)->name('adjustments.bulk_destroy');
    Route::post('/adjustments/import_csv', AdjustmentImportCsv::class)->name('adjustments.import_csv');
});

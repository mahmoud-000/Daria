<?php

use Modules\Stage\Http\Controllers\StageBulkDestroy;
use Modules\Stage\Http\Controllers\StageDestroy;
use Modules\Stage\Http\Controllers\StageImportCsv;
use Modules\Stage\Http\Controllers\StageOptions;
use Modules\Stage\Http\Controllers\StageShow;
use Modules\Stage\Http\Controllers\StagesList;
use Modules\Stage\Http\Controllers\StageStore;
use Modules\Stage\Http\Controllers\StageUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Route::get('/stages/options', StageOptions::class)->name('stages.options');
    Route::get('/stages', StagesList::class)->name('stages.index');
    Route::post('/stages', StageStore::class)->name('stages.store');
    Route::put('/stages/{stage}', StageUpdate::class)->name('stages.update');
    Route::get('/stages/{stage}', StageShow::class)->name('stages.show');
    Route::delete('/stages/{stage}', StageDestroy::class)->name('stages.destroy');
    Route::post('/stages/bulk_destroy', StageBulkDestroy::class)->name('stages.bulk_destroy');
    Route::post('/stages/import_csv', StageImportCsv::class)->name('stages.import_csv');
});

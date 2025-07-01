<?php

use Modules\Pipeline\Http\Controllers\PipelineBulkDestroy;
use Modules\Pipeline\Http\Controllers\PipelineDestroy;
use Modules\Pipeline\Http\Controllers\PipelineImportCsv;
use Modules\Pipeline\Http\Controllers\PipelineOptions;
use Modules\Pipeline\Http\Controllers\PipelineShow;
use Modules\Pipeline\Http\Controllers\PipelinesList;
use Modules\Pipeline\Http\Controllers\PipelineStore;
use Modules\Pipeline\Http\Controllers\PipelineUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/pipelines/options', PipelineOptions::class)->name('pipelines.options');
    Route::get('/pipelines', PipelinesList::class)->name('pipelines.index');
    Route::post('/pipelines', PipelineStore::class)->name('pipelines.store');
    Route::put('/pipelines/{pipeline}', PipelineUpdate::class)->name('pipelines.update');
    Route::get('/pipelines/{pipeline}', PipelineShow::class)->name('pipelines.show');
    Route::delete('/pipelines/{pipeline}', PipelineDestroy::class)->name('pipelines.destroy');
    Route::post('/pipelines/bulk_destroy', PipelineBulkDestroy::class)->name('pipelines.bulk_destroy');
    Route::post('/pipelines/import_csv', PipelineImportCsv::class)->name('pipelines.import_csv');
});

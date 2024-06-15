<?php

use Modules\Job\Http\Controllers\JobBulkDestroy;
use Modules\Job\Http\Controllers\JobDestroy;
use Modules\Job\Http\Controllers\JobImportCsv;
use Modules\Job\Http\Controllers\JobOptions;
use Modules\Job\Http\Controllers\JobShow;
use Modules\Job\Http\Controllers\JobsList;
use Modules\Job\Http\Controllers\JobStore;
use Modules\Job\Http\Controllers\JobUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/jobs/options', JobOptions::class)->name('jobs.options');
    Route::get('/jobs', JobsList::class)->name('jobs.index');
    Route::post('/jobs', JobStore::class)->name('jobs.store');
    Route::put('/jobs/{job}', JobUpdate::class)->name('jobs.update');
    Route::get('/jobs/{job}', JobShow::class)->name('jobs.show');
    Route::delete('/jobs/{job}', JobDestroy::class)->name('jobs.destroy');
    Route::post('/jobs/bulk_destroy', JobBulkDestroy::class)->name('jobs.bulk_destroy');
    Route::post('/jobs/import_csv', JobImportCsv::class)->name('jobs.import_csv');
});

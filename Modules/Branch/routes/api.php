<?php

use Modules\Branch\Http\Controllers\BranchBulkDestroy;
use Modules\Branch\Http\Controllers\BranchDestroy;
use Modules\Branch\Http\Controllers\BranchImportCsv;
use Modules\Branch\Http\Controllers\BranchOptions;
use Modules\Branch\Http\Controllers\BranchShow;
use Modules\Branch\Http\Controllers\BranchesList;
use Modules\Branch\Http\Controllers\BranchStore;
use Modules\Branch\Http\Controllers\BranchUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/branches/options', BranchOptions::class)->name('branches.options');
    Route::get('/branches', BranchesList::class)->name('branches.index');
    Route::post('/branches', BranchStore::class)->name('branches.store');
    Route::put('/branches/{branch}', BranchUpdate::class)->name('branches.update');
    Route::get('/branches/{branch}', BranchShow::class)->name('branches.show');
    Route::delete('/branches/{branch}', BranchDestroy::class)->name('branches.destroy');
    Route::post('/branches/bulk_destroy', BranchBulkDestroy::class)->name('branches.bulk_destroy');
    Route::post('/branches/import_csv', BranchImportCsv::class)->name('branches.import_csv');
});

<?php

use Modules\Patch\Http\Controllers\PatchBulkDestroy;
use Modules\Patch\Http\Controllers\PatchByWarehouse;
use Modules\Patch\Http\Controllers\PatchDestroy;
use Modules\Patch\Http\Controllers\PatchImportCsv;
use Modules\Patch\Http\Controllers\PatchShow;
use Modules\Patch\Http\Controllers\PatchsList;
use Modules\Patch\Http\Controllers\PatchStore;
use Modules\Patch\Http\Controllers\PatchUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/patch/by_warehouse', PatchByWarehouse::class)->name('patch.by_warehouse');
    Route::get('/patch', PatchsList::class)->name('patch.index');
    Route::post('/patch', PatchStore::class)->name('patch.store');
    Route::put('/patch/{patch?}', PatchUpdate::class)->name('patch.update');
    Route::get('/patch/{patch?}', PatchShow::class)->name('patch.show');
    Route::delete('/patch/{patch?}', PatchDestroy::class)->name('patch.destroy');
    Route::post('/patch/bulk_destroy', PatchBulkDestroy::class)->name('patch.bulk_destroy');
    Route::post('/patch/import_csv', PatchImportCsv::class)->name('patch.import_csv');
});

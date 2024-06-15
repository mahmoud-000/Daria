<?php

use Modules\Region\Http\Controllers\RegionBulkDestroy;
use Modules\Region\Http\Controllers\RegionDestroy;
use Modules\Region\Http\Controllers\RegionImportCsv;
use Modules\Region\Http\Controllers\RegionOptions;
use Modules\Region\Http\Controllers\RegionShow;
use Modules\Region\Http\Controllers\RegionsList;
use Modules\Region\Http\Controllers\RegionStore;
use Modules\Region\Http\Controllers\RegionUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/regions/options', RegionOptions::class)->name('regions.options');
    Route::get('/regions', RegionsList::class)->name('regions.index');
    Route::post('/regions', RegionStore::class)->name('regions.store');
    Route::put('/regions/{region}', RegionUpdate::class)->name('regions.update');
    Route::get('/regions/{region}', RegionShow::class)->name('regions.show');
    Route::delete('/regions/{region}', RegionDestroy::class)->name('regions.destroy');
    Route::post('/regions/bulk_destroy', RegionBulkDestroy::class)->name('regions.bulk_destroy');
    Route::post('/regions/import_csv', RegionImportCsv::class)->name('regions.import_csv');
});

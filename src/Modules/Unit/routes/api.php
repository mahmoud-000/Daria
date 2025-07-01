<?php

use Modules\Unit\Http\Controllers\UnitBulkDestroy;
use Modules\Unit\Http\Controllers\UnitDestroy;
use Modules\Unit\Http\Controllers\UnitImportCsv;
use Modules\Unit\Http\Controllers\UnitOptions;
use Modules\Unit\Http\Controllers\UnitShow;
use Modules\Unit\Http\Controllers\UnitsList;
use Modules\Unit\Http\Controllers\UnitStore;
use Modules\Unit\Http\Controllers\UnitUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/units/options', UnitOptions::class)->name('units.options');
    Route::get('/units', UnitsList::class)->name('units.index');
    Route::post('/units', UnitStore::class)->name('units.store');
    Route::put('/units/{unit}', UnitUpdate::class)->name('units.update');
    Route::get('/units/{unit}', UnitShow::class)->name('units.show');
    Route::delete('/units/{unit}', UnitDestroy::class)->name('units.destroy');
    Route::post('/units/bulk_destroy', UnitBulkDestroy::class)->name('units.bulk_destroy');
    Route::post('/units/import_csv', UnitImportCsv::class)->name('units.import_csv');
});

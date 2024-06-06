<?php

use Modules\Department\Http\Controllers\DepartmentBulkDestroy;
use Modules\Department\Http\Controllers\DepartmentDestroy;
use Modules\Department\Http\Controllers\DepartmentImportCsv;
use Modules\Department\Http\Controllers\DepartmentShow;
use Modules\Department\Http\Controllers\DepartmentsList;
use Modules\Department\Http\Controllers\DepartmentOptions;
use Modules\Department\Http\Controllers\DepartmentStore;
use Modules\Department\Http\Controllers\DepartmentUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/departments/options', DepartmentOptions::class)->name('departments.options');
    Route::get('/departments', DepartmentsList::class)->name('departments.index');
    Route::post('/departments', DepartmentStore::class)->name('departments.store');
    Route::put('/departments/{department}', DepartmentUpdate::class)->name('departments.update');
    Route::get('/departments/{department}', DepartmentShow::class)->name('departments.show');
    Route::delete('/departments/{department}', DepartmentDestroy::class)->name('departments.destroy');
    Route::post('/departments/bulk_destroy', DepartmentBulkDestroy::class)->name('departments.bulk_destroy');
    Route::post('/departments/import_csv', DepartmentImportCsv::class)->name('departments.import_csv');
});

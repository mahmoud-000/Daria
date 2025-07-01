<?php

use Modules\Role\Http\Controllers\RoleBulkDestroy;
use Modules\Role\Http\Controllers\RoleDestroy;
use Modules\Role\Http\Controllers\RoleImportCsv;
use Modules\Role\Http\Controllers\RoleOptions;
use Modules\Role\Http\Controllers\RoleShow;
use Modules\Role\Http\Controllers\RolesList;
use Modules\Role\Http\Controllers\RoleStore;
use Modules\Role\Http\Controllers\RoleUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/roles/options', RoleOptions::class)->name('roles.options');
    Route::get('/roles', RolesList::class)->name('roles.index');
    Route::post('/roles', RoleStore::class)->name('roles.store');
    Route::put('/roles/{role}', RoleUpdate::class)->name('roles.update');
    Route::get('/roles/{role}', RoleShow::class)->name('roles.show');
    Route::delete('/roles/{role}', RoleDestroy::class)->name('roles.destroy');
    Route::post('/roles/bulk_destroy', RoleBulkDestroy::class)->name('roles.bulk_destroy');
    Route::post('/roles/import_csv', RoleImportCsv::class)->name('roles.import_csv');
});

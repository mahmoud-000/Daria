<?php
use Modules\Permission\Http\Controllers\PermissionOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/permissions/options', PermissionOptions::class)->name('permissions.options');
});
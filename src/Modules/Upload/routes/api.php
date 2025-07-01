<?php

use Modules\Upload\Http\Controllers\FileDestroy;
use Modules\Upload\Http\Controllers\FilesReorder;
use Modules\Upload\Http\Controllers\FileUpload;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/uploads', FileUpload::class)->name('file.upload');
    Route::put('/uploads/reorder', FilesReorder::class)->name('files.reorder');
    Route::delete('/uploads/destroy', FileDestroy::class)->name('file.destroy');
});

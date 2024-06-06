<?php

use Modules\Category\Http\Controllers\CategoryBulkDestroy;
use Modules\Category\Http\Controllers\CategoryDestroy;
use Modules\Category\Http\Controllers\CategoryImportCsv;
use Modules\Category\Http\Controllers\CategoryShow;
use Modules\Category\Http\Controllers\CategoriesList;
use Modules\Category\Http\Controllers\CategoryOptions;
use Modules\Category\Http\Controllers\CategoryStore;
use Modules\Category\Http\Controllers\CategoryUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/categories/options', CategoryOptions::class)->name('categories.options');
    Route::get('/categories', CategoriesList::class)->name('categories.index');
    Route::post('/categories', CategoryStore::class)->name('categories.store');
    Route::put('/categories/{category}', CategoryUpdate::class)->name('categories.update');
    Route::get('/categories/{category}', CategoryShow::class)->name('categories.show');
    Route::delete('/categories/{category}', CategoryDestroy::class)->name('categories.destroy');
    Route::post('/categories/bulk_destroy', CategoryBulkDestroy::class)->name('categories.bulk_destroy');
    Route::post('/categories/import_csv', CategoryImportCsv::class)->name('categories.import_csv');
});

<?php

use Modules\Item\Http\Controllers\ItemBulkDestroy;
use Modules\Item\Http\Controllers\ItemDestroy;
use Modules\Item\Http\Controllers\ItemImportCsv;
use Modules\Item\Http\Controllers\ItemShow;
use Modules\Item\Http\Controllers\ItemsList;
use Modules\Item\Http\Controllers\ItemStore;
use Modules\Item\Http\Controllers\ItemUpdate;
use Modules\Item\Http\Controllers\ItemFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/items/options', ItemFormOptions::class)->name('items.options');
    Route::get('/items', ItemsList::class)->name('items.index');
    Route::post('/items', ItemStore::class)->name('items.store');
    Route::put('/items/{item}', ItemUpdate::class)->name('items.update');
    Route::get('/items/{item}', ItemShow::class)->name('items.show');
    Route::delete('/items/{item}', ItemDestroy::class)->name('items.destroy');
    Route::post('/items/bulk_destroy', ItemBulkDestroy::class)->name('items.bulk_destroy');
    Route::post('/items/import_csv', ItemImportCsv::class)->name('items.import_csv');
});

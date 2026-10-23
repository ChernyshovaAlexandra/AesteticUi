<?php

use Illuminate\Support\Facades\Route;
use Workbench\Http\Controllers\CatalogController;

Route::get('/', [CatalogController::class, 'index'])->name('catalog.home');
Route::get('/components', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/installation', [CatalogController::class, 'installation'])->name('catalog.installation');
Route::get('/components/{component}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/preview/{component}/{scenario}', [CatalogController::class, 'preview'])->name('catalog.preview');

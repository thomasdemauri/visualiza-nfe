<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XMLController;

Route::get('/', [XMLController::class, 'index'])->name('index');
Route::get('/upload', [XMLController::class, 'create'])->name('create_form');
Route::post('/send', [XMLController::class, 'store'])->name('store_files');
<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('admin.')->group(function () {
    // Movie Settings
    Route::resource('categories', CategoryController::class);
    Route::resource('types', TypeController::class);
    Route::resource('/movies', MovieController::class);
});

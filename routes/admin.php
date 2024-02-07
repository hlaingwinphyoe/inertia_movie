<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('admin.')->group(function () {
    // Movie Settings
    Route::resource('categories', CategoryController::class);
    Route::resource('types', TypeController::class);

    Route::controller(MovieController::class)->name('movies.')->group(function () {
        Route::get('/movies', 'index')->name('index');
        Route::get('/movies/create', 'create')->name('create');
        Route::post('/movies', 'store')->name('store');
        Route::get('/movies/{movie}/edit', 'edit')->name('edit');
        Route::post('/movies/{movie}/update', 'update')->name('update');
        Route::patch('/movies/{movie}/change-status', 'changeStatus')->name('change-status');
        Route::delete('/movies/{movie}/destroy', 'destroy')->name('destroy');
    });
});

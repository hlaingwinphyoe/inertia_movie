<?php

use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('admin.')->group(function () {
    // Movie Settings
    Route::resource('/genres', GenreController::class);
    Route::post('/genres/generate', [GenreController::class, 'generateGenreFromMDB'])->name('genres.generate');
    Route::resource('/tags', TagController::class);

    Route::resource('/casts', CastController::class);
    Route::post('/casts/generate', [CastController::class, 'generateCastFromMDB'])->name('casts.generate');

    Route::resource('/movies', MovieController::class);
    Route::post('/movies/generate', [MovieController::class, 'generateMovieFromMDB'])->name('movies.generate');
    
    // Route::controller(MovieController::class)->name('movies.')->group(function () {
    //     Route::get('/movies', 'index')->name('index');
    //     Route::get('/movies/create', 'create')->name('create');
    //     Route::post('/movies', 'store')->name('store');
    //     Route::get('/movies/{movie}/edit', 'edit')->name('edit');
    //     Route::post('/movies/{movie}/update', 'update')->name('update');
    //     Route::patch('/movies/{movie}/change-status', 'changeStatus')->name('change-status');
    //     Route::delete('/movies/{movie}/destroy', 'destroy')->name('destroy');
    // });
});

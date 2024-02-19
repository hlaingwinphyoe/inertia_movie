<?php

use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\CastsAttachToMovieController;
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
    Route::controller(MovieController::class)->name('movies.')->group(function () {
        Route::patch('/movies/{movie}/change-status', 'changeStatus')->name('change-status');
        Route::post('/movies/generate', 'generateMovieFromMDB')->name('generate');
        Route::delete('/movies/medias/{media}/destroy', 'destroyMedia')->name('destroyMedia');
    });

    Route::controller(CastsAttachToMovieController::class)->name('movies.')->group(function () {
        Route::post('/movies/{movie}/attach-casts', 'store')->name('attach-casts');
    });
});

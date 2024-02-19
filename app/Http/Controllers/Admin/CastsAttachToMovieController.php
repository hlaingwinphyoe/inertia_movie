<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class CastsAttachToMovieController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'casts' => 'required',
            'casts.*' => 'numeric|exists:casts,id'
        ]);

        $movie->movie_casts()->detach();

        $movie->movie_casts()->sync($request->casts);

        return redirect()->back()->with('success', "Casts Added Successful.");
    }
}

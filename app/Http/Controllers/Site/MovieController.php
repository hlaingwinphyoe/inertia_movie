<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function getMovies()
    {
        $perPage = request('page_size') ?: 10;
        $movies = Movie::query()->with(['genres', 'movie_casts'])->filterOn()->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn ($movie) => [
                'id' => $movie->id,
                'title' => $movie->title,
                'casts' => $movie->movie_casts,
                'genres' => $movie->getGenres(),
                'is_published' => $movie->is_published,
                'views' => $movie->views,
                'created_at' => $movie->created_at->diffForHumans(),
                'thumbnail' => $movie->thumbnail,
                'rating' => $movie->rating
            ]);
    }

    public function detail(Movie $movie)
    {
        return Inertia::render('Frontend/Movies/Detail', [
            "movie" => new MovieResource($movie)
        ]);
    }
}

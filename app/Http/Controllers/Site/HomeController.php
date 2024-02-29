<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $banners = Media::isType('banner')->get();
        $latest_movies = Movie::published()->filterOn()->latest()->inRandomOrder()->get()->take(10)->map(fn ($movie) => [
            'id' => $movie->id,
            'title' => $movie->title,
            'views' => $movie->views,
            'release_date' => Carbon::parse($movie->release_date)->format('d M, Y'),
            'thumbnail' => $movie->thumbnail,
            'rating' => $movie->rating
        ]);

        return Inertia::render('Welcome', [
            'latest_movies' => $latest_movies,
            'banners' => $banners,
        ]);
    }
}

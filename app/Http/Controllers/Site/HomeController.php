<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\FAQ;
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
            'rating' => $movie->rating,
        ]);

        $trailer_videos = Movie::published()->whereNotNull('trailer_video')->get()->take(7)->map(fn ($movie) => [
            'id' => $movie->id,
            'title' => $movie->title,
            'trailer_video' => $movie->trailer_video
        ]);

        $faqs = FAQ::isType('faq')->latest()->take(5)->get();

        return Inertia::render('Welcome', [
            'latest_movies' => $latest_movies,
            'trailer_videos' => $trailer_videos,
            'banners' => $banners,
            'faqs' => $faqs,
        ]);
    }

    public function search(Request $request)
    {
        if ($request->page_size) {
            $movies = Movie::query()->with(['genres', 'user', 'movie_casts', 'medias', 'country'])->published()->filterOn()->latest()->paginate($request->page_size)->withQueryString();
        } else {
            $movies = Movie::query()->with(['genres', 'user', 'movie_casts', 'medias', 'country'])->published()->filterOn()->latest()->paginate(9)->withQueryString();
        }


        $movies = MovieResource::collection($movies);

        return Inertia::render('Frontend/Search', [
            'movies' => $movies,
            'query' => request("query") ?? ''
        ]);
    }
}

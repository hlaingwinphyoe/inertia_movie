<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($movie) => [
                'id' => $movie->id,
                'title' => $movie->title,
            ]);

        return Inertia::render('Movie-Settings/Movie/Index', [
            'movies' => $movies,
            'filters' => request('search')
        ]);
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $types = Type::orderBy('name', 'ASC')->get();

        return Inertia::render('Movie-Settings/Movie/Create', [
            'title' => "Create",
            'countries' => $countries,
            'categories' => $categories,
            'types' => $types
        ]);
    }
}

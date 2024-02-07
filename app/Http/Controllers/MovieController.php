<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieStoreRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Type;
use App\Services\MediaService;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MovieController extends Controller
{
    private $movieSvc;
    private $mediaSvc;
    public function __construct(MovieService $movieSvc, MediaService $mediaSvc)
    {
        $this->movieSvc = $movieSvc;
        $this->mediaSvc = $mediaSvc;
    }

    public function index()
    {
        $movies = Movie::with('type', 'categories', 'status')->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($movie) => [
                'id' => $movie->id,
                'title' => $movie->title,
                'type' => $movie->type->name,
                'categories' => $movie->getCategories(),
                'is_published' => $movie->is_published,
                'views' => $movie->movie_views,
                'created_at' => $movie->created_at->diffForHumans(),
                'thumbnail' => $movie->thumbnail,
                'rating' => $movie->rating
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

    public function store(MovieStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $movie = $this->movieSvc->store($request->validated());

            if ($request->hasFile('cover')) {
                $mediaFormdata = [
                    'media' => $request->file('cover'),
                    'type' => 'movie_cover',
                ];

                $url = $this->mediaSvc->storeMedia($mediaFormdata);

                $movie->update([
                    'thumbnail' => $url
                ]);
            }
            DB::commit();
            return redirect()->route('admin.movies.index')->with('success', 'Successfully Created.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Movie $movie)
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $types = Type::orderBy('name', 'ASC')->get();

        return Inertia::render('Movie-Settings/Movie/Create', [
            'title' => "Edit",
            'countries' => $countries,
            'categories' => $categories,
            'types' => $types,
            'movie' => $movie->loadMissing('categories'),
        ]);
    }

    public function update(MovieStoreRequest $request, Movie $movie)
    {
        try {
            DB::beginTransaction();
            $movie = $this->movieSvc->update($movie, $request->validated());

            if ($request->hasFile('cover')) {
                $mediaFormdata = [
                    'media' => $request->file('cover'),
                    'folder' => 'movie_cover',
                    'type' => "image"
                ];

                $this->movieSvc->destroyImage($movie);

                $url = $this->mediaSvc->storeMedia($mediaFormdata);

                $movie->update([
                    'thumbnail' => '/' . $url // folder thumbnail path is in storage
                ]);
            }

            // video save
            if ($request->hasFile('video')) {
                $mediaFormdata = [
                    'media' => $request->file('video'),
                    'folder' => "movie_trailer",
                    'type' => 'video',
                ];

                $this->movieSvc->destroyVideo($movie);

                $url = $this->mediaSvc->storeMedia($mediaFormdata);

                $movie->update([
                    'video' => $url
                ]);
            }

            DB::commit();
            return redirect()->route('admin.movies.index')->with('success', 'Successfully Updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Movie $movie)
    {
        $this->movieSvc->destroyVideo($movie);
        $this->movieSvc->destroyImage($movie);

        $movie->delete();

        return redirect()->back()->with('success', "Successfully Deleted.");
    }

    public function changeStatus(Movie $movie)
    {
        if ($movie->is_published === 0) {
            $movie->update(['is_published' => 1]);
        } else {
            $movie->update(['is_published' => 0]);
        }

        return redirect()->back()->with('success', "Successfully Changed.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieStoreRequest;
use App\Models\Cast;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Movie;
use App\Services\MediaService;
use App\Services\MovieService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Str;

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
        $perPage = request('page_size') ?: 10;
        $movies = Movie::query()->with(['genres','movie_casts'])->filterOn()->latest()
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

        $casts = Cast::orderBy('name')->get();

        return Inertia::render('Movie-Settings/Movie/Index', [
            'movies' => $movies,
            'casts' => $casts
        ]);
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $genres = Genre::orderBy('name', 'ASC')->get();

        return Inertia::render('Movie-Settings/Movie/Create', [
            'title' => "Create",
            'countries' => $countries,
            'genres' => $genres,
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
                    'type' => "movie_cover",
                ];

                $url = $this->mediaSvc->storeMedia($mediaFormdata);

                $movie->update([
                    'thumbnail' => $url
                ]);
            }

            if ($request->hasFile('movie_images')) {
                $mediaFormdata = [
                    'medias' => $request->file('movie_images'),
                    'type' => 'movie_photos',
                ];

                $medias = $this->mediaSvc->storeMultiMedia($mediaFormdata);

                $movie->medias()->attach($medias);
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
        $genres = Genre::orderBy('name', 'ASC')->get();

        return Inertia::render('Movie-Settings/Movie/Create', [
            'title' => "Edit",
            'countries' => $countries,
            'genres' => $genres,
            'movie' => $movie->loadMissing(['genres', 'medias']),
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
                    'type' => 'movie_cover',
                ];

                $this->movieSvc->destroyImage($movie);

                $url = $this->mediaSvc->storeMedia($mediaFormdata);

                $movie->update([
                    'thumbnail' => $url
                ]);
            }

            if ($request->hasFile('movie_images')) {
                $mediaFormdata = [
                    'medias' => $request->file('movie_images'),
                    'type' => 'movie_photos',
                ];

                $medias = $this->mediaSvc->storeMultiMedia($mediaFormdata);

                $movie->medias()->attach($medias);
            }

            DB::commit();
            return redirect()->route('admin.movies.index')->with('success', 'Successfully Updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyMedia($id)
    {
        $this->mediaSvc->destroyMedia($id);

        return redirect()->back()->with('success', 'Photo Deleted.');
    }

    public function destroy(Movie $movie)
    {
        $this->movieSvc->destroyImage($movie);

        if ($movie->medias->count()) {
            foreach ($movie->medias as $media) {
                $media = $this->mediaSvc->destroyMedia($media->id);
            }
        }

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

    public function generateMovieFromMDB(Request $request)
    {
        $movie = Movie::where('tmdb_id', $request->movieId)->exists();
        if ($movie) {
            return redirect()->back()->with('success', 'Movie Already Exists.');
        }

        $tmdb_movie = Http::asJson()->get(config('services.tmdb.endpoint') . 'movie/' . $request->movieId . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');

        if ($tmdb_movie->successful()) {
            $movie = Movie::create([
                'tmdb_id' => $tmdb_movie['id'],
                'title' => $tmdb_movie['title'],
                'description' => $tmdb_movie['overview'],
                'excerpt' => Str::excerpt($tmdb_movie['overview']),
                'running_time' => $tmdb_movie['runtime'],
                'rating' => $tmdb_movie['vote_average'],
                'release_date' => $tmdb_movie['release_date'],
                'country_id' => Country::where('country_code', $tmdb_movie['production_countries'][0]['iso_3166_1'])->first()->id,
                'video_quality' => "HD",
                'is_published' => true,
                'user_id' => Auth::id(),
                'thumbnail' => config('services.tmdb.image_path') . $tmdb_movie['poster_path'],

                // 'backdrop_path' => $tmdb_movie['backdrop_path']
                // 'lang' => $tmdb_movie['original_language'],
            ]);

            $tmdb_genres = $tmdb_movie['genres'];
            $tmdb_genres_ids = collect($tmdb_genres)->pluck('id');
            $genres = Genre::whereIn('tmdb_id', $tmdb_genres_ids)->get();

            $movie->genres()->attach($genres);

            return redirect()->back()->with('success', 'Successfully Created.');
        } else {
            return redirect()->back()->with('success', 'Failed. Api Error.');
        }
    }
}

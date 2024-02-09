<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class GenreController extends Controller
{
    public function index()
    {
        $perPage = request('page_size') ?: 10;

        $genres = Genre::query()->filterOn()
            ->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn ($genre) => [
                'id' => $genre->id,
                'name' => $genre->name,
                'created_at' => $genre->created_at->diffForHumans(),
            ]);

        return Inertia::render('Movie-Settings/Genre/Index', [
            "genres" => $genres,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:genres,name',
        ]);

        $genre = new Genre();
        $genre->name = $request->name;
        $genre->save();

        return redirect()->back()->with('success', "Successfully Created.");
    }

    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:genres,name,' . $genre->id,
        ]);

        $genre->name = $request->name;
        $genre->update();

        return redirect()->back()->with('success', "Successfully Updated.");
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->back()->with('success', "Successfully Deleted.");
    }

    public function generateGenreFromMDB()
    {
        $tmdb_genres = Http::get(config('services.tmdb.endpoint') . 'genre/movie/list?api_key=' . config('services.tmdb.secret') . '&language=en-US');

        if ($tmdb_genres->successful()) {
            $tmdb_genres_data = $tmdb_genres->json();
            foreach ($tmdb_genres_data as $genre) {
                foreach ($genre as $item) {
                    $genre = Genre::where('tmdb_id', $item['id'])->first();
                    if (!$genre) {
                        Genre::create([
                            'tmdb_id' => $item['id'],
                            'name' => $item['name']
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Successfully Generated.');
        }
        return redirect()->back()->with('error', 'Failed. Api Error.');
    }
}

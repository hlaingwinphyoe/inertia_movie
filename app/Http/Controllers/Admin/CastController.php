<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CastStoreRequest;
use App\Models\Cast;
use App\Services\CastService;
use App\Services\MediaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CastController extends Controller
{
    private $castSvc;
    private $mediaSvc;

    public function __construct(CastService $castSvc, MediaService $mediaSvc)
    {
        $this->castSvc = $castSvc;
        $this->mediaSvc = $mediaSvc;
    }

    public function index()
    {
        $casts = Cast::when(request('search'), function ($q, $search) {
            return $q->where('name', 'like', "%$search%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cast) => [
                'id' => $cast->id,
                'name' => $cast->name,
                'birthday' => $cast->birthday,
                'biography' => $cast->biography,
                'gender' => $cast->gender,
                'profile' => $cast->profile,
                'created_at' => $cast->created_at->diffForHumans(),
            ]);

        return Inertia::render('Movie-Settings/Cast/Index', [
            'casts' => $casts
        ]);
    }

    public function store(CastStoreRequest $request)
    {
        $cast = new Cast();
        $response = $this->castSvc->storeOrUpdate($cast, $request->validated());
        $response->save();

        if ($request->hasFile('profile')) {
            $mediaFormdata = [
                'media' => $request->file('profile'),
                'folder' => 'casts_profile',
                'type' => 'image',
            ];

            $url = $this->mediaSvc->storeMedia($mediaFormdata);

            $response->update([
                'profile' => $url
            ]);
        }

        return redirect()->back()->with('success', 'Successfully Created.');
    }

    public function update(CastStoreRequest $request, Cast $cast)
    {
        $response = $this->castSvc->storeOrUpdate($cast, $request->validated());
        $response->update();

        if ($request->hasFile('profile')) {
            $mediaFormdata = [
                'media' => $request->file('profile'),
                'folder' => 'casts_profile',
                'type' => 'image',
            ];

            $this->castSvc->destroyImage($cast);

            $url = $this->mediaSvc->storeMedia($mediaFormdata);

            $response->update([
                'profile' => $url
            ]);
        }

        return redirect()->back()->with('success', 'Successfully Updated.');
    }

    public function destroy(Cast $cast)
    {
        $this->castSvc->destroyImage($cast);
        $cast->delete();

        return redirect()->back()->with('success', 'Successfully Deleted.');
    }

    public function generateCastFromMDB(Request $request)
    {
        $cast = Cast::where('tmdb_id', $request->castId)->first();
        if ($cast) {
            return redirect()->back()->with('error', 'Cast Exists.');
        }

        $tmdb_cast = Http::get(config('services.tmdb.endpoint') . 'person/' . $request->castId . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');

        if ($tmdb_cast->successful()) {
            $gender = $tmdb_cast['gender'] === 1 ? 'Female' : 'Male';

            Cast::create([
                'tmdb_id' => $tmdb_cast['id'],
                'name'    => $tmdb_cast['name'],
                'birthday'    => $tmdb_cast['birthday'],
                'gender'    => $gender,
                'biography'    => $tmdb_cast['biography'],
                'profile' => config('services.tmdb.image_path') . $tmdb_cast['profile_path']
            ]);
            return redirect()->back()->with('success', 'Successfully Created.');
        }

        return redirect()->back()->with('success', 'Failed. Api Error.');
    }
}

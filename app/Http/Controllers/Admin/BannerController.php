<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannerController extends Controller
{
    private $mediaSvc;

    public function __construct(MediaService $mediaSvc)
    {
        $this->mediaSvc = $mediaSvc;
    }

    public function index()
    {
        $banners = Media::isType('banner')
            ->latest()
            ->get()
            ->map(fn ($banner) => [
                'id' => $banner->id,
                'name' => $banner->name,
                'url' => $banner->url,
                'created_at' => $banner->created_at->diffForHumans(),
            ]);

        return Inertia::render('Movie-Settings/Banner/Index', [
            'banners' => $banners
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "banner_image" => "required|image|mimes:png,jpg,jpeg",
        ]);

        $mediaFormdata = [
            'media' => $request->file('banner_image'),
            'type' => 'banner',
        ];

        $media = $this->mediaSvc->storeBanner($mediaFormdata);

        return redirect()->back()->with('success', 'Successfully Upload.');
    }

    public function destroy($id)
    {
        $media = $this->mediaSvc->destroyMedia($id);

        return redirect()->back()->with('success', 'Successfully Deleted.');
    }
}

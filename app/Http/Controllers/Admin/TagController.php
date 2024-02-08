<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::when(request('search'), function ($q, $search) {
            return $q->where('name', 'like', "%$search%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'created_at' => $tag->created_at->diffForHumans(),
            ]);

        return Inertia::render("Movie-Settings/Tag/Index", [
            'tags' => $tags,
            'filters' => request('search')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:tags,name',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();

        return redirect()->back()->with('success', "Successfully Created.");
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:tags,name,' . $tag->id,
        ]);

        $tag->name = $request->name;
        $tag->update();

        return redirect()->back()->with('success', "Successfully Updated.");
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->back()->with('success', "Successfully Deleted.");
    }
}

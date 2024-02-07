<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(request('search'), function ($q, $search) {
            return $q->where('name', 'like', "%$search%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at->diffForHumans(),
            ]);

        return Inertia::render('Movie-Settings/Category/Index', [
            "categories" => $categories,
            "filters" => request('search')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:categories,name',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return redirect()->back()->with('success', "Successfully Created.");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:categories,name,' . $category->id,
        ]);

        $category->name = $request->name;
        $category->update();

        return redirect()->back()->with('success', "Successfully Updated.");
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->back()->with('success', "Successfully Deleted.");
    }
}

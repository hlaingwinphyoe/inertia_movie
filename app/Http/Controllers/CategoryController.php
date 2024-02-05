<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();

        return Inertia::render('Movie-Settings/Category/Index', [
            "categories" => $categories
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

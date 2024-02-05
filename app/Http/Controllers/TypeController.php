<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::latest()->get();

        return Inertia::render("Movie-Settings/Type/Index", [
            'types' => $types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:categories,name',
        ]);

        $type = new Type();
        $type->name = $request->name;
        $type->save();

        return redirect()->back()->with('success', "Successfully Created.");
    }

    public function update(Request $request, Type $type)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|string|unique:categories,name,' . $type->id,
        ]);

        $type->name = $request->name;
        $type->update();

        return redirect()->back()->with('success', "Successfully Updated.");
    }

    public function destroy(Type $type)
    {
        $type->delete();

        return redirect()->back()->with('success', "Successfully Deleted.");
    }
}

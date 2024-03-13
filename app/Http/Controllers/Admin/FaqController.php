<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\FaqType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faq_types = FaqType::orderBy('name', 'asc')->get();
        $faqs = FAQ::query()
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($faq) => [
                'id' => $faq->id,
                'title' => $faq->title,
                'desc' => $faq->desc,
                'faq_type' => $faq->faq_type->name,
                'faq_type_id' => $faq->faq_type_id,
                'created_at' => $faq->created_at->diffForHumans(),
            ]);;

        return Inertia::render('Movie-Settings/FAQ/Index', [
            'faqs' => $faqs,
            'faq_types' => $faq_types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'faq_type' => 'required|numeric|exists:faq_types,id'
        ]);

        $faq = FAQ::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'faq_type_id' => $request->faq_type
        ]);

        return redirect()->back()->with('success', 'Successfully Created');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'faq_type' => 'required|numeric|exists:faq_types,id'
        ]);

        $faq = FAQ::findOrFail($id);

        $faq->update([
            'title' => $request->title,
            'desc' => $request->desc,
            'faq_type_id' => $request->faq_type
        ]);

        return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}

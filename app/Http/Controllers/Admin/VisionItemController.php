<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisionItem;
use Illuminate\Http\Request;

class VisionItemController extends Controller
{
    // public function __construct() {
    //     $this->middleware('permission:manage about content'); // Reuse existing permission
    // }

    public function index()
    {
        $items = VisionItem::orderBy('order')->get();
        return view('admin.content.about.vision_items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.content.about.vision_items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);
        VisionItem::create($validated);
        return redirect()->route('admin.content.vision-items.index')->with('success', 'Vision Point created successfully.');
    }

    public function edit(VisionItem $visionItem) // Route model binding
    {
        return view('admin.content.about.vision_items.edit', compact('visionItem'));
    }

    public function update(Request $request, VisionItem $visionItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);
        $visionItem->update($validated);
        return redirect()->route('admin.content.vision-items.index')->with('success', 'Vision Point updated successfully.');
    }

    public function destroy(VisionItem $visionItem)
    {
        $visionItem->delete();
        return redirect()->route('admin.content.vision-items.index')->with('success', 'Vision Point deleted successfully.');
    }
}
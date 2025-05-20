<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
// REMOVE: use Illuminate\Support\Facades\Storage; // No longer using Storage::disk('public')->store()
use Illuminate\Support\Str; // For generating unique filenames

class HeroSlideController extends Controller {

    // public function __construct() {
    //     $this->middleware('permission:manage hero slides');
    // }

    public function index() {
        $slides = HeroSlide::orderBy('order')->orderBy('created_at')->get();
        return view('admin.content.hero_slides.index', compact('slides'));
    }

    public function create() {
        return view('admin.content.hero_slides.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cta1_text' => 'nullable|string|max:100',
            'cta1_link' => 'nullable|url|max:255',
            'cta2_text' => 'nullable|string|max:100',
            'cta2_link' => 'nullable|url|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Create a unique filename to avoid conflicts
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $uploadDirectory = 'uploads/hero-slides/'; // Relative to the 'public' directory
            $destinationPath = public_path($uploadDirectory); // Gets the absolute path to public/uploads/hero-slides

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create it with appropriate permissions
            }

            // Move the uploaded file
            $image->move($destinationPath, $filename);
            $imagePath = $uploadDirectory . $filename; // Store this path in the DB
        }

        HeroSlide::create(array_merge($request->except('image'), [
            'image_path' => $imagePath, // Store the path relative to 'public'
            'is_active' => $request->has('is_active') ?? false,
        ]));

        return redirect()->route('admin.content.hero-slides.index')->with('success', 'Hero slide created.');
    }

    public function edit(HeroSlide $heroSlide) {
        return view('admin.content.hero_slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide) {
         $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'cta1_text' => 'nullable|string|max:100',
            'cta1_link' => 'nullable|url|max:255',
            'cta2_text' => 'nullable|string|max:100',
            'cta2_link' => 'nullable|url|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ?? false;

        if ($request->hasFile('image')) {
            // 1. Delete old image if it exists
            if ($heroSlide->image_path && file_exists(public_path($heroSlide->image_path))) {
                unlink(public_path($heroSlide->image_path));
            }

            // 2. Upload new image
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $uploadDirectory = 'uploads/hero-slides/';
            $destinationPath = public_path($uploadDirectory);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $data['image_path'] = $uploadDirectory . $filename; // Path relative to public
        }

        $heroSlide->update($data);

        return redirect()->route('admin.content.hero-slides.index')->with('success', 'Hero slide updated.');
    }

    public function destroy(HeroSlide $heroSlide) {
        // Delete image file from public directory
         if ($heroSlide->image_path && file_exists(public_path($heroSlide->image_path))) {
             unlink(public_path($heroSlide->image_path));
         }
        $heroSlide->delete();
        return redirect()->route('admin.content.hero-slides.index')->with('success', 'Hero slide deleted.');
    }
}
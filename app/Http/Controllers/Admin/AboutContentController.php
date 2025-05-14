<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use Illuminate\Http\Request;

class AboutContentController extends Controller {

    //  public function __construct() {
    //      $this->middleware('permission:manage about content');
    //  }

    // Get the first record (or create if non-existent) to edit
    public function edit() {
        $content = AboutContent::firstOrCreate(['id' => 1]); // Ensure a row exists
        return view('admin.content.about.edit', compact('content'));
    }

    // Update the first (and only) record
    public function update(Request $request) {
        $content = AboutContent::firstOrCreate(['id' => 1]);

        // Add validation based on your fields (including JSON structure if needed)
         $validatedData = $request->validate([
            'main_title' => 'required|string|max:255',
            'main_subtitle' => 'nullable|string',
            'story_title' => 'required|string|max:255',
            'story_paragraph1' => 'nullable|string',
            'story_paragraph2' => 'nullable|string',
            'value_cards' => 'nullable|json', // Basic JSON validation
            'join_title' => 'required|string|max:255',
            'join_text' => 'nullable|string',
            'join_button_text' => 'required|string|max:50',
            'stats' => 'nullable|json',
        ]);

         // You might need more specific validation/handling for the JSON fields
         // For example, ensure value_cards is an array of objects with specific keys

        $content->update($validatedData);

        return redirect()->route('admin.content.about.edit')->with('success', 'About Us content updated.');
    }
}
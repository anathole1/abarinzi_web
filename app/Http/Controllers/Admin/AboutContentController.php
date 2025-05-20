<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use Illuminate\Http\Request;

class AboutContentController extends Controller {
    // public function __construct() {
    //     $this->middleware('permission:manage about content');
    // }

    public function edit() {
        $content = AboutContent::getContent();
        // Related items will be managed via their own CRUD interfaces linked from this page
        return view('admin.content.about.edit-main', compact('content'));
    }

    public function update(Request $request) {
        $content = AboutContent::getContent();
        $validatedData = $request->validate([
            'page_main_title' => 'required|string|max:255',
            'page_main_subtitle' => 'nullable|string',
            'intro_title' => 'required|string|max:255',
            'intro_content' => 'required|string',
            'mission_title' => 'required|string|max:255',
            'mission_summary' => 'nullable|string|max:500',
            'mission_content' => 'required|string',
            'core_objectives_section_title' => 'required|string|max:255',
            'vision_section_title' => 'required|string|max:255',
            'vision_section_intro_content' => 'nullable|string',
            'concluding_statement' => 'nullable|string',
            // ... join card and stats validation if kept ...
            'value_cards' => 'nullable|json', // Basic JSON validation
            'join_title' => 'required|string|max:255',
            'join_text' => 'nullable|string',
            'join_button_text' => 'required|string|max:50',
            'stats' => 'nullable|json',

        ]);
        $content->update($validatedData);
        return redirect()->route('admin.content.about.edit')->with('success', 'Main About Us content updated.');
    }
}
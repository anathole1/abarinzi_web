<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CoreObjectiveItem;
use Illuminate\Http\Request;

 class CoreObjectiveItemController extends Controller {
//     public function __construct() {
//         $this->middleware('permission:manage about content'); // Reuse existing permission
//     }
    public function index() {
        $items = CoreObjectiveItem::orderBy('order')->get();
        return view('admin.content.about.objectives.index', compact('items'));
    }
    public function create() { return view('admin.content.about.objectives.create'); }
    public function store(Request $request) {
        $validated = $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'order' => 'integer']);
        CoreObjectiveItem::create($validated);
        return redirect()->route('admin.content.objectives.index')->with('success', 'Objective created.');
    }
    public function edit(CoreObjectiveItem $coreObjectiveItem) { return view('admin.content.about.objectives.edit', compact('coreObjectiveItem')); }
    public function update(Request $request, CoreObjectiveItem $coreObjectiveItem) {
        $validated = $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'order' => 'integer']);
        $coreObjectiveItem->update($validated);
        return redirect()->route('admin.content.objectives.index')->with('success', 'Objective updated.');
    }
    public function destroy(CoreObjectiveItem $coreObjectiveItem) {
        $coreObjectiveItem->delete();
        return redirect()->route('admin.content.objectives.index')->with('success', 'Objective deleted.');
    }
}
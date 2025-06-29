<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberCategory;
use Illuminate\Http\Request;

class MemberCategoryController extends Controller
{
    // Add __construct with middleware for permissions, e.g., 'manage member categories'

    public function index()
    {
        $categories = MemberCategory::orderBy('name')->paginate(10);
        return view('admin.member_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.member_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:member_categories,name',
            'monthly_contribution' => 'required|numeric|min:0',
            'annual_contribution' => 'required|numeric|min:0',
            'percentage_of_loan_allowed' => 'required|numeric|min:0|max:100',
            'monthly_interest_rate_loan' => 'required|numeric|min:0|max:100',
            'social_monthly_contribution' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');

        MemberCategory::create($validated);
        return redirect()->route('admin.member-categories.index')->with('success', 'Member category created.');
    }

    public function edit(MemberCategory $memberCategory)
    {
        return view('admin.member_categories.edit', compact('memberCategory'));
    }

    public function update(Request $request, MemberCategory $memberCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:member_categories,name,' . $memberCategory->id,
            'monthly_contribution' => 'required|numeric|min:0',
            'annual_contribution' => 'required|numeric|min:0',
            'percentage_of_loan_allowed' => 'required|numeric|min:0|max:100',
            'monthly_interest_rate_loan' => 'required|numeric|min:0|max:100',
            'social_monthly_contribution' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');

        $memberCategory->update($validated);
        return redirect()->route('admin.member-categories.index')->with('success', 'Member category updated.');
    }

    public function destroy(MemberCategory $memberCategory)
    {
        if ($memberCategory->memberProfiles()->count() > 0) {
            return redirect()->route('admin.member-categories.index')->with('error', 'Cannot delete category, it has members assigned.');
        }
        $memberCategory->delete();
        return redirect()->route('admin.member-categories.index')->with('success', 'Member category deleted.');
    }
}
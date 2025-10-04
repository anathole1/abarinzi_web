<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct()
    {
        // Add a permission check if desired, e.g., 'manage offices'
        // $this->middleware('permission:manage offices');
    }

    public function index()
    {
        $offices = Office::with('user.memberProfile')->orderBy('display_order')->get();
        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        $users = User::whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
                     ->orderBy('name')->get();
        return view('admin.offices.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:offices,name',
            'code' => 'nullable|string|max:20|unique:offices,code',
            'description' => 'nullable|string',
            'display_order' => 'required|integer|min:0',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Office::create($validated);
        return redirect()->route('admin.offices.index')->with('success', 'Office created successfully.');
    }

    public function show(Office $office)
    {
        $office->load('user.memberProfile');
        return view('admin.offices.show', compact('office'));
    }

    public function edit(Office $office)
    {
        // Get users who are approved members, plus the currently assigned user (in case they are not an approved member)
        $users = User::query()
            ->whereHas('memberProfile', fn($q) => $q->where('status', 'approved'))
            ->when($office->user_id, function ($query) use ($office) {
                $query->orWhere('id', $office->user_id);
            })
            ->orderBy('name')->get();

        return view('admin.offices.edit', compact('office', 'users'));
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $office->id,
            'code' => 'nullable|string|max:20|unique:offices,code,' . $office->id,
            'description' => 'nullable|string',
            'display_order' => 'required|integer|min:0',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // If a user is being assigned, ensure they aren't already assigned to another office
        if ($validated['user_id']) {
            Office::where('user_id', $validated['user_id'])
                  ->where('id', '!=', $office->id)
                  ->update(['user_id' => null]);
        }

        $office->update($validated);
        return redirect()->route('admin.offices.index')->with('success', 'Office updated successfully.');
    }

    public function destroy(Office $office)
    {
        // You might add logic here to prevent deleting critical offices
        $office->delete();
        return redirect()->route('admin.offices.index')->with('success', 'Office deleted successfully.');
    }
}
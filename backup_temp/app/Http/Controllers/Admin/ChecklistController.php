<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function index()
    {
        $checklists = Checklist::orderBy('id', 'asc')->get();
        return response()->json($checklists);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:checklists,key|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'forms' => 'nullable|array',
            'total_documents' => 'required|integer|min:0',
            'sections' => 'required|array',
        ]);

        $checklist = Checklist::create($validated);
        return response()->json(['message' => 'Checklist created successfully', 'checklist' => $checklist], 201);
    }

    public function show($id)
    {
        $checklist = Checklist::findOrFail($id);
        return response()->json($checklist);
    }

    public function update(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);

        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:checklists,key,' . $id,
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'forms' => 'nullable|array',
            'total_documents' => 'required|integer|min:0',
            'sections' => 'required|array',
        ]);

        $checklist->update($validated);
        return response()->json(['message' => 'Checklist updated successfully', 'checklist' => $checklist]);
    }

    public function destroy($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->delete();
        return response()->json(['message' => 'Checklist deleted successfully']);
    }
}

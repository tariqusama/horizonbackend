<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Models\Document;

class AdminController extends Controller
{
    public function getClients()
    {
        // Fetch users who are not admins/managers
        $clients = User::where('role', 'user')->with(['applications', 'applications.documents'])->get();
        return response()->json($clients);
    }

    public function getApplications()
    {
        $applications = Application::with(['user', 'documents'])->latest()->get();
        return response()->json($applications);
    }

    public function updateApplication(Request $request, $id)
    {
        $request->validate([
            'status' => 'sometimes|string',
            'progress' => 'sometimes|string',
            'next_step' => 'sometimes|string',
            'timeline' => 'sometimes|array'
        ]);

        $application = Application::findOrFail($id);
        $application->update($request->only(['status', 'progress', 'next_step', 'timeline']));

        return response()->json($application->load(['user', 'manager']));
    }

    public function updateDocumentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $document = Document::findOrFail($id);
        $document->update(['status' => $request->status]);

        return response()->json($document);
    }
}

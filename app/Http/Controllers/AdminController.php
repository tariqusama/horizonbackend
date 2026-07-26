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
        $applications = Application::with(['user', 'documents', 'manager'])->latest()->get();
        return response()->json($applications);
    }

    public function updateApplication(Request $request, $id)
    {
        $request->validate([
            'status' => 'sometimes|string',
            'progress' => 'sometimes|string',
            'next_step' => 'sometimes|string',
            'timeline' => 'sometimes|array',
            'is_escalated' => 'sometimes|boolean',
            'title' => 'sometimes|string',
            'package_name' => 'sometimes|nullable|string',
            'subtitle' => 'sometimes|nullable|string',
            'amount' => 'sometimes|numeric',
            'paid_amount' => 'sometimes|numeric',
            'receipt_number' => 'sometimes|nullable|string',
        ]);

        $application = Application::findOrFail($id);
        $application->update($request->only([
            'status', 'progress', 'next_step', 'timeline', 'is_escalated',
            'title', 'package_name', 'subtitle', 'amount', 'paid_amount', 'receipt_number'
        ]));

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

<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CaseUpdated;

class ManagerController extends Controller
{
    public function assignedCases(Request $request)
    {
        $manager = $request->user();
        $isAdmin = str_contains(strtolower((string) $manager->role), 'admin');

        if (!$manager || (!str_contains(strtolower((string) $manager->role), 'manager') && !$isAdmin)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Application::with(['user', 'manager', 'documents'])->orderByDesc('created_at');
        if (!$isAdmin) {
            $query->where('manager_id', $manager->id);
        }
        $cases = $query->get();

        return response()->json($cases);
    }

    public function unassignedCases(Request $request)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cases = Application::with(['user'])
            ->whereNull('manager_id')
            ->orderByDesc('created_at')
            ->get();

        $requestedCaseIds = \App\Models\AssignmentRequest::where('manager_id', $manager->id)
            ->where('status', 'Pending')
            ->pluck('application_id')
            ->toArray();

        $cases->each(function ($case) use ($requestedCaseIds) {
            $case->is_requested = in_array($case->id, $requestedCaseIds);
        });

        return response()->json($cases);
    }

    public function requestAssignment(Request $request, $id)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application = Application::whereNull('manager_id')->findOrFail($id);

        $existingRequest = \App\Models\AssignmentRequest::where('application_id', $id)
            ->where('manager_id', $manager->id)
            ->where('status', 'Pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You have already requested this case.'], 400);
        }

        \App\Models\AssignmentRequest::create([
            'application_id' => $id,
            'manager_id' => $manager->id,
            'status' => 'Pending',
        ]);

        return response()->json(['message' => 'Assignment requested successfully']);
    }

    public function updateApplication(Request $request, $id)
    {
        $manager = $request->user();
        $isAdmin = str_contains(strtolower((string) $manager->role), 'admin');

        if (!$manager || (!str_contains(strtolower((string) $manager->role), 'manager') && !$isAdmin)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|string',
            'progress' => 'sometimes|string',
            'next_step' => 'sometimes|string',
            'timeline' => 'sometimes|array',
            'title' => 'sometimes|string|nullable',
            'package_name' => 'sometimes|string|nullable',
            'subtitle' => 'sometimes|string|nullable',
            'amount' => 'sometimes|numeric|nullable',
            'paid_amount' => 'sometimes|numeric|nullable',
            'receipt_number' => 'sometimes|string|nullable',
            'is_escalated' => 'sometimes|boolean',
            'internal_notes' => 'sometimes|array|nullable',
            'form_data' => 'sometimes|array|nullable',
        ]);

        $query = Application::where('id', $id);
        if (!$isAdmin) {
            $query->where('manager_id', $manager->id);
        }
        $application = $query->firstOrFail();

        $application->fill($request->only([
            'status',
            'progress',
            'next_step',
            'timeline',
            'title',
            'package_name',
            'subtitle',
            'amount',
            'paid_amount',
            'receipt_number',
            'is_escalated',
            'internal_notes',
            'form_data'
        ]));
        
        $changes = $application->getDirty();
        $application->save();

        if (!empty($changes) && $application->user) {
            try {
                Mail::to($application->user->email)->send(new CaseUpdated($application, $changes));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Case updated email failed: ' . $e->getMessage());
            }
        }

        return response()->json($application->load(['user', 'manager']));
    }
}

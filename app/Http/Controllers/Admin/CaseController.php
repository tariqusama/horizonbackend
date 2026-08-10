<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AssignmentRequest;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    // GET /api/admin/cases
    public function index()
    {
        $cases = Application::with(['user', 'manager'])->orderBy('created_at', 'desc')->get();

        // Optimize dynamically linked forms to prevent N+1 query
        $titles = $cases->pluck('title')->unique();
        $formsByTitle = [];
        foreach ($titles as $title) {
            $formsByTitle[$title] = \App\Models\DynamicForm::whereHas('services', function ($q) use ($title) {
                $q->where('title', $title);
            })->with(['services' => function ($q) use ($title) {
                $q->where('title', $title);
            }])->get(['dynamic_forms.id', 'dynamic_forms.slug', 'dynamic_forms.name']);
        }

        foreach ($cases as $app) {
            $answers = $app->questionnaire_answers ?? [];
            $allForms = $formsByTitle[$app->title] ?? collect();
            
            $filteredForms = $allForms->filter(function ($form) use ($answers) {
                $pivot = $form->services->first()?->pivot;
                if (!$pivot) return false;
                
                if ($pivot->is_required) {
                    return true;
                }
                
                if ($pivot->condition_code) {
                    return isset($answers[$pivot->condition_code]) && ($answers[$pivot->condition_code] === true || $answers[$pivot->condition_code] === 'true');
                }
                
                return false;
            })->map(function ($form) {
                return [
                    'slug' => $form->slug,
                    'name' => $form->name
                ];
            })->values();

            $app->linked_forms = $filteredForms;
        }

        return response()->json($cases);
    }

    // PUT /api/admin/cases/{id}/assign
    public function assignManager(Request $request, $id)
    {
        $validated = $request->validate([
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $application = Application::findOrFail($id);
        $application->manager_id = $validated['manager_id'];
        $application->save();

        if ($validated['manager_id']) {
            // Approve the request for the assigned manager (if they requested it)
            AssignmentRequest::where('application_id', $id)
                ->where('manager_id', $validated['manager_id'])
                ->where('status', 'Pending')
                ->update(['status' => 'Approved']);

            // Deny all other pending requests for this application since it's now assigned
            AssignmentRequest::where('application_id', $id)
                ->where('manager_id', '!=', $validated['manager_id'])
                ->where('status', 'Pending')
                ->update(['status' => 'Denied']);
        }

        return response()->json(['message' => 'Manager assigned successfully', 'application' => $application->load('manager')]);
    }

    // GET /api/admin/assignment-requests
    public function getRequests(Request $request)
    {
        $query = AssignmentRequest::with(['application.user', 'manager'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->get('manager_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $perPage = (int) $request->get('per_page', 10);

        $requests = $query->paginate($perPage);

        return response()->json($requests);
    }

    // GET /api/admin/assignment-requests/{id}
    public function showRequest($id)
    {
        $request = AssignmentRequest::with(['application.user', 'manager'])->findOrFail($id);
        return response()->json($request);
    }

    // PUT /api/admin/assignment-requests/{id}
    public function updateRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Denied',
        ]);

        $assignmentRequest = AssignmentRequest::findOrFail($id);
        $assignmentRequest->status = $validated['status'];
        $assignmentRequest->save();

        if ($validated['status'] === 'Approved') {
            $application = $assignmentRequest->application;
            $application->manager_id = $assignmentRequest->manager_id;
            $application->save();

            // Deny all other pending requests for this application
            AssignmentRequest::where('application_id', $application->id)
                ->where('id', '!=', $assignmentRequest->id)
                ->where('status', 'Pending')
                ->update(['status' => 'Denied']);
        }

        return response()->json(['message' => 'Request updated successfully', 'request' => $assignmentRequest->load(['application.user', 'manager'])]);
    }

    // PATCH /api/admin/cases/{id}/questionnaire
    public function updateQuestionnaire(Request $request, $id)
    {
        $validated = $request->validate([
            'questionnaire_answers' => 'required|array',
        ]);

        $application = Application::findOrFail($id);
        
        // Merge with existing answers or replace
        $existing = $application->questionnaire_answers ?? [];
        $application->questionnaire_answers = array_merge($existing, $validated['questionnaire_answers']);
        $application->save();

        return response()->json([
            'message' => 'Questionnaire answers updated successfully.',
            'application' => $application
        ]);
    }
}

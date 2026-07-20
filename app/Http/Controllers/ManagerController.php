<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function assignedCases(Request $request)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cases = Application::with(['user', 'manager'])
            ->where('manager_id', $manager->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($cases);
    }

    public function updateApplication(Request $request, $id)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|string',
            'progress' => 'sometimes|string',
            'next_step' => 'sometimes|string',
            'timeline' => 'sometimes|array',
        ]);

        $application = Application::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $application->fill($request->only(['status', 'progress', 'next_step', 'timeline']));
        $application->save();

        return response()->json($application->load(['user', 'manager']));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SignupGoal;
use App\Models\SignupQuestion;
use Illuminate\Http\Request;

class SignupSetupController extends Controller
{
    // Goals CRUD
    public function getGoals()
    {
        $goals = SignupGoal::orderBy('order_index', 'asc')->with('questions')->get();
        return response()->json($goals);
    }

    public function storeGoal(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'order_index' => 'required|integer',
            'default_service_id' => 'nullable|integer|exists:services,id',
        ]);

        $goal = SignupGoal::create($validated);
        return response()->json(['message' => 'Goal created successfully', 'goal' => $goal], 201);
    }

    public function updateGoal(Request $request, $id)
    {
        $goal = SignupGoal::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'order_index' => 'required|integer',
            'default_service_id' => 'nullable|integer|exists:services,id',
        ]);

        $goal->update($validated);
        return response()->json(['message' => 'Goal updated successfully', 'goal' => $goal]);
    }

    public function destroyGoal($id)
    {
        $goal = SignupGoal::findOrFail($id);
        $goal->delete();
        return response()->json(['message' => 'Goal deleted successfully']);
    }

    // Questions CRUD
    public function getQuestions($goalId)
    {
        $questions = SignupQuestion::where('signup_goal_id', $goalId)->orderBy('order_index', 'asc')->get();
        return response()->json($questions);
    }

    public function storeQuestion(Request $request, $goalId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'disqualifying_options' => 'nullable|array',
            'skip_to_end_options' => 'nullable|array',
            'service_mappings' => 'nullable|array',
            'order_index' => 'required|integer',
        ]);

        $validated['signup_goal_id'] = $goalId;

        $question = SignupQuestion::create($validated);
        return response()->json(['message' => 'Question created successfully', 'question' => $question], 201);
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = SignupQuestion::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'disqualifying_options' => 'nullable|array',
            'skip_to_end_options' => 'nullable|array',
            'service_mappings' => 'nullable|array',
            'order_index' => 'required|integer',
        ]);

        $question->update($validated);
        return response()->json(['message' => 'Question updated successfully', 'question' => $question]);
    }

    public function destroyQuestion($id)
    {
        $question = SignupQuestion::findOrFail($id);
        $question->delete();
        return response()->json(['message' => 'Question deleted successfully']);
    }
}

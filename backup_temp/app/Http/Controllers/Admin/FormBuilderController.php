<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DynamicForm;
use App\Models\DynamicFormSection;
use App\Models\DynamicFormQuestion;
use App\Models\DynamicFormOption;

class FormBuilderController extends Controller
{
    // GET /api/admin/guide-engine/forms
    public function getForms()
    {
        return response()->json(DynamicForm::with('service')->get());
    }

    // POST /api/admin/guide-engine/forms
    public function createForm(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|integer|exists:services,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:dynamic_forms,slug',
            'description' => 'nullable|string'
        ]);

        $form = DynamicForm::create($validated);
        return response()->json($form, 201);
    }

    // GET /api/admin/guide-engine/forms/{id}
    public function getForm($id)
    {
        $form = DynamicForm::with(['sections.questions.options'])->findOrFail($id);
        return response()->json($form);
    }

    // POST /api/admin/guide-engine/forms/{id}/sections
    public function addSection(Request $request, $id)
    {
        $form = DynamicForm::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer'
        ]);

        $section = $form->sections()->create($validated);
        return response()->json($section, 201);
    }

    // POST /api/admin/guide-engine/sections/{id}/questions
    public function addQuestion(Request $request, $id)
    {
        $section = DynamicFormSection::findOrFail($id);
        
        $validated = $request->validate([
            'question_text' => 'required|string',
            'help_text' => 'nullable|string',
            'field_type' => 'required|string',
            'field_name' => 'required|string',
            'is_required' => 'boolean',
            'order' => 'integer',
            'validation_rules' => 'nullable|array'
        ]);

        $question = $section->questions()->create($validated);
        
        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $index => $opt) {
                $question->options()->create([
                    'option_label' => $opt['label'],
                    'option_value' => $opt['value'],
                    'order' => $opt['order'] ?? $index
                ]);
            }
        }

        return response()->json($question->load('options'), 201);
    }
    
    // DELETE endpoints
    public function deleteSection($id)
    {
        DynamicFormSection::destroy($id);
        return response()->json(['success' => true]);
    }
    
    public function deleteQuestion($id)
    {
        DynamicFormQuestion::destroy($id);
        return response()->json(['success' => true]);
    }
}

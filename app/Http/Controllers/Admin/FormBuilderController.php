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
        return response()->json(DynamicForm::with('services')->get());
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

        $form = DynamicForm::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null
        ]);

        if (!empty($validated['service_id'])) {
            $form->services()->attach($validated['service_id']);
        }
        
        return response()->json($form->load('services'), 201);
    }

    // POST /api/admin/guide-engine/forms/{id}/connect
    public function connectForm(Request $request, $id)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id'
        ]);

        $form = DynamicForm::findOrFail($id);
        $form->services()->syncWithoutDetaching([$validated['service_id']]);

        return response()->json(['success' => true]);
    }

    // POST /api/admin/guide-engine/forms/{id}/unlink
    public function unlinkForm(Request $request, $id)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id'
        ]);

        $form = DynamicForm::findOrFail($id);
        $form->services()->detach($validated['service_id']);

        return response()->json(['success' => true]);
    }

    // POST /api/admin/guide-engine/forms/{id}/toggle-required
    public function toggleRequired(Request $request, $id)
    {
        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id'
        ]);

        $form = DynamicForm::findOrFail($id);
        
        $pivot = $form->services()->where('service_id', $validated['service_id'])->first()->pivot;
        
        $form->services()->updateExistingPivot($validated['service_id'], [
            'is_required' => !$pivot->is_required
        ]);

        return response()->json(['success' => true]);
    }

    // GET /api/admin/guide-engine/forms/{id}
    public function getForm($id)
    {
        $form = DynamicForm::with(['sections.questions.options', 'services'])->findOrFail($id);
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
    
    // PUT /api/admin/guide-engine/questions/{id}
    public function updateQuestion(Request $request, $id)
    {
        $question = DynamicFormQuestion::findOrFail($id);
        
        $validated = $request->validate([
            'question_text' => 'required|string',
            'help_text' => 'nullable|string',
            'field_type' => 'required|string',
            'field_name' => 'required|string',
            'is_required' => 'boolean',
            'order' => 'integer',
            'validation_rules' => 'nullable|array'
        ]);

        $question->update($validated);
        
        // Handle options
        if ($request->has('options')) {
            $question->options()->delete();
            if (is_array($request->options)) {
                foreach ($request->options as $index => $opt) {
                    $question->options()->create([
                        'option_label' => $opt['label'],
                        'option_value' => $opt['value'],
                        'order' => $opt['order'] ?? $index
                    ]);
                }
            }
        }

        return response()->json($question->load('options'), 200);
    }
    
    // PUT /api/admin/guide-engine/forms/{id}/reorder-sections
    public function reorderSections(Request $request, $id)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|integer',
            'sections.*.order' => 'required|integer'
        ]);

        foreach ($validated['sections'] as $sec) {
            DynamicFormSection::where('id', $sec['id'])->where('dynamic_form_id', $id)->update(['order' => $sec['order']]);
        }

        return response()->json(['success' => true]);
    }

    // PUT /api/admin/guide-engine/sections/{id}/reorder-questions
    public function reorderQuestions(Request $request, $id)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|integer',
            'questions.*.order' => 'required|integer'
        ]);

        foreach ($validated['questions'] as $q) {
            DynamicFormQuestion::where('id', $q['id'])->where('dynamic_form_section_id', $id)->update(['order' => $q['order']]);
        }

        return response()->json(['success' => true]);
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

    // POST /api/admin/guide-engine/forms/{id}/import-pdf-fields
    public function importPdfFields(Request $request, $id)
    {
        $form = DynamicForm::findOrFail($id);
        
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.title' => 'required|string',
            'sections.*.questions' => 'required|array',
            'sections.*.questions.*.question_text' => 'required|string',
            'sections.*.questions.*.field_name' => 'required|string',
            'sections.*.questions.*.field_type' => 'required|string',
        ]);

        $startOrder = $form->sections()->count();

        foreach ($validated['sections'] as $secIndex => $secData) {
            $section = $form->sections()->create([
                'title' => $secData['title'],
                'order' => $startOrder + $secIndex
            ]);

            foreach ($secData['questions'] as $qIndex => $qData) {
                $section->questions()->create([
                    'question_text' => $qData['question_text'],
                    'field_name' => $qData['field_name'],
                    'field_type' => $qData['field_type'],
                    'is_required' => false,
                    'order' => $qIndex
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DynamicForm;

class PublicFormEngineController extends Controller
{
    // GET /api/guide-engine/forms/{slug}
    public function getFormBySlug($slug)
    {
        $form = DynamicForm::where('slug', $slug)
            ->with(['sections.questions.options'])
            ->firstOrFail();
            
        return response()->json($form);
    }
}

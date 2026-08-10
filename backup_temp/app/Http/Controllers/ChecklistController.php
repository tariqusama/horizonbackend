<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Checklist;

class ChecklistController extends Controller
{
    /**
     * Return all checklists mapped by their key.
     */
    public function index()
    {
        $checklists = Checklist::all()->keyBy('key');
        return response()->json($checklists);
    }
}

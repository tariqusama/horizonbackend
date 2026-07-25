<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuideEngineFormEdition;
use App\Models\GuideEngineFieldChange;
use App\Models\GuideEngineQuestion;

class GuideEngineController extends Controller
{
    public function getStats()
    {
        return response()->json([
            'formEditions' => GuideEngineFormEdition::orderBy('created_at', 'desc')->get(),
            'fieldChanges' => GuideEngineFieldChange::orderBy('created_at', 'desc')->get(),
            'questions' => GuideEngineQuestion::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function analyze()
    {
        $packages = ['Green Card Renewal', 'DACA Renewal', 'Citizenship', 'Spouse Visa'];
        $package = $packages[array_rand($packages)];

        GuideEngineFieldChange::create([
            'title' => 'Update intake questions for ' . $package,
            'detail' => 'Sync changes to service requirements'
        ]);

        GuideEngineQuestion::create([
            'title' => 'Has the client provided all documents for ' . $package . '?',
            'detail' => 'Confirm supporting evidence for service'
        ]);

        return response()->json(['message' => 'Analysis complete']);
    }
}

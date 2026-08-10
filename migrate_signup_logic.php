<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SignupGoal;
use App\Models\Service;

echo "Migrating hardcoded signup logic to database...\n";

// Map: Goal Title -> Default Service Title
$goalDefaults = [
    "Remove conditions on residence (marriage-based conditional LPR)" => "Petition to Remove Conditions on Conditional Residence – Joint Filing",
    "Replace or fix a Green Card" => "Renew or Replace Permanent Resident Card (Green Card Renewal / I-90)",
    "DACA (Deferred Action) — Renewal" => "DACA Renewal (Deferred Action for Childhood Arrivals)",
    "Apply for U.S. Citizenship (Naturalization)" => "Application for U.S. Citizenship (Naturalization / N-400)"
];

$goalConditionals = [
    "Adjust status to permanent resident / get a Green Card while in US" => [
        'question_index' => 3, // 4th question
        'mappings' => [
            "Spouse" => "Marriage Green Card inside the U.S. – Concurrent Filing",
            "Parent" => "Parent Adjustment of Status inside the U.S. – Concurrent Filing",
            "Child" => "Child Adjustment of Status inside the U.S. – Concurrent Filing"
        ]
    ],
    "Bring a fiancé(e) or spouse/relative to the U.S." => [
        'question_index' => 0, // 1st question
        'mappings' => [
            "Spouse" => "Petition for a Spouse outside the U.S. – USCIS Petition only",
            "Fiancé(e)" => "K-1 Fiancé Visa – USCIS Petition only",
            "Child/Step Child" => "Petition for a Child outside the U.S. – USCIS Petition only",
            "Parent" => "Petition for a Parent outside the U.S. – USCIS Petition only",
            "Sibling" => "Petition for a Sibling outside the U.S. – USCIS Petition only"
        ]
    ]
];

function getServiceId($title) {
    $service = Service::where('title', $title)->first();
    return $service ? $service->id : null;
}

// 1. Migrate Defaults
foreach ($goalDefaults as $goalTitle => $serviceTitle) {
    $goal = SignupGoal::where('title', $goalTitle)->first();
    $serviceId = getServiceId($serviceTitle);
    
    if ($goal && $serviceId) {
        $goal->update(['default_service_id' => $serviceId]);
        echo "Mapped default for '{$goalTitle}' -> Service ID {$serviceId}\n";
    }
}

// 2. Migrate Conditionals
foreach ($goalConditionals as $goalTitle => $data) {
    $goal = SignupGoal::where('title', $goalTitle)->with('questions')->first();
    if (!$goal) continue;
    
    $qIndex = $data['question_index']; 
    $questions = $goal->questions->toArray();
    
    if (isset($questions[$qIndex])) {
        $questionId = $questions[$qIndex]['id'];
        $questionModel = App\Models\SignupQuestion::find($questionId);
        
        $mappings = [];
        foreach ($data['mappings'] as $answer => $serviceTitle) {
            $serviceId = getServiceId($serviceTitle);
            if ($serviceId) {
                $mappings[$answer] = $serviceId;
            }
        }
        
        if (!empty($mappings)) {
            $questionModel->update(['service_mappings' => $mappings]);
            echo "Mapped conditionals for '{$goalTitle}' Question Index {$qIndex}\n";
        }
    }
}

echo "Migration complete!\n";

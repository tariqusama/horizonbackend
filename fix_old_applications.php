<?php
namespace Database\Scripts;

use App\Models\Application;
use App\Models\DynamicForm;

echo "Starting recovery of missing data for old applications...\n";

// Get all applications that might be missing data
$applications = Application::whereNull('package_name')
    ->orWhereNull('service_id')
    ->orWhereNull('paid_amount')
    ->orWhere('paid_amount', 0)
    ->get();

$fixedCount = 0;

foreach ($applications as $app) {
    $changed = false;
    
    // 1. Recover paid_amount
    if (is_null($app->paid_amount) || $app->paid_amount == 0) {
        if ($app->amount > 0) {
            $app->paid_amount = $app->amount;
            $changed = true;
        }
    }
    
    // 2. Recover package_name from subtitle
    // Expected subtitle format: "Plan: Advanced Plan | Addons: Translation"
    if (empty($app->package_name) && !empty($app->subtitle)) {
        if (preg_match('/Plan:\s*([^|]+)/i', $app->subtitle, $matches)) {
            $app->package_name = trim($matches[1]);
            $changed = true;
        } else {
            // Fallback to title
            $app->package_name = $app->title;
            $changed = true;
        }
    } else if (empty($app->package_name) && empty($app->subtitle)) {
        $app->package_name = $app->title;
        $changed = true;
    }
    
    // 3. Recover service_id
    if (is_null($app->service_id) && !empty($app->form_slug)) {
        // Try to find the associated service using the DynamicForm slug
        $dynamicForm = DynamicForm::where('slug', $app->form_slug)->first();
        if ($dynamicForm && $dynamicForm->services()->exists()) {
            // Pick the first service associated with this form
            $service = $dynamicForm->services()->first();
            $app->service_id = $service->id;
            $changed = true;
        }
    }
    
    if ($changed) {
        $app->save();
        $fixedCount++;
        echo "Repaired Application ID #{$app->id} (User ID #{$app->user_id})\n";
    }
}

echo "Successfully repaired {$fixedCount} old applications!\n";

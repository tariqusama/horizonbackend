<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\DynamicForm;

echo "Starting recovery of missing data for old applications...\n";

$applications = Application::whereNull('package_name')
    ->orWhereNull('service_id')
    ->orWhereNull('paid_amount')
    ->orWhere('paid_amount', 0)
    ->get();

$fixedCount = 0;

foreach ($applications as $app) {
    $changed = false;
    
    if (is_null($app->paid_amount) || $app->paid_amount == 0) {
        if ($app->amount > 0) {
            $app->paid_amount = $app->amount;
            $changed = true;
        }
    }
    
    if (empty($app->package_name) && !empty($app->subtitle)) {
        if (preg_match('/Plan:\s*([^|]+)/i', $app->subtitle, $matches)) {
            $app->package_name = trim($matches[1]);
            $changed = true;
        } else {
            $app->package_name = $app->title;
            $changed = true;
        }
    } else if (empty($app->package_name) && empty($app->subtitle)) {
        $app->package_name = $app->title;
        $changed = true;
    }
    
    if (is_null($app->service_id) && !empty($app->form_slug)) {
        $dynamicForm = DynamicForm::where('slug', $app->form_slug)->first();
        if ($dynamicForm && $dynamicForm->services()->exists()) {
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

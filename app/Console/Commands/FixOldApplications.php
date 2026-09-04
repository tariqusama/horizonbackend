<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\DynamicForm;

class FixOldApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-old-applications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repairs old application records that are missing package_name, paid_amount, or service_id.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting recovery of missing data for old applications...");

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
            
            // 3. Recover service_id
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
                $this->line("Repaired Application ID #{$app->id} (User ID #{$app->user_id})");
            }
        }

        $this->info("Successfully repaired {$fixedCount} old applications!");
    }
}

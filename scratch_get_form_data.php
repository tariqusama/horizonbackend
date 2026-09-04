<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apps = App\Models\Application::whereNotNull('form_data')->orderBy('id', 'desc')->take(3)->get();
echo "Total applications with form_data: " . App\Models\Application::whereNotNull('form_data')->count() . "\n\n";

foreach($apps as $app) {
    echo "=== Application ID: {$app->id} ===\n";
    $formData = is_string($app->form_data) ? json_decode($app->form_data, true) : $app->form_data;
    if ($formData) {
        $count = count($formData);
        echo "Total keys in form_data: {$count}\n";
        print_r($formData);
    } else {
        echo "form_data is empty or invalid JSON.\n";
    }
    echo "\n";
}

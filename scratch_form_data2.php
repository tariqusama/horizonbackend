<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$cases = App\Models\Application::orderBy('id', 'desc')->take(10)->get();
foreach ($cases as $case) {
    echo "ID: " . $case->id . "\n";
    echo "FormData: " . json_encode($case->form_data) . "\n";
    echo "Questionnaire: " . json_encode($case->questionnaire_answers) . "\n";
    echo "--------------------\n";
}

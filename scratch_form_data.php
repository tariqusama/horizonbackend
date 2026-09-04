<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$case = App\Models\Application::latest()->first();
echo json_encode($case->form_data, JSON_PRETTY_PRINT);

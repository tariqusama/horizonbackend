<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$packages = \App\Models\ServicePackage::all()->pluck('name');
echo json_encode($packages);

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\App\Models\DynamicForm::where('slug', 'i-765')->delete();
echo "Cleaned i-765 forms.\n";

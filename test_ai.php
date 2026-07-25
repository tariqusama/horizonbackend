<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try { 
    $res = \Illuminate\Support\Facades\Http::timeout(10)->get('https://text.pollinations.ai/prompt/hello'); 
    echo "Success: " . $res->body() . "\n"; 
} catch (\Exception $e) { 
    echo "Error: " . $e->getMessage() . "\n"; 
}

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = new \Illuminate\Http\Request();
$request->merge([
    'name' => 'John Doe',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john'.rand().'@example.com',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'phone' => '1234567890',
    'country' => 'USA',
    'goal' => 'Green Card',
    'plan' => 'Standard',
    'amount' => 500,
    'addons' => [],
    'questionnaire' => [],
    'service_id' => '1'
]);

try {
    $response = app()->make(\App\Http\Controllers\AuthController::class)->register($request);
    echo "Success! Response: " . json_encode($response->getData());
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation failed! " . json_encode($e->errors());
} catch (\Exception $e) {
    echo "Error! " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}

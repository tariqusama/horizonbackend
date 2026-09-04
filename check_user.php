<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::orderBy('id', 'desc')->first();
if ($user) {
    $app = $user->applications()->orderBy('id', 'desc')->first();
    $data = [
        'application_id' => $app ? $app->id : null,
        'form_data' => $app ? $app->form_data : null,
        'questionnaire_answers' => $app ? $app->questionnaire_answers : null
    ];
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    echo 'No users found';
}

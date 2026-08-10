<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SignupGoal;

$titles = SignupGoal::select('title')->distinct()->pluck('title');

foreach ($titles as $title) {
    // Keep the one that has a default_service_id, or if none, keep the first one
    $keep = SignupGoal::where('title', $title)->whereNotNull('default_service_id')->first();
    if (!$keep) {
        $keep = SignupGoal::where('title', $title)->first();
    }

    if ($keep) {
        // Delete all others
        SignupGoal::where('title', $title)->where('id', '!=', $keep->id)->delete();
        echo "Cleaned duplicates for: $title (Kept ID: {$keep->id})\n";
    }
}
echo "Cleanup complete!\n";

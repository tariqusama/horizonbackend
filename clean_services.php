<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\ServicePackage;

$titles = Service::select('title')->distinct()->pluck('title');

foreach ($titles as $title) {
    // Keep the most recently updated one
    $keep = Service::where('title', $title)->orderBy('updated_at', 'desc')->first();

    if ($keep) {
        // Delete all others
        $duplicates = Service::where('title', $title)->where('id', '!=', $keep->id)->get();
        foreach ($duplicates as $duplicate) {
            // Delete packages
            ServicePackage::where('service_id', $duplicate->id)->delete();
            $duplicate->delete();
        }
        echo "Cleaned duplicates for: $title (Kept ID: {$keep->id})\n";
    }
}
echo "Cleanup complete!\n";

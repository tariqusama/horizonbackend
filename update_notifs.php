<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

DB::table('notifications')->whereIn('notifiable_id', User::whereIn('role', ['admin', 'manager'])->pluck('id'))->delete();

$admin = User::where('role', 'admin')->first();
if ($admin) {
    DB::table('notifications')->insert([
        [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\SystemAlert',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $admin->id,
            'data' => json_encode(['type' => 'security', 'title' => 'New login from unknown device', 'text' => 'A login attempt was made from an unrecognized IP address (192.168.1.100).']),
            'read_at' => null,
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ],
        [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\SystemUpdate',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $admin->id,
            'data' => json_encode(['type' => 'system', 'title' => 'System Update Available', 'text' => 'A new version of Horizon Pathways is ready to be installed.']),
            'read_at' => null,
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ]
    ]);
    echo "Admin notifications updated.\n";
}

$manager = User::where('role', 'manager')->first();
if ($manager) {
    DB::table('notifications')->insert([
        [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\CaseAlert',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $manager->id,
            'data' => json_encode(['type' => 'alert', 'title' => 'Case Escalated', 'text' => 'Case C-2026-881 has been escalated to your attention.']),
            'read_at' => null,
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]
    ]);
    echo "Manager notifications updated.\n";
}

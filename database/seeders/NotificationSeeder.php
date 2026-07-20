<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereIn('role', ['Admin', 'Manager'])->first() ?? User::first();

        if (!$admin) return;

        $notifications = [
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\SecurityAlert',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'type' => 'Security',
                    'title' => 'New login from unknown device',
                    'text' => 'A login attempt was made from an unrecognized IP address (192.168.1.100).',
                    'icon' => 'security',
                    'color' => '#D64545',
                    'bg' => '#FBE1E1'
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes(10),
                'updated_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\BillingAlert',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'type' => 'Billing',
                    'title' => 'Payment Failed: Invoice #INV-8422',
                    'text' => 'Credit card ending in 4242 was declined for Maria Rodriguez.',
                    'icon' => 'billing',
                    'color' => '#B98A0A',
                    'bg' => '#FBEFD1'
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            [
                'id' => Str::uuid(),
                'type' => 'App\Notifications\CaseAlert',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'type' => 'Case',
                    'title' => 'RFE Received for C-2026-881',
                    'text' => 'USCIS has issued a Request for Evidence. Deadline is Nov 24, 2026.',
                    'icon' => 'case',
                    'color' => '#2F8A5F',
                    'bg' => '#DDF3E4'
                ]),
                'read_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(3),
            ],
        ];

        DB::table('notifications')->insert($notifications);
    }
}

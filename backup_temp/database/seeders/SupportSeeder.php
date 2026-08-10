<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('role', 'Client')->first() ?? User::first();
        $staff = User::whereIn('role', ['Admin', 'Manager', 'Staff'])->inRandomOrder()->first() ?? User::first();
        
        $tickets = [
            ['ticket_id' => 'TKT-' . rand(1000, 9999), 'subject' => 'Issue with application upload', 'message' => 'I keep getting an error when uploading my passport.', 'status' => 'Open', 'priority' => 'High', 'user_id' => $client->id, 'assigned_to' => null],
            ['ticket_id' => 'TKT-' . rand(1000, 9999), 'subject' => 'Payment failed', 'message' => 'My card was declined but I have funds.', 'status' => 'In Progress', 'priority' => 'Medium', 'user_id' => $client->id, 'assigned_to' => $staff->id],
            ['ticket_id' => 'TKT-' . rand(1000, 9999), 'subject' => 'How long does processing take?', 'message' => 'Just wondering what the ETA is.', 'status' => 'Closed', 'priority' => 'Low', 'user_id' => $client->id, 'assigned_to' => $staff->id],
        ];

        foreach ($tickets as $t) {
            Ticket::create($t);
        }

        $actions = ['Login', 'Update Role', 'Create User', 'Assign Ticket', 'Status Change'];
        
        for ($i = 0; $i < 20; $i++) {
            AuditLog::create([
                'user_id' => $staff->id,
                'action' => $actions[array_rand($actions)],
                'target' => 'System',
                'details' => 'Performed routine action.',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

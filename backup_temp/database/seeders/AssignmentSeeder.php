<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Application;
use App\Models\AssignmentRequest;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('role', 'Client')->first() ?? User::first();
        $manager = User::where('role', 'Case Manager')->first() ?? User::first();

        // Seed some applications
        $app1 = Application::create([
            'user_id' => $client->id,
            'title' => 'O-1 Visa Application',
            'subtitle' => 'For individual with extraordinary ability',
            'status' => 'Active',
            'progress' => 'In progress',
            'next_step' => 'Document Collection',
            'receipt_number' => 'WAC1234567890',
            'timeline' => [
                ['date' => '2026-07-01', 'title' => 'Application Started', 'description' => 'Client created the case.']
            ]
        ]);

        $app2 = Application::create([
            'user_id' => $client->id,
            'title' => 'EB-2 NIW Application',
            'subtitle' => 'National Interest Waiver',
            'status' => 'Pending Review',
            'progress' => 'Under Review',
            'next_step' => 'Manager Assignment',
            'receipt_number' => null,
            'timeline' => [
                ['date' => '2026-07-15', 'title' => 'Application Submitted', 'description' => 'Awaiting manager assignment.']
            ]
        ]);

        // Create an assignment request for the second app
        AssignmentRequest::create([
            'application_id' => $app2->id,
            'manager_id' => $manager->id,
            'status' => 'Pending',
            'notes' => 'Requesting assignment for the new EB-2 NIW case.'
        ]);
    }
}

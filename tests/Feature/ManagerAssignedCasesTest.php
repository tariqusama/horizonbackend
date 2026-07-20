<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerAssignedCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_fetch_assigned_cases_and_update_timeline(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'email' => 'manager@example.com',
        ]);

        $client = User::factory()->create([
            'role' => 'user',
            'email' => 'client@example.com',
        ]);

        $otherManager = User::factory()->create([
            'role' => 'manager',
            'email' => 'other-manager@example.com',
        ]);

        $assignedApplication = Application::create([
            'user_id' => $client->id,
            'manager_id' => $manager->id,
            'title' => 'Family Petition',
            'subtitle' => 'Priority review',
            'status' => 'Active',
            'progress' => 'Reviewing documents',
            'next_step' => 'Follow up',
            'receipt_number' => 'RCPT-001',
            'timeline' => [],
        ]);

        Application::create([
            'user_id' => $client->id,
            'manager_id' => $otherManager->id,
            'title' => 'Other Case',
            'subtitle' => 'Other manager',
            'status' => 'Active',
            'progress' => 'Reviewing documents',
            'next_step' => 'Follow up',
            'receipt_number' => 'RCPT-002',
            'timeline' => [],
        ]);

        $response = $this->actingAs($manager, 'sanctum')->getJson('/api/manager/assigned-cases');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $assignedApplication->id)
            ->assertJsonPath('0.user.id', $client->id);

        $timeline = [[
            'id' => 'note-1',
            'author' => 'Manager',
            'text' => 'Reviewed client documents',
            'created_at' => now()->toIso8601String(),
        ]];

        $updateResponse = $this->actingAs($manager, 'sanctum')->putJson(
            "/api/manager/applications/{$assignedApplication->id}",
            ['timeline' => $timeline]
        );

        $updateResponse->assertOk()
            ->assertJsonPath('timeline.0.text', 'Reviewed client documents')
            ->assertJsonPath('user.id', $client->id);
    }
}

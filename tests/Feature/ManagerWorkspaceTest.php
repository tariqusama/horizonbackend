<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_send_message_and_escalate_case(): void
    {
        $manager = User::factory()->create(['role' => 'manager', 'email' => 'manager@example.com']);
        $client = User::factory()->create(['role' => 'user', 'email' => 'client@example.com']);

        $application = Application::create([
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

        $messageResponse = $this->actingAs($manager, 'sanctum')->postJson(
            "/api/manager/applications/{$application->id}/messages",
            ['message' => 'Please upload the latest passport copy.']
        );

        $messageResponse->assertCreated()
            ->assertJsonPath('message', 'Please upload the latest passport copy.');

        $escalationResponse = $this->actingAs($manager, 'sanctum')->postJson(
            "/api/manager/applications/{$application->id}/escalate",
            ['reason' => 'Legal complexity']
        );

        $escalationResponse->assertCreated()
            ->assertJsonPath('ticket.status', 'Open');
    }
}

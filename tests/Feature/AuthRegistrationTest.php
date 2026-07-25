<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_accepts_first_and_last_name_fields(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('email', 'jane@example.com');
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }
}

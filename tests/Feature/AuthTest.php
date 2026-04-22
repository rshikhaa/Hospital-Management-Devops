<?php

namespace Tests\Feature;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_and_logout()
    {
        $data = ['name' => 'Test', 'email' => 'test@example.com', 'password' => 'secret', 'password_confirmation' => 'secret'];
        $res = $this->postJson('/api/v1/register', $data);
        $res->assertStatus(201);
        $this->assertArrayHasKey('token', $res->json());

        $res2 = $this->postJson('/api/v1/login', ['email' => 'test@example.com', 'password' => 'secret']);
        $res2->assertStatus(200);
        $token = $res2->json('token');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/logout')
            ->assertStatus(200);
    }
}

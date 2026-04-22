<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $p = Patient::factory()->create();
        $this->actingAs($p, 'sanctum');
        return $p;
    }

    public function test_index_search_pagination()
    {
        $patient = $this->authenticate();
        Notification::factory()->count(12)->create(['patient_id' => $patient->id]);
        Notification::factory()->create(['patient_id' => $patient->id, 'message' => 'hello123']);

        $res = $this->getJson('/api/v1/notifications');
        $res->assertStatus(200);
        $this->assertCount(10, $res->json('data.data'));

        $search = $this->getJson('/api/v1/notifications?search=hello123');
        $search->assertStatus(200);
        $this->assertCount(1, $search->json('data.data'));
    }

    public function test_mark_read_and_forbidden()
    {
        $patient = $this->authenticate();
        $note = Notification::factory()->create(['patient_id' => $patient->id]);
        $other = Notification::factory()->create();

        $this->postJson("/api/v1/notifications/{$note->id}/read")->assertStatus(200);
        $this->assertTrue($note->fresh()->is_read);

        $this->postJson("/api/v1/notifications/{$other->id}/read")->assertStatus(403);
    }
}

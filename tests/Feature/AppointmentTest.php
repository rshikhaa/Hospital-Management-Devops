<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $patient = Patient::factory()->create();
        $this->actingAs($patient, 'sanctum');
        return $patient;
    }

    public function test_store_and_index_and_search_and_pagination()
    {
        $patient = $this->authenticate();
        $doctor = Doctor::factory()->create(['name' => 'DocX']);

        Appointment::factory()->count(12)->create(['patient_id' => $patient->id]);
        Appointment::factory()->create([ 'patient_id' => $patient->id, 'doctor_id' => $doctor->id]);

        $response = $this->getJson('/api/v1/appointments');
        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data.data'));

        $search = $this->getJson('/api/v1/appointments?search=DocX');
        $search->assertStatus(200);
        $this->assertNotEmpty($search->json('data.data'));

        $storeResp = $this->postJson('/api/v1/appointments', [
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00',
        ]);
        $storeResp->assertStatus(201);
        $this->assertDatabaseHas('appointments', ['patient_id' => $patient->id]);
    }

    public function test_show_update_delete_forbidden_when_other_patient()
    {
        $patient = Patient::factory()->create();
        $this->actingAs($patient, 'sanctum');
        $other = Appointment::factory()->create();

        $this->getJson("/api/v1/appointments/{$other->id}")->assertStatus(403);
        $this->putJson("/api/v1/appointments/{$other->id}", [])->assertStatus(403);
        $this->deleteJson("/api/v1/appointments/{$other->id}")->assertStatus(403);
    }
}

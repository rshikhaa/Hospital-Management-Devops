<?php

namespace Tests\Feature;

use App\Models\Prescription;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrescriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $patient = Patient::factory()->create();
        $this->actingAs($patient, 'sanctum');
        return $patient;
    }

    public function test_index_pagination_and_search()
    {
        $patient = $this->authenticate();
        $doctor = Doctor::factory()->create(['name' => 'SearchMe']);

        Prescription::factory()->count(12)->create(['patient_id' => $patient->id]);
        Prescription::factory()->create(['patient_id' => $patient->id, 'doctor_id' => $doctor->id]);

        $res = $this->getJson('/api/v1/prescriptions');
        $res->assertStatus(200);
        $this->assertCount(10, $res->json('data.data'));

        $search = $this->getJson('/api/v1/prescriptions?search=SearchMe');
        $search->assertStatus(200);
        $this->assertNotEmpty($search->json('data.data'));
    }
}

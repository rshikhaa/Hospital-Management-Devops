<?php

namespace Tests\Feature;

use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabReportTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $patient = Patient::factory()->create();
        $this->actingAs($patient, 'sanctum');
        return $patient;
    }

    public function test_index_and_search_and_pagination()
    {
        $patient = $this->authenticate();
        LabReport::factory()->count(12)->create(['patient_id' => $patient->id]);
        LabReport::factory()->create(['patient_id' => $patient->id, 'report_name' => 'XYZtest']);

        $res = $this->getJson('/api/v1/lab-reports');
        $res->assertStatus(200);
        $this->assertCount(10, $res->json('data.data'));

        $search = $this->getJson('/api/v1/lab-reports?search=XYZtest');
        $search->assertStatus(200);
        $this->assertCount(1, $search->json('data.data'));
    }

    public function test_show_forbidden(){
        $patient = $this->authenticate();
        $other = LabReport::factory()->create();
        $this->getJson("/api/v1/lab-reports/{$other->id}")->assertStatus(403);
    }
}

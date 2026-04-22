<?php

namespace Tests\Feature;

use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_and_searchable()
    {
        Doctor::factory()->count(15)->create();
        Doctor::factory()->create(['name' => 'Special Doctor']);

        $response = $this->getJson('/api/v1/doctors');
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertCount(10, $response->json('data.data')); // first page

        $searchResponse = $this->getJson('/api/v1/doctors?search=Special');
        $searchResponse->assertStatus(200);
        $this->assertCount(1, $searchResponse->json('data.data'));
    }
}

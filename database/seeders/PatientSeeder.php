<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '9876543210',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'address' => '123 Main St',
        ]);

        Patient::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone' => '9876543211',
            'date_of_birth' => '1992-05-20',
            'gender' => 'female',
            'address' => '456 Oak Ave',
        ]);

        // Create additional test patients
        Patient::factory(10)->create();
    }
}

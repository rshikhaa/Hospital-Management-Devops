<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
   public function run(): void
    {
        $doctor_data = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@hospital.com',
                'specialty' => 'Cardiology',
                'phone' => '(555) 123-0001',
                'biography' => 'Experienced cardiologist with 15+ years of practice specializing in heart disease prevention and treatment.',
                'address' => '123 Heart St, City, Country',
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@hospital.com',
                'specialty' => 'Dermatology',
                'phone' => '(555) 123-0002',
                'biography' => 'Expert in skin conditions and cosmetic treatments. Dedicated to patient care and satisfaction.',
                'address' => '456 Skin Ave, City, Country',
            ],
            [
                'name' => 'Dr. Michael Brown',
                'email' => 'michael.brown@hospital.com',
                'specialty' => 'Pediatrics',
                'phone' => '(555) 123-0003',
                'biography' => 'Compassionate pediatrician focused on child health and development. Parents\' favorite doctor.',
                'address' => '789 Child Rd, City, Country',
            ],
            [
                'name' => 'Dr. Emily Davis',
                'email' => 'emily.davis@hospital.com',
                'specialty' => 'General Practice',
                'phone' => '(555) 123-0004',
                'biography' => 'Holistic general practitioner providing comprehensive medical care for the whole family.',
                'address' => '321 Family Ln, City, Country',
            ],
        ];

        foreach ($doctor_data as $data) {
            Doctor::create($data);
        }

        // Optional: Generate 12 more random doctors using factory
        Doctor::factory()->count(12)->create();
    }
}

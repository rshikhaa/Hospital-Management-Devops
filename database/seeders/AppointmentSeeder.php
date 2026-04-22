<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        foreach ($patients as $patient) {
            for ($i = 0; $i < 5; $i++) {
                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctors->random()->id,
                    'appointment_date' => now()->addDays(rand(1, 30))->toDateString(),
                    'appointment_time' => rand(9, 17) . ':00:00',
                    'status' => ['scheduled', 'completed', 'cancelled'][rand(0, 2)],
                    'notes' => 'General checkup',
                ]);
            }
        }
    }
}

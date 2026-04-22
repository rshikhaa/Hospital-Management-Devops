<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),
            'appointment_date' => $this->faker->date,
            'appointment_time' => $this->faker->time,
            'status' => $this->faker->randomElement(['scheduled','completed','cancelled']),
            'notes' => $this->faker->sentence,
        ];
    }
}

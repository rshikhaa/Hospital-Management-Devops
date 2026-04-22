<?php

namespace Database\Factories;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'date' => $this->faker->date,
            'medication' => $this->faker->word,
            'file_url' => $this->faker->url,
        ];
    }
}

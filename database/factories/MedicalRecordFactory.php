<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'date' => $this->faker->date,
            'diagnosis' => $this->faker->sentence,
            'file_url' => $this->faker->url,
        ];
    }
}

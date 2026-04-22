<?php

namespace Database\Factories;

use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabReportFactory extends Factory
{
    protected $model = LabReport::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'report_name' => $this->faker->word,
            'report_date' => $this->faker->date,
            'file_path' => $this->faker->url,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'specialty' => $this->faker->randomElement(['Cardiology','Dermatology','Pediatrics','General']),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}

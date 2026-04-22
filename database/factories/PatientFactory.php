<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'phone' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->date,
            'gender' => $this->faker->randomElement(['male','female','other']),
            'address' => $this->faker->address,
        ];
    }
}

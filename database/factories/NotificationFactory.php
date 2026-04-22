<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'message' => $this->faker->sentence,
            'is_read' => false,
        ];
    }
}

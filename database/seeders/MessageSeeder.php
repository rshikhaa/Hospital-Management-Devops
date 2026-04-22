<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
         public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        $messageTexts = [
            'How are you feeling today?',
            'Please schedule a follow-up visit',
            'Your test results look good',
            'I recommend increasing your medication dosage',
            'Have you experienced any side effects?',
            'Looking forward to seeing you soon',
            'Please avoid strenuous activities',
        ];

        // Patient-to-patient messages
        foreach ($patients as $patient) {
            for ($i = 0; $i < 2; $i++) {
                $otherPatient = $patients->where('id', '!=', $patient->id)->random();
                Message::create([
                    'sender_id' => $patient->id,
                    'receiver_id' => $otherPatient->id,
                    'message' => $messageTexts[array_rand($messageTexts)],
                ]);
            }
        }
    }
}
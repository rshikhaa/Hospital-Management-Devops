<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $notifications = [
            ['title' => 'Appointment Reminder', 'message' => 'Your appointment is scheduled for tomorrow'],
            ['title' => 'Lab Results Ready', 'message' => 'Lab results are available for review'],
            ['title' => 'Prescription Update', 'message' => 'New prescription ready for pickup'],
            ['title' => 'Medication Reminder', 'message' => 'Reminder: Take your medications'],
            ['title' => 'New Message', 'message' => 'Doctor has sent you a message'],
            ['title' => 'Payment Due', 'message' => 'Invoice is due for payment'],
            ['title' => 'Appointment Cancelled', 'message' => 'Your appointment has been cancelled'],
        ];

        foreach ($patients as $patient) {
            for ($i = 0; $i < 3; $i++) {
                $notification = $notifications[array_rand($notifications)];
                Notification::create([
                    'patient_id' => $patient->id,
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'is_read' => rand(0, 1) === 1,
                ]);
            }
        }
    }
}

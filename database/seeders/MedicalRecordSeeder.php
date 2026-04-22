<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = \App\Models\Doctor::all();
        $diagnoses = ['Hypertension', 'Diabetes', 'Asthma', 'GERD', 'Anemia', 'Migraine', 'Sleep Apnea'];
        $treatments = ['Medication therapy', 'Lifestyle changes', 'Physical therapy', 'Surgery', 'Monitoring'];

        foreach ($patients as $patient) {
            for ($i = 0; $i < 2; $i++) {
                // create a simple text file for the record
                $fileName = 'records/record_' . $patient->id . '_' . $i . '.txt';
                $fullPath = storage_path('app/public/' . $fileName);

                // make sure folder exists so file_put_contents doesn't crash
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                if (!file_exists($fullPath)) {
                    file_put_contents($fullPath, "Medical record for patient {$patient->id}, entry {$i}");
                }

                MedicalRecord::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctors->random()->id,
                    'diagnosis' => $diagnoses[array_rand($diagnoses)],
                    'treatment' => $treatments[array_rand($treatments)],
                    'notes' => 'Regular follow-up required',
                    'record_date' => now()->subMonths(rand(1, 12))->toDateString(),
                    'file_path' => $fileName,
                ]);
            }
        }
    }
}

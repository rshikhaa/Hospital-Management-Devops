<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Dompdf\Dompdf;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        // truncate table before inserting so file paths remain accurate
        Prescription::truncate();

        $patients = Patient::all();
        $doctors = Doctor::all();
        $medications = ['Aspirin', 'Metformin', 'Lisinopril', 'Atorvastatin', 'Amoxicillin', 'Ibuprofen', 'Omeprazole'];
        // remove any existing prescription files
        $dirPath = storage_path('app/public/prescriptions');
        if (is_dir($dirPath)) {
            array_map('unlink', glob($dirPath.'/*.pdf'));
        }

        foreach ($patients as $patient) {
            for ($i = 0; $i < 3; $i++) {
                $doctor = $doctors->random();
                $med = $medications[array_rand($medications)];
                $dose = rand(100, 500) . 'mg';
                $instr = ['Take once daily','Take twice a day after meals','Apply to affected area'][array_rand(['Take once daily','Take twice a day after meals','Apply to affected area'])];
                $date = now()->subDays(rand(1, 60))->toDateString();
                $fileName = 'prescriptions/prescription_' . $patient->id . '_' . uniqid() . '.pdf';
                $fullPath = storage_path('app/public/' . $fileName);
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $dompdf = new Dompdf();
                $html = "<style>body{font-family:Arial,Helvetica,sans-serif;color:#333;} h1{background:#007bff;color:white;padding:10px;text-align:center;margin-bottom:20px;} table{width:100%;border-collapse:collapse;} th{background:#007bff;color:white;padding:8px;} td{padding:8px;border:1px solid #ddd;} tr:nth-child(even){background:#f9f9f9;}</style>";
                $html .= "<h1>Prescription</h1>";
                $html .= "<p><strong>Patient:</strong> {$patient->name} (ID {$patient->id})</p>";
                $html .= "<p><strong>Doctor:</strong> Dr. {$doctor->name}</p>";
                $html .= "<p><strong>Date:</strong> {$date}</p>";
                $html .= "<table>";
                $html .= "<tr><th>Medication</th><th>Dosage</th><th>Instructions</th></tr>";
                $html .= "<tr><td>{$med}</td><td>{$dose}</td><td>{$instr}</td></tr>";
                $html .= "</table>";
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($fullPath, $dompdf->output());
                Prescription::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'medication' => $med,
                    'dosage' => $dose,
                    'instructions' => $instr,
                    'file_path' => $fileName,
                ]);
            }
        }
    }
}

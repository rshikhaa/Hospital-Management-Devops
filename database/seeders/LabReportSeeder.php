<?php

namespace Database\Seeders;

use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Dompdf\Dompdf;

class LabReportSeeder extends Seeder
{
    public function run(): void
    {
        // wipe previous records to avoid stale file paths
        
        LabReport::truncate();

        $patients = Patient::all();
        $testNames = ['Blood Test', 'X-Ray', 'Ultrasound', 'CT Scan', 'MRI', 'ECG', 'EEG'];
        // clear old PDFs
        $dirPath = storage_path('app/public/reports');
        if (is_dir($dirPath)) {
            array_map('unlink', glob($dirPath.'/*.pdf'));
        }

        foreach ($patients as $patient) {
            for ($i = 0; $i < 3; $i++) {
                $test = $testNames[array_rand($testNames)];
                $date = now()->subDays(rand(1, 90))->toDateString();
                $fileName = 'reports/report_' . uniqid() . '.pdf';
                $fullPath = storage_path('app/public/' . $fileName);
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                // create some fake result rows
                $results = [];
                for ($j = 0; $j < 4; $j++) {
                    $results[] = [
                        'name' => 'Parameter ' . ($j + 1),
                        'value' => rand(10, 100) . ' ' . ['mg/dL','mmHg','bpm'][array_rand(['mg/dL','mmHg','bpm'])],
                        'normal' => 'Normal',
                    ];
                }

                $dompdf = new Dompdf();
                $html = "<style>body{font-family:Arial,Helvetica,sans-serif;color:#333;} h1{background:#007bff;color:white;padding:10px;text-align:center;margin-bottom:20px;} table{width:100%;border-collapse:collapse;} th{background:#007bff;color:white;padding:8px;} td{padding:8px;border:1px solid #ddd;} tr:nth-child(even){background:#f9f9f9;} </style>";
                $html .= "<h1>Medical Lab Report</h1>";
                $html .= "<p><strong>Patient:</strong> {$patient->name} (ID {$patient->id})</p>";
                $html .= "<p><strong>Report:</strong> {$test}</p>";
                $html .= "<p><strong>Date:</strong> {$date}</p>";
                $html .= "<table>";
                $html .= "<tr><th>Test</th><th>Result</th><th>Normal Range</th></tr>";
                foreach ($results as $r) {
                    $html .= "<tr><td>{$r['name']}</td><td>{$r['value']}</td><td>{$r['normal']}</td></tr>";
                }
                $html .= "</table>";

                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($fullPath, $dompdf->output());

                LabReport::create([
                    'patient_id' => $patient->id,
                    'report_name' => $test,
                    'report_date' => $date,
                    'file_path' => $fileName,
                ]);
            }
        }
    }
}

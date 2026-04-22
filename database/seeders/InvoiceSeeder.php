<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Dompdf\Dompdf;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // truncate invoice records to avoid orphaned file paths
        Invoice::truncate();

        $patients = Patient::all();
        $statuses = ['paid', 'pending', 'overdue'];
        // clean up previous invoices
        $dirPath = storage_path('app/public/invoices');
        if (is_dir($dirPath)) {
            array_map('unlink', glob($dirPath.'/*.pdf'));
        }

        foreach ($patients as $patient) {
            for ($i = 0; $i < 2; $i++) {
                $status = $statuses[array_rand($statuses)];
                $fileName = 'invoices/invoice_' . $patient->id . '_' . uniqid() . '.pdf';
                $fullPath = storage_path('app/public/' . $fileName);
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $dompdf = new Dompdf();
                $service = ['Consultation Fee','Lab Tests','Medication','Follow-up Visit'][array_rand(['Consultation Fee','Lab Tests','Medication','Follow-up Visit'])];
                $amount = rand(100, 1000);
                $date = now()->subDays(rand(1, 30))->toDateString();
                $html = "<style>body{font-family:Arial,Helvetica,sans-serif;color:#333;} h1{background:#007bff;color:white;padding:10px;text-align:center;margin-bottom:20px;} table{width:100%;border-collapse:collapse;} th{background:#007bff;color:white;padding:8px;} td{padding:8px;border:1px solid #ddd;} tr:nth-child(even){background:#f9f9f9;}</style>";
                $html .= "<h1>Invoice</h1>";
                $html .= "<p><strong>Invoice #</strong> {$patient->id}-" . uniqid() . "</p>";
                $html .= "<p><strong>Patient:</strong> {$patient->name} (ID {$patient->id})</p>";
                $html .= "<p><strong>Date:</strong> {$date}</p>";
                $html .= "<table>";
                $html .= "<tr><th>Description</th><th>Amount</th></tr>";
                $html .= "<tr><td>{$service}</td><td>$${amount}</td></tr>";
                $html .= "</table>";
                $html .= "<p><strong>Total Due:</strong> $${amount}</p>";
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($fullPath, $dompdf->output());
                Invoice::create([
                    'patient_id' => $patient->id,
                    'amount' => $amount,
                    'status' => $status,
                    'payment_date' => $status === 'paid' ? now()->subDays(rand(1, 30))->toDateString() : null,
                    'file_path' => $fileName,
                ]);
            }
        }
    }
}

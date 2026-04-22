<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\LabReport;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function show(Request $request, $path)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // security: ensure the file belongs to the authenticated patient
        $segments = explode('/', $path);
        $type = $segments[0] ?? '';
        $authorized = false;

        switch ($type) {
            case 'prescriptions':
                $authorized = Prescription::where('patient_id', $user->id)
                    ->where('file_path', $path)
                    ->exists();
                break;
            case 'reports':
            case 'lab_reports':
            case 'lab-reports':
                $authorized = LabReport::where('patient_id', $user->id)
                    ->where('file_path', $path)
                    ->exists();
                break;
            case 'records':
            case 'medical_records':
            case 'medical-records':
                $authorized = MedicalRecord::where('patient_id', $user->id)
                    ->where('file_path', $path)
                    ->exists();
                break;
            case 'invoices':
                $authorized = Invoice::where('patient_id', $user->id)
                    ->where('file_path', $path)
                    ->exists();
                break;
            default:
                $authorized = false;
        }

        if (! $authorized) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (! Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return Storage::disk('public')->response($path);
    }
}

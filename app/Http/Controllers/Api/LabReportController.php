<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabReport;
use Illuminate\Http\Request;

class LabReportController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user();
        $query = LabReport::where('patient_id', $patient->id);
        if ($search = $request->get('search')) {
            $query->where('report_name', 'like', "%{$search}%");
        }
        $reports = $query->paginate(10);
        $reports->getCollection()->each->append('file_url');
        return response()->json(['success' => true, 'data' => $reports]);
    }

    public function show(Request $request, LabReport $report)
    {
        if ($report->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $report->append('file_url');
        return response()->json(['success' => true, 'data' => $report]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController
{
    public function index(Request $request)
    {
        $records = MedicalRecord::where('patient_id', $request->user()->id)
            ->with('doctor')
            ->orderBy('record_date', 'desc')
            ->paginate(10);
        
        // append file_url attribute so frontend can use it
        $records->getCollection()->each->append('file_url');
        
        return response()->json(['success' => true, 'data' => $records]);
    }

    public function show(Request $request, MedicalRecord $record)
    {
        if ($record->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $record->append('file_url');
        return response()->json(['success' => true, 'data' => $record->load('doctor')]);
    }
}

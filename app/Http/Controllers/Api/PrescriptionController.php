<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user();
        $query = Prescription::where('patient_id', $patient->id)->with('doctor');
        if ($search = $request->get('search')) {
            $query->whereHas('doctor', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        $prescriptions = $query->paginate(10);
        $prescriptions->getCollection()->each->append('file_url');
        return response()->json(['success' => true, 'data' => $prescriptions]);
    }

    public function show(Request $request, Prescription $prescription)
    {
        if ($prescription->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $prescription->append('file_url');
        return response()->json(['success' => true, 'data' => $prescription]);
    }
}

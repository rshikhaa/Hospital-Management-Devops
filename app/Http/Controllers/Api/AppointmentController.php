<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user();
        $query = Appointment::where('patient_id', $patient->id)->with('doctor');
        if ($search = $request->get('search')) {
            $query->whereHas('doctor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        $appointments = $query->paginate(10);
        return response()->json(['success' => true, 'data' => $appointments]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $data['patient_id'] = $request->user()->id;
        // ensure time is in 24-hour H:i:s format for MySQL
        if (isset($data['appointment_time'])) {
            $data['appointment_time'] = date('H:i:s', strtotime($data['appointment_time']));
        }
        $appointment = Appointment::create($data);
        return response()->json(['success' => true, 'data' => $appointment], 201);
    }

    public function show(Request $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        return response()->json(['success' => true, 'data' => $appointment]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $data = $request->validate([
            'appointment_date' => 'date',
            'appointment_time' => '',
            'status' => '',
            'notes' => 'nullable|string',
        ]);
        if (isset($data['appointment_time'])) {
            $data['appointment_time'] = date('H:i:s', strtotime($data['appointment_time']));
        }
        $appointment->update($data);
        return response()->json(['success' => true, 'data' => $appointment]);
    }

    public function destroy(Request $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }
        $appointment->delete();
        return response()->json(['success' => true]);
    }
}

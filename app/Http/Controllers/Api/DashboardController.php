<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\LabReport;
use App\Models\Prescription;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $patient = $request->user();

        $upcoming = Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now()->toDateString())
            ->with('doctor')
            ->get();

        $recentReports = LabReport::where('patient_id', $patient->id)
            ->orderBy('report_date', 'desc')
            ->limit(5)
            ->get();

        $activePrescriptions = Prescription::where('patient_id', $patient->id)->get();

        $notifications = Notification::where('patient_id', $patient->id)
            ->where('is_read', false)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'upcoming_appointments' => $upcoming,
                'recent_lab_reports' => $recentReports,
                'active_prescriptions' => $activePrescriptions,
                'unread_notifications' => $notifications,
            ],
        ]);
    }
}

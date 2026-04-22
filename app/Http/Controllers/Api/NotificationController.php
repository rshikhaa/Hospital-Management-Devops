<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user();
        $query = Notification::where('patient_id', $patient->id);
        if ($search = $request->get('search')) {
            $query->where('message', 'like', "%{$search}%");
        }
        $notes = $query->paginate(10);
        return response()->json(['success' => true, 'data' => $notes]);
    }

    public function markRead(Request $request, Notification $notification)
    {
        if ($notification->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $notification->update(['is_read' => true]);
        return response()->json(['success' => true, 'data' => $notification]);
    }
}

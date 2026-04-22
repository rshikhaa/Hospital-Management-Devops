<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        // Search functionality
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%")
                  ->orWhere('qualifications', 'like', "%{$search}%");
        }

        // Filter by specialty
        if ($specialty = $request->get('specialty')) {
            $query->where('specialty', $specialty);
        }

        // Filter by availability
        if ($request->get('available') === 'true') {
            $query->where('is_available', true);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        if ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } elseif ($sortBy === 'name') {
            $query->orderBy('name', 'asc');
        }

        $doctors = $query->paginate(12);
        return response()->json(['success' => true, 'data' => $doctors]);
    }

    public function show(Doctor $doctor)
    {
        return response()->json(['success' => true, 'data' => $doctor->load('appointments', 'prescriptions')]);
    }
}

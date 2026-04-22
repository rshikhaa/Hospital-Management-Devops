<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $patient = Patient::create($data);
        $token = $patient->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'patient' => $patient,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
{
    try {
        $credentials = $request->only('email', 'password');
        $patient = Patient::where('email', $credentials['email'])->first();

        if (! $patient || ! Hash::check($credentials['password'], $patient->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $token = $patient->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'patient' => $patient,
                'token' => $token
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        // include avatar_url accessor
        $user->append('avatar_url');
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateProfile(Request $request)
{
    $user = $request->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'nullable|min:6'
    ]);

    $user->name = $request->name;

    if ($request->password) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    $user->append('avatar_url');

    return response()->json([
        'success' => true,
        'data' => $user
    ]);
}
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = $request->user();
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        // return updated user with new URL
        $user->append('avatar_url');
        return response()->json(['success' => true, 'data' => $user]);
    }
}

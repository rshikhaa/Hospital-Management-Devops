<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController
{
    public function index(Request $request)
    {
        $messages = Message::where('receiver_id', $request->user()->id)
            ->orWhere('sender_id', $request->user()->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:patients,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return response()->json(['success' => true, 'data' => $message->load(['sender', 'receiver'])], 201);
    }

    public function show(Request $request, Message $message)
    {
        if ($message->receiver_id !== $request->user()->id && $message->sender_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'data' => $message->load(['sender', 'receiver'])]);
    }

    /**
     * Return all messages between the authenticated user and another patient.
     */
    public function thread(Request $request, $patientId)
    {
        $userId = $request->user()->id;
        $messages = Message::where(function ($q) use ($userId, $patientId) {
            $q->where('sender_id', $userId)->where('receiver_id', $patientId);
        })->orWhere(function ($q) use ($userId, $patientId) {
            $q->where('sender_id', $patientId)->where('receiver_id', $userId);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at')
          ->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }
}

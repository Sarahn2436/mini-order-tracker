<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage; 
class ChatController extends Controller
{
    // Save a new message
    public function storeMessage(Request $request) 
    {
        try {
            $msg = ChatMessage::create([
                'session_id' => $request->session_id,
                'sender_type' => $request->sender_type, // checks if the sender is a customer or staff
                'message' => $request->message
            ]);

            return response()->json([
                'message' => $msg->message
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // Fetch conversation messages
    public function getMessages($sessionId) {
        return response()->json(ChatMessage::where('session_id', $sessionId)->get());
    }
    public function getActiveRooms() 
{
    $rooms = ChatMessage::select('session_id')
        ->distinct()
        ->orderBy('created_at', 'desc')
        ->get();
        
    return response()->json($rooms);
}
}
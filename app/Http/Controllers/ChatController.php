<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $messages = \App\Models\Message::where('user_id', $request->user()->id)
                        ->orderBy('created_at', 'asc')
                        ->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $userMessage = \App\Models\Message::create([
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'is_admin' => false
        ]);

        $prompt = "You are Nancy, a helpful and friendly immigration assistant for Horizon Pathways. Keep responses concise and helpful. The user says: " . $request->message;
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(15)->get('https://text.pollinations.ai/prompt/' . urlencode($prompt));
            $aiText = $response->successful() ? $response->body() : "I'm currently experiencing high volume. Please try again later.";
        } catch (\Exception $e) {
            $aiText = "I'm having trouble connecting to my servers right now. Please check back soon!";
        }

        $aiMessage = \App\Models\Message::create([
            'user_id' => $request->user()->id,
            'message' => $aiText,
            'is_admin' => true
        ]);

        return response()->json([$userMessage, $aiMessage], 201);
    }
}

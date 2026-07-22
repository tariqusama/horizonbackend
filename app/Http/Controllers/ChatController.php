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

        $application = \App\Models\Application::where('user_id', $request->user()->id)
            ->whereNotNull('manager_id')
            ->first();

        if ($application) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\NewMessageNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $application->manager_id,
                'data' => json_encode([
                    'title' => 'New Client Message',
                    'text' => 'Client ' . $request->user()->name . ' sent a new message.',
                    'type' => 'message'
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\NewMessageNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'title' => 'New Client Message',
                    'text' => 'Client ' . $request->user()->name . ' sent a new message.',
                    'type' => 'message'
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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

        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\NewMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $request->user()->id,
            'data' => json_encode([
                'title' => 'New Message',
                'text' => 'You have received a new message from Support.',
                'type' => 'message'
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([$userMessage, $aiMessage], 201);
    }
}

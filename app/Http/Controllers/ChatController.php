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
            $timeline = is_array($application->timeline) ? $application->timeline : [];
            $timeline[] = [
                'id' => 'msg-' . time() . '-' . uniqid(),
                'author' => $request->user()->name . ' (Client)',
                'text' => $request->message,
                'created_at' => now()->toIso8601String(),
            ];
            $application->timeline = $timeline;
            $application->save();

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
                    'title' => 'Manager received a note from user',
                    'text' => 'User ' . $request->user()->name . ' sent a note to their assigned Case Manager.',
                    'type' => 'message'
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json($userMessage, 201);
    }
}

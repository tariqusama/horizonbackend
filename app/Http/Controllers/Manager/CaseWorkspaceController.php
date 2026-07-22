<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CaseWorkspaceController extends Controller
{
    public function messages(Request $request, $applicationId)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application = Application::where('id', $applicationId)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $messages = Message::where('user_id', $application->user_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function storeMessage(Request $request, $applicationId)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $application = Application::where('id', $applicationId)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $message = Message::create([
            'user_id' => $application->user_id,
            'message' => $request->message,
            'is_admin' => true,
        ]);

        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\NewMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $application->user_id,
            'data' => json_encode([
                'title' => 'New Message',
                'text' => 'You have received a new message regarding your application.',
                'type' => 'message'
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($message, 201);
    }

    public function documents(Request $request, $applicationId)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application = Application::where('id', $applicationId)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        return response()->json($application->documents()->orderBy('created_at', 'desc')->get());
    }

    public function requestDocuments(Request $request, $applicationId)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'documents' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $application = Application::where('id', $applicationId)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $requestEntry = [
            'id' => 'req-' . time(),
            'documents' => $request->documents,
            'note' => $request->note ?? '',
            'createdAt' => now()->toIso8601String(),
        ];

        $timeline = is_array($application->timeline) ? $application->timeline : [];
        $timeline[] = [
            'id' => 'req-' . time(),
            'author' => $manager->email,
            'text' => 'Document request: ' . $request->documents,
            'created_at' => now()->toIso8601String(),
        ];

        $application->timeline = $timeline;
        $application->save();

        return response()->json(['request' => $requestEntry, 'application' => $application->load(['user', 'manager'])], 201);
    }

    public function escalate(Request $request, $applicationId)
    {
        $manager = $request->user();

        if (!$manager || !str_contains(strtolower((string) $manager->role), 'manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        $application = Application::where('id', $applicationId)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $ticket = Ticket::create([
            'ticket_id' => 'TICKET-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'subject' => 'Escalation for application #' . $application->id,
            'message' => 'Escalated by manager ' . $manager->email . '. Reason: ' . $request->reason,
            'status' => 'Open',
            'priority' => 'High',
            'user_id' => $application->user_id,
            'assigned_to' => $manager->id,
        ]);

        return response()->json(['ticket' => $ticket->load(['user', 'assignee'])], 201);
    }
}

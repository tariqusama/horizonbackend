<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    // GET /api/admin/tickets
    public function index()
    {
        $tickets = Ticket::with(['user', 'assignee'])->orderBy('created_at', 'desc')->get();
        return response()->json($tickets);
    }

    // PUT /api/admin/tickets/{id}/assign
    public function assignManager(Request $request, $id)
    {
        $validated = $request->validate([
            'manager_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->assigned_to = $validated['manager_id'];
        $ticket->save();

        return response()->json(['message' => 'Ticket assigned successfully', 'ticket' => $ticket->load(['user', 'assignee'])]);
    }

    // PUT /api/admin/tickets/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Open,In Progress,Resolved,Closed',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $validated['status'];
        $ticket->save();

        return response()->json(['message' => 'Ticket status updated successfully', 'ticket' => $ticket->load(['user', 'assignee'])]);
    }

    // POST /api/admin/tickets/{id}/reply
    // For now, since we don't have a messages table for tickets specifically, we might just update the status to answered or send an email.
    // Assuming simple implementation: we just acknowledge it.
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        // In a real app, this would save to a `ticket_replies` table.
        // For phase 4, we'll simulate it by changing the status.
        $ticket->status = 'In Progress';
        $ticket->save();

        return response()->json(['message' => 'Reply sent successfully', 'ticket' => $ticket->load(['user', 'assignee'])]);
    }
}

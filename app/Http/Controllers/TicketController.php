<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Find user's latest application if available to link the ticket
        $applicationId = $request->user()->applications()->latest()->first()?->id;

        $ticket = Ticket::create([
            'ticket_id' => 'TKT-' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'application_id' => $applicationId,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'Open',
            'priority' => 'Normal',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent. We will get back to you shortly.',
            'ticket' => $ticket
        ]);
    }
}

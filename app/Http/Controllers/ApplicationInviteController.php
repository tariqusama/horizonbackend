<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationInvite;
use App\Models\ApplicationParticipant;
use Illuminate\Support\Str;

class ApplicationInviteController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $application = Application::findOrFail($id);

        // Ensure user owns application or is admin (simplify for now, assuming auth middleware handles it)
        
        $invite = ApplicationInvite::create([
            'application_id' => $application->id,
            'email' => $request->email,
            'role' => $request->role,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        // Here we would send an email. For now, return the link.
        return response()->json([
            'message' => 'Invite created successfully',
            'invite' => $invite,
            'link' => env('FRONTEND_URL', 'http://localhost:3000') . '/invite/' . $invite->token
        ]);
    }

    public function show($token)
    {
        $invite = ApplicationInvite::with('application.user')->where('token', $token)->firstOrFail();
        
        if ($invite->status !== 'pending' || ($invite->expires_at && $invite->expires_at->isPast())) {
            return response()->json(['message' => 'Invite is invalid or expired'], 400);
        }

        return response()->json([
            'application_title' => $invite->application->title,
            'petitioner_name' => $invite->application->user->name,
            'role' => $invite->role,
            'email' => $invite->email,
        ]);
    }

    public function accept(Request $request, $token)
    {
        $invite = ApplicationInvite::where('token', $token)->firstOrFail();

        if ($invite->status !== 'pending' || ($invite->expires_at && $invite->expires_at->isPast())) {
            return response()->json(['message' => 'Invite is invalid or expired'], 400);
        }

        // Add user as participant
        ApplicationParticipant::firstOrCreate([
            'application_id' => $invite->application_id,
            'user_id' => auth()->id(),
            'role' => $invite->role,
        ]);

        // Mark invite accepted
        $invite->update(['status' => 'accepted']);

        return response()->json(['message' => 'Successfully joined the application']);
    }
}

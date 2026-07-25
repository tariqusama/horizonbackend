<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower($request->input('email'));
        $file = 'newsletter_subscribers.txt';

        $emails = [];
        if (Storage::disk('local')->exists($file)) {
            $emails = array_filter(array_map('trim', explode("\n", Storage::disk('local')->get($file))));
        }

        if (in_array($email, $emails, true)) {
            return response()->json([
                'message' => 'You are already subscribed to our newsletter.'
            ], 422);
        }

        $emails[] = $email;
        Storage::disk('local')->put($file, implode("\n", array_unique($emails)) . "\n");

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\NewsletterSubscribed());
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Illuminate\Support\Facades\Log::error('Failed to send newsletter subscription email: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Thanks for subscribing!']);
    }
}

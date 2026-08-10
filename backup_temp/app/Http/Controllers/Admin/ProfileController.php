<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // GET /api/admin/profile
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // PUT /api/admin/profile
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email_notifications' => 'sometimes|boolean',
            'sms_alerts' => 'sometimes|boolean',
            'marketing_emails' => 'sometimes|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        
        if (isset($validated['email_notifications'])) {
            $user->email_notifications = $validated['email_notifications'];
        }
        if (isset($validated['sms_alerts'])) {
            $user->sms_alerts = $validated['sms_alerts'];
        }
        if (isset($validated['marketing_emails'])) {
            $user->marketing_emails = $validated['marketing_emails'];
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}

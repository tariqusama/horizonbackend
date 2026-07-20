<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $otp = sprintf('%06d', mt_rand(100000, 999999));

        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        Mail::to($request->email)->send(new VerificationEmail($otp));

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP code'], 400);
        }

        Cache::forget('otp_' . $request->email);

        return response()->json(['message' => 'OTP verified successfully']);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'goal' => 'nullable|string',
            'plan' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        if ($request->goal) {
            $application = $user->applications()->create([
                'title' => $request->goal,
                'subtitle' => 'Plan: ' . ($request->plan ?? 'Standard'),
                'status' => 'Active',
                'progress' => 'Application received',
                'next_step' => 'Upload supporting documents',
                'receipt_number' => 'MSC-' . rand(100, 999) . '-' . rand(10000, 99999),
                'timeline' => [
                    ['step' => 'Application received', 'description' => 'USCIS has accepted your package.', 'complete' => true],
                    ['step' => 'Biometrics scheduled', 'description' => 'Waiting to schedule biometrics.', 'complete' => false],
                    ['step' => 'Evidence review', 'description' => 'Your documents will be under review.', 'complete' => false],
                    ['step' => 'Decision pending', 'description' => 'USCIS will issue a decision.', 'complete' => false]
                ]
            ]);

            $application->documents()->createMany([
                ['name' => 'Passport photo page', 'status' => 'Missing'],
                ['name' => 'Birth certificate', 'status' => 'Missing'],
                ['name' => 'Proof of residency', 'status' => 'Missing'],
                ['name' => 'Medical exam report', 'status' => 'Missing'],
                ['name' => 'Affidavit of support', 'status' => 'Missing'],
            ]);
        }

        Auth::login($user);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return response()->json(Auth::user());
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        // Determine which roles this authenticated user may assign.
        // Adjust mapping below as needed for your permission model.
        $roleName = trim((string) $user->role);
        $assignable = [];

        if (stripos($roleName, 'super') !== false || stripos($roleName, 'admin') !== false) {
            $assignable = ['Super Admin', 'Immigration Attorney', 'Case Manager', 'Printing Team'];
        } elseif (stripos($roleName, 'immigration attorney') !== false) {
            $assignable = ['Immigration Attorney', 'Case Manager'];
        } elseif (stripos($roleName, 'case manager') !== false) {
            $assignable = ['Case Manager', 'Paralegal'];
        } else {
            $assignable = [$roleName];
        }

        $payload = array_merge($user->toArray(), ['assignable_roles' => $assignable]);
        return response()->json($payload);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Middleware\AttachBearerFromCookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Mail\VerificationEmail;
use App\Mail\UserRegistered;
use App\Mail\AdminNotification;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users'
        ], [
            'email.unique' => 'Email already exists'
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
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/\d/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'goal' => 'nullable|string',
            'plan' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'addons' => 'nullable|array'
        ], [
            'email.unique' => 'Email already exists',
        ]);

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        try {
            Mail::to($user->email)->send(new UserRegistered($user));
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new AdminNotification('New User Registered', 'A new user has registered: ' . $user->name));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration email failed: ' . $e->getMessage());
        }

        $addonsString = '';
        if ($request->has('addons') && is_array($request->addons)) {
            $addonItems = [];
            foreach ($request->addons as $addon) {
                if (is_array($addon)) {
                    $qty = isset($addon['quantity']) && $addon['quantity'] > 1 ? ' (Qty: ' . $addon['quantity'] . ')' : '';
                    $name = $addon['name'] ?? 'Unknown Addon';
                    $addonItems[] = $name . $qty;
                } else {
                    $addonItems[] = $addon;
                }
            }
            if (count($addonItems) > 0) {
                $addonsString = ' | Addons: ' . implode(', ', $addonItems);
            }
        }

        if ($request->goal) {
            $application = $user->applications()->create([
                'title' => $request->goal,
                'amount' => $request->amount,
                'subtitle' => 'Plan: ' . ($request->plan ?? 'Standard') . $addonsString,
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

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $token = $user->createToken('spa-token')->plainTextToken;

        return $this->authResponse($user, $token);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('spa-token')->plainTextToken;

        return $this->authResponse($user, $token);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'Logged out successfully'])
            ->withCookie($this->forgetAuthCookie());
    }

    private function authResponse(User $user, string $token)
    {
        return response()->json(array_merge($user->toArray(), ['token' => $token]))
            ->withCookie($this->makeAuthCookie($token));
    }

    private function makeAuthCookie(string $token)
    {
        $isSecure = request()->isSecure() || app()->environment('production');
        return Cookie::make(
            AttachBearerFromCookie::COOKIE,
            $token,
            60 * 24 * 30,
            '/',
            null,
            $isSecure,
            true,
            false,
            $isSecure ? 'None' : 'Lax'
        );
    }

    private function forgetAuthCookie()
    {
        $isSecure = request()->isSecure() || app()->environment('production');
        return Cookie::make(
            AttachBearerFromCookie::COOKIE,
            '',
            -60,
            '/',
            null,
            $isSecure,
            true,
            false,
            $isSecure ? 'None' : 'Lax'
        );
    }

    public function sendPasswordResetLink(Request $request)
    {
        $email = $request->input('email');

        if (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email format'], 400);
        }

        if (!User::where('email', $email)->exists()) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        Password::sendResetLink(['email' => $email]);

        return response()->json([
            'message' => __('If your email is registered, a password reset link has been sent.'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status)], 400);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        // Determine which roles this authenticated user may assign.
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

        return response()->json(array_merge($user->toArray(), [
            'assignable_roles' => $assignable,
        ]));
    }
}

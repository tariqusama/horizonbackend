<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\PaymentReceived;
use App\Mail\AdminNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'email' => 'required|email',
            'plan' => 'required|string',
            'goal' => 'required|string',
            'payment_method_id' => 'nullable|string',
        ]);

        try {
            $stripeSecret = env('STRIPE_SECRET');
            if (!$stripeSecret) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stripe secret key is not configured.'
                ], 500);
            }

            \Stripe\Stripe::setApiKey($stripeSecret);
            $amountInCents = intval($request->amount * 100);

            if ($request->has('payment_method_id') && $request->payment_method_id) {
                // Process via PaymentIntent (for native CardElement integration)
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => 'usd',
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/dashboard?payment=success',
                ]);

                if ($paymentIntent->status == 'requires_action' &&
                    $paymentIntent->next_action->type == 'use_stripe_sdk') {
                    return response()->json([
                        'status' => 'requires_action',
                        'client_secret' => $paymentIntent->client_secret
                    ]);
                } else if ($paymentIntent->status == 'succeeded') {
                    return response()->json(['status' => 'success']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Invalid PaymentIntent status']);
                }
            } else {
                // Process via Checkout Session (for redirect-based integration)
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'customer_email' => $request->email,
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $request->plan,
                                'description' => $request->goal,
                            ],
                            'unit_amount' => $amountInCents,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/dashboard?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/dashboard',
                ]);

                return response()->json([
                    'status' => 'success',
                    'url' => $session->url
                ]);
            }
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not connect to Stripe.'
            ], 502);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function verifyPayment(Request $request)
    {
        $application = $request->user()->applications()->latest()->first();
        if ($application) {
            $application->paid_amount = $application->amount;
            if ($application->status === 'Pending') {
                $application->status = 'In Progress';
                $application->progress = 'Documents Needed';
            }
            $application->save();

            try {
                Mail::to($request->user()->email)->send(new PaymentReceived($application, $application->amount));
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new AdminNotification('Payment Received', 'A payment of $' . number_format($application->amount, 2) . ' was received from ' . $request->user()->name));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Payment email failed: ' . $e->getMessage());
            }
        }
        return response()->json(['status' => 'success']);
    }
}

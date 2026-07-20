<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'email' => 'required|email',
            'plan' => 'required|string',
            'goal' => 'required|string'
        ]);

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // The amount is received in dollars, convert to cents
            $amountInCents = intval($request->amount * 100);

            // Add addons info to line items if needed, but for simplicity we just pass one line item
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
                'cancel_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/signup',
            ]);

            return response()->json([
                'status' => 'success',
                'url' => $session->url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

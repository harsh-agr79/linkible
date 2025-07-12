<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1', // cents/paise
            'email' => 'nullable|email',
        ]);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Package Payment'],
                    'unit_amount' => $request->amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $request->email,
            'success_url' => 'https://linkible.vercel.app/payment/success',
            'cancel_url' => 'https://linkible.vercel.app/payment/cancel',
        ]);

        // Optional: store by session ID instead (not intent yet)
        Payment::updateOrCreate(
            ['session_id' => $session->id],
            [
                'status' => 'pending',
                'amount' => $request->amount,
                'currency' => 'usd',
                'email' => $request->email,
                'meta' => $session->toArray(),
            ]
        );

        return response()->json(['sessionId' => $session->id, 'session'=> $session]);
    }



    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid webhook signature.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $paymentIntentId = $session->payment_intent;

            // Update the record using session_id
            Payment::updateOrCreate(
                ['session_id' => $session->id],
                [
                    'payment_intent_id' => $paymentIntentId,
                    'status' => 'succeeded',
                    'email' => $session->customer_email,
                    'meta' => json_encode($session),
                ]
            );
        }

        return response('Webhook handled.', 200);
    }

}

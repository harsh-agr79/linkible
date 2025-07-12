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
            'amount' => 'required|integer|min:1',
            'email' => 'nullable|email',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Package Payment',
                    ],
                    'unit_amount' => $request->amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $request->email,
            'success_url' => 'https://linkible.vercel.app/payment/success',
            'cancel_url' => 'https://linkible.vercel.app/payment/cancel',
        ]);

        // Store session record
        Payment::updateOrCreate(
            ['payment_intent_id' => $session->payment_intent],
            [
                'status' => 'pending',
                'amount' => $request->amount,
                'currency' => 'usd',
                'email' => $request->email,
                'meta' => $session->toArray(),
            ]
        );

        return response()->json(['sessionId' => $session->id]);
    }


    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid webhook signature.', 400);
        }

        $intent = $event->data->object;

        if (in_array($event->type, ['payment_intent.succeeded', 'payment_intent.payment_failed'])) {
            Payment::updateOrCreate(
                ['payment_intent_id' => $intent->id],
                [
                    'status' => $intent->status,
                    'amount' => $intent->amount,
                    'currency' => $intent->currency,
                    'email' => $intent->receipt_email ?? null,
                    'meta' => json_encode($intent),
                ]
            );
        }

        return response('Webhook handled.', 200);
    }
}

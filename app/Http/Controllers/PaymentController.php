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
            'amount' => 'required|integer|min:1', // in paise
            'email' => 'nullable|email',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'USD',
            'automatic_payment_methods' => ['enabled' => true],
            'receipt_email' => $request->email,
        ]);

        // Store initial record as pending or processing
        Payment::updateOrCreate(
            ['payment_intent_id' => $intent->id],
            [
                'status' => $intent->status,
                'amount' => $intent->amount,
                'currency' => $intent->currency,
                'email' => $intent->receipt_email,
                'meta' => $intent->toArray(),
            ]
        );

        return response()->json(['clientSecret' => $intent->client_secret]);
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

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'pricing_id' => 'required|exists:pricings,id',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
            'contact' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'company' => 'nullable|string',
            'website' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        // Step 1: Get pricing and calculate Stripe amount in cents
        $pricing = Pricing::findOrFail($request->pricing_id);
        $amountInCents = intval($pricing->price * 100); // Stripe expects amount in cents

        Stripe::setApiKey(config('services.stripe.secret'));

        // Step 2: Generate unique order_id
        $orderId = strtoupper(Str::uuid()->toString());

        // Step 3: Create Checkout Session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $pricing->title ?? 'Package Payment',
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'client_reference_id' => $orderId,
            'customer_email' => $request->email,
            'success_url' => 'https://linkible.vercel.app/payment/success?order_id=' . $orderId,
            'cancel_url' => 'https://linkible.vercel.app/payment/cancel',
        ]);

        // Step 4: Store payment record
        Payment::updateOrCreate(
            ['session_id' => $session->id],
            [
                'order_id' => $orderId,
                'status' => 'pending',
                'amount' => $amountInCents,
                'currency' => 'usd',
                'email' => $request->email,
                'name' => $request->name,
                'contact' => $request->contact,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'company' => $request->company,
                'website' => $request->website,
                'message' => $request->message,
                'pricing_id' => $pricing->id,
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
            Log::warning('Stripe webhook signature invalid: ' . $e->getMessage());
            return response('Invalid webhook signature.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $paymentIntentId = $session->payment_intent;

            // Update existing payment record by session_id
            $payment = Payment::where('session_id', $session->id)->first();

            if ($payment) {
                $payment->update([
                    'payment_intent_id' => $paymentIntentId,
                    'status' => 'succeeded',
                    'email' => $session->customer_email,
                    'meta' => json_encode($session),
                ]);
            } else {
                Log::warning("No payment found for session_id: {$session->id}");
            }
        }

        return response('Webhook handled.', 200);
    }

    public function getOrderDetails(Request $request,$orderid){
        $payment = Payment::where('order_id', $orderid)->first();
        return response()->json($payment, 200);
    }
}
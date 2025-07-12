<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;

class UpdateStripePaymentStatuses extends Command
{
   
    protected $signature = 'stripe:update-statuses';
    protected $description = 'Auto-update the status of failed or incomplete Stripe payments.';

   public function handle(): int
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payments = Payment::whereIn('status', [
            'failed',
            'requires_payment_method',
            'requires_confirmation',
            'processing'
        ])->where('created_at', '>=', now()->subDays(7))->get();

        foreach ($payments as $payment) {
            $intentId = $payment->payment_intent_id;

            // Try to recover from meta if intent_id is missing
            if (!$intentId && !empty($payment->meta)) {
                $meta = is_array($payment->meta) ? $payment->meta : json_decode($payment->meta, true);
                $intentId = $meta['payment_intent'] ?? null;
            }

            if (!$intentId) {
                Log::warning("Skipping payment ID {$payment->id}: missing payment_intent_id.");
                continue;
            }

            try {
                $intent = PaymentIntent::retrieve($intentId);
                $newStatus = $intent->status;

                if ($newStatus !== $payment->status) {
                    $payment->update([
                        'status' => $newStatus,
                        'payment_intent_id' => $intentId, // Safe to set again
                        'meta' => $intent->toArray(),
                    ]);
                    Log::info("Updated payment {$intentId} to status: {$newStatus}");
                }
            } catch (\Exception $e) {
                Log::warning("Error checking payment intent {$intentId}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }

}

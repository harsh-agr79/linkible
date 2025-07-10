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

        $payments = Payment::whereIn('status', ['failed', 'requires_payment_method', 'requires_confirmation', 'processing'])->get();

        foreach ($payments as $payment) {
            try {
                $intent = PaymentIntent::retrieve($payment->payment_intent_id);
                $newStatus = $intent->status;

                if ($newStatus !== $payment->status) {
                    $payment->update([
                        'status' => $newStatus,
                        'meta' => $intent->toArray(),
                    ]);
                    Log::info("Updated payment {$payment->payment_intent_id} to status: {$newStatus}");
                }
            } catch (\Exception $e) {
                Log::warning("Error checking {$payment->payment_intent_id}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}

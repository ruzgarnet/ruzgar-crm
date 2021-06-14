<?php

namespace App\Models\Generators;

use App\Classes\Generator;
use App\Models\SubscriptionChange;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Generate data for service change
 */
trait SubscriptionChangeGenerator
{
    /**
     * Insert new sub and return
     *
     * @param \App\Models\Subscription $subscription
     * @param array $data
     * @return \App\Models\Subscription
     */
    public static function getChangedSubscription(Subscription $subscription, array $data)
    {
        return Subscription::create([
            'staff_id' => $data['staff_id'],
            'service_id' => $data['service_id'],
            'customer_id' => $subscription->customer_id,
            'subscription_no' => Generator::subscriptionNo(),
            'bbk_code' => $subscription->bbk_code,
            'commitment' => $subscription->commitment,
            'start_date' => Carbon::parse($data['date'])->format('Y-m-d'),
            'end_date' => $subscription->end_date,
            'price' => $data['price'],
            'payment' => $data['payment'],
            'options' => [
                'changed_service' => true
            ],
            'approved_at' => DB::raw('current_timestamp()'),
            'status' => 1
        ]);
    }

    /**
     * Insert changed rows info to change_subscriptions table
     *
     * @param \App\Models\Subscription $subscription Old Subscription
     * @param \App\Models\Subscription $changedSubscription New Subscription
     * @return void
     */
    public static function addChangedRow(Subscription $subscription, Subscription $changedSubscription)
    {
        return SubscriptionChange::create([
            'subscription_id' => $subscription->id,
            'changed_id' => $changedSubscription->id,
            'staff_id' => $changedSubscription->staff_id,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'commitment' => ($subscription->commitment > 0) ? $subscription->commitment - Carbon::parse($subscription->end_date)->diffInMonths('now') : null,
            'price' => $changedSubscription->price,
            'payment' => $changedSubscription->payment
        ]);
    }

    /**
     * Get payments for new subscription
     *
     * @param \App\Models\Subscription $subscription
     * @param integer $changed_id
     * @param float $price
     * @return array
     */
    public static function getChangedPayments(Subscription $subscription, int $changed_id, float $price)
    {
        $payments = Payment::where('subscription_id', $subscription->id)
            ->whereNull('paid_at')
            ->orderBy('date', 'asc')
            ->get();

        $data = [];
        foreach ($payments as $payment) {
            $data[] = [
                'subscription_id' => $changed_id,
                'date' => $payment->date,
                'price' => $payment->price - $subscription->price + $price
            ];
        }

        return $data;
    }

    /**
     * Delete old sub's not paided payments
     *
     * @param integer $subscription_id
     * @return void
     */
    public static function deleteChangedPayments(int $subscription_id)
    {
        return Payment::where('subscription_id', $subscription_id)
            ->whereNull('paid_at')
            ->orderBy('date', 'asc')
            ->delete();
    }
}

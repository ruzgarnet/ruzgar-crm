<?php

namespace App\Models\Generators;

use App\Classes\Generator;
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
     * @param array $data
     * @return \App\Models\Subscription
     */
    public function getChangedSubscription(array $data)
    {
        return self::create([
            'staff_id' => $data['staff_id'],
            'service_id' => $data['service_id'],
            'customer_id' => $this->customer_id,
            'subscription_no' => Generator::subscriptionNo(),
            'bbk_code' => $this->bbk_code,
            'commitment' => $this->commitment,
            'start_date' => Carbon::parse($data['date'])->format('Y-m-d'),
            'end_date' => $this->end_date,
            'price' => $data['price'],
            'payment' => $data['payment'],
            'options' => [
                'changed_service' => true
            ],
            'approved_at' => DB::raw('current_timestamp()')
        ]);
    }

    /**
     * Insert changed rows info to change_subscriptions table
     *
     * @param \App\Models\Subscription $sub New subscription
     * @return void
     */
    public function addChangedRow(Subscription $sub)
    {
        return DB::table('change_subscriptions')->insert([
            'subscription_id' => $this->id,
            'changed_id' => $sub->id,
            'staff_id' => $sub->staff_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'commitment' => ($this->commitment > 0) ? $this->commitment - Carbon::parse($this->end_date)->diffInMonths('now') : null,
            'price' => $sub->price,
            'payment' => $sub->payment
        ]);
    }

    /**
     * Get payments for new subscription
     *
     * @param integer $subscription_id
     * @param float $price
     * @return array
     */
    public function getChangedPayments(int $subscription_id, float $price)
    {
        $payments = Payment::where('subscription_id', $this->id)
            ->whereNull('paid_at')
            ->orderBy('date', 'asc')
            ->get();

        $data = [];
        foreach ($payments as $payment) {
            $data[] = [
                'subscription_id' => $subscription_id,
                'date' => $payment->date,
                'price' => $payment->price - $this->price + $price
            ];
        }

        return $data;
    }

    /**
     * Delete old sub's not paided payments
     *
     * @return void
     */
    public function deleteChangedPayments()
    {
        return Payment::where('subscription_id', $this->id)
            ->whereNull('paid_at')
            ->orderBy('date', 'asc')
            ->delete();
    }
}

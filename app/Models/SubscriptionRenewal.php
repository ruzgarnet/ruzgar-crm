<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRenewal extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Subscripiton relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Staff relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Renewal subscription
     *
     * @param  \App\Models\Subscription $subscription
     * @param  int $staff_id
     * @param  float $price
     * @return \App\Models\SubscriptionRenewal
     */
    public static function renewal(Subscription $subscription, int $staff_id, float $price)
    {
        self::where('status', 0)->where('subscription_id', $subscription->id)->update(['status' => 2]);

        return self::create([
            'subscription_id' => $subscription->id,
            'staff_id' => $staff_id,
            'new_price' => $price,
            'status' => 0
        ]);
    }
}

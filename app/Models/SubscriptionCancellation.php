<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionCancellation extends Model
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
     * Cancel subscription and delete next payments
     *
     * @param \App\Models\Subscription $subscription
     * @param array $data
     * @return boolean
     */
    public static function cancel(Subscription $subscription, array $data)
    {
        DB::beginTransaction();
        try {
            self::create($data);

            $subscription->end_date = Carbon::now()->format('Y-m-d');
            $subscription->status = 3;
            $subscription->save();

            Reference::cancel($subscription, $data['staff_id']);

            Payment::where('subscription_id', $subscription->id)
                ->whereNull('paid_at')
                ->delete();

            $subscription->freezes()->whereNull('unfreezed_at')->update(['unfreezed_at' => DB::raw('current_timestamp()')]);
            $subscription->sales()->whereNull('disabled_at')->update(['disabled_at' => DB::raw('current_timestamp()')]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

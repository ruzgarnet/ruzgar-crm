<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubscriptionFreeze extends Model
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
     * Freeze subscription and edit next payment
     *
     * @param \App\Models\Subscription $subscription
     * @param array $data
     * @return boolean
     */
    public static function freeze(Subscription $subscription, array $data)
    {
        DB::beginTransaction();
        try {
            self::create($data);

            $subscription->status = 4;
            $subscription->save();

            $payment = $subscription->nextPayment();

            $new_price = $payment->price / 2;

            PaymentPriceEdit::create([
                'payment_id' => $payment->id,
                'old_price' => $payment->price,
                'new_price' => $new_price,
                'description' => trans('response.system.freezing')
            ]);

            $payment->price = $new_price;
            $payment->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * UnFreeze subscription and edit next payment
     * @param \App\Models\Subscription $subscription
     * @param int $staff_id
     * @return boolean
     */
    public static function unFreeze(Subscription $subscription, int $staff_id)
    {
        DB::beginTransaction();
        try {
            $subscription->status = 1;
            $subscription->save();

            $freezes = $subscription->freezes()->whereNull('unfreezed_at')->get();

            foreach ($freezes as $freeze) {
                $freeze->unfreeze_staff = $staff_id;
                $freeze->unfreezed_at = DB::raw('current_timestamp()');
                $freeze->save();
            }

            $payment = $subscription->nextPayment();

            $new_price = $payment->price * 2;

            PaymentPriceEdit::create([
                'payment_id' => $payment->id,
                'old_price' => $payment->price,
                'new_price' => $new_price,
                'description' => trans('response.system.unfreezing')
            ]);

            $payment->price = $new_price;
            $payment->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return false;
        }
    }
}

<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentDelete extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array'
    ];

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
     * Delete payment and add log
     *
     * @param \App\Models\Payment $payment
     * @param array $data
     * @return boolean
     */
    public static function deletePayment(Payment $payment, array $data)
    {
        DB::beginTransaction();
        try {
            $data['subscription_id'] = $payment->subscription_id;
            $data['values'] = $payment;
            self::create($data);

            $payment->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

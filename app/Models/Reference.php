<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reference extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

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
     * Reference subscription relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reference()
    {
        return $this->belongsTo(Subscription::class, 'reference_id');
    }

    /**
     * Referenced subscription relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referenced()
    {
        return $this->belongsTo(Subscription::class, 'referenced_id');
    }

    /**
     * Add reference and change payment price
     *
     * @param array $data
     * @return boolean
     */
    public static function add_reference(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            self::insert($data);

            $subscription = Subscription::find($data['reference_id']);
            $payment = $subscription->currentPayment();

            $reference_price = $payment->price - $subscription->price + setting('reference.price', 10);

            EditPayment::create([
                'payment_id' => $payment->id,
                'staff_id' => $data['staff_id'],
                'old_price' => $payment->price,
                'new_price' => $reference_price,
                'description' => trans('response.system.referenced')
            ]);

            $payment->price = $reference_price;
            $payment->save();

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $success = false;
        }

        return $success;
    }
}

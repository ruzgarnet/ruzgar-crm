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
     * Get reference status
     * For titles use language files => tables.{table_name|model_name}.types.{type_id}
     *
     * 1 => Defined         - Tanımlandı
     * 2 => Confirmed       - Onaylandı
     * 3 => Canceled        - İptal Edildi
     * 4 => Sub Canceled    - Abonelik İptali
     * 5 => Service Changed - Tarife Değişimi
     *
     * @param boolean $implode
     * @return array|string
     */
    public static function getStatus($implode = false)
    {
        $data = [1, 2, 3, 4, 5];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Edit reference and change payment price
     *
     * @param array $data
     * @return boolean
     */
    public function edit_reference(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['status'] == 2) {
                $subscription = $this->reference;
                $payment = $subscription->nextPayment();

                $reference_discount = setting('reference.price', 9.9);
                if (!is_numeric($reference_discount)) {
                    $reference_discount = 9.9;
                }

                $service_price = $subscription->getValue('service_price', $subscription->price);

                $reference_price = $payment->price - $service_price + $reference_discount;
                if($reference_price <= 0)
                    $reference_price = $reference_discount;

                PaymentPriceEdit::create([
                    'payment_id' => $payment->id,
                    'staff_id' => $data['staff_id'],
                    'old_price' => $payment->price,
                    'new_price' => $reference_price,
                    'description' => trans('response.system.referenced', ['price' => $reference_price])
                ]);

                $payment->price = $reference_price;
                $payment->save();
            }

            $this->status = $data['status'];
            $this->staff_id = $data['staff_id'];
            $this->decided_at = DB::raw('current_timestamp()');
            $this->save();

            SentMessage::insert([
                'customer_id' => $this->reference->customer_id,
                'message_id' => 17,
                'staff_id' => request()->user()->staff_id,
            ]);

            SentMessage::insert([
                'customer_id' => $this->referenced->customer_id,
                'message_id' => 18,
                'staff_id' => request()->user()->staff_id,
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Cancel references when subscription canceled
     *
     * @param \App\Models\Subscription $subscription
     * @param int $staff_id
     * @return boolean
     */
    public static function cancel(Subscription $subscription, int $staff_id)
    {
        return self::where('status', 1)
            ->where(function ($query) use ($subscription) {
                $query->where('reference_id', $subscription->id)
                    ->orWhere('referenced_id', $subscription->id);
            })
            ->update([
                'status' => 5,
                'staff_id' => $staff_id,
                'decided_at' => DB::raw('current_timestamp()')
            ]);
    }

    /**
     * Change reference subscription when subscription service changed
     *
     * @param int $old_id
     * @param int $new_id
     * @return boolean
     */
    public static function change(int $old_id, int $new_id)
    {
        DB::beginTransaction();
        try {
            // Reference - Referans Olan
            self::where('status', 1)
                ->where('reference_id', $old_id)
                ->update([
                    'reference_id' => $new_id
                ]);

            // Referenced - Referans Olarak Gelen (Yeni gelen)
            self::where('status', 1)
                ->where('referenced_id', $old_id)
                ->update([
                    'referenced_id' => $new_id
                ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

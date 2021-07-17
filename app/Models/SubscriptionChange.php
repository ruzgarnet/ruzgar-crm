<?php

namespace App\Models;

use App\Models\Generators\SubscriptionChangeGenerator;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionChange extends Model
{
    use HasFactory, SubscriptionChangeGenerator;

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
     * Change service
     *
     * Create new sub, generate new payments, delete old payments, insert changed info
     *
     * @param \App\Models\Subscription $subscription
     * @param array $data
     * @return boolean
     */
    public static function change(Subscription $subscription, array $data)
    {
        DB::beginTransaction();
        try {
            $changedSubscription = self::getChangedSubscription($subscription, $data);

            self::addChangedRow($subscription, $changedSubscription);

            $payments = self::getChangedPayments($subscription, $changedSubscription->id, $data['price']);
            self::deleteChangedPayments($subscription->id);

            foreach ($payments as $payment) {
                Payment::insert($payment);
            }

            $subscription->end_date = Carbon::parse($data['date'])->format('Y-m-d');
            $subscription->status = 2;
            $subscription->save();

            Reference::change($subscription->id, $changedSubscription->id);


            $sales = MokaSale::where('subscription_id', $subscription->id)->whereNull('disabled_at')->get();
            if ($sales->count()) {
                foreach ($sales as $sale) {
                    $sale->disabled_at = DB::raw('current_timestamp()');
                    $sale->save();
                }
                $sale = $sales->last();

                MokaSale::create([
                    'subscription_id' => $changedSubscription->id,
                    'moka_customer_id' => $sale->moka_customer_id,
                    'moka_sale_id' => $sale->moka_sale_id,
                    'moka_card_token' => $sale->moka_card_token
                ]);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getDescriptionAttribute()
    {
        return 'Abonelik başlangıç tarihi:' . convert_date($this->start_date, 'mask') . ' bitiş tarihi:' . convert_date($this->end_date, 'mask') . ' taahhüt:' . $this->commitment . ' ücreti:' . print_money($this->price) . ' extra ödeme:' . print_money($this->payment);
    }
}

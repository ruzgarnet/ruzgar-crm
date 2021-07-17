<?php

namespace App\Models;

use App\Classes\Telegram;
use App\Models\Attributes\PaidAtAttribute;
use App\Models\Attributes\PaymentCategoryAttribute;
use App\Models\Attributes\PaymentDateAttribute;
use App\Models\Attributes\PriceAttribute;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory, PriceAttribute, PaymentDateAttribute, PaidAtAttribute, PaymentCategoryAttribute;

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
     * Moka Payment relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function mokaPayment()
    {
        return $this->hasOne(MokaPayment::class);
    }

    /**
     * Moka Auto Payment relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function mokaAutoPayment()
    {
        return $this->hasOne(MokaAutoPayment::class);
    }

    /**
     * Moka Logs relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function mokaLogs()
    {
        return $this->hasMany(MokaLog::class);
    }

    /**
     * Edit Payments relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function editPayments()
    {
        return $this->hasMany(EditPayment::class);
    }

    /**
     * Get payment type id's
     * For titles use language files => tables.{table_name|model_name}.types.{type_id}
     *
     * 1 => Cash                        - Nakit
     * 2 => Credit/Bank Cart (Pos)      - Kredi/Banka Kartı (Pos)
     * 3 => Transfer/EFT                - Havale/EFT
     * 4 => Credit/Bank Cart (Online)   - Kredi/Banka Kartı (Online)
     * 5 => Auto Payment                - Otomatik Ödeme
     *
     * @param bool $implode
     * @return array|string
     */
    public static function getTypes($implode = false)
    {
        $data = [1, 2, 3, 4, 5];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Get category status id's
     * For titles use language files => tables.{table_name|model_name}.status.{status_id}
     *
     * 1 => Defined                 - Sisteme Tanımlandı
     * 2 => Receipted               - Ödeme Alındı
     * 3 => Error While Receipting  - Ödeme Alınırken Hata Oluştu
     *
     * @param bool $implode
     * @return array
     */
    public static function getStatus($implode = false)
    {
        $data = [1, 2, 3];
        return $implode ? implode(',', $data) : $data;
    }

    /**
     * Check paided
     *
     * @return boolean
     */
    public function isPaid()
    {
        return !($this->type == NULL || $this->paid_at == NULL);
    }

    /**
     * Receive payment
     *
     * @param array $data
     * @return void
     */
    public function receive(array $data)
    {
        if (!$this->isPaid()) {
            DB::beginTransaction();
            try {
                $date = Carbon::parse($this->date);
                $month = Carbon::now()->format('m');

                $this->type = $data['type'];
                $this->status = 2;
                $this->paid_at = DB::raw('current_timestamp()');

                $this->save();

                $count = self::where('subscription_id', $this->subscription_id)
                    ->whereNull('paid_at')
                    ->whereDate(
                        'date',
                        '>',
                        Carbon::parse('last day of this month')->format('Y-m-d')
                    )
                    ->count();

                if ($this->subscription->isFreezed()) {
                    if ($count > 1) {
                        // If new payment exists change price for freeze
                        $next_payment = $this->subscription->currentPayment();
                        $old_price = $next_payment->price;
                        $next_payment->price = $old_price / 2;
                        $new_price = $old_price / 2;
                        $next_payment->save();
                    } else {
                        // If new payment not exists add new payment for freeze
                        $old_price = $this->subscription->price;
                        $new_price = $old_price / 2;

                        $next_payment = self::create([
                            'subscription_id' => $this->subscription_id,
                            'status' => 1,
                            'date' => $date->addMonth(1),
                            'price' => $new_price
                        ]);
                    }

                    PaymentPriceEdit::create([
                        'payment_id' => $next_payment->id,
                        'old_price' => $old_price,
                        'new_price' => $new_price,
                        'description' => trans('response.system.price_freezed')
                    ]);
                } else if ($count < 1 && $this->subscription->isActive()) {
                    self::create([
                        'subscription_id' => $this->subscription_id,
                        'status' => 1,
                        'date' => $date->addMonth(1),
                        'price' => $this->subscription->price
                    ]);
                }

                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();

                Telegram::send(
                    "Test",
                    "Payment Model, receive method error : " . $e->getMessage()
                );

                return false;
            }
        }
    }

    /**
     * Edit payment price
     *
     * @param array $data
     * @return void
     */
    public function edit_price(array $data)
    {
        DB::beginTransaction();
        try {
            if (PaymentPriceEdit::create($data)) {
                $this->price = $data['new_price'];
                $this->save();
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

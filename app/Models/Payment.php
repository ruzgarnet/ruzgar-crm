<?php

namespace App\Models;

use App\Models\Attributes\DateAttribute;
use App\Models\Attributes\PaidAtAttribute;
use App\Models\Attributes\PriceAttribute;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory, PriceAttribute, DateAttribute, PaidAtAttribute;

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
     * 2 => Transfer/EFT                - Havale/EFT
     * 3 => Credit/Bank Cart (Online)   - Kredi/Banka Kartı (Online)
     * 4 => Auto Payment                - Otomatik Ödeme
     *
     * @param bool $implode
     * @return array
     */
    public static function getTypes($implode = false)
    {
        $data = [1, 2, 3, 4];
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
     * Receive payment
     *
     * @param array $data
     * @return void
     */
    public function receive_payment(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            $date = Carbon::parse($this->date);
            $month = Carbon::now()->format('m');

            // TODO remove env conditions for product
            if (env('APP_ENV') === 'local' || $date->format('m') == $month) {
                $this->type = $data['type'];
                $this->status = 2;
                $this->paid_at = DB::raw('current_timestamp()');
            }

            if ($this->save()) {
                $count = $this
                    ->where('subscription_id', $this->subscription_id)
                    ->whereNull('paid_at')
                    ->whereDate(
                        'date',
                        '>',
                        Carbon::parse('last day of this month')->format('Y-m-d')
                    )
                    ->count();

                if ($count < 1) {
                    $this->insert([
                        'subscription_id' => $this->subscription_id,
                        'status' => 1,
                        'date' => $date->addMonth(1),
                        'price' => $this->subscription->price
                    ]);
                }
            }

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }

    /**
     * Edit payment price
     *
     * @param array $data
     * @return void
     */
    public function edit_price(array $data)
    {
        $success = false;

        DB::beginTransaction();
        try {
            if (EditPayment::create($data)) {
                $this->price = $data['new_price'];
                $this->save();
            }

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
        }

        return $success;
    }
}

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
    public function moka_payment()
    {
        return $this->hasOne(MokaPayment::class);
    }

    /**
     * Moka Logs relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function moka_logs()
    {
        return $this->hasMany(MokaLog::class);
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
            $month = Carbon::now()->format("m");

            if ($date->format("m") == $month) {
                $this->type = $data["type"];
                $this->status = 2;
                $this->paid_at = DB::raw('current_timestamp()');
            }

            if ($this->save()) {
                $count = $this->whereDate('date', ">", Carbon::parse("last day of this month")->format('Y-m-d'))->count();

                if ($count < 1) {
                    $this->insert([
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
            echo $e->getMessage();
        }

        return $success;
    }
}

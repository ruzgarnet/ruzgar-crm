<?php

namespace App\Models;

use App\Models\Attributes\DateAttribute;
use App\Models\Attributes\PaidAtAttribute;
use App\Models\Attributes\PriceAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * 2 => Error While Receipting  - Ödeme Alınırken Hata Oluştu
     *
     * @param bool $implode
     * @return array
     */
    public static function getStatus($implode = false)
    {
        $data = [1, 2, 3];
        return $implode ? implode(',', $data) : $data;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MokaLog extends Model
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
        'response' => 'array'
    ];

    /**
     * Payment Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Moka Log types
     *
     * 1 => - Online Satış 3D
     * 2 => - Online Satış Cevabı
     * 3 => - Otomatik Ödeme Tanımlama
     * 4 => - Ödeme Planı Ekleme
     * 5 => - Ödeme Planı Cevabı
     * 6 => - Online Satış 3D Hata
     * 7 => - Provizyon Çekimi
     *
     * @param boolean $implode
     * @return array|string
     */
    public static function getType($implode = false)
    {
        $data = [1, 2, 3, 4, 5, 6];
        return $implode ? implode(',', $data) : $data;
    }
}

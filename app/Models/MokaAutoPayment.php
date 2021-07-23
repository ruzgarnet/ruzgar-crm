<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MokaAutoPayment extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Payment relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Sale relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo(MokaSale::class, 'sale_id');
    }

    /**
     * Moka Refund relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refund()
    {
        return $this->hasOne(MokaRefund::class, 'auto_payment_id');
    }

    /**
     * Check refund
     *
     * @return boolean
     */
    public function isRefund()
    {
        return $this->refund->count() > 0 ? true : false;
    }
}

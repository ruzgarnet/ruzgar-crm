<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPriceEdit extends Model
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
     * Staff relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

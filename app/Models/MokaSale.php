<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MokaSale extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Subscription relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}

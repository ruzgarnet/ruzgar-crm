<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    use HasFactory;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'customer_info';

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Customer main relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function main()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * City relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * District relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

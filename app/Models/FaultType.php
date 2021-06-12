<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaultType extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Fault Record Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faultRecords()
    {
        return $this->hasMany(FaultRecord::class);
    }
}

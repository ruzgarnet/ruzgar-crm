<?php

namespace App\Models;

use App\Models\Attributes\FullNameAttribute;
use App\Models\Attributes\PersonSelectPrintAttribute;
use App\Models\Attributes\PhoneAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory, PhoneAttribute, FullNameAttribute, PersonSelectPrintAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Dealer relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Customer relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}

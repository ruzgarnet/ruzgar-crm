<?php

namespace App\Models;

use App\Models\Attributes\PriceAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, PriceAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];
}

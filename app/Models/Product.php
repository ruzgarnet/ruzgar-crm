<?php

namespace App\Models;

use App\Models\Attributes\PriceAttribute;
use App\Models\Attributes\ProductSelectPrintAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, PriceAttribute, ProductSelectPrintAttribute;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get product status id's
     * For titles use language files => tables.{table_name|model_name}.status.{status_id}
     * 1 => Active
     * 2 => Disabled
     *
     * @param bool $implode
     * @return array
     */
    public static function getStatus($implode = false)
    {
        $data = [1];
        return $implode ? implode(',', $data) : $data;
    }
}

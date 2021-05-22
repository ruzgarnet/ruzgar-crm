<?php

namespace App\Models\Attributes;

/**
 * price_print attribute
 */
trait PriceAttribute
{
    /**
     * Return formatted price with currency for print
     *
     * @return string
     */
    public function getPricePrintAttribute()
    {
        return number_format($this->price, 2, ",", ".") . "₺";
    }
}

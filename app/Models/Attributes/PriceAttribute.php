<?php

namespace App\Models\Attributes;

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

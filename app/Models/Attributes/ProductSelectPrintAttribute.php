<?php

namespace App\Models\Attributes;

/**
 * price_print attribute
 */
trait ProductSelectPrintAttribute
{
    /**
     * Return formatted price with currency for print
     *
     * @return string
     */
    public function getSelectPrintAttribute()
    {
        return "{$this->name} - {$this->price_print}";
    }
}

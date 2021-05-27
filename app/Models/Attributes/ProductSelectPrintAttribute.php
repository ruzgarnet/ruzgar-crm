<?php

namespace App\Models\Attributes;

/**
 * select_print attribute
 */
trait ProductSelectPrintAttribute
{
    /**
     * Return formatted name and price with currency for print select option
     *
     * @return string
     */
    public function getSelectPrintAttribute()
    {
        return "{$this->name} - {$this->price_print}";
    }
}

<?php

namespace App\Models\Attributes;

/**
 * select_print attribute for subscription
 */
trait SubscriptionSelectPrintAttribute
{
    /**
     * Return formatted name and secret identification number for print select option
     *
     * @return string
     */
    public function getSelectPrintAttribute()
    {
        return "{$this->customer->full_name} - {$this->service->name} - {$this->price_print}";
    }
}

<?php

namespace App\Models\Attributes;

/**
 * reference_print attribute for subscription
 */
trait SubscriptionReferencePrintAttribute
{
    /**
     * Return customer full name and reference code
     *
     * @return string
     */
    public function getReferencePrintAttribute()
    {
        return "{$this->customer->reference_code} - {$this->customer->full_name} - {$this->service->name} - {$this->price_print}";
    }
}

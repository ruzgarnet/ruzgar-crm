<?php

namespace App\Models\Attributes;

/**
 * address attribute for subscription
 */
trait SubscriptionAddressAttribute
{
    /**
     * Return options with field translates
     *
     * @return string
     */
    public function getAddressAttribute()
    {
        return $this->getOption('address', $this->customer->customerInfo->address);
    }
}

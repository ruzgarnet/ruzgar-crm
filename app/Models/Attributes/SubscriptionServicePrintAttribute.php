<?php

namespace App\Models\Attributes;

/**
 * service_print attribute for subscription
 */
trait SubscriptionServicePrintAttribute
{
    /**
     * Return service name and subscription price
     *
     * @return string
     */
    public function getServicePrintAttribute()
    {
        return "{$this->service->name} - {$this->price_print}";
    }
}

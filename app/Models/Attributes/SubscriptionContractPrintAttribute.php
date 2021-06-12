<?php

namespace App\Models\Attributes;

/**
 * contract_print attribute for subscription
 */
trait SubscriptionContractPrintAttribute
{
    /**
     * Return title for contracts
     *
     * @return string
     */
    public function getContractPrintAttribute()
    {
        return "{$this->customer->full_name} - {$this->service->name} Sözleşmesi";
    }
}

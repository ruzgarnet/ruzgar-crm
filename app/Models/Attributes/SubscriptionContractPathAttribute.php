<?php

namespace App\Models\Attributes;

/**
 * contract_path attribute for subscription
 */
trait SubscriptionContractPathAttribute
{
    /**
     * Return contract path
     *
     * @return string
     */
    public function getContractPathAttribute()
    {
        return $this->contract ?? md5($this->subscription_no) . '.pdf';
    }
}

<?php

namespace App\Models\Attributes;

/**
 * payment_print attribute
 */
trait PaymentAttribute
{
    /**
     * Return formatted payment with currency for print
     *
     * @return string
     */
    public function getPaymentPrintAttribute()
    {
        return $this->payment ? number_format($this->payment, 2, ",", ".") . "₺" : 0;
    }
}

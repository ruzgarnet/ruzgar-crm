<?php

namespace App\Models\Attributes;

/**
 * paid_at_print attribute
 */
trait PaidAtAttribute
{
    /**
     * Return formatted and translated paid date
     *
     * @return string
     */
    public function getPaidAtPrintAttribute()
    {
        return convert_date($this->paid_at, 'large');
    }
}

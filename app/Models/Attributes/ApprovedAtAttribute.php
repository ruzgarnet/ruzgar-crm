<?php

namespace App\Models\Attributes;

/**
 * approved_at_print attribute
 */
trait ApprovedAtAttribute
{
    /**
     * Return formatted and translated approved date
     *
     * @return string
     */
    public function getApprovedAtPrintAttribute()
    {
        return convert_date($this->approved_at, 'large');
    }
}

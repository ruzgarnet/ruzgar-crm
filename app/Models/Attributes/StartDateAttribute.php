<?php

namespace App\Models\Attributes;

/**
 * start_date_print attribute
 */
trait StartDateAttribute
{
    /**
     * Return formatted and translated date
     *
     * @return string
     */
    public function getStartDatePrintAttribute()
    {
        return convert_date($this->start_date, 'medium');
    }
}

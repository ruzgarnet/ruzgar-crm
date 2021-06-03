<?php

namespace App\Models\Attributes;

/**
 * end_date_print attribute
 */
trait EndDateAttribute
{
    /**
     * Return formatted and translated date
     *
     * @return string
     */
    public function getEndDatePrintAttribute()
    {
        return convert_date($this->end_date, 'medium');
    }
}

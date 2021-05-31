<?php

namespace App\Models\Attributes;

/**
 * date_print attribute
 */
trait DateAttribute
{
    /**
     * Return formatted and translated date
     *
     * @return string
     */
    public function getDatePrintAttribute()
    {
        return convert_date($this->date, 'medium');
    }
}

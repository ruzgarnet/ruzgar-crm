<?php

namespace App\Models\Attributes;

/**
 * telephone_print attribute
 */
trait PhoneAttribute
{
    /**
     * Return telephone number for print
     *
     * @return string
     */
    public function getTelephonePrintAttribute()
    {
        return preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '0 $1 $2 $3 $4', $this->telephone);
    }
}

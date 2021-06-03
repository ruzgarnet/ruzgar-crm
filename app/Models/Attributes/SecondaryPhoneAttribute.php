<?php

namespace App\Models\Attributes;

/**
 * secondary_telephone_print attribute
 */
trait SecondaryPhoneAttribute
{
    /**
     * Return telephone number for print
     *
     * @return string
     */
    public function getSecondaryTelephonePrintAttribute()
    {
        return preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '0 $1 $2 $3 $4', $this->secondary_telephone);
    }
}

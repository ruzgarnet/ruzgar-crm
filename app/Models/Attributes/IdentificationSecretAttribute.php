<?php

namespace App\Models\Attributes;

/**
 * identification_secret attribute
 */
trait IdentificationSecretAttribute
{
    /**
     * Return identification number for print
     *
     * @return string
     */
    public function getIdentificationSecretAttribute()
    {
        return $this->identification_number;
        //return preg_replace('/.{7}(\w{4})/', str_repeat('*', 7) . '$1', $this->identification_number);
    }
}

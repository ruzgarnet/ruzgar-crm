<?php

namespace App\Models\Attributes;

trait IdentificationSecretAttribute
{
    /**
     * Return identification number for print
     *
     * @return string
     */
    public function getIdentificationSecretAttribute()
    {
        return preg_replace('/.{7}(\w{4})/', str_repeat('*', 7) . '$1', $this->identification_number);
    }
}

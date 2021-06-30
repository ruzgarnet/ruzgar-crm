<?php

namespace App\Models\Attributes;

/**
 * select_print attribute for person
 */
trait PersonSelectPrintAttribute
{
    /**
     * Return formatted name and secret identification number for print select option
     *
     * @return string
     */
    public function getSelectPrintAttribute()
    {
        return "{$this->full_name} - {$this->identification_secret}";
    }
}

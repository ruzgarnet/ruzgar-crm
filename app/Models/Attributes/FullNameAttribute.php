<?php

namespace App\Models\Attributes;

trait FullNameAttribute
{
    /**
     * Return full name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

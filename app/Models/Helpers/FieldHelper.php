<?php

namespace App\Models\Helpers;

use Illuminate\Support\Facades\Schema;

trait FieldHelper
{
    /**
     * Return table fields
     *
     * @return array
     */
    public function getFields()
    {
        return Schema::getColumnListing($this->getTable());
    }
}

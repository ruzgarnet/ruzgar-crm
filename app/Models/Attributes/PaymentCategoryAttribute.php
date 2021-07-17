<?php

namespace App\Models\Attributes;

use Illuminate\Support\Str;

/**
 * category attribute for payment
 */
trait PaymentCategoryAttribute
{
    /**
     * Return slugged category
     *
     * @return string
     */
    public function getCategoryAttribute()
    {
        return (string)Str::of($this->subscription->service->category->key)->slug();
    }
}

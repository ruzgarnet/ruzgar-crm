<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telephone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($value) == 10;
    }

    /**
     * Get the validation error message.
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function message()
    {
        return trans('validation.telephone');
    }
}

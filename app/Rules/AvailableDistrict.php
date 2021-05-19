<?php

namespace App\Rules;

use App\Models\City;
use Illuminate\Contracts\Validation\Rule;

class AvailableDistrict implements Rule
{
    /**
     * City Model
     *
     * @var \App\Models\City
     */
    private $city;

    /**
     * Create a new rule instance.
     *
     * @param  string $input
     * @return void
     */
    public function __construct($input)
    {
        $this->city = City::find(request()->input($input));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->city) {
            return false;
        }
        if (in_array($value, $this->city->districts->pluck('id')->toArray())) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function message()
    {
        return trans('validation.district');
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TCNo implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @link  https://www.projehocam.com/php-ile-t-c-kimlik-no-dogrulama-fonksiyonu/
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (env('APP_ENV') == 'test' || env('APP_ENV') == 'local') {
            return true;
        }
        $invalids = [
            '11111111110', '22222222220', '33333333330', '44444444440', '55555555550',
            '66666666660', '7777777770', '88888888880', '99999999990'
        ];

        if ($value[0] == 0 or !ctype_digit($value) or strlen($value) != 11) {
            return false;
        } else {
            $digits = [0, 0, 0];

            for ($a = 0; $a < 9; $a = $a + 2) {
                $digits[0] = $digits[0] + $value[$a];
            }
            for ($a = 1; $a < 9; $a = $a + 2) {
                $digits[1] = $digits[1] + $value[$a];
            }
            for ($a = 0; $a < 10; $a = $a + 1) {
                $digits[2] = $digits[2] + $value[$a];
            }
            if (($digits[0] * 7 - $digits[1]) % 10 != $value[9] or $digits[2] % 10 != $value[10]) {
                return false;
            } else {
                foreach ($invalids as $item) {
                    if ($value == $item) {
                        return false;
                    }
                }
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function message()
    {
        return trans('validation.tcno');
    }
}

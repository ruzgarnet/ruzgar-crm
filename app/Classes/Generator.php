<?php

namespace App\Classes;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * For generating values
 */
class Generator
{
    /**
     * Generate customer number
     *
     * @return string
     */
    public static function customerNo()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:customers,customer_no'];
        do {
            $rand = rand(1000, 9999) . rand(1000, 9999) . rand(100, 999);
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass !== true);

        return $rand;
    }

    /**
     * Generate reference code
     *
     * @return string
     */
    public static function referenceCode()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:customers,reference_code'];
        do {
            $rand = 'R-' . (string)Str::of(Str::random(6))->upper();
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass !== true);

        return $rand;
    }
}

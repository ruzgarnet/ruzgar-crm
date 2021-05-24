<?php

namespace App\Http\Controllers;

/**
 * For mutating requests before validate
 */
class Mutator extends Controller
{
    /**
     * Mutate telephone numbers to clean type
     *
     * @param string $str
     * @return mixed
     */
    public static function phone($str)
    {
        if (is_string($str) && strlen($str) >= 10) {
            $data = preg_replace('/\D/', '', $str);
            return preg_replace('/[\+90|0]?([1-9][0-9]{9})/', '$1', $data);
        }

        return $str;
    }
}

<?php

namespace App\Classes;

/**
 * For mutating requests before validate
 */
class Mutator
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
            return preg_replace('/[90|0]*([1-9][0-9]{9})/', '$1', $data);
        }

        return $str;
    }

    /**
     * Mutate expire date
     *
     * @param string $str
     * @return array
     */
    public static function expire_date($str)
    {
        $data = explode("/", $str);

        if ((int)$data[0] < 10 && strlen($str) == 1)
            $data[0] = '0' . $data[0];

        if (strlen($data[1]) == 2)
            $data[1] = '20' . $data[1];

        return $data;
    }
}

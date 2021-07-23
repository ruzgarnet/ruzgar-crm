<?php

namespace App\Classes;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Milon\Barcode\Facades\DNS1DFacade;

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
        } while ($pass != true);

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
        } while ($pass != true);

        return $rand;
    }

    /**
     * Generate serial number
     *
     * @return string
     */
    public static function serialNumber()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:fault_records,serial_number'];
        do {
            $rand = 'RN-' . (string)Str::of(Str::random(6))->upper();
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass != true);

        return $rand;
    }

    /**
     * Generate subscription number
     *
     * @return string
     */
    public static function subscriptionNo()
    {
        // Control for unique
        $pass = false;
        $rand = '';
        $rule = ['rand' => 'unique:subscriptions,subscription_no'];
        do {
            $rand = rand(1000, 9999) . rand(1000, 9999) . rand(100, 999);
            $input = ['rand' => $rand];
            $validator = Validator::make($input, $rule);
            if (!$validator->fails()) {
                $pass = true;
            } else {
                $pass = false;
            }
        } while ($pass != true);

        return $rand;
    }

    /**
     * Prints barcode image
     *
     * @param string $barcode
     * @return string
     */
    public static function barcode(string $barcode)
    {
        return $barcode = DNS1DFacade::getBarcodePNG($barcode, 'C128');
    }

    /**
     * Generate unique code for Moka
     *
     * @param string $subscription_no
     * @param string $payment_created_at
     * @return string
     */
    public static function trxCode($subscription_no, $payment_created_at)
    {
        $string = $subscription_no . "-" . date('YmdHi', strtotime($payment_created_at)) . "-" . date('YmdHis') . rand(100, 999);
        return substr(hash('sha256', $string), 0, 32);
    }
}

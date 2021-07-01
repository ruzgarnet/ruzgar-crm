<?php

use App\Models\Setting;
use Illuminate\Support\Carbon;

if (!function_exists('relative_route')) {
    /**
     * Generate the relative path to a named route.
     *
     * @param  array|string  $name
     * @param  array|object  $parameters
     * @return string
     */
    function relative_route($name, $parameters = [])
    {
        return app('url')->route($name, $parameters, false);
    }
}

if (!function_exists('meta_title')) {
    /**
     * Generate meta title with translate
     *
     * @param string $title
     * @param string $divider
     * @return string
     */
    function meta_title($title, $divider = '|')
    {
        return trans($title) . ' ' . $divider . ' ' . env('APP_NAME');
    }
}

if (!function_exists('convert_date')) {
    /**
     * Date converts
     *
     * @param string $date
     * @param mixed|null $type mysql | mysql_time | mask | mask_time | month_period | medium | large
     * @return string
     */
    function convert_date($date, $type = null)
    {
        $date = Carbon::parse($date);

        switch ($type) {
            case 'mysql':
                return $date->format('Y-m-d');
                break;

            case 'mysql_time':
                return $date->format('Y-m-d H:i');
                break;

            case 'mask':
                return $date->format('d/m/Y');
                break;

            case 'mask_time':
                return $date->format('d/m/Y H:i');
                break;

            case 'month_period':
                return $date->translatedFormat('F Y');
                break;

            case 'medium':
                return $date->translatedFormat('j F Y');
                break;

            case 'large':
                return $date->translatedFormat('j F Y, l H:i');
                break;

            default:
                return $date->format('Y-m-d H:i');
                break;
        }
    }
}

if (!function_exists('setting')) {
    /**
     * Get value of setting
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('print_money')) {
    /**
     * Returns value's formatted money with currency
     *
     * @param string $price
     * @return mixed
     */
    function print_money($price)
    {
        if (is_numeric($price)) {
            return number_format($price, 2, ",", ".") . "₺";
        }
        return $price;
    }
}

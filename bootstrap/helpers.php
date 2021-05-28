<?php

use App\Models\Setting;

if (!function_exists('relative_route')) {
    /**
     * Generate the relative path to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
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
     * @param mixed $type mysql | mask
     * @return string
     */
    function convert_date($date, $type = 'mysql')
    {
        $date = new DateTime($date);
        switch ($type) {
            case 'mysql':
                return $date->format('Y-m-d');
                break;

            case 'mask':
                return $date->format('d/m/Y');
                break;

            case 'mask_time':
                return $date->format('d/m/Y H:i');
                break;

            default:
                return $date->format('Y-m-d');
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

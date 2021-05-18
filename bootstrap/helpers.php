<?php

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

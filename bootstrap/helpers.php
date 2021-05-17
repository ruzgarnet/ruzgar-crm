<?php

if (! function_exists('relative_route')) {
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

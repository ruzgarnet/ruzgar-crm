<?php

namespace App\Models\Attributes;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * option_values attribute
 */
trait OptionValuesAttribute
{
    /**
     * Return options with field translates
     *
     * @return array
     */
    public function getOptionValuesAttribute()
    {
        $data = [];
        if (is_array($this->options)) {
            foreach ($this->options as $key => $value) {
                $option = (string)Str::of($key)->plural();

                $data[$key]['title'] = trans("fields.{$key}");

                if (Lang::has("fields.{$option}.{$value}")) {
                    $data[$key]['value'] = trans("fields.{$option}.{$value}");
                } else {
                    if ($value === '1' || $value === true) {
                        $data[$key]['value'] = trans("titles.yes");
                    } else if ($value === '0' || $value === false) {
                        $data[$key]['value'] = trans("titles.no");
                    } else {
                        $data[$key]['value'] = $value;
                    }
                }
            }
        }
        return $data;
    }
}

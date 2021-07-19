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
                if ($price = $this->getValue($key)) {
                    $data[$key]['title'] = trans("fields.{$key}", ['price' => print_money($price)]);
                }
                if ($key != "devices") {
                    if (Lang::has("fields.{$option}.{$value}")) {
                        $data[$key]['value'] = trans("fields.{$option}.{$value}");
                        if ($key == 'modem') {
                            $price = $this->getValue('modem_payment', 0);
                            $data[$key]['value'] = trans("fields.{$option}.{$value}", ['price' => print_money($price)]);
                        }
                    } else {
                        if ($key == 'modem_price') {
                            $data[$key]['value'] = print_money($value);
                        } else if ($key == "address") {
                            $data[$key]['value'] = $this->address;
                        } else if ($value == '1' || $value == true) {
                            $data[$key]['value'] = trans("titles.yes");
                        } else if ($value == '0' || $value == false) {
                            $data[$key]['value'] = trans("titles.no");
                        } else {
                            $data[$key]['value'] = $value;
                        }
                    }
                }
                else
                {
                    unset($data[$key]);
                }
            }
        }
        if ($this->getValue('service_price')) {
            $data["service_price"]['title'] = trans("fields.service_price");
            $data["service_price"]['value'] = print_money($this->getValue('service_price'));
        }
        return $data;
    }
}

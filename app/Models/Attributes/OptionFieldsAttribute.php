<?php

namespace App\Models\Attributes;

/**
 * option_fields attribute
 */
trait OptionFieldsAttribute
{
    /**
     * Return options with field translates
     *
     * @return array
     */
    public function getOptionFieldsAttribute()
    {
        $data = [];
        if (is_array($this->options)) {
            foreach ($this->options as $option => $values) {
                if (is_array($values)) {
                    $data[$option] = $this->optionFields($option, $values);
                } else if ($option == "modem_model") {
                    $data[$option] = json_decode(setting("service.modems"), true);
                } else {
                    $data[$option] = $values;
                }
            }
        }
        return $data;
    }

    /**
     * Get options fields
     *
     * @param string $option
     * @param array $values
     * @return array
     */
    public function optionFields($option, $values)
    {
        $data = [];
        foreach ($values as $value) {
            $title = trans("fields.{$option}.{$value}");

            if ($option == 'modems' && ($value == 2 || $value == 3)) {
                $type = 'adsl';
                if ($value == 3) {
                    $type = 'vdsl';
                }

                $price = (float)setting("service.modem.{$type}");

                $title = trans("fields.modems.{$value}", ['price' => print_money($price)]);
            }

            $data[] = [
                'value' => $value,
                'title' => $title
            ];
        }
        return $data;
    }
}

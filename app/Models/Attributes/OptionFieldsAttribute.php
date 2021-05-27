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
            $data[] = [
                'value' => $value,
                'title' => trans("fields.{$option}.{$value}")
            ];
        }
        return $data;
    }
}

<?php

namespace App\Models\Mutators;

use Illuminate\Support\Facades\DB;

/**
 * Generate payments datas for subscription model
 */
trait SubscriptionPaymentMutator
{
    /**
     * Generates first payments
     *
     * @return array
     */
    public function generatePayments()
    {
        $data = [];

        foreach ($this->options as $key => $value) {
            if (method_exists($this, $key)) {
                $data[] = $this->{$key}($value);
            }
        }

        $data = collect($data);
        $month = $data->max('month');

        $variables = $data->map(function ($item) {
            $row = [];
            if (isset($item['monthly'])) {
                if ($item['monthly'] === true) {
                    for ($i = 0; $i < $item['month']; $i++) {
                        $row[$i]['price'] = $item['payment'] / $item['month'];
                    }
                } else if ($item['month'] === 0) {
                    $row['payment'] = $item['payment'];
                }
            } else if (isset($item['pre_payment'])) {
                $row[0]['paid_at'] = DB::raw('current_timestamp()');
                $row[0]['type'] = 1;
                $row[0]['status'] = 2;

                $row[1]['status'] = 1;
            }
            return $row;
        });

        $this->payment = $this->payment + $variables->sum('payment');

        $months = [];
        for ($i = 0; $i < $month; $i++) {
            $months[$i]['price'] = (float)$this->price;
        }

        foreach ($variables as $item) {
            foreach ($item as $index => $value) {
                if ($index !== 'payment') {
                    if (isset($value['price'])) {
                        $months[$index]['price'] = (isset($months[$index]['price']) ? $months[$index]['price'] : 0) + $value['price'];
                        unset($value['price']);
                    }
                    if (is_array($value) && count($value)) {
                        $months[$index] = array_merge($months[$index], $value);
                    }
                    $months[$index]['subscription_id'] = $this->id;
                }
            }
        }

        $dateAppend = 1;
        if (isset($this->options['pre_payment']) && $this->options['pre_payment'] == true) {
            $dateAppend = 0;
        }

        foreach ($months as $index => $value) {
            $months[$index]['date'] = date('Y-m-15', strtotime('+' . $dateAppend . ' month'));
            $dateAppend++;
        }

        return $months;
    }

    /**
     * Setup payment variables
     *
     * @param int $value
     * @return array
     */
    public function setup_payment($value)
    {
        return [
            'payment' => (int)setting('service.setup.payment'),
            'monthly' => ($value - 1) > 0 ? true : false,
            'month' => $value - 1
        ];
    }

    /**
     * Pre Payment Variables
     *
     * @return array
     */
    public function pre_payment()
    {
        return [
            'pre_payment' => true
        ];
    }

    /**
     * Modem payment variables
     *
     * @param int $value
     * @return array
     */
    public function modem_payment($value)
    {
        // 1 => yok
        // 2 => adsl
        // 3 => vdsl
        // 4 => fiber

        $type = (int)$this->options['modem'];

        $typeVal = '';
        if ($type === 2) {
            $typeVal = 'adsl';
        } else if ($type === 3) {
            $typeVal = 'vdsl';
        }

        $payment = (int)setting("service.modem.{$typeVal}");

        return [
            'payment' => $payment,
            'monthly' => ($value - 1) > 0 ? true : false,
            'month' => $value - 1
        ];
    }

    /**
     * Summer campaing variables
     *
     * @param int $value
     * @return array
     */
    public function summer_campaing_payment($value)
    {
        return [
            'payment' => (int)setting('service.setup.payment'),
            'monthly' => ($value - 1) > 0 ? true : false,
            'month' => $value - 1
        ];
    }
}

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

        $this->payment = $data->sum("payment");
        $this->price += $data->sum("price");

        $payment_variables = $data->filter(function ($value) {
            return in_array("payments", array_keys($value));
        })->pluck("payments");

        $months = $payment_variables->map(function ($item) {
            return count($item);
        })->max();

        $data_append = 1;

        if (isset($this->options["pre_payment"]) && $this->options["pre_payment"] && $months < 2) {
            $months = 2;
            $data_append = 0;
        }

        $payments = [];
        for ($i = 0; $i < $months; $i++) {
            $payments[$i]["price"] = (float)$this->price;
        }

        foreach ($payment_variables->toArray() as $values) {
            foreach ($values as $index => $value) {
                $payments[$index]["price"] += (float)$value;
            }
        }

        foreach ($payments as $index => $value) {
            $payments[$index]["subscription_id"] = $this->id;
            $payments[$index]["date"] = date('Y-m-15', strtotime("+{$data_append} month"));
            $data_append++;
        }

        if (isset($this->options["pre_payment"]) && $this->options["pre_payment"]) {
            $payments[0]['paid_at'] = DB::raw('current_timestamp()');
            $payments[0]['type'] = 1;
            $payments[0]['status'] = 2;
        }

        return $payments;
    }

    /**
     * Setup payment variables
     *
     * @param int $value
     * @return array
     */
    public function setup_payment($value)
    {
        $payment = (float)setting('service.setup.payment');

        if ($value == 1) {
            return ['payment' => $payment];
        }

        $data = [];
        for ($i = 0; $i < $value - 1; $i++) {
            $data[] = $payment / ($value - 1);
        }

        return [
            'payments' => $data
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

        $payment = (float)setting("service.modem.{$typeVal}");

        if ($value == 1) {
            return ['payment' => $payment];
        }

        $data = [];
        for ($i = 0; $i < $value - 1; $i++) {
            $data[] = $payment / ($value - 1);
        }

        return [
            'payments' => $data
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
        $payment = (float)setting('service.summer.campaing.payment');

        if ($value == 1) {
            return ['payment' => $payment];
        }

        $data = [];
        for ($i = 0; $i < $value - 1; $i++) {
            $data[] = $payment / ($value - 1);
        }

        return [
            'payments' => $data
        ];
    }

    /**
     * Modem Price Variables
     *
     * @return array
     */
    public function modem_price()
    {
        if (isset($this->options["modem_price"]) && is_numeric($this->options["modem_price"])) {
            return [
                'price' => (float)$this->options["modem_price"]
            ];
        }
    }
}

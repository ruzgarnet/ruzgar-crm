<?php

namespace App\Classes;

class Messages
{
    public function generate(string $message, array $parameters)
    {
        foreach ($parameters as $key => $parameter) {
            $message = str_replace("{" . $key . "}", $parameter, $message);
        }

        return $message;
    }

    public function multiMessage(string $message, $subscriptions)
    {
        preg_match_all("/{[^}]*}/", $message, $parameters);
        $variables = $this->parameters();
        $data = [];
        $values = [];
        $messages = [];
        foreach ($subscriptions as $subscription)
        {
            $values[$subscription->id] = [];
            foreach ($parameters[0] as $parameter)
            {
                $parameter = str_replace(["{", "}"], ["", ""], $parameter);
                $temporary = $subscription;
                foreach ($variables[$parameter] as $variable) {
                    if($temporary != null)
                        $temporary = $temporary->{$variable};
                }

                if(!is_null($temporary) || is_string($temporary) || is_numeric($temporary))
                    $values[$subscription->id][$parameter] = $temporary;
            }
            $messages[] = [
                $subscription->customer->telephone,
                $this->generate(
                    $message,
                    $values[$subscription->id]
                )
            ];
        }

        return $messages;
    }

    public function parameters()
    {
        return [
            "ad_soyad" => [
                "customer",
                "full_name"
            ],
            "tarih" => [
                "current_payment",
                "date_short"
            ],
            "reference_code" => [
                'customer',
                'reference_code'
            ],
            'tutar' => [
                'current_payment',
                'price'
            ]
        ];
    }
}

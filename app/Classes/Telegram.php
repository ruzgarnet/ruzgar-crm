<?php

namespace App\Classes;

use Exception;

/**
 * Telegram API
 */
class Telegram
{
    public $chats = [
        'AboneTamamlanan' => "-1001446123222",
        'AltyapıSorgulama' => '-1001234818134',
        'NiğdeSatış' => '-1001312842188',
        'WebÜzerindenSatış' => '-1001239160019',
        'RüzgarNETÖdeme' => '-1001188341295',
        'AboneBilgileri' => '-1001393165900',
        'KaliteKontrolEkibi' => '-1001468489934',
        'İptalİşlemler' => '-1001123700542',
        'BizSiziArayalım' => '-1001459396907',
        'RüzgarTeknik' => '-1001270493121',
        'RüzgarCELLKotaSorgulama' => '-1001470398107',
        'SözleşmesiSonaErecekler' => '-1001172443073',
        'BayiSatışlar' => '-1001412338702'
    ];

    /**
     * API Telegram Token
     *
     * @var string
     */
    private $token = "";

    /**
     * URL
     *
     * @var string
     */
    private $url = "";

    /**
     * Function
     *
     * @var string
     */
    private $function = "sendMessage";

    /**
     * Request Parameters
     *
     * @var array
     */
    private $request_parameters = [];

    public function __construct()
    {
        $token = env("TELEGRAM_API_TOKEN");
        if ($token && !empty($token)) {
            $this->url = "https://api.telegram.org/bot";
            $this->token = env("TELEGRAM_API_TOKEN");
            $this->url .= $this->token . "/";
        } else {
            throw new Exception('Token not found', 100);
        }
    }

    /**
     * Send message to id
     *
     * @param int $chat_id
     * @param string $message
     * @return json|null
     */
    public function send_message($chat_id, $message)
    {
        if (env('APP_ENV') === 'local') {
            $chat_id = '-562316544';
        }
        $this->request_parameters["chat_id"] = $chat_id;
        $this->request_parameters["text"] = $message;
        $this->function = "sendMessage";
        $this->send();
    }

    private function send()
    {
        $this->url .= $this->function . "?" . http_build_query($this->request_parameters);
        return file_get_contents($this->url);
    }
}

<?php

namespace App\Classes;

/**
 * Telegram API
 */
class Telegram
{
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
        $this->url = "https://api.telegram.org/bot";
        $this->token = env("TELEGRAM_API_TOKEN");
        $this->url .= $this->token."/";
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
        $this->request_parameters["chat_id"] = $chat_id;
        $this->request_parameters["text"] = $message;
        $this->function = "sendMessage";
        $this->send();
    }

    private function send()
    {
        $this->url .= $this->function."?".http_build_query($this->request_parameters);
        return file_get_contents($this->url);
    }
}

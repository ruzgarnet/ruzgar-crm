<?php

namespace App\Classes;

/**
 * Class for Moka payment
 *
 * @link https://developer.moka.com
 */
class Moka
{
    /**
     * Moka API Url
     *
     * @var string $moka_url
     */
    private string $moka_url = "https://service.moka.com/";

    /**
     * Moka Authentication Fields
     *
     * @var array $auth
     */
    private array $auth = [];

    /**
     * Request URI
     *
     * @var string $action
     */
    private string $action = "";

    public function __construct()
    {
        $dealer_code = env('MOKA_DEALER_CODE');
        $username = env('MOKA_USERNAME');
        $password = env('MOKA_PASSWORD');

        $check_key = hash("sha256", $dealer_code . "MK" . $username . "PD" . $password);

        $this->auth = [
            'DealerCode' => $dealer_code,
            'Username' => $username,
            'Password' => $password,
            'CheckKey' => $check_key
        ];
    }

    /**
     * Sends request to url
     *
     * @param array $request
     * @return object|null
     */
    private function send($request)
    {
        $request = json_encode($request);

        $ch = curl_init($this->url());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    /**
     * Returns request url
     *
     * @return string
     */
    private function url()
    {
        return trim($this->moka_url, "/") . "/" . trim($this->action, "/");
    }

    /**
     * Make Payment
     *
     * @param array $card Card Info
     * @param string $uri Action Uri
     * @param array $optional Optional Fields
     * @return object|null
     */
    public function pay(
        array $card,
        string $uri,
        array $optional = []
    ) {
        $this->action = "PaymentDealer/DoDirectPaymentThreeD";

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            "PaymentDealerRequest" => [
                "CardHolderFullName" => $card["full_name"],
                "CardNumber" => $card["number"],
                "ExpMonth" => $card["expire_month"],
                "ExpYear" => $card["expire_year"],
                "CvcNumber" => $card["security_code"],
                "Amount" => $card["amount"],
                "Currency" => "TL",
                "InstallmentNumber" => "1",
                "ClientIP" => $_SERVER['REMOTE_ADDR'],
                "OtherTrxCode" => $this->generate_unique_code(),
                "IsPreAuth" => 0,
                "IsPoolPayment" => 0,
                "Software" => "RüzgarNET",
                "RedirectUrl" => $uri,
                "RedirectType" => 0
            ]
        ];

        if (isset($optional["brand_name"])) {
            $request["PaymentDealerRequest"]["BuyerInformation"] = $optional["brand_name"] . " fatura ödemesidir.";
        }

        return $this->send($request);
    }

    /**
     * Create customer with card info
     *
     * @param array $customer
     * @param array $card
     * @return object|null
     */
    public function create_customer(
        array $customer,
        array $card
    ) {
        $this->action = "DealerCustomer/AddCustomerWithCard";

        $request = [
            'DealerCustomerAuthentication' => $this->auth,
            "DealerCustomerRequest" => [
                "CustomerCode" => $customer["id"],
                "FirstName" => $customer["first_name"],
                "LastName" => $customer["last_name"],
                "CardHolderFullName" => $card["full_name"],
                "CardNumber" => $card["number"],
                "ExpMonth" => $card["expire_month"],
                "ExpYear" => $card["expire_year"],
                "CardName" => $customer["first_name"] . ' ' . $customer["last_name"] . ' Kredi/Banka Kartı'
            ]
        ];

        return $this->send($request);
    }

    /**
     * Get payment plan list
     *
     * @param int $sale_id
     * @return object|null
     */
    public function get_sale_payment_list(int $sale_id)
    {
        $this->action = "DealerSale/GetPaymentPlanList";

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            "DealerSaleRequest" => [
                "DealerSaleId" => $sale_id,
                "PaymentPlanPaymentDateStart" => "20180622",
                "PaymentPlanPaymentDateEnd" => date('Ymd')
            ]
        ];

        return $this->send($request);
    }

    /**
     * Adds sale to customer
     *
     * @param array $customer
     * @param array $subscription
     * @param array $date
     * @return object|null
    */
    public function add_sale(
        array $customer,
        array $subscription,
        array $date
    ) {
        $this->action = "DealerSale/AddSale";

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            "DealerSaleRequest" => [
                "DealerCustomerId" => $customer["moka_id"],
                "ProductCode" => $subscription["service_code"],
                "Amount" => $subscription["amount"],
                "Currency" => "TL",
                "InstallmentNumber" => 1,
                "DealerSaleScheduleId" => "",
                "SaleDate" => date('Ymd'),
                "BeginDate" => $date["start"],
                "EndDate" => $date["end"],
                "HowManyTrial" => 1,
                "PlanType" => 3,
                "Description" => "",
                "DealerCustomerTypeId" => "",
                "DefaultCard1Token" => $customer["card_token"]
            ]
        ];

        return $this->send($request);
    }

    /**
     * Get sale info
     *
     * @param int $sale_id
     * @return object|null
     */
    public function get_sale(int $sale_id)
    {
        $this->action = "DealerSale/GetSale";

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            "DealerSaleRequest" => [
                "DealerSaleId" => $sale_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Refund payment
     *
     * @param int $order_id
     * @return object|null
     */
    public function refund(
        int $order_id
    ) {
        $this->action = "PaymentDealer/DoCreateRefundRequest";

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            "PaymentDealerRequest" => [
                "VirtualPosOrderId" => $order_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Get payment detail list
     *
     * @param int $payment_id
     * @return object|null
     */
    public function get_payment_detail(
        int $payment_id
    ) {
        $this->action = "PaymentDealer/GetDealerPaymentTrxDetailList";

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            "PaymentDealerRequest" => [
                "PaymentId" => $payment_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Generate unique code for Moka
     *
     * @return string
     */
    private function generate_unique_code()
    {
        return substr(hash('sha256', str_shuffle('abcdefghijklmnoprstuvyzwxqABCDEFGHIKJLMNOPRSTUVYZWXQ0123456789')), 0, 32);
    }
}
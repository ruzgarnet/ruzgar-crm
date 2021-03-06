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
    private string $moka_url = 'https://service.moka.com/';

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
    private string $action = '';

    /**
     * Trx Code
     *
     * @var string $trx_code
     */
    public string $trx_code = '';

    public function __construct()
    {
        $dealer_code = env('MOKA_DEALER_CODE');
        $username = env('MOKA_USERNAME');
        $password = env('MOKA_PASSWORD');

        $check_key = hash('sha256', $dealer_code . 'MK' . $username . 'PD' . $password);

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
        return trim($this->moka_url, '/') . '/' . trim($this->action, '/');
    }

    /**
     * Make Payment
     *
     * @param array $card Card Info
     * @param string $uri Action Uri
     * @param array $hash Hash
     * @param array $optional Optional Fields
     * @return object|null
     */
    public function pay(
        array $card,
        string $uri,
        array $hash,
        array $optional = []
    ) {
        $this->action = 'PaymentDealer/DoDirectPaymentThreeD';

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            'PaymentDealerRequest' => [
                'CardHolderFullName' => $card['full_name'],
                'CardNumber' => $card['number'],
                'ExpMonth' => $card['expire_month'],
                'ExpYear' => $card['expire_year'],
                'CvcNumber' => $card['security_code'],
                'Amount' => $card['amount'],
                'Currency' => 'TL',
                'InstallmentNumber' => '1',
                'ClientIP' => $_SERVER['REMOTE_ADDR'],
                'OtherTrxCode' => $this->generate_unique_code(
                    $hash['subscription_no'],
                    $hash['payment_created_at']
                ),
                'IsPreAuth' => 0,
                'ReturnHash' => 1,
                'IsPoolPayment' => 0,
                'Software' => 'R??zgarNET',
                'RedirectUrl' => $uri,
                'RedirectType' => 1
            ]
        ];

        if (isset($optional['brand_name'])) {
            $request['PaymentDealerRequest']['BuyerInformation'] = $optional['brand_name'] . ' fatura ??demesidir.';
        }
        if (isset($optional['is_pre_auth'])) {
            $request['PaymentDealerRequest']['IsPreAuth'] = $optional['is_pre_auth'];
        }
        if (isset($optional['Amount'])) {
            $request['PaymentDealerRequest']['Amount'] = $optional['pre_auth_price'];
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
        $this->action = 'DealerCustomer/AddCustomerWithCard';

        $request = [
            'DealerCustomerAuthentication' => $this->auth,
            'DealerCustomerRequest' => [
                'CustomerCode' => $customer['id'],
                'FirstName' => $customer['first_name'],
                'LastName' => $customer['last_name'],
                'Phone' => $customer['telephone'],
                'CardHolderFullName' => $card['full_name'],
                'CardNumber' => $card['number'],
                'ExpMonth' => $card['expire_month'],
                'ExpYear' => $card['expire_year'],
                'CardName' => $customer['first_name'] . ' ' . $customer['last_name'] . ' Kredi/Banka Kart??'
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
        $this->action = 'DealerSale/GetPaymentPlanList';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerSaleId' => $sale_id,
                'PaymentPlanPaymentDateStart' => '20180622',
                'PaymentPlanPaymentDateEnd' => date('Ymd')
            ]
        ];

        return $this->send($request);
    }

    /**
     * Update sale's card_token or end date
     *
     * @param int $sale_id
     * @param array $optional
     * @return object|false|null
     */
    public function update_sale(
        $sale_id,
        $optional = []
    ) {
        $this->action = 'DealerSale/UpdateSale';

        if (count(array_filter($optional)) > 0) {
            $request = [
                'DealerSaleAuthentication' => $this->auth,
                'DealerSaleRequest' =>  array(
                    'DealerSaleId' => $sale_id
                )
            ];

            if (isset($optional['begin_date'])) {
                $request['DealerSaleRequest']['BeginDate'] = $optional['begin_date'];
            }
            if (isset($optional['end_date'])) {
                $request['DealerSaleRequest']['EndDate'] = $optional['end_date'];
            }
            if (isset($optional['card_token'])) {
                $request['DealerSaleRequest']['DefaultCard1Token'] = $optional['card_token'];
            }

            return $this->send($request);
        }

        return false;
    }

    /**
     * Delete sale
     *
     * @param int $sale_id
     * @return object|null
     */
    public function delete_sale(
        $sale_id
    ) {
        $this->action = '/DealerSale/DeleteSale';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerSaleId' => $sale_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Update sale's end date
     *
     * @param int $sale_id
     * @param string $end_date
     * @return object|null
     */
    public function update_sale_end_date(
        $sale_id,
        $end_date
    ) {
        $this->action = 'DealerSale/UpdateSale';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerSaleId' => $sale_id,
                'EndDate' => $end_date
            ]
        ];

        return $this->send($request);
    }

    /**
     * Add new card to customer
     *
     * @param int $customer_id
     * @param string $full_name
     * @param string $number
     * @param string $number
     * @param string $month
     * @return object|null
     */
    public function add_card(
        $customer_id,
        $full_name,
        $number,
        $month,
        $year
    ) {
        $this->action = 'DealerCustomer/AddCard';

        $request = [
            'DealerCustomerAuthentication' => $this->auth,
            'DealerCustomerRequest' => [
                'DealerCustomerId' => $customer_id,
                'CardHolderFullName' => $full_name,
                'CardNumber' => $number,
                'ExpMonth' => $month,
                'ExpYear' => $year
            ]
        ];

        return $this->send($request);
    }

    /**
     * Remove payment plan
     *
     * @param int $payment_plan_id
     * @return object|null
     */
    public function remove_plan(int $payment_plan_id)
    {
        $this->action = '/DealerSale/DeletePaymentPlan';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerPaymentPlanId' => $payment_plan_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Remove card
     *
     * @param string $card_token
     * @return object|null
     */
    public function remove_card(string $card_token)
    {
        $this->action = 'DealerCustomer/RemoveCard';

        $request = [
            'DealerCustomerAuthentication' => $this->auth,
            'DealerCustomerRequest' => [
                'CardToken' => $card_token
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
        $this->action = 'DealerSale/AddSale';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerCustomerId' => $customer['moka_id'],
                'ProductCode' => $subscription['service_code'],
                'Amount' => $subscription['amount'],
                'Currency' => 'TL',
                'InstallmentNumber' => 1,
                'DealerSaleScheduleId' => '',
                'SaleDate' => date('Ymd'),
                'BeginDate' => $date['start'],
                'EndDate' => $date['end'],
                'HowManyTrial' => 4,
                'PlanType' => 2,
                'Description' => '',
                'DealerCustomerTypeId' => '',
                'DefaultCard1Token' => $customer['card_token']
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
        $this->action = 'DealerSale/GetSale';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerSaleId' => $sale_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Do Void payment
     *
     * @param string $trx_code
     * @return object|null
     */
    public function do_void(
        string $trx_code
    ) {
        $this->action = 'PaymentDealer/DoVoid';

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            'PaymentDealerRequest' => [
                'OtherTrxCode' => $trx_code,
                'VoidRefundReason' => 2
            ]
        ];

        return $this->send($request);
    }

    /**
     * Refund payment
     *
     * @param string $trx_code
     * @return object|null
     */
    public function refund(
        string $trx_code
    ) {
        $this->action = 'PaymentDealer/DoCreateRefundRequest';

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            'PaymentDealerRequest' => [
                'OtherTrxCode' => $trx_code
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
        $this->action = 'PaymentDealer/GetDealerPaymentTrxDetailList';

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            'PaymentDealerRequest' => [
                'PaymentId' => $payment_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Get payment detail list
     *
     * @param string $other_trx_code
     * @return object|null
     */
    public function get_payment_detail_by_other_trx(
        string $other_trx_code
    ) {
        $this->action = 'PaymentDealer/GetDealerPaymentTrxDetailList';

        $request = [
            'PaymentDealerAuthentication' => $this->auth,
            'PaymentDealerRequest' => [
                'OtherTrxCode' => $other_trx_code
            ]
        ];

        return $this->send($request);
    }

    /**
     * Get payment detail list
     *
     * @param int $plan_id
     * @return object|null
     */
    public function get_payment_plan(
        int $plan_id
    ) {
        $this->action = '/DealerSale/GetPaymentPlan';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerPaymentPlanId' => $plan_id
            ]
        ];

        return $this->send($request);
    }

    /**
     * Add payment plan
     *
     * @param int $sale_id
     * @param string $payment
     * @param float|int $amount
     * @return object|null
     */
    public function add_payment_plan(
        int $sale_id,
        string $payment_date,
        $amount
    ) {
        $this->action = '/DealerSale/AddPaymentPlan';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'DealerSaleId' => $sale_id,
                'PaymentDate' => $payment_date,
                'Amount' => $amount
            ]
        ];

        return $this->send($request);
    }

    /**
     * Add product
     *
     * @param string $product_code
     * @param string $product_name
     * @return object|null
     */
    public function add_product(
        string $product_code,
        string $product_name
    ) {
        $this->action = '/DealerSale/AddProduct';

        $request = [
            'DealerSaleAuthentication' => $this->auth,
            'DealerSaleRequest' => [
                'ProductCode' => $product_code,
                'ProductName' => $product_name
            ]
        ];

        return $this->send($request);
    }

    /**
     * Generate unique code for Moka
     *
     * @param string $subscription_no
     * @param string $payment_created_at
     * @return string
     */
    private function generate_unique_code($subscription_no, $payment_created_at)
    {
        $string = $subscription_no . "-" . date('YmdHi', strtotime($payment_created_at)) . "-" . date('YmdHis') . rand(100, 999);
        $this->trx_code = substr(hash('sha256', $string), 0, 32);
        return $this->trx_code;
    }

    /**
     * Check hash
     *
     * @param string $hash_value
     * @param string $code_for_hash
     * @return boolean
     */
    public static function check_hash(string $hash_value, string $code_for_hash)
    {
        $hash = hash('sha256', $code_for_hash . 'T');
        return $hash == $hash_value ? true : false;
    }
}

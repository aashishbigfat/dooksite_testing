<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class CASHFREE extends BaseController
{
    private $payment_gateway_table;
    private $transaction_table;
    private $secretKey;
    private $appId;
    private $PaymentBaseURL;
    private $APIStatusURL;
    public $web_partner_id;
    public function __construct()
    {
        ini_set('serialize_precision', -1);

        $this->web_partner_id = web_partner_details['id'];

        $whitelabel = $this->getWhiteLabelConfig();
        $this->payment_gateway_table = $whitelabel['payment_gateway_type'] === 'webpartner'  ? "web_partner_payment_gateway_mode_activation" : "super_admin_payment_gateway_mode_activation";
        $this->transaction_table = $whitelabel['payment_gateway_type'] === 'webpartner' ? "web_partner_payment_transaction" : "super_admin_payment_transaction";

        $PaymentModel = new PaymentModel();
        $gatewayInfo = json_decode($PaymentModel->gateway_setting($this->payment_gateway_table, 'CASHFREE'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from gateway settings');
        }

        $this->secretKey = $gatewayInfo['secret_key'] ?? '';
        $this->appId = $gatewayInfo['app_id'] ?? '';
        $this->setApiUrls(strtolower($gatewayInfo['Mode'] ?? 'test'));
    }

    private function setApiUrls($mode)
    {
        if ($mode === 'test') {
            $this->PaymentBaseURL = "https://sandbox.cashfree.com/pg/links";
            $this->APIStatusURL = "https://sandbox.cashfree.com/pg/links";
        } else {
            $this->PaymentBaseURL = "https://api.cashfree.com/pg/links";
            $this->APIStatusURL = "https://api.cashfree.com/pg/links";
        }
    }

    public function request(array $request)
    {
        $tid = $this->generateTransactionId();
        $order_id = 'PTTS' . $this->generateOrderId();
        $payment_option = get_cashfree_payment_mode($request['PaymentMode']);
        $Amounts = round($request['Amount'], 2);

        $data = [
            "link_id" => $order_id,
            "link_amount" => $Amounts,
            "link_currency" => $request['Currency'],
            "link_purpose" => $request['Service'],
            "customer_details" => [
                "customer_name" => $request['FirstName'] . ' ' . $request['LastName'],
                "customer_phone" => $request['MobileNumber'],
                "customer_email" => $request['Email'],
            ],
            "link_notify" => [
                'send_sms' => false,
                'send_email' => false
            ],
            "link_meta" => [ 
                "return_url" => $request['RedirectURL'] . '?link_id=' . dev_encode($order_id) . '&developer-verify='. $order_id,
                "notify_url" => $request['NotifyURL'],
                "payment_methods" => $payment_option,
            ],
        ];

        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service'],$request['WebPartnerId']);
        $payment_log = $this->preparePaymentLog($request, $tid, $order_id, $servicePrefix, $data);

        $insertTable = $request['TransactionTable'];
        $PaymentModel->insertData($insertTable, $payment_log);

        $redirecturl = $this->createOrder($this->PaymentBaseURL, $data);


        $redirecturl = json_decode($redirecturl, true);
        if (isset($redirecturl['link_status']) && $redirecturl['link_status'] == "ACTIVE" && isset($redirecturl['link_url'])) {
            return [
                'Error' => ["ErrorCode" => 0, "ErrorMessage" => ''],
                'Result' => ["paymentLink" => $redirecturl['link_url']]
            ];
        } else {
            $errorMessage = $redirecturl['message'] ?? "Error";
            return [
                'Error' => ["ErrorCode" => 101, "ErrorMessage" => $errorMessage]
            ];
        }
    }

    public function response(array $response)
    {
        $status_api_response = $this->checkStatus($response['link_id']);
     /*    $payment_status = isset($status_api_response['cf_link_id']) && $status_api_response['link_amount_paid'] ? 'Successful' : 'Failed'; */
       


        if(isset($status_api_response['code']) && $status_api_response['code'] =='link_not_found' || isset($status_api_response['type']) && $status_api_response['type'] == 'invalid_request_error'){ 
            return array('codeError' => $response['link_id']); 
        }
        if (isset($status_api_response['cf_link_id']) && $status_api_response['cf_link_id']) {
            if ($status_api_response['link_amount_paid']) {
                $payment_status = 'Successful';
            } else {
                $payment_status = 'Failed';
            }
        } 



        $PaymentModel = new PaymentModel();

        $paymentrecord = $PaymentModel->checkpayment_record($this->transaction_table, ['order_id' =>$response["link_id"], 'payment_status' => 'Processing','web_partner_id'=>$this->web_partner_id]);

        if (!empty($paymentrecord)) {
            $updatepaymentdata = [
                'status_api_response' => json_encode($status_api_response),
                'payment_response' => json_encode($response),
                'payment_status' => $payment_status
            ];
            $PaymentModel->updateData($this->transaction_table, ['order_id' => $response["link_id"]], $updatepaymentdata);
            return [
                'payment_status' => $payment_status,
                'order_id' => $response["link_id"],
                'amount' => $status_api_response["link_amount"] ?? 0
            ];
        } else {
            return array('ErrorCode' => 4001, 'ErrorMessage' => 'Unauthorized request detected', 'order_id' =>$response["link_id"]);
        }


      
        
    }

    private function checkStatus($order_no)
    {
        $url = $this->APIStatusURL . '/' . $order_no;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "x-api-version: 2023-08-01",
                "x-client-id: $this->appId",
                "x-client-secret: $this->secretKey"
            ]
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    private function createOrder($url, array $requestData)
    {
        $request = json_encode($requestData);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "x-api-version: 2023-08-01",
                "x-client-id: $this->appId",
                "x-client-secret: $this->secretKey"
            ]
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function getWhiteLabelConfig()
    {
        return whitelabel;
    }

    private function generateTransactionId()
    {
        return rand(1000, 99999999) . time();
    }

    private function generateOrderId()
    {
        return rand(1000, 99999999) . time();
    }

    private function preparePaymentLog(array $request, $tid, $order_id, $servicePrefix, array $data)
    {
        return [
            'web_partner_id' => $request['WebPartnerId'],
            'transaction_id' => $tid,
            'order_id' => $order_id,
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'booking_prefix' => isset($servicePrefix['pre_fix']) ? $servicePrefix['pre_fix'] : null,
            'amount' => $request['Amount'],
            'payment_request' => json_encode($data),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'actually_convenience_fee' => isset($request['ActuallyConvenienceFee']) ? $request['ActuallyConvenienceFee'] : 0,
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'CASHFREE',
            'wl_customer_id' => $request['wl_customer_id'],
            'payment_source' => $request['PaymentSource'],
            'service_log' => $request['ServiceLog'],
            'actually_amounts' => isset($request['ActuallyAmounts']) ? $request['ActuallyAmounts'] : NULL,
            'default_currency' =>  isset($request['DefaultCurrency']) ? $request['DefaultCurrency'] : NULL,
            'selected_currency' =>  isset($request['BookingCurrency']) ? $request['BookingCurrency'] : NULL,
            'conversion_rate' =>  isset($request['ConversionRate']) ? $request['ConversionRate'] : NULL,
            'currency_symbol' =>  isset($request['CurrencySymbol']) ? $request['CurrencySymbol'] : NULL,
            'created' => create_date()
        ];
    }
}

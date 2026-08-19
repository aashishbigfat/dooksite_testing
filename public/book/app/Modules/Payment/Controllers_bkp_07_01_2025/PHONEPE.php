<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class PHONEPE extends BaseController
{

    public $APIStatusURL;
    public $PaymentBaseURL;
    public $web_partner_details;
    public $keyIndex;
    public $keySalt;
    public $merchantId;
    public $transaction_table;
    public $payment_gateway_table;

    public function __construct()
    {
        ini_set('serialize_precision', -1);

        if (whitelabel['payment_gateway_type'] === 'webpartner') {
            $this->payment_gateway_table = "web_partner_payment_gateway_mode_activation";
            $this->transaction_table = "web_partner_payment_transaction";
        } else {
            $this->payment_gateway_table = "super_admin_payment_gateway_mode_activation";
            $this->transaction_table = "super_admin_payment_transaction";
        }

        $PaymentModel = new PaymentModel();
        $gatewayInfo = $PaymentModel->gateway_setting($this->payment_gateway_table, 'PHONEPE');

        $gatewayInfo = json_decode($gatewayInfo, true);
        $this->merchantId = $gatewayInfo['phonepe_merchant_id'];
        $this->keyIndex = $gatewayInfo['phonepe_key_index'];
        $this->keySalt = $gatewayInfo['phonepe_key_salt'];
        if (strtolower($gatewayInfo['Mode']) === 'test') {
            $this->PaymentBaseURL = "https://api-preprod.phonepe.com/apis/pg-sandbox";
        } else {
            $this->PaymentBaseURL = "https://api.phonepe.com/apis/hermes";
        }
    }



    public function request($request)
    {
        $tid = rand(1000, 99999999) . time();
        $merchantUserId = "TTS" . rand(1000, 99999999) . time();
        $order_id = $tid;
        /*         $payment_option = get_phonepay_payment_mode($request['PaymentMode']);
                $card_type = $request['PaymentMode']; */
        $amount = round($request['Amount']) * 100;  /* Amount In Paisa */
        $PhoneData = array(
            "merchantId" => $this->merchantId,
            "merchantTransactionId" => $tid,
            "amount" => $amount,
            "merchantUserId" => $merchantUserId,
            "redirectUrl" => $request['RedirectURL'],
            "redirectMode" => "POST",
            "callbackUrl" => $request['callbackUrl'],
            "mobileNumber" => $request['MobileNumber'],
            "paymentInstrument" => array(
                "type" => "PAY_PAGE",
            ),
        );



        $PhoneDataJson = json_encode($PhoneData);
        $PhoneDecodePayload = array("request" => base64_encode($PhoneDataJson));
        $PhoneDecodePayloadJosn = json_encode($PhoneDecodePayload);
        $XVERIFY = hash("sha256", base64_encode($PhoneDataJson) . "/pg/v1/pay" . $this->keySalt) . "###" . $this->keyIndex;
        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service'],$request['WebPartnerId']);

        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
            'user_id' => $request['UserId'],
            'transaction_id' => $tid,
            'order_id' => $order_id,
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'amount' => $request['Amount'],
            'payment_request' => json_encode($PhoneData),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'PHONEPE',
            'self_order_id' => $order_id,
            'booking_prefix' => isset($servicePrefix['pre_fix']) ? $servicePrefix['pre_fix'] : null,
            'extraParam' => json_encode(array("gatewayOrderData" => $PhoneDecodePayload, "XVERIFY" => $XVERIFY)),
            'service_log' => $request['ServiceLog'],
            'payment_source' => isset($request['PaymentSource']) ? $request['PaymentSource'] : "",
            'wl_customer_id' => $request['wl_customer_id'],
            'actually_amounts' => isset($request['ActuallyAmounts']) ? $request['ActuallyAmounts'] : NULL,
            'default_currency' =>  isset($request['DefaultCurrency']) ? $request['DefaultCurrency'] : NULL,
            'selected_currency' =>  isset($request['BookingCurrency']) ? $request['BookingCurrency'] : NULL,
            'conversion_rate' =>  isset($request['ConversionRate']) ? $request['ConversionRate'] : NULL,
            'currency_symbol' =>  isset($request['CurrencySymbol']) ? $request['CurrencySymbol'] : NULL,
            'created' => create_date(),

        );


        $insertTable = $request['TransactionTable'];
        $PaymentModel = new PaymentModel();

        $PaymentModel->insertData($insertTable, $payment_log);
        $redirecturl = PHONEPE::Createorder($this->PaymentBaseURL, $PhoneDecodePayloadJosn, $XVERIFY);

        $redirecturl = json_decode($redirecturl, true);

        if (isset($redirecturl['success']) && isset($redirecturl['code']) && $redirecturl['success'] && $redirecturl['code'] == "PAYMENT_INITIATED") {
            $response = [
                'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
                'Result' => array("url" => $redirecturl['data']['instrumentResponse']['redirectInfo']['url'])
            ];
            return $response;
        } else {
            $errorMessage = isset($redirecturl['message']) ? $redirecturl['message'] : "Error";
            return $response = [
                'Error' => array("ErrorCode" => 101, "ErrorMessage" => $errorMessage)
            ];
        }
    }

    public function response($response)
    {

        $orderId = $response["transactionId"];
        $orderAmount = $response["amount"] / 100;
        $referenceId = isset($response["providerReferenceId"]) ? $response["providerReferenceId"] : "";
        $txStatus = $response["code"];
        $checksum = $response["checksum"];
        $merchantId = $response["merchantId"];
        $payment_status = '';
        if ($response['code'] == "PAYMENT_SUCCESS") {
            $payment_status = 'Successful';
        } else {
            $payment_status = 'Failed';
        }
        $status_api_response = PHONEPE::check_status($response['transactionId']);
        if (isset($status_api_response['success']) && $status_api_response['success']) {
            if ($status_api_response['code'] == 'PAYMENT_SUCCESS' && $status_api_response['data']['state'] == 'COMPLETED' && $status_api_response['data']['responseCode'] == 'SUCCESS') {
                $payment_status = 'Successful';
            } else {
                $payment_status = 'Failed';
            }
        }

        $updatepaymentdata = array('status_api_response' => json_encode($status_api_response), 'payment_response' => json_encode($response), 'payment_status' => $payment_status);
        $PaymentModel = new PaymentModel();
        $PaymentModel->updateData('web_partner_payment_transaction', array('order_id' => $response["transactionId"]), $updatepaymentdata);
        return array('payment_status' => $payment_status, 'order_id' => $response["transactionId"], 'amount' => $orderAmount);
        //return $saveresponse;
    }

    public function check_status($order_no)
    {
        $url = $this->PaymentBaseURL . '/pg/v1/status/' . $this->merchantId . '/' . $order_no;
        $XVERIFY = hash("sha256", '/pg/v1/status/' . $this->merchantId . '/' . $order_no . $this->keySalt) . "###" . $this->keyIndex;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-VERIFY:' . $XVERIFY, 'X-MERCHANT-ID:' . $this->merchantId));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
    function Createorder($url, $requestData, $XVERIFY)
    {
        $url = $url . "/pg/v1/pay";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-VERIFY:' . $XVERIFY));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

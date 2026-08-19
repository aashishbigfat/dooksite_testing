<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

require_once APPPATH . 'ThirdParty/razorpaysdk/Razorpay.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RAZORPAY extends BaseController
{
    public $YOUR_SECRET;
    public $YOUR_KEY_ID;
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
        $gatewayInfo = $PaymentModel->gateway_setting($this->payment_gateway_table, 'RAZORPAY');

        $gatewayInfo = json_decode($gatewayInfo, true);
        $this->YOUR_KEY_ID = 'rzp_live_V7ZXCVpTnRdWxc';
        $this->YOUR_SECRET = '3DGA7aCs4u8iIspgBihabS4q';
        /* $this->YOUR_KEY_ID = $gatewayInfo['key_id'];
        $this->YOUR_SECRET = $gatewayInfo['secret_key']; */
    }



    public function request($request)
    {
        $tid = rand(1000, 99999999) . time();
        $order_id = rand(1000, 99999999) . time();
        $payment_option = get_razorpay_payment_mode($request['PaymentMode']);
        $card_type = $request['PaymentMode'];
        $orderCurrency = "INR";
        $tnxAmount = (round($request['Amount']) * 100);
        $tnxAmount = 100;
        $orderData = array(
            "amount" => $tnxAmount,
            "currency" => $request['Currency'],
            "receipt" => "rcptid_" . $order_id,
            "payment_capture" => 1,
        );
        $api = new Api($this->YOUR_KEY_ID, $this->YOUR_SECRET);

        $gatewayOrderData = $api->order->create($orderData);


        $GatewayPaymentDetails = array(
            'ID' => $gatewayOrderData['id'],
            "ORDER_ID" => $order_id,
            "customer_id" => $request['WebPartnerId'],
            "name" => $request['FirstName'] . ' ' . $request['LastName'],
            "email" => $request['Email'],
            "contact" => $request['MobileNumber'],
            "method" => $payment_option,
            "extraparmeter" => $gatewayOrderData['id'],
            "key" => $this->YOUR_KEY_ID,
            "amount" => $tnxAmount,
            "returnUrl" => $request['RedirectURL'],
        );

        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service']);
        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
            'user_id' => $request['UserId'],
            'transaction_id' => $tid,
            'order_id' => $gatewayOrderData['id'],
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'amount' => $request['Amount'],
            'payment_request' => json_encode($GatewayPaymentDetails),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'RAZORPAY',
            'razorpay_order_id' => $gatewayOrderData['id'],
            'self_order_id' => $order_id,
            'booking_prefix' => isset($servicePrefix['pre_fix']) ? $servicePrefix['pre_fix'] : null,
            'extraParam' => json_encode(array("gatewayOrderData" => $gatewayOrderData)),
            'service_log' => $request['ServiceLog'],
            'payment_source' => isset($request['PaymentSource']) ? $request['PaymentSource'] : "",
            'wl_customer_id' => isset($request['wl_customer_id']) ? $request['wl_customer_id'] : "",
            'actually_amounts' => isset($request['ActuallyAmounts']) ? $request['ActuallyAmounts'] : NULL,
            'default_currency' =>  isset($request['DefaultCurrency']) ? $request['DefaultCurrency'] : NULL,
            'selected_currency' =>  isset($request['BookingCurrency']) ? $request['BookingCurrency'] : NULL,
            'conversion_rate' =>  isset($request['ConversionRate']) ? $request['ConversionRate'] : NULL,
            'currency_symbol' =>  isset($request['CurrencySymbol']) ? $request['CurrencySymbol'] : NULL,
            'created' => create_date()
        );


        $PaymentModel = new PaymentModel();
        $insertTable = $request['TransactionTable'];
        $PaymentModel->insertData($insertTable, $payment_log);

        if (isset($gatewayOrderData['id'])) {
            $response = [
                'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
                'Result' => array("GatewayPaymentDetails" => $GatewayPaymentDetails)
            ];

            return $response;
        } else {
            $errorMessage = isset($redirecturl['reason']) ? $redirecturl['reason'] : "Error";
            return $response = [
                'Error' => array("ErrorCode" => 101, "ErrorMessage" => $errorMessage)
            ];
        }
    }

    public function response($response)
    {

        $apidata = array();
        $api = new Api($this->YOUR_KEY_ID, $this->YOUR_SECRET);
        $success = true;
        $error = "Sorry, your payment failed";
        $payment_status = 'Failed';
        if (empty($response['error']['reason'])) {
            $apidata = $api->payment->fetch($response['razorpay_payment_id']);
            try {
                $attributes = array(
                    'razorpay_order_id' => $apidata['order_id'],
                    'razorpay_payment_id' => $response['razorpay_payment_id'],
                    'razorpay_signature' => $response['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if (isset($response['razorpay_payment_id'])) {
            $respose = array(
                'id' => $apidata['id'],
                'entity' => $apidata['entity'],
                'amount' => $apidata['amount'],
                'currency' => $apidata['currency'],
                'status' => $apidata['status'],
                'order_id' => $apidata['order_id'],
                'invoice_id' => $apidata['invoice_id'],
                'international' => $apidata['international'],
                'method' => $apidata['method'],
                'amount_refunded' => $apidata['amount_refunded'],
                'refund_status' => $apidata['refund_status'],
                'captured' => $apidata['captured'],
                'description' => $apidata['description'],
                'card_id' => $apidata['card_id'],
                'bank' => $apidata['bank'],
                'wallet' => $apidata['wallet'],
                'vpa' => $apidata['vpa'],
                'email' => $apidata['email'],
                'contact' => $apidata['contact'],
                'extraparmeter' => $apidata['notes']['extraparmeter'],
                'fee' => $apidata['fee'],
                'tax' => $apidata['tax'],
                'error_code' => $apidata['error_code'],
                'error_description' => $apidata['error_description'],
                'error_source' => $apidata['error_source'],
                'error_step' => $apidata['error_step'],
                'error_reason' => $apidata['error_reason'],
                'acquirer_data' => isset($apidata['acquirer_data']['auth_code']) ? $apidata['acquirer_data']['auth_code'] : "",
                'created_at' => $apidata['created_at']
            );

            if ($success == "true" || isset($response['razorpay_payment_id'])) {
                $payment_status = 'Successful';
            } else {
                $payment_status = 'Failed';
            }
        } else {
            $payment_status = 'Failed';
        }
        $orderAmount = 0;
        if (isset($apidata['amount'])) {
            $orderAmount = round(($apidata['amount'] / 100), 2);
        }
        $updatepaymentdata = array('status_api_response' => json_encode($apidata), 'payment_response' => json_encode($response), 'payment_status' => $payment_status);
        $PaymentModel = new PaymentModel();
        if (isset($response["razorpay_order_id"])) {
            $PaymentModel->updateData($this->transaction_table, array('order_id' => $response["razorpay_order_id"]), $updatepaymentdata);
            return array('payment_status' => $payment_status, 'order_id' => $response['razorpay_order_id'], 'amount' => $orderAmount);
        } else {
            return array('payment_status' => $payment_status, 'order_id' => 0, 'amount' => $orderAmount);
        }
    }
}

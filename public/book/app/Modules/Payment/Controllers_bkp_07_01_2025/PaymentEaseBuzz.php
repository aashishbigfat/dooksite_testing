<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

require_once APPPATH . 'ThirdParty/easebuzz-lib/easebuzz_payment_gateway.php';

use Easebuzz;

class PaymentEaseBuzz extends BaseController
{

    public $web_partner_details;
    public $transaction_table;
    public $payment_gateway_table;

    private $MERCHANT_KEY = "Y4JF26IF96";
    private $SALT = "CF87SLCB1V";
    private $ENV = "prod";
 
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
        $gatewayInfo = $PaymentModel->gateway_setting($this->payment_gateway_table, 'EASEBUZZ');

        $gatewayInfo = json_decode($gatewayInfo, true);
        $this->MERCHANT_KEY = $gatewayInfo['MERCHANT_KEY'];
        $this->SALT = $gatewayInfo['SALT'];
        $this->ENV = $gatewayInfo['ENV'];
    }




    public function request($request)
    {
        $tid = rand(1000, 99999999) . time();
        $order_id = 'PTTS' . rand(1000, 99999999) . time();

        $postData = array(
            "txnid" => $order_id,
            "amount" => sprintf("%.2f", round($request['Amount'], 2)),
            "firstname" => $request['FirstName'] . ' ' . $request['LastName'],
            "email" => $request['Email'],
            "phone" => $request['MobileNumber'],
            "productinfo" => "Amount For " . str_replace("_", " ", $request['Service']),
            "surl" => $request['RedirectURL'],
            "furl" => $request['CancelURL'],
            "udf1" => $request['Service'],
            "udf2" => $request['BookingId'],
            "udf3" => "",
            "udf4" => $request['BookingId'],
            "udf5" => "",
            "address1" => web_partner_details['address'],
            "address2" => "",
            "city" => web_partner_details['city'],
            "state" => web_partner_details['state'],
            "country" => web_partner_details['country'],
            "zipcode" => web_partner_details['pincode'],
            "show_payment_mode" => get_easebuzz_payment_mode($request['PaymentMode'])
        );


        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service']);

        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
            'user_id' => $request['UserId'],
            'transaction_id' => $tid,
            'order_id' => $order_id,
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'amount' => $request['Amount'],
            'payment_request' => json_encode($postData),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'payment_mode' => $request['SavePaymentMode'],
            'booking_prefix' => isset($servicePrefix['pre_fix']) ? $servicePrefix['pre_fix'] : null,
            'payment_getway_name' => 'CCAVENUE',
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


        $insertTable = $request['TransactionTable'];
        $PaymentModel->insertData($insertTable, $payment_log);

        $paymentResponse = $easebuzzObj->initiatePaymentAPI($postData);

        if (isset($paymentResponse->status) && $paymentResponse->status == 0) {
            $response = [
                'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
                'Result' => array("url" => $paymentResponse->data)
            ];
            return $response;
        } else {
            $errorMessage = isset($paymentResponse['message']) ? $paymentResponse['message'] : "Error";
            return $response = [
                'Error' => array("ErrorCode" => 101, "ErrorMessage" => $errorMessage)
            ];
        }
    }

    public function response($response)
    {
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $raw_result = $easebuzzObj->easebuzzResponse($response);
        $result = json_decode($raw_result, true);

        $payment_status = '';
        if ($result['data']["status"] == "success") {
            $payment_status = 'Successful';
        } else {
            $payment_status = 'Failed';
        }
        $postData = array(
            "txnid" => $result['data']['txnid'],
            "amount" => $result['data']['amount'],
            "email" => $result['data']['email'],
            "phone" => $result['data']['phone']
        );
        $status_api_response = PaymentEaseBuzz::check_status($postData);
        if ($status_api_response['status']) {
            if ($status_api_response['msg']['status'] == 'success') {
                $payment_status = 'Successful';
            } else {
                $payment_status = 'Failed';
            }
        } else {
            $payment_status = 'Failed';
        }
        $updatepaymentdata = array('status_api_response' => json_encode($status_api_response), 'payment_response' => json_encode($result), 'payment_status' => $payment_status);
        $PaymentModel = new PaymentModel();
        $PaymentModel->updateData($this->transaction_table, array('order_id' => $result['data']["txnid"]), $updatepaymentdata);
        return array('payment_status' => $payment_status, 'order_id' => $result['data']["txnid"], 'amount' => $result['data']["amount"], 'tracking_id' => $result['data']["txnid"], 'transaction_id' => $result['data']["txnid"]);
    }

    function check_status($postData)
    {

        $easebuzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $transresult = $easebuzObj->transactionAPI($postData);

        return json_decode($transresult, true);
    }
}

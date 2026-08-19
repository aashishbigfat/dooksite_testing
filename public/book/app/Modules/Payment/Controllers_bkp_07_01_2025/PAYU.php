<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class PAYU extends BaseController
{

    public $payu_key;
    public $payu_salt;
    public $payu_base_url;
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
        $gatewayInfo = $PaymentModel->gateway_setting($this->payment_gateway_table, 'PAYU');

        $gatewayInfo = json_decode($gatewayInfo, true);
        $this->payu_key = $gatewayInfo['payu_key'];
        $this->payu_salt = $gatewayInfo['payu_salt'];
        if (strtolower($gatewayInfo['Mode']) === 'test') {
            $this->payu_base_url = "https://test.payu.in";
        } else {
            $this->payu_base_url = "https://test.payu.in";
        }
    }



    public function request($request)
    {
        $tid = rand(1000, 99999999) . time();
        $order_id = rand(1000, 99999999) . time();

        $data = array(
            'action' => $this->payu_base_url . '/_payment',
            'payuprovider' => 'payu',
            'key' => $this->payu_key,
            'salt' => $this->payu_salt,
            'txnid' => $tid,
            'surl' => $request['RedirectURL'],
            'furl' => $request['RedirectURL'],
            'curl' => $request['RedirectURL'],
            'productinfo' => trim('Amount For ' . $request['Service'] . ' Booking'),
            'amount' => round($request['Amount'], 2),
            'firstname' => $request['FirstName'],
            'lastname' => $request['LastName'],
            'phone' => $request['MobileNumber'],
            'email' => $request['Email']
        );

        $hash = PAYU::generateHash($data);
        $data['hash'] = $hash;

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
            'payment_request' => json_encode($data),
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

        $data = [
            'title' => 'Payment',
            'detail' => $data,
            'view' => "Payment\Views\payu\index",
        ];
        return view('template/default-layout', $data);
    }
    function generateHash($Payment_details)
    {
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach ($hashVarsSeq as $hash_var) {
            $hash_string .= isset($Payment_details[$hash_var]) ? $Payment_details[$hash_var] : '';
            $hash_string .= '|';
        }

        $hash_string .= $Payment_details['salt'];
        $hash = strtolower(hash('sha512', $hash_string));
        return $hash;
    }
    function payment_hash_check($data)
    {
        $status = $data["status"];
        $firstname = $data["firstname"];
        $amount = $data["amount"];
        $txnid = $data["txnid"];
        $posted_hash = $data["hash"];
        $key = $data["key"];
        $productinfo = $data["productinfo"];
        $email = $data["email"];
        $udf1 = $data["udf1"];
        $udf2 = $data["udf2"];
        $salt = $this->payu_salt;
        // Salt should be same Post Request 
        if (isset($data["additionalCharges"])) {
            $additionalCharges = $data["additionalCharges"];
            $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        } else {
            $retHashSeq = $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        }
        $hash = hash("sha512", $retHashSeq);
        if ($hash != $posted_hash) {

            return "failure";
        } else {

            if ($status == "success") {
                return "success";
            } else {
                return "failure";
            }
        }
    }
    public function response($response)
    {
        $status = PAYU::payment_hash_check($response);
        if ($status == 'success') {
            $payment_status = 'Successful';
        } else {
            $payment_status = 'Failed';
        }
        $orderAmount = $response["amount"];
        $txnid = $response["txnid"];
        $status_api_response = array();
        $updatepaymentdata = array('status_api_response' => json_encode($status_api_response), 'payment_response' => json_encode($response), 'payment_status' => $payment_status);
        $PaymentModel = new PaymentModel();
        $PaymentModel->updateData($this->transaction_table, array('order_id' => $txnid), $updatepaymentdata);
        return array('payment_status' => $payment_status, 'order_id' => $txnid, 'amount' => $orderAmount);
    }
}

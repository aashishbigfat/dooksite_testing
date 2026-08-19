<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class Amazon extends BaseController
{
    private $payment_gateway_table;
    protected $transaction_table;
    private $AccessCode;
    private $MerchantIdentifier;
    private $Signature;
    protected $PaymentBaseURL;
    protected $CheckStatusBaseURL;
    public $web_partner_id;

    public function __construct()
    {
        ini_set('serialize_precision', -1);
        $this->web_partner_id = web_partner_details['id'];
        if (whitelabel['payment_gateway_type'] === 'webpartner') {
            $this->payment_gateway_table = "web_partner_payment_gateway_mode_activation";
            $this->transaction_table = "web_partner_payment_transaction";
        } else {
            $this->payment_gateway_table = "super_admin_payment_gateway_mode_activation";
            $this->transaction_table = "super_admin_payment_transaction";
        }

        $PaymentModel = new PaymentModel();
        $gatewayInfos = $PaymentModel->gateway_setting($this->payment_gateway_table, 'AMAZONPAY');
        $gatewayInfo = json_decode($gatewayInfos, true);
        $this->AccessCode = $gatewayInfo['access_code'];
        $this->MerchantIdentifier = $gatewayInfo['merchant_identifier'];
        $this->Signature = $gatewayInfo['signature'];
        if (strtolower($gatewayInfo['Mode']) === 'test') {
            $this->PaymentBaseURL = "https://sbcheckout.payfort.com/FortAPI/paymentPage";
            $this->CheckStatusBaseURL = "https://sbpaymentservices.payfort.com/FortAPI/paymentApi";
        } else {
            $this->PaymentBaseURL = "https://checkout.payfort.com/FortAPI/paymentPage";
            $this->CheckStatusBaseURL = "https://paymentservices.payfort.com/FortAPI/paymentApi";
        }
    }


    function generateCustomId($prefix = 'INV', $length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomId = $prefix;
        for ($i = 0; $i < $length - strlen($prefix); $i++) {
            $randomId .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomId . time();
    }



    public function request($request)
    {
        $customInvoiceId = $this->generateCustomId('INV', 8);
        $tid = rand(1000, 99999999) . time();
        $amount = round($request['Amount']);
        $RequestParams = array(
            'command' => 'PURCHASE',
            'access_code' => "$this->AccessCode",
            'merchant_identifier' => "$this->MerchantIdentifier",
            'merchant_reference' => "$customInvoiceId",
            'amount' => "$amount",
            'currency' => "EGP",
            'language' => 'en',
            'customer_email' => $request['Email'],
            'phone_number' => $request['MobileNumber'],
            'return_url' => $request['RedirectURL'],
        );

        $shaString  = '';
        ksort($RequestParams);
        foreach ($RequestParams as $key => $value) {
            $shaString .= "$key=$value";
        }
        $shaString = $this->Signature . $shaString . $this->Signature;
        $signature = hash("sha256", $shaString);
        $RequestParams['signature'] = $signature;
        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service'], $request['WebPartnerId']);
        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
            'user_id' => $request['UserId'],
            'transaction_id' => $tid,
            'order_id' => $customInvoiceId,
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'amount' => $request['Amount'],
            'payment_request' => json_encode($RequestParams),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'actually_convenience_fee' => isset($request['ActuallyConvenienceFee']) ? $request['ActuallyConvenienceFee'] : 0,
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'AMAZONPAY',
            'self_order_id' => $customInvoiceId,
            'booking_prefix' => isset($servicePrefix['pre_fix']) ? $servicePrefix['pre_fix'] : null,
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
        $PaymentModel->insertData($insertTable, $payment_log);
        $response = [
            'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
            'Result' => array("GatewayPaymentDetails" => $RequestParams, "URL" => $this->PaymentBaseURL)
        ];
        return $response;
    }





    public function response($response)
    {
        // Check payment status from external response
        $responseData = $this->checkpaymentstatus($response);
        $checkpaymentstatus = [];
        if (!empty($responseData)) {
            $checkpaymentstatus = json_decode($responseData, true);
        }

        // Prepare the response for saving
        $finalresponseSave = [
            'After_check_status_response' => $response,
            'Before_check_status_response' => $checkpaymentstatus,
        ];

        $payment_status = 'Failed';

        // Check if the response is successful

        $isResponseSuccess = (isset($response['status'], $response['response_message'], $response['response_code']) && $response['status'] == "14" && $response['response_message'] == "Success" && $response['response_code'] == '14000');
        $isCheckPaymentStatusSuccess = (isset($checkpaymentstatus['transaction_status'], $checkpaymentstatus['response_message'], $checkpaymentstatus['transaction_code']) && $checkpaymentstatus['transaction_status'] == "14" && $checkpaymentstatus['response_message'] == "Success" && $checkpaymentstatus['transaction_code'] == '14000');

        // Set payment status to successful if all success conditions are met
        if ($isResponseSuccess && $isCheckPaymentStatusSuccess) {
            $payment_status = 'Successful';
        } 


        $PaymentModel = new PaymentModel();
        $paymentrecord = $PaymentModel->checkpayment_record($this->transaction_table, ['order_id' => $response['merchant_reference'], 'payment_status' => 'Processing','web_partner_id'=>$this->web_partner_id]);

        if (!empty($paymentrecord)) {
            $updatepaymentdata = ['status_api_response' => $responseData, 'payment_response' => json_encode($finalresponseSave), 'payment_status' => $payment_status];

            try {
                $PaymentModel = new PaymentModel();
                $PaymentModel->updateData('web_partner_payment_transaction', ['order_id' => $response['merchant_reference']], $updatepaymentdata);
            } catch (Exception $e) {
                return [
                    'error' => 'Database update failed',
                    'message' => $e->getMessage()
                ];
            }

            return [
                'payment_status' => $payment_status,
                'order_id' => $checkpaymentstatus['merchant_reference'],
                'amount' => $checkpaymentstatus['authorized_amount'],
            ];
        } else {
            return array('ErrorCode' => 4001, 'ErrorMessage' => 'Unauthorized request detected', 'order_id' => $response['merchant_reference']);
        } 

    }


    private function checkpaymentstatus($response)
    {
        if (!empty($response)) {
            $RequestData = array(
                'query_command' => 'CHECK_STATUS',
                'access_code' => isset($response['access_code']) ? $response['access_code'] : $this->AccessCode,
                'merchant_identifier' => isset($response['merchant_identifier']) ? $response['merchant_identifier'] : $this->MerchantIdentifier,
                'merchant_reference' => isset($response['merchant_reference']) ? $response['merchant_reference'] : '',
                'language' => 'en',
            );

            $shaString  = '';
            ksort($RequestData);
            foreach ($RequestData as $key => $value) {
                $shaString .= "$key=$value";
            }
            $shaString = $this->Signature . $shaString . $this->Signature;
            $signature = hash("sha256", $shaString);
            $RequestData['signature'] = $signature;


            $ch = curl_init($this->CheckStatusBaseURL);
            $data = json_encode($RequestData);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        } else {
            return $response;
        }
    }
}

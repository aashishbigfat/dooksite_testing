<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class Paypal extends BaseController
{
    private $payment_gateway_table;
    protected $transaction_table;
    private $clientId;
    private $clientSecret;
    protected $PaymentBaseURLAuthLive;
    protected $PaymentBaseURLAuthTest;
    protected $PaymentBaseURLLIVE;
    protected $PaymentBaseURLTEST;
    private $sandbox;
    protected $acceccToken;
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
        $gatewayInfos = $PaymentModel->gateway_setting($this->payment_gateway_table, 'PAYPAL');

        $gatewayInfo = json_decode($gatewayInfos, true);
        $this->clientId = trim($gatewayInfo['clientId']);
        $this->clientSecret = trim($gatewayInfo['clientSecret']);

        if (strtolower($gatewayInfo['Mode']) === 'test') {
            $this->PaymentBaseURLAuthTest = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
            $this->PaymentBaseURLTEST = 'https://api-m.sandbox.paypal.com/v1/payments/payment';
            $this->sandbox = true;
        } else {
            $this->PaymentBaseURLAuthLive = "https://api-m.paypal.com/v1/oauth2/token";
            $this->PaymentBaseURLLIVE = 'https://api-m.paypal.com/v1/payments/payment';
            $this->sandbox = false;
        }

        $getAuthTokenData = $this->getAuthToken();
        $this->acceccToken = '';
        if (!empty($getAuthTokenData)) {
            $this->acceccToken = $getAuthTokenData;
        }
    }
    /* ********************************************************* */ 
        /*  Paypal Payment Gateway Integration By Abhay */  
    /* ********************************************************* */


    public function getAuthToken()
    {
        $BasicAuth = base64_encode(trim($this->clientId) . ':' . trim($this->clientSecret));
        $url = $this->sandbox ? $this->PaymentBaseURLAuthTest : $this->PaymentBaseURLAuthLive;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization:Basic ' . trim($BasicAuth)
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response);
        if (isset($responseData->access_token)) {
            return $responseData->access_token;
        } else {
            throw new \Exception('Error fetching PayPal access token');
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
      
        $totalAmountFormatted = number_format($request['Amount'], 2, '.', '');
        $customInvoiceId = $this->generateCustomId('INV', 8);
        $tid = rand(1000, 99999999) . time();
        $url = $this->sandbox ? $this->PaymentBaseURLTEST : $this->PaymentBaseURLLIVE;

        $paymentData = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal',
            ],
            'transactions' => [
                [
                    "reference_id" => $customInvoiceId,
                    'amount' => [
                        'total' => $totalAmountFormatted,
                        'currency' => $request['BookingCurrency'],
                       /*  'currency' => 'USD', */
                    ],
                    'description' => "The payment transaction description.",
                    'invoice_number' => $customInvoiceId,
                    'notify_url' => $request['RedirectURL'],
                ],
            ],

            'redirect_urls' => [
                'return_url' => $request['RedirectURL'],
                'cancel_url' => $request['RedirectURL'],
            ]
        ];



        $PaymentModel = new PaymentModel();
        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service'],$request['WebPartnerId']);
        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
            'user_id' => $request['UserId'],
            'transaction_id' => $tid,
            'order_id' => $customInvoiceId,
            'payment_status' => 'Processing',
            'service' => $request['Service'],
            'booking_ref_no' => $request['BookingId'],
            'amount' => $request['Amount'],
            'payment_request' => json_encode($paymentData),
            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'mobile_number' => $request['MobileNumber'],
            'email_id' => $request['Email'],
            'convenience_fee' => $request['convenience_fee'],
            'actually_convenience_fee' => isset($request['ActuallyConvenienceFee']) ? $request['ActuallyConvenienceFee'] : 0,
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'PAYPAL',
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




        // cURL setup to create the payment
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->acceccToken
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));

        $response = curl_exec($ch); 
        $CURLERROR = '';
        if (curl_errno($ch)) {
            $CURLERROR =  'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $responseData = json_decode($response);
        $URLRETURN = [];
        if (isset($responseData->links)) {
            foreach ($responseData->links as $link) {
                if ($link->rel === 'approval_url') {
                    $URLRETURN = $link->href;
                }
            }
        }

        $responsemessage = [
            'Error' => array("ErrorCode" => 0, "ErrorMessage" => $CURLERROR),
            'Result' => array("GatewayPaymentDetails" => $responseData, "URL" => $URLRETURN)
        ];

        return $responsemessage;
    }


    public function verifyPayment($paymentId, $token, $PayerID)
    {
        $url = $this->sandbox ? $this->PaymentBaseURLTEST . '/' . $paymentId . '/execute' : $this->PaymentBaseURLLIVE . '/' . $paymentId . '/execute';
        $paymentData = [
            "payer_id" => $PayerID
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $this->acceccToken"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
        $response = curl_exec($ch);
        $CURLERROR = '';
        if (curl_errno($ch)) {
            $CURLERROR =  'Error:' . curl_error($ch);
        }

        curl_close($ch);
        $jsonResponse = json_decode($response);
        $ErrorCode = 401;
        $invoice_number = '';
        if (isset($jsonResponse->state) && $jsonResponse->state == 'approved') {
            $ErrorCode = 0;
            $getPaymentDetailsResponse = $this->getPaymentDetails($jsonResponse->id);
            $invoice_number = isset($getPaymentDetailsResponse['Result']['getPaymentDetails']->transactions[0]->invoice_number) ? $getPaymentDetailsResponse['Result']['getPaymentDetails']->transactions[0]->invoice_number : '';

            $isResponseSuccess = (isset($getPaymentDetailsResponse['Error']['ErrorCode'], $getPaymentDetailsResponse['Error']['ErrorMessage'], $getPaymentDetailsResponse['Result']['Status']) && $getPaymentDetailsResponse['Error']['ErrorCode'] == "0" && $getPaymentDetailsResponse['Error']['ErrorMessage'] == "Success" && $getPaymentDetailsResponse['Result']['Status'] == 'approved');
            if ($isResponseSuccess) {
                $responsemessage = [
                    'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $getPaymentDetailsResponse['Error']['ErrorMessage']),
                    'Result' => array("getPaymentDetails" => json_encode($getPaymentDetailsResponse), "InvoiceNumber" => $invoice_number)
                ];
                return $responsemessage;
            } else {
                $responsemessage = [
                    'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $getPaymentDetailsResponse['Error']['ErrorMessage']),
                    'Result' => array("getPaymentDetails" => json_encode($getPaymentDetailsResponse), "InvoiceNumber" => $invoice_number)
                ];
                return $responsemessage;
            }
        } else {
            $responsemessage = [
                'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $CURLERROR),
                'Result' => array("getPaymentDetails" => ($response), "InvoiceNumber" => $invoice_number)
            ];
            return $responsemessage;
        }
    }


    private function getPaymentDetails($paymentId)
    {
        $url = $this->sandbox ? $this->PaymentBaseURLTEST . '/' . $paymentId : $this->PaymentBaseURLLIVE . '/' . $paymentId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->acceccToken"
        ]);
        $response = curl_exec($ch);
        $CURLERROR = '';
        if (curl_errno($ch)) {
            $CURLERROR =  'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $jsonResponse = json_decode($response);

        $Status = 'failed';
        $ErrorCode = 401;
        if (isset($jsonResponse->state)) {
            $Status = $jsonResponse->state;
            $ErrorCode = 0;
        }
        $responsemessage = [
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $CURLERROR),
            'Result' => array("getPaymentDetails" => $jsonResponse, "Status" => $Status)
        ];

        return $responsemessage;
    }



    


    public function response($response)
    {  
        $getPaymentDetails =  $response['Paypalpaymentresponse']->Result->getPaymentDetails;   
      
        $payment_status = 'Failed'; 
        $isResponseSuccess = (isset($response['Paypalpaymentresponse']->Result->Status, $response['Paypalpaymentresponse']->Result->getPaymentDetails->state, $response['Paypalpaymentresponse']->Result->getPaymentDetails->payer->status) && $response['Paypalpaymentresponse']->Result->Status == "approved" && $response['Paypalpaymentresponse']->Result->getPaymentDetails->state == "approved" && $response['Paypalpaymentresponse']->Result->getPaymentDetails->payer->status == 'VERIFIED');
        if ($isResponseSuccess) {
            $payment_status = 'Successful';
        }



        $PaymentModel = new PaymentModel();
        $paymentrecord = $PaymentModel->checkpayment_record($this->transaction_table, ['order_id' => $response['PaypalInvoiceNumber'], 'payment_status' => 'Processing','web_partner_id'=>$this->web_partner_id]);

        if (!empty($paymentrecord)) {
            $updatepaymentdata = ['status_api_response' => json_encode($getPaymentDetails), 'payment_response' => json_encode($getPaymentDetails), 'payment_status' => $payment_status];

            try { 
                $PaymentModel->updateData('web_partner_payment_transaction', ['order_id' => $response['PaypalInvoiceNumber']], $updatepaymentdata);
            } catch (Exception $e) {
                return [
                    'error' => 'Database update failed',
                    'message' => $e->getMessage()
                ];
            }
    
            return [
                'payment_status' => $payment_status,
                'order_id' => $response['PaypalInvoiceNumber'],
                'amount' => $response['Paypalpaymentresponse']->Result->getPaymentDetails->transactions[0]->amount->total,
            ];
        } else {
            return array('ErrorCode' => 4001, 'ErrorMessage' => 'Unauthorized request detected', 'order_id' => $response['PaypalInvoiceNumber']);
        } 











        
    }
}

 
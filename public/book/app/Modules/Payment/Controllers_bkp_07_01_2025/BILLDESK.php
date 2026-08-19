<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class BILLDESK extends BaseController
{

    public $APIStatusURL;
    public $PaymentBaseURL;
    public $web_partner_details;
    public $merchantId;
    public $Client_ID;
    public $Key_ID;
    public $Encryption_Key;
    public $transaction_table;
    public $payment_gateway_table;
    public $keyIndex;
    public $keySalt;

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
        $gatewayInfo = $PaymentModel->gateway_setting($this->payment_gateway_table, 'BILLDESK');
        $gatewayInfo = json_decode($gatewayInfo, true);
       
        $this->merchantId = $gatewayInfo['Merchant_ID'];
        $this->Client_ID = $gatewayInfo['Client_ID'];
        $this->Key_ID = $gatewayInfo['Key_ID'];
        $this->Encryption_Key = $gatewayInfo['Encryption_Key'];

        $this->keyIndex = '';
        $this->keySalt = '';
        if (strtolower($gatewayInfo['Mode']) === 'test') {
            $this->PaymentBaseURL = "https://uat1.billdesk.com/u2/payments/ve1_2/orders/create";
        } else {
            $this->PaymentBaseURL = "	https://api.billdesk.com/";
        }
    }

    public function request($request)
    {
        $order_id = $this->generateOrderId();
        $OrderDate = date('Y-m-d\TH:i:sP');
        $getMoreInfo = $this->getGenerateMoreInfo();
        $customInvoiceId = $this->generateCustomId('INV', 8);
        $amount = round_value($request['Amount']);
        $BillDeskRequestData = array(
            "orderid" => $order_id,
            "mercid" => $this->merchantId,
            "order_date" => $OrderDate,
            "amount" => $amount,
            "currency" => "356",
            "ru" =>  $request['RedirectURL'],
            "itemcode" => "DIRECT",
            "device" => array(
                "init_channel" => "internet",
                "ip" => $getMoreInfo['getIpAddress'],
                "user_agent" => $getMoreInfo['getUserAgent'],
                "accept_header" => $getMoreInfo['getAcceptHeader']
            ),
            "customer" => array(
                "first_name" => isset($request['FirstName']) ? $request['FirstName'] : "Just",
                "last_name" => isset($request['LastName']) ? $request['LastName'] : "Checking"
            ),
            "additional_info" => array(
                "additional_info1" => !empty($request['AdditionalInfo1']) ? $request['AdditionalInfo1'] : "Details1",
                "additional_info2" => !empty($request['AdditionalInfo2']) ? $request['AdditionalInfo2'] : "Details2",
                "additional_info3" => !empty($request['AdditionalInfo3']) ? $request['AdditionalInfo3'] : "Details3",
            ),
            "invoice" => array(
                "invoice_number" => $customInvoiceId,
                "customer_name" => isset($request['FirstName']) ? $request['FirstName'] : "Just",
                "invoice_date" => date('Y-m-d'),
                "invoice_display_number" => $customInvoiceId,
            ),
        );





        $jsonRequest = json_encode($BillDeskRequestData);

        $encryptedRequest = $this->encryptRequest($jsonRequest, $this->Encryption_Key, $this->Key_ID, $this->Client_ID);


        $signedRequest = $this->signRequest($encryptedRequest, $this->Encryption_Key, $this->Key_ID, $this->Client_ID);


        $signedRequest = $this->checkedRequest($signedRequest);




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




 


 

    /* --------------------------------------------------- */



    private function encryptRequest($jsonRequest, $encryptionKey, $keyId, $clientId)
    {
        
        $iv = random_bytes(12); // AES-GCM requires a 12-byte IV
        $ciphertext = openssl_encrypt($jsonRequest, 'aes-256-gcm', $encryptionKey, OPENSSL_RAW_DATA, $iv, $tag); 

     
        $jweHeader = [
            "alg" => "dir",
            "enc" => "A256GCM",
            "kid" => $keyId,
            "clientid" => $clientId
        ];
        
        // Base64 encode components
        $encodedHeader = base64_encode(json_encode($jweHeader));
        $encodedIv = base64_encode($iv);
        $encodedCiphertext = base64_encode($ciphertext);
        $encodedTag = base64_encode($tag);

        // Concatenate as JOSE compact serialization
        return "$encodedHeader.$encodedIv.$encodedCiphertext.$encodedTag";
    }



    private function signRequest($encryptedRequest, $signingKey, $keyId, $clientId)
    {
        $jwsHeader = [
            "alg" => "HS256",
            "kid" => $keyId,
            "clientid" => $clientId
        ];

        // Base64 encode header
        $encodedHeader = base64_encode(json_encode($jwsHeader));

        // Generate signature
        $signature = hash_hmac('sha256', "$encodedHeader.$encryptedRequest", $signingKey, true);
        $encodedSignature = base64_encode($signature);

        // Construct JWS
        return "$encodedHeader.$encryptedRequest.$encodedSignature";
    }



    private function checkedRequest($signedRequest)
    {
 
        $bdTimestamp = date('YmdHis');
        $bdTraceID = strtoupper(uniqid('TID', true));
        $headers = [
            'Content-Type: application/jose',
            'Accept: application/jose',
            'BD-Traceid: ' . $bdTraceID,
            'BD-Timestamp: ' . $bdTimestamp
        ];

        $apiEndpoint = $this->PaymentBaseURL;

        $ch = curl_init($apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $signedRequest);

        $response = curl_exec($ch);
        pr($response);
        exit;
        if (curl_errno($ch)) {
            echo 'Request Error:' . curl_error($ch);
        }
        curl_close($ch);
    }




    function verifyResponse($response, $signingKey)
    {
        list($encodedHeader, $encodedPayload, $encodedSignature) = explode('.', $response);

        // Verify signature
        $calculatedSignature = base64_encode(hash_hmac('sha256', "$encodedHeader.$encodedPayload", $signingKey, true));

        if ($calculatedSignature !== $encodedSignature) {
            throw new Exception("Invalid signature.");
        }

        return base64_decode($encodedPayload);
    }

    function decryptResponse($encryptedPayload, $encryptionKey)
    {
        list($encodedHeader, $encodedIv, $encodedCiphertext, $encodedTag) = explode('.', $encryptedPayload);

        $iv = base64_decode($encodedIv);
        $ciphertext = base64_decode($encodedCiphertext);
        $tag = base64_decode($encodedTag);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);
    }

    // Verify and decrypt
    /*  $verifiedPayload = verifyResponse($response, $signingKey);
    $decryptedResponse = decryptResponse($verifiedPayload, $encryptionKey); */





    /* --------------------------------------------------- */








    private function generateOrderId()
    {
        $randomPart = str_pad(rand(100000, 999999), 4, '0');
        $timestampPart = substr(time(), -8);
        $randomNumber = $randomPart . $timestampPart;
        return "TTSORID" . $randomNumber;
    }


    private function getGenerateMoreInfo()
    {
        $request_service = service('request');
        $ip = $request_service->getIpAddress();
        if (strlen($ip) > 15) {
            $ip = substr($ip, 0, 15);
        }
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (strlen($user_agent) > 2048) {
            $user_agent = substr($user_agent, 0, 2048);
        }
        $accept_header = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
        if (strlen($accept_header) > 255) {
            $accept_header = substr($accept_header, 0, 255);
        }
        $MoreInfo = [
            "getIpAddress"    => $ip,
            "getUserAgent"    => $user_agent,
            "getAcceptHeader" => $accept_header,
        ];
        return $MoreInfo;
    }


    private function generateCustomId($prefix = 'INV', $length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomId = $prefix;
        for ($i = 0; $i < $length - strlen($prefix); $i++) {
            $randomId .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomId . time();
    }
}

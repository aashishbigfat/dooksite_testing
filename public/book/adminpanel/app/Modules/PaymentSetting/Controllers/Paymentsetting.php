<?php

namespace Modules\PaymentSetting\Controllers;

use App\Controllers\BaseController;
use Modules\PaymentSetting\Config\Validation;
use App\Modules\PaymentSetting\Models\WebpartnerPaymentGatewayModeActivationModel;

class Paymentsetting extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $web_partner_details;  
    protected $admin_comapny_detail;   

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Payment Setting"; 
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        if (permission_access_error("Setting", "Setting_Module")) {
        }
       
    }

    public function index()
    {
        if (permission_access_error("Setting", "payment_setting_list")) {
            $PaymentGatewayMode = new WebpartnerPaymentGatewayModeActivationModel();
            $PaymentGatewayModeList = $PaymentGatewayMode->getPaymentGatewayMode($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'PaymentGatewaylist' => $PaymentGatewayModeList,
                'pager' => $PaymentGatewayMode->pager,
                'view' => "PaymentSetting\Views\index"
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function edit_payment_setting_template()
    {
        if (permission_access_error("Setting", "payment_setting_edit")) {
            $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));
            $PaymentGatewayMode = new WebpartnerPaymentGatewayModeActivationModel();
            $PaymentGateway = $PaymentGatewayMode->edit_payment_gateway_mode($id,$this->web_partner_id);
            
            $payment_gateway = $PaymentGateway['payment_gateway'];
            $Credentials = $this->PaymentGatewayCredentialField($payment_gateway);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $PaymentGateway,
                'credentials' => $Credentials
            ];
            $details = view('Modules\PaymentSetting\Views/payment_gateway_mode', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }




    public function edit_payment_setting()
    {
        if (permission_access_error("Setting", "payment_setting_edit")) {
            $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));
            $data = $this->request->getPost(); 
            $PaymentGatewayMode = new WebpartnerPaymentGatewayModeActivationModel();
        
            $paymentModeArray = $data['payment_mode']; 
            $data['payment_mode'] = implode(',', $paymentModeArray);
        
            // Convert credentials to JSON 
            if (isset($data['credentials'])) {
                $data['credentials'] = json_encode($data['credentials']);
            }
            $data['modified'] = create_date(); 
        
            $added_data = $PaymentGatewayMode->where("id", $id)->where('web_partner_id', $this->web_partner_id)->set($data)->update(); 

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Payment Setting Successfully Updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Payment Setting not  Updated", "Class" => "error_popup", "Reload" => "true");
            } 
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
         
    } 



    public function api_supplier_status_change()
    { 
        if (permission_access_error("Setting", "payment_setting_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else { 
                $PaymentGatewayMode = new WebpartnerPaymentGatewayModeActivationModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $PaymentGatewayMode->getPaymentGatewayMode_status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Payment Setting status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Payment Setting status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
        
            }
        }
    }


    private function PaymentGatewayCredentialField($payment_gateway_name = null)
    {
        $api_field = array(
            'PHONEPE' => array(
                'phonepe_merchant_id' => '',
                'phonepe_key_index' => '',
                'phonepe_key_salt' => '',
                'Mode' => '',
            ),
            'PAYU' => array(
                'payu_key' => '',
                'payu_salt' => '',
                'Mode' => '',
            ),
            'RAZORPAY' => array(
                'key_id' => '',
                'secret_key' => '',
                'Mode' => '',
            ),
            'CASHFREE' => array(
                'app_id' => '',
                'secret_key' => '',
                'Mode' => '',
            ),
            'CCAVENUE' => array(
                'ccavenue_merchant_id' => '',
                'ccavenue_working_key' => '',
                'ccavenue_access_code' => '',
                'ccavenue_merchant_id' => '',
                'Mode' => '',
            ),
            'ICICIBANK' => array(
                'ccavenue_merchant_id' => '',
                'ccavenue_working_key' => '',
                'ccavenue_access_code' => '',
                'ccavenue_merchant_id' => '',
                'Mode' => '',
            ),
            'HDFCBANK' => array(
                'merchant_id' => '',
                'working_key' => '',
                'access_code' => '',
                'merchant_id' => '',
                'Mode' => '',
            ),
            'HDFC' => array(
                'merchant_id' => '',
                'working_key' => '',
                'access_code' => '',
                'merchant_id' => '',
                'Mode' => '',
            ),
            'ICICI' => array(
                'merchant_id' => '',
                'working_key' => '',
                'access_code' => '',
                'merchant_id' => '',
                'Mode' => '',
            ),
            'EASEBUZZ' => array(
                'MERCHANT_KEY' => '',
                'SALT' => '',
                'ENV' => '',
                'Mode' => '',
            ),
            'PAYPAL' => array(
                'clientId' => '',
                'clientSecret' => '',
                'Mode' => '',
            ),
            'BILLDESK' => array(
                'Merchant_ID' => '',
                'Client_ID' => '',
                'Key_ID' => '',
                'Encryption_Key' => '', 
                'Mode' => '',
            ),
            'AMAZONPAY' => array(
                'access_code' => '',
                'merchant_identifier' => '',
                'signature' => '', 
                'Mode' => '',
            ),
            'PAYPAL' => array(
                'clientId' => '',
                'clientSecret' => '',
                'Mode' => '',
            ),

        );

        $response = array();
        if ($payment_gateway_name) {
            if (array_key_exists($payment_gateway_name, $api_field)) {
                $response = $api_field[$payment_gateway_name];
            }
        } else {
            $response = $api_field;
        }
        return $response;
    }
}

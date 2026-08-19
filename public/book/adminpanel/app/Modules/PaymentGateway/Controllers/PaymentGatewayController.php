<?php

namespace Modules\PaymentGateway\Controllers;

use App\Controllers\BaseController;
use App\Modules\PaymentGateway\Models\PaymentModel;
use Modules\PaymentGateway\Config\Validation;


class PaymentGatewayController extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $web_partner_details;  
    protected $admin_comapny_detail;  
    protected $whitelabel_setting;  
   

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "PaymentGateway";
        helper('Modules\PaymentGateway\Helpers\gateway');
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->whitelabel_setting = admin_cookie_data()['whitelabel_setting_data'];
    }



    public function index()
    {
        $PaymentModel = new PaymentModel();

        $data = [   
            'title' => $this->title,
            'list' => $PaymentModel->gatewayList($this->web_partner_id),
            'view' => "PaymentGateway\Views\gateway-list",
            'pager' => $PaymentModel->pager,
        ];

 
        return view('template/sidebar-layout', $data);
    }


    function addGatewayView()
    {
        $payment_gateway = array();
        if (strtolower($this->whitelabel_setting['payment_gateway_type']) === 'webpartner') {
            $payment_gateway = explode(',', $this->whitelabel_setting['payment_gateway_name']);
        }

        $data = [
            'title' => $this->title,
            'payment_gateway' => $payment_gateway,
        ];

        $gatewayDetail = view('Modules\PaymentGateway\Views\add-payment-gateway', $data);
        $data = array("StatusCode" => 0, "Message" => $gatewayDetail, 'class' => 'success_popup');
        return $this->response->setJSON($data);

    }



    public function addGateway()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->updateGateway());
        if ($rules) {
            $data = $this->request->getPost();
            $PaymentModel = new PaymentModel();
            
            $payment_mode = $data['payment_mode'];
            if (in_array('credit_card', $payment_mode) && (in_array('rupay_credit_card', $payment_mode) || in_array('visa_credit_card', $payment_mode) || in_array('mastercard_credit_card', $payment_mode) || in_array('american_express_credit_card', $payment_mode))) {
                $errors['payment_mode.*'] = "Choose either any credit card or particular credit cards";
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            }
            
            
            $data['created'] = create_date();
            $data['web_partner_id'] = $this->web_partner_id;
            $dd = implode(",", $data['payment_mode']);
            unset($data['payment_mode']);
            $data['payment_mode'] = $dd;
            $details = $PaymentModel->insert($data);
            if ($details) {
                $RedirectUrl = site_url('payment-gateway');
                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            } else {
                $RedirectUrl = site_url('payment-gateway');
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => 'Payment Gateway not updated', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            }
        } else {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }




    public function editGateway()
    {
        $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));
        $PaymentModel = new PaymentModel();


        $payment_gateway = array();
        if (strtolower($this->whitelabel_setting['payment_gateway_type']) === 'webpartner') {
            $payment_gateway = explode(',', $this->whitelabel_setting['payment_gateway_name']);
        }
        $data = [
            'title' => $this->title,
            'id' => $id,
            'payment_gateway' => $payment_gateway,
            'detail' => $PaymentModel->gatewayDetail($id),
        ];

        $gateway_detail = view('Modules\PaymentGateway\Views\edit-payment-gateway', $data);
        $data = array("StatusCode" => 0, "Message" => $gateway_detail, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    function updateGateway()
    {
        $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));

        $validate = new Validation();
        $rules = $this->validate($validate->updateGateway($id));
        if ($rules) {
            $data = $this->request->getPost();
            $PaymentModel = new PaymentModel();

            $payment_mode = $data['payment_mode'];
            if (in_array('credit_card', $payment_mode) && (in_array('rupay_credit_card', $payment_mode) || in_array('visa_credit_card', $payment_mode) || in_array('mastercard_credit_card', $payment_mode) || in_array('american_express_credit_card', $payment_mode))) {
                $errors['payment_mode.*'] = "Choose either any credit card or partcular credit cards";
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            }

            $modes = implode(",", $data['payment_mode']);
            unset($data['payment_mode']);
            $data['payment_mode'] = $modes;
            $data['modified'] = create_date();
            $details = $PaymentModel->update($id, $data);
            if ($details) {
                $RedirectUrl = site_url('payment-gateway');
                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            } else {
                $RedirectUrl = site_url('payment-gatway');
                $data_validation = array("StatusCode" => 3, "ErrorMessage" => 'Payment Gateway not updated', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            }
        } else {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }



    function removeGateway()
    {
        $data = $this->request->getPost();
        foreach ($data['checklist'] as $key) {
            $PaymentModel = new PaymentModel();
            $PaymentModel->where('id', $key)->delete();
        }
        $RedirectUrl = site_url('payment-gateway');
        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
        return $this->response->setJSON($data_validation);
    }
}
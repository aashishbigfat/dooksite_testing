<?php

namespace Modules\Accounts\Controllers;

use App\Modules\Accounts\Models\MakePaymentModel;
use App\Modules\Accounts\Models\AccountsModel;
use App\Modules\Accounts\Models\WhiteLabelMakePaymentModel;
use App\Modules\Agent\Models\AgentAccountLogModel;
use App\Controllers\BaseController;
use Modules\Accounts\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Accounts extends BaseController
{

    protected $title; 
    protected $web_partner_id;
    protected $web_partner_details;
    protected $user_id; 
    protected $admin_comapny_detail; 
    protected $make_payment_upload_folder; 
    protected $web_partner_class_id; 
    protected $gateway_name; 


    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Accounts";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->make_payment_upload_folder = "make_payment";
        $this->web_partner_class_id =  $this->admin_comapny_detail['web_partner_class_id'];
        $this->gateway_name =  "HDFC";
    }

    public function index()
    {
        $WebPartnerAccountModel = new WebPartnerAccountModel();
        $webpartnerInfo = array();
        if ($this->request->getGet() && $this->request->getGet('key-value') && $this->request->getGet('key-value') != "") {
            $webpartnerInfo = $WebPartnerAccountModel->search_webpartner($this->request->getGet());
        }
        $data = [
            'title' => $this->title,
            'view' => "WebPartnerAccount\Views\index",
            'webpartnerInfo' => $webpartnerInfo,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);
    }


    public function wl_payment_history()

    {
        if (permission_access_error("Accounts", "payment_history_list")) {
            $WhiteLabelMakePaymentModel = new WhiteLabelMakePaymentModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $WhiteLabelMakePaymentModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $WhiteLabelMakePaymentModel->payment_history($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Accounts\Views\wl-payment-history",
                'pager' => $WhiteLabelMakePaymentModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function wl_payment_history_detail()
    {
        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));  
        $WhiteLabelMakePaymentModel = new WhiteLabelMakePaymentModel();

        $details = $WhiteLabelMakePaymentModel->payment_history_detail($id, $this->web_partner_id);
        unset($details['web_partner_id']);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $details = view('Modules\Accounts\Views\wl-payment-history-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }
    public function wl_payment_status_change()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->payment_status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();


            $WhiteLabelMakePaymentModel = new WhiteLabelMakePaymentModel();
            $payment_id = dev_decode($data['payment_id']);
            $store_data['status'] = $data['status'];
            $store_data['admin_remark'] = $data['admin_remark'];
            $store_data['agent_staff_id'] = $this->user_id;
            $update = $WhiteLabelMakePaymentModel->payment_status_change($payment_id, $store_data);

            $details = $WhiteLabelMakePaymentModel->payment_history_detail($payment_id,$this->web_partner_id);

            if ($data['status'] == 'approved') {
                $web_partner_id = $details['web_partner_id'];
                $wl_agent_id = $details['wl_agent_id'];

                $available_balance = $WhiteLabelMakePaymentModel->available_balance($web_partner_id,$wl_agent_id);

                $account_data['remark'] = $data['admin_remark'];
                $account_data['credit'] = $details['amount'];
                $account_data['payment_mode'] = 'Account_Transfer';
                $account_data['web_partner_id'] = $web_partner_id;
                $account_data['wl_agent_id'] = $wl_agent_id;
                $account_data['user_id'] = $this->user_id;
                $account_data['created'] = create_date();
                $account_data['transaction_type'] = 'credit';
                $account_data['action_type'] = 'recharge';
                $account_data['role'] = 'web_partner';
                $account_data['acc_ref_number'] = mt_rand(100000, 999999);
                if (!isset($available_balance['balance'])) {
                    $available_balance['balance'] = 0;
                }



                $account_data['balance'] = $available_balance['balance'] + $account_data['credit'];

                $credit_amount = 'INR '.$account_data['credit'];

                $available_balance= 'INR '.$account_data['balance'];

                $AgentAccountLogModel = new AgentAccountLogModel();

                $added_data_id = $AgentAccountLogModel->insert($account_data);

                $update_account['acc_ref_number'] = reference_number($added_data_id);

                $AgentAccountLogModel->where("id", $added_data_id)->set($update_account)->update();

                $type = 'credited';

                $extra_parameter ='';

                $message = "Dear Travel Partner,{$credit_amount} has been successfully {$type} your wallet {$extra_parameter}. Your available balance is {$available_balance}.Team Tourista";

                /*$to_mob = $user_data['mobile_no'];
                $tempid = $this->template_id_wallet_update;
                $sms_type = 'credit balance';
                send_sms($to_mob, $message, $tempid, $sms_type);*/


            }

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "payment status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "payment status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function MakePaymentView()
    {
        $AccountsModel = new AccountsModel();
        $MakePaymentModel = new MakePaymentModel();
        $data = [
            'title' => $this->title,
            'view' => "Accounts\Views\make-payment",
            'superAdminBankAccountinfo' => $AccountsModel->super_admin_bank_account_info(),
            'ConvenienceFeeInfo' => $MakePaymentModel->convenience_fee('Make_Payment',$this->web_partner_class_id,$this->gateway_name),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function MakePayment()
    {

        $Validation = new Validation();
        $upload_file = $this->request->getFile('upload_file');
        $rules = $this->validate($Validation->makePaymentRequest($this->request->getPost(), $upload_file));
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $AccountsModel = new AccountsModel();
            $postData = $this->request->getPost();
           
            $insertData['web_partner_id'] = $this->web_partner_id;
            $insertData['agent_staff_id'] = $this->user_id;
            $insertData['amount'] = $postData['amount'];
            $insertData['payment_mode'] = $postData['payment_mode'];
            $insertData['remark'] = $postData['remark'];
            $insertData['cheque_draft_utr_number'] = isset($postData['cheque_draft_utr_number']) ? $postData['cheque_draft_utr_number'] : null;
            $insertData['payment_date'] = isset($postData['date']) && $postData['date'] != "" ? strtotime($postData['date']) : create_date();
            $insertData['bank'] = isset($postData['bank']) ? $postData['bank'] : null;
            $insertData['bank_branch'] = isset($postData['branch']) ? $postData['branch'] : null;
            $insertData['bank_transaction_id'] = isset($postData['bank_transaction_id']) ? $postData['bank_transaction_id'] : null;
            $insertData['status'] = 'pending';
            $insertData['created'] = create_date();
            if (isset($postData['company_bank_account']) && $postData['company_bank_account'] != "") {
                $company_Bank_account_detail = $AccountsModel->super_admin_bank_account_details($postData['company_bank_account']);
                $insertData['company_bank_name'] = isset($company_Bank_account_detail['bank_name']) ? $company_Bank_account_detail['bank_name'] : null;
                $insertData['company_branch_name'] = isset($company_Bank_account_detail['branch_name']) ? $company_Bank_account_detail['branch_name'] : null;
                $insertData['company_account_holder_name'] = isset($company_Bank_account_detail['account_holder_name']) ? $company_Bank_account_detail['account_holder_name'] : null;
                $insertData['company_account_no'] = isset($company_Bank_account_detail['account_no']) ? $company_Bank_account_detail['account_no'] : null;
                $insertData['company_ifsc_code'] = isset($company_Bank_account_detail['ifsc_code']) ? $company_Bank_account_detail['ifsc_code'] : null;
                $insertData['company_swift_code'] = isset($company_Bank_account_detail['swift_code']) ? $company_Bank_account_detail['swift_code'] : null;
            }
            if ($upload_file->getName() != '') {

                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'upload_file';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 560, 'height' => 360);
                $image_upload = image_upload($file, $field_name, $this->make_payment_upload_folder, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $insertData['file_name'] = $image_upload['file_name'];
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }


            }
            $insertId = $AccountsModel->insertData("web_partner_make_payment", $insertData);
            $Redirect_Url = "";
            $Reload = "true";
            $StatusCode = 0;
            $onlinepaymentMode = array('CreditCard', 'DebitCard', 'NetBanking', 'UPIPayments','RuPayCreditCard','VisaCreditCard','MastercardCreditCard','AmericanExpressCreditCard');
            if (in_array($postData['payment_mode'], $onlinepaymentMode)) {
                $Reload = "true";
                $StatusCode = 3;
                $Redirect_Url = site_url('payment/make-payment/' . dev_encode($insertId));
            }
            if ($insertId) {
                $message = array("StatusCode" => $StatusCode, "Message" => "Make payment request successfully created", "Class" => "success_popup", "Reload" => $Reload, "Redirect_Url" => $Redirect_Url);
            } else {
                $message = array("StatusCode" => 2, "Message" => "Unable to created make payment request", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }


    }

    public function payment_history()
    {
        $MakePaymentModel = new MakePaymentModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $MakePaymentModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $MakePaymentModel->payment_history($this->web_partner_id);
        }


        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Accounts\Views\payment-history",
            'pager' => $MakePaymentModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function payment_history_detail()
    {
        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3)); 
        $MakePaymentModel = new MakePaymentModel();

        $details = $MakePaymentModel->payment_history_detail($id, $this->web_partner_id);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $details = view('Modules\Accounts\Views\payment-history-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }

}

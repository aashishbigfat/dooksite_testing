<?php

namespace Modules\Customer\Controllers;

use App\Modules\Customer\Models\CustomerModel;
use App\Modules\Customer\Models\CustomerAccountLogModel;

use App\Controllers\BaseController;
use Modules\Customer\Config\Validation;

use PhpParser\Node\Expr\PreDec;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Customer extends BaseController
{

    protected $title;
    protected $web_partner_id;
    protected $web_partner_details;
    protected $customer_id_prefix;
    protected $user_id;
    protected $whitelabel_user;
    protected $folder_name;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Customer";
        $this->folder_name = 'customer';

        $admin_cookie_data = admin_cookie_data();
        $this->web_partner_id = $admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->user_id = $admin_cookie_data['admin_user_details']['id'];
        $this->customer_id_prefix = $admin_cookie_data['admin_comapny_detail']['customer_pre_fix'];
        $this->whitelabel_user = $admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $whitelabel_setting_data = $admin_cookie_data['whitelabel_setting_data'];


        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['b2c_business'] != "active") {
            access_denied();
        }
        if (permission_access_error("Customer", "Customer_Module")) {
        }
    }

    public function index()
    {
        $CustomerModel = new CustomerModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CustomerModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $CustomerModel->customer_list($this->web_partner_id);
        }

        $data = [
            'UserIp' => $this->request->getIpAddress(),
            'title' => $this->title,
            'list' => $lists,
            'view' => "Customer\Views\customer-list",
            'pager' => $CustomerModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function add_customer_view()
    {
        if (permission_access("Customer", "add_customer")) {
            $add_slider_view = view('Modules\Customer\Views\add-customer');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_customer()
    {
        if (permission_access("Customer", "add_customer")) {
            $validate = new Validation();
            $resizeDim = array('width' => 360, 'height' => 200);
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $field_name_profile_pic = 'profile_pic';
            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->customer_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->customer_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $rules = $this->validate($validate->customer_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $CustomerModel = new CustomerModel();
                $data = $this->request->getPost();
                /* optional file upload */
                $checkRegisterCustomer = $CustomerModel->checkRegisterCustomer($data['email_id'], $this->web_partner_id);
                if ($checkRegisterCustomer > 0) {
                    $error['email_id'] = "this customer is already registered. ";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($error));
                    return $this->response->setJSON($data_validation);
                }
                if ($profile_pic->getName() != '') {
                    $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->folder_name, $resizeDim);
                    if ($profile_pic_upload['status_code'] == 0) {
                        $profile_pic = $profile_pic_upload['file_name'];
                    } else {
                        $profile_pic = null;
                        $message = array("StatusCode" => 2, "Message" => $profile_pic_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $profile_pic = null;
                }

                /*code to send email */
                $send_status = 0;
                if (isset($data['send_email'])) {
                    if ($data['send_email'] == 'send') {
                        $send_status = 1;
                        unset($data['send_email']);
                    }
                }

                /*end code to send email */

                /*password encryption*/
                $password = md5($this->request->getPost('password'));
                $data['password'] = $password;
                /*end password encryption*/
                $email_verify = $this->request->getPost('email_verify');
                $mobile_verify = $this->request->getPost('mobile_verify');
                if ($mobile_verify == 'Verified') {
                    $data['mobile_verify'] = 1;
                } else {
                    $data['mobile_verify'] = 0;
                }
                if ($email_verify == 'Verified') {
                    $data['email_verify'] = 1;
                } else {
                    $data['email_verify'] = 0;
                }

                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data[$field_name_profile_pic] = $profile_pic;
                $data['created'] = create_date();
                $added_data_id = $CustomerModel->insert($data);
                $updateData['customer_id'] = $this->customer_id_prefix . $added_data_id;
                $CustomerModel->where(array("web_partner_id" => $this->web_partner_id, "id" => $added_data_id))->set($updateData)->update();
                $log['customer_id'] = $added_data_id;
                $log['web_partner_id'] = $this->web_partner_id;
                $log['created'] = create_date();
                $log['action_type'] = 'credit';
                $data['credit'] = 0;
                $log['balance'] = 0;
                $log['role'] = 'web_partner';
                $log['user_id'] = $this->user_id;
                $log['remark'] = 'Account created by admin';
                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $account_log_added = $CustomerAccountLogModel->insert($log);
                if ($added_data_id) {
                    if ($send_status == 1) {
                        $email = [
                            "email" => $this->request->getPost('email_id'),
                            "mobile_no" => $this->request->getPost('mobile_no'),
                            "password" => $this->request->getPost('password'),
                            "message" => "Thanks for signing up! We’re so glad you decided to be part of the family."
                        ];
                        $subject = "Congratulations! Your account has been created successfully.";
                        $message = view('Views/emails/common-registration-email', $email);
                        $email_type = 'Your Account Has Been Created';
                        send_email($email['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);
                    }
                    $message = array("StatusCode" => 0, "Message" => "Customer Successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_customer_view()
    {
        if (permission_access("Customer", "edit_customer")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));

            $CustomerModel = new CustomerModel();
            $details = $CustomerModel->customer_details($id, $this->web_partner_id);
            if (isset($details['dob']) && $details['dob'] != '') {
                $details['dob'] = timestamp_to_date($details['dob']);
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\Customer\Views\edit-customer', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_customer()
    {
        if (permission_access("Customer", "edit_customer")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));


            $validate = new Validation();
            unset($validate->customer_validation['password']);
            $validate->customer_validation['email_id']['rules'] = "required|valid_email";

            $resizeDim = array('width' => 360, 'height' => 200);
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $field_name_profile_pic = 'profile_pic';
            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->customer_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->customer_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $rules = $this->validate($validate->customer_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $data = $this->request->getPost();
                $checkRegisterCustomer = $CustomerModel->checkRegisterCustomer($data['email_id'], $this->web_partner_id, $id);
                if ($checkRegisterCustomer > 0) {
                    $error['email_id'] = "this customer is already registered. ";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($error));
                    return $this->response->setJSON($data_validation);
                }
                $previous_data = $CustomerModel->customer_details($id, $this->web_partner_id);
                /* optional file upload */

                if ($profile_pic->getName() != '') {
                    $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->folder_name, $resizeDim);
                    if ($profile_pic_upload['status_code'] == 0) {
                        $profile_pic = $profile_pic_upload['file_name'];
                        if ($previous_data[$field_name_profile_pic]) {
                            if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name_profile_pic])) {
                                unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name_profile_pic]);
                                unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_profile_pic]);
                            }
                        }
                    } else {
                        $profile_pic = null;
                        $message = array("StatusCode" => 2, "Message" => $profile_pic_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $profile_pic = $previous_data[$field_name_profile_pic];
                }
                /* end optional file upload */

                $email_verify = $this->request->getPost('email_verify');
                $mobile_verify = $this->request->getPost('mobile_verify');
                if ($mobile_verify == 'Verified') {
                    $data['mobile_verify'] = 1;
                } else {
                    $data['mobile_verify'] = 0;
                }
                if ($email_verify == 'Verified') {
                    $data['email_verify'] = 1;
                } else {
                    $data['email_verify'] = 0;
                }
                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                $data[$field_name_profile_pic] = $profile_pic;
                $data['modified'] = create_date();
                $added_data = $CustomerModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Customer Successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function customer_status_change()
    {
        if (permission_access("Customer", "customer_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $CustomerModel->customer_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Customer status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function change_customer_password()
    {
        if (permission_access("Customer", "change_password")) {
            $validate = new Validation();
            $rules = $this->validate($validate->customer_password_change);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $id = dev_decode($this->request->getPost('customer_id'));
                $CustomerModel = new CustomerModel();

                $details = $CustomerModel->customer_details($id, $this->web_partner_id);
                $EmailId = $details['email_id'];
                $send_email = $this->request->getPost('send_email'); //send email pending

                $data['password'] = md5($this->request->getPost('password'));
                /*code to send email */
                $send_status = 0;
                if (isset($send_email) && $send_email) {
                    if ($send_email == 'send') {
                        $send_status = 1;
                        unset($send_email);
                    }
                }

                $update = $CustomerModel->where("id", $id)->set($data)->update();

                if ($update) {

                    if ($send_status == 1) {
                        $PaddwordReset = [
                            "email" => $EmailId,
                            "password" => $this->request->getPost('password'),
                            "passwordMessage" => "Congratulations! Your password has been reset successfully."
                        ];

                        $subject = "Congratulations! Your password has been reset successfully.";
                        $message = view('Views/emails/common-registration-email', $PaddwordReset);
                        $email_type = 'Password Reset';
                        send_email($PaddwordReset['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);
                    }

                    $message = array("StatusCode" => 0, "Message" => "Customer Password  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer Password not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_customer()
    {
        if (permission_access("Customer", "delete_customer")) {
            $CustomerModel = new CustomerModel();
            $ids = $this->request->getPost('checklist');
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $field_name_profile_pic = "profile_pic";

            foreach ($ids as $id) {
                $details = $CustomerModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name_profile_pic]) {
                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $details[$field_name_profile_pic])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $details[$field_name_profile_pic]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $details[$field_name_profile_pic]);
                    }
                }
                $CustomerAccountLogModel->customer_account_log($id, $this->web_partner_id);
                $delete = $CustomerModel->remove_customer($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Customer  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Customer  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

/*     public function getCustomer()
    {
        $terms = trim($this->request->getGet('term'));
        $CustomerModel = new CustomerModel();
        $CustomerAccountLogModel = new CustomerAccountLogModel();
        $customerInfo = $CustomerModel->customerinfo($terms, $this->web_partner_id);
        $availableCustomerInfo = [];
        if (!empty($customerInfo)) {
            foreach ($customerInfo as $info) {
                $customerAcountBalance = $CustomerAccountLogModel->available_balance($info['id'], $this->web_partner_id);
                $customerBalance = isset($customerAcountBalance['balance']) ? $customerAcountBalance['balance'] : 0;
                $availableCustomerInfo[] = ['id' => $info['id'], 'customer_name' => $info['first_name'] . ' ' . $info['last_name'], 'label' => $info['first_name'] . ' ' . $info['last_name'], 'email_id' => $info['email_id'], 'mobile_number' => $info['mobile_no'], 'balance' => $customerBalance];
            }
        }
        echo json_encode($availableCustomerInfo);
    } */



    public function getCustomer()
    {
        $terms = trim($this->request->getGet('term'));
        $CustomerModel = new CustomerModel();
        $CustomerAccountLogModel = new CustomerAccountLogModel();
        $customerInfo = $CustomerModel->customerinfo($terms, $this->web_partner_id);
        $availableCustomerInfo = [];
        if (!empty($customerInfo)) {
            foreach ($customerInfo as $info) {
                $customerAccountBalance = $CustomerAccountLogModel->available_balance($info['id'], $this->web_partner_id);
                $customerBalance = isset($customerAccountBalance['balance']) ? $customerAccountBalance['balance'] : 0;

                if (!empty($info['first_name']) && !empty($info['last_name'])) {
                    $customerName = $info['first_name'] . ' ' . $info['last_name'];
                    $label = $info['first_name'] . ' ' . $info['last_name'];
                } elseif (!empty($info['mobile_no'])) {
                    $customerName = $info['mobile_no'];
                    $label = $info['mobile_no'];
                } else {
                    $customerName = $info['email_id'];
                    $label = $info['email_id'];
                }
                $availableCustomerInfo[] = [
                    'id' => $info['id'],
                    'customer_name' => $customerName,
                    'label' => $label,
                    'email_id' => $info['email_id'],
                    'mobile_number' => $info['mobile_no'],
                    'balance' => $customerBalance
                ];
            }
        }
        echo json_encode($availableCustomerInfo);
    }


    public function customer_details()
    {
        $uri = $this->request->getUri();
        $customer_id = dev_decode($uri->getSegment(3));
        $CustomerModel = new CustomerModel();
        $details = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
        $CustomerAccountLogModel = new CustomerAccountLogModel();
        $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
        $details['balance'] = isset($available_balance['balance']) ? $available_balance['balance'] : 0;
        $data = [
            'title' => $this->title,
            'id' => $customer_id,
            'details' => $details
        ];
        $details = view('Modules\Customer\Views\customer-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function customer_account_logs(): string
    {
        if (permission_access_error("Customer", "account_logs")) {
            $uri = $this->request->getUri();
            $customer_id = dev_decode($uri->getSegment(3));
            $CustomerModel = new CustomerModel();
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
            $details = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            $details['balance'] = $available_balance['balance'];
            $searchData = array();
            if (isset($_GET['key'])) {
                $searchData = $this->request->getGet();
                $account_logs = $CustomerAccountLogModel->account_logs($customer_id, $this->web_partner_id, $searchData);
                $pager = $CustomerAccountLogModel->pager;
            } else {
                $account_logs = $CustomerAccountLogModel->account_logs($customer_id, $this->web_partner_id);
                $pager = $CustomerAccountLogModel->pager;
            }
            if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
                Customer::export_customer_account_logs($account_logs);
            }
            $data = [
                'title' => $this->title,
                'account_logs' => $account_logs,
                'details' => $details,
                'search_bar_data' => $searchData,
                'view' => "Customer\Views\customer-account-logs-list",
                'pager' => $pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function virtual_topup_view()
    {
        if (permission_access("Customer", "virtual_topup")) {
            $uri = $this->request->getUri();
            $customer_id = dev_decode($uri->getSegment(3));
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $CustomerModel = new CustomerModel();

            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

            $data['details'] = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Customer\Views\topup-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_topup()
    {
        if (permission_access("Customer", "virtual_topup")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_credit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $uri = $this->request->getUri();
                $customer_id = dev_decode($uri->getSegment(3));
                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['customer_id'] = $customer_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "credit";
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['credit'] = $Postdata['credit'];
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $CustomerAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id)['id'];
                }
                $data['balance'] = $available_balance['balance'] + $Postdata['credit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);

                $added_data = $CustomerAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data);
                $CustomerAccountLogModel->where(["id" => $added_data, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit_view()
    {
        if (permission_access("Customer", "virtual_deduct")) {
            $uri = $this->request->getUri();
            $customer_id = dev_decode($uri->getSegment(3));
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $CustomerModel = new CustomerModel();

            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

            $data['details'] = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Customer\Views\debit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit()
    {
        if (permission_access("Customer", "virtual_deduct")) {
            $validate = new Validation();
            $rules = $this->validate($validate->virtual_debit);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $uri = $this->request->getUri();
                $customer_id = dev_decode($uri->getSegment(3));

                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['customer_id'] = $customer_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "debit";
                $data['debit'] = $Postdata['debit'];
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $CustomerAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id)['id'];
                }
                $data['balance'] = $available_balance['balance'] - $Postdata['debit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);
                $added_data_id = $CustomerAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data_id);
                $CustomerAccountLogModel->where(["id" => $added_data_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function export_customer()
    {
        if (permission_access("Customer", "customer_export")) {
            $rules = $this->validate([
                'from_date' => [
                    'label' => 'From Date',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select from date.'
                    ]
                ],
                'to_date' => [
                    'label' => 'To Date',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select to date.'
                    ]
                ]
            ]);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            }

            $fileName = 'customer.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:S1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A:S")->getFont()->setName('Arial')->setSize(11);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            $sheet->getColumnDimension('R')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);

            $sheet->setCellValue('A1', 'Sr. No.');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Mobile');
            $sheet->setCellValue('E1', 'Mobile Verify');
            $sheet->setCellValue('F1', 'Email Verify');
            $sheet->setCellValue('G1', 'DOB');
            $sheet->setCellValue('H1', 'Marital Status');
            $sheet->setCellValue('I1', 'Address');
            $sheet->setCellValue('J1', 'City');
            $sheet->setCellValue('K1', 'State');
            $sheet->setCellValue('L1', 'Country');
            $sheet->setCellValue('M1', 'Pin Code');
            $sheet->setCellValue('N1', 'status');
            $sheet->setCellValue('O1', 'Balance');
            $sheet->setCellValue('P1', 'Last Login Time');
            $sheet->setCellValue('Q1', 'Login IP Address');
            $sheet->setCellValue('R1', 'modified');
            $sheet->setCellValue('S1', 'Created Date');


            $rows = 2;
            $CustomerModel = new CustomerModel();
            $customer_excel = $CustomerModel->customer_export($this->web_partner_id, $this->request->getPost());

            foreach ($customer_excel as $key => $val) {
                if ($val['modified']) {
                    $modified = date_created_format($val['modified']);
                } else {
                    $modified = '';
                }

                if ($val['dob']) {
                    $dob = timestamp_to_date($val['dob']);
                } else {
                    $dob = '';
                }

                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['title'] . ' ' . $val['first_name'] . ' ' . $val['last_name']);
                $sheet->setCellValue('C' . $rows, $val['email_id']);
                $sheet->setCellValue('D' . $rows, $val['mobile_no']);
                $sheet->setCellValue('E' . $rows, $val['mobile_verify']);
                $sheet->setCellValue('F' . $rows, $val['email_verify']);
                $sheet->setCellValue('G' . $rows, $dob);
                $sheet->setCellValue('H' . $rows, $val['marital_status']);
                $sheet->setCellValue('I' . $rows, $val['address']);
                $sheet->setCellValue('J' . $rows, $val['city']);
                $sheet->setCellValue('K' . $rows, $val['state']);
                $sheet->setCellValue('L' . $rows, $val['country']);
                $sheet->setCellValue('M' . $rows, $val['pin_code']);
                $sheet->setCellValue('N' . $rows, $val['status']);
                $sheet->setCellValue('O' . $rows, $val['balance']);
                $sheet->setCellValue('P' . $rows, $val['last_login_time']);
                $sheet->setCellValue('Q' . $rows, $val['login_ip_address']);
                $sheet->setCellValue('R' . $rows, $modified);
                $sheet->setCellValue('S' . $rows, date_created_format($val['created']));
                $rows++;
            }
            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function accountUpdateLogRemark()
    {
        if (permission_access("Customer", "update_remark")) {
            $validate = new Validation();
            $rules = $this->validate($validate->accountUpdateLogRemark);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {


                $uri = $this->request->getUri();
                $wl_customer_data = dev_decode($uri->getSegment(3));

                $added_data_id = explode("-", $wl_customer_data)[0];
                $customer_id = explode("-", $wl_customer_data)[1];
                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $data = array();
                $Postdata = $this->request->getPost();
                if ($Postdata['action_type'] == "booking") {
                    $data['invoice_number'] = $Postdata['invoice_number'];
                }

                $data['user_id'] = $this->user_id;
                $data['modified'] = create_date();
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                $added_data_id = $CustomerAccountLogModel->where(["id" => $added_data_id, 'web_partner_id' => $this->web_partner_id, 'customer_id' => $customer_id])->set($data)->update();

                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account remark successfully updated", "Class" => "success_popup", "Reload" => "false");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account remark", "Class" => "error_popup", "Reload" => "false");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function export_customer_account_logs($account_logs)
    {
        if (permission_access_error("Customer", "export_account_logs")) {
            $fileName = 'customer-Account-Logs' . "." . 'xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->mergeCells('A1:K1');
            $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1', 'Customer Account logs');
            $sheet->getStyle("A1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
            $sheet->getStyle("A2:K2")->getFont()->setBold(true)->setName('Arial')->setSize(11);
            $sheet->getStyle("A:K")->getFont()->setName('Arial')->setSize(11);

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);

            $sheet->setCellValue('A2', 'Sr.No.');
            $sheet->setCellValue('B2', 'Customer Name/Id');
            $sheet->setCellValue('C2', 'Invoice No.');
            $sheet->setCellValue('D2', 'Credit Note No.');
            $sheet->setCellValue('E2', 'Debit');
            $sheet->setCellValue('F2', 'Credit');
            $sheet->setCellValue('G2', 'Balance');
            $sheet->setCellValue('H2', 'Date');
            $sheet->setCellValue('I2', 'Payment Type');
            $sheet->setCellValue('J2', 'Staff Name');
            $sheet->setCellValue('K2', 'Remark');
            $rows = 3;
            foreach ($account_logs as $key => $val) {
                $PaymentType = "";
                $transaction_id = '';
                if (isset($val['transaction_id']) && $val['transaction_id'] != '') {
                    $PaymentType = $PaymentType . 'Transaction Id -' . $val['transaction_id'] . " ";
                }
                if (isset($val['payment_mode']) && $val['payment_mode'] != '') {
                    $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . $val['payment_mode'];
                } else {
                    $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . 'Wallet';
                }
                if ($val['action_type'] == 'refund') {
                    $creditNoteNumber = "-";
                    $invoiceNumber = "-";
                    $creditNoteNumber = $val['invoice_number'];
                } else {
                    $creditNoteNumber = "-";
                    $invoiceNumber = "-";
                    $invoiceNumber = $val['invoice_number'];
                }
                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['customerName'] . "(" . $val['customer_id'] . ")");
                $sheet->setCellValue('C' . $rows, $invoiceNumber);
                $sheet->setCellValue('D' . $rows, $creditNoteNumber);
                $sheet->setCellValue('E' . $rows, $val['debit']);
                $sheet->setCellValue('F' . $rows, $val['credit']);
                $sheet->setCellValue('G' . $rows, $val['balance']);
                $sheet->setCellValue('H' . $rows, date_created_format($val['created']));
                $sheet->setCellValue('I' . $rows, $PaymentType);
                $sheet->setCellValue('J' . $rows, $val['web_partner_staff_name']);
                $sheet->setCellValue('K' . $rows, $val['remark']);
                $rows++;
            }

            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);
            $fileSize = filesize($tempFile);
            while (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . $fileSize);
            header('Cache-Control: max-age=0');
            readfile($tempFile);
            unlink($tempFile);
            exit;
        }
    }
    public function view_remark()
    {

        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));
        $CustomerAccountLogModel = new CustomerAccountLogModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'data' => $CustomerAccountLogModel->view_remark_detail($id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\Customer\Views\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
}

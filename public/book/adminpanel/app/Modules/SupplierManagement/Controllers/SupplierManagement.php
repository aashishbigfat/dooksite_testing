<?php

namespace Modules\SupplierManagement\Controllers;

use App\Modules\SupplierManagement\Models\ApiSupplierFlightMgtModel;
use App\Controllers\BaseController;
use Modules\SupplierManagement\Config\Validation;


class SupplierManagement extends BaseController
{


    protected $title; 
    protected $web_partner_id;  
    protected $whitelabel_setting_data;  

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Supplier Management";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data']; 
    }

    public function index()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $ApiSupplierFlightMgtModel->search_data($this->request->getGet());
            } else {
                $lists = $ApiSupplierFlightMgtModel->supplier_mgt_list();
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "SupplierManagement\Views/flight-api-supplier-mgt-list",
                'pager' => $ApiSupplierFlightMgtModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        } else {
            access_denied();
        }
    }

    public function add_flight_api_mgt_template()
    {
        //if (permission_access_error("Feedback", "add_feedback")) {
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();
        $data = [
            'title' => $this->title,
            'supplier_list' => $supplier_list
        ];
        $add_blog_view = view('Modules\SupplierManagement\Views\add-flight-api-mgt', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }




    public function add_flight_api_mgt()
    {
        //if (permission_access_error("Feedback", "add_feedback")) {
        $data = $this->request->getPost();
        $validate = new Validation();

        /*   if ($data['allowed_airline']=='' && $data['excluded_airline']==''){
               $validate->flight_api_supllier_mgt_validation['allowed_airline']['rules'] = "required";
           }*/

        $rules = $this->validate($validate->flight_api_supllier_mgt_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
                $message = array("StatusCode" => 2, "Message" => "please select atlest one allowed airline or excluded airline", "Class" => "error_popup");
                return $this->response->setJSON($message);
            } else {

                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();


                $allowed_airline_array = explode(',', $data['allowed_airline']);
                $airline_code = "";
                foreach ($allowed_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['allowed_airline'] = substr_replace($airline_code, "", -2);


                $excluded_airline_array = explode(',', $data['excluded_airline']);
                $airline_code = "";
                foreach ($excluded_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['excluded_airline'] = substr_replace($airline_code, "", -2);

                if (isset($data['fare_type'])) {
                    $data['fare_type'] = implode(',', $data['fare_type']);
                }


                $data['created'] = create_date();

                $added_data = $ApiSupplierFlightMgtModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "data successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "data not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        //}
    }

    public function edit_flight_api_mgt_template()
    {
        //if (permission_access_error("Feedback", "edit_feedback")) {
      
        $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3)); 
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();
        $details = $ApiSupplierFlightMgtModel->details($id);
        if ($details['allowed_airline']) {
            $details['allowed_airline'] = $details['allowed_airline'] . ',';
        }
        if ($details['excluded_airline']) {
            $details['excluded_airline'] = $details['excluded_airline'] . ',';
        }
        $details['fare_type'] = explode(',', $details['fare_type']);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'supplier_list' => $supplier_list,
            'details' => $details,
        ];
        $blog_details = view('Modules\SupplierManagement\Views\edit-flight-api-mgt', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        //}
    }


    public function edit_flight_api_mgt()
    {

        //if (permission_access_error("Feedback", "edit_feedback")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3)); 
        $data = $this->request->getPost();
        $validate = new Validation();

        unset($validate->flight_api_supllier_mgt_validation['status']);

        /*if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
            $validate->flight_api_supllier_mgt_validation['allowed_airline']['rules'] = "required";
        }*/

        $rules = $this->validate($validate->flight_api_supllier_mgt_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
                $message = array("StatusCode" => 2, "Message" => "please select atlest one allowed airline or excluded airline", "Class" => "error_popup");
                return $this->response->setJSON($message);
            } else {

                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();


                $allowed_airline_array = explode(',', $data['allowed_airline']);
                $airline_code = "";
                foreach ($allowed_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['allowed_airline'] = substr_replace($airline_code, "", -2);


                $excluded_airline_array = explode(',', $data['excluded_airline']);
                $airline_code = "";
                foreach ($excluded_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['excluded_airline'] = substr_replace($airline_code, "", -2);

                if (isset($data['fare_type'])) {
                    $data['fare_type'] = implode(',', $data['fare_type']);
                }
                $data['modified'] = create_date();
                $added_data = $ApiSupplierFlightMgtModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "data successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "data not updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        //}
    }


    public function remove_flight_api_mgt()
    {
        //if (permission_access_error("Feedback", "delete_feedback")) {
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $ids = $this->request->getPost('checklist');
        $delete = $ApiSupplierFlightMgtModel->remove($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "record successfully Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "record  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }

    public function flight_api_mgt_status_change()
    {

        //if (permission_access_error("Feedback", "feedback_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $ApiSupplierFlightMgtModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => " status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => " status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }



    public function api_supplier()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            if (permission_access_error("APISuppliers", "APISuppliers_list")) {
                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
                $ApiSupplier = $ApiSupplierFlightMgtModel->api_supplier($this->web_partner_id);

                $data = [
                    'title' => $this->title,
                    'allsupplier' => $ApiSupplier,
                    'view' => "SupplierManagement\Views/api_supplier_list",
                ];
                return view('template/sidebar-layout', $data);
            } else {
                access_denied();
            }
        } else {
            access_denied();
        }
    }




    public function edit_api_supplier_template()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            if (permission_access_error("APISuppliers", "APISuppliers_edit")) {
                $uri = $this->request->getUri();   
                $id =  dev_decode($uri->getSegment(3)); 
                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
                $ApiSupplier = $ApiSupplierFlightMgtModel->edit_api_supplier($id,$this->web_partner_id);

                $supplier_name = $ApiSupplier['supplier_name'];
                $Credentials = $this->APICredentialField($supplier_name);

                $StaticSupplier = array('INDIGO');
                $api_account_group = [];
                if (in_array($supplier_name, $StaticSupplier)) {
                    $api_account_id = $ApiSupplierFlightMgtModel->api_account_id();
                    if ($api_account_id) {
                        foreach ($api_account_id as $account_id) {
                            $api_supplier_name = $account_id['api_supplier_name'];
                            unset($account_id['api_supplier_name']);
                            $api_account_group[$api_supplier_name][] = $account_id;
                        }
                    }
                }

                $data = [
                    'title' => $this->title,
                    'id' => $id,
                    'details' => $ApiSupplier,
                    'credentials' => $Credentials,
                    'api_account_group' => $api_account_group
                ];
                $details = view('Modules\SupplierManagement\Views/edit_api_supplier', $data);
                $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
                return $this->response->setJSON($data);
            } else {
                access_denied();
            }
        } else {
            access_denied();
        }
    }


    public function edit_api_supplier()
    {
        if (permission_access_error("APISuppliers", "APISuppliers_edit")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3)); 
            $data = $this->request->getPost();
            $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();

            // Check if API account groups exist and update them
            if (isset($data['api_account_group']) && !empty($data['api_account_group'])) {
                foreach ($data['api_account_group'] as $group_key => $api_account) {
                    foreach ($api_account as $account_key => $account) {
                        $primary_id = $account['id'];
                        $account['modified'] = create_date();
                        $account['web_partner_id'] =   $this->web_partner_id;
                        unset($account['id']);
                        // Update API account data
                        $added_data = $ApiSupplierFlightMgtModel->updateData('api_account_id', ['id' => $primary_id], $account);
                        if (!$added_data) {
                            // Handle update failure
                            return $this->response->setJSON(["StatusCode" => 2, "Message" => "Failed to update API account", "Class" => "error_popup"]);
                        }
                    }
                }
            }

            // Convert credentials to JSON 
            if (isset($data['credentials'])) {
                $data['credentials'] = json_encode($data['credentials']);
            }

            $data['modified'] = create_date(); 
            $data['web_partner_id'] =   $this->web_partner_id;
            // Unset specified keys
            $keysToRemove = ['supplier_name', 'api_account_group', 'temp'];
            foreach ($keysToRemove as $key) {
                if (isset($data[$key])) {
                    unset($data[$key]);
                }
            }

            // Update API supplier data
            $added_data = $ApiSupplierFlightMgtModel->updateData("api_supplier", ["id" => $id], $data);

            if ($added_data) {
                $message = ["StatusCode" => 0, "Message" => "API supplier successfully updated", "Class" => "success_popup", "Reload" => true];
            } else {
                $message = ["StatusCode" => 2, "Message" => "Failed to update API supplier", "Class" => "error_popup"];
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            access_denied();
        }
    }





    public function api_supplier_status_change()
    {
        if (permission_access_error("APISuppliers", "APISuppliers_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $ApiSupplierFlightMgtModel->api_supplier_status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Api Supplier status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Api Supplier status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            access_denied();
        }
    }

    private function APICredentialField($supplier_name = null)
    {
        $api_field = array(
            'TBO' => array(
                'UserName' => '',
                'Password' => '',
                'Mode' => '',
            ),
            'KAFILA' => array(
                'A_ID' => '',
                'U_ID' => '',
                'PWD' => '',
                'Mode' => '',
            ),
            'TRAVELPORT' => array(
                'UserName' => '',
                'Password' => '',
                'TargetBranch' => '',
                'PPC' => '',
                'Provider' => '',
                'AuthorizedBy' => '',
                'Mode' => '',
            ),
            'INDIGO' => array(
                'UserName' => '',
                'Password' => '',
                'TargetBranch' => '',
                'PPC' => '',
                'Provider' => '',
                'AuthorizedBy' => '',
                'Mode' => '',
            ),
            'TRIPJACK' => array(
                'ApiKey' => '',
                'Mode' => '',
            ),
            'JUSTCLICK' => array(
                'UserId' => '',
                'Password' => '',
                'CustomerMobile' => '',
                'Mode' => '',
            ),
            'MYSTIFLY' => array(
                'AccountNumber' => '',
                'UserName' => '',
                'Password' => '',
                'Mode' => '',
            ),
            'GOFIRST' => array(
                'DomainCode' => '',
                'AgentName' => '',
                'Password' => '',
                'Mode' => '',
            ),
            'GOFIRSTCOUPON' => array(
                'DomainCode' => '',
                'AgentName' => '',
                'Password' => '',
                'Mode' => '',
            ),
            'BITLA' => array(
                'ApiKey' => '',
                'Mode' => '',
            ),
            'TRAVELYAARI' => array(
                'ClientId' => '',
                'ClientSecret' => '',
                'Mode' => '',
            ),
            'ADIVAH' => array(
                'APIKey' => '',
                'PID' => '',
                'Mode' => '',
            ),
            'DOTW' => array(
                'UserName' => '',
                'Password' => '',
                'CompanyCode' => '',
                'Mode' => '',
            ),
            'TBOINT' => array(
                'UserName' => '',
                'Password' => '',
                'Mode' => '',
            ),
            'ETRAV' => array(
                'UserId' => '',
                'Password' => '',
                'CustomerMobile' => '',
                'Mode' => '',
            ),
            'PROVAB' => array(                  
                'UserName' => '',
                'Password' => '',
                'DomainKey' => '',
                'System' => '',
                'Mode' => '',
            ),
            'RATEHAWK' => array(
                'key' => '',
                'key_value' => '', 
                'Mode' => '',
            ),
        );

        $response = array();
        if ($supplier_name) {
            if (array_key_exists($supplier_name, $api_field)) {
                $response = $api_field[$supplier_name];
            }
        } else {
            $response = $api_field;
        }
        return $response;
    }
}

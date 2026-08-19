<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\VisaCouponModel;
use App\Modules\Coupon\Models\VisaCountryModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminVisaCoupon extends BaseController
{

    protected $title; 
    protected $web_partner_id;
    protected $web_partner_details;
    protected $user_id; 
    protected $admin_comapny_detail; 

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Webpartner";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        $this->web_partner_details = admin_cookie_data()['admin_user_details'];

        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];

        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

         if (permission_access_error("Coupon", "Coupon_Module")) {

        }
    }


    public function visa_coupon(): string
    {
        //if (permission_access_error("Coupon", "coupon_holiday_list")) {
        $VisaCouponModel = new VisaCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $VisaCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $VisaCouponModel->visa_coupon_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views/visaCoupon/Visa-coupon-list",
            'pager' => $VisaCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

    //   pr($data['list']); die;
        return view('template/sidebar-layout', $data);
       // }
    }




    public function add_visa_coupon_view()
    {
        if (permission_access_error("Coupon", "add_coupon_visa")) {
            $VisaCountryModel = new VisaCountryModel(); 

        $data = [
            'title' => $this->title,
            'country' => $VisaCountryModel->get_country_code($this->web_partner_id),
        ];
        $add_blog_view = view('Modules\Coupon\Views\visaCoupon\add-visa-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }

   
    


    public function add_coupon_visa()
    {
        if (permission_access("Coupon", "add_coupon_visa")) {
            $data = $this->request->getPost();
           
            $validate = new Validation();
            
            $rules = $this->validate($validate->visa_coupon_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if(isset($errors['visa_type_id.*']) && $errors['visa_type_id.*'] != ''){
                    $errors['visa_type_id[]'] = $errors['visa_type_id.*'];
                    unset($errors['visa_type_id.*']);
                }
               
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaCouponModel = new VisaCouponModel();
               
                $getVisaCouponExists = $VisaCouponModel->getCouponCode($data['code'],$this->web_partner_id);

                if(!empty($getVisaCouponExists)){
                    $errorMsg = array('code'=>'Coupon Code Already Exists');
                    $data_validation =  array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                    return $this->response->setJSON($data_validation);
                    die;
                }

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                if ($data['valid_from']) {
                    $data['valid_from'] = strtotime($data['valid_from']);
                }
                if ($data['valid_to']) {
                    $data['valid_to'] = strtotime($data['valid_to']);
                }


                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }
               
                $data['visa_type_id'] = ($data['visa_country_id'] == "ANY" ) ? "ANY" : implode(',', $data['visa_type_id']);
                // $data['visa_type_id'] = ($data['visa_country_id'] == "ANY") ? "ANY" : $data['visa_type_id'];
                $added_data = $VisaCouponModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Visa Coupon Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Visa Coupon not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        

    }


    public function visa_coupon_status_change()
    {
        if (permission_access_error("Coupon", "visa_coupon_status_change")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $VisaCouponModel = new VisaCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $VisaCouponModel->status_change($ids, $data,$this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Visa Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Visa status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function remove_visa_coupon()
    {
         if (permission_access_error("Coupon", "remove_visa_coupon")) {
        $VisaCouponModel = new VisaCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $VisaCouponModel->remove_coupon($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Visa Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Visa Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
       }
    }


    public function coupon_visa_details()
    { 
         if(permission_access("Coupon", "details_coupon_visa")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
        $VisaCouponModel = new VisaCouponModel();
        $visa_type_list = $VisaCouponModel->visa_type_list($this->web_partner_id);
        $visa_type_list = array_column($visa_type_list, 'visa_title', 'id');
        $details = $VisaCouponModel->visa_coupon_details_list($id,$this->web_partner_id);


        $data = [
            'title' => $this->title,
            'details' => $details,
            'visa_type_list'=>$visa_type_list,
           
        ];
        $blog_details = view('Modules\Coupon\Views\visaCoupon\coupon-visa-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
}
   

}

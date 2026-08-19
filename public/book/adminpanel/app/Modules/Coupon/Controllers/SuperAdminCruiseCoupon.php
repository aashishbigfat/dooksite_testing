<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\CruiseCouponModel;
use App\Modules\Coupon\Models\CruisePortModel;
use App\Modules\Coupon\Models\CruiseLineModel;


use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminCruiseCoupon extends BaseController
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


    public function cruise_coupon(): string
    {
        
        $CruiseCouponModel = new CruiseCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CruiseCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $CruiseCouponModel->cruise_coupon_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views\\cruiseCoupon\cruise-coupon-list",
            'pager' => $CruiseCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
           return view('template/sidebar-layout', $data);
        }
   

    public function cruise_coupon_view()
    {
        if (permission_access_error("Coupon", "add_coupon_cruise")) {
            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();

        $data = [
            'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
            'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Coupon\Views\cruiseCoupon\add-cruise-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
         }
    }

    public function add_coupon_cruise()
    {
        if (permission_access_error("Coupon", "add_coupon_cruise")) {
        $data = $this->request->getPost();
        $validate = new Validation();
       
        $rules = $this->validate($validate->cruise_coupon_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CruiseCouponModel = new CruiseCouponModel();
            
            $checkCruiseCouponExists = $CruiseCouponModel->getCouponCodeExists($data['code'],$this->web_partner_id);

            if(!empty($checkCruiseCouponExists)){
                $errorMsg = array('code' => 'Coupon Code Already Exists');
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                return $this->response->setJSON($data_validation);
                die;
            }

            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;
            if ($data['travel_from']) {
                $data['travel_from'] = strtotime($data['travel_from']);
            }
            if ($data['travel_date']) {
                $data['travel_date'] = strtotime($data['travel_date']);
            }

            if ($data['valid_from']) {
                $data['valid_from'] = strtotime($data['valid_from']);
            }
            if ($data['valid_to']) {
                $data['valid_to'] = strtotime($data['valid_to']);
            }
            $data['web_partner_id'] = $this->web_partner_id;

     
            $added_data = $CruiseCouponModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Cruise Coupon Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Cruise Coupon not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }



    public function cruise_coupon_status_change()
    {
        if (permission_access_error("Coupon", "cruise_coupon_status_change")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CruiseCouponModel = new CruiseCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $CruiseCouponModel->status_change($ids, $data,$this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Cruise Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Cruise Coupon status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function remove_cruise_coupon()
    {
        if (permission_access_error("Coupon", "remove_cruise_coupon")) {
        $CruiseCouponModel = new CruiseCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $CruiseCouponModel->remove_cruise($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Cruise Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Cruise Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }



    public function coupon_cruise_details()
    {
        if (permission_access("Coupon", "coupon_cruise_Details")) { 
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
        $CruiseCouponModel = new CruiseCouponModel();
        $details = $CruiseCouponModel->cruise_coupon_detail_list($id,$this->web_partner_id);
    

        

        $data = [
            'title' => $this->title,
            'details' => $details,
           
        ];
        $blog_details = view('Modules\Coupon\Views\cruiseCoupon\coupon-cruise-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
}


}

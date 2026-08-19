<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\BusCouponModel;



use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminBusCoupon extends BaseController
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


    public function bus_coupon(): string
    {
        
        $BusCouponModel = new BusCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $BusCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $BusCouponModel->discount_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views\\busCoupon\Bus-coupon-list",
            'pager' => $BusCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        }
   

    public function bus_coupon_view()
    {
        if (permission_access_error("Coupon", "coupon_bus_add")) {

        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Coupon\Views\busCoupon\add-bus-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
         }
    }

    public function add_coupon_bus()
    {
        if (permission_access_error("Coupon", "coupon_bus_add")) {
        $data = $this->request->getPost();
        $validate = new Validation();
       
        $rules = $this->validate($validate->bus_coupon_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $BusCouponModel = new BusCouponModel();
            
            $checkBusCouponCodeExists = $BusCouponModel->getCouponCode($data['code'],$this->web_partner_id);

            if(!empty($checkBusCouponCodeExists)){
                $errorMsg = array('code' => 'Coupon Code Already Exists');
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                return $this->response->setJSON($data_validation);
                die;
            }

            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }

            if ($data['valid_from']) {
                $data['valid_from'] = strtotime($data['valid_from']);
            }
            if ($data['valid_to']) {
                $data['valid_to'] = strtotime($data['valid_to']);
            }
            $data['web_partner_id'] = $this->web_partner_id;
            $added_data = $BusCouponModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Bus Coupon Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Bus Coupon not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function bus_coupon_status_change()
    {
        if (permission_access_error("Coupon", "coupon_bus_change_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $BusCouponModel = new BusCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $BusCouponModel->status_change($ids, $data,$this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Bus Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Bus Coupon status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function remove_bus_coupon()
    {
        if (permission_access_error("Coupon", "coupon_bus_delete")) {
        $BusCouponModel = new BusCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $BusCouponModel->remove_discount($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Bus Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Bus Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }


    public function coupon_bus_details()
    {
        if (permission_access("Coupon", "coupon_bus_Details")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
        $BusCouponModel = new BusCouponModel();
        $details = $BusCouponModel->bus_coupon_detail_list($id,$this->web_partner_id);
    
        $data = [
            'title' => $this->title,
            'details' => $details,
            
           
        ];
        $blog_details = view('Modules\Coupon\Views\busCoupon\bus-coupon-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

}
   


}

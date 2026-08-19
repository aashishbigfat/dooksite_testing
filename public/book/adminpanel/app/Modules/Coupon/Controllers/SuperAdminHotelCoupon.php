<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\HotelCouponModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminHotelCoupon extends BaseController
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


    public function hotel_coupon(): string
    {
        
        $HotelCouponModel = new HotelCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HotelCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $HotelCouponModel->discount_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views\hotelCoupon\Hotel-coupon-list",
            'pager' => $HotelCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        
        return view('template/sidebar-layout', $data);
        
    }

    public function hotel_coupon_view()
    {
        if (permission_access_error("Coupon", "coupon_hotel_add")) {

        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Coupon\Views\hotelCoupon\add-hotel-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
         }
    }

    public function add_coupon_hotel()
    {
        if (permission_access_error("Coupon", "coupon_hotel_add")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        $rules = $this->validate($validate->hotel_coupon_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            if(isset($errors['region_type.*'])){
                $errors['region_type[]'] = $errors['region_type.*'];
                unset($errors['region_type.*']);
            }
            if(isset($errors['star_rating.*'])){
                $errors['star_rating[]'] = $errors['star_rating.*'];
                unset($errors['star_rating.*']);
            }
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HotelCouponModel = new HotelCouponModel();
           
           
            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;

            $checkHotelCouponCode = $HotelCouponModel->getCouponCode($data['code'],$this->web_partner_id);

            if(!empty($checkHotelCouponCode)){
                $errorMsg = array('code' => 'Coupon Code Already Exists');
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                return $this->response->setJSON($data_validation);
                die;
            }

            if ($data['check_in_date_from']) {
                $data['check_in_date_from'] = strtotime($data['check_in_date_from']);
            }
            if ($data['check_out_date_to']) {
                $data['check_out_date_to'] = strtotime($data['check_out_date_to']);
            }

            if ($data['valid_from']) {
                $data['valid_from'] = strtotime($data['valid_from']);
            }
            if ($data['valid_to']) {
                $data['valid_to'] = strtotime($data['valid_to']);
            }
            $data['web_partner_id'] = $this->web_partner_id;
            $data['region_type'] = implode(',', $data['region_type']);
            $data['star_rating'] = implode(',', $data['star_rating']);
            $added_data = $HotelCouponModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Hotel Coupon Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Hotel Coupon not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function hotel_coupon_status_change()
    {
        if (permission_access_error("Coupon", "coupon_hotel_change_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HotelCouponModel = new HotelCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $HotelCouponModel->status_change($ids, $data,$this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Hotel Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Hotel Coupon status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function remove_hotel_coupon()
    {
        if (permission_access_error("Coupon", "coupon_hotel_delete")) {
        $HotelCouponModel = new HotelCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $HotelCouponModel->remove_discount($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Hotel Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Hotel Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }



    public function coupon_hotel_details()
    {
      if (permission_access("Coupon", "coupon_hotel_Details")) { 
        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));
        $HotelCouponModel = new HotelCouponModel();
        $details = $HotelCouponModel->hotel_coupon_detail_list($id,$this->web_partner_id);
       
    
        $data = [
            'title' => $this->title,
            'details' => $details,
            
           
        ];
        $blog_details = view('Modules\Coupon\Views\hotelCoupon\hotel-coupon-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
}

}

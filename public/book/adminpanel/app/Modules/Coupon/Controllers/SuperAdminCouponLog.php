<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\CouponLogModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminCouponLog extends BaseController
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
        
        /*if (permission_access_error("Flight", "Flight_Module")) {

        }*/
    }


    public function coupon_log(): string
    {  
        //if (permission_access_error("Flight", "flight_discount_list")) {
        $CouponLogModel = new CouponLogModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CouponLogModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $CouponLogModel->couponlog_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views\CouponLog\coupon-log-list",
            'pager' => $CouponLogModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}
    }

    public function remove_hotel_coupon()
    {
        // if (permission_access_error("Hotel", "flight_discount_status")) {
        $HotelCouponModel = new HotelCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $HotelCouponModel->remove_discount($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Hotel Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Hotel Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        // }
    }

}

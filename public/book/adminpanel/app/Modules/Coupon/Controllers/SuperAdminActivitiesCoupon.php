<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\ActivitiesCouponModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminActivitiesCoupon extends BaseController
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


    public function tour_activities_coupon(): string
    {
       
        $ActivitiesCouponModel = new ActivitiesCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $ActivitiesCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $ActivitiesCouponModel->activities_coupon_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views/activitiesCoupon/activities-coupon-list",
            'pager' => $ActivitiesCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

    //  pr($data['list']); die;
        return view('template/sidebar-layout', $data);
        
    }

    public function add_activities_coupon_view()
    {
        if (permission_access_error("Coupon", "add_coupon_activities")) {

        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Coupon\Views\activitiesCoupon\add-activities-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }



    public function add_coupon_activities()
    {
        if (permission_access_error("Coupon", "add_coupon_activities")) {
            $data = $this->request->getPost();
            $validate = new Validation();

            // Remove agent_class validation if markup_for is B2C
            

            $rules = $this->validate($validate->activities_coupon_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                return $this->response->setJSON($data_validation);
            }else{
                // Handle "ANY" cases
                $data['activities_id'] = ($data['activities_name'] == "ANY") ? "ANY" : $data['activities_id'];
                $data['categories_id'] = ($data['categories_name'] == "ANY") ? "ANY" : $data['categories_id'];
                $data['destination_id'] = ($data['destination_name'] == "ANY") ? "ANY" : $data['destination_id'];
    
                // Set additional data before insertion
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
              
                if ($data['valid_from']) {
                    $data['valid_from'] = strtotime($data['valid_from']);
                }
                if ($data['valid_to']) {
                    $data['valid_to'] = strtotime($data['valid_to']);
                }
    
    
                if ($data['activity_date_from']) {
                    $data['activity_date_from'] = strtotime($data['activity_date_from']);
                }
                if ($data['activity_date_to']) {
                    $data['activity_date_to'] = strtotime($data['activity_date_to']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
     
                $ActivitiesCouponModel = new ActivitiesCouponModel();
                $added_data = $ActivitiesCouponModel->insert($data);
    
                if ($added_data) {
                    $message = ["StatusCode" => 0, "Message" => "Successful Addition of Coupon for Activities", "Class" => "success_popup"];
                } else {
                    $message = ["StatusCode" => 2, "Message" => "Incomplete Addition of Activities Coupon. Kindly make another attempt.", "Class" => "error_popup"];
                }
    
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);

            }

        }
    }




    public function activities_coupon_status_change()
    {
        if (permission_access_error("Coupon", "activities_coupon_status_change")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $ActivitiesCouponModel = new ActivitiesCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $ActivitiesCouponModel->status_change($ids, $data,$this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Tour Guide Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Tour Guide status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function remove_activities_coupon()
    {
         if (permission_access_error("Coupon", "remove_activities_coupon")) {
        $ActivitiesCouponModel = new ActivitiesCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $ActivitiesCouponModel->remove_coupon($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Tour Guide Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Tour Guide Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
       }
    }


    public function coupon_activities_details()
    {

        if(permission_access("Coupon", "activities_coupon_details_list")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
        $ActivitiesCouponModel = new ActivitiesCouponModel();
       
        $details = $ActivitiesCouponModel->activities_coupon_detail_list($id,$this->web_partner_id);

        // pr($details); die;
    
    

        

        $data = [
            'title' => $this->title,
            'details' => $details,
           
           
        ];
        $blog_details = view('Modules\Coupon\Views\activitiesCoupon\coupon-activities-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

}
   


 

}

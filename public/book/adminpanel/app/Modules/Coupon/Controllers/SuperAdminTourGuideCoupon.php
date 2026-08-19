<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\TourguideCouponModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminTourGuideCoupon extends BaseController
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


    public function tour_guide_coupon(): string
    {
        
        $TourguideCouponModel = new TourguideCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $TourguideCouponModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $TourguideCouponModel->tourguide_coupon_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views/tourGouideCoupon/tourguide-coupon-list",
            'pager' => $TourguideCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

    //   pr($data['list']); die;
        return view('template/sidebar-layout', $data);
       
    }




    public function add_tour_guide_coupon_view()
    {
        if (permission_access_error("Coupon", "add_coupon_tour_guide")) {

        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Coupon\Views\tourGouideCoupon\add-tour-guide-coupon', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }

   
    public function add_coupon_tour_guide()
    {
        if (permission_access_error("Coupon", "add_coupon_tour_guide")) {
            $data = $this->request->getPost();
            $validate = new Validation();

         

            $rules = $this->validate($validate->tourguide_coupon_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['city_id.*'])) {
                    $errors['city_id[]'] = $errors['city_id.*'];
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $TourguideCouponModel = new TourguideCouponModel();

                $data = $this->request->getPost();

                $city_id = [];
                $city_name = [];
                foreach ($data['city_id'] as $key => $location) {
                    $locationArray = explode('?', $location);
                    $city_id[$key] = $locationArray[0];
                    $city[$key] = $locationArray[1];
                }

                $city_id = implode(',', $city_id);
                $location = implode(',', $city);
                $data['city_id'] =  $city_id;

                $data['city_name'] = $location;
                unset($data['location']);

                $data['created'] = create_date();
                if ($data['valid_from']) {
                    $data['valid_from'] = strtotime($data['valid_from']);
                }
                if ($data['valid_to']) {
                    $data['valid_to'] = strtotime($data['valid_to']);
                }


                if ($data['tour_date_from']) {
                    $data['tour_date_from'] = strtotime($data['tour_date_from']);
                }
                if ($data['tour_date_to']) {
                    $data['tour_date_to'] = strtotime($data['tour_date_to']);
                }
                $data['web_partner_id'] = $this->web_partner_id;



                $added_data = $TourguideCouponModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "TourGuide Coupon Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "TourGuide Coupon not Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }




    public function tour_guide_coupon_status_change()
    {
        if (permission_access_error("Coupon", "tour_guide_coupon_status_change")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $TourguideCouponModel = new TourguideCouponModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $TourguideCouponModel->status_change($ids, $data,$this->web_partner_id);

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

    public function remove_tour_guide_coupon()
    {
         if (permission_access_error("Coupon", "remove_tour_guide_coupon")) {
        $TourguideCouponModel = new TourguideCouponModel();
        $ids = $this->request->getPost('checklist');
        $delete = $TourguideCouponModel->remove_coupon($ids,$this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Tour Guide Coupon Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Tour Guide Coupon  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }



    public function coupon_tourguide_details()
    {
        if (permission_access("Coupon", "coupon_tour_guide_Details")) { 
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
        $TourguideCouponModel = new TourguideCouponModel();
        $details = $TourguideCouponModel->tourguide_coupon_details($id,$this->web_partner_id);
    
    

        

        $data = [
            'title' => $this->title,
            'details' => $details,
            
           
        ];
        $blog_details = view('Modules\Coupon\Views\tourGouideCoupon\coupon-tour-guide-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

}
   


 

}

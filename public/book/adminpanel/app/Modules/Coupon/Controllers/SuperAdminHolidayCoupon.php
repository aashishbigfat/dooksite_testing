<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\HolidayCouponModel;
use App\Modules\Holiday\Models\HolidayDestinationModel;
use App\Modules\Holiday\Models\HolidayThemesModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminHolidayCoupon extends BaseController
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


    public function holiday_coupon(): string
    {
        if (permission_access_error("Coupon", "coupon_holiday_list")) {
            $HolidayCouponModel = new HolidayCouponModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayCouponModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayCouponModel->holidaycoupon_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Coupon\Views\holidayCoupon\Holiday-coupon-list",
                'pager' => $HolidayCouponModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            //  pr($data['list']); die;
            return view('template/sidebar-layout', $data);
        }
    }




    public function add_holiday_coupon_view()
    {
        if (permission_access_error("Coupon", "coupon_holiday_add")) {
            $HolidayCouponModel = new HolidayCouponModel();
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $theme = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $package_list = $HolidayCouponModel->getDataArray('holiday_list',['web_partner_id'=>$this->web_partner_id],0,1,'id,holiday_name');
            $data = [
                'title' => $this->title,
                'holiday_theme'=> $theme,
                'holiday_destination'=> $destination,
                'holiday_package'=> $package_list,
            ];

           
            $add_blog_view = view('Modules\Coupon\Views\holidayCoupon\add-holiday-coupon', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_coupon_holiday()
    {
        if (permission_access_error("Coupon", "coupon_holiday_add")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if(isset($data['destination_select']) && $data['destination_select'] == 'ANY'){
                unset($validate->holiday_coupon_validation['destination_name.*']);
            }
            if(isset($data['theme_select']) && $data['theme_select'] == 'ANY'){
                unset($validate->holiday_coupon_validation['theme_name.*']);
            }
            if(isset($data['package_select']) && $data['package_select'] == 'ANY'){
                unset($validate->holiday_coupon_validation['holiday_package.*']);
            }
            $rules = $this->validate($validate->holiday_coupon_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['theme_name.*'])) {
                    $errors['theme_name[]'] = $errors['theme_name.*'];
                    unset($errors['theme_name.*']);
                }
                if (isset($errors['destination_name.*'])) {
                    $errors['destination_name[]'] = $errors['destination_name.*'];
                    unset($errors['destination_name.*']);
                }
                if (isset($errors['holiday_package.*'])) {
                    $errors['holiday_package[]'] = $errors['holiday_package.*'];
                    unset($errors['holiday_package.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayCouponModel = new HolidayCouponModel();


               
                $checkHolidayCouponCodeExists = $HolidayCouponModel->getDataArray("coupon_holiday",['code'=>$data['code'],'web_partner_id'=>$this->web_partner_id],0,1,'id');

                if(!empty($checkHolidayCouponCodeExists)){
                    $errorMsg = array('code' => 'Coupon Code Already Exists');
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                    return $this->response->setJSON($data_validation);
                    die;
                }

                $data['created'] = create_date();
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
                $data['web_partner_id'] = $this->web_partner_id;

                if(isset($data['destination_select']) && $data['destination_select'] == 'ANY'){
                    $data['destination_name'] = 'ANY';
                    $data['destination_id'] = 'ANY';
                    unset($data['destination_select']);
                }else{
                    $destination_id = array();
                    $destination_name = array();
                    foreach($data['destination_name'] as $destination){
                        $temp_des = explode('_',$destination);
                        array_push($destination_id,$temp_des[0]);
                        array_push($destination_name,$temp_des[1]);
                    }
                    $data['destination_id'] = implode(',',$destination_id);
                    $data['destination_name'] = implode(',',$destination_name);
                }
                if(isset($data['theme_select']) && $data['theme_select'] == 'ANY'){
                    $data['theme_name'] = 'ANY';
                    $data['theme_id'] = 'ANY';
                    unset($data['theme_select']);
                }else{
                    $theme_id = array();
                    $theme_name = array();
                    foreach($data['theme_name'] as $theme){
                        $temp_theme = explode('_',$theme);
                        array_push($theme_id,$temp_theme[0]);
                        array_push($theme_name,$temp_theme[1]);
                    }
                    $data['theme_id'] = implode(',',$theme_id);
                    $data['theme_name'] = implode(',',$theme_name);
                }
                if(isset($data['package_select']) && $data['package_select'] == 'ANY'){
                    $data['holiday_package'] = 'ANY';
                    $data['holiday_package_id'] = 'ANY';
                    unset($data['package_select']);
                }else{
                    $holiday_package_id = array();
                    $holiday_package = array();
                    foreach($data['holiday_package'] as $package){
                        $temp_package = explode('_',$package);
                        array_push($holiday_package_id,$temp_package[0]);
                        array_push($holiday_package,$temp_package[1]);
                    }
                    $data['holiday_package_id'] = implode(',',$holiday_package_id);
                    $data['holiday_package'] = implode(',',$holiday_package);
                }

             
                $added_data = $HolidayCouponModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function holiday_coupon_status_change()
    {
        if (permission_access_error("Coupon", "coupon_holiday_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayCouponModel = new HolidayCouponModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayCouponModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Coupon status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_holiday_coupon()
    {
        if (permission_access_error("Coupon", "coupon_holiday_delete")) {
            $HolidayCouponModel = new HolidayCouponModel();
            $ids = $this->request->getPost('checklist');
            $delete = $HolidayCouponModel->remove_coupon($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Holiday Coupon Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Holiday Coupon  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function coupon_holiday_details()
    {

        if (permission_access("Coupon", "coupon_holiday_detail_list")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
            $HolidayCouponModel = new HolidayCouponModel();
            $details = $HolidayCouponModel->holiday_coupon_details($id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'details' => $details,


            ];
            $blog_details = view('Modules\Coupon\Views\holidayCoupon\coupon-holiday-detail', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }
}

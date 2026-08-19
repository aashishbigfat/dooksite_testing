<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\HajjCouponModel;
use App\Modules\Hajj\Models\HajjDestinationModel;
use App\Modules\Hajj\Models\HajjThemesModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;
use Throwable;

class SuperAdminHajjCoupon extends BaseController
{

    protected $title;
    protected $web_partner_id;
    protected $web_partner_details;
    protected $user_id;
    protected $admin_comapny_detail;




    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Coupon";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        $this->web_partner_details = admin_cookie_data()['admin_user_details'];

        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];

        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        if (permission_access_error("Coupon", "Coupon_Module")) {
        }
    }



    public function hajj_coupon(): string
    {
        try {
            $HajjCouponModel = new HajjCouponModel();
            $search_data = $this->request->getGet();
            $lists = $HajjCouponModel->hajj_coupon_list($this->web_partner_id, $search_data);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Coupon\Views\HajjCoupon\hajj-coupon-list",
                'pager' => $HajjCouponModel->pager,
                'search_bar_data' => $search_data,
            ];
            return view('template/sidebar-layout', $data);
        } catch (Throwable $e) {
            log_error_details($e);

            $data = [
                'view' => "Hajj\Views/exception_view",
                'error_message' => 'An error occurred. Please try again later.'
            ];
            return view('template/sidebar-layout', $data);
        }
    }





    public function add_hajj_coupon_view()
    {
        $StatusCode = 2;
        $Message = "Unexpected Error.";
        $Class = "error_popup";
        $doReload = "true";
        $Redirect_Url = site_url();
        try {
            if (permission_access_error("Coupon", "coupon_hajj_add")) {
                // initialize_error_handler();
                $HajjCouponModel = new HajjCouponModel();
                $HajjThemesModel = new HajjThemesModel();
                $HajjDestinationModel = new HajjDestinationModel();

                $theme = $HajjThemesModel->hajj_themes_list_select($this->web_partner_id);
                $destination = $HajjDestinationModel->hajj_destination_list_select($this->web_partner_id);
                $package_list = $HajjCouponModel->getDataArray('hajj_package_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id, package_name');

                $data = [
                    'title' => $this->title,
                    'themes' => $theme,
                    'destinations' => $destination,
                    'package_list' => $package_list,
                ];

                $add_coupon_view = view('Modules\Coupon\Views\HajjCoupon\add-hajj-coupon', $data);
                $data = ["StatusCode" => 0, "Message" => $add_coupon_view, 'class' => 'success_popup', "Reload" => "false"];
                return $this->response->setJSON($data);
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
            $response = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload, 'Redirect_Url' => $Redirect_Url];
            $this->session->setFlashdata('Message', $response);
            return $this->response->setJSON($response);
        }
    }





    public function add_coupon_hajj()
    {


        $StatusCode = 2;
        $Message = "An error occurred while adding coupon.";
        $Class = "error_popup";
        $doReload = "true";
        try {
            if (permission_access_error("Coupon", "coupon_hajj_add")) {
                $data = $this->request->getPost();
                $validate = new Validation();

                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    unset($validate->hajj_coupon_validation['package_list.*']);
                }


                $rules = $this->validate($validate->hajj_coupon_validation);
                if (!$rules) {
                    $errors = $this->validator->getErrors();

                    if (isset($errors['package_list.*'])) {
                        $errors['package_list[]'] = $errors['package_list.*'];
                        unset($errors['package_list.*']);
                    }
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                    return $this->response->setJSON($data_validation);
                }

                $HajjCouponModel = new HajjCouponModel();
                $checkCouponCodeExists = $HajjCouponModel->getDataArray("coupon_hajj_package", ['code' => $data['code'], 'web_partner_id' => $this->web_partner_id], 0, 1, 'id');


                if (!empty($checkCouponCodeExists)) {
                    $errorMsg = ['code' => 'Coupon Code Already Exists'];
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg)];
                    return $this->response->setJSON($data_validation);
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

                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['package_name'] = 'ANY';
                    $data['package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $package_id = [];
                    $package_name = [];

                    foreach ($data['package_list'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($package_id, $temp_package[0]);
                        array_push($package_name, $temp_package[1]);
                    }
                    $data['package_id'] = implode(',', $package_id);
                    $data['package_name'] = implode(',', $package_name);
                }
                $data['destination_name'] = 'ANY';
                $data['destination_id'] = 'ANY';
                $data['theme_name'] = 'ANY';
                $data['theme_id'] = 'ANY';


                $added_data = $HajjCouponModel->insert($data);
                if ($added_data) {
                    $StatusCode = 0;
                    $Message = "Coupon added successfully";
                    $Class = "success_popup";
                    $doReload = "true";
                } else {
                    $Message = "Unable to add Coupan";
                }
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $response = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $response);
        return $this->response->setJSON($response);
    }



    public function coupon_hajj_details()
    {
        try {

            if (permission_access("Coupon", "coupon_hajj_detail_list")) {

                $uri = $this->request->getUri();
                $id = dev_decode($uri->getSegment(3));
                $HajjCouponModel = new HajjCouponModel();
                $details = $HajjCouponModel->hajj_coupon_details($id, $this->web_partner_id);

                $data = [
                    'title' => $this->title,
                    'details' => $details,
                ];

                $coupon_details = view('Modules\Coupon\Views\HajjCoupon\coupon-hajj-detail', $data);

                $data = ["StatusCode" => 0, "Message" => $coupon_details, 'class' => 'success_popup'];
                return $this->response->setJSON($data);
            } else {
                $response = ["StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true"];
                $this->session->setFlashdata('Message', $response);
                return $this->response->setJSON($response);
            }
        } catch (Throwable $e) {
            log_error_details($e);
            $response = ["StatusCode" => 2, "Message" => "Unexpected error", "Class" => "error_popup", "Reload" => "true"];
            $this->session->setFlashdata('Message', $response);
            return $this->response->setJSON($response);
        }
    }



    public function hajj_coupon_status_change()
    {


        $StatusCode = 2;
        $Message = "An error occurred while changing status.";
        $Class = "error_popup";
        $doReload = "true";

        try {
            if (permission_access_error("Coupon", "coupon_hajj_change_status")) {
                initialize_error_handler();
                $validate = new Validation();
                $rules = $this->validate($validate->status);
                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                    return $this->response->setJSON($data_validation);
                }
                $HajjCouponModel = new HajjCouponModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HajjCouponModel->status_change($ids, $data, $this->web_partner_id);

                $StatusCode = 0;
                $Message = "Hajj Coupon status changed successfully.";
                $Class = "success_popup";
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $message = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }


    public function remove_hajj_coupon()
    {
        $StatusCode = 2;
        $Message = "Coupon could not be deleted.";
        $Class = "error_popup";
        $doReload = "true";

        try {
            initialize_error_handler();
            $HajjCouponModel = new HajjCouponModel();
            $ids = $this->request->getPost('checklist');
            $delete = $HajjCouponModel->remove_coupon($ids, $this->web_partner_id);

            if ($delete) {
                $StatusCode = 0;
                $Message = "Coupon deleted successfully.";
                $Class = "success_popup";
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $message = [
            "StatusCode" => $StatusCode,
            "Message" => $Message,
            "Class" => $Class,
            "Reload" => $doReload
        ];

        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        // }
    }
}

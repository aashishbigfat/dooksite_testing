<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\CarCouponModel;

use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;
use Throwable;


class SuperAdminCarCoupon extends BaseController
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



    public function car_coupon()
    {
        try {
            if (permission_access_error("Coupon", "car_coupon_list")) {
                $CarCouponModel = new CarCouponModel();

                $search_data = $this->request->getGet();
                $lists = $CarCouponModel->couponList($this->web_partner_id, $search_data);

                $data = [
                    'title' => $this->title,
                    'list' => $lists,
                    'view' => "Coupon\Views\carCoupon\car-coupon-list",
                    'pager' => $CarCouponModel->pager,
                    'search_bar_data' => $search_data,
                ];

                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);

            $data = [
                'view' => "CarExtranet\Views/exception_view",
                'error_message' => 'An error occurred. Please try again later.'
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function car_coupon_view()
    {
        try {
            if (permission_access_error("Coupon", "add_coupon_car")) {

                $data = [
                    'title' => $this->title,
                ];

                $add_coupon_view = view('Modules\Coupon\Views\carCoupon\add-car-coupon', $data);
                $data = ["StatusCode" => 0, "Message" => $add_coupon_view, 'class' => 'success_popup', "Reload" => "false"];
                return $this->response->setJSON($data);
            }
        } catch (Throwable $e) {
            log_error_details($e);

            $data = ["StatusCode" => 1, "Message" => "Unexpected error.", 'class' => 'error_popup', "Reload" => "true"];
            return $this->response->setJSON($data);
        }
    }



    public function add_coupon_car()
    {
        try {
            if (permission_access_error("Coupon", "add_coupon_car")) {
                $StatusCode = 2;
                $Message = "Unexpected error occurred.";
                $Class = "error_popup";
                $doReload = "true";


                $data = $this->request->getPost();
                $validate = new Validation();

                $rules = $this->validate($validate->car_coupon_validation);
                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                    return $this->response->setJSON($data_validation);
                }

                $CarCouponModel = new CarCouponModel();

                $CheckCarCouponExists = $CarCouponModel->getCouponCode($data['code'], $this->web_partner_id);

                if (!empty($CheckCarCouponExists)) {
                    $errorMsg = ['code' => 'Coupon Code Already Exists'];
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg)];
                    return $this->response->setJSON($data_validation);
                }

                $data['created'] = create_date();
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

                $added_data = $CarCouponModel->insertData($data);

                if ($added_data) {
                    $StatusCode = 0;
                    $Message = "Coupon added successfully";
                    $Class = "success_popup";
                    $doReload = "true";
                } else {
                    $Message = "Coupon could not be added";
                }
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }

        $response = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $response);
        return $this->response->setJSON($response);
    }



    public function coupon_car_details()
    {
        try {
            if (permission_access("Coupon", "car_coupon_details_list")) {

                $uri = $this->request->getUri();
                $id = dev_decode($uri->getSegment(3));
                $CarCouponModel = new CarCouponModel();
                $details = $CarCouponModel->car_coupon_detail_list($id, $this->web_partner_id);


                $data = [
                    'title' => $this->title,
                    'details' => $details,

                ];
                $blog_details = view('Modules\Coupon\Views\carCoupon\coupon-car-details', $data);
                $data = ["StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup'];
                return $this->response->setJSON($data);
            }
        } catch (Throwable $e) {
            log_error_details($e);

            $data = ["StatusCode" => 1, "Message" => "Unexpected error.", 'class' => 'error_popup', "Reload" => "true"];
            return $this->response->setJSON($data);
        }
    }


    public function car_coupon_status_change()
    {
        try {
            if (permission_access_error("Coupon", "car_coupon_status_change")) {
                $StatusCode = 2;
                $Message = "Unexpected error occurred.";
                $Class = "error_popup";
                $doReload = "true";


                $validate = new Validation();
                $rules = $this->validate($validate->status);
                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                    return $this->response->setJSON($data_validation);
                }

                $CarCouponModel = new CarCouponModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $ids = explode(",", $ids);
                $updated = $CarCouponModel->status_change($ids, $data, $this->web_partner_id);

                if ($updated) {
                    $StatusCode = 0;
                    $Message = "Status Changed Succesfully";
                    $Class = "success_popup";
                } else {
                    $Message = "Status Couldn't be Changed";
                }
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $message = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }



    public function remove_car_coupon()
    {
        try {
            if (permission_access_error("Coupon", "remove_car_coupon")) {
                $StatusCode = 2;
                $Message = "Unexpected error occurred.";
                $Class = "error_popup";
                $doReload = "true";


                $CarCouponModel = new CarCouponModel();
                $ids = $this->request->getPost('checklist');
                $deleted = $CarCouponModel->remove_coupon($ids, $this->web_partner_id);

                if ($deleted) {
                    $StatusCode = 0;
                    $Message = "Coupon deleted successfully";
                    $Class = "success_popup";
                } else {
                    $Message = "Coupon could not be deleted!";
                }
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $message = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }
}

<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\FlightCouponModel;
use App\Modules\Coupon\Models\FlightAirportModel;
use App\Modules\Coupon\Models\FlightAirlineModel;


use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;

class SuperAdminFlightCoupon extends BaseController
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


    public function flight_coupon(): string
    {

        $FlightCouponModel = new FlightCouponModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightCouponModel->search_data($this->request->getGet(), $this->web_partner_id);
        } else {
            $lists = $FlightCouponModel->discount_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Coupon\Views\\flightCoupon\Flight-coupon-list",
            'pager' => $FlightCouponModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }

    public function flight_coupon_view()
    {
        if (permission_access_error("Coupon", "coupon_flight_add")) {

            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Coupon\Views\flightCoupon\add-flight-coupon', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_coupon()
    {
        if (permission_access_error("Coupon", "coupon_flight_add")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if ($data['max_limit']) {
                $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
                $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            $rules = $this->validate($validate->flight_discount_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['journey_type.*'])) {
                    $errors['journey_type[]'] = $errors['journey_type.*'];
                    unset($errors['journey_type.*']);
                }
                if (isset($errors['is_domestic.*'])) {
                    $errors['is_domestic[]'] = $errors['is_domestic.*'];
                    unset($errors['is_domestic.*']);
                }
                if (isset($errors['cabin_class.*'])) {
                    $errors['cabin_class[]'] = $errors['cabin_class.*'];
                    unset($errors['cabin_class.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCouponModel = new FlightCouponModel();


                $checkCouponCodeExist = $FlightCouponModel->getCouponCode($data['code'], $this->web_partner_id);

                if (!empty($checkCouponCodeExist)) {
                    $errorMsg = array('code' => 'Coupon Code Already Exists');
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorMsg));
                    return $this->response->setJSON($data_validation);
                    die;
                }

                $temp_airline = $data['airline_code'];
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[0])) {
                    $data['airline_name'] = $temp_airline[1];
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
                $data['is_domestic'] = implode(',', $data['is_domestic']);
                $data['journey_type'] = implode(',', $data['journey_type']);
                $data['cabin_class'] = implode(',', $data['cabin_class']);
                $added_data = $FlightCouponModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Coupon Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Coupon not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function coupon_status_change()
    {
        if (permission_access_error("Coupon", "coupon_flight_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCouponModel = new FlightCouponModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $FlightCouponModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Coupon status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Coupon status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_coupon()
    {
        if (permission_access_error("Coupon", "coupon_flight_delete")) {
            $FlightCouponModel = new FlightCouponModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightCouponModel->remove_discount($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Flight Coupon Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Coupon  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function get_airports()
    {

        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);

        $FlightAirportModel = new FlightAirportModel();

        $get_airport = $FlightAirportModel->get_airport_autosuggestion($terms);
        $availableAirport = [];
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirport[] = ['city' => $data['city_name'], 'airport_code' => $data['code'], 'label' => $data['city_name'] . ' (' . $data['code'] . '), ' . ucfirst(strtolower($data['country_name'])) . '', 'airport_name' => $data['name'], 'country_code' => $data['country_code'], 'country_name' => ucfirst(strtolower($data['country_name']))];
            }
        }
        echo json_encode($availableAirport);
    }

    public function get_airline()
    {
        $terms = $this->request->getGet('term');
        $FlightAirlineModel = new FlightAirlineModel();

        $get_airport = $FlightAirlineModel->get_airline_autosuggestion($terms);
        $availableAirline = [];
        $availableAirline[] = 'ANY' . '-' . 'Any Airline';
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
            }
        }

        echo json_encode($availableAirline);
    }







    public function coupon_flight_details()
    {
        if (permission_access("Coupon", "coupon_flight_details_list")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
            $FlightCouponModel = new FlightCouponModel();
            $details = $FlightCouponModel->coupon_flight_detail($id, $this->web_partner_id);


            $data = [
                'title' => $this->title,
                'details' => $details,


            ];
            $blog_details = view('Modules\Coupon\Views\flightCoupon\flight-coupon-details', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }
}

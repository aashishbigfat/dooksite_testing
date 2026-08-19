<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

use Modules\Dashboard\Config\Validation;
use App\Modules\Dashboard\Models\DashboardModel;
use App\Modules\Dashboard\Models\CustomerModel;
use App\Modules\Dashboard\Models\Account\AccountLogeModel;
use App\Modules\Dashboard\Models\Bus\BusBookingModel;
use App\Modules\Dashboard\Models\Bus\BusModel;
use App\Modules\Bus\Models\BusAmendmentModel;
use App\Modules\Dashboard\Models\Flight\FlightBookingModel;
use App\Modules\Flight\Models\FlightAmendmentModel;
use App\Modules\Dashboard\Models\Hotel\HotelBookingModel;
use App\Modules\Dashboard\Models\Holiday\HolidayModel;

use App\Modules\Dashboard\Models\Visa\VisaModel;

use App\Modules\Dashboard\Models\Car\CarBookingModel;
use App\Modules\Hotel\Models\HotelAmendmentModel;
use App\Modules\Holiday\Models\HolidayAmendmentModel;

use App\Modules\Dashboard\Models\Cruise\CruiseBookingModel;
use App\Modules\Cruise\Models\AmendmentModel;

use App\Modules\Visa\Models\VisaAmendmentModel;
use App\Modules\CarExtranet\Models\CarAmendmentModel;
use App\Modules\Dashboard\Models\Activity\ActivityBookingModel;
use App\Modules\Activities\Models\ActivityAmendmentModel;

use App\Modules\Dashboard\Models\TourGuide\TourGouidBookingModel;
use App\Modules\Tourguide\Models\TourguideAmendmentModel;
use App\Modules\Dashboard\Models\BikeTour\BikeTourBookingModel;
use App\Modules\BikeTour\Models\BikeTourAmendmentModel;

class Dashboard extends BaseController
{

    public $title;
    public $folder_name;
    public $web_partner_details;
    public $web_partner_id;
    public $wl_customer_id;
    public $booking_source;
    public $wl_customer_info;
    public $Services;  

    public $validation;
    public $request;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Dashboard";
        $this->folder_name = "customer";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        if (isset(session()->get('wl_customer')['id'])) {
            $this->wl_customer_id = session()->get('wl_customer')['id'];
        } 
        $this->booking_source = "Wl_b2c";

    }

    public function index()
    {
        $DashboardModel = new DashboardModel(); 

        $customer = session()->get('wl_customer');
        if ($customer['dob']) {
            $customer['dob'] = timestamp_to_date($customer['dob']);
        }

        $country_list = $DashboardModel->country_list();

        $data = [
            "country_list" => $country_list,
            "customer" => $customer,
            'title' => $this->title,
            'view' => "Dashboard\Views\dashbaord"
        ];
        return view('template/default-layout', $data);
    }

    public function update_customer_profile()
    {

        

        $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));

        $field_name = 'profile_pic';

        $validate = new Validation();

        $post_images = $this->request->getFile($field_name);

        if ($post_images->getName() == '') {
            unset($validate->customer_validation[$field_name]);
        }

        $rules = $this->validate($validate->customer_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $DashboardModel = new DashboardModel();
            $CustomerModel = new CustomerModel();
            $data = $this->request->getPost();
            $customer = session()->get('wl_customer');
            if ($customer['dob']) {
                $customer['dob'] = timestamp_to_date($customer['dob']);
            }
            $previous_data = $customer;
            $file = $this->request->getFile($field_name);
            if ($file->getName() != '') {
                $resizeDim = array('width' => 150, 'height' => 150);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data[$field_name] = $image_upload['file_name'];
                    if ($previous_data[$field_name]) {
                        if (file_exists(FCPATH . "uploads/$this->folder_name/" . $previous_data[$field_name])) {
                            unlink(FCPATH . "uploads/$this->folder_name/" . $previous_data[$field_name]);
                            unlink(FCPATH . "/uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                        }
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Please upload valid image", "Class" => "error_popup", "Reload" => "true");
                    return $this->response->setJSON($message);
                }
            } else {
                $data[$field_name] = $previous_data[$field_name];
            }
            if ($data['dob']) {
                $data['dob'] = strtotime($data['dob']);
            }

            $data['modified'] = create_date();
            if ($data['city']) {
                $city_data = $DashboardModel->city_list_row($data['city']);
                $data['city'] = $city_data['name'];
            } else {
                unset($data['city']);
            }

            $country_data = $DashboardModel->country_list_row($data['country']);
            if ($country_data) {
                $data['country'] = $country_data['name'];
            }
            if ($data['state']) {
                $state_data = $DashboardModel->state_list_row($data['state']);
                $data['state'] = $state_data['name'];
            } else {
                unset($data['state']);
            }

            $added_data = $CustomerModel->where("web_partner_id", web_partner_details['id'])->where("id", $id)->set($data)->update();

            $user = $CustomerModel->customer_detail(web_partner_details['id'], $id);
            $this->session->set('wl_customer', $user);


            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "profile has been successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "profile has not been updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }

    }

    public function change_password()
    {
        $validate = new Validation();
        $id = dev_decode($this->request->uri->getSegment(3));
        $rules = $this->validate($validate->customer_password_change);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CustomerModel = new CustomerModel();

            $old_password = md5($this->request->getPost('old_password'));

            $check = $CustomerModel->customer_check_old_password(web_partner_details['id'], $id, $old_password);
            if ($check) {
                $data['password'] = md5($this->request->getPost('password'));

                $update = $CustomerModel->where("id", $id)->set($data)->update();

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "password has been successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "password has not been changed", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "old password does not matched !", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function bus_booking_list()
    {

        $BusBookingModel = new BusBookingModel();
        $BusAmendmentModel = new BusAmendmentModel();

        $all_lists = $BusBookingModel->bus_booking_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $upcomming_lists = $BusBookingModel->bus_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $BusBookingModel->bus_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $amendment_list = $BusAmendmentModel->bus_amendment_list($this->web_partner_id, $this->wl_customer_id);
        // pr($amendment_list);
        // die;
        $data = [
            'title' => $this->title,
            'all_list' => $all_lists,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'view' => "Dashboard\Views/Bus/bus-booking-list",
            'pager' => $BusBookingModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
    }



    function flight_booking_list()
    {
        $FlightBookingModel = new FlightBookingModel();
        $FlightAmendmentModel = new FlightAmendmentModel();
        $getData = $this->request->getGET();

        $all_list = $FlightBookingModel->flight_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcomming_lists = $FlightBookingModel->flight_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $FlightBookingModel->flight_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $amendment_list = $FlightAmendmentModel->flight_amendment_list($this->web_partner_id, $this->wl_customer_id);

        // pr($amendment_list);exit;

        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Flight/flight-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
            'amendment_pager' => $FlightAmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }


    function hotel_booking_list()
    {
        $HotelBookingModel = new HotelBookingModel();
        $HotelAmendmentModel = new HotelAmendmentModel();

        $all_list = $HotelBookingModel->hotel_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);


        $upcomming_lists = $HotelBookingModel->hotel_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);


        $cancelled_lists = $HotelBookingModel->hotel_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $amendment_list = $HotelAmendmentModel->hotel_amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Hotel/hotel-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'pager' => $HotelBookingModel->pager,
            'amendment_pager' => $HotelAmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }


    function holiday_booking_list()
    {
        $HolidayModel = new HolidayModel();

        $AmendmentModel = new HolidayAmendmentModel();
        $all_list = $HolidayModel->holiday_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcomming_lists = $HolidayModel->holiday_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $HolidayModel->holiday_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $amendment_list = $AmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Holiday/holiday-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'pager' => $HolidayModel->pager,
            'amendment_pager' => $AmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }
    function visa_booking_list()
    {
        $VisaModel = new VisaModel();


        $all_list = $VisaModel->visa_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $upcomming_lists = $VisaModel->visa_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $cancelled_lists = $VisaModel->visa_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $AmendmentModel = new VisaAmendmentModel();
        $amendment_list = $AmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Visa/visa-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'pager' => $VisaModel->pager,
            'amendment_pager' => $AmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }

    public function car_booking_list()
    {
        $CarBookingModel = new CarBookingModel();

        $all_lists = $CarBookingModel->car_booking_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $upcomming_lists = $CarBookingModel->car_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $cancelled_lists = $CarBookingModel->car_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);
        $AmendmentModel = new CarAmendmentModel();
        $amendment_list = $AmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,
            'all_list' => $all_lists,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'view' => "Dashboard\Views/Car/car-booking-list",
            'pager' => $CarBookingModel->pager,
            'amendment_pager' => $AmendmentModel->pager,
        ];

        return view('template/default-layout', $data);
    }

    function cruise_booking_list()
    {
        $CruiseBookingModel = new CruiseBookingModel();
        $getData = $this->request->getGET();

        $all_list = $CruiseBookingModel->cruise_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcomming_lists = $CruiseBookingModel->cruise_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $CruiseBookingModel->cruise_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $AmendmentModel = new AmendmentModel();

        $amendment_list = $AmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Cruise/cruise-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            "search_bar_data" => $getData,
            'pager' => $CruiseBookingModel->pager,
            'amendment_pager' => $CruiseBookingModel->pager,
        ];
        //pr($data);exit;
        return view('template/default-layout', $data);
    }


    public function state()
    {
        $DashboardModel = new DashboardModel();
        $data = $this->request->getPost();
        $id = trim($data['id']);
        $city_list = $DashboardModel->state_list($id);

        if ($city_list) {
            foreach ($city_list as $state) {
                echo "<option value=" . $state['id'] . ">" . $state['name'] . "</option>";
            }
        }
    }

    public function city()
    {
        $DashboardModel = new DashboardModel();
        $data = $this->request->getPost();
        $id = trim($data['id']);
        $city_list = $DashboardModel->city_list($id);

        if ($city_list) {
            foreach ($city_list as $city) {
                echo "<option value=" . $city['id'] . ">" . $city['name'] . "</option>";
            }
        }
    }







    function tour_guide_booking_list()
    {
        $TourGouidBookingModel = new TourGouidBookingModel();
        $all_list = $TourGouidBookingModel->tour_guide_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcoming_lists = $TourGouidBookingModel->tour_guide_booking_upcomming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $TourGouidBookingModel->tour_guide_booking_cancel_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);


        $TourguideAmendmentModel = new TourguideAmendmentModel();

        $amendment_list = $TourguideAmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);

        $data = [
            'title' => $this->title,

            "all_list" => $all_list,
            'upcoming_lists' => $upcoming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'view' => "Dashboard\Views/TourGuide/tour-guide-booking-list",
            'pager' => $TourGouidBookingModel->pager,
            'amendment_pager' => $TourguideAmendmentModel->pager,

        ];


        return view('template/default-layout', $data);
    }



    function activities_booking_list()
    {
        $ActivityBookingModel = new ActivityBookingModel();

        $all_list = $ActivityBookingModel->activities_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcoming_lists = $ActivityBookingModel->activities_booking_upcoming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $ActivityBookingModel->activities_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $ActivityAmendmentModel = new ActivityAmendmentModel();

        $amendment_list = $ActivityAmendmentModel->amendment_list($this->web_partner_id, $this->wl_customer_id);


        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Activity/activity-booking-list",
            "all_list" => $all_list,
            'upcoming_lists' => $upcoming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            'pager' => $ActivityBookingModel->pager,
            'amendment_pager' => $ActivityAmendmentModel->pager,
        ];
        //pr($data);exit;
        return view('template/default-layout', $data);
    }

    function account_logs_list()
    {
        $AccountLogeModel = new AccountLogeModel();
        $account_logs_all_list = $AccountLogeModel->agent_account_list_all($this->web_partner_id, $this->wl_customer_id);
        $data = [
            'title' => $this->title,
            "account_loge" => $account_logs_all_list,
            'pager' => $AccountLogeModel->pager,
            'view' => "Dashboard\Views/Account/account-logs-list",
        ];

        return view('template/default-layout', $data);
    }

    public function view_remark()
    {
        $uri = service('uri');
        $id = dev_decode($uri->getSegment(3));
        $AccountLogeModel = new AccountLogeModel();

        $data = [
            'title' => $this->title,
            'id' => $id,
            'data' => $AccountLogeModel->view_remark_detail($id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\Dashboard\Views\Account\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    function biketour_booking_list()
    {
        $BikeTourBookingModel = new BikeTourBookingModel();
        $BikeTourAmendmentModel = new BikeTourAmendmentModel();
        $getData = $this->request->getGET();

        $all_list = $BikeTourBookingModel->biketour_booking_list_all($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $upcomming_lists = $BikeTourBookingModel->biketour_booking_upcoming_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $cancelled_lists = $BikeTourBookingModel->biketour_booking_cancelled_list($this->web_partner_id, $this->wl_customer_id, $this->booking_source);

        $amendment_list = $BikeTourBookingModel->amendment_list($this->web_partner_id, $this->wl_customer_id);


        $data = [
            'title' => $this->title,
            'view' => "Dashboard\Views/Biketour/biketour-booking-list",
            "all_list" => $all_list,
            'upcomming_lists' => $upcomming_lists,
            'cancelled_lists' => $cancelled_lists,
            'amendment_list' => $amendment_list,
            "search_bar_data" => $getData,
            'pager' => $BikeTourBookingModel->pager,
            'amendment_pager' => $BikeTourBookingModel->pager,
        ];
        return view('template/default-layout', $data);
    }






}

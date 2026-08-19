<?php

namespace Modules\Cruise\Controllers;

use App\Modules\Cruise\Models\CruiseListModel;
use App\Modules\Cruise\Models\CruiseLineModel;
use App\Modules\Cruise\Models\CruiseShipModel;
use App\Modules\Cruise\Models\CruiseShipGalleryModel;
use App\Modules\Cruise\Models\CruiseCabinModel;
use App\Modules\Cruise\Models\CruisePriceModel;
use App\Modules\Cruise\Models\CruiseOceanModel;
use App\Modules\Cruise\Models\CruisePortModel;
use App\Modules\Cruise\Models\WebPartnerCruiseDiscountModel; 
use App\Modules\Cruise\Models\WebPartnerCruiseMarkupModel;
use App\Modules\Cruise\Models\CruiseBookingModel;
use App\Modules\Cruise\Models\CruiseModel;
use App\Modules\Visa\Models\AgentClassModel;
use App\Modules\Cruise\Models\AmendmentModel;
use App\Controllers\BaseController;
use Modules\Cruise\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cruise extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Cruise";

        $this->folder_name = 'cruise';

        $this->Services = API_REQUEST_URL . 'cruiseservice/rest/';
        if (permission_access_error("Cruise", "Cruise_Module")) {
        }
        helper('Modules\Cruise\Helpers\cruise');

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];

        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    }



    public function cruise_booking_list()
    {
        $CruiseBookingModel = new CruiseBookingModel();

        $bookingType = 'all';
        $source  = '';
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $lists = $CruiseBookingModel->search_data($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'],$bookingType,$source);
        } else { 
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            if (isset($_GET['bookingtype'])) {
                $bookingType = $this->request->getGET('bookingtype');
            }
            $lists = $CruiseBookingModel->cruise_booking_list($this->web_partner_id , $this->web_partner_details['id'], $this->web_partner_details['primary_user'],$bookingType,$source);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Cruise\Views\cruise-booking-list",
            'pager' => $CruiseBookingModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }

    function AssignUpdateCruiseTicket()
    {
        $bookingReferenceNumber = dev_decode($this->request->uri->getSegment(3));
        $CruiseModel = new CruiseModel();
        $BookingDetail = $CruiseModel->getBookingDetailData($bookingReferenceNumber, $this->web_partner_id);
        if ($BookingDetail) {
            $checkbookingflighttime = checkbookingflighttime($BookingDetail['created'], 'Cruise');
            if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->redirect($this->request->getUserAgent()->getReferrer());
            }
            $updateData['webpartner_assign_user'] = $this->user_id;
            $CruiseModel->updateUserData("cruise_booking_list", array("booking_ref_number" => $bookingReferenceNumber), $updateData);
            $message = array("StatusCode" => 0, "Message" => "Voucher assign successfully", "Class" => "success_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->redirect($this->request->getUserAgent()->getReferrer());
        } else {
            return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
        }
    }


    public function getUpdatecruiseVoucherInfo()
    {

        $uri = service('uri');
        $ref_id = $uri->getSegment(3);

        $CruiseBookingModel = new CruiseBookingModel();
        $bookingInfo = $CruiseBookingModel->getBookingDetailData($ref_id, $this->web_partner_id);
        $amendment_list = $CruiseBookingModel->amendment_list($bookingInfo['id'], $this->web_partner_id);
        // pr($bookingInfo);exit;
        $FareBreakUp  = array();
        $fareBreakupArray  =  json_decode($bookingInfo['web_partner_fare_break_up'], true);
        $markup  =  isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp']['Value'] : 0;
        $discount  =  isset($fareBreakupArray['WebpDiscount']) ? $fareBreakupArray['WebpDiscount']['Value'] : 0;

        $FareBreakUp =  array(
            "FareBreakup" => array(
                "BaseFare" =>  array("Value" => $fareBreakupArray['TTSBreakup']['BasePrice'], "LabelText" => "Base Price"),
                "Taxes" => array("Value" => $fareBreakupArray['TTSBreakup']['Tax'], "LabelText" => "Taxes"),
                "Discount" =>  array("Value" => $fareBreakupArray['TTSBreakup']['Discount'], "LabelText" => "Discount (-)"),
                "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
            ),
            "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['TTSBreakup']['OfferedPrice'], "LabelText" => "Total Amount"),
            "GSTDetails" => $fareBreakupArray['GST'],
            "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
            "WebPDiscount" =>  array("Value" => $discount, "LabelText" => "Apply Discount"),
        );

        $cruiseSupplier = $CruiseBookingModel->getBulkData("offline_provider", array('cruise_service' => 'active'), 'id,supplier_name');


        $bookingInfo['FareBreakUp'] =  $FareBreakUp;

        $data = [
            'title' => $this->title,
            'amendment_list' => $amendment_list,
            'bookingDetail' => $bookingInfo,
            "cruiseSupplier" => $cruiseSupplier,
            "UpdateVoucher" => 1,
            'view' => "Cruise\Views\booking\cruise-booking-details",
        ];

        return view('template/sidebar-layout', $data);
    }

    function UpdateCruiseVoucherInfo()
    {

        $input = $this->request->getPost();
        $validate = new Validation();
        $validationConfigArray = $validate->voucher_update_validation($input);
        $this->validation->setRules($validationConfigArray);
        $rules = true;
        if ($input['booking_status'] != "Failed") {
            $rules = $this->validation->run($input);
        }
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CruiseModel = new CruiseModel();
            $AmendmentModel = new AmendmentModel();
            $booking_refrence_number = dev_decode($input['booking_ref_number']);
            $cruise_booking_id = dev_decode($input['cruise_booking_id']);
            $bookingInfo = $CruiseModel->getBookingDetailData($booking_refrence_number);
            if ($bookingInfo && (isset($bookingInfo['id']) && ($bookingInfo['id'] == $cruise_booking_id))) {

                $checkbookingflighttime = checkbookingflighttime($bookingInfo['created'], "Cruise");
                if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                    $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    $RedirectUrl = site_url('cruise');
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                }
                if ($bookingInfo['assign_user'] == $this->user_id || super_admin_cookie_data()['super_admin_user_details']['primary_user'] == 1) {
                    if (isset($input['refundbookingamount']) && $input['refundbookingamount'] == "yes") {
                        if (((isset($bookingInfo['booking_status']) && $bookingInfo['booking_status'] == 'Failed') || $input['booking_status'] == "Failed") && (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == 'Successful')) {
                            $cruiseBookingAccountinfo = $CruiseModel->getData('web_partner_account_log', array("booking_ref_no" => $bookingInfo['id'], 'service' => "cruise", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']), '*');
                            $checkflighBookingRefund = $CruiseModel->getData('web_partner_account_log', array("booking_ref_no" => $bookingInfo['id'], 'service' => "cruise", "action_type" => "bookingrefund", 'transaction_type' => "credit", 'web_partner_id' => $bookingInfo['web_partner_id']), '*');
                            if (empty($checkflighBookingRefund)) {
                                if (!empty($cruiseBookingAccountinfo) && $cruiseBookingAccountinfo) {
                                    $serviceLog = json_decode($cruiseBookingAccountinfo['service_log'], true);
                                    if (empty($serviceLog)) {
                                        $serviceLog = array();
                                    }
                                    $Confirmationprefix = $CruiseModel->getData("super_admin_website_setting", array('id' => 1), "cruise_refund_counter,cruise_refund_prefix,id");
                                    $CruiseModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("cruise_refund_counter" => ($Confirmationprefix['cruise_refund_counter'] + 1)));
                                    $BookingRefundConfirmationNumber = GenerateRefundConfirmationNumber("Cruise", $Confirmationprefix['cruise_refund_prefix'], ($Confirmationprefix['cruise_refund_counter'] + 1));
                                    $serviceLog['BookingRefrenceNumber'] = $booking_refrence_number;
                                    $web_partner_id = $cruiseBookingAccountinfo['web_partner_id'];
                                    $topupAmount = round_value(($cruiseBookingAccountinfo['debit']));
                                    $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                                    $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                    $WebPartnerAccountLogData['created'] = create_date();
                                    $WebPartnerAccountLogData['transaction_type'] = "credit";
                                    $WebPartnerAccountLogData['action_type'] = "bookingrefund";
                                    $WebPartnerAccountLogData['role'] = 'super_admin';
                                    $WebPartnerAccountLogData['remark'] = $input['remark'];
                                    $WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);
                                    $WebPartnerAccountLogData['service'] = "cruise";
                                    $WebPartnerAccountLogData['booking_confirmation_number'] = $cruiseBookingAccountinfo['booking_confirmation_number'];
                                    $WebPartnerAccountLogData['booking_refund_confirmation_number'] = $BookingRefundConfirmationNumber;
                                    $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                    $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                                    $available_balance = $AmendmentModel->web_partner_available_balance($web_partner_id);
                                    if (!$available_balance) {
                                        $available_balance['balance'] = 0;
                                    }
                                    $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));
                                    $WebPartnerAccountLogData['credit'] = $topupAmount;
                                    $added_data_id = $CruiseModel->insertData('web_partner_account_log', $WebPartnerAccountLogData);
                                    $updateData['acc_ref_number'] = reference_number($added_data_id);
                                    $CruiseModel->updateUserData("web_partner_account_log", array("id" => $added_data_id), $updateData);
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);;
                                    $RedirectUrl = site_url('cruise');
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            } else {
                                $message = array("StatusCode" => 2, "Message" => "Refund for this booking has been done already", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);;
                                $RedirectUrl = site_url('cruise');
                                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                return $this->response->setJSON($data_validation);
                            }
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                            $this->session->setFlashdata('Message', $message);;
                            $RedirectUrl = site_url('cruise');
                            $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                            return $this->response->setJSON($data_validation);
                        }
                    }
                    if ($input['booking_status'] == "Confirmed") {
                        $cruiseBookingAccountinfo = $CruiseModel->getData('web_partner_account_log', array("booking_ref_no" => $bookingInfo['id'], 'service' => "cruise", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']), '*');
                        if (empty($cruiseBookingAccountinfo)) {
                            if (isset($input['deductbookingamount']) && $input['deductbookingamount'] == "yes") {
                                $web_partner_id = $bookingInfo['web_partner_id'];
                                $available_balance = $AmendmentModel->web_partner_available_balance($web_partner_id);
                                $pax_detail = $AmendmentModel->get_list_by_table_name('cruise_booking_travelers', "first_name,last_name", ["cruise_booking_id" => $bookingInfo['id'], 'lead_pax' => 1]);

                                $booking_detail = $AmendmentModel->get_list_by_table_name('cruise_booking_list', "cruise_country,cruise_type,booking_ref_number", ["id" => $bookingInfo['id']]);

                                $PaxName = $pax_detail['first_name'] . ' ' . $pax_detail['last_name'];

                                $serviceLog = array('PaxName' => $PaxName, 'Sector' => $booking_detail['cruise_country'] . '-' . $booking_detail['cruise_type']);
                                if (isset($available_balance['balance']) && $available_balance['balance'] > $bookingInfo['total_price']) {
                                    $debitAmount = round_value(($bookingInfo['total_price']));
                                    $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                                    $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                    $WebPartnerAccountLogData['created'] = create_date();
                                    $WebPartnerAccountLogData['transaction_type'] = "debit";
                                    $WebPartnerAccountLogData['action_type'] = "booking";
                                    $WebPartnerAccountLogData['role'] = 'super_admin';
                                    $WebPartnerAccountLogData['remark'] = $input['remark'];
                                    $WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);
                                    $WebPartnerAccountLogData['service'] = "cruise";
                                    $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                    $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                                    $available_balance = $AmendmentModel->web_partner_available_balance($web_partner_id);
                                    if (!$available_balance) {
                                        $available_balance['balance'] = 0;
                                    }
                                    $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] - $debitAmount));
                                    $WebPartnerAccountLogData['debit'] = $debitAmount;
                                    $added_data_id = $CruiseModel->insertData('web_partner_account_log', $WebPartnerAccountLogData);
                                    $updateData['acc_ref_number'] = reference_number($added_data_id, "cruise", 1);
                                    $CruiseModel->updateUserData("web_partner_account_log", array("id" => $added_data_id), $updateData);
                                    $input['payment_status'] = "Successful";
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "Web Partner  have not enough balance", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);;
                                    $RedirectUrl = site_url('/cruise/get-update-cruise-voucher-info/' . $booking_refrence_number);
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            } else {
                                $message = array("StatusCode" => 2, "Message" => "Payment  have not done for this booking", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);;
                                $RedirectUrl = site_url('/cruise/get-update-cruise-voucher-info/' . $booking_refrence_number);
                                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                return $this->response->setJSON($data_validation);
                            }
                        }
                    }
                    $saveNoteData = array(
                        "booking_ref_no" => $cruise_booking_id,
                        'sup_staff_id' => $this->user_id,
                        'service_type' => "cruise",
                        'add_by' => "superadmin",
                        'comment' => $input['remark'],
                        'created' => create_date()
                    );
                    $saveNoteDataId = $CruiseModel->insertData('web_partner_booking_notes', $saveNoteData);
                    if ($input['booking_status'] == 'Confirmed' && $bookingInfo['booking_status'] != 'Confirmed') {
                        $Confirmationprefix = $CruiseModel->getData("super_admin_website_setting", array(), "cruise_confirmation_counter,cruise_confirmation_prefix,id");
                        $BookingConfirmationNumber = GenerateConfirmationNumber("Cruise", $Confirmationprefix['cruise_confirmation_prefix'], ($Confirmationprefix['cruise_confirmation_counter'] + 1));
                        $CruiseModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("cruise_confirmation_counter" => ($Confirmationprefix['cruise_confirmation_counter'] + 1)));
                        $CruiseModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $cruise_booking_id, "service" => "cruise", 'transaction_type' => "debit", 'action_type' => "booking"], ["booking_confirmation_number" => $BookingConfirmationNumber]);
                    }
                    $superAdminStaffDetail = super_admin_cookie_data()['super_admin_user_details'];
                    $updateFlightBookingData = array(
                        "booking_status" => $input['booking_status'],
                        "payment_status" => $input['payment_status'],
                        "issue_supplier" => $input['supplier'],
                        "confirmation_no" => $input['confirmation_number'],
                        "assign_user" => null,
                        'is_manual' => 1,
                        "update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),

                    );
                    $CruiseModel->updateUserData("cruise_booking_list", array("id" => $cruise_booking_id), $updateFlightBookingData);
                    $message = array("StatusCode" => 1, "Message" => "Voucher Update successfully", "Class" => "success_popup");
                    $this->session->setFlashdata('Message', $message);;
                    $RedirectUrl = site_url('cruise/cruise-booking-details/' . $booking_refrence_number);
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                } else {
                    $message = array("StatusCode" => 2, "Message" => "You are not eligible update Voucher", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);;
                    $RedirectUrl = site_url('cruise');
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
                $this->session->setFlashdata('Message', $message);;
                $RedirectUrl = site_url('cruise');
                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            }
        }
    }


    public function index()
    {

        $CruiseListModel = new CruiseListModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CruiseListModel->search_data($this->request->getGet(), $this->web_partner_id);
        } else {
            $lists = $CruiseListModel->cruise_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Cruise\Views\Cruise-list",
            'pager' => $CruiseListModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function add_cruise_view()
    {
        if (permission_access_error("Cruise", "add_cruise_list")) {

            $CruiseLineModel = new CruiseLineModel();
            $cruise_line = $CruiseLineModel->cruise_line_select($this->web_partner_id);


            $CruiseOceanModel = new CruiseOceanModel();
            $cruise_ocean = $CruiseOceanModel->cruise_ocean_select($this->web_partner_id);

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);


            $data = [
                'title' => $this->title,
                'cruise_line' => $cruise_line,
                'cruise_ocean' => $cruise_ocean,
                'cruise_ship' => $cruise_ship
            ];
            $add_blog_view = view('Modules\Cruise\Views\add-cruise', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function get_cruise_list_select()
    {
        $data = $this->request->getPost();
        $cruise_ocean_id = $data['country_id'];
        $CruisePortModel = new CruisePortModel();
        $cruise_port = $CruisePortModel->cruise_port_select($cruise_ocean_id, $this->web_partner_id);
        if ($cruise_port) {
            echo "<option value='' selected>" . 'Select Departure Port' . "</option>";
            foreach ($cruise_port as $data) {
                echo "<option value=" . $data['id'] . ">" . $data['port_name'] . "</option>";
            }
        } else {
            echo "<option value='' selected>" . 'Select Departure Port' . "</option>";
        }
    }

    public function add_cruise()
    {
        if (permission_access_error("Cruise", "add_cruise_list")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_list_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseListModel = new CruiseListModel();
                $data = $this->request->getPost();

                if (isset($data['adult_passport']) && $data['adult_passport'] == 1) {
                    $data['adult_passport'] = 1;
                } else {
                    $data['adult_passport'] = 0;
                }
                if (isset($data['child_passport']) && $data['child_passport'] == 1) {
                    $data['child_passport'] = 1;
                } else {
                    $data['child_passport'] = 0;
                }
                if (isset($data['infant_passport']) && $data['infant_passport'] == 1) {
                    $data['infant_passport'] = 1;
                } else {
                    $data['infant_passport'] = 0;
                }
                $final_itinerary = [];
                if (isset($data['cruise_itinerary'])) {
                    $cruise_itinerary = $data['cruise_itinerary'];
                    foreach ($cruise_itinerary['city'] as $key => $item) {
                        if (isset($cruise_itinerary['stopas'][$key]) && $cruise_itinerary['stopas'][$key] == "yes") {
                            $final_itinerary[$key]['stopas'] = 'yes';
                        } else {
                            $final_itinerary[$key]['stopas'] = 'no';
                        }
                        $final_itinerary[$key]['city'] = $cruise_itinerary['city'][$key];
                        $final_itinerary[$key]['time_duration'] = $cruise_itinerary['time_duration'][$key];
                        $final_itinerary[$key]['description'] = $cruise_itinerary['description'][$key];
                    }
                }

                $data['no_of_nights'] = count($cruise_itinerary['city']);

                $data['cruise_itinerary'] = json_encode($final_itinerary);
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $CruiseListModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise  successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise   not added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_list")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseListModel = new CruiseListModel();
            $CruiseLineModel = new CruiseLineModel();
            $details = $CruiseListModel->cruise_list_details($id, $this->web_partner_id);
            $cruise_line = $CruiseLineModel->cruise_line_select($this->web_partner_id);

            $CruiseOceanModel = new CruiseOceanModel();
            $cruise_ocean = $CruiseOceanModel->cruise_ocean_select($this->web_partner_id);
            $CruisePortModel = new CruisePortModel();
            $cruise_port = $CruisePortModel->cruise_port_select($details['cruise_ocean_id'], $this->web_partner_id);

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);

            $itinerary = null;
            $itinerary_count = null;
            if ($details['cruise_itinerary']) {
                $itinerary = json_decode($details['cruise_itinerary'], true);
                $itinerary_count = count($itinerary);
            }

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_line' => $cruise_line,
                'itinerary_count' => $itinerary_count,
                'itinerary' => $itinerary,
                'cruise_port' => $cruise_port,
                'cruise_ocean' => $cruise_ocean,
                'cruise_ship' => $cruise_ship
            ];
            $details = view('Modules\Cruise\Views\edit-cruise', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_cruise()
    {
        if (permission_access_error("Cruise", "edit_cruise_list")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->cruise_list_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                /* $errors['cruise_itinerary']['city'][] =$errors['cruise_itinerary.city.*'];*/
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseListModel = new CruiseListModel();
                $data = $this->request->getPost();

                if (isset($data['adult_passport']) && $data['adult_passport'] == 1) {
                    $data['adult_passport'] = 1;
                } else {
                    $data['adult_passport'] = 0;
                }
                if (isset($data['child_passport']) && $data['child_passport'] == 1) {
                    $data['child_passport'] = 1;
                } else {
                    $data['child_passport'] = 0;
                }
                if (isset($data['infant_passport']) && $data['infant_passport'] == 1) {
                    $data['infant_passport'] = 1;
                } else {
                    $data['infant_passport'] = 0;
                }
                $final_itinerary = [];
                if (isset($data['cruise_itinerary'])) {
                    $cruise_itinerary = $data['cruise_itinerary'];
                    foreach ($cruise_itinerary['city'] as $key => $item) {
                        if (isset($cruise_itinerary['stopas'][$key]) && $cruise_itinerary['stopas'][$key] == "yes") {
                            $final_itinerary[$key]['stopas'] = 'yes';
                        } else {
                            $final_itinerary[$key]['stopas'] = 'no';
                        }
                        $final_itinerary[$key]['city'] = $cruise_itinerary['city'][$key];
                        $final_itinerary[$key]['time_duration'] = $cruise_itinerary['time_duration'][$key];
                        $final_itinerary[$key]['description'] = $cruise_itinerary['description'][$key];
                    }
                }

                $data['no_of_nights'] = count($cruise_itinerary['city']);

                $data['cruise_itinerary'] = json_encode($final_itinerary);
                $data['modified'] = create_date();
                $added_data = $CruiseListModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise  successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise  not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function cruise_list_status_change()
    {

        if (permission_access_error("Cruise", "cruise_list_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseListModel = new CruiseListModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseListModel->cruise_list_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_list()
    {
        if (permission_access_error("Cruise", "delete_cruise_list")) {
            $CruiseListModel = new CruiseListModel();
            $ids = $this->request->getPost('checklist');

            $delete = $CruiseListModel->remove_cruise_list($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function cruise_booking_detail()
    {
        $uri = service('uri');
        $ref_id = $uri->getSegment(3);

        $CruiseBookingModel = new CruiseBookingModel();
        $bookingInfo = $CruiseBookingModel->getBookingDetailData($ref_id, $this->web_partner_id);
        $amendment_list = $CruiseBookingModel->amendment_list($bookingInfo['id'], $this->web_partner_id);
        // pr($bookingInfo);exit;
        $FareBreakUp  = array();
        $fareBreakupArray  =  json_decode($bookingInfo['web_partner_fare_break_up'], true);
        $markup  =  isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp']['Value'] : 0;
        $discount  =  isset($fareBreakupArray['WebpDiscount']) ? $fareBreakupArray['WebpDiscount']['Value'] : 0;

        $FareBreakUp =  array(
            "FareBreakup" => array(
                "BaseFare" =>  array("Value" => $fareBreakupArray['TTSBreakup']['BasePrice'], "LabelText" => "Base Price"),
                "Taxes" => array("Value" => $fareBreakupArray['TTSBreakup']['Tax'], "LabelText" => "Taxes"),
                /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                "Discount" =>  array("Value" => $fareBreakupArray['TTSBreakup']['Discount'], "LabelText" => "Discount (-)"),
                "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
            ),
            "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['TTSBreakup']['OfferedPrice'], "LabelText" => "Total Amount"),
            "GSTDetails" => $fareBreakupArray['GST'],
            "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
            "WebPDiscount" =>  array("Value" => $discount, "LabelText" => "Apply Discount"),
        );


        $bookingInfo['FareBreakUp'] =  $FareBreakUp;

        $data = [
            'title' => $this->title,
            'amendment_list' => $amendment_list,
            'bookingDetail' => $bookingInfo,
            'view' => "Cruise\Views\booking\cruise-booking-details",
        ];

        return view('template/sidebar-layout', $data);
    }

    public function confirmation()
    {
        $uri = service('uri');
        $ticketData = $uri->getSegment(3);
        $bookingId = json_decode(dev_decode($ticketData), true);
        if ($bookingId) {

            $CruiseBookingModel = new CruiseBookingModel();
            $bookingInfo = $CruiseBookingModel->getBookingConfirmationData($bookingId['BookingId']);

            $BookingRefNumber = $bookingInfo['booking_ref_number'];
            $BookingStatus = $bookingInfo['booking_status'];
            $PaymentStatus = $bookingInfo['payment_status'];

            $fareBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
            $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp']['Value'] : 0;
            $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount']['Value'] : 0;
            $addMarkupInTax = 0;
            $addMarkupInServiceCharge = 0;
            if (isset($fareBreakupArray['WebPMarkUp']['DisplayFormat']) && $fareBreakupArray['WebPMarkUp']['DisplayFormat'] == 'in_service_charge') {
                $addMarkupInServiceCharge = $markup;
            } else {
                $addMarkupInTax = $markup;
            }

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['TTSBreakup']['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['TTSBreakup']['Tax'] + $addMarkupInTax, "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['TTSBreakup']['ServiceCharges'] + $addMarkupInServiceCharge, "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $discount + $fareBreakupArray['TTSBreakup']['Discount'], "LabelText" => "Discount (-)"),
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['TTSBreakup']['PublishedPrice'] + $markup, "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => $markup,
                "WebPDiscount" => $discount,
            );

            $ConfrimationData = array(
                "ConfirmationBookingData" => $bookingInfo, "bookingRefNumber" => $BookingRefNumber, "paymentStatus" => $PaymentStatus, 'FareBreakUpData' => $FareBreakUp,
                "bookingStatus" => $BookingStatus, "cruiseLineName" => $bookingInfo['cruise_line_name'], "ShipName" => $bookingInfo['ship_name'], 'departureDate' => $bookingInfo['sailing_date'], 'cruiseItinerary' => json_decode($bookingInfo['cruise_itinerary'], true)
            );

            $data = [
                'title' => $this->title,
                'booking' => $ConfrimationData,
                'view' => "Cruise\Views\booking\booking-confirmation-page",
            ];
            return view('template/sidebar-layout', $data);
        } else {
            return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
        }
    }

    public function cruise_line_list()
    {
        if (permission_access_error("Cruise", "cruise_line_list")) {
            $CruiseLineModel = new CruiseLineModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseLineModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $CruiseLineModel->cruise_line_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\Cruise-line-list",
                'pager' => $CruiseLineModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];


            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_line_template()
    {
        if (permission_access_error("Cruise", "add_cruise_line")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Cruise\Views\add-cruise-line', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_cruise_line()
    {
        if (permission_access_error("Cruise", "add_cruise_line")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_line_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseLineModel = new CruiseLineModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'cruise_line_image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $CruiseLineModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise line successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise line  not added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_line_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_line")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseLineModel = new CruiseLineModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $CruiseLineModel->cruise_line_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Cruise\Views\edit-cruise-line', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_cruise_line()
    {
        if (permission_access_error("Cruise", "edit_cruise_line")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'cruise_line_image';

            $validate = new Validation();

            $images = $this->request->getFile($field_name);
            if ($images->getName() == '') {
                unset($validate->cruise_line_validation[$field_name]);
            }

            $rules = $this->validate($validate->cruise_line_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseLineModel = new CruiseLineModel();
                $data = $this->request->getPost();
                $previous_data = $CruiseLineModel->cruise_line_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $CruiseLineModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "cruise line successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "cruise line not updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $CruiseLineModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise line successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise line not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function cruise_line_status_change()
    {

        if (permission_access_error("Cruise", "cruise_line_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseLineModel = new CruiseLineModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseLineModel->cruise_line_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise line status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise line status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_line()
    {
        if (permission_access_error("Cruise", "delete_cruise_line")) {
            $CruiseLineModel = new CruiseLineModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'cruise_line_image';

            foreach ($ids as $id) {
                $details = $CruiseLineModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }

                $blog_delete = $CruiseLineModel->remove_cruise_line($id, $this->web_partner_id);
            }

            if ($blog_delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise line  successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise line  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function cruise_ship_list()
    {
        if (permission_access_error("Cruise", "cruise_ship_list")) {
            $CruiseShipModel = new CruiseShipModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseShipModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $CruiseShipModel->cruise_ship_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\Cruise-ship-list",
                'pager' => $CruiseShipModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_ship_template()
    {
        if (permission_access_error("Cruise", "add_cruise_ship")) {
            $CruiseLineModel = new CruiseLineModel();
            $cruise_line = $CruiseLineModel->cruise_line_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_line' => $cruise_line
            ];
            $view = view('Modules\Cruise\Views\add-cruise-ship', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_cruise_ship()
    {
        if (permission_access_error("Cruise", "add_cruise_ship")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_ship_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipModel = new CruiseShipModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'ship_image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $CruiseShipModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise ship successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise ship  not added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_ship_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_ship")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseShipModel = new CruiseShipModel();
            $CruiseLineModel = new CruiseLineModel();
            $cruise_line = $CruiseLineModel->cruise_line_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'cruise_line' => $cruise_line,
                'details' => $CruiseShipModel->cruise_ship_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Cruise\Views\edit-cruise-ship', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_cruise_ship()
    {
        if (permission_access_error("Cruise", "edit_cruise_ship")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'ship_image';

            $validate = new Validation();

            $images = $this->request->getFile($field_name);
            if ($images->getName() == '') {
                unset($validate->cruise_ship_validation[$field_name]);
            }

            $rules = $this->validate($validate->cruise_ship_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipModel = new CruiseShipModel();
                $data = $this->request->getPost();
                $previous_data = $CruiseShipModel->cruise_ship_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $CruiseShipModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "cruise ship successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "cruise ship not updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $CruiseShipModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise ship successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise ship not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function cruise_ship_status_change()
    {

        if (permission_access_error("Cruise", "cruise_ship_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipModel = new CruiseShipModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseShipModel->cruise_ship_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise ship status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise ship status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_ship()
    {
        if (permission_access_error("Cruise", "delete_cruise_ship")) {
            $CruiseShipModel = new CruiseShipModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'ship_image';

            foreach ($ids as $id) {
                $details = $CruiseShipModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }

                $blog_delete = $CruiseShipModel->remove_cruise_ship($id, $this->web_partner_id);
            }

            if ($blog_delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise ship  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise ship  not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function cruise_ship_gallery_list()
    {
        if (permission_access_error("Cruise", "cruise_ship_gallery_list")) {
            $CruiseShipGalleryModel = new CruiseShipGalleryModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseShipGalleryModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $CruiseShipGalleryModel->cruise_ship_gallery_list($this->web_partner_id);
            }  //prd($lists);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\Cruise-ship-gallery-list",
                'pager' => $CruiseShipGalleryModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_ship_gallery_template()
    {
        if (permission_access_error("Cruise", "add_cruise_ship_gallery")) {
            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_ship' => $cruise_ship
            ];
            $view = view('Modules\Cruise\Views\add-cruise-ship-gallery', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_cruise_ship_gallery()
    {
        if (permission_access_error("Cruise", "add_cruise_ship_gallery")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_ship_gallery_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipGalleryModel = new CruiseShipGalleryModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'images';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $CruiseShipGalleryModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise ship gallery successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise ship gallery not added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_ship_gallery_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_ship_gallery")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseShipModel = new CruiseShipModel();
            $CruiseShipGalleryModel = new CruiseShipGalleryModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'cruise_ship' => $cruise_ship,
                'details' => $CruiseShipGalleryModel->cruise_ship_gallery_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Cruise\Views\edit-cruise-ship-gallery', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_cruise_ship_gallery()
    {
        if (permission_access_error("Cruise", "edit_cruise_ship_gallery")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'images';

            $validate = new Validation();

            $images = $this->request->getFile($field_name);
            if ($images->getName() == '') {
                unset($validate->cruise_ship_gallery_validation[$field_name]);
            }

            $rules = $this->validate($validate->cruise_ship_gallery_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipGalleryModel = new CruiseShipGalleryModel();
                $data = $this->request->getPost();
                $previous_data = $CruiseShipGalleryModel->cruise_ship_gallery_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $CruiseShipGalleryModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "cruise ship gallery successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "cruise ship gallery not updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $CruiseShipGalleryModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "cruise ship gallery successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "cruise ship not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function cruise_ship_gallery_status_change()
    {

        if (permission_access_error("Cruise", "cruise_ship_gallery_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseShipGalleryModel = new CruiseShipGalleryModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseShipGalleryModel->cruise_ship_gallery_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise ship gallery status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise ship gallery status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_ship_gallery()
    {
        if (permission_access_error("Cruise", "delete_cruise_ship_gallery")) {
            $CruiseShipGalleryModel = new CruiseShipGalleryModel();
            $ids = $this->request->getPost('checklist');
            $field_name = 'images';
            foreach ($ids as $id) {
                $details = $CruiseShipGalleryModel->delete_image($id, $this->web_partner_id);
                if ($details[$field_name]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $CruiseShipGalleryModel->remove_cruise_ship_gallery($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise ship gallery image successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise ship gallery image not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function cruise_cabin_list()
    {
        if (permission_access_error("Cruise", "cruise_cabin_list")) {
            $CruiseCabinModel = new CruiseCabinModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseCabinModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $CruiseCabinModel->cruise_cabin_list($this->web_partner_id);
            } // prd($lists);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\Cruise-cabin-list",
                'pager' => $CruiseCabinModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_cabin_template()
    {
        if (permission_access_error("Cruise", "add_cruise_cabin")) {
            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_ship' => $cruise_ship
            ];
            $view = view('Modules\Cruise\Views\add-cruise-cabin', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_cruise_cabin()
    {
        if (permission_access_error("Cruise", "add_cruise_cabin")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_cabin_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseCabinModel = new CruiseCabinModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $CruiseCabinModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise cabin successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise cabin not added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_cabin_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_cabin")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseShipModel = new CruiseShipModel();
            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'cruise_ship' => $cruise_ship,
                'details' => $CruiseCabinModel->cruise_cabin_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Cruise\Views\edit-cruise-cabin', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_cruise_cabin()
    {
        if (permission_access_error("Cruise", "edit_cruise_cabin")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_cabin_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseCabinModel = new CruiseCabinModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $added_data = $CruiseCabinModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise cabin successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise cabin not updated", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function cruise_cabin_status_change()
    {

        if (permission_access_error("Cruise", "cruise_cabin_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseCabinModel = new CruiseCabinModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseCabinModel->cruise_cabin_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise cabin status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise cabin status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_cabin()
    {
        if (permission_access_error("Cruise", "delete_cruise_cabin")) {
            $CruiseCabinModel = new CruiseCabinModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CruiseCabinModel->remove_cruise_cabin($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise cabin successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise ship cabin not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function cruise_ocean_list()
    {
        if (permission_access_error("Cruise", "cruise_ocean_list")) {
            $CruiseOceanModel = new CruiseOceanModel();
            $cruise_list_id = dev_decode($this->request->uri->getSegment(3));
            $lists = $CruiseOceanModel->cruise_ocean_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\cruise-ocean-list",
                'pager' => $CruiseOceanModel->pager,
                'cruise_list_id' => $cruise_list_id
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_ocean_template()
    {
        if (permission_access_error("Cruise", "add_cruise_ocean")) {
            $data = [
                'title' => $this->title,
            ];
            $details = view('Modules\Cruise\Views\add-cruise-ocean', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_ocean()
    {
        if (permission_access_error("Cruise", "add_cruise_ocean")) {
            $validate = new Validation();

            $rules = $this->validate($validate->cruise_ocean_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseOceanModel = new CruiseOceanModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $CruiseOceanModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise ocean successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise ocean not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_cruise_ocean_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_ocean")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruiseOceanModel = new CruiseOceanModel();
            $details = $CruiseOceanModel->cruise_ocean_details($id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\Cruise\Views\edit-cruise-ocean', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_cruise_ocean()
    {
        if (permission_access_error("Cruise", "edit_cruise_ocean")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->cruise_ocean_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseOceanModel = new CruiseOceanModel();
                $data = $this->request->getPost();

                $data['modified'] = create_date();
                $added_data = $CruiseOceanModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise ocean successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise ocean not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function cruise_ocean_status_change()
    {

        if (permission_access_error("Cruise", "cruise_ocean_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseOceanModel = new CruiseOceanModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruiseOceanModel->cruise_ocean_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise ocean status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise ocean status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_ocean()
    {
        if (permission_access_error("Cruise", "delete_cruise_ocean")) {
            $CruiseOceanModel = new CruiseOceanModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CruiseOceanModel->remove_cruise_ocean($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise ocean successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise ocean not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function cruise_port_list()
    {
        if (permission_access_error("Cruise", "add_cruise_port")) {
            $CruisePortModel = new CruisePortModel();
            $cruise_list_id = dev_decode($this->request->uri->getSegment(3));
            $lists = $CruisePortModel->cruise_port_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\cruise-port-list",
                'pager' => $CruisePortModel->pager,
                'cruise_list_id' => $cruise_list_id
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_port_template()
    {
        if (permission_access_error("Cruise", "add_cruise_port")) {
            $CruiseOceanModel = new CruiseOceanModel();
            $cruise_ocean = $CruiseOceanModel->cruise_ocean_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_ocean' => $cruise_ocean
            ];
            $details = view('Modules\Cruise\Views\add-cruise-port', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_port()
    {
        if (permission_access_error("Cruise", "add_cruise_port")) {
            $validate = new Validation();

            $rules = $this->validate($validate->cruise_port_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePortModel = new CruisePortModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $CruisePortModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise port successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise port not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_cruise_port_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_port")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruisePortModel = new CruisePortModel();
            $details = $CruisePortModel->cruise_port_details($id, $this->web_partner_id);

            $CruiseOceanModel = new CruiseOceanModel();
            $cruise_ocean = $CruiseOceanModel->cruise_ocean_select($this->web_partner_id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_ocean' => $cruise_ocean
            ];
            $details = view('Modules\Cruise\Views\edit-cruise-port', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_cruise_port()
    {
        if (permission_access_error("Cruise", "edit_cruise_port")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->cruise_port_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePortModel = new CruisePortModel();
                $data = $this->request->getPost();

                $data['modified'] = create_date();
                $added_data = $CruisePortModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise port successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise port not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function cruise_port_status_change()
    {

        if (permission_access_error("Cruise", "cruise_port_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePortModel = new CruisePortModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruisePortModel->cruise_port_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise port status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise port status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_port()
    {
        if (permission_access_error("Cruise", "delete_cruise_port")) {
            $CruisePortModel = new CruisePortModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CruisePortModel->remove_cruise_port($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise port successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise port not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }




    public function cruise_price_list()
    {
        if (permission_access_error("Cruise", "cruise_price_list")) {
            $CruisePriceModel = new CruisePriceModel();
            $cruise_list_id = dev_decode($this->request->uri->getSegment(3));
            $lists = $CruisePriceModel->price_list($cruise_list_id);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Cruise\Views\cruise-price-list",
                'pager' => $CruisePriceModel->pager,
                'cruise_list_id' => $cruise_list_id
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_cruise_price_view()
    {
        if (permission_access_error("Cruise", "add_cruise_price")) {
            $cruise_list_id = dev_decode($this->request->uri->getSegment(3));
            $CruiseCabinModel = new CruiseCabinModel();

            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_list_id' => $cruise_list_id,
                'cruise_cabin' => $cruise_cabin
            ];
            $details = view('Modules\Cruise\Views\add-cruise-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_price()
    {
        if (permission_access_error("Cruise", "add_cruise_price")) {
            $validate = new Validation();

            $rules = $this->validate($validate->cruise_price_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePriceModel = new CruisePriceModel();
                $data = $this->request->getPost();
                $cruise_list_id = dev_decode($this->request->uri->getSegment(3));
                if (isset($data['book_online']) && $data['book_online'] == 'yes') {
                    $data['book_online'] = 'yes';
                } else {
                    $data['book_online'] = 'no';
                }

                if ($data['selling_date']) {
                    $data['selling_date'] = strtotime($data['selling_date']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['cruise_list_id'] = $cruise_list_id;
                $added_data = $CruisePriceModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise price successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise price not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_cruise_price_view()
    {
        if (permission_access_error("Cruise", "edit_cruise_price")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CruisePriceModel = new CruisePriceModel();
            $details = $CruisePriceModel->price_details($id);

            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select($this->web_partner_id);
            if (isset($details['selling_date']) && $details['selling_date'] != '') {
                $details['selling_date'] = date('d-M-Y', $details['selling_date']);
            }

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_cabin' => $cruise_cabin
            ];
            $details = view('Modules\Cruise\Views\edit-cruise-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_cruise_price()
    {
        if (permission_access_error("Cruise", "edit_cruise_price")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->cruise_price_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePriceModel = new CruisePriceModel();
                $data = $this->request->getPost();
                if (isset($data['book_online']) && $data['book_online'] == 'yes') {
                    $data['book_online'] = 'yes';
                } else {
                    $data['book_online'] = 'no';
                }
                if ($data['selling_date']) {
                    $data['selling_date'] = strtotime($data['selling_date']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data['modified'] = create_date();
                $added_data = $CruisePriceModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise price successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise price not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function cruise_price_status_change()
    {

        if (permission_access_error("Cruise", "cruise_price_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruisePriceModel = new CruisePriceModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $CruisePriceModel->price_status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "cruise price status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise price status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_price()
    {
        if (permission_access_error("Cruise", "delete_cruise_price")) {
            $CruisePriceModel = new CruisePriceModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CruisePriceModel->remove_price($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise price successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise price not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }



    public function get_cruise_ship_id_select()
    {
        $data = $this->request->getPost();
        $cruise_line_id = $data['country_id'];
        $CruiseShipModel = new CruiseShipModel();
        $cruise_port = $CruiseShipModel->cruise_ship_select_cruise_line($cruise_line_id);
        if ($cruise_port) {
            echo "<option value='' selected>" . 'Select Cruise Ship' . "</option>";
            foreach ($cruise_port as $data) {
                echo "<option value=" . $data['id'] . ">" . $data['ship_name'] . "</option>";
            }
        } else {
            echo "<option value='ANY' selected>" . 'ANY Cruise Ship' . "</option>";
        }
    }

    public function get_cruise_cabin_id_select()
    {
        $data = $this->request->getPost();
        $cruise_ship_id = $data['country_id'];
        $CruiseCabinModel = new CruiseCabinModel();
        $cruise_cabin = $CruiseCabinModel->cruise_cabin_select_ship_id($cruise_ship_id, $this->web_partner_id);
        if ($cruise_cabin) {
            echo "<option value='' selected>" . 'Select Cruise Cabin' . "</option>";
            foreach ($cruise_cabin as $data) {
                echo "<option value=" . $data['id'] . ">" . $data['cabin_name'] . "</option>";
            }
        } else {
            echo "<option value='ANY' selected>" . 'ANY Cruise Cabin' . "</option>";
        }
    }

    function amendment_lists()
    {
        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $list = $AmendmentModel->search_bookings($getData, $this->web_partner_id);
        } else {
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            $list = $AmendmentModel->amendment_list($this->web_partner_id, $bookingType, $source);
        }

        $data = [
            'title' => $this->title,
            'view' => "Cruise\Views/amendment/amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    function amendments_details()
    {
        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $AmendmentModel = new AmendmentModel();
        $AmendmentDetail = $AmendmentModel->amendment_detail($amendmentId, $this->web_partner_id);

        if ($AmendmentDetail) {

            $FareBreakUp = array();
            $fareBreakupArray = json_decode($AmendmentDetail['web_partner_fare_break_up'], true);
            $super_admin_fare_break_up = json_decode($AmendmentDetail['super_admin_fare_break_up'], true);
            $markup = isset($super_admin_fare_break_up['WebPMarkUp']) ? $super_admin_fare_break_up['WebPMarkUp']['Value'] : 0;
            $discount = isset($super_admin_fare_break_up['WebPDiscount']['Value']) ? $super_admin_fare_break_up['WebPDiscount']['Value'] : 0;
            // $markup = isset($super_admin_fare_break_up['SUP_Markup']) ? $super_admin_fare_break_up['SUP_Markup'] : 0;
            // $discount = isset($super_admin_fare_break_up['SUP_Discount']) ? $super_admin_fare_break_up['SUP_Discount'] + $super_admin_fare_break_up['SUP_ExtraDiscount'] : 0;


            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $super_admin_fare_break_up['TTSBreakup']['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $super_admin_fare_break_up['TTSBreakup']['Tax'], "LabelText" => "Taxes"),
                    /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                    /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                    "Discount" => array("Value" => $super_admin_fare_break_up['TTSBreakup']['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $super_admin_fare_break_up['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $super_admin_fare_break_up['TDS'] + $super_admin_fare_break_up['TTSBreakup']['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $super_admin_fare_break_up['GST'],
                "MarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                "Discount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
            );

            $AmendmentDetail['FareBreakUp'] = $FareBreakUp;

            $AmendmentDetail['web_partner_fare_break_up'] = $fareBreakupArray;
            $data = [
                'title' => $this->title,
                'view' => "Cruise\Views/amendment/amendments-detail",
                "amendmentDetail" => $AmendmentDetail,
            ];
            return view('template/sidebar-layout', $data);
        } else {
            return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
        }
    }

    function amendment_cancellation_charge()
    {
        $data = $this->request->getPost();
        $validate = new Validation();
        $rules = $this->validate($validate->amendment_refund_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $AmendmentModel = new AmendmentModel();
            $amendment_id = dev_decode($data['amendment_id']);
            $AmendmentDetail = $AmendmentModel->amendment_detail_by_id($amendment_id, $this->web_partner_id);

            if ($AmendmentDetail) {
                $hotel_booking_id = $AmendmentDetail['booking_ref_no'];

                $webPartnerGstCode = $AmendmentModel->webpartner_gst_state_code($AmendmentDetail['web_partner_id']);

                $refundAmount = $data['refund'];

                $TDS = 0;

                $TDSReturnidentifier = "no";

                if (isset($data['tdsreturn']) && $data['tdsreturn'] == "yes") {
                    $TDSReturnidentifier = "yes";
                    $TDSReturn = $TDS;
                }
                $GSTInfo = gst_calculate("Cruise", $webPartnerGstCode, super_admin_website_setting['gst_state_code'], $data['service_charge']);


                $RefundChargeDetails = array(
                    "Charge" => $data['charge'],
                    "ServiceCharge" => $data['service_charge'],
                    "Refund" => $refundAmount,
                    "GST" => $GSTInfo,
                    "TDSReturnIdentifier" => $TDSReturnidentifier,
                );

                $updateData = array();
                if ($refundAmount > 0) {
                    $updateData = array(
                        "amendment_charges" => json_encode($RefundChargeDetails),
                        "amendment_type" => $AmendmentDetail['amendment_type'],
                        "amendment_id" => $amendment_id,

                    );
                    $update = $AmendmentModel->updateWithTableData("cruise_booking_list", $updateData, array("id" => $hotel_booking_id), array("web_partner_id" => $this->web_partner_id));

                    $updateAmendmentData = array();
                    $updateAmendmentData['sup_staff_id'] = $this->user_id;
                    $updateAmendmentData['refund_status'] = "Open";
                    $updateAmendmentData['refund_amount'] = $refundAmount;
                    $updateAmendmentData['modified'] = create_date();
                    $updateAmendmentData['refund_date'] = create_date();
                    $update = $AmendmentModel->updateData($updateAmendmentData, array("id" => $amendment_id), array("web_partner_id" => $this->web_partner_id));
                    $message = array("StatusCode" => 0, "Message" => "Refund is Opened", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Please check refund amount value is negative", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function amendment_status_change()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->amendment_status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();
            $AmendmentModel = new AmendmentModel();
            $amendment_id = dev_decode($data['amendment_id']);
            if ($data['status'] == "approved") {
                $AmendmentDetail = $AmendmentModel->amendment_detail($amendment_id, $this->web_partner_id);
                if ($AmendmentDetail['amendment_type'] == "cancellation") {
                    $booking_id = $AmendmentDetail['booking_ref_no'];
                    if ($AmendmentDetail['amendment_type'] == 'cancellation' && $data['status'] == 'approved') {
                        $AmendmentModel->updateWithTableData("cruise_booking_list", array("booking_status" => 'Cancelled'), array("id" => $booking_id), array("web_partner_id" => $this->web_partner_id));
                    }
                }
            }


            $updateData['amendment_status'] = $data['status'];
            $updateData['remark_from_super_admin'] = $data['admin_remark'];
            $updateData['sup_staff_id'] = $this->user_id;


            $update = $AmendmentModel->updateData($updateData, array("id" => $amendment_id), array("web_partner_id" => $this->web_partner_id));

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Amendment status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Amendment status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    function refund_lists()
    {
        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $list = $AmendmentModel->search_refund_list($getData, $this->web_partner_id);
        } else {
            $list = $AmendmentModel->refund_list($this->web_partner_id);
        }

        if (isset($getData['export_excel']) && $getData['export_excel'] == 1) {
            if (isset($getData['key'])) {

                $excellist = $AmendmentModel->search_refund_list($getData, $this->web_partner_id);
            } else {
                $excellist = $AmendmentModel->search_refund_list();
            }
            HotelListings::export_refund_list($excellist);
        }

        $data = [
            'title' => $this->title,
            'view' => "Cruise\Views/refund/refund-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function refund_close()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->refund_close_status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();
            $AmendmentModel = new AmendmentModel();
            $CruiseModel = new CruiseModel();
            $amendment_id = dev_decode($data['amendment_id']);
            $AmendmentDetail = $AmendmentModel->amendment_detail_by_id($amendment_id, $this->web_partner_id);


            if ($AmendmentDetail) {
                $booking_id = $AmendmentDetail['booking_ref_no'];


                if ($AmendmentDetail) {


                    $refundAmount = 0;
                    $OtherCharges = 0;
                    $Discount = 0;
                    $TDS = 0;
                    $GSTAmount = 0;
                    $AgentCommission = 0;
                    $ssrReturnAmount = 0;
                    $TDSReturn = 0;


                    $refundAmount = round_value($AmendmentDetail['refund_amount']);
                    $cruiseBookingAccountinfo      =  $AmendmentModel->get_list_by_table_name('web_partner_account_log', '*', array("booking_ref_no" => $booking_id, 'service' => "cruise", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $AmendmentDetail['web_partner_id']));
                    $webPartnerBalanceInfo = $AmendmentModel->web_partner_available_balance($AmendmentDetail['web_partner_id']);
                    $Confirmationprefix          =  $CruiseModel->getData("super_admin_website_setting", array('id' => 1), "cruise_refund_counter,cruise_refund_prefix,id");
                    $CruiseModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("cruise_refund_counter" => ($Confirmationprefix['cruise_refund_counter'] + 1)));
                    $BookingRefundConfirmationNumber  =   GenerateRefundConfirmationNumber("Cruise", $Confirmationprefix['cruise_refund_prefix'], ($Confirmationprefix['cruise_refund_counter'] + 1));
                    $serviceLog['BookingRefrenceNumber'] =  $cruiseBookingAccountinfo['booking_confirmation_number'];
                    $webPartnerBalance = 0;
                    if (isset($webPartnerBalanceInfo['balance'])) {
                        $webPartnerBalance = $webPartnerBalanceInfo['balance'];
                    }
                    $WebPatnerAccountLogData['web_partner_id'] = $AmendmentDetail['web_partner_id'];
                    $WebPatnerAccountLogData['user_id'] = $this->user_id;
                    $WebPatnerAccountLogData['created'] = create_date();
                    $WebPatnerAccountLogData['transaction_type'] = 'credit';
                    $WebPatnerAccountLogData['action_type'] = 'refund';
                    $WebPatnerAccountLogData['role'] = 'super_admin';
                    $WebPatnerAccountLogData['credit'] = $refundAmount;
                    $WebPatnerAccountLogData['service'] = "cruise";
                    $WebPatnerAccountLogData['booking_confirmation_number'] =  $cruiseBookingAccountinfo['booking_confirmation_number'];
                    $WebPatnerAccountLogData['booking_refund_confirmation_number'] =  $BookingRefundConfirmationNumber;

                    $pax_detail = $AmendmentModel->get_list_by_table_name('cruise_booking_travelers', "first_name,last_name", ["cruise_booking_id" => $AmendmentDetail['booking_ref_no'], 'lead_pax' => 1]);

                    $booking_detail = $AmendmentModel->get_list_by_table_name('cruise_booking_list', "sailing_date,cruise_line_name,ship_name,no_of_travellers,cruise_ocean,departure_port,booking_ref_number", ["id" => $AmendmentDetail['booking_ref_no']]);

                    $PaxName = $pax_detail['first_name'] . ' ' . $pax_detail['last_name'] . ' X ' . $booking_detail['no_of_travellers'];

                    $WebPatnerAccountLogData['service_log'] = json_encode(array('PaxName' => $PaxName, 'SailingDate' => $booking_detail['sailing_date'], "CruiseLineName" =>  $booking_detail['cruise_line_name'], "ShipName" =>  $booking_detail['ship_name'], "CruiseOcean" =>  $booking_detail['cruise_ocean'], "DeparturePort" =>  $booking_detail['departure_port']));

                    $WebPatnerAccountLogData['remark'] = "Refund for Reference No - " . $booking_detail['booking_ref_number'] . " Remark " . $AmendmentDetail['remark_from_web_partner'] . " Remark " . $AmendmentDetail['remark_from_super_admin'] . " Remark " . $data['account_remark'];


                    $WebPatnerAccountLogData['booking_ref_no'] = $booking_id;
                    $WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);


                    $WebPatnerAccountLogData['balance'] = round_value(($webPartnerBalance + $refundAmount));


                    $added_data_id = $AmendmentModel->insertData('web_partner_account_log', $WebPatnerAccountLogData);
                    $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
                    $AmendmentModel->updateWithTableData("web_partner_account_log", $WebPatnerAccountLogDataUpdate, array("id" => $added_data_id));

                    $AmendmentModel->updateWithTableData("cruise_booking_list", array("refund_account_id" => $added_data_id), array("id" => $booking_id));


                    $updateData['refund_status'] = "Close";
                    $updateData['account_remark'] = $data['account_remark'];
                    $updateData['sup_staff_id'] = $this->user_id;
                    $updateData['modified'] = create_date();
                    $updateData['refund_close_date'] = create_date();

                    $update = $AmendmentModel->updateData($updateData, array("id" => $amendment_id));
                    if ($update) {
                        $message = array("StatusCode" => 0, "Message" => "Refund  has been successfully done", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Refund  has not been successfully done", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function get_invoice_ticket()
    {

        $CruiseModel = new CruiseModel();

        if (!$this->request->isAJAX()) {
            $getData = $this->request->getGet();
            $bookingRefNumbers = $getData['booking_ref_number'];
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            if ($bookingRefNumbers) {

                $bookingInfoData = $CruiseModel->getData('cruise_booking_list', ['booking_ref_number' => $bookingRefNumbers], "id,tts_search_token");
                $bookingInfo = $bookingInfoData;
                $bookingInfoId = $bookingInfoData['id'];
                $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";

                if ($bookingRefNumbers && $bookingInfo) {

                    if ($getData['type'] == "PrintTicket") {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "Admin",
                            "ViewService" => "View",
                            "WithPrice" => isset($getData['price']) ? 1 : 0,
                            "WithAgencyDetail" => isset($getData['agency_detail']) ? 1 : 0,
                            "ViewSize" => "",
                        );
                    } else {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "Admin",
                            "ViewService" => "View",
                            "WithPrice" => 1,
                            "WithAgencyDetail" => 1,
                            "ViewSize" => "",
                        );
                    }
                    // echo json_encode($TicketViewRequest);die;
                    $url = $this->Services . 'generate-voucher-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    if (isset($response['Result']['Html'])) {
                        $Html = $response['Result']['Html'];
                    } else {
                        $Html = "Something went wrong please call admin";
                    }
                    $data = [
                        'title' => $this->title,
                        'view' => "Cruise\Views\booking\print_ticket",
                        'data' => $Html,
                    ];
                    return view('template/sidebar-layout', $data);
                } else {
                    return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
            }
        } else {
            $validate = new Validation();
            $rules = $this->validate($validate->EmailTicketValidation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $getData = $this->request->getPost();
                $bookingRefNumbers = $getData['booking_ref_number'];
                if ($bookingRefNumbers) {

                    $bookingInfoData = $CruiseModel->getData('cruise_booking_list', ['booking_ref_number' => $bookingRefNumbers],  "id,tts_search_token");
                    $bookingInfo = $bookingInfoData;
                    $bookingInfoId = $bookingInfoData['id'];
                    $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";

                    if ($bookingRefNumbers && $bookingInfo) {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => "Ticket",
                            "UserType" => "Admin",
                            "ViewService" => "Email",
                            "WithPrice" => "1",
                            "WithAgencyDetail" => "0",
                            "TicketInvoiceJourney" => "Both",
                            "ViewSize" => "",
                        );
                        $url = $this->Services . 'generate-voucher-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);

                        $htmlView = $response['Result']['Html'];
                        $subject = "Cruise Voucher";
                        $to = $getData['email'];
                        $data = send_email($to, $subject, $htmlView);
                        $message = array("StatusCode" => 0, "Message" => "Email Successfully Send", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                } else {
                    return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
                }
            }
        }
    }

    function getCreditNote()
    {
        $CruiseModel = new CruiseModel();
        $getData = $this->request->getPOST();
        if (!$this->request->isAJAX()) {
            $getVoucherInvioceType = array("PrintVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $CruiseModel->getData("cruise_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {
                    $TicketViewRequest = array(
                        "BookingId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => "Ticket",
                        "UserType" => "WebPartner",
                        "ViewService" => "View",
                        "WithAgencyDetail" => "1",
                        "ViewSize" => "",
                    );
                    $url = $this->Services . 'generate-credit-note';

                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    // echo $url; die;
                    $data = [
                        'title' => $this->title,
                        'view' => "Cruise\Views\booking\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/sidebar-layout', $data);
                } else {
                    return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('cruise/error?errormessage=Request Not Allowed'));
            }
        }
    }

    public function cruise_markup_list()
    {
        if (permission_access_error("Cruise", "cruise_markup_list")) {
            $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $WebPartnerCruiseMarkupModel->search_data($this->request->getGet(),$this->web_partner_id);
            } else {
                $lists = $WebPartnerCruiseMarkupModel->cruise_markup_list($this->web_partner_id);
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id'); 
             
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "Cruise\Views\MarkupAndDiscount\cruise-markup-list",
                'pager' => $WebPartnerCruiseMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function cruise_markup_view()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
                'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
                'agent_class' => $agent_class_list
            ];
            $add_blog_view = view('Modules\Cruise\Views\MarkupAndDiscount\add-cruise-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_markup()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if($data['markup_for']== "B2C"){
                unset($validate->cruise_markup_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->cruise_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
              
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                $added_data = $WebPartnerCruiseMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_cruise_markup_template()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();
            $AgentClassModel = new AgentClassModel();

            $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();
            $details = $WebPartnerCruiseMarkupModel->web_partner_cruise_markup_details($id,$this->web_partner_id);
          

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select_cruise_line($details['cruise_line_id']);
           
            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select_ship_id($details['cruise_ship_id'],$this->web_partner_id);
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $details['agent_class'] = explode(',', $details['agent_class']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_ship' => $cruise_ship,
                'cruise_cabin' => $cruise_cabin,
                'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
                'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
                'agent_class_list' => $agent_class_list
            ];

            $details = view('Modules\Cruise\Views\MarkupAndDiscount\edit-cruise-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_cruise_markup()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
            $data = $this->request->getPost();
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            if($data['markup_for']== "B2C"){
                unset($validate->cruise_markup_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->cruise_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();
               
                $data['modified'] = create_date();
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
             
                $added_data = $WebPartnerCruiseMarkupModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }

    }

    public function cruise_markup_status_change()
    {
        if (permission_access_error("Cruise", "cruise_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();

                $update = $WebPartnerCruiseMarkupModel->status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_markup()
    {
        if (permission_access_error("Cruise", "delete_cruise_markup")) {
            $WebPartnerCruiseMarkupModel = new WebPartnerCruiseMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $WebPartnerCruiseMarkupModel->remove_markup($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    
    public function cruise_discount_list()
    {
        if (permission_access_error("Cruise", "cruise_discount_list")) {
            $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $WebPartnerCruiseDiscountModel->search_data($this->request->getGet(),$this->web_partner_id);
            } else {
                $lists = $WebPartnerCruiseDiscountModel->cruise_discount_list($this->web_partner_id);
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id'); 
             
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "Cruise\Views\MarkupAndDiscount\cruise-discount-list",
                'pager' => $WebPartnerCruiseDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }
    

    public function cruise_discount_view()
    { 
        if (permission_access_error("Cruise", "add_cruise_discount")) {
            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
                'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
                'agent_class' => $agent_class_list
            ];
            $add_blog_view = view('Modules\Cruise\Views\MarkupAndDiscount\add-cruise-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }
    
    public function add_cruise_discount()
    {
        if (permission_access_error("Cruise", "add_cruise_discount")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if($data['discount_for']== "B2C"){
                unset($validate->cruise_discount_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->cruise_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
              
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                $added_data = $WebPartnerCruiseDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    
    public function edit_cruise_discount_template()
    {
        if (permission_access_error("Cruise", "edit_cruise_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();
            $AgentClassModel = new AgentClassModel();

            $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();
            $details = $WebPartnerCruiseDiscountModel->web_partner_cruise_discount_details($id,$this->web_partner_id);
          

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select_cruise_line($details['cruise_line_id']);
           
            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select_ship_id($details['cruise_ship_id'],$this->web_partner_id);
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $details['agent_class'] = explode(',', $details['agent_class']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_ship' => $cruise_ship,
                'cruise_cabin' => $cruise_cabin,
                'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
                'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
                'agent_class_list' => $agent_class_list
            ];

            $details = view('Modules\Cruise\Views\MarkupAndDiscount\edit-cruise-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_cruise_discount()
    {
        if (permission_access_error("Cruise", "edit_cruise_discount")) {
            $data = $this->request->getPost();
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            if($data['discount_for']== "B2C"){
                unset($validate->cruise_discount_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->cruise_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();
               
                $data['modified'] = create_date();
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
             
                $added_data = $WebPartnerCruiseDiscountModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }

    }


    public function cruise_discount_status_change()
    {
        if (permission_access_error("Cruise", "cruise_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();

                $update = $WebPartnerCruiseDiscountModel->status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }
    

    public function remove_cruise_discount()
    {
        if (permission_access_error("Cruise", "delete_cruise_discount")) {
            $WebPartnerCruiseDiscountModel = new WebPartnerCruiseDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $WebPartnerCruiseDiscountModel->remove_discount($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
    
    
    
    public function export_amendments()
    {

        $rules = $this->validate([

            'from_date' => [
                'label' => 'From Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select from date.'
                ]
            ],
            'to_date' => [
                'label' => 'To Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select to date.'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            $get_data = $this->request->getPost();


            $AmendmentModel = new AmendmentModel();
            $BookingDetail = $AmendmentModel->search_bookings($get_data, $this->web_partner_id);

            $fileName = 'cruise-amendments-report' . "." . 'xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:M1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
            $sheet->getStyle("A:M")->getFont()->setName('Arial')->setSize(11);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);


            $sheet->setCellValue('A1', 'Ref. No.');
            $sheet->setCellValue('B1', 'Amendment Id');
            $sheet->setCellValue('C1', 'Amendment Type');
            $sheet->setCellValue('D1', 'Amendment Status');
            $sheet->setCellValue('E1', 'Cruise Line');
            $sheet->setCellValue('F1', 'Ship Name');
            $sheet->setCellValue('G1', 'Travel Date');


            $sheet->setCellValue('H1', 'Booking Status');
            $sheet->setCellValue('I1', 'Remark');

            $sheet->setCellValue('J1', 'Generate By');

            $sheet->setCellValue('K1', 'Web Partner');
            $sheet->setCellValue('L1', 'Admin Staff');
            $sheet->setCellValue('M1', 'Created');

            $rows = 2;

            foreach ($BookingDetail as $key => $data) {

                $sheet->setCellValue('A' . $rows, $data['booking_ref_number']);
                $sheet->setCellValue('B' . $rows, $data['id']);
                $sheet->setCellValue('C' . $rows, ucfirst($data['amendment_type']));
                $sheet->setCellValue('D' . $rows, ucfirst($data['amendment_status']));
                $sheet->setCellValue('E' . $rows, ucwords($data['cruise_line_name']));
                $sheet->setCellValue('F' . $rows, ucwords($data['ship_name']));
                $sheet->setCellValue('G' . $rows, ($data['sailing_date']));


                $sheet->setCellValue('H' . $rows, ucfirst($data['booking_status']));
                $sheet->setCellValue('I' . $rows, $data['remark_from_web_partner']);

                $sheet->setCellValue('J' . $rows, $data['staff_name']);

                $sheet->setCellValue('K' . $rows, ucfirst($data['company_name']) . '(' . $data['company_id'] . ')');
                $sheet->setCellValue('L' . $rows, $data['super_admin_staff_name']);
                $sheet->setCellValue('M' . $rows, date_created_format($data['created']));
                $rows++;
            }

            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);

            return $this->response->setJSON($data_validation);
        }
    }
}

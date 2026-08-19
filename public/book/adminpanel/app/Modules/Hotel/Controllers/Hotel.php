<?php

namespace Modules\Hotel\Controllers;

use App\Modules\Hotel\Models\HotelCitiesModel;
use App\Modules\Hotel\Models\HotelModel;
use App\Modules\Hotel\Models\HotelBookingModel;
use App\Modules\Hotel\Models\HotelAmendmentModel;
use App\Modules\Hotel\Models\HotelDiscountModel;
use App\Modules\Hotel\Models\HotelMarkupModel;
use App\Modules\Hotel\Models\AgentClassModel;
use App\Models\CommonModel;
use App\Controllers\BaseController;
use Modules\Hotel\Config\Validation;


class Hotel extends BaseController
{   
    protected $title;
    protected $web_partner_id;
    protected $user_id;
    protected $web_partner_details;
    protected $admin_comapny_detail;
    protected $Services;
    protected $folder_name;
    protected $superAdminAccess;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Hotel";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->folder_name = 'Hotel';
        $this->Services = API_REQUEST_URL . '/hotelservice/rest/';
        helper('Modules\Hotel\Helpers\hotel');
        ini_set("memory_limit", "1024M");
        $this->superAdminAccess = 0;
        if (isset(session()->get('admin_user')['accessBySuperAdmin']) && session()->get('admin_user')['accessBySuperAdmin']) {
            $this->superAdminAccess = 1;
        }
    }


    public function city_list()
    {
        $terms = $this->request->getGet('term');
        $HotelCitiesModel = new HotelCitiesModel();
        echo $HotelCitiesModel->cities_list($terms);
    }



    public function index()
    {
        return redirect()->to(site_url('/dashboard'));
    }


    public function hotel_result()
    {
        $request = $this->request->getGET();
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\booking\hotel_result",
            'searchData' => $request,
        ];
        return view('template/default-layout', $data);
    }



    function blockHotel()
    {
        $blockhotelInfo = $this->request->getPOST();
        $HotelModel = new HotelModel();
        $insertData['hotel_code'] = trim($blockhotelInfo['HotelCode']);
        $insertData['hotel_name'] = $blockhotelInfo['HotelName'];
        $insertData['city_id'] = trim($blockhotelInfo['CityId']);
        $insertData['city_name'] = $blockhotelInfo['CityName'];
        $insertData['created'] = create_date();
        $blockId = $HotelModel->insertData('hotel_block_list', $insertData);
        $response = array("StatusCode" => 1, "ErrorMessage" => "Error Occured");
        if ($blockId) {
            $response = array("StatusCode" => 0, "ErrorMessage" => "");
        }
        return $this->response->setJSON($response);
    }
    function error()
    {
        $error = $this->request->getGET('errormessage');
        return view('template/custom-error-layout', ['error_message' => $error]);
    }


    function getVoucherInvoice()
    {
        $HotelModel = new HotelModel();
        $getData = $this->request->getPOST();
        if (!$this->request->isAJAX()) {
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {
                    $HtmlType = ($bookingInfo['booking_source'] == "Wl_b2b") ? 'AgencyInvoice' : 'CustomerInvoice';
                    $UserType = ($bookingInfo['booking_source'] == "Wl_b2b") ? 'wl-agent' : 'wl-customer';
                    if (whitelabel['is_direct_website'] == "inactive") {
                        $getVoucherInvioceType = array("PrintVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
                    } else {
                        $getVoucherInvioceType = array("PrintVoucher" => "Voucher", "CustomerInvoice" => $HtmlType);
                    }
                    $TicketViewRequest = array(
                        "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => $getVoucherInvioceType[$getData['type']],
                        "UserType" => $UserType,
                        "ViewService" => "View",
                        "WithAgencyDetail" => (isset($getData['agency_detail']) && $getData['agency_detail'] == 1) ? 0 : 1,
                        "ViewSize" => "",
                        "RequestBy" => 'WebPartner',
                    );

                    $url = $this->Services . 'generate-wl-voucher-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Hotel\Views\booking\print_voucher",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/sidebar-layout', $data);
                } else {
                    return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
            }
        } else {
            /* special ajax here */
            $getData = $this->request->getPOST();
            $validate = new Validation();
            $rules = $this->validate($validate->EmailVoucherValidation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $getData = $this->request->getPost();
                $bookingRefNumber = $getData['booking_ref_number'];
                $bookingInfo = array();
                if ($bookingRefNumber) {
                    $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                    $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");

                    if ($bookingInfo) {
                        $HtmlType = ($bookingInfo['booking_source'] == "Wl_b2b") ? 'AgencyInvoice' : 'CustomerInvoice';
                        $UserType = ($bookingInfo['booking_source'] == "Wl_b2b") ? 'wl-agent' : 'wl-customer';
                        if (whitelabel['is_direct_website'] == "inactive") {
                            $getVoucherInvioceType = array("EmailVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
                        } else {
                            $getVoucherInvioceType = array("EmailVoucher" => "Voucher", "CustomerInvoice" => $HtmlType);
                        }
                        $TicketViewRequest = array(
                            "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                            "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                            "HtmlType" => $getVoucherInvioceType[$getData['type']],
                            "UserType" => $UserType,
                            "ViewService" => "Email",
                            "WithAgencyDetail" => "1",
                            "ViewSize" => "",
                            "RequestBy" => 'WebPartner',
                        );
                        $url = $this->Services . 'generate-wl-voucher-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);
                        $htmlView = $response['Result']['Html'];
                        $subject = "Hotel Voucher";
                        $to = $getData['email'];
                        $data = send_email($to, $subject, $htmlView);
                        $message = array("StatusCode" => 0, "Message" => "Email Successfully Send", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    function bookingLists()
    {
        $HotelBookingModel = new HotelBookingModel();
        $bookingType = 'all';
        $source = '';

        if ($this->request->getGet() && $this->request->getGet('key')) {

            $list = $HotelBookingModel->search_bookings($this->request->getGet(), $this->web_partner_id,);
        } else {
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            if (isset($_GET['bookingtype'])) {
                $bookingType = $this->request->getGET('bookingtype');
            }
            $list = $HotelBookingModel->hotel_booking_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
        }
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-booking-list",
            "list" => $list,
            'pager' => $HotelBookingModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    function AssignUpdateHotelTicket()
    {


        $uri = $this->request->getUri();
        $bookingReferenceNumber =  ($uri->getSegment(3));


        $HotelBookingModel = new HotelBookingModel();
        $BookingDetail = $HotelBookingModel->hotel_booking_detail($this->web_partner_id, $bookingReferenceNumber);
        if ($BookingDetail) {
            $checkbookingflighttime = checkbookingflighttime($BookingDetail['created'], 'Hotel');
            if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->redirect($this->request->getUserAgent()->getReferrer());
            }
            $updateData['webpartner_assign_user'] = $this->user_id;
            $HotelBookingModel->updateData("hotel_booking_list", array("booking_ref_number" => $bookingReferenceNumber), $updateData);
            $message = array("StatusCode" => 0, "Message" => "Voucher assign successfully", "Class" => "success_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->redirect($this->request->getUserAgent()->getReferrer());
        } else {
            return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
        }
    }

    function getUpdatehotelVoucherInfo()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $HotelBookingModel = new HotelBookingModel();
        $BookingDetail = $HotelBookingModel->hotel_booking_detail($this->web_partner_id, $bookingReferenceNumber);
        $amendment_list = $HotelBookingModel->amendment_list($this->web_partner_id, $BookingDetail['id']);
        $publishedFare = 0;
        $offeredFare = 0;
        $CommEarned = 0;
        $TDS = 0;
        $ApplyagentMarkup = 0;
        $ApplyagentDiscount = 0;
        $ApplyDiscount = 0;
        $ApplyMarkup = 0;
        $CGSTAmount = 0;
        $IGSTAmount = 0;
        $SGSTAmount = 0;
        $TaxableAmount = 0;
        if ($BookingDetail) {
            // pr($BookingDetail);exit;

            $web_partner_fare_break_up = json_decode($BookingDetail['web_partner_fare_break_up'], true);
            if (isset($BookingDetail['wl_agent_id']) && $BookingDetail['wl_agent_id'] > 0) {
                $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
            } else {
                $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
            }

            $SupplierFareBreakUp = json_decode($BookingDetail['supplier_fare_break_up'], true);
            $FareBreakUpSupplir = [];
            if(!empty($SupplierFareBreakUp) && $SupplierFareBreakUp !==NULL ){
 
                $PFare = 0;
                $OFare = 0;
                $CE = 0;
                $TDSs = 0;
                $ADiscount = 0;
                $AMarkup = 0;
                $CGSTAt = 0;
                $IGSTAt = 0;
                $SGSTAt = 0;
                $TaxableAt = 0;
                $cAmount = 0; 
                $SUPP_DisplayMarkup = ''; 

                foreach ($SupplierFareBreakUp as $key => $SUpplierHotelRooms) {
                    if (isset($SUpplierHotelRooms['GST'])) {
                        $GST = $SUpplierHotelRooms['GST'];
                        $GSTDATA['CGSTAmount'] = $CGSTAt + $GST['CGSTAmount'];
                        $GSTDATA['IGSTAmount'] = $SGSTAt + $GST['IGSTAmount'];
                        $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                        $GSTDATA['TaxableAmount'] = $TaxableAt + $GST['TaxableAmount'];
                    } 
                    if (isset($SUpplierHotelRooms['PublishedPrice'])) {
                        $PFare = $PFare + $SUpplierHotelRooms['PublishedPrice'];
                    }
                    if (isset($SUpplierHotelRooms['OfferedPrice'])) {
                        $OFare = $OFare + $SUpplierHotelRooms['OfferedPrice'];
                    }
                    if (isset($SUpplierHotelRooms['AgentCommission']) && isset($SUpplierHotelRooms['Discount'])) {
                        $CE = $CE + $SUpplierHotelRooms['AgentCommission'] + $SUpplierHotelRooms['Discount'];
                    }
                    if (isset($SUpplierHotelRooms['SUPP_Markup']) && isset($SUpplierHotelRooms['SUPP_Markup'])) {
                        $AMarkup = $AMarkup + $SUpplierHotelRooms['SUPP_Markup'] + $SUpplierHotelRooms['SUPP_Markup'];
                    }
                    if (isset($SUpplierHotelRooms['SUPP_Discount']) && isset($SUpplierHotelRooms['SUPP_Discount'])) {
                        $ADiscount = $AMarkup + $SUpplierHotelRooms['SUPP_Discount'] + $SUpplierHotelRooms['SUPP_Discount'];
                    }
                    $SUPP_DisplayMarkup = isset($SUpplierHotelRooms['SUPP_DisplayMarkup']) ? $SUpplierHotelRooms['SUPP_DisplayMarkup'] : 'in_tax';
                     
                }

                $FareBreakUpSupplir = array(
                    "FareBreakup" => array(
                        "PublishedPrice" => array("Value" => custom_money_format(round_value($PFare)), "LabelText" => "Published Price"),
                        /* "CommEarned" => array("Value" => custom_money_format(round_value($CE)), "LabelText" => "Comm Earned (-)"),
                        "TDS" => array("Value" => custom_money_format(round_value($TDSs)), "LabelText" => "TDS (+)") */
                    ),
                    "TotalAmount" => array("Value" => custom_money_format(round_value($OFare + $TDSs - $cAmount)), "LabelText" => "Total Amount"),
                    "GSTDetails" => $GSTDATA,
                    "WebPMarkUp" => array("Value" => custom_money_format(round_value($AMarkup)), "LabelText" => "Apply Mark Up"),
                    "WebPDiscount" => array("Value" => custom_money_format(round_value($ADiscount)), "LabelText" => "Apply Discount"), 
                    "ApplyDisPlayMarkup" => array("Value" => $SUPP_DisplayMarkup, "LabelText" => "Apply DisPlay Markup"),
                );
                
            }
            $conveniencefee = isset($BookingDetail['conveniencefee']) ? $BookingDetail['conveniencefee'] : 0;

            $couponAmount = isset($fareBreakupArray['couponAmount']) ? $fareBreakupArray['couponAmount'] : 0;
            unset($fareBreakupArray['couponAmount']);
            $GSTDATA = $fareBreakupArray[0]['GST'];
            foreach ($fareBreakupArray as $key => $HotelRooms) {
                $GST = $HotelRooms['GST'];
                $GSTDATA['CGSTAmount'] = $CGSTAmount + $GST['CGSTAmount'];
                $GSTDATA['IGSTAmount'] = $IGSTAmount + $GST['IGSTAmount'];
                $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                $super_admin_fare_break_up = $web_partner_fare_break_up[$key];
                $markup = isset($super_admin_fare_break_up['WebPMarkUp']) ? $super_admin_fare_break_up['WebPMarkUp'] : 0;
                $discount = isset($super_admin_fare_break_up['WebPDiscount']) ? $super_admin_fare_break_up['WebPDiscount'] : 0;
                $ApplyDisPlayMarkup = isset($super_admin_fare_break_up['WebPDisplayMarkup']) ? $super_admin_fare_break_up['WebPDisplayMarkup'] : 'in_tax';
                $ApplyMarkup = $ApplyMarkup + $markup;
                $ApplyDiscount = $ApplyDiscount + $discount;

                $ApplyagentsMarkup = isset($HotelRooms['AgentMarkUp']) ? $HotelRooms['AgentMarkUp'] : 0;
                $Applyagentsdesc = isset($HotelRooms['AgentWebPDiscount']) ? $HotelRooms['AgentWebPDiscount'] : 0;

                $ApplyagentMarkup = $ApplyagentMarkup +$ApplyagentsMarkup;
                $ApplyagentDiscount = $ApplyagentDiscount +$Applyagentsdesc;

                $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                $TDS = $TDS + $HotelRooms['TDS'];
            }
        }
        $FareBreakUp = array(
            "FareBreakup" => array(
                "PublishedPrice" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Published Price"),
                "CommEarned" => array("Value" => custom_money_format(round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)"),
            
                "conveniencefee" => array("Value" => custom_money_format(round_value($conveniencefee)), "LabelText" => "Convenience fee (+)")
            ),
            "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $TDS - $couponAmount +$conveniencefee)), "LabelText" => "Total Amount"),
            "GSTDetails" => $GSTDATA,
            "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
            "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
            "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
        );
        if($ApplyagentMarkup)
        {
            $FareBreakUp['AgentMarkUp'] = array("Value" => custom_money_format(round_value($ApplyagentMarkup)), "LabelText" => "Apply Agent Mark Up");
        }
        if($ApplyagentDiscount){
            $FareBreakUp['AgentDiscount'] = array("Value" => custom_money_format(round_value($ApplyagentDiscount)), "LabelText" => "Apply Agent Discount");
        }
        if ($couponAmount > 0) {
            $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => custom_money_format(round_value($couponAmount)), "LabelText" => "Promocode Discount (-)");
        }
        $hotelSupplier = $HotelBookingModel->getData("offline_provider", array('hotel_service' => 'active'), $singalRecord = 0, $whereApply = 1, 'id,supplier_name');


        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-booking-detail",
            "bookingDetail" => $BookingDetail,
            "amendment_list" => $amendment_list,
            "hotelSupplier" => $hotelSupplier,
            "FareBreakUp" => $FareBreakUp,
            "FareBreakUpSupplir" => $FareBreakUpSupplir,
            "UpdateVoucher" => 1,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function UpdateHotelVoucherInfo()
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
            $HotelBookingModel = new HotelBookingModel();
            $booking_refrence_number = dev_decode($input['booking_ref_number']);
            $hotel_booking_id = dev_decode($input['hotel_booking_id']);
            $bookingInfo = $HotelBookingModel->hotel_booking_detail($this->web_partner_id, $booking_refrence_number);




            if ($bookingInfo && (isset($bookingInfo['id']) && ($bookingInfo['id'] == $hotel_booking_id))) {

                $checkbookingflighttime = checkbookingflighttime($bookingInfo['created'], "Hotel");
                if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                    $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    $RedirectUrl = site_url('hotel/bookings');
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                }
                if ($bookingInfo['wl_agent_id'] > 0) {
                    $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
                    $account_log_id = $bookingInfo['wl_agent_id'];
                    $AccountTableName = "agent_account_log";
                    $key = "wl_agent_id";
                    $user = "Agent";
                } else {
                    $fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
                    $account_log_id = $bookingInfo['wl_customer_id'];
                    $AccountTableName = "customer_account_log";
                    $key = "customer_id";
                    $user = "Customer";
                }




                /* ---------------------------------------------------- */
                $session = session();
                $currencyData = $session->get('currencyinfo');
                if (isset($currencyData[$bookingInfo["booking_currency"]])) {
                    $CurrencySymbol = $currencyData[$bookingInfo["booking_currency"]]['currency_symbol'];
                } else {
                    $CurrencySymbol = '₹';
                }
                /* ---------------------------------------------------- */




                if ($bookingInfo['webpartner_assign_user'] == $this->user_id || admin_cookie_data()['admin_user_details']['primary_user'] == 1) {
                    if (isset($input['refundbookingamount']) && $input['refundbookingamount'] == "yes") {
                        if (((isset($bookingInfo['booking_status']) && $bookingInfo['booking_status'] == 'Failed') || $input['booking_status'] == "Failed") && (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == 'Successful')) {
                            $hotelBookingAccountinfo = $HotelBookingModel->getData($AccountTableName, array("booking_ref_no" => $bookingInfo['id'], 'service' => "hotel", "action_type" => "booking", 'transaction_type' => "debit", $key => $account_log_id, 'web_partner_id' => $bookingInfo['web_partner_id']), $singalRecord = 1, $whereApply = 1);
                            $checkflighBookingRefund = $HotelBookingModel->getData($AccountTableName, array("booking_ref_no" => $bookingInfo['id'], 'service' => "hotel", "action_type" => "bookingrefund", 'transaction_type' => "credit", $key => $account_log_id, 'web_partner_id' => $bookingInfo['web_partner_id']), $singalRecord = 1, $whereApply = 1);
                            if (empty($checkflighBookingRefund)) {
                                if (!empty($hotelBookingAccountinfo) && $hotelBookingAccountinfo) {
                                    $serviceLog = json_decode($hotelBookingAccountinfo['service_log'], true);
                                    $extraParam = json_decode($hotelBookingAccountinfo['extra_param'], true);
                                    if (empty($serviceLog)) {
                                        $serviceLog = array();
                                    }
                                    $serviceLog['BookingRefrenceNumber'] = $booking_refrence_number;
                                    $web_partner_id = $hotelBookingAccountinfo['web_partner_id'];
                                    $topupAmount = round_value(($hotelBookingAccountinfo['debit']));
                                    $WebPartnerAccountLogData['web_partner_id'] = $this->web_partner_id;
                                    $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                    $WebPartnerAccountLogData['created'] = create_date();
                                    $WebPartnerAccountLogData['transaction_type'] = "credit";
                                    $WebPartnerAccountLogData['action_type'] = "bookingrefund";
                                    $WebPartnerAccountLogData['role'] = 'web_partner';
                                    $WebPartnerAccountLogData['payment_mode'] = 'Wallet';
                                    $WebPartnerAccountLogData[$key] = $account_log_id;
                                    $WebPartnerAccountLogData['remark'] = $input['remark'];
                                    $WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);
                                    $WebPartnerAccountLogData['extra_param'] = json_encode($extraParam);
                                    $WebPartnerAccountLogData['service'] = "hotel";
                                    $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                    $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);

                                    $WebPartnerAccountLogData['convertion_rate'] = isset($bookingInfo["currency_rate"]) ? $bookingInfo["currency_rate"] : 1;
                                    $WebPartnerAccountLogData['currency'] = isset($bookingInfo["booking_currency"]) ? $bookingInfo["booking_currency"] : "INR";
                                    $WebPartnerAccountLogData['currency_symbol'] = $CurrencySymbol;

                                    $available_balance = $HotelBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                    if (!$available_balance) {
                                        $available_balance['balance'] = 0;
                                    }
                                    $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));
                                    $WebPartnerAccountLogData['credit'] = $topupAmount;
                                    $added_data_id = $HotelBookingModel->InsertData($AccountTableName, $WebPartnerAccountLogData);
                                    $updateData['acc_ref_number'] = reference_number($added_data_id);
                                    $HotelBookingModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);
                                    $RedirectUrl = site_url('hotel/bookings');
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            } else {
                                $message = array("StatusCode" => 2, "Message" => "Refund for this booking has been done already", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);
                                $RedirectUrl = site_url('hotel/bookings');
                                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                return $this->response->setJSON($data_validation);
                            }
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                            $this->session->setFlashdata('Message', $message);
                            $RedirectUrl = site_url('hotel/bookings');
                            $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                            return $this->response->setJSON($data_validation);
                        }
                    }
                    if ($input['booking_status'] == "Confirmed") {
                        $hotelBookingAccountinfo = $HotelBookingModel->getData($AccountTableName, array("booking_ref_no" => $bookingInfo['id'], 'service' => "hotel", "action_type" => "booking", 'transaction_type' => "debit", $key => $account_log_id, 'web_partner_id' => $bookingInfo['web_partner_id']), $singalRecord = 1, $whereApply = 1);
                        if (empty($hotelBookingAccountinfo)) {
                            if (isset($input['deductbookingamount']) && $input['deductbookingamount'] == "yes") {
                                $web_partner_id = $bookingInfo['web_partner_id'];
                                $room_guests = json_decode($bookingInfo['room_guests'], true);
                                $totalPax = 0;
                                foreach ($room_guests as $guests) {
                                    $totalPax = $totalPax + $guests['Adult'] + $guests['Child'];
                                }
                                $available_balance = $HotelBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                $serviceLog['PaxName'] = $bookingInfo['lead_passenger_name'] . " X " . $totalPax;
                                $serviceLog['City'] = $bookingInfo['city'];
                                $serviceLog['CheckInDate'] = $bookingInfo['check_in_date'];
                                $serviceLog['CheckOutDate'] = $bookingInfo['check_in_date'];

                                $extraParam['booking_ref_number'] = $bookingInfo['booking_ref_number'];
                                $extraParam['webPartnerBreakUpInfo'] = $bookingInfo['web_partner_fare_break_up'];
                                if ($key == "wl_agent_id") {
                                    $extraParam['agentBreakUpInfo'] = $bookingInfo['agent_fare_break_up'];
                                } else {
                                    $extraParam['customerBreakUpInfo'] = $bookingInfo['customer_fare_break_up'];
                                }


                                $bookingInfoTotalPrice =     booking_currency_refunds($bookingInfo['total_price'], $bookingInfo["booking_currency"], $bookingInfo["currency_rate"]);

                                if (isset($available_balance['balance']) && $available_balance['balance'] > $bookingInfoTotalPrice) {
                                    $debitAmount = round_value(($bookingInfo['total_price']));
                                    $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                                    $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                    $WebPartnerAccountLogData['created'] = create_date();
                                    $WebPartnerAccountLogData['transaction_type'] = "debit";
                                    $WebPartnerAccountLogData['action_type'] = "booking";
                                    $WebPartnerAccountLogData['payment_mode'] = 'Wallet';
                                    $WebPartnerAccountLogData['role'] = 'web_partner';
                                    $WebPartnerAccountLogData['remark'] = $input['remark'];
                                    $WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);
                                    $WebPartnerAccountLogData['extra_param'] = json_encode($extraParam);
                                    $WebPartnerAccountLogData['service'] = "hotel";
                                    $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                    $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                                    $available_balance = $HotelBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                    if (!$available_balance) {
                                        $available_balance['balance'] = 0;
                                    }


                                    $debitAmountConvert =     booking_currency_refunds($debitAmount, $bookingInfo["booking_currency"], $bookingInfo["currency_rate"]);

                                    if ($bookingInfo["booking_currency"] == "INR" || $bookingInfo["booking_currency"] == NULL) {
                                        $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] - $debitAmountConvert));
                                        $WebPartnerAccountLogData['debit'] = $debitAmountConvert;
                                    } else {
                                        $WebPartnerAccountLogData['balance'] = ($available_balance['balance'] - $debitAmountConvert);
                                        $WebPartnerAccountLogData['debit'] = $debitAmountConvert;
                                    }



                                    /*  $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] - $debitAmount));
                                    $WebPartnerAccountLogData['debit'] = $debitAmount; */
                                    $added_data_id = $HotelBookingModel->InsertData($AccountTableName, $WebPartnerAccountLogData);
                                    $updateData['acc_ref_number'] = reference_number($added_data_id, "hotel", $bookingInfo['is_domestic']);
                                    $HotelBookingModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);
                                    $input['payment_status'] = "Successful";
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "$user  have not enough balance", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);
                                    $RedirectUrl = site_url('/hotel/get-update-hotel-voucher-info/' . $booking_refrence_number);
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            } else {
                                $message = array("StatusCode" => 2, "Message" => "Payment  have not done for this booking", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);
                                $RedirectUrl = site_url('/hotel/get-update-hotel-voucher-info/' . $booking_refrence_number);
                                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                return $this->response->setJSON($data_validation);
                            }
                        }
                    }
                    $saveNoteData = array(
                        "booking_ref_no" => $hotel_booking_id,
                        'agent_staff_id' => $this->user_id,
                        'web_partner_id' => $this->web_partner_id,
                        'service_type' => "hotel",
                        'add_by' => "weppartner",
                        'comment' => $input['remark'],
                        'created' => create_date()
                    );
                    if ($key == "wl_agent_id") {
                        $saveNoteData['wl_agent_staff_id'] = $bookingInfo['wl_agent_staff_id'];
                    } else {
                        $saveNoteData['wl_customer_id'] = $bookingInfo['wl_customer_id'];
                    }
                    $saveNoteDataId = $HotelBookingModel->insertData('web_partner_booking_notes', $saveNoteData);
                    if ($input['booking_status'] == 'Confirmed' && $bookingInfo['booking_status'] != 'Confirmed') {
                        $InvoiceNumber = "";
                        /* invoice  Number Generate Number */
                        $CommonModel = new CommonModel();
                        $WebPartnerfareBreakup = $fareBreakupArray;
                        // $WebpartnerGSTInfo  =  $CommonModel->getDataRowType("web_partner", array("id" => $bookingInfo['web_partner_id']), "company_gst_no");
                        $checkTaxableInvoce = checkTaxableNonTaxableINV($WebPartnerfareBreakup, "", 'hotel', 'INV');
                        $INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'hotel');
                        $financialYear = get_financial_year();
                        $whereCondition['web_partner_id'] = $this->web_partner_id;
                        $whereCondition['service'] = 'hotel';
                        $whereCondition['invoice_type'] = 'INV';
                        $whereCondition['financial_year'] = $financialYear;
                        $otherParameter['financialYear'] = $financialYear;
                        $otherParameter['service'] = 'hotel';
                        $otherParameter['invoice_type'] = 'INV';
                        $otherParameter['INVPrifix'] = $INVPrifix;
                        $otherParameter['web_partner_id'] = $this->web_partner_id;
                        $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
                        $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                        $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                        $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                        $InvoiceupdateData = $InvoiceInfoData['updateData'];
                        $HotelBookingModel->updateData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                        $HotelBookingModel->updateData($AccountTableName, ['booking_ref_no' => $hotel_booking_id, "service" => "hotel", 'transaction_type' => "debit", 'action_type' => "booking", 'web_partner_id' => $this->web_partner_id, $key => $account_log_id], ["invoice_number" => $InvoiceNumber]);
                    }
                    $superAdminStaffDetail = admin_cookie_data()['admin_user_details'];
                    $updateFlightBookingData = array(
                        "booking_status" => $input['booking_status'],
                        "payment_status" => $input['payment_status'],
                        "issue_supplier" => $input['supplier'],
                        "confirmation_no" => $input['confirmation_number'],
                        "webpartner_assign_user" => null,
                        'is_manual' => 1,
                        "webpartner_update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),

                    );
                    $HotelBookingModel->updateData("hotel_booking_list", array("id" => $hotel_booking_id), $updateFlightBookingData);
                    $message = array("StatusCode" => 1, "Message" => "Voucher Update successfully", "Class" => "success_popup");
                    $this->session->setFlashdata('Message', $message);
                    $RedirectUrl = site_url('hotel/details/' . $booking_refrence_number);
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                } else {
                    $message = array("StatusCode" => 2, "Message" => "You are not eligible to update Voucher", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    $RedirectUrl = site_url('hotel/bookings');
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
                $this->session->setFlashdata('Message', $message);
                $RedirectUrl = site_url('hotel/bookings');
                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                return $this->response->setJSON($data_validation);
            }
        }
    }


    function confirmation()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $HotelBookingModel = new HotelBookingModel();
        $BookingConfirmation = $HotelBookingModel->hotel_booking_info($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
        $publishedFare = 0;
        $offeredFare = 0;
        $CommEarned = 0;
        $TDS = 0;
        $couponAmount = 0;

        if ($BookingConfirmation['booking_source'] == "Wl_b2b") {
            $fareBreakupArray = json_decode($BookingConfirmation['agent_fare_break_up'], true);
        } else {
            $fareBreakupArray = json_decode($BookingConfirmation['customer_fare_break_up'], true);
        }
        if ($BookingConfirmation) {
            $HotelRoomsDetails = $fareBreakupArray;
            $couponAmount = isset($HotelRoomsDetails['couponAmount']) ? $HotelRoomsDetails['couponAmount'] : 0;
            foreach ($HotelRoomsDetails as $HotelRooms) {
                if (isset($HotelRooms['PublishedPrice'])) {
                    $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                }
                if (isset($HotelRooms['OfferedPrice'])) {
                    $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                }
                if (isset($HotelRooms['AgentCommission']) && isset($HotelRooms['Discount'])) {
                    $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                }
                if ($BookingConfirmation['booking_source'] == "Wl_b2b" && isset($HotelRooms['TDS'])) {
                    $TDS = $TDS + $HotelRooms['TDS'];
                }
            }
        }
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-confirmation",
            "hotelInfo" => $BookingConfirmation,
            "publishedFare" => $publishedFare,
            "offeredFare" => $offeredFare,
            "CommEarned" => $CommEarned,
            "couponAmount" => $couponAmount,
            "TDS" => $TDS,
        ];
        return view('template/sidebar-layout', $data);
    }

    function bookingDetails()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $HotelBookingModel = new HotelBookingModel();

        $BookingDetail = $HotelBookingModel->hotel_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
        $publishedFare = 0;
        $offeredFare = 0;
        $CommEarned = 0;
        $TDS = 0;
        $ApplyDiscount = 0;
        $ApplyMarkup = 0;
        $ApplyagentMarkup = 0;
        $ApplyagentDiscount = 0;
        $CGSTAmount = 0;
        $IGSTAmount = 0;
        $SGSTAmount = 0;
        $TaxableAmount = 0;
        if ($BookingDetail) {
            $amendment_list = $HotelBookingModel->amendment_list($this->web_partner_id, $BookingDetail['id']);
            $webpartnerBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
            if ($BookingDetail['booking_source'] == "Wl_b2b") {
                $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true); 
            } else if ($BookingDetail['booking_source'] == "Wl_b2c") {
                $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
            }
            $SupplierFareBreakUp = json_decode($BookingDetail['supplier_fare_break_up'], true);
            $FareBreakUpSupplir = [];
            if(!empty($SupplierFareBreakUp) && $SupplierFareBreakUp !==NULL ){
 
                $PFare = 0;
                $OFare = 0;
                $CE = 0;
                $TDSs = 0;
                $ADiscount = 0;
                $AMarkup = 0;
                $CGSTAt = 0;
                $IGSTAt = 0;
                $SGSTAt = 0;
                $TaxableAt = 0;
                $cAmount = 0; 
                $SUPP_DisplayMarkup = ''; 

                foreach ($SupplierFareBreakUp as $key => $SUpplierHotelRooms) {
                    if (isset($SUpplierHotelRooms['GST'])) {
                        $GST = $SUpplierHotelRooms['GST'];
                        $GSTDATA['CGSTAmount'] = $CGSTAt + $GST['CGSTAmount'];
                        $GSTDATA['IGSTAmount'] = $SGSTAt + $GST['IGSTAmount'];
                        $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                        $GSTDATA['TaxableAmount'] = $TaxableAt + $GST['TaxableAmount'];
                    } 
                    if (isset($SUpplierHotelRooms['PublishedPrice'])) {
                        $PFare = $PFare + $SUpplierHotelRooms['PublishedPrice'];
                    }
                    if (isset($SUpplierHotelRooms['OfferedPrice'])) {
                        $OFare = $OFare + $SUpplierHotelRooms['OfferedPrice'];
                    }
                    if (isset($SUpplierHotelRooms['AgentCommission']) && isset($SUpplierHotelRooms['Discount'])) {
                        $CE = $CE + $SUpplierHotelRooms['AgentCommission'] + $SUpplierHotelRooms['Discount'];
                    }
                    if (isset($SUpplierHotelRooms['SUPP_Markup']) && isset($SUpplierHotelRooms['SUPP_Markup'])) {
                        $AMarkup = $AMarkup + $SUpplierHotelRooms['SUPP_Markup'] + $SUpplierHotelRooms['SUPP_Markup'];
                    }
                    if (isset($SUpplierHotelRooms['SUPP_Discount']) && isset($SUpplierHotelRooms['SUPP_Discount'])) {
                        $ADiscount = $AMarkup + $SUpplierHotelRooms['SUPP_Discount'] + $SUpplierHotelRooms['SUPP_Discount'];
                    }
                    $SUPP_DisplayMarkup = isset($SUpplierHotelRooms['SUPP_DisplayMarkup']) ? $SUpplierHotelRooms['SUPP_DisplayMarkup'] : 'in_tax';
                     
                }

                $FareBreakUpSupplir = array(
                    "FareBreakup" => array(
                        "PublishedPrice" => array("Value" => custom_money_format(round_value($PFare)), "LabelText" => "Published Price"),
                        /* "CommEarned" => array("Value" => custom_money_format(round_value($CE)), "LabelText" => "Comm Earned (-)"),
                        "TDS" => array("Value" => custom_money_format(round_value($TDSs)), "LabelText" => "TDS (+)") */
                    ),
                    "TotalAmount" => array("Value" => custom_money_format(round_value($OFare + $TDSs - $cAmount)), "LabelText" => "Total Amount"),
                    "GSTDetails" => $GSTDATA,
                    "WebPMarkUp" => array("Value" => custom_money_format(round_value($AMarkup)), "LabelText" => "Apply Mark Up"),
                    "WebPDiscount" => array("Value" => custom_money_format(round_value($ADiscount)), "LabelText" => "Apply Discount"), 
                    "ApplyDisPlayMarkup" => array("Value" => $SUPP_DisplayMarkup, "LabelText" => "Apply DisPlay Markup"),
                );
                
            }
 


            $conveniencefee = isset($BookingDetail['conveniencefee']) ? $BookingDetail['conveniencefee'] : 0;


            $GSTDATA = $fareBreakupArray[0]['GST'];
            $couponAmount = isset($fareBreakupArray['couponAmount']) ? $fareBreakupArray['couponAmount'] : 0;
            unset($fareBreakupArray['couponAmount']); 

            foreach ($fareBreakupArray as $key => $HotelRooms) {
                if (isset($HotelRooms['GST'])) {
                    $GST = $HotelRooms['GST'];
                    $GSTDATA['CGSTAmount'] = $CGSTAmount + $GST['CGSTAmount'];
                    $GSTDATA['IGSTAmount'] = $IGSTAmount + $GST['IGSTAmount'];
                    $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                    $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                }
                $web_partner_Breakup_Array = $webpartnerBreakupArray[$key];
                $markup = isset($web_partner_Breakup_Array['WebPMarkUp']) ? $web_partner_Breakup_Array['WebPMarkUp'] : 0;
                $discount = isset($web_partner_Breakup_Array['WebPDiscount']) ? $web_partner_Breakup_Array['WebPDiscount'] : 0;
                $ApplyDisPlayMarkup = isset($web_partner_Breakup_Array['WebPDisplayMarkup']) ? $web_partner_Breakup_Array['WebPDisplayMarkup'] : 'in_tax';
                $ApplyMarkup = $ApplyMarkup + $markup;
                $ApplyDiscount = $ApplyDiscount + $discount;
                
                $ApplyagentsMarkup = isset($HotelRooms['AgentMarkUp']) ? $HotelRooms['AgentMarkUp'] : 0;
                $Applyagentsdesc = isset($HotelRooms['AgentWebPDiscount']) ? $HotelRooms['AgentWebPDiscount'] : 0;

                $ApplyagentMarkup = $ApplyagentMarkup +$ApplyagentsMarkup;
                $ApplyagentDiscount = $ApplyagentDiscount +$Applyagentsdesc;
 
                if (isset($HotelRooms['PublishedPrice'])) {
                    $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                }
                if (isset($HotelRooms['OfferedPrice'])) {
                    $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                }
                if (isset($HotelRooms['AgentCommission']) && isset($HotelRooms['Discount'])) {
                    $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                }
                if (isset($HotelRooms['TDS']) && $BookingDetail['booking_source'] == "Wl_b2b") {
                    $TDS = $TDS + $HotelRooms['TDS'];
                }
            }
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "PublishedPrice" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Published Price"),
                    "CommEarned" => array("Value" => custom_money_format(round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                    "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)"),
                    "couponAmount" => array("Value" => custom_money_format(round_value($couponAmount)), "LabelText" => "Promocode Discount (-)"),
                    "conveniencefee" => array("Value" => custom_money_format(round_value($conveniencefee)), "LabelText" => "Convenience fee (+)")
                ),
                "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $TDS - $couponAmount + $conveniencefee)), "LabelText" => "Total Amount"),
                "GSTDetails" => $GSTDATA,
                "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"), 
                "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"), 
            );

            if($ApplyagentMarkup)
            {
                $FareBreakUp['AgentMarkUp'] = array("Value" => custom_money_format(round_value($ApplyagentMarkup)), "LabelText" => "Apply Agent Mark Up");
            }
            if($ApplyagentDiscount){
                $FareBreakUp['AgentDiscount'] = array("Value" => custom_money_format(round_value($ApplyagentDiscount)), "LabelText" => "Apply Agent Discount");
            }
             
            $data = [
                'title' => $this->title,
                'view' => "Hotel\Views\listing\hotel-booking-detail",
                "bookingDetail" => $BookingDetail,
                "FareBreakUp" => $FareBreakUp,
                "FareBreakUpSupplir" => $FareBreakUpSupplir,
                "amendment_list" => $amendment_list,
            ];
            return view('template/sidebar-layout', $data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");

            $this->session->setFlashdata('Message', $message);
            $url = site_url('hotel/bookings');
            return redirect()->to($url);
        }
    }

    public function raiseAmendment()
    {
        $requestData = $this->request->getPOST();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendment);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelModel = new HotelModel();
                $BookingRefNumber = $this->request->getPOST('booking_ref_number');
                $whereClauseBookingCheck = array("booking_ref_number" => $BookingRefNumber);
                $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");
                if ($BookingRefNumber && $bookingInfo) {
                    $request = array(
                        "BookingId" => $bookingInfo['id'],
                        "Type" => $this->request->getPOST('amendment_type'),
                        "Remarks" => $this->request->getPOST('remark'),
                        "RequesterInfo" => array("RequesterId" => $this->web_partner_details['id'], "Requester" => "WebPartner"),
                    );
                    $service = "submitamendment";
                    $url = $this->Services . $service;
                    $response = Request_hotel($request, $url, $service);
                    if ($response['Error']['ErrorCode'] == 0) {
                        $message = array("StatusCode" => 0, "Message" => "Amendment has  Successfully Submitted.", 'Class' => 'success_popup');
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $response['Error']['ErrorMessage'], "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }


    public function hotelAmendmentLists()
    {
        $HotelAmendmentModel = new HotelAmendmentModel();
        $bookingType = 'all';
        $source = '';
        $getData = $this->request->getGET();

        if (isset($getData['key'])) {
            $list = $HotelAmendmentModel->search_data($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
        } else {
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            if ($this->request->getGET('bookingtype')) {
                $bookingType = $this->request->getGET('bookingtype');
            }
            $list = $HotelAmendmentModel->hotel_amendment_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
        }

        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views/listing/hotel-amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $HotelAmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    function amendmentsDetails()
    {
        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $HotelAmendmentModel = new HotelAmendmentModel();
        $AmendmentDetail = $HotelAmendmentModel->amendment_detail($this->web_partner_id, $amendmentId);
       
        if ($AmendmentDetail) {
            $publishedFare = 0;
            $offeredFare = 0;
            $CommEarned = 0;
            $TDS = 0;
            $ApplyDiscount = 0;
            $ApplyMarkup = 0;
            $CGSTAmount = 0;
            $IGSTAmount = 0;
            $SGSTAmount = 0;
            $TaxableAmount = 0;
            $totalGST = 0;
            $ApplyagentMarkup = 0;
            $ApplyagentDiscount = 0;

            $convertion_rate = 1;
            if ($AmendmentDetail['booking_currency']) {
                $booking_currency = $HotelAmendmentModel->getcurrentcurrencyrates($AmendmentDetail['booking_currency'], $this->web_partner_id);
                if ($booking_currency) {
                    $convertion_rate = $booking_currency['convertion_rate'];
                }
            }
            if ($AmendmentDetail) {


                $SupplierFareBreakUp = json_decode($AmendmentDetail['supplier_fare_break_up'], true);
                $FareBreakUpSupplir = [];
                if(!empty($SupplierFareBreakUp) && $SupplierFareBreakUp !==NULL ){
     
                    $PFare = 0;
                    $OFare = 0;
                    $CE = 0;
                    $TDSs = 0;
                    $ADiscount = 0;
                    $AMarkup = 0;
                    $CGSTAt = 0;
                    $IGSTAt = 0;
                    $SGSTAt = 0;
                    $TaxableAt = 0;
                    $cAmount = 0; 
                    $SUPP_DisplayMarkup = ''; 
    
                    foreach ($SupplierFareBreakUp as $key => $SUpplierHotelRooms) {
                        if (isset($SUpplierHotelRooms['GST'])) {
                            $GST = $SUpplierHotelRooms['GST'];
                            $GSTDATA['CGSTAmount'] = $CGSTAt + $GST['CGSTAmount'];
                            $GSTDATA['IGSTAmount'] = $SGSTAt + $GST['IGSTAmount'];
                            $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                            $GSTDATA['TaxableAmount'] = $TaxableAt + $GST['TaxableAmount'];
                        } 
                        if (isset($SUpplierHotelRooms['PublishedPrice'])) {
                            $PFare = $PFare + $SUpplierHotelRooms['PublishedPrice'];
                        }
                        if (isset($SUpplierHotelRooms['OfferedPrice'])) {
                            $OFare = $OFare + $SUpplierHotelRooms['OfferedPrice'];
                        }
                        if (isset($SUpplierHotelRooms['AgentCommission']) && isset($SUpplierHotelRooms['Discount'])) {
                            $CE = $CE + $SUpplierHotelRooms['AgentCommission'] + $SUpplierHotelRooms['Discount'];
                        }
                        if (isset($SUpplierHotelRooms['SUPP_Markup']) && isset($SUpplierHotelRooms['SUPP_Markup'])) {
                            $AMarkup = $AMarkup + $SUpplierHotelRooms['SUPP_Markup'] + $SUpplierHotelRooms['SUPP_Markup'];
                        }
                        if (isset($SUpplierHotelRooms['SUPP_Discount']) && isset($SUpplierHotelRooms['SUPP_Discount'])) {
                            $ADiscount = $AMarkup + $SUpplierHotelRooms['SUPP_Discount'] + $SUpplierHotelRooms['SUPP_Discount'];
                        }
                        $SUPP_DisplayMarkup = isset($SUpplierHotelRooms['SUPP_DisplayMarkup']) ? $SUpplierHotelRooms['SUPP_DisplayMarkup'] : 'in_tax';
                         
                    }
    
                    $FareBreakUpSupplir = array(
                        "FareBreakup" => array(
                            "PublishedPrice" => array("Value" => custom_money_format(round_value($PFare)), "LabelText" => "Published Price"),
                            /* "CommEarned" => array("Value" => custom_money_format(round_value($CE)), "LabelText" => "Comm Earned (-)"),
                            "TDS" => array("Value" => custom_money_format(round_value($TDSs)), "LabelText" => "TDS (+)") */
                        ),
                        "TotalAmount" => array("Value" => custom_money_format(round_value($OFare + $TDSs - $cAmount)), "LabelText" => "Total Amount"),
                        "GSTDetails" => $GSTDATA,
                        "WebPMarkUp" => array("Value" => custom_money_format(round_value($AMarkup)), "LabelText" => "Apply Mark Up"),
                        "WebPDiscount" => array("Value" => custom_money_format(round_value($ADiscount)), "LabelText" => "Apply Discount"), 
                        "ApplyDisPlayMarkup" => array("Value" => $SUPP_DisplayMarkup, "LabelText" => "Apply DisPlay Markup"),
                    );
                    
                }
 
 
                $conveniencefee = isset($AmendmentDetail['conveniencefee']) ? $AmendmentDetail['conveniencefee'] : 0;

                $webpartnerBreakupArray = json_decode($AmendmentDetail['web_partner_fare_break_up'], true);
                if ($AmendmentDetail['booking_source'] == "Wl_b2b") {
                    $fareBreakupArray = json_decode($AmendmentDetail['agent_fare_break_up'], true); 
                } else if ($AmendmentDetail['booking_source'] == "Wl_b2c") {
                    $fareBreakupArray = json_decode($AmendmentDetail['customer_fare_break_up'], true);
                }
                $couponAmount = isset($fareBreakupArray['couponAmount']) ? $fareBreakupArray['couponAmount'] : 0;
                unset($fareBreakupArray['couponAmount']);

                $GSTDATA = $fareBreakupArray[0]['GST'];
                foreach ($fareBreakupArray as $key => $HotelRooms) {
                    if (isset($HotelRooms['GST'])) {
                        $GST = $HotelRooms['GST'];
                        $GSTDATA['CGSTAmount'] = $totalGST + $CGSTAmount + $GST['CGSTAmount'];
                        $GSTDATA['IGSTAmount'] = $totalGST + $IGSTAmount + $GST['IGSTAmount'];
                        $GSTDATA['SGSTAmount'] = $totalGST + $SGSTAmount + $GST['SGSTAmount'];
                        $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                    }

                    $web_partner_fare_break_up = $webpartnerBreakupArray[$key];
                    $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
                    $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
                    $ApplyDisPlayMarkup = isset($web_partner_fare_break_up['WebPDisplayMarkup']) && $web_partner_fare_break_up['WebPDisplayMarkup'] == 'in_tax';

                    $ApplyMarkup = $ApplyMarkup + $markup;
                    $ApplyDiscount = $ApplyDiscount + $discount;


                    $ApplyagentsMarkup = isset($HotelRooms['AgentMarkUp']) ? $HotelRooms['AgentMarkUp'] : 0;
                    $Applyagentsdesc = isset($HotelRooms['AgentWebPDiscount']) ? $HotelRooms['AgentWebPDiscount'] : 0;
    
                    $ApplyagentMarkup = $ApplyagentMarkup +$ApplyagentsMarkup;
                    $ApplyagentDiscount = $ApplyagentDiscount +$Applyagentsdesc;

                    $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                    $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                    $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                    $TDS = $TDS + $HotelRooms['TDS'];
                }
            }
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "PublishedPrice" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Published Price"),
                    "CommEarned" => array("Value" => custom_money_format(round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                    "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)"),
                    "conveniencefee" => array("Value" => custom_money_format(round_value($conveniencefee)), "LabelText" => "Convenience fee (+)")
                ),
                "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $TDS - $couponAmount+$conveniencefee)), "LabelText" => "Total Amount"),
                "OfferedFare" => array("Value" => custom_money_format(round_value($offeredFare)), "LabelText" => "Offered Fare"),
                "GSTDetails" => $GSTDATA,
                "TotalGST" => $totalGST,
                "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
            );
            if($ApplyagentMarkup)
            {
                $FareBreakUp['AgentMarkUp'] = array("Value" => custom_money_format(round_value($ApplyagentMarkup)), "LabelText" => "Apply Agent Mark Up");
            }
            if($ApplyagentDiscount){
                $FareBreakUp['AgentDiscount'] = array("Value" => custom_money_format(round_value($ApplyagentDiscount)), "LabelText" => "Apply Agent Discount");
            }

            if ($couponAmount) {
                $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => custom_money_format(round_value($couponAmount)), "LabelText" => "Promocode Discount (-)");
            }

            $FareBreakUp2 = array(
                "FareBreakup" => array(
                    "PublishedPrice" => array("Value" => (round_value($publishedFare)), "LabelText" => "Published Price"),
                    "CommEarned" => array("Value" => (round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                    "TDS" => array("Value" => (round_value($TDS)), "LabelText" => "TDS (+)"),
                    "conveniencefee" => array("Value" => custom_money_format(round_value($conveniencefee)), "LabelText" => "Convenience fee (+)")
                ),
                "TotalAmount" => array("Value" => (round_value($offeredFare + $TDS - $couponAmount +$conveniencefee )), "LabelText" => "Total Amount"),
                "OfferedFare" => array("Value" => (round_value($offeredFare)), "LabelText" => "Offered Fare"),
                "GSTDetails" => $GSTDATA,
                "TotalGST" => $totalGST,
                "WebPMarkUp" => array("Value" => (round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => (round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
            );

            if($ApplyagentMarkup)
            {
                $FareBreakUp2['AgentMarkUp'] = array("Value" => custom_money_format(round_value($ApplyagentMarkup)), "LabelText" => "Apply Agent Mark Up");
            }
            if($ApplyagentDiscount){
                $FareBreakUp2['AgentDiscount'] = array("Value" => custom_money_format(round_value($ApplyagentDiscount)), "LabelText" => "Apply Agent Discount");
            }

            if ($couponAmount) {
                $FareBreakUp2['FareBreakup']['Promocode'] = array("Value" => custom_money_format(round_value($couponAmount)), "LabelText" => "Promocode Discount (-)");
            }

            if ($couponAmount) {
                $FareBreakUp2['FareBreakup']['Promocode'] = array("Value" => custom_money_format(round_value($couponAmount)), "LabelText" => "Promocode Discount (-)");
            }
            $data = [
                'title' => $this->title,
                'view' => "Hotel\Views\listing\hotel-amendments-detail",
                "AmendmentInfo" => $AmendmentDetail,
                "RefundVouhcer" => 1,
                "FareBreakUp" => $FareBreakUp,
                "FareBreakUp2" => $FareBreakUp2,
                "convertion_rate" => $convertion_rate,
                "FareBreakUpSupplir" => $FareBreakUpSupplir,
            ];
            return view('template/sidebar-layout', $data);
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }


    function hotelAmendmentCancellationCharge()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->amendment_refund_validation);
        if (!$rules) {
            $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $data = $this->request->getPost();

            $HotelAmendmentModel = new HotelAmendmentModel();
            $amendment_id = dev_decode($data['amendment_id']);
            $HotelAmendmentDetail = $HotelAmendmentModel->hotel_amendment_detail_by_id($amendment_id, $this->web_partner_id);
            if ($HotelAmendmentDetail) {
                $hotel_booking_id = $HotelAmendmentDetail['booking_ref_no'];
                $hotel_booking_info = $HotelAmendmentModel->hotel_booking_by_id($this->web_partner_id, $hotel_booking_id);
                if (!empty($hotel_booking_info)) {
                    if (isset($HotelAmendmentDetail['wl_agent_id']) && $HotelAmendmentDetail['wl_agent_id'] > 0) {
                        $tableName = "agent";
                        $user_id = $HotelAmendmentDetail['wl_agent_id'];
                        $fareBreakupArray = json_decode($hotel_booking_info['agent_fare_break_up'], true);
                        $agentUserGstCode = $HotelAmendmentModel->agent_user_gst_state_code($tableName, $user_id, $this->web_partner_id);
                    } else {
                        $tableName = "customer";
                        $user_id = $HotelAmendmentDetail['wl_customer_id'];
                        $fareBreakupArray = json_decode($hotel_booking_info['customer_fare_break_up'], true);
                        $agentUserGstCode = "";
                    }
                    if (isset($fareBreakupArray['couponAmount'])) {
                        $couponAmount = $fareBreakupArray['couponAmount'];
                        unset($fareBreakupArray['couponAmount']);
                    } else {
                        $couponAmount = 0;
                    }
                    $offeredFare = 0;
                    $TDS = 0;
                    foreach ($fareBreakupArray as $fare) {
                        $offeredFare += $fare['OfferedPrice'];
                        $TDS += $fare['TDS'];
                    }

                    $TDSReturn = 0;
                    $TDSReturnidentifier = "no";
                    if (isset($data['tdsreturn']) && $data['tdsreturn'] == "yes") {
                        $TDSReturnidentifier = "yes";
                        $TDSReturn = $TDS;
                    }
                    $GSTInfo = gst_calculate("Hotel", $agentUserGstCode, super_admin_website_setting['gst_state_code'], $data['service_charge']);
                    $totaldeductionAmount = $data['charge'] + $data['service_charge'] + $GSTInfo['TotalGSTAmount'];
                    $refundAmount = $offeredFare - $couponAmount - $totaldeductionAmount;

                    $refundAmountTotal = round_value(($refundAmount + $TDSReturn));

                    $RefundChargeDetails = [
                        "Charge" => $data['charge'],
                        "ServiceCharge" => $data['service_charge'],
                        "Refund" => $refundAmountTotal,
                        "GST" => $GSTInfo,
                        "TDSReturnIdentifier" => $TDSReturnidentifier,
                    ];

                    $updateData = array();
                    if ($refundAmount > 0) {
                        $updateData = array(
                            "amendment_charges" => json_encode($RefundChargeDetails),
                            "amendment_type" => $HotelAmendmentDetail['amendment_type'],
                            "amendment_id" => $amendment_id,

                        );
                        $update = $HotelAmendmentModel->updateUserData("hotel_booking_list", ["id" => $hotel_booking_id, "web_partner_id" => $this->web_partner_id], $updateData);


                        $updateAmendmentData = [
                            'agent_staff_id' => $this->user_id,
                            'refund_status' => "Open",
                            'refund_amount' => $refundAmountTotal,
                            'refund_date' => create_date(),
                            'modified' => create_date(),
                        ];

                        if (isset($data['current_currency_rate_refund']) && $data['current_currency_rate_refund'] == 'yes') {
                            $updateAmendmentData['refund_currency_rate'] = $data['currency_rate'];
                        } else {
                            $updateAmendmentData['refund_currency_rate'] = $data['current_currency_rate'];
                        }

                        $updateAmendmentData['booking_currency'] = $data['booking_currency'];
                        $updateAmendmentData['currency_symbol'] = $data['currency_symbol'];

                        $update = $HotelAmendmentModel->updateUserData("hotel_amendment", ["id" => $amendment_id, "web_partner_id" => $this->web_partner_id], $updateAmendmentData);

                        $message = array("StatusCode" => 0, "Message" => "Refund is Opened", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Please check refund amount value is negative", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Refund Already Completed for this booking or ", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "Please approved amendment status first", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }



    function hotelRefundLists()
    {
        $HotelAmendmentModel = new HotelAmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $list = $HotelAmendmentModel->search_hotel_refund_list($this->web_partner_id, $getData);
        } else {
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            $list = $HotelAmendmentModel->hotel_refund_list($this->web_partner_id, $bookingType, $source);
        }

        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views/hotelRefund/hotel-refund-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $HotelAmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function hotelRefundClose()
    {

        $validate = new Validation();
        $rules = $this->validate($validate->refund_close_status);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();
            $HotelAmendmentModel = new HotelAmendmentModel();
            $HotelBookingModel = new HotelBookingModel();
            $amendment_id = dev_decode($data['amendment_id']);
            $HotelAmendmentDetail = $HotelAmendmentModel->hotel_amendment_detail_by_id($amendment_id, $this->web_partner_id);

            if ($HotelAmendmentDetail) {
                $hotel_booking_id = $HotelAmendmentDetail['booking_ref_no'];
                $hotel_booking_info = $HotelAmendmentModel->hotel_amendment_by_id($this->web_partner_id, $hotel_booking_id);
                if ($hotel_booking_info) {
                    if ($HotelAmendmentDetail['request_for'] == "Wl_B2B") {
                        $tableName = "agent_account_log";
                        $account_log_id = $HotelAmendmentDetail['wl_agent_id'];
                        $key = 'wl_agent_id';
                        $fareBreakupArray = json_decode($HotelAmendmentDetail['agent_fare_break_up'], true);
                        $agentUserGstCode = $HotelAmendmentModel->agent_user_gst_state_code("agent", $account_log_id, $this->web_partner_id);
                    } else {
                        $tableName = "customer_account_log";
                        $account_log_id = $HotelAmendmentDetail['wl_customer_id'];
                        $key = 'customer_id';
                        $fareBreakupArray = json_decode($HotelAmendmentDetail['customer_fare_break_up'], true);
                        $agentUserGstCode = "";
                    }

                    $hotelBookingAccountinfo = $HotelBookingModel->getData($tableName, array("booking_ref_no" => $hotel_booking_id, 'service' => "hotel", $key => $account_log_id, "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $HotelAmendmentDetail['web_partner_id']), $singalRecord = 1, $whereApply = 1);

                    if (isset($fareBreakupArray['couponAmount'])) {
                        $couponAmount = $fareBreakupArray['couponAmount'];
                        unset($fareBreakupArray['couponAmount']);
                    } else {
                        $couponAmount = 0;
                    }
                    $offeredFare = 0;
                    $TDS = 0;
                    foreach ($fareBreakupArray as $fare) {
                        $offeredFare += $fare['OfferedPrice'];
                        $TDS += $fare['TDS'];
                    }
                    $amendment_charges = json_decode($hotel_booking_info['amendment_charges'], true);

                    $TDSReturn = 0;
                    if (isset($amendment_charges['TDSReturnIdentifier']) && $amendment_charges['TDSReturnIdentifier'] == "yes") {
                        $TDSReturn = $TDS;
                    }
                    $GSTInfo = gst_calculate("Hotel", $agentUserGstCode, super_admin_website_setting['gst_state_code'], $amendment_charges['ServiceCharge']);
                    $totaldeductionAmount = $amendment_charges['Charge'] + $amendment_charges['ServiceCharge'] + $GSTInfo['TotalGSTAmount'];
                    $refundAmount = $offeredFare - $couponAmount - $totaldeductionAmount;

                    $refundAmountTotal = round_value(($refundAmount + $TDSReturn));


                    /* invoice  Number Generate Number */
                    $CommonModel = new CommonModel();
                    $ServiceCharges = $amendment_charges['ServiceCharge'];
                    $checkTaxableInvoce = checkTaxableNonTaxableINV($ServiceCharges, "", 'hotel', 'RFND');

                    $INVPrifix = getTaxableNonTaxableINVSuffix('RFND', $checkTaxableInvoce, 'hotel');
                    $financialYear = get_financial_year();
                    $whereCondition['service'] = 'hotel';
                    $whereCondition['web_partner_id'] = $this->web_partner_id;
                    $whereCondition['invoice_type'] = 'RFND';
                    $whereCondition['financial_year'] = $financialYear;
                    $otherParameter['financialYear'] = $financialYear;
                    $otherParameter['service'] = 'hotel';
                    $otherParameter['invoice_type'] = 'RFND';
                    $otherParameter['INVPrifix'] = $INVPrifix;
                    $otherParameter['web_partner_id'] = $this->web_partner_id;
                    $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;

                    $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                    $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                    $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                    $InvoiceupdateData = $InvoiceInfoData['updateData'];
                    //$CommonModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                    /* invoice  Number Generate Number */

                    $available_balance = $HotelBookingModel->agent_user_available_balance($tableName, $key, $account_log_id, $this->web_partner_id);
                    $agentuserBalance = 0;
                    if (isset($available_balance['balance'])) {
                        $agentuserBalance = $available_balance['balance'];
                    }

                    $WebPatnerAccountLogData['web_partner_id'] = $HotelAmendmentDetail['web_partner_id'];
                    $WebPatnerAccountLogData['user_id'] = $this->user_id;
                    $WebPatnerAccountLogData[$key] = $account_log_id;
                    $WebPatnerAccountLogData['created'] = create_date();
                    $WebPatnerAccountLogData['transaction_type'] = 'credit';
                    $WebPatnerAccountLogData['action_type'] = 'refund';
                    $WebPatnerAccountLogData['role'] = 'web_partner';

                    $WebPatnerAccountLogData['service'] = "hotel";
                    $WebPatnerAccountLogData['payment_mode'] = "Wallet";
                    $extra_param = array();
                    $extra_param = json_decode($hotelBookingAccountinfo['extra_param'], true);
                    $totalpax = 0;
                    $room_guests = json_decode($HotelAmendmentDetail['room_guests'], true);
                    $hotel_rooms_details = json_decode($HotelAmendmentDetail['hotel_rooms_details'], true);
                    foreach ($room_guests as $room_guest) {
                        $totalpax = $totalpax + $room_guest['Adult'] + $room_guest['Child'];
                    }

                    $PaxName = $hotel_rooms_details[0]['HotelPassenger'][0]['FirstName'] . ' ' . $hotel_rooms_details[0]['HotelPassenger'][0]['LastName'] . ' X ' . $totalpax;
                    $WebPatnerAccountLogData['service_log'] = json_encode(array('PaxName' => $PaxName, 'City' => $HotelAmendmentDetail['city'], 'CheckInDate' => $HotelAmendmentDetail['check_in_date'], 'CheckOutDate' => $HotelAmendmentDetail['check_out_date']));
                    $WebPatnerAccountLogData['extra_param'] = json_encode($extra_param);
                    $WebPatnerAccountLogData['remark'] = "Refund for Confirmation No - " . $HotelAmendmentDetail['confirmation_no'] . "Admin Remark " . $HotelAmendmentDetail['remark_from_web_partner'] . " Remark " . $HotelAmendmentDetail['remark_from_user'] . " Remark " . $data['account_remark'];
                    $WebPatnerAccountLogData['booking_ref_no'] = $hotel_booking_id;
                    $WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                    $WebPatnerAccountLogData['invoice_number'] = $InvoiceNumber;

                    $WebPatnerAccountLogData['convertion_rate'] = isset($HotelAmendmentDetail["refund_currency_rate"]) ? $HotelAmendmentDetail["refund_currency_rate"] : 1;
                    $WebPatnerAccountLogData['currency'] = isset($HotelAmendmentDetail["booking_currency"]) ? $HotelAmendmentDetail["booking_currency"] : "INR";
                    $WebPatnerAccountLogData['currency_symbol'] = isset($HotelAmendmentDetail["currency_symbol"]) ? $HotelAmendmentDetail["currency_symbol"] : "₹";

                    $refundAmountConvert =     booking_currency_refunds($refundAmountTotal, $HotelAmendmentDetail["booking_currency"], $HotelAmendmentDetail["refund_currency_rate"]);
                    if ($HotelAmendmentDetail["booking_currency"] == "INR" || $HotelAmendmentDetail["booking_currency"] == NULL) {
                        $WebPatnerAccountLogData['balance'] = round_value(($agentuserBalance + $refundAmountConvert));
                        $WebPatnerAccountLogData['credit'] = $refundAmountConvert;
                    } else {
                        $WebPatnerAccountLogData['balance'] = ($agentuserBalance + $refundAmountConvert);
                        $WebPatnerAccountLogData['credit'] = $refundAmountConvert;
                    }
                    $added_data_id = $HotelAmendmentModel->insertData($tableName, $WebPatnerAccountLogData);

                    $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
                    $HotelAmendmentModel->updateUserData($tableName, array("id" => $added_data_id), $WebPatnerAccountLogDataUpdate);
                    $HotelAmendmentModel->updateUserData("hotel_booking_list", array("id" => $hotel_booking_id), array("refund_account_id" => $added_data_id));
                    $updateData['refund_status'] = "Close";
                    $updateData['account_remark'] = $data['account_remark'];
                    $updateData['agent_staff_id'] = $this->user_id;
                    $updateData['refund_close_date'] = create_date();
                    $update = $HotelAmendmentModel->updateUserData("hotel_amendment", array("id" => $amendment_id), $updateData);
                    if ($update) {
                        $message = array("StatusCode" => 0, "Message" => "Refund  has been successfully done", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Refund  has not been successfully done", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Refund Already Completed for this booking", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "Refund Details Not Found", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    function getCreditNote()
    {
        $HotelBookingModel = new HotelBookingModel();
        $getData = $this->request->getPOST();
        if (!$this->request->isAJAX()) {
            $getVoucherInvioceType = array("CreditNote" => "Voucher");
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $HotelBookingModel->getDataRowType("hotel_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {
                    $userType = ($bookingInfo['booking_source'] == 'Wl_b2b') ? 'wl-agent' : 'wl-customer';
                    $TicketViewRequest = array(
                        "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => "Voucher",
                        "UserType" => $userType,
                        "ViewService" => "View",
                        "WithAgencyDetail" => 0,
                        "ViewSize" => "",
                        "RequestBy" => "WebPartner"
                    );
                    $url = $this->Services . 'generate-wl-credit-note';

                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Hotel\Views\booking\print_voucher",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/sidebar-layout', $data);
                } else {
                    return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
            }
        }
    }

    public function amendmentStatusChange()
    {
        /*  if (permission_access("Visa", "amendment_status_change")) { */
        $validate = new Validation();
        $rules = $this->validate($validate->amendment_status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
            return $this->response->setJSON($data_validation);
        }
        $data = $this->request->getPost();
        $AmendmentModel = new HotelAmendmentModel();
        $HotelBooking = new HotelBookingModel();
        $amendment_id = dev_decode($data['amendment_id']);

        if ($data['status'] == "approved") {
            $AmendmentDetail = $AmendmentModel->amendment_detail($this->web_partner_id, $amendment_id);

            if ($AmendmentDetail['amendment_type'] == "cancellation") {
                $booking_id = $AmendmentDetail['booking_ref_no'];
                if ($data['status'] == 'approved') {
                    $HotelBooking->updateUserData("hotel_booking_list", ["id" => $booking_id, "web_partner_id" => $this->web_partner_id], ["booking_status" => 'Cancelled']);
                }
            }
        }
        $updateData = [
            'amendment_status' => $data['status'],
            'remark_from_web_partner' => $data['admin_remark'],
            'agent_staff_id' => $this->user_id,
            'modified' => create_date()
        ];
        $update = $HotelBooking->updateUserData("hotel_amendment", ["id" => $amendment_id, "web_partner_id" => $this->web_partner_id], $updateData);
        if ($update) {
            $message = ["StatusCode" => 0, "Message" => "Amendment status successfully changed", "Class" => "success_popup", "Reload" => true];
        } else {
            $message = ["StatusCode" => 2, "Message" => "Amendment status not changed", "Class" => "error_popup", "Reload" => true];
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        /*  } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        } */
    }







    public function hotel_markup_list()
    {
        if (permission_access_error("Hotel", "hotel_markup_list")) {
            $HotelMarkupModel = new HotelMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HotelMarkupModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HotelMarkupModel->hotel_markup_list($this->web_partner_id);
            }

            $AgentClassModel = new AgentClassModel();

            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);

            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list' => $agent_class_list,
                'view' => "Hotel\Views\hotel-markup-list",
                'pager' => $HotelMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];


            return view('template/sidebar-layout', $data);
        }
    }

    public function hotel_markup_view()
    {
        if (permission_access_error("Hotel", "add_hotel_markup")) {
            $AgentClassModel = new AgentClassModel();
            $data = [
                'title' => $this->title,
                'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\Hotel\Views\add-hotel-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_hotel_markup()
    {
        if (permission_access_error("Hotel", "add_hotel_markup")) {
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['markup_for'] == "B2C") {
                unset($validate->hotel_markup_validation['agent_class.*']);
            }

            $rules = $this->validate($validate->hotel_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelMarkupModel = new HotelMarkupModel();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                $data['star_rating'] = implode(',', array_map('strval', $data['star_rating']));
                $data['region_type'] = implode(',', $data['region_type']);

                $added_data = $HotelMarkupModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_admin_hotel_markup_template()
    {
        if (permission_access_error("Hotel", "edit_hotel_markup")) {
            $uri = $this->request->getUri();
            $id =  dev_decode($uri->getSegment(3));

            $HotelMarkupModel = new HotelMarkupModel();
            $details = $HotelMarkupModel->hotel_markup_details($id, $this->web_partner_id);

            $AgentClassModel = new AgentClassModel();
            /*  $details['agent_class'] = explode(',', $details['agent_class']);
            $details['region_type'] = explode(',', $details['region_type']);
            $details['star_rating'] = explode(',', $details['star_rating']); */

            $details['agent_class'] = isset($details['agent_class']) && $details['agent_class'] !== null ? explode(',', $details['agent_class']) : [];
            $details['region_type'] = isset($details['region_type']) && $details['region_type'] !== null ? explode(',', $details['region_type']) : [];
            $details['star_rating'] = isset($details['star_rating']) && $details['star_rating'] !== null ? explode(',', $details['star_rating']) : [];


            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),

            ];

            $details = view('Modules\Hotel\Views\edit-hotel-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_hotel_markup()
    {
        if (permission_access_error("Hotel", "edit_hotel_markup")) {
            $data = $this->request->getPost();
            $uri = $this->request->getUri();
            $id =  dev_decode($uri->getSegment(3));
            $validate = new Validation();
            if ($data['markup_for'] == "B2C") {
                unset($validate->hotel_markup_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->hotel_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelMarkupModel = new HotelMarkupModel();
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                $data['star_rating'] = implode(',', $data['star_rating']);
                $data['region_type'] = implode(',', $data['region_type']);
                $data['modified'] = create_date();

                $added_data = $HotelMarkupModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function hotel_markup_status_change()
    {
        if (permission_access_error("Hotel", "hotel_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelMarkupModel = new HotelMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HotelMarkupModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_hotel_markup()
    {
        if (permission_access_error("Hotel", "delete_hotel_markup")) {
            $HotelMarkupModel = new HotelMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HotelMarkupModel->remove_markup($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "hotel markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function hotel_discount_list()
    {
        if (permission_access_error("Hotel", "hotel_discount_list")) {
            $HotelDiscountModel = new HotelDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HotelDiscountModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HotelDiscountModel->hotel_discount_list($this->web_partner_id);
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list' => $agent_class_list,
                'view' => "Hotel\Views\hotel-discount-list",
                'pager' => $HotelDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function hotel_discount_view()
    {
        if (permission_access_error("Hotel", "add_hotel_discount")) {
            $AgentClassModel = new AgentClassModel();
            $data = [
                'title' => $this->title,
                'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\Hotel\Views\add-hotel-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_hotel_discount()
    {
        if (permission_access_error("Hotel", "add_hotel_discount")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if ($data['discount_for'] == "B2C") {
                unset($validate->hotel_discount_validation['agent_class.*']);
            }

            $rules = $this->validate($validate->hotel_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();
                $data['created'] = create_date();

                $data['web_partner_id'] = $this->web_partner_id;
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

                $data['region_type'] = implode(',', $data['region_type']);

                $added_data = $HotelDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_admin_hotel_discount_template()
    {
        if (permission_access_error("Hotel", "edit_hotel_discount")) {
            $uri = $this->request->getUri();
            $id =  dev_decode($uri->getSegment(3));

            $HotelDiscountModel = new HotelDiscountModel();
            $details = $HotelDiscountModel->hotel_discount_details($id, $this->web_partner_id);

            $details['agent_class'] = isset($details['agent_class']) && $details['agent_class'] !== null ? explode(',', $details['agent_class']) : [];
            $details['region_type'] = isset($details['region_type']) && $details['region_type'] !== null ? explode(',', $details['region_type']) : [];

            $AgentClassModel = new AgentClassModel();

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id)
            ];

            $details = view('Modules\Hotel\Views\edit-hotel-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_hotel_discount()
    {
        if (permission_access_error("Hotel", "edit_hotel_discount")) {
            $uri = $this->request->getUri();
            $id =  dev_decode($uri->getSegment(3));
            $data = $this->request->getPost();
            $validate = new Validation();
            if ($data['discount_for'] == "B2C") {
                unset($validate->hotel_discount_validation['agent_class.*']);
            }
            $rules = $this->validate($validate->hotel_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();

                $data['modified'] = create_date();
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                $data['region_type'] = implode(',', $data['region_type']);
                $added_data = $HotelDiscountModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function hotel_discount_status_change()
    {
        if (permission_access_error("CarExtranet", "car_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HotelDiscountModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "hotel discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_hotel_discount()
    {
        if (permission_access_error("CarExtranet", "delete_car_discount")) {
            $HotelDiscountModel = new HotelDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HotelDiscountModel->remove_discount($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "hotel discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
}

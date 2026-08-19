<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightModel;
use App\Modules\Flight\Models\FlightAirportModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Modules\Flight\Models\FlightBookingModel;
use App\Models\CommonModel;
use App\Modules\Flight\Models\WebPartnerFlightMarkupModel;
use App\Modules\Flight\Models\WebPartnerFlightDiscountModel;
use App\Modules\Flight\Models\CouponModel;
use App\Modules\Flight\Models\FlightAmendmentModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;

class Flight extends BaseController
{
    protected $title;
    protected $web_partner_details;
    protected $wl_customer_id;
    protected $wl_customer_info;
    protected $Services;
    protected $web_partner_id;
    protected $HolidayServices;
    protected $web_partner_gst_code;
    protected $wl_customer_gst_code;
    public $validation;
    public $request;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        $this->wl_customer_info = array();
        $this->web_partner_gst_code = substr(web_partner_details['company_gst_no'], 0, 2);
        $this->wl_customer_gst_code = substr(web_partner_details['company_gst_no'], 0, 2);
        if (isset(session()->get('wl_customer')['id'])) {
            $this->wl_customer_id = session()->get('wl_customer')['id'];
            $this->wl_customer_info = session()->get('wl_customer');
        }
        $this->Services = API_REQUEST_URL . '/airservice/rest/';
     
        $this->HolidayServices = API_REQUEST_URL . '/holidayservice/rest/';

        if (permission_access_error("flight_module")) {
        }

        helper('Modules\Flight\Helpers\flight');
    }

    public function check_search_validation()
    {
        $data = $this->request->getPOST();

        $validate = new Validation();
        $rules = $this->validate($validate->search_validation($data));
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 0, "Message" => "");
            return $this->response->setJSON($message);
        }
        
    }



    public function index()
    {
        $FlightModel = new FlightModel();
        $CommonModel = new CommonModel();
        $offers_list = $CommonModel->offers_list($this->web_partner_id);
        $slider_list = $CommonModel->slider_list($this->web_partner_id);
        $blog_list = $FlightModel->blog_list($this->web_partner_id);

        $feedbac_list = $FlightModel->get_feedback_model_list($this->web_partner_id);

        $top_routes_list = $FlightModel->get_top_routes_list($this->web_partner_id);

        $custom_routes_list = array();
        foreach ($top_routes_list as $key => $routes_list) {

            $origin = explode("(", $routes_list['origin']);
            $custom_routes_list[$key]['city_origin'] = $origin[0];
            $destination = explode("(", $routes_list['destination']);
            $custom_routes_list[$key]['city_destination'] = $destination[0];
            if ($routes_list['journeytype'] == 'oneway') {
                $arrowType = 'to';
                $journeytype = 'Oneway';
                $seperator = '-';
            } else {
                $arrowType = '<i class="fa fa-exchange" aria-hidden="true"></i>';
                $journeytype = 'Roundtrip';
                $seperator = '<i class="fa fa-exchange" aria-hidden="true"></i>';
            }
            $custom_routes_list[$key]['arrowType'] = $arrowType;
            $custom_routes_list[$key]['seperator'] = $seperator;
            $custom_routes_list[$key]['OriginCode'] = $routes_list['origin_code'];
            $custom_routes_list[$key]['DestinationCode'] = $routes_list['destination_code'];
            $custom_routes_list[$key]['url']['journeytype'] = $journeytype;
            $custom_routes_list[$key]['url']['origin'] = $routes_list['origin'];
            $custom_routes_list[$key]['url']['destination'] = $routes_list['destination'];
            $custom_routes_list[$key]['url']['adults'] = $routes_list['adult'];
            $custom_routes_list[$key]['url']['child'] = $routes_list['child'];
            $custom_routes_list[$key]['url']['infant'] = $routes_list['infant'];
            $convertedrouteslistPrice = convertCurrencyRate($routes_list['price']);
            $custom_routes_list[$key]['price'] = $convertedrouteslistPrice['ConvertedPrice'];
            $custom_routes_list[$key]['CurrencySymbol'] = $convertedrouteslistPrice['CurrencySymbol'];
            if (!empty($routes_list['depart_date'])) {
                $custom_routes_list[$key]['url']['departdate'] = $routes_list['depart_date'];
            } else {
                $custom_routes_list[$key]['url']['departdate'] = date('d M Y');
            }

            if ($routes_list['journeytype'] == 'round-trip' && !empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = $routes_list['return_date'];
            } else if ($routes_list['journeytype'] == 'round-trip' && empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = date('Y-m-d H:i:s', strtotime($custom_routes_list[$key]['url']['departdate'] . ' +1 day'));
            } else {
                $custom_routes_list[$key]['url']['returndate'] = '';
            }
            $custom_routes_list[$key]['url']['cabinclass'] = $routes_list['cabin_class'];
            if ($routes_list['direct_flight'] == 'true') {
                $custom_routes_list[$key]['url']['direct_flight'] = 1;
            } else {
                $custom_routes_list[$key]['url']['direct_flight'] = 0;
            }
            $custom_routes_list[$key]['url']['preferred_carriers'] = '';
        }

        $MetaInfoData = static_meta_information('Flight', 'Index');
        $offers_list['home'] = isset($offers_list['bestoffer']) ? $offers_list['bestoffer'] : [];
        unset($offers_list['bestoffer']);
        $desired_order = ['home', 'flight', 'hotel', 'holiday', 'bus', 'visa', 'car', 'activities', 'tourguide', 'hajj', 'umrah'];
        $ordered_list = [];
        foreach ($desired_order as $key) {
            if (isset($offers_list[$key])) {
                $ordered_list[$key] = $offers_list[$key];
            }
        }
        $offers_list = $ordered_list;
        foreach ($offers_list as $key => $value) {
            if (!isset($ordered_list[$key])) {
                $ordered_list[$key] = $value;
            }
        }
        $data = [

            'offers_list' => $ordered_list,
            'top_routes_list' => $custom_routes_list,
            'blog_list' => $blog_list,
            'slider_list' => $slider_list,
            'feedbac_list' => $feedbac_list,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => "Flight\Views\FlightBookingtemplate\index",
        ];
        return view('template/default-layout', $data);
    }




    public function search()
    {
        $FlightModel = new FlightModel();
        if (isset($_GET['token']) && $_GET['token'] != "") {

            $token = $_GET['token'];
            $searchInfo = $FlightModel->getApiLogsData(array("tts_search_token" => $token, "service" => "search"), "tts_custom_request");
            $searchData = json_decode($searchInfo['tts_custom_request'], true);
        } else {
            $searchData = $this->request->getGET();
        }
        $type = $searchData['journeytype'];
        $OriginDestinationAirportCodeData = getSearchOriginDestinationAirportCode($searchData);
        $searchData = $OriginDestinationAirportCodeData['searchData'];
        $OriginDestinationAirportCode = $OriginDestinationAirportCodeData['airportCodeArray'];
        $OriginDestinationAirportDetail = $FlightModel->selected_airport_detail($OriginDestinationAirportCode);
        $view = "Flight\Views\FlightBookingtemplate/flight_result";


        if ($type == "Roundtrip") {
            /* Start Checking is domestic  */
            $origin_country_code = get_country_name($searchData['origin']);
            $destination_country_code = get_country_name($searchData['destination']);
            if ($origin_country_code == "india" && $destination_country_code == "india") {
                $isdomestic = "true";
                $view = "Flight\Views\FlightBookingtemplate/flight_result_roundtrip";
            }
            /* End Checking is domestic  */
        }

        $MetaInfoData = static_meta_information('Flight', 'Result');
        $data = [
            'searchData' => $searchData,
            'OriginDestinationAirportDetail' => $OriginDestinationAirportDetail,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => $view,
        ];
        return view('template/default-layout', $data);
    }

    public function flightResult()
    {
        $FlightModel = new FlightModel();
        if (isset($_GET['token']) && $_GET['token'] != "") {
            $token = $_GET['token'];
            $search_data = $FlightModel->getApiLogsData(array("tts_search_token" => $token, "service" => "search"), "request,response");
        } else {
            $search_data = $this->request->getGET();
        }

        if ($search_data) {
            $airlineLogoClass = "";
            if (!isset($_GET['token'])) {
                $type = $this->request->getGET("journeytype");
                $no_adult = $this->request->getGET("adults");
                $no_child = $this->request->getGET("child");
                $no_infants = $this->request->getGET("infant");
                $class = get_api_cabinclass($this->request->getGET("cabinclass"));

                /*  $response = json_decode(file_get_contents(FCPATH . '/webroot/flight_response.json'), true); */

                if ($type == "Oneway") {
                    $journeytype = 1;
                    $origin = AirportCode($this->request->getGET("origin"));
                    $destination = AirportCode($this->request->getGET("destination"));
                    $depart_date_time = get_api_date_format($this->request->getGET("departdate")) . "T00:00:00";
                    $searchjson_value = array(
                        array(
                            'Origin' => $origin,
                            'Destination' => $destination,
                            'PreferredTime' => $depart_date_time
                        )
                    );

                    /* Start Checking is domestic  */
                    $origin_country_code = get_country_name($this->request->getGET("origin"));
                    $destination_country_code = get_country_name($this->request->getGET("destination"));

                    if ($origin_country_code == "india" && $destination_country_code == "india") {
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                } else if ($type == "Roundtrip") {
                    $journeytype = 2;
                    $origin = AirportCode($this->request->getGET("origin"));
                    $destination = AirportCode($this->request->getGET("destination"));
                    $depart_date_time = get_api_date_format($this->request->getGET("departdate")) . "T00:00:00";
                    $return_date_time = get_api_date_format($this->request->getGET("returndate")) . "T00:00:00";
                    $searchjson_value = array(
                        array(
                            'Origin' => $origin,
                            'Destination' => $destination,
                            'PreferredTime' => $depart_date_time
                        ),
                        array(
                            'Origin' => $destination,
                            'Destination' => $origin,
                            'PreferredTime' => $return_date_time
                        )
                    );
                    /* Start Checking is domestic  */
                    $origin_country_code = get_country_name($this->request->getGET("origin"));
                    $destination_country_code = get_country_name($this->request->getGET("destination"));
                    if ($origin_country_code == "india" && $destination_country_code == "india") {
                        /*    $response = json_decode(file_get_contents(FCPATH . '/webroot/flight_roundtrip_result.json'), true); */
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                } else {
                    $journeytype = 3;
                    $search_data = $this->request->getGET("search_data");
                    foreach ($search_data as $key => $searchjourney) {
                        $country_name[] = get_country_name($searchjourney['origin']);
                        $country_name[] = get_country_name($searchjourney['origin']);
                        $searchjson_value[] = array(
                            'Origin' => AirportCode($searchjourney['origin']),
                            'Destination' => AirportCode($searchjourney['destination']),
                            'PreferredTime' => get_api_date_format($searchjourney['departdate']) . "T00:00:00"
                        );
                    }

                    $country_code_data = array_unique($country_name);
                    $country_code_count = count(array_unique($country_name));
                    /* Start Checking is domestic  */
                    if ($country_code_count == 1 && $country_code_data[0] == "india") {
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                }
                $DirectFlight = false;
                $PreferredCarriers = NULL;
                $ResultFareType = '';
                if (isset($search_data['direct_flight']) && $search_data['direct_flight'] != 0) {
                    $DirectFlight = true;
                }
                if (isset($search_data['preferred_carriers']) && $search_data['preferred_carriers'] != '' && $search_data['preferred_carriers'] != 'all') {
                    $PreferredCarriers = explode(",", $search_data['preferred_carriers']);
                }

                if (isset($search_data['result_fare_type']) && $search_data['result_fare_type'] != '') {
                    $ResultFareType = $search_data['result_fare_type'];
                }
                $request = array(
                    'UserIp' => $this->request->getIpAddress(),
                    'Adult' => $no_adult,
                    'Child' => $no_child,
                    'Infant' => $no_infants,
                    'DirectFlight' => $DirectFlight,
                    'JourneyType' => $journeytype,
                    'PreferredCarriers' => $PreferredCarriers,
                    'CabinClass' => $class,
                    'ResultFareType' => $ResultFareType,
                    'AirSegments' => $searchjson_value,
                    'Sources' => NULL
                );
                $service = "search";
                $url = $this->Services . $service;
                /*  echo json_encode($request);exit; */
                $response = TTSRequest($request, $url, $service);

                /*    pr("line number 344");
                pr($response);
                die; */

                $MarkupInput = $request;
                $MarkupInput['IsDomestic'] = $isdomestic;
            } else {
                $searchData = json_decode($search_data['request'], true);
                $MarkupInput = $searchData;
                if ($searchData['IsDomestic'] == "true") {
                    $isdomestic = "true";
                    $airlineLogoClass = "domAirLogo";
                } else {
                    $isdomestic = "false";
                    $airlineLogoClass = "intAirLogo";
                }
                $response = json_decode($search_data['response'], true);
            }


            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                if (!isset($_GET['token'])) {
                    $FlightModel->updateApiLogsData(array("tts_search_token" => $response['SearchTokenId'], "service" => "search"), array("tts_custom_request" => json_encode($_GET)));
                }
                $FlightJourneyResults = $response['Result'];
                $TotalResults = 0;
                $TotalReturnResults = 0;
                $priceArray = array();
                $selectedMarkupDataInfo = array();
                $selectedDiscountDataInfo = array();
                unset($response['Result']);
                if ($FlightJourneyResults) {

                    $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                    $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                    $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $MarkupInput);
                    $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $MarkupInput);
                    $FilterData = array();

                    foreach ($FlightJourneyResults as $journeykey => $FlightJourneyResult) {
                        if ($journeykey == 0) {
                            $TotalResults = count($FlightJourneyResult);
                        } else if ($journeykey == 1) {
                            $TotalReturnResults = count($FlightJourneyResult);
                        }

                        /*-------Start For Filter----*/
                        $FilterPrice = array();
                        $FilterAirline = array();
                        $FilterFareType = array();
                        $FilterStop = array();
                        $FilterDepart = array();
                        $CurrencySymbol = "";
                        $CurrencyCode = "";
                        foreach ($FlightJourneyResult as $flightkey => $flight_result) {

                            $first_segment = current($flight_result['Segments']);
                            $first_segment = current($first_segment);
                            $FareListOptions = $flight_result['FareList'];

                            foreach ($flight_result['FareList'] as $farelistkey => $FareList) {
                                $extraParam = array();
                                $extraParam['CabinClass'] = $FareList['CabinClass'];
                                $extraParam['FareType'] = $FareList['FareType'];
                                $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                                $FlightPrice = get_flight_fare($MarkupInput, $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $FareList['Fare'], $FareList['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam);
                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $FareWithMarkup = $FlightPrice['Fare'];
                                $ConvertFareWithMarkup   = convertCurrencyRate($FareWithMarkup);
                                $CurrencySymbol = $ConvertFareWithMarkup['CurrencySymbol'];
                                $CurrencyCode = $ConvertFareWithMarkup['CurrencyCode'];
                                $FareBreakdown = $FlightPrice['FareBreakdown'];
                                foreach ($FlightPrice['FareBreakdown'] as $paxKey => $FbreakDown) {
                                    $PassengerCount  = $FbreakDown['PassengerCount'];
                                    unset($FbreakDown['PassengerCount']);
                                    $paxFare  =   convertCurrencyRate($FbreakDown);
                                    $paxFare =  $paxFare['ConvertedPrice'];
                                    $paxFare['PassengerCount'] =  $PassengerCount;
                                    $FareBreakdown[$paxKey] = $paxFare;
                                }
                                $FareList['Fare'] = $ConvertFareWithMarkup['ConvertedPrice'];
                                $FareList['FareBreakdown'] = $FareBreakdown;
                                $FareList['PublishedPrice'] = $ConvertFareWithMarkup['ConvertedPrice']['PublishedPrice'];
                                $FareListOptions[$farelistkey] = $FareList;
                            }
                            /*------ Sort Price Group    --------*/
                            $keys_publishprice = array_column($FareListOptions, 'PublishedPrice');
                            array_multisort($keys_publishprice, SORT_ASC, $FareListOptions);
                            $flight_result['FareList'] = $FareListOptions;
                            $mainSegment = array();
                            foreach ($flight_result['Segments'] as $tripindicatorkey => $result) {
                                $Duration = array();
                                $Airline = array();
                                $firstSegment = reset($result);
                                $lastSegment = end($result);
                                $departurecity = "";
                                $arrivalcity = "";
                                $departtime = "";
                                $airlineName = "";
                                $TotalDurationMin = 0;
                                $arrivalDays = 0;
                                $tripStops = "";
                                $airlineCodes = array();
                                $airlineNames = array();
                                $airlineFlightNumber = array();
                                $airlineCodeFlightNumber = array();
                                $Airline = array_column($result, "Airline");
                                $Duration = array_column($result, "Duration");
                                $airlineCodes = array_column($Airline, "AirlineCode");
                                $airlineCodeFlightNumber = array_column($Airline, "AirlineCode", "FlightNumber");
                                $airlineNames = array_column($Airline, "AirlineName");
                                $airlineFlightNumber = array_column($Airline, "FlightNumber");
                                $departurecity = $firstSegment['Origin']['AirportCode'];
                                $arrivalcity = $lastSegment['Destination']['AirportCode'];
                                $departtime = get_flight_time($firstSegment['Origin']['DepartTime']);
                                $arrivaltime = get_flight_time($lastSegment['Destination']['ArrivalTime']);
                                $departdate = get_flight_date($firstSegment['Origin']['DepartTime']);
                                $arrivaldate = get_flight_date($lastSegment['Destination']['ArrivalTime']);
                                $tripStops = (count($result) - 1);
                                /*     $TotalDurationMin = array_sum($Duration); */
                                $TotalDurationMin = $firstSegment['TotalDuration'];
                                $arrivalDays = get_flight_arrival_days($firstSegment['Origin']['DepartTime'], $lastSegment['Destination']['ArrivalTime']);
                                $airlineCodeFlightNumber = array_map(function ($value, $key) {
                                    return $value . '-' . $key;
                                }, array_values($airlineCodeFlightNumber), array_keys($airlineCodeFlightNumber));
                                $airlineCodeFlightNumber = implode(',', $airlineCodeFlightNumber);
                                array_push($FilterPrice, $flight_result['FareList'][0]['Fare']['PublishedPrice']);
                                $airline_code = array_unique($airlineCodes)[0];
                                $airline_name = array_unique($airlineNames)[0];
                                $subairlineArray = array('value' => $airline_code, 'label' => $airline_name, 'isChecked' => false);
                                array_push($FilterAirline, $subairlineArray);

                                if ($tripStops == 0) {
                                    $stoplabel = 'Non Stop';
                                } else {
                                    $stoplabel = $tripStops . ' Stop';
                                }
                                $substopArray = array('value' => $tripStops, 'label' => $stoplabel, 'isChecked' => false);
                                array_push($FilterStop, $substopArray);

                                $mainSegment[$tripindicatorkey] = array(
                                    "DepartureCity" => $departurecity,
                                    "Fare" => $flight_result['FareList'][0]['Fare']['PublishedPrice'],
                                    "ArrivalCity" => $arrivalcity,
                                    "DepartTime" => $departtime,
                                    "DepartDate" => $departdate,
                                    "ArrivalTime" => $arrivaltime,
                                    "ArrivalDate" => $arrivaldate,
                                    "Stops" => $tripStops,
                                    "ArrivalDays" => $arrivalDays,
                                    "Seats" => isset($flight_result['FareList'][0]['SeatBaggage'][$tripindicatorkey][0]['NoOfSeatAvailable']) ? $flight_result['FareList'][0]['SeatBaggage'][$tripindicatorkey][0]['NoOfSeatAvailable'] : 0,
                                    "Airlinecodes" => array_unique($airlineCodes),
                                    "Airlinecode" => trim($airline_code),
                                    "AirlineName" => trim($airline_name),
                                    "AirlineFlightNumber" => array_unique($airlineFlightNumber),
                                    "AirlineCodeFlightNumberString" => trim($airlineCodeFlightNumber, ", "),
                                    "DurationMin" => trim(intval($TotalDurationMin)),
                                    "Duration" => get_convertToHoursMinsfromMinDuration($TotalDurationMin),
                                    "DepartString" => filter_deparr_string($departtime),
                                    "ArrivalString" => filter_deparr_string($arrivaltime),
                                );
                            }
                            $FlightJourneyResults[$journeykey][$flightkey]['MainSegment'] = $mainSegment;
                            $FlightJourneyResults[$journeykey][$flightkey]['TtsIndex'] = $flightkey;
                            $FlightJourneyResults[$journeykey][$flightkey]['FareList'] = $flight_result['FareList'];
                            $FlightJourneyResults[$journeykey][$flightkey]['AirlineRemark'] = $flight_result['FareList'][0]['AirlineRemark'];
                        }

                        $FilterAirline = array_values(array_map("unserialize", array_unique(array_map("serialize", $FilterAirline))));
                        $FilterStop = array_values(array_map("unserialize", array_unique(array_map("serialize", $FilterStop))));
                        array_multisort(array_column($FilterStop, 'value'), SORT_ASC, $FilterStop);
                        array_multisort(array_column($FilterAirline, 'label'), SORT_ASC, $FilterAirline);
                        $FilterData[$journeykey] = array(
                            'Price' => array('min' => min($FilterPrice), 'max' => max($FilterPrice)),
                            'FareType' => array(
                                array('label' => 'Refundable', 'value' => true, 'isChecked' => false),
                                array('label' => 'Non Refundable', 'value' => false, 'isChecked' => false)
                            ),
                            'Stop' => $FilterStop,
                            'DepartTime' => array(
                                array('label' => '12AM-6AM', 'value' => 'EarlyMorning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/morning.svg')),
                                array('label' => '6AM-12PM', 'value' => 'Morning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sun.svg')),
                                array('label' => '12PM-6PM', 'value' => 'Afternoon', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sunset.svg')),
                                array('label' => '6PM-12AM', 'value' => 'Night', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/moon.svg')),
                            ),
                            'ArrivalTime' => array(
                                array('label' => '12AM-6AM', 'value' => 'EarlyMorning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/morning.svg')),
                                array('label' => '6AM-12PM', 'value' => 'Morning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sun.svg')),
                                array('label' => '12PM-6PM', 'value' => 'Afternoon', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sunset.svg')),
                                array('label' => '6PM-12AM', 'value' => 'Night', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/moon.svg')),
                            ),
                            'Airline' => $FilterAirline
                        );
                    }
                    $response['Result'] = $FlightJourneyResults;
                    $response['TotalResults'] = $TotalResults;
                    $response['TotalReturnResults'] = $TotalReturnResults;
                    $response['airlineLogoClass'] = $airlineLogoClass;
                    $response['Filter'] = $FilterData;
                    $response['CurrencySymbol'] = $CurrencySymbol;
                    $response['CurrencyCode'] = $CurrencyCode;

                    /*     if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                        ob_start('ob_gzhandler');
                    else ob_start(); */

                    return $this->response->setJson($response);
                }
            } else {
                return $this->response->setJson($response);
            }
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "Search is not allowed.",
                )
            );
            return $this->response->setJson($response);
        }
    }


    function fareRule()
    {
        $fare_rule_data = $this->request->getPOST();
        if ($fare_rule_data) {
            $request = array(
                'UserIp' => $this->request->getIpAddress(),
                'ResultIndex' => $fare_rule_data['FareId'],
                'SearchTokenId' => $fare_rule_data['token'],
            );
            $service = "farerule";
            $url = $this->Services . $service;
            $response = TTSRequest($request, $url, $service);
            return $this->response->setJson($response);
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
            return $this->response->setJson($response);
        }
    }




    public function fareConfirmation()
    {
        $fareConfirmationData = $this->request->getPOST();
        if (!$fareConfirmationData) {
            return $this->response->setJson([
                "Error" => [
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                ]
            ]);
        }

        $request = [
            'UserIp' => $this->request->getIpAddress(),
            'ResultIndex' => $fareConfirmationData['FareId'],
            'SearchTokenId' => $fareConfirmationData['token'],
        ];

        $service = "fareconfirmation";
        $url = $this->Services . $service;
        $response = TTSRequest($request, $url, $service);

        if ($response['Error']['ErrorCode'] != 0) {
            return $this->response->setJson($response);
        }

        $getConfirmShowBox = 0;
        $title = "Confirming Your Flight";
        $priceChange = "";
        $redirectUrl = site_url() . "flight/flight-details?token=" . urlencode($fareConfirmationData['token']) . "&farecode=" . urlencode($fareConfirmationData['FareId']) . "&rtype=frcmn";
        if ($response['IsPriceChanged'] == 1) {
            $title = "Price has been changed";
            $getConfirmShowBox = 1;

            $newPrice = $response['Result']['Fare']['PublishedPrice'];
            $flightModel = new FlightModel();
            $searchInfo = $flightModel->getApiLogsData([
                "tts_search_token" => $fareConfirmationData['token'],
                "service" => "search"
            ], "response");

            $searchResult = json_decode($searchInfo['response'], true)['Result'][0][$fareConfirmationData['TtsResultIndexkey']];
            $selectedFareInfo = $searchResult['FareList'][$fareConfirmationData['FareListOptionkey']];
            $oldPrice = $selectedFareInfo['Fare']['PublishedPrice'];

            $difference = str_replace("-", "", ($newPrice - $oldPrice));
            // $difference = abs($newPrice - $oldPrice); // Absolute difference
            $signType = check_int($difference);

            $convertedDifferenceFare = convertCurrencyRate($difference);
            $convertedNewPrice = convertCurrencyRate($newPrice);
            $convertedOldPrice = convertCurrencyRate($oldPrice);

            $currencySymbol = htmlspecialchars($convertedDifferenceFare['CurrencySymbol'], ENT_QUOTES, 'UTF-8');
            $difference = htmlspecialchars($convertedDifferenceFare['ConvertedPrice'], ENT_QUOTES, 'UTF-8');
            $newPrice = htmlspecialchars($convertedNewPrice['ConvertedPrice'], ENT_QUOTES, 'UTF-8');
            $oldPrice = htmlspecialchars($convertedOldPrice['ConvertedPrice'], ENT_QUOTES, 'UTF-8');
            $sign = htmlspecialchars($signType['sign'], ENT_QUOTES, 'UTF-8');

            $priceChange = '
                <div class="col-lg-12 align-self-center">
                    <img src="' . htmlspecialchars($signType['image'], ENT_QUOTES, 'UTF-8') . '" alt="change price" class="w90 pb10"> 
                    <div class="row">
                        <p class="msg">
                            <samp>The Airline has ' . htmlspecialchars($signType['text'], ENT_QUOTES, 'UTF-8') . ' the fare on this route by</samp>
                            <samp> ' . $currencySymbol . ' ' . change_money_format($difference) . '.</samp>
                            Please note that airfares are dynamic and subject to change. Select from the options below.
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center"> 
                    <div class="col-12 col-lg-8 col-sm-8 p0 fare-update">
                        <table class="table">
                            <tbody>
                                <tr> 
                                    <td><strong>Updated Fare</strong></td> 
                                    <td><samp>' . $currencySymbol . ' ' . change_money_format($newPrice) . '</samp></td>
                                </tr>
                                <tr> 
                                    <td><strong>Original Fare</strong></td> 
                                    <td><samp>' . $currencySymbol . ' ' . change_money_format($oldPrice) . '</samp></td>
                                </tr>
                                <tr class="total"> 
                                    <td><strong>Difference</strong></td>  
                                    <td><samp><b>' . $sign . '</b> ' . $currencySymbol . ' ' . change_money_format($difference) . '</samp></td>
                                </tr>  
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center mb15 mlr0">
                    <div class="col col-auto">
                        <button type="button" class="btn btn-primary another_fare" data-bs-dismiss="modal" aria-label="Close">Select Another Flight</button>
                    </div> 
                    <div class="col col-auto">
                        <a href="' . htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') . '" class="btn go_button">Continue</a> 
                    </div>      
                </div>
            ';
        }

        $response = [
            "Error" => [
                "ErrorCode" => 0,
                "ErrorMessage" => "",
            ],
            "Title" => $title,
            "RedirectUrl" => $redirectUrl,
            "GetConfirmShowBox" => $getConfirmShowBox,
            "PriceChange" => $priceChange,
        ];

        return $this->response->setJson($response);
    }




    function fareConfirmationRoundtrip()
    {
        $fare_confirmation_data = $this->request->getPOST();
        if (!empty($fare_confirmation_data['OnwardFareId']) && !empty($fare_confirmation_data['ReturnFareId'])) {
            $requestData = [
                'OB' => [
                    'UserIp' => $this->request->getIpAddress(),
                    'ResultIndex' => $fare_confirmation_data['OnwardFareId'],
                    'SearchTokenId' => $fare_confirmation_data['token'],
                ],
                'IB' => [
                    'UserIp' => $this->request->getIpAddress(),
                    'ResultIndex' => $fare_confirmation_data['ReturnFareId'],
                    'SearchTokenId' => $fare_confirmation_data['token'],
                ]
            ];

            $getConfirmshowbox = 0;
            $title = "Confirming Your Flight";
            $price_change = "";
            $IsPriceChanged = 0;
            $ErrorCode = 0;
            $ErrorMessage = "";
            $redirectUrl = site_url("flight/flight-details") . "?token=" . $fare_confirmation_data['token'] . "&farecode=" . $fare_confirmation_data['OnwardFareId'] . "&farecodereturn=" . $fare_confirmation_data['ReturnFareId'] . "&rtype=frcmn";

            foreach ($requestData as $key => $request) {
                // Set parameters for each journey
                if ($key == "OB") {
                    $selectedJourney = 0;
                    $resultIndexkey = $fare_confirmation_data['TtsOnwardResultIndexkey'];
                    $Selectfarekey = $fare_confirmation_data['FareListOptionOnwardkey'];
                    $JourneyType = "Outbound";
                } else { // IB for return journey
                    $selectedJourney = 1;
                    $resultIndexkey = $fare_confirmation_data['TtsReturnResultIndexkey'];
                    $Selectfarekey = $fare_confirmation_data['FareListOptionReturnkey'];
                    $JourneyType = "Inbound";
                }

                $service = "fareconfirmation";
                $url = $this->Services . $service;
                $response = TTSRequest($request, $url, $service);

                if ($response['Error']['ErrorCode'] == 0) {
                    if ($response['IsPriceChanged'] == 1) {
                        $IsPriceChanged = 1;
                        $title = "Price has been changed";
                        $getConfirmshowbox = 1;
                        $newprice = $response['Result']['Fare']['PublishedPrice'];
                        // Retrieve search result from API logs
                        $FlightModel = new FlightModel();
                        $searchInfo = $FlightModel->getApiLogsData(["tts_search_token" => $fare_confirmation_data['token'], "service" => "search"], "response");
                        $SearchResultIndexInfo = json_decode($searchInfo['response'], true)['Result'][$selectedJourney][$resultIndexkey];
                        $SeletedFareInfo = $SearchResultIndexInfo['FareList'][$Selectfarekey];
                        $oldprice = $SeletedFareInfo['Fare']['PublishedPrice'];

                        // Calculate price difference
                        $difference = $newprice - $oldprice;
                        $sign_type = check_int($difference);

                        // Convert prices to the desired currency
                        $convertedDifferenceFare = convertCurrencyRate($difference);
                        $convertedNewPrice = convertCurrencyRate($newprice);
                        $convertedOldPrice = convertCurrencyRate($oldprice);

                        $currencySymbol = htmlspecialchars($convertedDifferenceFare['CurrencySymbol'], ENT_QUOTES, 'UTF-8');
                        $difference = htmlspecialchars($convertedDifferenceFare['ConvertPrice'], ENT_QUOTES, 'UTF-8');
                        $newPrice = htmlspecialchars($convertedNewPrice['ConvertPrice'], ENT_QUOTES, 'UTF-8');
                        $oldPrice = htmlspecialchars($convertedOldPrice['ConvertPrice'], ENT_QUOTES, 'UTF-8');
                        $sign = htmlspecialchars($sign_type['sign'], ENT_QUOTES, 'UTF-8');

                        // Prepare price change information
                        $price_change = '
                        <table class="table table-striped">
                            <tr><td class="info" colspan="2"><strong>' . $JourneyType . '</strong></td></tr>
                        </table>
                        <div class="col-lg-12 align-self-center">
                            <img src="' . $sign_type['image'] . '" alt="price change" class="w90 pb10">
                            <div class="row">
                                <p class="msg">
                                    <samp>The Airline has ' . $sign_type['text'] . ' the fare on this route by </samp>
                                    <samp>' . $currencySymbol . change_money_format($difference) . '.</samp>
                                    Please note that airfares are dynamic and subject to change. Select from the options below.
                                </p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-8 col-sm-8 p0 fare-update">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><strong>Updated Fare</strong></td>
                                            <td><samp>' . $currencySymbol . change_money_format($newPrice) . '</samp></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Original Fare</strong></td>
                                            <td><samp>' . $currencySymbol . change_money_format($oldPrice) . '</samp></td>
                                        </tr>
                                        <tr class="total">
                                            <td><strong>Difference</strong></td>
                                            <td><samp><b>' . $sign . '</b> ' . $currencySymbol . change_money_format($difference) . '</samp></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>';
                    }
                } else {
                    $ErrorCode = $response['Error']['ErrorCode'];
                    $ErrorMessage = $response['Error']['ErrorMessage'];
                    $redirectUrl = "";
                    $title = "Confirming Your Flight";
                    $getConfirmshowbox = 0;
                    $price_change = "";
                    break;
                }
            }

            if ($IsPriceChanged) {
                $price_change .= '
                <div class="row justify-content-center mb15 mlr0">
                    <div class="col col-auto">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Select Another Flight</button>
                    </div>
                    <div class="col col-auto">
                        <a href="' . $redirectUrl . '" class="btn go_button">Continue</a>
                    </div>
                </div>';
            }

            $response = [
                "Error" => [
                    "ErrorCode" => $ErrorCode,
                    "ErrorMessage" => $ErrorMessage,
                ],
                "Title" => $title,
                "RedirectUrl" => $redirectUrl,
                "GetConfirmShowBox" => $getConfirmshowbox,
                "PriceChange" => $price_change,
            ];

            return $this->response->setJson($response);
        } else {
            $response = [
                "Error" => [
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                ]
            ];
            return $this->response->setJson($response);
        }
    }






    public function flight_details()
    { 
        $flightConfrimationRequest = $this->request->getGET();
        if ($flightConfrimationRequest) {
            if ($flightConfrimationRequest['rtype'] == "frcmn") {
                $FlightModel = new FlightModel();
                $flightConfrimationData = array();
                $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecode']);
                $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');
                if ($flightConfrimationDataOB) {
                    $flightConfrimationData['OB'] = $flightConfrimationDataOB['response'];
                } else {
                    $flightConfrimationData['OB'] = array();
                }
                if (isset($flightConfrimationRequest['farecodereturn']) and $flightConfrimationRequest['farecodereturn'] != "") {
                    $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                    $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
                    if ($flightConfrimationDataIB) {
                        $flightConfrimationData['IB'] = $flightConfrimationDataIB['response'];
                    } else {
                        $flightConfrimationData['IB'] = array();
                    }
                }
                $searchRequest = array();
                $dial_code = array();
                $whereFlightSearch = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "search");
                $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
                $searchRequest = json_decode($FlightSearchInfo['request'], true);
                $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $searchRequest);
                $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $searchRequest);
                $IsPanMandatory = false;
                $IsPassportMandatory = false;
                $IsGSTMandatory = false;
                $IsAdultDOBMandatory = false;
                $IsADTDOBRequired = false;
                $IsDocumentIdMandatory = false;
                $DocumentIdAllowed = false;
                $GSTAllowed = false;
                $IsGDS = false;
                $couponlistInfo = array();
                if (whitelabel['b2c_coupon'] == 'active') {
                    $couponModel = new CouponModel();
                    $couponlistInfo = $couponModel->getCouponList($searchRequest, $this->web_partner_id);
                }
                if ($flightConfrimationData) {
                    $ErrorCode = 0;
                    $ErrorMessage = "";
                    $flightConfrimationResponseData = array();
                    $flightConfrimationFareDetailData = array();
                    foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                        if ($fareconfirmation) {
                            $fareconfirmationArray = json_decode($fareconfirmation, true);
                            if ($fareconfirmationArray['Error']['ErrorCode'] == 0) {

                                $selectedMarkupDataInfo = array();
                                $selectedDiscountDataInfo = array();
                                if ($fareconfirmationArray['Result']['IsPanRequiredAtBook']) {
                                    $IsPanMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['IsPassportRequiredAtBook']) {
                                    $IsPassportMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['IsGSTMandatory']) {
                                    $IsGSTMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['GSTAllowed']) {
                                    $GSTAllowed = true;
                                }
                                if (isset($fareconfirmationArray['Result']['IsDocumentIdAllowed']) && $fareconfirmationArray['Result']['IsDocumentIdAllowed']) {
                                    $DocumentIdAllowed = true;
                                }

                                if (isset($fareconfirmationArray['Result']['IsADTDOBRequired']) && $fareconfirmationArray['Result']['IsADTDOBRequired']) {
                                    $IsADTDOBRequired = true;
                                }

                                if (isset($fareconfirmationArray['Result']['IsAdultDOBMandatory']) && $fareconfirmationArray['Result']['IsAdultDOBMandatory']) {
                                    $IsAdultDOBMandatory = true;
                                }

                                if (isset($fareconfirmationArray['Result']['IsDocumentIdMandatory']) && $fareconfirmationArray['Result']['IsDocumentIdMandatory']) {
                                    $IsDocumentIdMandatory = true;
                                }
                                if (!$fareconfirmationArray['Result']['IsLCC']) {
                                    $IsGDS = true;
                                }
                                unset($fareconfirmationArray['Error']);
                                $first_segment = current($fareconfirmationArray['Result']['Segments']);
                                $first_segment = current($first_segment);
                                $extraParam = array();
                                $extraParam['CabinClass'] = $first_segment['CabinClass'];
                                $extraParam['FareType'] = $fareconfirmationArray['Result']['FareType'];
                                $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                                $FlightPrice = get_flight_fare($searchRequest, $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $fareconfirmationArray['Result']['Fare'], $fareconfirmationArray['Result']['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');
                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $convertfareBreakupArray =  convertCurrencyRate($FlightPrice['Fare']);
                                $fareBreakupArray = $convertfareBreakupArray['ConvertedPrice'];
                                $CurrencySymbol = $convertfareBreakupArray['CurrencySymbol'];
                                $CurrencyCode = $convertfareBreakupArray['CurrencyCode'];

                                /* $discount = isset($FlightPrice['WebPartnerFareBreakup']['WebPDiscount']) ? $FlightPrice['WebPartnerFareBreakup']['WebPDiscount'] : 0; */
                                /* $fareBreakupArray = $fareconfirmationArray['Result']['Fare']; */

                                $discount  = $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'];
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
                                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                                        "Meal" => array("Value" => 0, "LabelText" => "Meal (+)"),
                                        "Baggage" => array("Value" => 0, "LabelText" => "Baggage (+)"),
                                        "Seat" => array("Value" => 0, "LabelText" => "Seat (+)"),
                                    ),
                                );
 


                                if ($discount) {
                                    $FareBreakUp['FareBreakup']['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
                                }



                                $FareBreakUp['TotalAmount'] = array("Value" => round_value($fareBreakupArray['OfferedPrice']), "LabelText" => "Pay  Amount");
                                $flightConfrimationFareDetailData[$fareconfirmationKey] = $FareBreakUp;
                                $flightConfrimationResponseData[$fareconfirmationKey] = $fareconfirmationArray;
                            } else {
                                $ErrorCode = $fareconfirmationArray['Error']['ErrorCode'];
                                $ErrorMessage = $fareconfirmationArray['Error']['ErrorMessage'];
                                $flightConfrimationResponseData = array();
                                $flightConfrimationFareDetailData = array();
                                break;
                            }
                        } else {
                            $ErrorCode = 400;
                            $ErrorMessage = "No Result Found.";
                            $flightConfrimationResponseData = array();
                            $flightConfrimationFareDetailData = array();
                            break;
                        }
                    }
                    $returnResponse = array(
                        "Error" => array(
                            "ErrorCode" => $ErrorCode,
                            "ErrorMessage" => $ErrorMessage,
                        ),
                        "flightConfrimationResponse" => $flightConfrimationResponseData,
                        "FareBreakUpData" => $flightConfrimationFareDetailData
                    );

                    $dial_code = $FlightModel->get_dial_code();
                } else {
                    $returnResponse = array(
                        "Error" => array(
                            "ErrorCode" => 400,
                            "ErrorMessage" => "No Result Found.",
                        )
                    );
                }
            } else {
                $returnResponse = array(
                    "Error" => array(
                        "ErrorCode" => 400,
                        "ErrorMessage" => "No Result Found.",
                    )
                );
            }
        } else {
            $returnResponse = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
        }
        $searchPaxInfo = array(
            "Adult" => $searchRequest['Adult'],
            "Child" => $searchRequest['Child'],
            "Infant" => $searchRequest['Infant'],
        );
        $IsDomestic = $searchRequest['IsDomestic'];

        $response_ssr = [];


        $b2c_coupon = (whitelabel['b2c_coupon'] == 'active');

        $CommonModel = new CommonModel();
        $processedbutton = 1;
        $webpartnerBalance = $CommonModel->web_partner_balance($this->web_partner_id);
        if (isset($webpartnerBalance['balance']) && $webpartnerBalance['balance'] < 0) {
            $processedbutton = 0;
        }
        $MetaInfoData = static_meta_information('Flight', 'Details');

        $data = [
            'view' => "Flight\Views\FlightBookingtemplate/flight_details",
            'confirmationResponse' => $returnResponse,
            'searchRequest' => $searchRequest,
            'searchPaxInfo' => $searchPaxInfo,
            'IsPanMandatory' => $IsPanMandatory,
            'couponlist' => $couponlistInfo,
            'IsPassportMandatory' => $IsPassportMandatory,
            'IsAdultDOBMandatory' => $IsAdultDOBMandatory,
            'IsGSTMandatory' => $IsGSTMandatory,
            'GSTAllowed' => $GSTAllowed,
            'IsADTDOBRequired' => $IsADTDOBRequired,
            'IsDocumentIdMandatory' => $IsDocumentIdMandatory,
            'DocumentIdAllowed' => $DocumentIdAllowed,
            'IsGDS' => $IsGDS,
            'IsDomestic' => $IsDomestic,
            'dial_code' => $dial_code,
            'b2c_coupon' => $b2c_coupon,
            'web_partner_details' => $this->web_partner_details,
            'wl_customer_info' => $this->wl_customer_info,
            'processedbutton' => $processedbutton,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'ssr' => $response_ssr,
            'CurrencySymbol' => $CurrencySymbol,
            'CurrencyCode' => $CurrencyCode,
        ];
        return view('template/default-layout', $data);
    }


    function GetMealBaggageData()
    {
        $flightConfrimationRequest = $this->request->getGET();
        $response_ssr = [];
        $MealBaggageContainer = [];
        if ($flightConfrimationRequest) {
            $FlightModel = new FlightModel();
            $whereFlightSearch = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "search");
            $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
            $searchRequest = json_decode($FlightSearchInfo['request'], true);
            $searchPaxInfo = array(
                "Adult" => $searchRequest['Adult'],
                "Child" => $searchRequest['Child'],
                "Infant" => $searchRequest['Infant'],
            );
            $IsDomestic = $searchRequest['IsDomestic'];
            $whereClauseSSR = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "ssr", "selected_index" => $flightConfrimationRequest['farecode']);
            $SSRDataOB = $FlightModel->getApiLogsData($whereClauseSSR, 'response');
            if (empty($SSRDataOB)) {
                $request = array(
                    'UserIp' => $this->request->getIpAddress(),
                    'ResultIndex' => $flightConfrimationRequest['farecode'],
                    'SearchTokenId' => $flightConfrimationRequest['token'],
                );
                $service = "ssr";
                $url = $this->Services . $service;
                $response_ssr['ssrOB'] = TTSRequest($request, $url, $service);
            } else {
                $response_ssr['ssrOB'] = json_decode($SSRDataOB['response'], true);
            }
            $ssrOBmeal = [];
            $ssrOBbaggage = [];
            $ssrIBmeal = [];
            $ssrIBbaggage = [];
            $SeatDynamic = [];
            $SeatIBDynamic = [];
            $seatData = [];
            $seatpaxdata = [];
            if (isset($response_ssr['ssrOB']['Error']['ErrorCode']) && $response_ssr['ssrOB']['Error']['ErrorCode'] == 0) {
                $IsGDS = false;
                $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecode']);
                $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');
                $flightConfrimationDataOB = json_decode($flightConfrimationDataOB['response'], true);
                if (!$flightConfrimationDataOB['Result']['IsLCC']) {
                    $IsGDS = true;
                }
                if (isset($response_ssr['ssrOB']['Result']['Meal'])) {
                    if ($IsGDS) {
                        $ssrOBmeal = $response_ssr['ssrOB']['Result']['Meal'];
                        $ssrOBmeal = array(array($ssrOBmeal));
                    } else {
                        foreach ($response_ssr['ssrOB']['Result']['Meal'] as $key => $meals) {
                            foreach ($meals as $sub_key => $meal) {

                                $convertPrice =  convertCurrencyRate($meal['Price']);
                                $meal['Price'] = $convertPrice['ConvertedPrice'];
                                $meal['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                                $meal['CurrencyCode'] = $convertPrice['CurrencyCode'];
                                $segmentKey = "{$meal['Origin']}-{$meal['Destination']}";
                                if (isset($ssrOBmeal[$key]) && array_key_exists($segmentKey, $ssrOBmeal[$key])) {
                                    $ssrOBmeal[$key][$segmentKey][] = $meal;
                                } else {
                                    $ssrOBmeal[$key][$segmentKey][] = $meal;
                                }
                            }
                        }
                    }
                }
 
                if (isset($response_ssr['ssrOB']['Result']['Baggage'])) {
                    foreach ($response_ssr['ssrOB']['Result']['Baggage'] as $key => $baggages) {
                        foreach ($baggages as $sub_key => $baggage) {
                            $convertPrice =  convertCurrencyRate($baggage['Price']);
                            $baggage['Price'] = $convertPrice['ConvertedPrice'];
                            $baggage['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                            $baggage['CurrencyCode'] = $convertPrice['CurrencyCode'];



                            $segmentKey = "{$baggage['Origin']}-{$baggage['Destination']}";
                            if (isset($ssrOBbaggage[$key]) && array_key_exists($segmentKey, $ssrOBbaggage[$key])) {
                                $ssrOBbaggage[$key][$segmentKey][] = $baggage;
                            } else {
                                $ssrOBbaggage[$key][$segmentKey][] = $baggage;
                            }
                        }
                    }
                }
                /* if (isset($response_ssr['ssrOB']['Result']['Seats'])) {
                    $SeatDynamic = $response_ssr['ssrOB']['Result']['Seats']; 

                } */


                /* ************************Code Modify by Abhay ************************ */
                if (isset($response_ssr['ssrOB']['Result']['Seats'])) {
                    $SeatDynamic = $response_ssr['ssrOB']['Result']['Seats'];
                    foreach ($SeatDynamic as $key => $flightArray) { 
                        foreach ($flightArray as $subKey => $seatData) { 
                            if (isset($seatData['ul']) && is_array($seatData['ul'])) { 
                                foreach ($seatData['ul'] as $ulKey => $ulArray) { 
                                    foreach ($ulArray as $seatKey => $seat) { 
                                        if (isset($seat['Price'])) {
                                            $Price = floatval($seat['Price']);
                                            $convertPrice = convertCurrencyRate($Price); 
                                            $seat['Price'] = $convertPrice['ConvertedPrice']; 
                                            $seat['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                                            $seat['CurrencyCode'] = $convertPrice['CurrencyCode'];
                                            $SeatDynamic[$key][$subKey]['ul'][$ulKey][$seatKey] = $seat;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $response_ssr['ssrOB']['Result']['Seats'] = $SeatDynamic;
                }
                /* ************************Code Modify by Abhay ************************ */
             



            }
            if ($ssrOBmeal) {
                $MealBaggageContainer[0]['Meal'] = $ssrOBmeal;
            }
            if ($ssrOBbaggage) {
                $MealBaggageContainer[0]['Baggage'] = $ssrOBbaggage;
            }
            if ($SeatDynamic) {
                $seatData = $SeatDynamic;
            }


            if (isset($flightConfrimationRequest['farecodereturn']) && $flightConfrimationRequest['farecodereturn'] != '') {
                $whereClauseSSRIB = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "ssr", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                $SSRDataIB = $FlightModel->getApiLogsData($whereClauseSSRIB, 'response');
                if (empty($SSRDataIB)) {
                    $request = array(
                        'UserIp' => $this->request->getIpAddress(),
                        'ResultIndex' => $flightConfrimationRequest['farecodereturn'],
                        'SearchTokenId' => $flightConfrimationRequest['token'],
                    );
                    $service = "ssr";
                    $url = $this->Services . $service;
                    $response_ssr['ssrIB'] = TTSRequest($request, $url, $service);
                } else {
                    $response_ssr['ssrIB'] = json_decode($SSRDataIB['response'], true);
                }

                if (isset($response_ssr['ssrIB']['Error']['ErrorCode']) && $response_ssr['ssrIB']['Error']['ErrorCode'] == 0) {
                    $IsGDS = false;
                    $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                    $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
                    $flightConfrimationDataIB = json_decode($flightConfrimationDataIB['response'], true);
                    if (!$flightConfrimationDataIB['Result']['IsLCC']) {
                        $IsGDS = true;
                    }
                    /* if (isset($response_ssr['ssrIB']['Result']['Seats'])) {
                        $SeatIBDynamic = $response_ssr['ssrIB']['Result']['Seats'];
                    } */


                    /* ************************Code Modify by Abhay ************************ */
                    if (isset($response_ssr['ssrIB']['Result']['Seats'])) {
                        $SeatIBDynamic = $response_ssr['ssrIB']['Result']['Seats'];
                        foreach ($SeatIBDynamic as $key => $flightArray) { 
                            foreach ($flightArray as $subKey => $seatData) { 
                                if (isset($seatData['ul']) && is_array($seatData['ul'])) { 
                                    foreach ($seatData['ul'] as $ulKey => $ulArray) { 
                                        foreach ($ulArray as $seatKey => $seat) { 
                                            if (isset($seat['Price'])) {
                                                $Price = floatval($seat['Price']);
                                                $convertPrice = convertCurrencyRate($Price); 
                                                $seat['Price'] = $convertPrice['ConvertedPrice']; 
                                                $seat['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                                                $seat['CurrencyCode'] = $convertPrice['CurrencyCode'];
                                                $SeatIBDynamic[$key][$subKey]['ul'][$ulKey][$seatKey] = $seat;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $response_ssr['ssrIB']['Result']['Seats'] = $SeatIBDynamic;
                    }
                /* ************************Code Modify by Abhay ************************ */




                    if (isset($response_ssr['ssrIB']['Result']['Meal'])) {
                        if ($IsGDS) {
                            $ssrIBmeal = $response_ssr['ssrIB']['Result']['Meal'];
                            $ssrIBmeal = array(array($ssrIBmeal));
                        } else {
                            foreach ($response_ssr['ssrIB']['Result']['Meal'] as $key => $meals) {
                                foreach ($meals as $sub_key => $meal) {
                                    $convertPrice =  convertCurrencyRate($meal['Price']);
                                    $meal['Price'] = $convertPrice['ConvertedPrice'];
                                    $meal['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                                    $meal['CurrencyCode'] = $convertPrice['CurrencyCode'];

                                    $segmentKey = "{$meal['Origin']}-{$meal['Destination']}";
                                    if (isset($ssrIBmeal[$key]) && array_key_exists($segmentKey, $ssrIBmeal[$key])) {
                                        $ssrIBmeal[$key][$segmentKey][] = $meal;
                                    } else {
                                        $ssrIBmeal[$key][$segmentKey][] = $meal;
                                    }
                                }
                            }
                        }
                    }

                    if (isset($response_ssr['ssrIB']['Result']['Baggage'])) {

                        foreach ($response_ssr['ssrIB']['Result']['Baggage'] as $key => $baggages) {
                            foreach ($baggages as $sub_key => $baggage) {
                                $convertPrice =  convertCurrencyRate($baggage['Price']);
                                $baggage['Price'] = $convertPrice['ConvertedPrice'];
                                $baggage['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
                                $baggage['CurrencyCode'] = $convertPrice['CurrencyCode'];
                                $segmentKey = "{$baggage['Origin']}-{$baggage['Destination']}";

                                if (isset($ssrIBbaggage[$key]) && array_key_exists($segmentKey, $ssrIBbaggage[$key])) {
                                    $ssrIBbaggage[$key][$segmentKey][] = $baggage;
                                } else {
                                    $ssrIBbaggage[$key][$segmentKey][] = $baggage;
                                }
                            }
                        }
                    }
                }
                if ($ssrIBmeal) {
                    $MealBaggageContainer[1]['Meal'] = $ssrIBmeal;
                }
                if ($ssrIBbaggage) {
                    $MealBaggageContainer[1]['Baggage'] = $ssrIBbaggage;
                }
                if ($SeatIBDynamic) {
                    array_push($seatData, $SeatIBDynamic[0]);
                    /* $seatData = $SeatIBDynamic; */
                }
            }
        }

        $finalssrvalue = array();
        if ($MealBaggageContainer) {
            foreach ($MealBaggageContainer as $key => $ssrinfo) {
                foreach ($searchPaxInfo as $paxkey => $pax) {
                    if ($paxkey != 'Infant') {
                        if ($pax != 0) {
                            for ($i = 1; $i <= $pax; $i++) {
                                $finalssrvalue[$key][$paxkey][$i] = $ssrinfo;
                            }
                        }
                    }
                }
            }
        }
        if ($seatData) {
            foreach ($seatData as $key => $seatInfo) {
                foreach ($seatInfo as $subkey => $seatInf) {

                    foreach ($searchPaxInfo as $paxkey => $pax) {
                        if ($paxkey != 'Infant') {
                            if ($pax != 0) {
                                for ($i = 1; $i <= $pax; $i++) {
                                    $seatpaxdata[$key][$paxkey][$i][$seatInf['Origin'] . "-" . $seatInf['Destination']] = array();
                                }
                            }
                        }
                    }
                }
            }
        }
        if (empty($convertPrice)) {
            $convertPrice =  convertCurrencyRate(0);
        }
        if ($MealBaggageContainer) {
            $returnData['ErrorCode'] = 0;
            $returnData['ErrorMessage'] = '';
            $returnData['CurrencySymbol'] = $convertPrice['CurrencySymbol'];
            $returnData['currencyCode'] = $convertPrice['CurrencyCode'];
            $returnData['decimalPoint'] = $convertPrice['DecimalPoint'];
            $returnData['SSRData'] = $finalssrvalue;
            $returnData['SeatData'] = $seatData;
            $returnData['SeatPaxData'] = $seatpaxdata;
        } else {
            $returnData['ErrorCode'] = 1;
            $returnData['ErrorMessage'] = 'No SSR Available';
            $returnData['SSRData'] = [];
        }

        return $this->response->setJSON($returnData);
    }

    public function validate_travellers()
    {
        $data = $this->request->getPost();
        $FlightModel = new FlightModel();
        $whereFlightSearch = array("tts_search_token" => $data['SearchTokenId'], "service" => "search");
        $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
        $searchRequest = json_decode($FlightSearchInfo['request'], true);
        $validate = new Validation();
        $validationConfigArray = $validate->pax_validation($data, $searchRequest);
        $this->validation->setRules($validationConfigArray);
        $rules = $this->validation->run($data);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $response = Flight::savaData($data);
            return $this->response->setJSON($response);
        }
    }

    private function savaData($data)
    {
        $FlightConfirmationPaxInfo = $data;
        if ($FlightConfirmationPaxInfo) {
            $FlightModel = new FlightModel();
            $whereClauseBookingCheck = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId']);
            $bookingInfo = $FlightModel->getData("flight_booking_list", $whereClauseBookingCheck, "tts_search_token");
            if (!isset($bookingInfo['tts_search_token'])) {
                if ($FlightConfirmationPaxInfo['rtype'] == "frcmn") {
                    if (!isset($data['gst'])) {
                        $gst_info = json_encode(
                            array(
                                'name' => '',
                                'number' => '',
                                'phone' => '',
                                'email' => '',
                                'address' => ''
                            )
                        );
                    }
                    $BaggageData = array();
                    $MealData = array();
                    $SeatData = array();
                    $SelectedPassengerBaggageData = array();
                    $SelectedPassengerMealData = array();
                    $IsGDSArray = array();
                    if (isset($FlightConfirmationPaxInfo['ssr']['meal']) && !empty($FlightConfirmationPaxInfo['ssr']['meal'])) {
                        $SelectedPassengerMealData = $FlightConfirmationPaxInfo['ssr']['meal'];
                    }
                    if (isset($FlightConfirmationPaxInfo['ssr']['baggage']) && !empty($FlightConfirmationPaxInfo['ssr']['baggage'])) {
                        $SelectedPassengerBaggageData = $FlightConfirmationPaxInfo['ssr']['baggage'];
                    }
                    $whereFlightSearch = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "search");
                    $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
                    $searchRequest = json_decode($FlightSearchInfo['request'], true);
                    $TotalPax = $searchRequest['Adult'] + $searchRequest['Child'];
                    $TotalPaxWithInfant = $searchRequest['Adult'] + $searchRequest['Child'] + $searchRequest['Infant'];
                    $GSTNumber = $this->wl_customer_gst_code;
                    $GstNumber = isset($data['gst']['number']) && $data['gst']['number'] != "" ? $data['gst']['number'] : $GSTNumber;
                    $flightConfrimationData = array();
                    $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $FlightConfirmationPaxInfo['farecode']);
                    $flightConfrimationData['OB'] = $FlightModel->getApiLogsData($whereClause, 'response,tts_index_response');
                    $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "ssr", "selected_index" => $FlightConfirmationPaxInfo['farecode']);
                    $OBSSRADTA = $FlightModel->getApiLogsData($whereClause, 'response');
                    if (isset($OBSSRADTA['response'])) {

                        $OBSSRADTA = json_decode($OBSSRADTA['response'], true);
                        if ($OBSSRADTA['Error']['ErrorCode'] == 0) {
                            $IsGDSArray[0] = json_decode($flightConfrimationData['OB']['response'], true)['Result']['IsLCC'];
                            if (isset($OBSSRADTA['Result']['Meal'])) {
                                $MealData[0] = $OBSSRADTA['Result']['Meal'];
                            }
                            if (isset($OBSSRADTA['Result']['Baggage'])) {
                                $BaggageData[0] = $OBSSRADTA['Result']['Baggage'];
                            }
                            if (isset($OBSSRADTA['Result']['Seats'])) {
                                $SeatData = $OBSSRADTA['Result']['Seats'];
                            }
                        }
                    }
                    if (isset($FlightConfirmationPaxInfo['farecodereturn']) && $FlightConfirmationPaxInfo['farecodereturn'] != "" && $searchRequest['IsDomestic']) {
                        $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $FlightConfirmationPaxInfo['farecodereturn']);
                        $flightConfrimationData['IB'] = $FlightModel->getApiLogsData($whereClause, 'response,tts_index_response');;
                        $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "ssr", "selected_index" => $FlightConfirmationPaxInfo['farecodereturn']);
                        $IBSSRADTA = $FlightModel->getApiLogsData($whereClause, 'response');
                        if (isset($IBSSRADTA['response'])) {
                            $IBSSRADTA = json_decode($IBSSRADTA['response'], true);
                            if ($IBSSRADTA['Error']['ErrorCode'] == 0) {
                                $IsGDSArray[1] = json_decode($flightConfrimationData['IB']['response'], true)['Result']['IsLCC'];
                                if (isset($IBSSRADTA['Result']['Meal'])) {
                                    $MealData[1] = $IBSSRADTA['Result']['Meal'];
                                }
                                if (isset($IBSSRADTA['Result']['Baggage'])) {
                                    $BaggageData[1] = $IBSSRADTA['Result']['Baggage'];
                                }
                                if (isset($IBSSRADTA['Result']['Seats'])) {
                                    array_push($SeatData, $IBSSRADTA['Result']['Seats'][0]);
                                }
                            }
                        }
                    }
                    $selectedSeatdata = array();
                    if (isset($FlightConfirmationPaxInfo['ssr']['seat']) && !empty($FlightConfirmationPaxInfo['ssr']['seat'])) {
                        $convertSeatData = convertTTSFormatSeatData($SeatData, $IsGDSArray, $searchRequest['IsDomestic']);
                        $selectedSeatdata = getSelectedPaxSeat($FlightConfirmationPaxInfo['ssr']['seat'], $searchRequest['IsDomestic'], $convertSeatData);
                    }
                    if ($flightConfrimationData) {
                        $ErrorCode = 0;
                        $ErrorMessage = "";
                        $SaveDataArray = array();
                        $ConvertSSRData = ConvertSSRDataIntoTTSFormat($MealData, $BaggageData, $IsGDSArray);

                        $MealData = $ConvertSSRData['MealData'];
                        /*  pr($MealData );die; */
                        $BaggageData = $ConvertSSRData['BaggageData'];
                        $passenger_info = $FlightConfirmationPaxInfo['pax'];
                        $tts_search_token = $FlightConfirmationPaxInfo['SearchTokenId'];
                        $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                        $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                        $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $searchRequest);
                        $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $searchRequest);
                        foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                            $getTripIndicator = $fareconfirmationKey == 'OB' ? 0 : 1;
                            $common_data = array();
                            $selectedMarkupDataInfo = array();
                            $selectedDiscountDataInfo = array();
                            if ($fareconfirmation) {
                                $fareconfirmationArray = json_decode($fareconfirmation['response'], true);
                                $fare_confimation = $fareconfirmationArray['Result'];
                                $firstSegment = reset($fare_confimation['Segments']);
                                $lastSegment = end($fare_confimation['Segments']);
                                $origin = $firstSegment['0']['Origin']['CityCode'];
                                $departure_date = explode("T", $firstSegment['0']['Origin']['DepartTime'])[0];
                                if ($searchRequest['JourneyType'] == 3) {
                                    $lastSegmentKey = count($lastSegment) - 1;
                                    $destination = $lastSegment[$lastSegmentKey]['Destination']['CityCode'];
                                } else {
                                    $lastSegmentKey = count($firstSegment) - 1;
                                    $destination = $firstSegment[$lastSegmentKey]['Destination']['CityCode'];
                                }
                                $airline_code = $firstSegment['0']['Airline']['AirlineCode'];
                                $extraParam['CabinClass'] = $firstSegment['0']['CabinClass'];
                                $extraParam['FareType'] = $fare_confimation['FareType'];
                                $extraParam['FareClass'] = $firstSegment[0]['Airline']['FareClass'];
                                $FlightPrice = get_flight_fare($searchRequest, $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $fareconfirmationArray['Result']['Fare'], $fareconfirmationArray['Result']['FareBreakdown'], $airline_code, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');
                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $CustomerFare = $FlightPrice['Fare'];
                                $CustomerFareBreakdown = $FlightPrice['FareBreakdown'];
                                $WebPartnerFareBreakup = $FlightPrice['WebPartnerFareBreakup'];
                                $WebPMarkUp = isset($WebPartnerFareBreakup['WebPMarkUp']) ? $WebPartnerFareBreakup['WebPMarkUp'] : 0;
                                $WebPDiscount = isset($WebPartnerFareBreakup['WebPDiscount']) ? $WebPartnerFareBreakup['WebPDiscount'] : 0;
                                $WebPDisplayMarkup = isset($WebPartnerFareBreakup['DisplayMarkup']) ? $WebPartnerFareBreakup['DisplayMarkup'] : 'in_tax';
                                $TotalBaggagePrice = 0;
                                $TotalSeatPrice = 0;
                                $TotalMealPrice = 0;
                                $TotalGST = 0;
                                $perPaxDiscount = 0;
                                $perPaxOtherCharges = 0;
                                $perPaxTDS = 0;
                                $perPaxAgentCommission = 0;
                                $perPaxGST = 0;
                                $perPaxWebPartnermarkup = 0;
                                $perPaxWebPartnerdiscount = 0;
                                $is_price_changed = $fareconfirmationArray['IsPriceChanged'];
                                $FlightFareInfo = $fareconfirmationArray['Result']['Fare'];
                                $TotalGST = $FlightFareInfo['GST']['CGSTAmount'] + $FlightFareInfo['GST']['IGSTAmount'] + $FlightFareInfo['GST']['SGSTAmount'];
                                $perPaxGST = round_value(($TotalGST / $TotalPax));
                                $perPaxDiscount = round_value(($FlightFareInfo['Discount'] / $TotalPax));
                                $perPaxAgentCommission = round_value(($FlightFareInfo['AgentCommission'] / $TotalPax));
                                $perPaxTDS = round_value(($FlightFareInfo['TDS'] / $TotalPax));
                                $perPaxOtherCharges = round_value(($FlightFareInfo['OtherCharges'] / $TotalPax));
                                $perPaxWebPartnermarkup = round_value(($WebPMarkUp / $TotalPax));
                                $perPaxWebPartnerdiscount = round_value(($WebPDiscount / $TotalPax));
                                /*B2B Calculation */

                                $perPaxB2BAgentrdiscount = 0;
                                $AgentTotalGST = 0;
                                $perPaxAgentDiscount = 0;
                                $perPaxAgentOtherCharges = 0;
                                $perPaxAgentTDS = 0;
                                $perPaxB2BAgentCommission = 0;
                                $perPaxAgentGST = 0;
                                $perPaxAgentmarkup = 0;
                                $AgentTotalGST = $CustomerFare['GST']['CGSTAmount'] + $CustomerFare['GST']['IGSTAmount'] + $CustomerFare['GST']['SGSTAmount'];
                                $perPaxAgentGST = round_value(($AgentTotalGST / $TotalPax));
                                $perPaxAgentDiscount = round_value(($CustomerFare['Discount'] / $TotalPax));
                                $perPaxB2BAgentCommission = round_value(($CustomerFare['AgentCommission'] / $TotalPax));
                                $perPaxAgentTDS = round_value(($CustomerFare['TDS'] / $TotalPax));
                                $perPaxAgentOtherCharges = round_value(($CustomerFare['OtherCharges'] / $TotalPax));
                                /*B2B Calculation */
                                $common_data = json_decode($fareconfirmation['tts_index_response'], true);
                                $common_data = $common_data[$fare_confimation['ResultIndex']];
                                $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
                                $is_time_changed = false;
                                $savePaxinfo = array();
                                $insertPaxdata = array();
                                $couponAmount = 0;
                                $couponModel = new couponModel();
                                $appliedCouponCode = $couponModel->getCouponByToken($FlightConfirmationPaxInfo['SearchTokenId'], $this->web_partner_id);
                                if (!empty($appliedCouponCode)) {
                                    $AppliedcouponInfo = json_decode($appliedCouponCode['couponInfo'], true);
                                    if ($AppliedcouponInfo['coupon_type'] == 'fixed') {
                                        $couponAmount = $AppliedcouponInfo['value'];
                                    } else {
                                        $cAmount = ($AppliedcouponInfo['value'] * $TTS_Invoice_Amount) / 100;
                                        $couponAmount = ($cAmount > $AppliedcouponInfo['max_limit']) ? $AppliedcouponInfo['max_limit'] : $cAmount;
                                    }
                                    if ($couponAmount) {
                                        $NoofJourney = count($flightConfrimationData);
                                        $couponAmount = round_value(($couponAmount / $NoofJourney));
                                    }
                                }
                                if ($fareconfirmationArray['Error']['ErrorCode'] == 0) {
                                    foreach ($passenger_info as $paxsupkey => $passengers) {
                                        foreach ($passengers as $paxsubkey => $passenger) {
                                            $apipassengerData = array();
                                            $baggage = array();
                                            $meal = array();
                                            $seat = array();
                                            $baggage_charges = 0;
                                            $meal_charges = 0;
                                            $seat_charges = 0;
                                            if ($SelectedPassengerMealData) {
                                                if (isset($SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey]) && !empty($SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey])) {
                                                    $PassengermealDataInfos = $SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey];
                                                    if ($PassengermealDataInfos) {
                                                        foreach ($PassengermealDataInfos as $journeyKey => $PassengermealDataInfo) {
                                                            foreach ($PassengermealDataInfo as $segmentkey => $selectpaxMeal) {
                                                                if ($selectpaxMeal) {
                                                                    $selectpaxMealCode = explode('@@', $selectpaxMeal)[0];
                                                                    $segMentMealInfo = $MealData[$getTripIndicator][$journeyKey][$segmentkey];
                                                                    $MealCodeArray = array_column($segMentMealInfo, 'Code');
                                                                    $MealCodeIndexKey = array_search($selectpaxMealCode, $MealCodeArray);
                                                                    $selectedMeal = isset($segMentMealInfo[$MealCodeIndexKey]) ? $segMentMealInfo[$MealCodeIndexKey] : array();
                                                                    if ($selectedMeal) {
                                                                        array_push($meal, $selectedMeal);
                                                                        $meal_charges = $meal_charges + $selectedMeal['Price'];
                                                                        $TotalMealPrice = $TotalMealPrice + $selectedMeal['Price'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if ($SelectedPassengerBaggageData) {
                                                if (isset($SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey]) && !empty($SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey])) {
                                                    $PassengerBaggageDataInfos = $SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey];
                                                    if ($PassengerBaggageDataInfos) {
                                                        foreach ($PassengerBaggageDataInfos as $journeyKey => $PassengerBaggageDataInfo) {
                                                            foreach ($PassengerBaggageDataInfo as $segmentkey => $selectpaxBaggage) {
                                                                if ($selectpaxBaggage) {
                                                                    $selectpaxBaggageCode = explode('@@', $selectpaxBaggage)[0];
                                                                    $segMentBaggageInfo = $BaggageData[$getTripIndicator][$journeyKey][$segmentkey];
                                                                    $BaggageCodeArray = array_column($segMentBaggageInfo, 'Code');
                                                                    $BaggageCodeIndexKey = array_search($selectpaxBaggageCode, $BaggageCodeArray);
                                                                    $selectedBaggage = isset($segMentBaggageInfo[$BaggageCodeIndexKey]) ? $segMentBaggageInfo[$BaggageCodeIndexKey] : array();
                                                                    if ($selectedBaggage) {
                                                                        array_push($baggage, $selectedBaggage);
                                                                        $baggage_charges = $baggage_charges + $selectedBaggage['Price'];
                                                                        $TotalBaggagePrice = $TotalBaggagePrice + $selectedBaggage['Price'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if (isset($selectedSeatdata[$getTripIndicator][$paxsupkey][$paxsubkey])) {
                                                $seat_charges = $selectedSeatdata[$getTripIndicator][$paxsupkey][$paxsubkey]['Price'];
                                                $seat = $selectedSeatdata[$getTripIndicator][$paxsupkey][$paxsubkey]['seats'];
                                                $TotalSeatPrice = $TotalSeatPrice + $seat_charges;
                                            }
                                            if (isset($passenger['passport_no']) && $passenger['passport_no'] != '') {
                                                $PassportNo = $passenger['passport_no'];
                                            } else {
                                                $PassportNo = NULL;
                                            }
                                            if (isset($passenger['passport_issue_date']) && $passenger['passport_issue_date'] != '') {
                                                $PassportIssueDate = date("Y-m-d", strtotime($passenger['passport_issue_date'])) . "T00:00:00";
                                            } else {
                                                $PassportIssueDate = NULL;
                                            }
                                            if (isset($passenger['passport_expire_date']) && $passenger['passport_expire_date'] != '') {
                                                $PassportExpDate = date("Y-m-d", strtotime($passenger['passport_expire_date'])) . "T00:00:00";
                                            } else {
                                                $PassportExpDate = NULL;
                                            }
                                            if (isset($passenger['dob']) && $passenger['dob'] != '') {
                                                $dob = date("Y-m-d", strtotime($passenger['dob'])) . "T00:00:00";
                                            } else {
                                                $dob = NULL;
                                            }
                                            if (isset($passenger['pancard'])) {
                                                $pancard = $passenger['pancard'];
                                            } else {
                                                $pancard = NULL;
                                            }

                                            if (isset($passenger['documentid'])) {
                                                $documentnumber = $passenger['documentid'];
                                            } else {
                                                $documentnumber = NULL;
                                            }

                                            if (isset($passenger['nationality']) && $passenger['nationality'] != "") {
                                                $nationality = $passenger['nationality'];
                                            } else {
                                                $nationality = NULL;
                                            }
                                            $paxcode = get_paxtype_code($paxsupkey);
                                            $pax_fare_breakdown = $fare_confimation['FareBreakdown'][$paxcode];
                                            $base_fare = round_value($pax_fare_breakdown['BaseFare'] / $pax_fare_breakdown['PassengerCount']);
                                            $tax = round_value($pax_fare_breakdown['Tax'] / $pax_fare_breakdown['PassengerCount']);
                                            $coupon_amount = 0;
                                            $yq_tax = round_value($pax_fare_breakdown['YQTax'] / $pax_fare_breakdown['PassengerCount']);
                                            $service_charge = round_value($pax_fare_breakdown['ServiceCharges'] / $pax_fare_breakdown['PassengerCount']);
                                            if ($paxsupkey == "Infant") {
                                                $perPaxDiscount = 0;
                                                $perPaxTDS = 0;
                                                $perPaxAgentCommission = 0;
                                                $perPaxGST = 0;
                                                $perPaxOtherCharges = 0;
                                                $perPaxWebPartnermarkup = 0;
                                                $perPaxWebPartnerdiscount = 0;
                                            }
                                            $fare = array(
                                                'BaseFare' => $base_fare,
                                                'Tax' => $tax,
                                                'YQTax' => $yq_tax,
                                                'ServiceCharges' => $service_charge,
                                                'OtherCharges' => $perPaxOtherCharges,
                                                'Discount' => $perPaxDiscount,
                                                'AgentCommission' => $perPaxAgentCommission,
                                                'TDS' => $perPaxTDS,
                                                'WebPMarkUp' => $perPaxWebPartnermarkup,
                                                'WebPDiscount' => $perPaxWebPartnerdiscount,
                                                'GSTAmount' => $perPaxGST,
                                                'PublishedPrice' => round_value($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST - $coupon_amount),
                                                'OfferedPrice' => round_value(($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST) - ($perPaxAgentCommission + $perPaxDiscount + $coupon_amount)),
                                                'BaggageCharges' => $baggage_charges,
                                                'MealCharges' => $meal_charges,
                                                'SeatCharges' => $seat_charges,
                                                'CouponAmount' => $coupon_amount,
                                            );
                                            $pax_fare_breakdown_agent = $CustomerFareBreakdown[$paxcode];
                                            $base_fare_agent = round_value($pax_fare_breakdown_agent['BaseFare'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $tax_agent = round_value($pax_fare_breakdown_agent['Tax'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $yq_tax_agent = round_value($pax_fare_breakdown_agent['YQTax'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $coupon_amount_agent = round_value($couponAmount / $pax_fare_breakdown_agent['PassengerCount']);
                                            $service_charge_agent = round_value($pax_fare_breakdown_agent['ServiceCharges'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            if ($paxsupkey == "Infant") {
                                                $perPaxAgentDiscount = 0;
                                                $perPaxAgentOtherCharges = 0;
                                                $perPaxAgentTDS = 0;
                                                $perPaxB2BAgentCommission = 0;
                                                $perPaxAgentGST = 0;
                                                $perPaxAgentmarkup = 0;
                                                $perPaxB2BAgentrdiscount = 0;
                                            }
                                            $customer_fare = array(
                                                'BaseFare' => $base_fare_agent,
                                                'Tax' => $tax_agent,
                                                'YQTax' => $yq_tax_agent,
                                                'ServiceCharges' => $service_charge_agent,
                                                'OtherCharges' => $perPaxAgentOtherCharges,
                                                'Discount' => $perPaxAgentDiscount,
                                                'AgentCommission' => $perPaxB2BAgentCommission,
                                                'TDS' => $perPaxAgentTDS,
                                                'MarkUp' => $perPaxAgentmarkup,
                                                'AgentDiscount' => $perPaxB2BAgentrdiscount,
                                                'GSTAmount' => $perPaxAgentGST,
                                                'PublishedPrice' => round_value($base_fare_agent + $tax_agent + $service_charge_agent + $perPaxAgentOtherCharges + $perPaxAgentGST - $coupon_amount_agent),
                                                'OfferedPrice' => round_value(($base_fare_agent + $tax_agent + $service_charge_agent + $perPaxAgentOtherCharges + $perPaxAgentGST) - ($perPaxB2BAgentCommission + $perPaxB2BAgentrdiscount + $coupon_amount_agent)),
                                                'BaggageCharges' => $baggage_charges,
                                                'MealCharges' => $meal_charges,
                                                'SeatCharges' => $seat_charges,
                                                'CouponAmount' => $coupon_amount_agent,
                                            );
                                            $gender = pax_title_type($passenger['title']);
                                            $apipassengerData = array(
                                                "title" => $passenger['title'],
                                                "first_name" => $passenger['first_name'],
                                                "last_name" => $passenger['last_name'],
                                                "pax_type" => type_adt($paxsupkey),
                                                "gendar" => $gender == 1 ? "male" : "female",
                                                "date_of_birth" => $dob,
                                                "pan_number" => $pancard,
                                                "passport_number" => $PassportNo,
                                                "document_number" => $documentnumber,
                                                "passport_expiry" => $PassportExpDate,
                                                "passport_issue_date" => $PassportIssueDate,
                                                "lead_pax" => $paxsupkey == "Adult" && $paxsubkey == "1" ? true : false,
                                                "email_id" => $FlightConfirmationPaxInfo['email'],
                                                "mobile_number" => $FlightConfirmationPaxInfo['mobile_number'],
                                                "address_1" => (isset($this->wl_customer_info['address']) && $this->wl_customer_info['address'] != null) ? $this->wl_customer_info['address'] : $this->web_partner_details['address'],
                                                "address_2" => "",
                                                "city" => (isset($this->wl_customer_info['city']) && $this->wl_customer_info['city'] != null) ? $this->wl_customer_info['city'] : $this->web_partner_details['city'],
                                                "country_code" => "IN",
                                                "nationality" => $nationality,
                                                "country_name" => (isset($this->wl_customer_info['country']) && $this->wl_customer_info['country'] != null) ? $this->wl_customer_info['country'] : $this->web_partner_details['country'],
                                                "ff_airline" => isset($passenger['frequent_fly_airline']) ? $passenger['frequent_fly_airline'] : "",
                                                "ff_number" => isset($passenger['frequent_fly_number']) ? $passenger['frequent_fly_number'] : "",
                                                "fare" => json_encode($fare),
                                                "customer_fare" => json_encode($customer_fare),
                                                "baggage" => !empty($baggage) ? json_encode($baggage) : null,
                                                "meal" => !empty($meal) ? json_encode($meal) : null,
                                                "seat" => !empty($seat) ? json_encode($seat) : null,
                                                'booking_status' => "Processing"
                                            );
                                            array_push($insertPaxdata, $apipassengerData);
                                        }
                                    }
                                    $leadPax = $insertPaxdata[0]['title'] . " " . $insertPaxdata[0]['first_name'] . " " . $insertPaxdata[0]['last_name'];
                                    $WebPartnerFareBreakup = $common_data['WebPartnerFareBreakup'];
                                    if ($WebPartnerFareBreakup) {
                                        $WebPartnerFareBreakup['WebPMarkUp'] = $WebPMarkUp;
                                        $WebPartnerFareBreakup['WebPDiscount'] = $WebPDiscount;
                                        $WebPartnerFareBreakup['WebPDisplayMarkup'] = $WebPDisplayMarkup;
                                    }
                                    $WebPartnerFareBreakup['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $WebPartnerFareBreakup['TotalMealCharges'] = $TotalMealPrice;
                                    $WebPartnerFareBreakup['TotalSeatCharges'] = $TotalSeatPrice;
                                    $CustomerFare['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $CustomerFare['TotalMealCharges'] = $TotalMealPrice;
                                    $CustomerFare['TotalSeatCharges'] = $TotalSeatPrice;
                                    $common_data['SuperAdminFareBreakup']['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $common_data['SuperAdminFareBreakup']['TotalMealCharges'] = $TotalMealPrice;
                                    $common_data['SuperAdminFareBreakup']['TotalSeatCharges'] = $TotalSeatPrice;
                                    $TTS_Invoice_Amount = floatval($CustomerFare['OfferedPrice']) + floatval($TotalMealPrice) + floatval($TotalBaggagePrice) + floatval($TotalSeatPrice);
                                    $Customer_TTS_Invoice_Amount = floatval($CustomerFare['OfferedPrice']) + floatval($TotalMealPrice) + floatval($TotalBaggagePrice) + floatval($TotalSeatPrice);
                                    $web_partner_booking_total_price = floatval($FlightFareInfo['OfferedPrice']) + floatval($FlightFareInfo['TDS']) + floatval($TotalMealPrice) + floatval($TotalBaggagePrice) + floatval($TotalSeatPrice);
                                    $couponInfo = array();
                                    if (!empty($appliedCouponCode)) {
                                        $couponInfo = array(
                                            'couponCode' => $AppliedcouponInfo['coupon_code'],
                                            'couponAmount' => $couponAmount,
                                            'beforeAmount' => $TTS_Invoice_Amount,
                                            'AfterAmount' => ($TTS_Invoice_Amount - $couponAmount)
                                        );
                                    } else {
                                        $couponModel->remove_promo_log($tts_search_token, $this->web_partner_id);
                                    }
                                    $TTS_Invoice_Amount -= $couponAmount;
                                    $Customer_TTS_Invoice_Amount = $Customer_TTS_Invoice_Amount - $couponAmount;

                                    $CustomerFare['CustomerTTSInvoiceAmount'] = $Customer_TTS_Invoice_Amount;
                                    $CustomerFare['couponAmount'] = $couponAmount;
                                    $web_partner_commission = $FlightFareInfo['AgentCommission'] + $FlightFareInfo['Discount'];
                                    $super_admin_commission = $common_data['SuperAdminFareBreakup']['Discount'];
                                    $customer_commission = $CustomerFare['Discount'] + $CustomerFare['AgentCommission'];


                                    $selectedWebsiteCurrency  = isset($_SESSION['selected_website_currency']) ? $_SESSION['selected_website_currency'] : null;
                                    $website_currencies  = isset($_SESSION['website_currencies']) ? $_SESSION['website_currencies'] : [];
                                    $selectedcurrencyCode = isset($selectedWebsiteCurrency['currency']) ? $selectedWebsiteCurrency['currency'] : null;
                                    $selectedCoversionRate = isset($selectedWebsiteCurrency['convertion_rate']) ? $selectedWebsiteCurrency['convertion_rate'] : 1;
                                    $currencyArray  = !empty($website_currencies) ? array_column($website_currencies, 'currency', 'default_currency') : [];



                                    $savePaxinfo = array(
                                        'tts_search_token' => $tts_search_token,
                                        'web_partner_id' => $this->web_partner_id,
                                        'is_price_changed' => $is_price_changed,
                                        'is_time_changed' => $is_time_changed,
                                        'trip_indicator' => $fareconfirmationKey == "OB" ? 1 : 2,
                                        'search_request' => json_encode($searchRequest),
                                        'journey_type' => get_journey_type($searchRequest['JourneyType']),
                                        'origin' => $origin,
                                        'resultIndex' => $fare_confimation['ResultIndex'],
                                        'destination' => $destination,
                                        'departure_date' => $departure_date,
                                        'is_domestic' => $searchRequest['IsDomestic'],
                                        'is_lcc' => $fare_confimation['IsLCC'],
                                        'is_refundable' => $fare_confimation['IsRefundable'],
                                        'fare_type' => $fare_confimation['FareType'],
                                        'airline_code' => $airline_code,
                                        'validating_airline_code' => $fare_confimation['ValidatingAirline'],
                                        'last_ticket_date' => $fare_confimation['LastTicketDate'],
                                        'airline_remark' => $fare_confimation['AirlineRemark'],
                                        'segments' => json_encode($fare_confimation['Segments']),
                                        'api_supplier' => $common_data['Supplier'],
                                        'coupon_info' => json_encode($couponInfo),
                                        'payment_mode' => 'Online',
                                        'is_manual' => false,
                                        'lead_pax' => $leadPax,
                                        'payment_status' => 'Processing',
                                        'booking_status' => 'Processing',
                                        'gst_info' => isset($data['gst']) ? json_encode($data['gst']) : $gst_info,
                                        'super_admin_fare_break_up' => json_encode($common_data['SuperAdminFareBreakup']),
                                        'web_partner_fare_break_up' => json_encode($WebPartnerFareBreakup),
                                        'customer_fare_break_up' => json_encode($CustomerFare),
                                        'super_admin_commision' => $super_admin_commission,
                                        'web_partner_commision' => $web_partner_commission,
                                        'customer_commision' => $customer_commission,
                                        'booking_channel' => 'Desktop',
                                        'total_price' => $TTS_Invoice_Amount,
                                        'wl_customer_id' => $this->wl_customer_id,
                                        'booking_source' => "Wl_b2c",
                                        'web_partner_booking_total_price' => $web_partner_booking_total_price,
                                        'web_partner_payment_status' => 'Processing',
                                        'is_gst_mandatory' => $fare_confimation['IsGSTMandatory'],
                                        'is_gst_allowed' => $fare_confimation['GSTAllowed'],
                                        'passenger_info' => $insertPaxdata,
                                        'booking_currency' => $selectedcurrencyCode,
                                        'currency_rate' => $selectedCoversionRate,
                                        'default_currency' => $currencyArray['active'],
                                        'created' => create_date()
                                    );
                                    $SaveDataArray[$fareconfirmationKey] = $savePaxinfo;
                                } else {
                                    $ErrorCode = $fareconfirmationArray['Error']['ErrorCode'];
                                    $ErrorMessage = $fareconfirmationArray['Error']['ErrorMessage'];
                                    $flightConfrimationResponseData = array();
                                    $SaveDataArray = array();
                                    break;
                                }
                            } else {
                                $ErrorCode = 400;
                                $ErrorMessage = "No Result Found.";
                                $SaveDataArray = array();
                                break;
                            }
                        }
                    } else {
                        $ErrorCode = 400;
                        $ErrorMessage = "No Result Found.";
                        $SaveDataArray = array();
                    }
                    if ($ErrorCode == 0 && $SaveDataArray) {
                        $bookingIdArray = array();
                        $bookingRefNumberArray = array();
                        foreach ($SaveDataArray as $savekey => $flight_booking) {
                            $passengerInfo = $flight_booking['passenger_info'];
                            unset($flight_booking['passenger_info']);
                            $booking_id = $FlightModel->insertData('flight_booking_list', $flight_booking);
                            $bookingIdArray[$savekey] = $booking_id;
                            $InsertDatapassengerInfo = array_map(function ($value, $flightBookingId) {
                                $value['flight_booking_id'] = $flightBookingId;
                                return $value;
                            }, $passengerInfo, array_fill(0, (count($passengerInfo)), $booking_id));
                            $FlightModel->insertBatchData('flight_booking_travelers', $InsertDatapassengerInfo);
                            /*------------------ Update Booking  Data ----------------------------*/
                            $super_admin__booking_pre_fix_code = $FlightModel->service_booking_pre_fix_code($this->web_partner_id)['pre_fix'];
                            $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_id;
                            $bookingRefNumberArray[$savekey] = $booking_ref_number;

                            $booking_update_data = array(
                                'booking_ref_number' => $booking_ref_number,
                            );
                            $FlightModel->updateUserData('flight_booking_list', ['id' => $booking_id], $booking_update_data);
                            /*------------------ Update BookingData ----------------------------*/
                        }
                        if (!empty($couponInfo)) {
                            $FlightModel->updateUserData('coupon_log', ['web_partner_id' => $this->web_partner_id, 'token' => $tts_search_token], ['booking_ref_number' => implode(',', $bookingRefNumberArray)]);
                        }
                        $paymentkey = dev_encode(json_encode(array('service' => 'flight', 'booking_id' => $bookingIdArray, "SearchTokenId" => $tts_search_token, 'booking_ref_number' => $bookingRefNumberArray)));
                        $url = site_url('payment/opt/') . $paymentkey;
                        return array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $url);
                    } else {
                        return array("StatusCode" => 9, "ErrorMessage" => $ErrorMessage ? $ErrorMessage : "Technical problem occured");
                    }
                } else {
                    return array("StatusCode" => 9, "ErrorMessage" => "Technical problem occured");
                }
            } else {
                return array("StatusCode" => 9, "ErrorMessage" => "Request Not Allowed");
            }
        } else {
            return array("StatusCode" => 9, "ErrorMessage" => "Request Not Allowed");
        }
    }

    function paymentStatus()
    {
        $uri = service('uri');
        $payment_token = $uri->getSegment(3);
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return $this->response->redirect(site_url('flight/error?errormessage=Payment Record not found'));
        } else {
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\FlightBookingtemplate\payment_redirect",
                'payment_token' => $payment_token,
                'paymentdata' => $paymentdata
            ];
            return view('template/default-layout', $data);
        }
    }

    function flightBooking()
    {
        $flightbookRequest = $this->request->getPOST();
        if ($flightbookRequest) {
            $FlightModel = new FlightModel();
            $payment_token = $flightbookRequest['payment_token'];
            $paymentdata = json_decode(dev_decode($payment_token), true);
            if ($paymentdata) {
                $bookingIds = explode(",", $paymentdata['booking_id']);
                $bookingRefNo = array();
                $bookingRefNo = $bookingIds;
                $bookingData = array();
                if ($bookingIds) {
                    foreach ($bookingIds as $bookingId) {
                        $bookingInfo = $FlightModel->getBookingData($bookingId, $this->web_partner_id);
                        $wl_extra_info = json_decode($bookingInfo['wl_extra_info'], true);
                        if (isset($bookingInfo['id']) && $bookingInfo['id'] == $bookingId) {
                            if ($bookingInfo['booking_status'] == "Processing" && $bookingInfo['book_request'] != "requested" && $bookingInfo['payment_status'] == "Successful") {
                                $bookingData[] = $bookingInfo;
                            } else {
                                $bookingData = array();
                                break;
                            }
                        } else {
                            $bookingData = array();
                            break;
                        }
                    }
                }
                if ($bookingData) {
                    $couponData = json_decode($bookingData[0]['coupon_info'], true);
                    $couponModel = new couponModel();
                    if (!empty($couponData) && isset($couponData['couponCode'])) {
                        $couponInfo = $couponModel->getData('coupon_flight', ['code' => $couponData['couponCode'], 'status' => 'active', 'web_partner_id' => $this->web_partner_id], 'use_limit');
                        if (isset($couponInfo['use_limit'])) {
                            $couponModel->updateData('coupon_flight', ['code' => $couponData['couponCode'], 'status' => 'active', 'web_partner_id' => $this->web_partner_id], ['use_limit' => $couponInfo['use_limit'] - 1]);
                        }
                    }
                    $ErrorCode = 0;
                    $ErrorMessage = "";
                    foreach ($bookingData as $bookingInfo) {
                        $bookingId = $bookingInfo['id'];
                        $bookingRefNumber = $bookingInfo['booking_ref_number'];
                        $rtype = $bookingInfo['trip_indicator'] == 1 ? "OB" : "IB";
                        $passengerInfo = json_decode($bookingInfo['travelersInfo'], true);
                        $gst_info = json_decode($bookingInfo['gst_info'], true);
                        $MobileNumber = $passengerInfo[0]['mobile_number'];
                        $EmailId = $passengerInfo[0]['email_id'];
                        $bookingPaxdata = array();
                        foreach ($passengerInfo as $passenger) {
                            $FFAirline = "";
                            $FFNumber = "";
                            if (!$bookingInfo['is_lcc']) {
                                $FFAirline = $passenger['ff_airline'];
                                $FFNumber = $passenger['ff_number'];
                            }
                            $apipassengerData = array(
                                "Title" => $passenger['title'],
                                "FirstName" => $passenger['first_name'],
                                "LastName" => $passenger['last_name'],
                                "PaxType" => type_adt($passenger['pax_type']),
                                "DateOfBirth" => $passenger['date_of_birth'],
                                "Gender" => $passenger['gendar'] == 'male' ? 1 : 2,
                                "PassportNo" => $passenger['passport_number'],
                                "PassportExpiry" => $passenger['passport_expiry'],
                                "PassportIssue" => $passenger['passport_issue_date'],
                                "PAN" => $passenger['pan_number'],
                                "DocumentNumber" => $passenger['document_number'],
                                "AddressLine1" => substr($passenger['address_1'], 0, 28),
                                "AddressLine2" => $passenger['address_2'],
                                "City" => $passenger['city'],
                                "CountryCode" => $passenger['country_code'],
                                "CountryName" => $passenger['country_name'],
                                "ContactNo" => $passenger['mobile_number'],
                                "Email" => $passenger['email_id'],
                                "Nationality" => $passenger['nationality'],
                                "IsLeadPax" => $passenger['lead_pax'],
                                "FFAirline" => $FFAirline,
                                "FFNumber" => $FFNumber,
                                "Baggage" => isset($passenger['baggage']) && !empty($passenger['baggage']) ? json_decode($passenger['baggage'], true) : null,
                                "Meal" => isset($passenger['meal']) && !empty($passenger['meal']) ? json_decode($passenger['meal'], true) : null,
                                "Seat" => isset($passenger['seat']) && !empty($passenger['seat']) ? json_decode($passenger['seat'], true) : null,

                            );
                            if ($bookingInfo['is_gst_allowed']) {
                                $apipassengerData['GSTCompanyAddress'] = isset($gst_info['address']) ? $gst_info['address'] : null;
                                $apipassengerData['GSTCompanyContactNumber'] = isset($gst_info['phone']) ? $gst_info['phone'] : null;
                                $apipassengerData['GSTCompanyEmail'] = isset($gst_info['email']) ? $gst_info['email'] : null;
                                $apipassengerData['GSTCompanyName'] = isset($gst_info['name']) ? $gst_info['name'] : null;
                                $apipassengerData['GSTNumber'] = isset($gst_info['number']) ? $gst_info['number'] : null;
                            } else {
                                $apipassengerData['GSTCompanyAddress'] = null;
                                $apipassengerData['GSTCompanyContactNumber'] = null;
                                $apipassengerData['GSTCompanyEmail'] = null;
                                $apipassengerData['GSTCompanyName'] = null;
                                $apipassengerData['GSTNumber'] = null;
                            }
                            array_push($bookingPaxdata, $apipassengerData);
                        }
                        $bookRequest = array(
                            "UserIp" => $this->request->getIpAddress(),
                            "SearchTokenId" => $bookingInfo['tts_search_token'],
                            "ResultIndex" => $bookingInfo['resultIndex'],
                            "Passengers" => $bookingPaxdata,

                        );
                        $service = "book";
                        $url = $this->Services . $service;
                        $response = TTSRequest($bookRequest, $url, $service);
                        if ($response) {
                            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                                $ErrorCode = $response['Error']['ErrorCode'];
                                $ErrorMessage = $response['Error']['ErrorMessage'];
                                $updateData = array("book_request" => "requested");
                                $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id);
                                $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                                $getBookingInfo = $FlightModel->getData("flight_booking_list", $whereCondition, "booking_status,booking_ref_number,agent_fare_break_up,wl_agent_id,gst_info,pnr,customer_fare_break_up,wl_customer_id");
                                if ($getBookingInfo['booking_status'] == 'Confirmed') {
                                    $agent_fare_break_up = json_decode($getBookingInfo['customer_fare_break_up'], true);
                                    $gst_info = json_decode($getBookingInfo['gst_info'], true);
                                    $GstNumber = isset($data['number']) && $gst_info['number'] != "" ? $gst_info['number'] : "";
                                    $checkTaxableInvoce = checkTaxableNonTaxableINV($agent_fare_break_up, $GstNumber, 'flight', 'INV');
                                    $INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'flight');
                                    $financialYear = get_financial_year();
                                    $whereCondition = array();
                                    $whereCondition['service'] = 'flight';
                                    $whereCondition['web_partner_id'] = $this->web_partner_id;
                                    $whereCondition['invoice_type'] = 'INV';
                                    $whereCondition['financial_year'] = $financialYear;
                                    $otherParameter['financialYear'] = $financialYear;
                                    $otherParameter['service'] = 'flight';
                                    $otherParameter['invoice_type'] = 'INV';
                                    $otherParameter['INVPrifix'] = $INVPrifix;
                                    $otherParameter['web_partner_id'] = $this->web_partner_id;
                                    $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
                                    $CommonModel = new CommonModel();
                                    $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                                    $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                                    $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                                    $InvoiceupdateData = $InvoiceInfoData['updateData'];
                                    $agent_account_log = $FlightModel->getData("customer_account_log", ['booking_ref_no' => $bookingId, "web_partner_id" => $this->web_partner_id, "action_type" => "booking", "transaction_type" => "debit", "customer_id" => $getBookingInfo['wl_customer_id']], "service_log");
                                    $service_log = json_decode($agent_account_log['service_log'], true);
                                    $service_log['TicketNo'] = $getBookingInfo['pnr'];
                                    $FlightModel->updateUserData('customer_account_log', ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "customer_id" => $getBookingInfo['wl_customer_id'], "web_partner_id" => $this->web_partner_id], ["invoice_number" => $InvoiceNumber, "service_log" => json_encode($service_log)]);
                                    $FlightModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                                }
                            } else {
                                $ErrorCode = $response['Error']['ErrorCode'];
                                $ErrorMessage = $response['Error']['ErrorMessage'];
                                $updateData = array("book_request" => "requested");
                                $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id);
                                $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                            }
                            if ($ErrorCode != 0) {
                                break;
                            }
                        } else {
                            $ErrorCode == 400;
                            $ErrorMessage = "Request Not Allowed";
                            $updateData = array("book_request" => "requested");
                            $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id);
                            $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                            break;
                        }
                    }
                    if ($rtype == "OB" && $ErrorCode != 0) {
                        $sms_type = "Flight Booking Failed";
                        $tempid = $this->SmsTemplate['FlightBookingFailed'];
                        $Smsmessage = "Dear Travel Partner, Your Booking with Id " . $bookingRefNumber . " has failed. In case of any issues, kindly contact the support team on +918882233322 or support@tourista.in";
                        /*         send_sms($MobileNumber, $Smsmessage, $tempid, $sms_type); */
                        return $this->response->redirect(site_url('flight/error?errormessage=' . $ErrorMessage));
                    } else {
                        $ticketData = dev_encode(json_encode($bookingRefNo));
                        return $this->response->redirect(site_url('flight/confirmation/' . $ticketData . "?type=Booking"));
                    }
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }


    function error()
    {
        $error = $this->request->getGET('errormessage');
        return view('template/custom-error-layout', ['error_message' => $error]);
    }

    function confirmation()
    {
        $uri = service('uri');
        $bookingIds = $uri->getSegment(3);
        $bookingIds = json_decode(dev_decode($bookingIds), true);
        $FlightModel = new FlightModel();
        $bookingConfrimationData = array();
        $ConfrimationData = array();
        if ($bookingIds) {
            $onwardPnr = "";
            $returnPnr = "";
            $returnBookingRefNumber = "";
            $onwardBookingRefNumber = "";
            $airlineLogoClass = "domAirLogo";
            $BookingStatus = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareBreakUpDataArray = array();
            $TicketOption = array();
            $InvoiceOption = array();
            $smsCount = 0;
            foreach ($bookingIds as $bookingId) {
                $paymentGatewayInfo = array();
                $FareBreakUp = array();
                $bookingInfo = $FlightModel->getBookingConfirmationData($bookingId, $this->web_partner_id);

                $rtype = $bookingInfo['trip_indicator'] == 1 ? "OB" : "IB";
                $searchData = json_decode($bookingInfo['search_request'], true);
                $segments = json_decode($bookingInfo['segments'], true);
                $FlightNumber = $bookingInfo['airline_code'] . "-" . $segments[0][0]['Airline']['FlightNumber'];
                $childCount = $searchData['Child'];
                $infantCount = $searchData['Infant'];
                $TravelersInfo = json_decode($bookingInfo['travelersInfo'], true);
                $Name = $TravelersInfo[0]['title'] . " " . $TravelersInfo[0]['first_name'] . " " . $TravelersInfo[0]['last_name'];
                $MobileNumber = $TravelersInfo[0]['mobile_number'];
                $EmailId = $TravelersInfo[0]['email_id'];
                if ($bookingInfo['paymentgatewayDetail']) {
                    /*    $paymentGatewayInfo[$rtype] =  array(
                                  "PaymentStatus"=>$bookingInfo['paymentgatewayDetail']['payment_status'],
                                  "OrderId"=>$bookingInfo['paymentgatewayDetail']['order_id'],
                                  "Amount"=>$bookingInfo['paymentgatewayDetail']['amount'],

                       ); */
                }
                if ($bookingInfo['is_domestic']) {
                    $airlineLogoClass = "domAirLogo";
                } else {
                    $airlineLogoClass = "intAirLogo";
                }
                if ($bookingInfo['trip_indicator'] == 1) {
                    $onwardPnr = $bookingInfo['pnr'];
                    $onwardBookingRefNumber = $bookingInfo['booking_ref_number'];
                    $onwardBookingstatus = $bookingInfo['booking_status'];
                    $BookingStatus[] = $onwardBookingstatus;
                    if ($onwardPnr) {
                        $Pnr[] = $onwardPnr;
                        if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic']) {
                            $TicketOption[] = "Onward";
                            $InvoiceOption[] = "Onward";
                        }
                    }
                    $BookingRefNumber[] = $onwardBookingRefNumber;
                } else {
                    $returnPnr = $bookingInfo['pnr'];
                    $returnBookingRefNumber = $bookingInfo['booking_ref_number'];
                    $returnBookingstatus = $bookingInfo['booking_status'];
                    $BookingStatus[] = $returnBookingstatus;
                    if ($returnPnr) {
                        $Pnr[] = $returnPnr;
                        if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic']) {
                            $TicketOption[] = "Return";
                            $TicketOption[] = "Both";
                            $InvoiceOption[] = "Return";
                        }
                    }
                    $BookingRefNumber[] = $returnBookingRefNumber;
                }
                $convertFareBreakUp = json_decode($bookingInfo['customer_fare_break_up'], true);

                $convertBookingCurrencyRate =  convertBookingCurrencyRate($convertFareBreakUp, $bookingInfo['booking_currency'], $bookingInfo['default_currency'], $bookingInfo['currency_rate']);

                $fareBreakupArray = $convertBookingCurrencyRate['ConvertPrice'];
                $discount = $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'];
                $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                $couponAmount = isset($fareBreakupArray['couponAmount']) ? $fareBreakupArray['couponAmount'] : 0;
                $convenienceFee = isset($fareBreakupArray['convenienceFee']) ? $fareBreakupArray['convenienceFee'] : 0;
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                        "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"),
                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                    ),
                    "BookingId" => $bookingInfo['id']
                );

                if ($discount) {
                    $FareBreakUp['FareBreakup']['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
                }
                if (isset($fareBreakupArray['couponAmount']) && $fareBreakupArray['couponAmount']) {
                    $FareBreakUp['FareBreakup']['couponAmount'] = array("Value" => round_value($fareBreakupArray['couponAmount']), "LabelText" => "Promo Code Discount (-)");
                }
                if (isset($fareBreakupArray['convenienceFee']) && $fareBreakupArray['convenienceFee']) {
                    $FareBreakUp['FareBreakup']['convenienceFee'] = array("Value" => round_value($fareBreakupArray['convenienceFee']), "LabelText" => "Convenience Fee");
                }

                $FareBreakUp['TotalAmount'] = array("Value" => round_value(($fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge + $convenienceFee - $couponAmount)), "LabelText" => "Pay  Amount");
                $FareBreakUp['CurrencySymbol'] = $convertBookingCurrencyRate['CurrencySymbol'];
                $FareBreakUpDataArray[$rtype] = $FareBreakUp;
                $ConfirmationBookingData = array(
                    'Segments' => json_decode($bookingInfo['segments'], true),
                    "FareRule" => json_decode($bookingInfo['fare_rule'], true),
                    "IsRefundable" => $bookingInfo['is_refundable'],
                    "PaymentStatus" => $bookingInfo['payment_status'],
                    "BookingStatus" => $bookingInfo['booking_status'],
                    "BookingRefNumber" => $bookingInfo['booking_ref_number'],
                    "BookingId" => $bookingInfo['id'],
                    "FareType" => $bookingInfo['fare_type'],
                    "TravelersInfo" => json_decode($bookingInfo['travelersInfo'], true),
                );

                /*       pr($FareBreakUpDataArray);exit; */

                $bookingConfrimationData[$rtype] = $ConfirmationBookingData;
                if (isset($_GET['type']) && $_GET['type'] == "Booking") {
                    $smsBookingStatus = $bookingInfo['booking_status'];
                    $smsbookingRefNumber = $bookingInfo['booking_ref_number'];
                    $tempid = $this->SmsTemplate['FlightBookingFailed'];
                    $Smsmessage = "Dear Travel Partner, Your Booking with Id " . $smsbookingRefNumber . " has Processing. In case of any issues, kindly contact the support team on +918882233322 or support@tourista.in";
                    $sms_type = "Flight Booking";
                    if ($smsBookingStatus == "Confirmed") {
                        $sms_type = "Flight Booking Confirmed";
                        $tempid = $this->SmsTemplate['FlightBookingConfirm'];
                        $Smsmessage = "Dear " . $Name . ",Thanks for booking with " . web_partner_details['company_name'] . ". BookingRef-" . $smsbookingRefNumber . ",Service-" . "Flight,PNR-" . $bookingInfo['pnr'] . ",FltNum-" . $FlightNumber . ",Route-" . $bookingInfo['origin'] . "-" . $bookingInfo['destination'] . "|Trip Date-" . display_custom_date_format($bookingInfo['departure_date']);
                    }
                    if ($smsBookingStatus == "Hold") {
                        $sms_type = "Flight Booking Hold";
                        $tempid = $this->SmsTemplate['FlightBookingHold'];
                        $Smsmessage = "Dear Travel Partner, your Booking with Id " . $smsbookingRefNumber . " is on hold. In case of any issues, kindly contact the support team on +91-8882233322 or support@tourista.in";
                    }
                    if ($smsBookingStatus == "Confirmed" || $smsBookingStatus == "Hold") {
                        $checkSmssendingStatus = $FlightModel->getData("logs_sms", array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"), "id");
                        if (empty($checkSmssendingStatus)) {
                            /* send_sms($MobileNumber, $Smsmessage, $tempid, $sms_type, array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"));
                             */
                        }
                        $checkEmailsendingStatus = $FlightModel->getData("logs_email", array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"), "id");
                    }
                    if (empty($checkEmailsendingStatus)) {
                        if ($smsBookingStatus == "Confirmed" || $smsBookingStatus == "Hold") {
                            $TicketViewRequest = array(
                                "BookingId" => array($bookingInfo['id']),
                                "SearchTokenId" => $bookingInfo['tts_search_token'],
                                "HtmlType" => "Ticket",
                                "UserType" => "wl-customer",
                                "ViewService" => "Email",
                                "WithPrice" => 0,
                                "WithAgencyDetail" => 1,
                                "TicketInvoiceJourney" => $bookingInfo['trip_indicator'] == 1 ? "Onward" : "Return",
                                "ViewSize" => "",
                                "RequestBy" => "WebPartner"
                            );
                            $url = $this->Services . 'generate-wl-ticket-invoice';
                            $response = RequestWithoutAuth($TicketViewRequest, $url);

                            $emailMessage = $response['Result']['Html'];
                            $EmailType = $sms_type;
                            send_email($EmailId, $Smsmessage, $emailMessage, $EmailType, null, array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"));
                        }
                    }
                }
            }
            $pnrString = "";
            if (!empty($Pnr)) {
                $pnrString = implode(',', $Pnr);
            }
            $ConfrimationData = array("ConfirmationBookingData" => $bookingConfrimationData, "childCount" => $childCount, "infantCount" => $infantCount, "airlineLogoClass" => $airlineLogoClass, "bookingRefNumber" => $BookingRefNumber, "pnr" => $Pnr, "bookingStatus" => $BookingStatus, "bookingStatusString" => implode(',', $BookingStatus), "bookingRefNumberString" => implode(',', $BookingRefNumber), "pnrString" => $pnrString, "FareBreakUpData" => $FareBreakUpDataArray, "TicketOption" => $TicketOption, "InvoiceOption" => $InvoiceOption, "paymentGatewayInfo" => $paymentGatewayInfo);
        }






        $data = [
            'title' => $this->title,
            'bookingConfrimationData' => $ConfrimationData,
            'view' => "Flight\Views\FlightBookingtemplate\booking-confirmation-page",
        ];



        // new code to store data in email_logs table starts here 

        if ($bookingInfo['journey_type'] == 'RoundTrip') {
            $travelersInfoKeys = array(
                '0' => 'OB',
                '1' => 'IB',
            );
        } else {
            $travelersInfoKeys = array(
                '0' => 'OB',
            );
        }
     /*    pr($ConfrimationData['bookingRefNumber']);exit; */
        foreach ($ConfrimationData['bookingRefNumber'] as $confirmatioinKey => $confirmatioinValue) {
            $TravelerDataDetail = $ConfrimationData['ConfirmationBookingData'][$travelersInfoKeys[$confirmatioinKey]]['TravelersInfo'][0];
            $TripFligtDataOrigin = $ConfrimationData['ConfirmationBookingData'][$travelersInfoKeys[$confirmatioinKey]]['Segments'][0][0]['Origin'];
            $TripFligtDataDestinationArray = end($ConfrimationData['ConfirmationBookingData'][$travelersInfoKeys[$confirmatioinKey]]['Segments'][0]);
            $TripFligtDataDestination = $TripFligtDataDestinationArray['Destination'];
            $Origindate = new \DateTime($TripFligtDataOrigin['DepartTime'], new \DateTimeZone('UTC'));
            $timestampOrigin = $Origindate->getTimestamp();
            $Destinationdate = new \DateTime($TripFligtDataDestination['ArrivalTime'], new \DateTimeZone('UTC'));
            $timestampDestination = $Destinationdate->getTimestamp();
            $bookingData[] = array(
                'BookingRefrenceNumber' => $confirmatioinValue,
                'BookingPNR' => isset($ConfrimationData['pnr'][$confirmatioinKey]) ? $ConfrimationData['pnr'][$confirmatioinKey] : '',
                'BookingStatus' => $ConfrimationData['bookingStatus'][$confirmatioinKey],
                'Origin' => $TripFligtDataOrigin,
                'Destination' => $TripFligtDataOrigin,
                'PackageStartDate' =>  $timestampOrigin,
                'PackageEndsDate' => $timestampDestination,
                'To_email' => $TravelerDataDetail['email_id'],

            );
        }









        foreach ($bookingData as $bookingDataKey => $bookingDataValue) {
            $dataURL = array(
                'BookingRefrenceNumber' => $bookingDataValue['BookingRefrenceNumber'],
                'service' => "flight",
            );



            $dataForMailDocument['service'] = isset($dataURL['service']) ? ucfirst($dataURL['service']) : '';
            $dataForMailDocument['url'] = site_url('booking-review/' . dev_encode(implode(",", $dataURL)));
            $dataForMailDocument['PassengerName'] = isset($TravelerDataDetail['title']) ? $TravelerDataDetail['title'] . ' ' : '';
            $dataForMailDocument['PassengerName'] .= isset($TravelerDataDetail['first_name']) ? $TravelerDataDetail['first_name'] . ' ' : '';
            $dataForMailDocument['PassengerName'] .= isset($TravelerDataDetail['last_name']) ? $TravelerDataDetail['last_name'] : '';
            $dataForMailDocument['createdDate'] = isset($bookingInfo['created']) ? $bookingInfo['created'] : '';
            $dataForMailDocument['TravelStartDate'] = isset($bookingDataValue['PackageStartDate']) ? $bookingDataValue['PackageStartDate'] : '';
            $dataForMailDocument['BookingRefrenceNumber'] = isset($bookingDataValue['BookingRefrenceNumber']) ? $bookingDataValue['BookingRefrenceNumber'] : '';
            $dataForMailDocument['logo'] = isset($this->web_partner_details['company_logo']) ? $this->web_partner_details['company_logo'] : '';
            $dataForMailDocument['company_name'] = isset($this->web_partner_details['company_name']) ? $this->web_partner_details['company_name'] : '';
            $dataForMailDocument['address'] = isset($this->web_partner_details['address']) ? $this->web_partner_details['address'] : '';
            $dataForMailDocument['city'] = isset($this->web_partner_details['city']) ? $this->web_partner_details['city'] : '';
            $dataForMailDocument['state'] = isset($this->web_partner_details['state']) ? $this->web_partner_details['state'] : '';
            $dataForMailDocument['country'] = isset($this->web_partner_details['country']) ? $this->web_partner_details['country'] : '';
            $dataForMailDocument['facebook_link'] = isset($this->web_partner_details['facebook_link']) ? $this->web_partner_details['facebook_link'] : '';
            $dataForMailDocument['linkedin_link'] = isset($this->web_partner_details['linkedin_link']) ? $this->web_partner_details['linkedin_link'] : '';
            $dataForMailDocument['instagram_link'] = isset($this->web_partner_details['instagram_link']) ? $this->web_partner_details['instagram_link'] : '';
            $dataForMailDocument['youtube_link'] = isset($this->web_partner_details['youtube_link']) ? $this->web_partner_details['youtube_link'] : '';
            $dataForMailDocument['twitter_link'] = isset($this->web_partner_details['twitter_link']) ? $this->web_partner_details['twitter_link'] : '';




            $messageData =  view('Views/emails/feedback-email', $dataForMailDocument);


            // pr($this->web_partner_details);




            $emamilLogsData = array(
                'package_booking_date' => $bookingDataValue['PackageStartDate'],
                'package_end_date' => $bookingDataValue['PackageEndsDate'],
                'mail_send_status' => '0',
                'service' => 'Flight_booking',
                'created' => create_date(),
                'booking_info' => $bookingDataValue['BookingRefrenceNumber'],
                'from_email' => $this->web_partner_details['support_email'],
                'to_email' => $TravelerDataDetail['email_id'],
                'subject' => "Flight Booking Review",
                'message' => $messageData,
                'web_partner_id' => $this->web_partner_details['id'],
                'bcc_email' => isset($this->web_partner_details['bcc_email']) ? $this->web_partner_details['bcc_email'] : "",
                'cc_email' => isset($this->web_partner_details['cc_email']) ? $this->web_partner_details['cc_email'] : "",
                'email_type' => "Booking Review Logs",
                'booking_source' => 'B2C',
            );


            $logs_email_insert = $FlightModel->insertIntoLogs("logs_email", $emamilLogsData);
        }





        // new code to store data in email_logs table ends  here 




        return view('template/default-layout', $data);
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
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
            }
        }
        $availableAirline[] = 'ANY' . '-' . 'Any Airline';
        echo json_encode($availableAirline);
    }



    function getInvoiceTicket()
    {
        $FlightModel = new FlightModel();
        if (!$this->request->isAJAX()) {
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getData = $this->request->getGet();
            $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
            $bookingInfo = array();
            if ($bookingRefNumbers) {
                foreach ($bookingRefNumbers as $BookingRefNumber) {
                    $bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,tts_search_token");
                    if ($bookingInfoData) {
                        $bookingInfo[] = $bookingInfoData;
                        $bookingInfoId[] = $bookingInfoData['id'];
                        $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                    }
                }
                if ($bookingRefNumbers && $bookingInfo) {
                    if ($getData['type'] == "PrintTicket") {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "wl-customer",
                            "ViewService" => "View",
                            "WithPrice" => 1,
                            "WithAgencyDetail" => 1,
                            "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : 'Onward',
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner"
                        );
                    } else {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "wl-customer",
                            "ViewService" => "View",
                            "WithPrice" => 1,
                            "WithAgencyDetail" => 1,
                            "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : 'Onward',
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner"
                        );
                    }
                   /*  echo */ $url = $this->Services . 'generate-wl-ticket-invoice';
                 /*    echo json_encode($TicketViewRequest);exit; */
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Flight\Views\FlightBookingtemplate\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
            }
        } else {
            /* special ajax here */
            $getData = $this->request->getPOST();
            $validate = new Validation();
            $rules = $this->validate($validate->EmailTicketValidation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $getData = $this->request->getPost();
                $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
                $bookingInfo = array();
                if ($bookingRefNumbers) {
                    foreach ($bookingRefNumbers as $BookingRefNumber) {
                        $bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,tts_search_token");
                        if ($bookingInfoData) {
                            $bookingInfo[] = $bookingInfoData;
                            $bookingInfoId[] = $bookingInfoData['id'];
                            $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                        }
                    }
                    if ($bookingRefNumbers && $bookingInfo) {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => "Ticket",
                            "UserType" => "wl-customer",
                            "ViewService" => "Email",
                            "WithPrice" => 0,
                            "WithAgencyDetail" => 1,
                            "TicketInvoiceJourney" => "Both",
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner"
                        );
                        $url = $this->Services . 'generate-wl-ticket-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);
                        $htmlView = $response['Result']['Html'];
                        $subject = "Flight Ticket";
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
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $list = $FlightBookingModel->search_bookings($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->wl_customer_id);
        } else {
            $list = $FlightBookingModel->flight_booking_list($this->web_partner_id, $this->web_partner_details['id'], $this->wl_customer_id);
        }
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/default-layout', $data);
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
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->getBookingWithVariableFieldNameData($bookingReferenceNumber, $this->web_partner_id, "booking_ref_number");
                if ($bookingReferenceNumber && $BookingDetail) {
                    $query_string = http_build_query($requestData);
                    $redirect_url = site_url('flight/amendment-itinerary?' . $query_string);
                    $message = array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $redirect_url);
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

    public function amendmentItinerary()
    {
        $requestData = $this->request->getGET();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendment);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $errors = implode(",", array_values($errors));
                return $this->response->redirect(site_url('flight/error?errormessage=' . $errors));
            } else {
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($this->web_partner_id, $bookingReferenceNumber);
                if ($bookingReferenceNumber && $BookingDetail) {
                    if ($BookingDetail['is_domestic']) {
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $airlineLogoClass = "intAirLogo";
                    }
                    $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
                    $data = [
                        'title' => $this->title,
                        'view' => "Flight\Views\listing/flight-amendment-itinerary",
                        "bookingDetail" => $BookingDetail,
                        "requestData" => $requestData,
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    public function raiseAmendmentType()
    {
        $requestData = $this->request->getPOST();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendmentType);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                if (isset($errors['passengers.*'])) {
                    $errors['passengers[]'] = $errors['passengers.*'];
                }
                unset($errors['passengers.*']);
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($this->web_partner_id, $bookingReferenceNumber);
                if ($bookingReferenceNumber && $BookingDetail) {
                    $AmendmentStatus = "";
                    $sectors = array();
                    $PaxIds = array();
                    foreach ($requestData['passengers'] as $passengerId) {
                        $paxId = dev_decode($passengerId);
                        array_push($PaxIds, $paxId);
                    }


                    $tripInfo = json_decode($BookingDetail['segments'], true);
                    foreach ($tripInfo as $key => $trips) {
                        foreach ($trips as $segmentIndicatorkey => $segment) {
                            $sector = array(
                                "Origin" => $segment['Origin']['CityCode'],
                                "Destination" => $segment['Destination']['CityCode'],
                            );
                            array_push($sectors, $sector);
                        }
                    }

                    $request = array(
                        "BookingId" => $BookingDetail['id'],
                        "Type" => $requestData['amendment_type'],
                        "Remarks" => $requestData['remark'],
                        "RequesterInfo" => array("RequesterId" => $this->wl_customer_id, "Requester" => "WhitelabelB2C", "wl_customer_id" => $this->wl_customer_id),
                        "Sectors" => $sectors,
                        "PaxId" => $PaxIds,
                    );


                    if ($AmendmentStatus != "") {
                        $request['AmendmentStatus'] = $AmendmentStatus;
                    }
                    $service = "submitamendment";
                    $url = $this->Services . $service;
                    $response = TTSRequest($request, $url, $service);

                    $EmailType = 'Amendment';
                    $EmailId = $this->web_partner_details['support_email'];
                    if ($EmailId) {

                        $data['paxs'] = $FlightBookingModel->pax_details($PaxIds);

                        $Smsmessage = "Amendment request successfully submitted";
                        $data['remark'] = $requestData['remark'];
                        $data['message'] = $Smsmessage;

                        $data['type'] = $requestData['amendment_type'];
                        $message = view('Views/emails/amendment-emails', $data);

                        $param1 = 'ticketing';

                        send_email($EmailId, $Smsmessage, $message, $EmailType, $attachment = null, $extraprameter = null, $param1);
                    }
                    if ($response['Error']['ErrorCode'] == 0) {
                        $message = array("StatusCode" => 0, "Message" => "Amendment has  Successfully Submitted.", 'Class' => 'success_popup', "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $response['Error']['ErrorMessage'], "Class" => "error_popup", "Reload" => "true");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    public function amendmentDetails()
    {
        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $FlightBookingModel = new FlightBookingModel();
        $BookingDetail = $FlightBookingModel->flight_amendment_detail($this->web_partner_id, $this->wl_customer_id, $amendmentId);
        if ($amendmentId && $BookingDetail) {
            if ($BookingDetail['is_domestic']) {
                $airlineLogoClass = "domAirLogo";
            } else {
                $airlineLogoClass = "intAirLogo";
            }
            $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\listing/flight-amendment-detail",
                "bookingDetail" => $BookingDetail,
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }



    function flightAmendmentLists()
    {
        $FlightAmendmentModel = new FlightAmendmentModel();
        $getData = $this->request->getGET();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $FlightAmendmentModel->search_data($this->web_partner_id, $this->wl_customer_id, $getData);
        } else {
            $list = $FlightAmendmentModel->flight_amendment_list($this->web_partner_id, $this->wl_customer_id);
        }
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views/flightAmendment/flight-amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $FlightAmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function promocode()
    {
        $requestData = $this->request->getPOST();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->promocodeValidation);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $couponModel = new CouponModel();
                $inputData = $this->request->getPOST();

                $CouponCodeExists = $couponModel->getCouponByToken($inputData['SearchTokenId'], $this->web_partner_id);
                if (!empty($CouponCodeExists)) {
                    $couponModel->remove_promo_log($inputData['SearchTokenId'], $this->web_partner_id);
                }
                $FlightModel = new FlightModel();
                $whereFlightSearch = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "search");
                $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
                $searchRequest = json_decode($FlightSearchInfo['request'], true);
                $searchRequest['code'] = $inputData['couponCode'];
                $coupounInfo = $couponModel->getDataByCode($searchRequest, $this->web_partner_id);

                if (empty($coupounInfo)) {
                    $errors = ['couponCode' => 'Invalid Promo code'];
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                } else {

                    $flightConfrimationData = array();
                    $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $inputData['FareCode']);
                    $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');
                    if ($flightConfrimationDataOB) {
                        $flightConfrimationData['OB'] = $flightConfrimationDataOB['response'];
                    } else {
                        $flightConfrimationData['OB'] = array();
                    }
                    if (isset($inputData['farecodereturn']) and $inputData['farecodereturn'] != "") {
                        $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $inputData['farecodereturn']);
                        $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
                        if ($flightConfrimationDataIB) {
                            $flightConfrimationData['IB'] = $flightConfrimationDataIB['response'];
                        } else {
                            $flightConfrimationData['IB'] = array();
                        }
                    }
                    $selectedMarkupDataInfo = array();
                    $selectedDiscountDataInfo = array();
                    if ($flightConfrimationData) {
                        $flightConfrimationFareDetailData = array();
                        $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                        $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                        $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $searchRequest);
                        $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $searchRequest);
                        foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                            if ($fareconfirmation) {
                                $flightConfirmationArray = json_decode($fareconfirmation, true);
                                if ($flightConfirmationArray && isset($flightConfirmationArray['Error']) && $flightConfirmationArray['Error']['ErrorCode'] == 0) {
                                    $first_segment = current($flightConfirmationArray['Result']['Segments']);
                                    $first_segment = current($first_segment);
                                    $extraParam['CabinClass'] = $first_segment['CabinClass'];
                                    $extraParam['FareType'] = $flightConfirmationArray['Result']['FareType'];
                                    $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                                    $FareWithMarkupArray = get_flight_fare($searchRequest, $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $flightConfirmationArray['Result']['Fare'], $flightConfirmationArray['Result']['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');


                                    $FareWithMarkup = $FareWithMarkupArray['Fare'];
                                    $convertfareBreakupArray =  convertCurrencyRate($FareWithMarkup);
                                    $fareBreakupArray = $convertfareBreakupArray['ConvertedPrice'];
                                    $fareconfirmationArray['Result']['Fare'] = $FareWithMarkup;
                                    $discount = $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'];
                                    $FareBreakUp = array();
                                    $CoupomBreakUp = array();
                                    $FareBreakUp = array(
                                        "FareBreakup" => array(
                                            "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                                            "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                                            "ServiceAndOtherCharge" => array("Value" => round_value( + $fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
                                            "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                                            "Meal" => array("Value" => 0, "LabelText" => "Meal (+)"),
                                            "Baggage" => array("Value" => 0, "LabelText" => "Baggage (+)"),
                                            "Seat" => array("Value" => 0, "LabelText" => "Seat (+)"),
                                        ),
                                    );
                                    if ($discount) {
                                        $FareBreakUp['FareBreakup']['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
                                    }
                                    $couponAmount = 0;
                                    if ($coupounInfo) {
                                        if ($coupounInfo['coupon_type'] == 'fixed') {

                                            $convertCouponAmount = convertCurrencyRate($coupounInfo['value']);
                                            $couponAmount  =  $convertCouponAmount['ConvertedPrice'];
                                        } else {
                                            $convertCouponAmount = convertCurrencyRate($coupounInfo['value']);
                                            $couponAmount  =  $convertCouponAmount['ConvertedPrice'];
                                            $cAmount = ($couponAmount * $fareBreakupArray['OfferedPrice']) / 100;
                                            $couponAmount = ($cAmount > $coupounInfo['max_limit']) ? $coupounInfo['max_limit'] : $cAmount;
                                        }
                                        if (empty($flightConfrimationData['IB'])) {
                                            $CoupomBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promo Code Discount (-)");
                                            $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promo Code Discount (-)");
                                        } else {
                                            $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount / 2), "LabelText" => "Promo Code Discount (-)");
                                            $CoupomBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount / 2), "LabelText" => "Promo Code Discount (-)");
                                        }
                                    }
                                    if (empty($flightConfrimationData['IB'])) {
                                        $FareBreakUp['TotalAmount'] = array("Value" => round_value($fareBreakupArray['OfferedPrice'] - $couponAmount), "LabelText" => "Pay  Amount");
                                    } else {
                                        $FareBreakUp['TotalAmount'] = array("Value" => round_value($fareBreakupArray['OfferedPrice'] - ($couponAmount / 2)), "LabelText" => "Pay  Amount");
                                    }
                                    $flightConfrimationFareDetailData[$fareconfirmationKey] = $FareBreakUp;
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => 'Unable to Applied Promo Code,Refresh the Page', "Class" => "error_popup", "Reload" => "true");
                                    return $this->response->setJSON($message);
                                }
                            }
                        }

                        $couponData = [];
                        $couponData['token'] = $inputData['SearchTokenId'];
                        $couponData['web_partner_id'] = $this->web_partner_id;
                        $couponData['use_for'] = 'Flight';
                        $couponData['coupon_code'] = $coupounInfo['coupon_code'];
                        unset($coupounInfo['id']);
                        $couponData['couponInfo'] = json_encode($coupounInfo);
                        $couponData['created'] = create_date();

                        $insertId = $couponModel->insertData('coupon_log', $couponData);

                        if ($insertId) {
                            $message = array("StatusCode" => 0, "Message" => "Promo Code Applied Successfully", "Class" => "success_popup", "Reload" => "true", "FareBreakUpData" => $flightConfrimationFareDetailData, 'CouponRBreakUp' => $CoupomBreakUp, 'CouponID' => $insertId);
                        } else {
                            $message = array("StatusCode" => 2, "Message" => 'Unable to Applied Promo Code', "Class" => "error_popup", "Reload" => "true", "FareBreakUpData" => $flightConfrimationFareDetailData, 'CouponID' => '', 'CouponRBreakUp' => $CoupomBreakUp);
                        }
                        return $this->response->setJSON($message);
                    }
                }
            }
        }
    }

    public function removePromoCode()
    {
        $couponModel = new CouponModel();
        $inputData = $this->request->getPOST();
        $deleteCode = $couponModel->remove_promo_log($inputData['SearchTokenId'], $this->web_partner_id);
        $FlightModel = new FlightModel();
        $whereFlightSearch = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "search");
        $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
        $searchRequest = json_decode($FlightSearchInfo['request'], true);
        $flightConfrimationData = array();
        $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $inputData['FareCode']);
        $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');
        if ($flightConfrimationDataOB) {
            $flightConfrimationData['OB'] = $flightConfrimationDataOB['response'];
        } else {
            $flightConfrimationData['OB'] = array();
        }
        if (isset($inputData['farecodereturn']) and $inputData['farecodereturn'] != "") {
            $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $inputData['farecodereturn']);
            $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
            if ($flightConfrimationDataIB) {
                $flightConfrimationData['IB'] = $flightConfrimationDataIB['response'];
            } else {
                $flightConfrimationData['IB'] = array();
            }
        }
        $selectedMarkupDataInfo = array();
        $selectedDiscountDataInfo = array();
        if ($flightConfrimationData) {
            $flightConfrimationFareDetailData = array();
            $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
            $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
            $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $searchRequest);
            $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $searchRequest);
            foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                if ($fareconfirmation) {
                    $flightConfirmationArray = json_decode($fareconfirmation, true);
                    if ($flightConfirmationArray && isset($flightConfirmationArray['Error']) && $flightConfirmationArray['Error']['ErrorCode'] == 0) {
                        $first_segment = current($flightConfirmationArray['Result']['Segments']);
                        $first_segment = current($first_segment);

                        $extraParam['CabinClass'] = $first_segment['CabinClass'];
                        $extraParam['FareType'] = $flightConfirmationArray['Result']['FareType'];
                        $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                        $FareWithMarkup = get_flight_fare($searchRequest, $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $flightConfirmationArray['Result']['Fare'], $flightConfirmationArray['Result']['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');

                        $FareWithMarkup = $FareWithMarkup['Fare'];
                      
                        $convertfareBreakupArray =  convertCurrencyRate($FareWithMarkup); 
                        $fareBreakupArray = $convertfareBreakupArray['ConvertedPrice'];

                        $fareconfirmationArray['Result']['Fare'] = $FareWithMarkup;
                        /*      $fareBreakupArray = $fareconfirmationArray['Result']['Fare']['Fare']; */
                        $discount = $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'];
                        $FareBreakUp = array(); 
                        $FareBreakUp = array(
                            "FareBreakup" => array(
                                "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                                "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                                "ServiceAndOtherCharge" => array("Value" => round_value( + $fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
                                "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                                "Meal" => array("Value" => 0, "LabelText" => "Meal (+)"),
                                "Baggage" => array("Value" => 0, "LabelText" => "Baggage (+)"),
                                "Seat" => array("Value" => 0, "LabelText" => "Seat (+)"),
                            ),
                        );
 

                        if ($discount) {
                            $FareBreakUp['FareBreakup']['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
                        }
                        $FareBreakUp['TotalAmount'] = array("Value" => round_value($fareBreakupArray['OfferedPrice']), "LabelText" => "Pay Amount");
                        $flightConfrimationFareDetailData[$fareconfirmationKey] = $FareBreakUp;
                    } else {
                        $message = array("StatusCode" => 2, "Message" => 'Unable to Applied Promo Code,Refresh the Page', "Class" => "error_popup", "Reload" => "true");
                        return $this->response->setJSON($message);
                    }
                }
            }
        }
        if ($deleteCode) {
            $message = array("StatusCode" => 0, "Message" => 'Promo Code Remove Successfully', "Class" => "error_popup", "Reload" => "true", "FareBreakUpData" => $flightConfrimationFareDetailData, 'CouponID' => '');
        } else {
            $message = array("StatusCode" => 2, "Message" => 'Unable to Delete Promo Code', "Class" => "error_popup", "Reload" => "true", 'CouponID' => $inputData['CouponId']);
        }
        return $this->response->setJSON($message);
    }


    function groupTicket()
    {
        $data = json_decode('{"journeytype":"Oneway","origin":"Delhi (DEL), India","destination":"Mumbai (BOM), India","departdate":"22 Nov 24","returndate":"","adults":"1","child":"0","infant":"0","cabinclass":"Any","direct_flight":"0","preferred_carriers":"","result_fare_type":"RegularFare"}', true);

        $OriginDestinationAirportDetail = '{"BOM":"Chhatrapati Shivaji","DEL":"Delhi Indira Gandhi Intl"}';
        $data = [
            'searchData' => $data,
            'OriginDestinationAirportDetail' => $OriginDestinationAirportDetail,
            'view' => "Flight\Views\group-ticket",
        ];
        return view('template/default-layout', $data);
    }


    function newflightfilter()
    {
        $data = [
            'view' => "Flight\Views\new-flight-filter",
        ];
        return view('template/default-layout', $data);
    }
    function newflightresult()
    {
        $data = [
            'view' => "Flight\Views\new-flight-result",
        ];
        return view('template/default-layout', $data);
    }
}

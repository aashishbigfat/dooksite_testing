<?php

namespace Modules\Hotel\Controllers;

use App\Controllers\BaseController;
use Modules\Hotel\Config\Validation;

use App\Modules\Hotel\Models\HotelCitiesModel;
use App\Modules\Hotel\Models\HotelModel;
use App\Modules\Hotel\Models\HotelBookingModel;
use App\Models\CommonModel;
use App\Modules\Hotel\Models\WebPartnerHotelMarkupModel;
use App\Modules\Hotel\Models\WebPartnerHotelDiscountModel;
use App\Modules\Hotel\Models\HotelAmendmentModel;
use App\Modules\Hotel\Models\CouponModel;

class Hotel extends BaseController
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
    protected $folder_name;
    protected $booking_source;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        if (permission_access_error("hotel_module")) {
        }

        $this->title = "Hotel";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        if (isset(session()->get('wl_customer')['id'])) {
            $this->wl_customer_id = session()->get('wl_customer')['id'];
        }
        $this->web_partner_gst_code = substr(web_partner_details['company_gst_no'], 0, 2);
        $this->wl_customer_gst_code = substr(web_partner_details['company_gst_no'], 0, 2);
        $this->folder_name = 'Hotel';
        $this->Services = API_REQUEST_URL . '/hotelservice/rest/';
        helper('Modules\Hotel\Helpers\hotel');
        ini_set("memory_limit", "1024M");
        $this->booking_source = "Wl_b2c";
    }


    public function city_list()
    {
        $terms = $this->request->getGet('term');
        $HotelCitiesModel = new HotelCitiesModel();
        echo $HotelCitiesModel->cities_list($terms);
    }

    public function check_search_validtion()
    {
        $requestData = $this->request->getPOST();
        $validationConfigArray = array();
        $errors = array();
        $errorMessage = array("location" => "Please select city", "cityDom" => "Please select city", "room" => "Please select room", "checkIn" => "Please select check in date", "checkOut" => "Please select check out date");
        if ($requestData) {
            foreach ($requestData as $requestParameterKey => $requestParameter) {
                if ($requestParameterKey == "checkIn" || $requestParameterKey == "checkOut") {
                    $validationConfigArray[$requestParameterKey] = array("label" => ucfirst(str_replace("_", " ", $requestParameterKey)), "rules" => "trim|required|valid_date[d M y]", "errors" => array("required" => isset($errorMessage[$requestParameterKey]) ? $errorMessage[$requestParameterKey] : "Please select " . ucfirst(str_replace("_", " ", $requestParameterKey))));
                } else {
                    $validationConfigArray[$requestParameterKey] = array("label" => ucfirst(str_replace("_", " ", $requestParameterKey)), "rules" => "trim|required", "errors" => array("required" => isset($errorMessage[$requestParameterKey]) ? $errorMessage[$requestParameterKey] : "Please select " . ucfirst(str_replace("_", " ", $requestParameterKey))));
                }
            }
            $this->validation->setRules($validationConfigArray);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $message = array("StatusCode" => 0, "Message" => "");
                return $this->response->setJSON($message);
            }
        } else {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }

    public function index()
    {
        $HotelModel = new HotelModel();
        $CommonModel = new CommonModel();
        $notification_list = $HotelModel->admin_notification();
        $MetaInfoData = static_meta_information('Hotel', 'Index');
        $offers_list = $CommonModel->offers_list($this->web_partner_id);
        /*  $offers_list['home'] = $offers_list['bestoffer']; */
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
            'notification_list' => $notification_list,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'offers_list' =>  $ordered_list,
            'blog_list' => $this->session->get('bloglist'),
            'slider_list' => $this->session->get('sliderList'),
            'feedbac_list' => $this->session->get('feedback'),
            'view' => "Hotel\Views\booking\index",
        ];
        return view('template/default-layout', $data);
    }


    public function hotel_result()
    {
        $request = $this->request->getGET();
        $MetaInfoData = static_meta_information('Hotel', 'Result');

        $data = [
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => "Hotel\Views\booking\hotel_result",
            'searchData' => $request,
        ];
        return view('template/default-layout', $data);
    }

    public function get_hotel_lists()
    {
        $search_data = $this->request->getGET();
        if ($search_data) {
            $nationality = $search_data['nationalitycode'];
            $city = $search_data['cityDom'];
            $exp = explode("_", $city);
            $cityCode = $exp[1];
            $country = $exp[2];
            $checkin = hotel_date_format($search_data['checkIn']);
            $checkout = hotel_date_format($search_data['checkOut']);
            $nights = getDateDiffrence($search_data['checkIn'], $search_data['checkOut']);
            $rate = gethotelStarRating($search_data['rating']);
            $NoOfRooms = $search_data["room"];
            $child_age = array();
            $RoomGuests = array();
            for ($r = 1; $r <= $search_data["room"]; $r++) {

                unset($child_age);
                if (isset($search_data["child_" . $r])) {
                    $NoOfChild = intval($search_data["child_" . $r]);
                } else {
                    $NoOfChild = 0;
                }
                if ($search_data["child_" . $r] !== 0) {

                    for ($c = 1; $c <= $search_data["child_" . $r]; $c++) {
                        $child_age[] = intval($search_data["age_" . $r . '_' . $c]);
                    }
                }
                if (isset($search_data["adult_" . $r])) {
                    if (isset($child_age)) {
                        $child_age = $child_age;
                    } else {
                        $child_age = array();
                    }
                    $RoomGuests[] = array(
                        'Adult' => intval($search_data["adult_" . $r]),
                        'Child' => $NoOfChild,
                        'ChildAge' => $child_age,
                    );
                }
            }


            $request = array(
                "CheckInDate" => $checkin,
                "CheckOutDate" => $checkout,
                "NoOfNights" => intval($nights),
                "CountryCode" => $country,
                "DestinationCityId" => intval($cityCode),
                "ResultCount" => null,
                "GuestNationality" => $nationality,
                "NoOfRooms" => intval($NoOfRooms),
                "RoomGuests" => $RoomGuests,
                "MaxRating" => intval($rate['max']),
                "MinRating" => intval($rate['min']),
                "UserIp" => $this->request->getIpAddress(),
            );
            $service = "search";
            /*  echo */
            $url = $this->Services . $service;
            /*  echo json_encode($request);exit; */
            $response = TTSRequest($request, $url, $service);



            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                $selectedMarkupDataInfo = array();
                $selectedDiscountDataInfo = array();
                $PriceList = [];
                $LocationList = [];
                $StarRatingList = [];
                $HotelNameList = [];
                $hotelResultCustom = [];
                if ($request['CountryCode'] == 'IN') {
                    $region_type = 'domestic';
                } else {
                    $region_type = 'international';
                }
                $input = array(
                    "RegionType" => $region_type,
                    'StarRating' => $rate['min'],
                );
                $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
                $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
                $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
                $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);

                foreach ($response['Result'] as $hotelkey => $hotelResult) {
                    $input['StarRating'] = intval($hotelResult['StarRating'] >= 1 ? $hotelResult['StarRating'] : 0);
                    $HotelPrice = get_hotel_fare($input, $request['NoOfNights'], $request['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $hotelResult['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);

                    $ConvertFareWithMarkup   = convertCurrencyRate($HotelPrice['CustomerFareBreakUp']);
                    $hotelResult['Price'] =  $ConvertFareWithMarkup['ConvertedPrice'];
                    $currencySymbol = $ConvertFareWithMarkup['CurrencySymbol'];
                    $currencyCode = $ConvertFareWithMarkup['CurrencyCode'];
                    $hotelResult["HotelLocation"] = $hotelResult['HotelLocation'] != NULL ? $hotelResult['HotelLocation'] : 'None';
                    $hotelResult["HotelPromotion"] = strip_tags($hotelResult['HotelPromotion']);
                    $hotelResult["HotelDescription"] = strip_tags($hotelResult['HotelDescription']);
                    $hotelResultCustom[$hotelkey] = $hotelResult;
                    $PriceList[] = $hotelResult['Price']['OfferedPrice'];
                    $LocationList[] = $hotelResult['HotelLocation'] != NULL ? $hotelResult['HotelLocation'] : 'None';
                    $StarRatingList[] = intval($hotelResult['StarRating'] >= 1 ? $hotelResult['StarRating'] : 0);
                    $HotelNameList[] = $hotelResult['HotelName'];
                }
                $response['Result'] = $hotelResultCustom;
                $response['TotalResults'] = count($response['Result']);
                $response['CurrencySymbol'] = $currencySymbol;
                $response['CurrencyCode'] = $currencyCode;
                $response['Price'] = array('min' => min($PriceList), 'max' => max($PriceList));
                $response['LocationType'] = createhotelfilterarray(array_values(array_unique($LocationList)));
                $response['HotelName'] = array_values(array_unique($HotelNameList));
                $StarRatingList = array_values(array_unique($StarRatingList));
                rsort($StarRatingList);
                $response['StarRatingType'] = createhotelfilterarray($StarRatingList);
                return $this->response->setJson($response);
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

    public function hotel_rooms()
    {

        $hotelInfoRequest = $this->request->getGET();
        $HotelModel = new HotelModel();
        $whereClause = array("tts_search_token" => $hotelInfoRequest['token'], "service" => "search");
        $searchRequest = $HotelModel->getDataFromApi($whereClause, 'request');
        $searchRequest = json_decode($searchRequest['request'], true);
        $data = [
            'title' => $this->title,
            'searchRequest' => $searchRequest,
            'view' => "Hotel\Views\booking\hotel-rooms",
        ];
        return view('template/default-layout', $data);
    }

    public function get_hotel_info()
    {
        $hotelInfoRequest = $this->request->getGET();
        if ($hotelInfoRequest) {
            $ResultIndex = $hotelInfoRequest['rindex'];
            $SearchTokenId = $hotelInfoRequest['token'];
            $HotelCode = $hotelInfoRequest['hcode'];
            $request = array(
                "UserIp" => $this->request->getIpAddress(),
                "ResultIndex" => $ResultIndex,
                "HotelCode" => $HotelCode,
                "SearchTokenId" => $SearchTokenId
            );
            $service = "gethotelinfo";
            $url = $this->Services . $service;
            /*   echo $url;
            echo json_encode($request);exit; */
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

    public function get_room_info()
    {
        $hotelInfoRequest = $this->request->getGET();
        if ($hotelInfoRequest) {
            $ResultIndex = $hotelInfoRequest['rindex'];
            $SearchTokenId = $hotelInfoRequest['token'];
            $HotelCode = $hotelInfoRequest['hcode'];
            $request = array(
                "UserIp" => $this->request->getIpAddress(),
                "ResultIndex" => $ResultIndex,
                "HotelCode" => $HotelCode,
                "SearchTokenId" => $SearchTokenId
            );
            $service = "getroominfo";
            $url = $this->Services . $service;
            $response = TTSRequest($request, $url, $service);
            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                $FinalRoomData = array();
                $FinalPriceDataRoomData = array();
                $RoomCombinations = $response['Result']['RoomCombinations']['RoomCombination'];
                $InfoSource = $response['Result']['RoomCombinations']['InfoSource'];
                $selectedMarkupDataInfo = array();
                if ($RoomCombinations) {
                    $HotelModel = new HotelModel();
                    $whereHotelSearch = array("tts_search_token" => $SearchTokenId, "service" => "search");
                    $HotelSearchInfo = $HotelModel->getDataFromApi($whereHotelSearch, 'request');
                    $searchRequest = json_decode($HotelSearchInfo['request'], true);
                    $whereHotelInfoSearch = array("tts_search_token" => $SearchTokenId, "service" => "gethotelinfo");
                    $HotelInfo = $HotelModel->getDataFromApi($whereHotelInfoSearch, 'response');
                    $HotelInfo = json_decode($HotelInfo['response'], true);

                    if ($searchRequest['CountryCode'] == 'IN') {
                        $region_type = 'domestic';
                    } else {
                        $region_type = 'international';
                    }
                    $input = array(
                        "RegionType" => $region_type,
                        'StarRating' => $HotelInfo['Result']['StarRating'],
                    );
                    $HotelModel = new HotelModel();
                    $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
                    $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
                    $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
                    $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);
                    $selectedMarkupDataInfo = array();
                    $selectedDiscountDataInfo = array();
                    $openCombinationData =  array();
                    foreach ($RoomCombinations as $roomSuperKey => $RoomCombination) {
                        if ($InfoSource == "FixedCombination") {
                            $RoomTotalPrice = 0;
                            foreach ($RoomCombination['RoomIndex'] as $RoomIndex) {
                                foreach ($response['Result']['HotelRoomsDetails'] as $HotelRoomsDetails) {
                                    $HotelPrice = get_hotel_fare($input, $searchRequest['NoOfNights'], $searchRequest['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $HotelRoomsDetails['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
                                    $selectedMarkupDataInfo =   $HotelPrice['selectedMarkupDataInfo'];
                                    $selectedDiscountDataInfo =   $HotelPrice['selectedDiscountDataInfo'];
                                    $HotelRoomsDetails['Price'] =  $HotelPrice['CustomerFareBreakUp'];
                                    if ($RoomIndex == $HotelRoomsDetails['RoomIndex']) {
                                        $RoomTotalPrice = $RoomTotalPrice + $HotelRoomsDetails['Price']['OfferedPrice'];
                                        if (!isset($FinalRoomData[$roomSuperKey])) {
                                            $FinalRoomData[$roomSuperKey][0] = $HotelRoomsDetails;
                                        } else {
                                            array_push($FinalRoomData[$roomSuperKey], $HotelRoomsDetails);
                                        }
                                        $ConvertRoomPrice   = convertCurrencyRate($RoomTotalPrice);
                                        $currencySymbol = $ConvertRoomPrice['CurrencySymbol'];
                                        $currencyCode = $ConvertRoomPrice['CurrencyCode'];
                                        $RoomTotalPrice =  $ConvertRoomPrice['ConvertedPrice'];
                                        $FinalPriceDataRoomData[$roomSuperKey] = $RoomTotalPrice;
                                    }
                                }
                            }
                        } else {
                            foreach ($RoomCombination['RoomIndex'] as $roomkey => $RoomIndex) {
                                foreach ($response['Result']['HotelRoomsDetails'] as $HotelRoomsDetails) {
                                    $RoomTotalPrice = 0;
                                    $HotelPrice = get_hotel_fare($input, $searchRequest['NoOfNights'], $searchRequest['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $HotelRoomsDetails['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
                                    $selectedMarkupDataInfo =   $HotelPrice['selectedMarkupDataInfo'];
                                    $selectedDiscountDataInfo =   $HotelPrice['selectedDiscountDataInfo'];
                                    $HotelRoomsDetails['Price'] =  $HotelPrice['CustomerFareBreakUp'];
                                    if ($RoomIndex == $HotelRoomsDetails['RoomIndex']) {
                                        $RoomTotalPrice = $RoomTotalPrice + $HotelRoomsDetails['Price']['OfferedPrice'];

                                        $ConvertRoomPrice   = convertCurrencyRate($RoomTotalPrice);
                                        $currencySymbol = $ConvertRoomPrice['CurrencySymbol'];
                                        $currencyCode = $ConvertRoomPrice['CurrencyCode'];
                                        $RoomTotalPrice =  $ConvertRoomPrice['ConvertedPrice'];
                                        $HotelRoomsDetails['TotalPrice'] =  $RoomTotalPrice;
                                        $HotelRoomsDetails['CurrencySymbol'] =  $currencySymbol;
                                        $HotelRoomsDetails['CurrencyCode'] =  $currencyCode;
                                        $FinalRoomData[$roomSuperKey][$roomkey] = $HotelRoomsDetails;
                                        $FinalPriceDataRoomData[$roomSuperKey][$roomkey] = $RoomTotalPrice;
                                        if ($roomkey == 0) {
                                            array_push($openCombinationData, $HotelRoomsDetails);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $response['Result']['InfoSource'] = $response['Result']['RoomCombinations']['InfoSource'];
                unset($response['Result']['HotelRoomsDetails']);
                unset($response['Result']['RoomCombinations']);
                $response['Result']['FinalRoomData'] = $FinalRoomData;
                $response['Result']['OpenCombinationData'] = $openCombinationData;
                $response['Result']['FinalPriceDataRoomData'] = $FinalPriceDataRoomData;
                $response['Result']['CurrencySymbol'] = $currencySymbol;
                $response['Result']['CurrencyCode'] = $currencyCode;
            }

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

    public function get_blockroom_info()
    {
        $hotelblockRequest = $this->request->getPost();

        if ($hotelblockRequest) {
            $HotelName = $hotelblockRequest['HotelName'] ?? '';
            $ResultIndex = $hotelblockRequest['ResultIndex'] ?? '';
            $SearchTokenId = $hotelblockRequest['SearchTokenId'] ?? '';
            $HotelCode = $hotelblockRequest['HotelCode'] ?? '';
            $NoOfRooms = $hotelblockRequest['NoOfRooms'] ?? 0;
            $roomInfo = [];
            $oldprice = 0;
            $newprice = 0;
            if (!empty($hotelblockRequest['HotelRoomsDetails'])) {
                foreach ($hotelblockRequest['HotelRoomsDetails'] as $HotelRoomsDetails) {
                    if (isset($HotelRoomsDetails['Price']['OfferedPrice'])) {
                        $oldprice += $HotelRoomsDetails['Price']['OfferedPrice'];
                        $roomInfo[] = ['RoomIndex' => $HotelRoomsDetails['RoomIndex']];
                    }
                }
            }

            $request = [
                "UserIp" => $this->request->getIPAddress(),
                "HotelName" => $HotelName,
                "ResultIndex" => $ResultIndex,
                "HotelCode" => $HotelCode,
                "NoOfRooms" => $NoOfRooms,
                "HotelRoomsDetails" => $roomInfo,
                "SearchTokenId" => $SearchTokenId
            ];

            $service = "blockroom";
            $url = $this->Services . $service;
            $response = TTSRequest($request, $url, $service);
            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                foreach ($response['Result']['HotelRoomsDetails'] as $HotelRoomsDetails) {
                    if (isset($HotelRoomsDetails['Price']['OfferedPrice'])) {
                        $newprice += $HotelRoomsDetails['Price']['OfferedPrice'];
                    }
                }

                $redirectUrl = site_url() . "hotel/hotel-details?rindex=" . urlencode($ResultIndex) . "&token=" . urlencode($SearchTokenId) . "&hcode=" . urlencode($HotelCode) . "&rtype=blcrm";
                $title = "";
                $price_change = "";
                $getConfirmshowbox = 0;
                if ($response['Result']['IsPriceChanged']) {
                    $title = "Price has been changed";
                    $getConfirmshowbox = 1;
                    $difference = $newprice - $oldprice;
                    $sign_type = check_int($difference);
                    $convertedDifferenceFare = convertCurrencyRate($difference);
                    $convertedNewPrice = convertCurrencyRate($newprice);
                    $convertedOldPrice = convertCurrencyRate($oldprice);

                    $currencySymbol = htmlspecialchars($convertedDifferenceFare['CurrencySymbol'], ENT_QUOTES, 'UTF-8');
                    $difference = htmlspecialchars($convertedDifferenceFare['ConvertPrice'], ENT_QUOTES, 'UTF-8');
                    $newPrice = htmlspecialchars($convertedNewPrice['ConvertPrice'], ENT_QUOTES, 'UTF-8');
                    $oldPrice = htmlspecialchars($convertedOldPrice['ConvertPrice'], ENT_QUOTES, 'UTF-8');

                    $price_change = '
                    <div class="col-lg-12 align-self-center">
                        <div class="row">
                            <p class="msg">
                                <samp>The hotel has ' . $sign_type['text'] . ' the fare </samp> 
                                <samp>' . $currencySymbol . ' ' . $difference . '.</samp>  
                                Please note that hotel fares are dynamic and subject to change. Select from the options below.
                            </p>
                        </div>
                    </div>
                    <div class="row justify-content-center"> 
                        <div class="col-12 col-lg-8 col-sm-8 p0 fare-update">
                            <table class="table">							  
                                <tbody>
                                    <tr> 
                                        <td><strong>Updated Fare</strong></td> 
                                        <td><samp>' . $currencySymbol . ' ' . $newPrice . '</samp></td>
                                    </tr>
                                    <tr> 
                                        <td><strong>Original Fare</strong></td> 
                                        <td><samp>' . $currencySymbol . ' ' . $oldPrice . '</samp></td>
                                    </tr>
                                    <tr class="total"> 
                                        <td><strong>Difference</strong></td>  
                                        <td><samp><b>' . $sign_type['sign'] . '</b>' . $currencySymbol . ' ' . $difference . '</samp></td>
                                    </tr>  
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="row justify-content-center mb15 mlr0 oneshow">
                        <div class="col col-auto">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Select Another Room</button>
                        </div> 
                        <div class="col col-auto">
                            <a href="' . $redirectUrl . '" class="btn go_button">Continue</a> 
                        </div>      
                    </div>';
                }

                $response = [
                    "Error" => [
                        "ErrorCode" => 0,
                        "ErrorMessage" => "",
                    ],
                    "Title" => $title,
                    "RedirectUrl" => $redirectUrl,
                    "GetConfirmShowBox" => $getConfirmshowbox,
                    "PriceChange" => $price_change,
                ];
            } else {
                $response = [
                    "Error" => [
                        "ErrorCode" => $response['Error']['ErrorCode'] ?? 500,
                        "ErrorMessage" => $response['Error']['ErrorMessage'] ?? "An unexpected error occurred.",
                    ]
                ];
            }

            return $this->response->setJSON($response);
        } else {
            return $this->response->setJSON([
                "Error" => [
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                ]
            ]);
        }
    }

    public function hotel_details()
    {
        $hotelblockRequest = $this->request->getGet();
        if (!$hotelblockRequest || !isset($hotelblockRequest['rtype']) || $hotelblockRequest['rtype'] !== "blcrm") {
            return $this->response->setJSON([
                "Error" => [
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                ]
            ]);
        }

        $HotelModel = new HotelModel();
        $whereClause = [
            "tts_search_token" => $hotelblockRequest['token'],
            "service" => "blockroom",
            "selected_index" => $hotelblockRequest['rindex']
        ];
        $whereHotelInfoClause = [
            "tts_search_token" => $hotelblockRequest['token'],
            "service" => "gethotelinfo",
            "selected_index" => $hotelblockRequest['rindex']
        ];

        $blockInfo = $HotelModel->getDataFromApi($whereClause, 'response');
        $hotelInfo = $HotelModel->getDataFromApi($whereHotelInfoClause, 'response');
        $HotelSearchInfo = $HotelModel->getDataFromApi([
            "tts_search_token" => $hotelblockRequest['token'],
            "service" => "search"
        ], 'request');

        if (!$blockInfo || !$hotelInfo || !$HotelSearchInfo) {
            return $this->response->setJSON([
                "Error" => [
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                ]
            ]);
        }

        $searchRequest = json_decode($HotelSearchInfo['request'], true);
        $response = json_decode($blockInfo['response'], true);
        $hotelInfo = json_decode($hotelInfo['response'], true);
        $region_type = ($searchRequest['CountryCode'] == 'IN') ? 'domestic' : 'international';
        $input = [
            "RegionType" => $region_type,
            'StarRating' => $response['Result']['StarRating']
        ];
        $couponRequest = array_merge($input, [
            'CheckInDate' => $searchRequest['CheckInDate'],
            'CheckOutDate' => $searchRequest['CheckOutDate']
        ]);

        // Get markup and discount data
        $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
        $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
        $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
        $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);

        $couponlistInfo = [];
        if (whitelabel['b2c_coupon'] == 'active') {
            $couponModel = new CouponModel();
            $couponlistInfo = $couponModel->getCouponList($couponRequest, $this->web_partner_id);
        }


        $BaseFare = 0;
        $Tax = 0;
        $OtherCharges = 0;
        $ServiceCharges = 0;
        $CommEarned = 0;
        $CGSTAmount = 0;
        $PublishedPrice = 0;
        $OfferedPrice = 0;
        $TDS = 0;
        $CurrencySymbol = '';
        $selectedMarkupDataInfo = array();
        $selectedDiscountDataInfo = array();
        if (isset($response['Result']['HotelRoomsDetails'])) {
            $HotelRoomsDetails =  $response['Result']['HotelRoomsDetails'];
            foreach ($HotelRoomsDetails as $key => $HotelRoomsDetail) {
                $HotelPrice = get_hotel_fare($input, $searchRequest['NoOfNights'], $searchRequest['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $HotelRoomsDetail['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
                $ConvertRoomPrice   = convertCurrencyRate($HotelPrice['CustomerFareBreakUp']);
                $PriceBreakup = $ConvertRoomPrice['ConvertedPrice'];
                $BaseFare = $BaseFare + $PriceBreakup['RoomPrice'];
                $Tax = $Tax + $PriceBreakup['Tax'];
                $OtherCharges = $OtherCharges + $PriceBreakup['OtherCharges'];
                $ServiceCharges = $ServiceCharges + $PriceBreakup['ServiceCharges'];
                $PublishedPrice = $PublishedPrice + $PriceBreakup['PublishedPrice'];
                $CGSTAmount = $CGSTAmount + $PriceBreakup['GST']['CGSTAmount'] + $PriceBreakup['GST']['IGSTAmount'] + $PriceBreakup['GST']['SGSTAmount'];
                $OfferedPrice = $OfferedPrice + $PriceBreakup['OfferedPrice'];
                $CommEarned = $CommEarned + $PriceBreakup['AgentCommission'] + $PriceBreakup['Discount'];
                $TDS = $TDS + $PriceBreakup['TDS'];
                $CurrencySymbol = $ConvertRoomPrice['CurrencySymbol'];
            }
        }

        $FareBreakup = [];
        $FareBreakup = [
            "FareBreakup" => [
                "BaseFare" => ["Value" => $BaseFare, "LabelText" => "Base Fare"],
                "Taxes" => ["Value" => $Tax + $OtherCharges, "LabelText" => "Taxes"],
                "ServiceAndOtherCharge" => ["Value" => $ServiceCharges, "LabelText" => "Other & Service Charges"],
                "GST" => ["Value" => $CGSTAmount, "LabelText" => "GST (+)"],
                "Discount" => ["Value" => 0, "LabelText" => "Discount (-)"]
            ],
            "TotalAmount" => ["Value" => $PublishedPrice, "LabelText" => "Pay Amount"],
            "OfferedFare" => ["Value" => $OfferedPrice + $TDS, "LabelText" => "Offered Fare"],
        ];

        $dial_code = $HotelModel->get_dial_code();
        $b2c_coupon = (whitelabel['b2c_coupon'] == 'active');
        $CommonModel = new CommonModel();
        $processedbutton = 1;
        $webpartnerBalance   = $CommonModel->web_partner_balance($this->web_partner_id);
        if (isset($webpartnerBalance['balance']) && $webpartnerBalance['balance'] < 0) {
            $processedbutton = 0;
        }

        $MetaInfoData = static_meta_information('Hotel', 'Details');

        $data = [
            'view' => "Hotel\Views\booking\hotel_pax_details",
            'blockResponse' => $response,
            'hotelInfoResponse' => $hotelInfo,
            'searchRequest' => $searchRequest,
            'couponlist' => $couponlistInfo,
            'dial_code' => $dial_code,
            'processedbutton' => $processedbutton,
            'web_partner_details' => $this->web_partner_details,
            'b2c_coupon' => !empty($b2c_coupon),
            'FareBreakup' => $FareBreakup,
            'CurrencySymbol' => $CurrencySymbol,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
        ];
        return view('template/default-layout', $data);
    }

    public function validate_travellers()
    {
        $data = $this->request->getPost();
        $validate = new Validation();
        $validationConfigArray = $validate->pax_validation($data);
        $this->validation->setRules($validationConfigArray);
        $rules = $this->validation->run($data);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $response = Hotel::savaData($data);
            return $this->response->setJSON($response);
        }
    }

    private function savaData($data)
    {
        $hotelblockRequest = $data;
        if ($hotelblockRequest) {
            $HotelModel = new HotelModel();
            $whereClauseBookingCheck = array("tts_search_token" => $hotelblockRequest['SearchTokenId']);
            $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "tts_search_token");
           
          
            if (!isset($bookingInfo['tts_search_token'])) {
                if ($hotelblockRequest['rtype'] == "blcrm") {
                    $whereClause = array("tts_search_token" => $hotelblockRequest['SearchTokenId'], "service" => "blockroom", "selected_index" => $hotelblockRequest['ResultIndex']);
                    $block_info = $HotelModel->getDataFromApi($whereClause, 'response,tts_index_response');
                    $block_response = json_decode($block_info['response'], true);
                
                    if (isset($block_response['Error']['ErrorCode']) && $block_response['Error']['ErrorCode'] == 0) {
                        $SearchTokenId = $block_response['SearchTokenId'];
                        $block_response = $block_response['Result'];
                        $whereHotelSearch = array("tts_search_token" => $hotelblockRequest['SearchTokenId'], "service" => "search");
                        $HotelSearchInfo = $HotelModel->getDataFromApi($whereHotelSearch, 'request');
                        $search_request_array = json_decode($HotelSearchInfo['request'], true);
                        $search_city_name = $HotelModel->get_city_name($search_request_array['DestinationCityId']);
                        $common_data = json_decode($block_info['tts_index_response'], true);
                        $common_data = $common_data;
                        if ($common_data['RegionType'] == 'domestic') {
                            $is_domestic = true;
                        } else {
                            $is_domestic = false;
                        }
                        $paxInfromation = $hotelblockRequest['pax'];
                        $leadPassengerName = $paxInfromation[1]['Adult'][1]['first_name'] . " " . $paxInfromation[1]['Adult'][1]['last_name'];
                        $Passenger = array();
                        $AdultCountArray = array();
                        foreach ($paxInfromation as $key => $pax_details) {
                            $AdultCount = 0;
                            foreach ($pax_details as $key1 => $paxinfos) {
                                foreach ($paxinfos as $key2 => $paxinfo) {
                                    if ($key1 == "Adult" && $key2 == 1) {
                                        $Lead = "true";
                                    } else {
                                        $Lead = "false";
                                    }
                                    if (isset($paxinfo['passport_no'])) {
                                        $PassportNo = $paxinfo['passport_no'];
                                    } else {
                                        $PassportNo = NULL;
                                    }
                                    if (isset($paxinfo['passport_issue_date'])) {
                                        $PassportIssueDate = $paxinfo['passport_issue_date'];
                                    } else {
                                        $PassportIssueDate = NULL;
                                    }
                                    if (isset($paxinfo['passport_expire_date'])) {
                                        $PassportExpDate = $paxinfo['passport_expire_date'];
                                    } else {
                                        $PassportExpDate = NULL;
                                    }
                                    if (isset($paxinfo['pancard'])) {
                                        $pancard = $paxinfo['pancard'];
                                    } else {
                                        $pancard = NULL;
                                    }
                                    if ($key1 == "Adult") {
                                        $AdultCount = $AdultCount + 1;
                                        $PaxType = 1;
                                        $Age = 0;
                                    } else {
                                        $PaxType = 2;
                                        $Age = $paxinfo['age'];
                                    }


                                    $Passenger[$key][] = array(
                                        "Title" => $paxinfo['title'],
                                        "FirstName" => $paxinfo['first_name'],
                                        "MiddleName" => NULL,
                                        "LastName" => $paxinfo['last_name'],
                                        "Phoneno" => $hotelblockRequest['mobile_number'],
                                        "Email" => $hotelblockRequest['email'],
                                        "PaxType" => $PaxType,
                                        "LeadPassenger" => $Lead,
                                        "Age" => $Age,
                                        "PassportNo" => $PassportNo,
                                        "PassportIssueDate" => $PassportIssueDate,
                                        "PassportExpDate" => $PassportExpDate,
                                        "PAN" => $pancard,
                                        "GSTCompanyAddress" => isset($hotelblockRequest['gst[address]']) ? $hotelblockRequest['gst[address]'] : null,
                                        "GSTCompanyContactNumber" => isset($hotelblockRequest['gst[phone]']) ? $hotelblockRequest['gst[phone]'] : null,
                                        "GSTCompanyEmail" => isset($hotelblockRequest['gst[email]']) ? $hotelblockRequest['gst[email]'] : null,
                                        "GSTCompanyName" => isset($hotelblockRequest['gst[name]']) ? $hotelblockRequest['gst[name]'] : null,
                                        "GSTNumber" => isset($hotelblockRequest['gst[number]']) ? $hotelblockRequest['gst[number]'] : null,

                                    );
                                }
                            }
                            $AdultCountArray[$key] = $AdultCount;
                        }
                        $selectedMarkupDataInfo = array();
                        $publishedFare = 0;
                        $WebPDiscount = 0;
                        $super_admin_commission = 0;
                        $web_partner_commission = 0;
                        $customer_commission = 0;
                        $WebPMarkUp = 0;
                        $web_partner_booking_total_price = 0;
                        $TTS_Invoice_Amount = 0;
                        $input = array(
                            "RegionType" => $common_data['RegionType'],
                            'StarRating' => $block_response['StarRating'],
                        );
                        $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
                        $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
                        $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
                        $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);
                        $selectedMarkupDataInfo = array();
                        $selectedDiscountDataInfo = array();
                        $CustomerFareBreakUp = array();
                        $WebPartnerFarebreakup = $common_data['WebPartnerFarebreakup'];
                        foreach ($block_response['HotelRoomsDetails'] as $blockroomKey => $RoomDetails) {
                            $HotelPrice = get_hotel_fare($input, $search_request_array['NoOfNights'], $search_request_array['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $RoomDetails['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
                            $selectedMarkupDataInfo = $HotelPrice['selectedMarkupDataInfo'];
                            $selectedDiscountDataInfo = $HotelPrice['selectedDiscountDataInfo'];
                            $CustomerFareBreakUp[$blockroomKey] = $HotelPrice['CustomerFareBreakUp'];
                            $WebPartnerFarebreakup[$blockroomKey] = $HotelPrice['WebPartnerFareBreakUp'];
                            if (isset($common_data['SuperAdminFarebreakup'][$blockroomKey]['AgentCommission']) && isset($common_data['SuperAdminFarebreakup'][$blockroomKey]['Discount'])) {
                                $super_admin_commission += $common_data['SuperAdminFarebreakup'][$blockroomKey]['AgentCommission'] + $common_data['SuperAdminFarebreakup'][$blockroomKey]['Discount'];
                            }
                            $web_partner_commission += $HotelPrice['WebPartnerFareBreakUp']['AgentCommission'] + $HotelPrice['WebPartnerFareBreakUp']['Discount'];
                            $customer_commission += $HotelPrice['CustomerFareBreakUp']['AgentCommission'] + $HotelPrice['CustomerFareBreakUp']['Discount'];
                            $blockroomKey = ($blockroomKey + 1);
                            $RoomDetails['AdultCount'] = $AdultCountArray[$blockroomKey];
                            $RoomDetails['HotelPassenger'] = $Passenger[$blockroomKey];
                            $hotel_rooms_details[] = $RoomDetails;
                            $TTS_Invoice_Amount = $TTS_Invoice_Amount + $HotelPrice['CustomerFareBreakUp']['OfferedPrice'];
                            $web_partner_booking_total_price = $web_partner_booking_total_price + $HotelPrice['WebPartnerFareBreakUp']['OfferedPrice'] + $HotelPrice['WebPartnerFareBreakUp']['TDS'];
                        }
                        $couponAmount = 0;
                        $couponInfo = array();
                        $couponModel = new couponModel();
                        $appliedCouponCode = $couponModel->getCouponByToken($hotelblockRequest['SearchTokenId'], $this->web_partner_id);
                        if (!empty($appliedCouponCode)) {
                            $AppliedcouponInfo = json_decode($appliedCouponCode['couponInfo'], true);
                            if ($AppliedcouponInfo['coupon_type'] == 'fixed') {
                                $couponAmount = $AppliedcouponInfo['value'];
                            } else {
                                $cAmount = ($AppliedcouponInfo['value'] * $TTS_Invoice_Amount) / 100;
                                $couponAmount = ($cAmount > $AppliedcouponInfo['max_limit']) ? $AppliedcouponInfo['max_limit'] : $cAmount;
                            }
                            $couponInfo = array(
                                'couponCode' => $AppliedcouponInfo['code'],
                                'couponAmount' => $couponAmount,
                                'beforeAmount' => $TTS_Invoice_Amount,
                                'AfterAmount' => ($TTS_Invoice_Amount - $couponAmount)
                            );
                        } else {
                            $couponModel->remove_promo_log($hotelblockRequest['SearchTokenId'], $this->web_partner_id);
                        }
                        $CustomerFareBreakUp['couponAmount'] = $couponAmount;
                        $TTS_Invoice_Amount = $TTS_Invoice_Amount - $couponAmount;
                        $last_cancellation_date = $hotel_rooms_details[0]['LastCancellationDate'];
                        $last_voucher_date = $hotel_rooms_details[0]['LastVoucherDate'];

                        $selectedWebsiteCurrency  = isset($_SESSION['selected_website_currency']) ? $_SESSION['selected_website_currency'] : null;
                        $website_currencies  = isset($_SESSION['website_currencies']) ? $_SESSION['website_currencies'] : [];
                        $selectedcurrencyCode = isset($selectedWebsiteCurrency['currency']) ? $selectedWebsiteCurrency['currency'] : null;
                        $selectedCoversionRate = isset($selectedWebsiteCurrency['convertion_rate']) ? $selectedWebsiteCurrency['convertion_rate'] : 1;
                        $currencyArray  = !empty($website_currencies) ? array_column($website_currencies, 'currency', 'default_currency') : [];

                        $hotel_booking = array(
                            'tts_search_token' => $SearchTokenId,
                            'web_partner_id' => $this->web_partner_id,
                            'lead_passenger_name' => $leadPassengerName,
                            'contact_number' => $hotelblockRequest['mobile_number'],
                            'contact_email_id' => $hotelblockRequest['email'],
                            'city' => $search_city_name['destination'],
                            'resultIndex' => $hotelblockRequest['ResultIndex'],
                            'city_id' => $search_request_array['DestinationCityId'],
                            'check_in_date' => $search_request_array['CheckInDate'],
                            'check_out_date' => $search_request_array['CheckOutDate'],
                            'no_of_nights' => $search_request_array['NoOfNights'],
                            'no_of_rooms' => $search_request_array['NoOfRooms'],
                            'room_guests' => json_encode($search_request_array['RoomGuests']),
                            'country_code' => $search_request_array['CountryCode'],
                            'guest_nationality' => $search_request_array['GuestNationality'],
                            'is_domestic' => $is_domestic,
                            'hotel_code' => $hotelblockRequest['hcode'],
                            'hotel_name' => $block_response['HotelName'],
                            'inventory_source' => isset($block_response['InventorySource'])?$block_response['InventorySource']:'',
                            'star_rating' => $block_response['StarRating'],
                            'address1' => $block_response['AddressLine1'],
                            'address2' => $block_response['AddressLine2'],
                            'latitude' => $block_response['Latitude'],
                            'longitude' => $block_response['Longitude'],
                            'gst_info' => isset($hotelblockRequest['gst']) ? json_encode($hotelblockRequest['gst']) : null,
                            'hotel_norms' => tag_exist($block_response['HotelNorms']),
                            'hotel_policy_detail' => tag_exist($block_response['HotelPolicyDetail']),
                            'last_cancellation_date' => $last_cancellation_date,
                            'last_voucher_date' => $last_voucher_date,
                            'hotel_rooms_details' => json_encode($hotel_rooms_details),
                            'api_supplier' => $common_data['Supplier'],
                            'super_admin_fare_break_up' => json_encode($common_data['SuperAdminFarebreakup']),
                            'supplier_fare_break_up' => isset($common_data['Supplierbreakup']) ? json_encode($common_data['Supplierbreakup']) : NULL,
                            'web_partner_fare_break_up' => json_encode($WebPartnerFarebreakup),
                            'customer_fare_break_up' => json_encode($CustomerFareBreakUp),
                            'coupon_info' => json_encode($couponInfo),
                            'super_admin_commision' => $super_admin_commission,
                            'web_partner_commision' => $web_partner_commission,
                            'customer_commision'       => $customer_commission,
                            'payment_mode' => 'Online',
                            'payment_status' => 'Processing',
                            'booking_status' => 'Processing',
                            'total_price' => $TTS_Invoice_Amount,
                            'wl_customer_id' => $this->wl_customer_id,
                            'web_partner_booking_total_price' => $web_partner_booking_total_price,
                            'web_partner_payment_status' => 'Processing',
                            'created' => create_date(),
                            'booking_source' => $this->booking_source,
                            'booking_currency' => $selectedcurrencyCode,
                            'currency_rate' => $selectedCoversionRate,
                            'default_currency' => $currencyArray['active'],
                        );

                        $booking_id = $HotelModel->insertData('hotel_booking_list', $hotel_booking);
                        /*------------------ Update Booking  Data ----------------------------*/
                        $super_admin__booking_pre_fix_code = $HotelModel->service_booking_pre_fix_code($this->web_partner_id)['hotel_pre_fix'];
                        $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_id;
                        $booking_update_data = array(
                            'booking_ref_number' => $booking_ref_number,
                        );
                        $HotelModel->updateUserData('hotel_booking_list', ['id' => $booking_id], $booking_update_data);

                        if (!empty($couponInfo)) {
                            $HotelModel->updateUserData('coupon_log', ['web_partner_id' => $this->web_partner_id, 'token' => $SearchTokenId], ['booking_ref_number' => $booking_ref_number]);
                        }
                        /*------------------ Update BookingData ----------------------------*/
                        $paymentkey = dev_encode(json_encode(array('service' => 'hotel', 'booking_id' => $booking_id)));
                        $url = site_url('payment/opt/') . $paymentkey;
                        return array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $url);
                    } else {
                        return array("StatusCode" => 9, "ErrorMessage" => isset($blockInfo['Error']['ErrorMessage']) ? $blockInfo['Error']['ErrorMessage'] : "Technical problem occured");
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
            return $this->response->redirect(site_url('hotel/error?errormessage=Payment Record not found'));
        } else {
            $data = [
                'title' => $this->title,
                'view' => "Hotel\Views\booking\payment_redirect",
                'payment_token' => $payment_token,
                'paymentdata' => $paymentdata
            ];
            return view('template/default-layout', $data);
        }
    }

    function hotelBooking()
    {
        $hotelbookRequest = $this->request->getPOST();
        if ($hotelbookRequest) {
            $HotelModel = new HotelModel();
            $payment_token = $hotelbookRequest['payment_token'];
            $paymentdata = json_decode(dev_decode($payment_token), true);
            if ($paymentdata) {
                $bookingId = $paymentdata['booking_id'];
                $whereClauseBookingCheck = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id);
                $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");
                if (isset($bookingInfo['id']) && $bookingInfo['id'] == $bookingId) {
                    if (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == "Successful" && $bookingInfo['booking_status'] == "Processing" && $bookingInfo['book_request'] != "requested") {
                        $blockRoomInfo = json_decode($bookingInfo['hotel_rooms_details'], true);
                        $HotelRoomDetail = array();
                        $couponData = json_decode($bookingInfo['coupon_info'], true);
                        $couponModel = new couponModel();
                        if (!empty($couponData) && isset($couponData['couponCode'])) {
                            $couponInfo = $couponModel->getData('coupon_hotel', ['code' => $couponData['couponCode'], 'status' => 'active', 'web_partner_id' => $this->web_partner_id], 'use_limit');
                            if (isset($couponInfo['use_limit'])) {
                                $couponModel->updateData('coupon_hotel', ['code' => $couponData['couponCode'], 'status' => 'active', 'web_partner_id' => $this->web_partner_id], ['use_limit' => $couponInfo['use_limit'] - 1]);
                            }
                        }
                        if ($blockRoomInfo) {
                            foreach ($blockRoomInfo as $blockroomKey => $blockRoom) {
                                $HotelRoomDetail[$blockroomKey] = array("RoomIndex" => $blockRoom['RoomIndex'], "HotelPassenger" => $blockRoom['HotelPassenger']);
                            }
                            $BookingRequest = array(
                                "ResultIndex" => isset($bookingInfo['resultIndex']) ? $bookingInfo['resultIndex'] : "",
                                "HotelCode" => isset($bookingInfo['hotel_code']) ? $bookingInfo['hotel_code'] : "",
                                "HotelName" => isset($bookingInfo['hotel_name']) ? $bookingInfo['hotel_name'] : "",
                                "GuestNationality" => isset($bookingInfo['guest_nationality']) ? $bookingInfo['guest_nationality'] : "",
                                "NoOfRooms" => isset($bookingInfo['no_of_rooms']) ? $bookingInfo['no_of_rooms'] : "",
                                "ClientReferenceNo" => 0,
                                "IsVoucherBooking" => "true",
                                "HotelRoomsDetails" => $HotelRoomDetail,
                                "UserIp" => $this->request->getIPAddress(),
                                "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                            );
                            $service = "book";
                            $url = $this->Services . $service;
                            $response = TTSRequest($BookingRequest, $url, $service);
                          
                            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                                $updateData = array("book_request" => "requested");
                                $whereCondition = array("id" => $bookingId);
                                $HotelModel->updateUserData("hotel_booking_list", $whereCondition, $updateData);
                                $getBookingInfo = $HotelModel->getData("hotel_booking_list", $whereCondition, "booking_status,booking_ref_number,agent_fare_break_up,wl_agent_id,gst_info,confirmation_no,customer_fare_break_up,wl_customer_id");
                                if ($getBookingInfo['booking_status'] == 'Confirmed') {
                                    $agent_fare_break_up = json_decode($getBookingInfo['customer_fare_break_up'], true);
                                    $gst_info = json_decode($getBookingInfo['gst_info'], true);
                                    $GstNumber = isset($data['number']) && $gst_info['number'] != "" ? $gst_info['number'] : "";
                                    $checkTaxableInvoce = checkTaxableNonTaxableINV($agent_fare_break_up, $GstNumber, 'hotel', 'INV');
                                    $INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'hotel');
                                    $financialYear = get_financial_year();
                                    $whereCondition = array();
                                    $whereCondition['service'] = 'hotel';
                                    $whereCondition['web_partner_id'] = $this->web_partner_id;
                                    $whereCondition['invoice_type'] = 'INV';
                                    $whereCondition['financial_year'] = $financialYear;
                                    $otherParameter['financialYear'] = $financialYear;
                                    $otherParameter['service'] = 'hotel';
                                    $otherParameter['invoice_type'] = 'INV';
                                    $otherParameter['INVPrifix'] = $INVPrifix;
                                    $otherParameter['web_partner_id'] = $this->web_partner_id;
                                    $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
                                    $CommonModel = new CommonModel();
                                    $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                                    $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                                    $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                                    $InvoiceupdateData = $InvoiceInfoData['updateData'];
                                    $agent_account_log = $HotelModel->getData("customer_account_log", ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "customer_id" => $getBookingInfo['wl_customer_id']], "service_log");
                                    $service_log = json_decode($agent_account_log['service_log'], true);
                                    $service_log['ConfirmationNo'] = $getBookingInfo['confirmation_no'];
                                    $HotelModel->updateUserData('customer_account_log', ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "customer_id" => $getBookingInfo['wl_customer_id'], "web_partner_id" => $this->web_partner_id], ["invoice_number" => $InvoiceNumber, "service_log" => json_encode($service_log)]);
                                    $HotelModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                                    /*    $ticketData  =  dev_encode(json_encode(array("BookingId" => $bookingInfo['id'], "BookingToken" => $bookingInfo['tts_search_token']))); */
                                }
                                $ticketData = $bookingInfo['booking_ref_number'];
                                return $this->response->redirect(site_url('hotel/confirmation/' . $ticketData));
                            } else {
                                $updateData = array("book_request" => "requested");
                                $whereCondition = array("id" => $bookingId);
                                $HotelModel->updateUserData("hotel_booking_list", $whereCondition, $updateData);
                                return $this->response->redirect(site_url('hotel/error?errormessage=' . $response['Error']['ErrorMessage']));
                            }
                        } else {
                            return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
                        }
                    } else {
                        return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
                    }
                } else {
                    return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('hotel/error?errormessage=Request Not Allowed'));
            }
        }
    }

    function error()
    {
        $error = $this->request->getGET('errormessage');
        return view('template/custom-error-layout', ['error_message' => $error]);
    }


    public function getVoucherInvoice()
    {
        $HotelModel = new HotelModel();
        $getData = $this->request->getPOST();
        $getTicketInvioceType = array("PrintVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
        if (!$this->request->isAJAX()) {
            $getVoucherInvioceType = array("PrintVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {
                    $TicketViewRequest = array(
                        "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => "Voucher",
                        "UserType" => "wl-customer",
                        "ViewService" => "View",
                        "WithAgencyDetail" => "1",
                        "ViewSize" => "",
                        "RequestBy" => "WebPartner",
                    );

                    if ($getData['type'] == "PrintVoucher") {
                        $TicketViewRequest = array(
                            "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                            "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "wl-customer",
                            "ViewService" => "View",
                            "WithPrice" => isset($getData['price']) ? 1 : 0,
                            "WithAgencyDetail" => 1,
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner",
                        );
                    } else {
                        $TicketViewRequest = array(
                            "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                            "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => "wl-customer",
                            "ViewService" => "View",
                            "WithPrice" => 1,
                            "WithAgencyDetail" => 1,
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner",
                        );
                    }

                    $url = $this->Services . 'generate-wl-voucher-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Hotel\Views\booking\print_voucher",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/default-layout', $data);
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
                        $TicketViewRequest = array(
                            "BookigId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                            "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                            "HtmlType" => "Voucher",
                            "UserType" => "wl-customer",
                            "ViewService" => "Email",
                            "WithAgencyDetail" => "1",
                            "ViewSize" => "",
                            "RequestBy" => "WebPartner",
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

    public function bookingLists()
    {
        $HotelBookingModel = new HotelBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {
            $list = $HotelBookingModel->search_data($getData);
        } else {
            $list = $HotelBookingModel->hotel_booking_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
        }
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-booking-list",
            "list" => $list,
            'pager' => $HotelBookingModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function confirmation()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $HotelBookingModel = new HotelBookingModel();
        $BookingConfirmation = $HotelBookingModel->hotel_booking_info($this->web_partner_id, $bookingReferenceNumber, $this->wl_customer_id, $this->booking_source);
        $publishedFare = 0;
        $offeredFare = 0;
        $basePrice = 0;
        $Tax = 0;
        $OtherCharges = 0;
        $ServiceCharges = 0;
        $gst = 0;
        $discount = 0;
        $couponAmount = 0;
        $conveniencefee = 0;
        $TDS = 0;
        if ($BookingConfirmation) {

            $conveniencefee = $BookingConfirmation['conveniencefee'];
            $HotelRoomsDetails = json_decode($BookingConfirmation['hotel_rooms_details'], true);

            $customer_break_up = json_decode($BookingConfirmation['customer_fare_break_up'], true);

            $convertBookingCurrencyRate =  convertBookingCurrencyRate($customer_break_up, $BookingConfirmation['booking_currency'], $BookingConfirmation['default_currency'], $BookingConfirmation['currency_rate']);

            $CurrencySymbol =  $convertBookingCurrencyRate['CurrencySymbol'];
            $customer_break_up =  $convertBookingCurrencyRate['ConvertPrice'];



            foreach ($HotelRoomsDetails as $roomkey => $HotelRooms) {
                $Hotelprice = $customer_break_up[$roomkey];
                $basePrice = $basePrice + $Hotelprice['RoomPrice'];
                if (isset($Hotelprice['Tax'])) {
                    $Tax = $Tax + $Hotelprice['Tax'];
                }
                if (isset($Hotelprice['OtherCharges']) && isset($Hotelprice['ServiceCharges'])) {
                    $OtherCharges = $OtherCharges + $Hotelprice['OtherCharges'];
                    $ServiceCharges = $ServiceCharges + $Hotelprice['ServiceCharges'];
                }
                $gst = $gst + $Hotelprice['GST']['CGSTAmount'] + $Hotelprice['GST']['IGSTAmount'] + $Hotelprice['GST']['SGSTAmount'];
                $publishedFare = $publishedFare + $Hotelprice['PublishedPrice'];
                $offeredFare = $offeredFare + $Hotelprice['OfferedPrice'];
                $discount = $discount + $Hotelprice['Discount'] + $Hotelprice['AgentCommission'];
                $couponAmount = isset($customer_break_up['couponAmount']) ? $customer_break_up['couponAmount'] : 0;
            }
        }
        $FareBreakUp = array(
            'HotelPrice' => array("Value" => round_value($basePrice), "LabelText" => "Base Fare"),
            'Tax' => array("Value" => round_value($Tax + $OtherCharges), "LabelText" => "Tax"),
            'ServiceCharges' => array("Value" => round_value($ServiceCharges), "LabelText" => "Service Charges"),
        );

        if ($discount) {
            $FareBreakUp['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
        }
        $FareBreakUp['GST'] = array("Value" => round_value($gst), "LabelText" => "GST (+)");
        if ($couponAmount) {
            $FareBreakUp['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promocode (-)");
        }
        if ($conveniencefee) {
            $FareBreakUp['conveniencefee'] = array("Value" => round_value($conveniencefee), "LabelText" => "Convenience Fee (+)");
        }
        $FareBreakUp['TotalAmount'] = array("Value" => round_value($offeredFare + $conveniencefee - $couponAmount), "LabelText" => "Pay Amount");








        //code to insert in email logs starts here 


        $dataURL = array(
            'BookingRefrenceNumber' => $BookingConfirmation['booking_ref_number'],
            'service' => "hotel",
        );



        $checkInDate = new \DateTime($BookingConfirmation['check_in_date'], new \DateTimeZone('UTC'));
        $checkInDate->setTime(0, 0, 0);
        $checkIntimestamp = $checkInDate->getTimestamp();



        $checkOutDate = new \DateTime($BookingConfirmation['check_out_date'], new \DateTimeZone('UTC'));
        $checkOutDate->setTime(0, 0, 0);
        $checkOuttimestamp = $checkOutDate->getTimestamp();




        //line number 1180 ends here 


        //new code 
        $datatouseEmailLogs['url'] = site_url('booking-review/' . dev_encode(implode(",", $dataURL)));
        $datatouseEmailLogs['PassengerName'] = isset($BookingConfirmation['lead_passenger_name']) ? $BookingConfirmation['lead_passenger_name'] . ' ' : '';
        $datatouseEmailLogs['createdDate'] = isset($BookingConfirmation['created']) ? $BookingConfirmation['created'] : '';


        $datatouseEmailLogs['service'] = isset($dataURL['service']) ? ucfirst($dataURL['service']) : '';

        $datatouseEmailLogs['TravelStartDate'] = isset($checkIntimestamp) ? $checkIntimestamp : '';
        $datatouseEmailLogs['BookingRefrenceNumber'] = isset($BookingConfirmation['booking_ref_number']) ? $BookingConfirmation['booking_ref_number'] : '';

        $datatouseEmailLogs['logo'] = isset($this->web_partner_details['company_logo']) ? $this->web_partner_details['company_logo'] : '';
        $datatouseEmailLogs['company_name'] = isset($this->web_partner_details['company_name']) ? $this->web_partner_details['company_name'] : '';
        $datatouseEmailLogs['address'] = isset($this->web_partner_details['address']) ? $this->web_partner_details['address'] : '';
        $datatouseEmailLogs['city'] = isset($this->web_partner_details['city']) ? $this->web_partner_details['city'] : '';
        $datatouseEmailLogs['state'] = isset($this->web_partner_details['state']) ? $this->web_partner_details['state'] : '';
        $datatouseEmailLogs['country'] = isset($this->web_partner_details['country']) ? $this->web_partner_details['country'] : '';
        $datatouseEmailLogs['facebook_link'] = isset($this->web_partner_details['facebook_link']) ? $this->web_partner_details['facebook_link'] : '';
        $datatouseEmailLogs['linkedin_link'] = isset($this->web_partner_details['linkedin_link']) ? $this->web_partner_details['linkedin_link'] : '';
        $datatouseEmailLogs['instagram_link'] = isset($this->web_partner_details['instagram_link']) ? $this->web_partner_details['instagram_link'] : '';
        $datatouseEmailLogs['youtube_link'] = isset($this->web_partner_details['youtube_link']) ? $this->web_partner_details['youtube_link'] : '';
        $datatouseEmailLogs['twitter_link'] = isset($this->web_partner_details['twitter_link']) ? $this->web_partner_details['twitter_link'] : '';



        $passengerDataTouse = json_decode($BookingConfirmation['hotel_rooms_details'], true);
        $TravellerEmail =  $passengerDataTouse[0]['HotelPassenger'][0]['Email'];




        $messageData =  view('Views/emails/feedback-email', $datatouseEmailLogs);


        //new code 


        $emamilLogsData = array(
            'package_booking_date' => $checkIntimestamp,
            'package_end_date' => $checkOuttimestamp,
            'mail_send_status' => '0',
            'service' => 'Hotel_booking',
            'created' => create_date(),
            'booking_info' => $BookingConfirmation['booking_ref_number'],
            'from_email' => $this->web_partner_details['support_email'],
            'to_email' => $TravellerEmail,
            'subject' => "Hotel Booking Review",
            'message' => $messageData,
            'web_partner_id' => $this->web_partner_details['id'],
            'bcc_email' => isset($this->web_partner_details['bcc_email']) ? $this->web_partner_details['bcc_email'] : "",
            'cc_email' => isset($this->web_partner_details['cc_email']) ? $this->web_partner_details['cc_email'] : "",
            'email_type' => "Booking Review Logs",
            'booking_source' => 'B2C',
        );





        $logs_email_insert = $HotelBookingModel->insertIntoLogs("logs_email", $emamilLogsData);

        // code to insert email logs ends here 

        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-confirmation",
            "hotelInfo" => $BookingConfirmation,
            "farebreakup" => $FareBreakUp,
            "CurrencySymbol" => $CurrencySymbol,
        ];





        return view('template/default-layout', $data);
    }

    public function bookingDetails()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $HotelBookingModel = new HotelBookingModel();

        $BookingDetail = $HotelBookingModel->hotel_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
        if ($BookingDetail) {
            $amendment_list = $HotelBookingModel->amendment_list($this->web_partner_id, $BookingDetail['id']);

            $publishedFare = 0;
            $offeredFare = 0;
            $CommEarned = 0;
            $TDS = 0;
            if ($BookingDetail) {
                $HotelRoomsDetails = json_decode($BookingDetail['hotel_rooms_details'], true);
                foreach ($HotelRoomsDetails as $HotelRooms) {
                    $publishedFare = $publishedFare + $HotelRooms['Price']['PublishedPrice'];
                    $offeredFare = $offeredFare + $HotelRooms['Price']['OfferedPrice'];
                    $CommEarned = $CommEarned + $HotelRooms['Price']['AgentCommission'] + $HotelRooms['Price']['Discount'];
                    $TDS = $TDS + $HotelRooms['Price']['TDS'];
                }
            }
            $data = [
                'title' => $this->title,
                'view' => "Hotel\Views\listing\hotel-booking-detail",
                "bookingDetail" => $BookingDetail,
                "amendment_list" => $amendment_list,
                "publishedFare" => $publishedFare,
                "offeredFare" => $offeredFare,
                "CommEarned" => $CommEarned,
                "TDS" => $TDS,
            ];
            return view('template/sidebar-layout', $data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");

            $this->session->setFlashdata('Message', $message);
            $url = site_url('hotel/bookings/');
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
                $whereClauseBookingCheck = array("booking_ref_number" => $BookingRefNumber, 'web_partner_id' => $this->web_partner_id, 'wl_customer_id' => $this->wl_customer_id);
                $bookingInfo = $HotelModel->getData("hotel_booking_list", $whereClauseBookingCheck, "*");
                if ($BookingRefNumber && $bookingInfo) {
                    $request = array(
                        "BookingId" => $bookingInfo['id'],
                        "Type" => $this->request->getPOST('amendment_type'),
                        "Remarks" => $this->request->getPOST('remark'),
                        "RequesterInfo" => array("Requester" => "WhitelabelB2C", "wl_customer_id" => $this->wl_customer_id),
                    );
                    $service = "submitamendment";
                    $url = $this->Services . $service;
                    $response = TTSRequest($request, $url, $service); //prd($response);
                    if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
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
        $getData = $this->request->getGET();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $HotelAmendmentModel->search_data($this->web_partner_id, $getData);
        } else {
            $list = $HotelAmendmentModel->hotel_amendment_list($this->web_partner_id);
        }
        //prd($list);
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views/listing/hotel-amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $HotelAmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }

    public function amendmentsDetails()
    {
        $uri = service('uri');
        $amendmentId = $uri->getSegment(3); //prd($amendmentId);
        $amendmentId = dev_decode($amendmentId);
        $HotelAmendmentModel = new HotelAmendmentModel();
        $BookingDetail = $HotelAmendmentModel->hotel_amendment_detail($this->web_partner_id, $this->wl_customer_id, $amendmentId);
        $publishedFare = 0;
        $offeredFare = 0;
        $CommEarned = 0;
        $TDS = 0;
        if ($BookingDetail) {
            $HotelRoomsDetails = json_decode($BookingDetail['customer_fare_break_up'], true);
            $couponAmount = isset($HotelRoomsDetails['couponAmount']) ? $HotelRoomsDetails['couponAmount'] : 0;
            unset($HotelRoomsDetails['couponAmount']);
            foreach ($HotelRoomsDetails as $HotelRooms) {
                $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                $TDS = $TDS + $HotelRooms['TDS'];
            }
        }
        $data = [
            'title' => $this->title,
            'view' => "Hotel\Views\listing\hotel-amendments-detail",
            "AmendmentInfo" => $BookingDetail,
            "publishedFare" => $publishedFare,
            "offeredFare" => $offeredFare,
            "CommEarned" => $CommEarned,
            "couponAmount" => $couponAmount,
            "TDS" => $TDS,
        ];
        return view('template/default-layout', $data);
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
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => 'Coupon Code Required');
                return $this->response->setJSON($data_validation);
            } else {
                $couponModel = new CouponModel();
                $inputData = $this->request->getPOST();
                $CouponCodeExists = $couponModel->getCouponByToken($inputData['SearchTokenId'], $this->web_partner_id);
                if (!empty($CouponCodeExists)) {
                    $couponModel->remove_promo_log($inputData['SearchTokenId'], $this->web_partner_id);
                }
                $searchRequest = array();
                $hotelInfo = array();
                $HotelModel = new HotelModel();
                $whereHotelSearch = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "search");
                $HotelSearchInfo = $HotelModel->getDataFromApi($whereHotelSearch, 'request');
                $whereHotelInfoClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "gethotelinfo", "selected_index" => $inputData['rindex']);
                $hotelInfo = $HotelModel->getDataFromApi($whereHotelInfoClause, 'response');
                $hotelInfo = json_decode($hotelInfo['response'], true);
                $searchRequest = json_decode($HotelSearchInfo['request'], true);
                if ($searchRequest['CountryCode'] == 'IN') {
                    $region_type = 'domestic';
                    $searchRequest['RegionType'] = 'domestic';
                } else {
                    $region_type = 'international';
                    $searchRequest['RegionType'] = 'international';
                }
                if (isset($hotelInfo['Result']) && !empty($hotelInfo['Result'])) {
                    $searchRequest['StarRating'] = $hotelInfo['Result']['StarRating'];
                }
                $searchRequest['code'] = $inputData['couponCode'];
                $coupounInfo = $couponModel->getDataByCode($searchRequest, $this->web_partner_id);
                if (empty($coupounInfo)) {
                    $errors = 'Invalid Promo code';
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => $errors);
                    return $this->response->setJSON($data_validation);
                } else {

                    $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "blockroom", "selected_index" => $inputData['rindex']);
                    $blockInfo = $HotelModel->getDataFromApi($whereClause, 'response');
                    if ($blockInfo) {
                        $FareBreakup = array();
                        $response = json_decode($blockInfo['response'], true);
                        if (isset($response['Result']['HotelRoomsDetails'])) {
                            $HotelRoomsDetails = $response['Result']['HotelRoomsDetails'];
                            $fareSummary = '';
                            $BaseFare = 0;
                            $Tax = 0;
                            $OtherCharges = 0;
                            $ServiceCharges = 0;
                            $CommEarned = 0;
                            $CGSTAmount = 0;
                            $PublishedPrice = 0;
                            $OfferedPrice = 0;
                            $TDS = 0;
                            $couponAmount = 0;
                            $totalAmount = 0;

                            $input = array(
                                "RegionType" => $region_type,
                                'StarRating' => $searchRequest['StarRating'],
                            );

                            $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
                            $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
                            $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
                            $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);
                            $selectedMarkupDataInfo = array();
                            $selectedDiscountDataInfo = array();
                            foreach ($HotelRoomsDetails as $key => $HotelRoomsDetail) {
                                $HotelPrice = get_hotel_fare($input, $searchRequest['NoOfNights'], $searchRequest['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $HotelRoomsDetail['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
                                $convertedAgentFareBreakUpFare = convertCurrencyRate($HotelPrice['CustomerFareBreakUp']);
                                $PriceBreakup = $convertedAgentFareBreakUpFare['ConvertedPrice'];
                                $CurrencySymbol  = $convertedAgentFareBreakUpFare['CurrencySymbol'];
                                $BaseFare = $BaseFare + $PriceBreakup['RoomPrice'];
                                $Tax = $Tax + $PriceBreakup['Tax'];
                                $OtherCharges = $OtherCharges + $PriceBreakup['OtherCharges'];
                                $ServiceCharges = $ServiceCharges + $PriceBreakup['ServiceCharges'];
                                $PublishedPrice = $PublishedPrice + $PriceBreakup['PublishedPrice'];
                                $CGSTAmount = $CGSTAmount + $PriceBreakup['GST']['CGSTAmount'] + $PriceBreakup['GST']['IGSTAmount'] + $PriceBreakup['GST']['SGSTAmount'];
                                $OfferedPrice = $OfferedPrice + $PriceBreakup['OfferedPrice'];
                                $CommEarned = $CommEarned + $PriceBreakup['AgentCommission'] + $PriceBreakup['Discount'];
                                $TDS = $TDS + $PriceBreakup['TDS'];
                                $totalAmount = ($OfferedPrice + $TDS);
                            }


                            $couponAmount = 0;
                            if ($coupounInfo) {
                                $convertedPromoCode = convertCurrencyRate($coupounInfo['value']);
                                $CurrencySymbol  = $convertedPromoCode['CurrencySymbol'];
                                $coupounInfoAmounts = $convertedPromoCode['ConvertedPrice'];
                                if ($coupounInfo['coupon_type'] == 'fixed') {
                                    $couponAmount = $coupounInfoAmounts;
                                } else {
                                    $cAmount = ($coupounInfoAmounts * $totalAmount) / 100;
                                    $couponAmount = ($cAmount > $coupounInfo['max_limit']) ? $coupounInfo['max_limit'] : $cAmount;
                                }
                            }

                            if (($OfferedPrice - $couponAmount) < 0) {
                                $errors = 'Promocode Not Applicable';
                                $data_validation = array("StatusCode" => 1, "ErrorMessage" => $errors);
                                return $this->response->setJSON($data_validation);
                            }
                        }
                        $FareBreakup = [
                            "FareBreakup" => [
                                "BaseFare" => ["Value" => $BaseFare, "LabelText" => "Base Fare"],
                                "Taxes" => ["Value" => $Tax + $OtherCharges, "LabelText" => "Taxes"],
                                "ServiceAndOtherCharge" => ["Value" => $ServiceCharges, "LabelText" => "Other & Service Charges"],
                                "GST" => ["Value" => $CGSTAmount, "LabelText" => "GST (+)"],
                                "Discount" => ["Value" => 0, "LabelText" => "Discount (-)"]
                            ],
                            /*  "TotalAmount" => ["Value" => $PublishedPrice , "LabelText" => "Pay Amount"], */
                            "OfferedFare" => ["Value" => $totalAmount - $couponAmount, "LabelText" => "Pay Amount"],
                        ];
                        if ($couponAmount > 0) {
                            $FareBreakup['FareBreakup']['Promocode'] = ["Value" => $couponAmount, "LabelText" => "Apply Promocode (-)"];
                        }



                        $fareSummary = '<div class="card-header bg-transparent p-3">
                        <h6 class="mb-0"> Fare Summary</h6>
                     </div><div class="card-body">';
                        if (isset($FareBreakup['FareBreakup']) && !empty($FareBreakup['FareBreakup'])) {
                            foreach ($FareBreakup['FareBreakup'] as $farebreakup) {
                                $labelText = htmlspecialchars($farebreakup['LabelText'] ?? '', ENT_QUOTES, 'UTF-8');
                                $value = htmlspecialchars($farebreakup['Value'] ?? '0', ENT_QUOTES, 'UTF-8');

                                $fareSummary .= '
                                
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span>' . $labelText . '</span>
                                            <span>' . $CurrencySymbol . ' ' . $value  . '</span>
                                        </div>
                                    ';
                            }
                        }


                        $fareSummary .= '</div>'; // Close card body 
                        $totalLabelText = $totalValue = $OfferLabelText = $OfferValue = $PromocodeLabelText = $PromocodeValue = '';

                        // Check if OfferedFare is set
                        if (isset($FareBreakup['OfferedFare'])) {
                            $OfferLabelText = htmlspecialchars($FareBreakup['OfferedFare']['LabelText'] ?? '', ENT_QUOTES, 'UTF-8');
                            $OfferValue = htmlspecialchars($FareBreakup['OfferedFare']['Value'] ?? '0', ENT_QUOTES, 'UTF-8');
                        }

                        $fareSummary .= '
                        <div class="card-footer bg-transparent p-3">
                            
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><strong>' . $OfferLabelText . '</strong></span>
                                    <span><span><strong>' . $CurrencySymbol . ' ' . $OfferValue . '</strong></span></span>
                                </div> 

                            
                        </div>
                        </div>'; // Close card 


                        $couponData = [];
                        $couponData['token'] = $inputData['SearchTokenId'];
                        $couponData['use_for'] = 'Hotel';
                        $couponData['web_partner_id'] = $this->web_partner_id;
                        $couponData['coupon_code'] = $coupounInfo['code'];
                        unset($coupounInfo['id']);
                        $couponData['couponInfo'] = json_encode($coupounInfo);
                        $couponData['created'] = strtotime('now');
                        $insertId = $couponModel->insertData('coupon_log', $couponData);
                        if ($insertId) {
                            $message = array("StatusCode" => 0, "Message" => "PromoCode Applied Successfully", "Class" => "success_popup", "Reload" => "true", "FareBreakUpData" => $fareSummary, 'CouponID' => $insertId);
                        } else {
                            $message = array("StatusCode" => 2, "Message" => 'Unable to Applied PromoCode', "Class" => "error_popup", "Reload" => "true", "FareBreakUpData" => $fareSummary, 'CouponID' => '');
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
        $HotelModel = new HotelModel();
        $whereClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "blockroom", "selected_index" => $inputData['rindex']);
        $whereHotelInfoClause = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "gethotelinfo", "selected_index" => $inputData['rindex']);
        $blockInfo = $HotelModel->getDataFromApi($whereClause, 'response');
        $hotelInfo = array();
        $searchRequest = array();
        $dial_code = array();
        $hotelInfo = $HotelModel->getDataFromApi($whereHotelInfoClause, 'response');
        $hotelInfo = json_decode($hotelInfo['response'], true);
        $whereHotelSearch = array("tts_search_token" => $inputData['SearchTokenId'], "service" => "search");
        $HotelSearchInfo = $HotelModel->getDataFromApi($whereHotelSearch, 'request');
        $searchRequest = json_decode($HotelSearchInfo['request'], true);
        if ($searchRequest['CountryCode'] == 'IN') {
            $region_type = 'domestic';
        } else {
            $region_type = 'international';
        }
        $input = array(
            "RegionType" => $region_type,
            'StarRating' => $hotelInfo['Result']['StarRating'],
        );
        if ($blockInfo) {
            $FareBreakUp = array();
            $WebPartnerHotelMarkModel = new WebPartnerHotelMarkupModel();
            $WebPartnerHotelDiscountModel = new WebPartnerHotelDiscountModel();
            $WebPartnerMarkUpData = $WebPartnerHotelMarkModel->getHotelmarkup($this->web_partner_id, $input);
            $WebPartnerHotelDiscountData = $WebPartnerHotelDiscountModel->getHoteldiscount($this->web_partner_id, $input);
            $selectedMarkupDataInfo = array();
            $selectedDiscountDataInfo = array();
            $response = json_decode($blockInfo['response'], true);
            if (isset($response['Result']['HotelRoomsDetails'])) {
                $HotelRoomsDetails = $response['Result']['HotelRoomsDetails'];
                $fareSummary = '';
                $BaseFare = 0;
                $Tax = 0;
                $OtherCharges = 0;
                $ServiceCharges = 0;
                $CommEarned = 0;
                $CGSTAmount = 0;
                $PublishedPrice = 0;
                $OfferedPrice = 0;
                $TDS = 0;
                $couponAmount = 0;
                $AgentWebPDiscount = 0;
                $AgentMarkUp = 0;
                $totalAmount = 0;


                foreach ($HotelRoomsDetails as $key => $HotelRoomsDetail) {
                    $HotelPrice = get_hotel_fare($input, $searchRequest['NoOfNights'], $searchRequest['NoOfRooms'], $this->wl_customer_gst_code, $this->web_partner_gst_code, $WebPartnerMarkUpData, $WebPartnerHotelDiscountData, $HotelRoomsDetail['Price'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);


                    $ConvertRoomPrice   = convertCurrencyRate($HotelPrice['CustomerFareBreakUp']);
                    $priceBreakup = $ConvertRoomPrice['ConvertedPrice'];
                    $currencySymbol = $ConvertRoomPrice['CurrencySymbol'];
                    $BaseFare = $BaseFare + $priceBreakup['RoomPrice'];
                    $Tax = $Tax + $priceBreakup['Tax'];
                    $OtherCharges = $OtherCharges + $priceBreakup['OtherCharges'];
                    $ServiceCharges = $ServiceCharges + $priceBreakup['ServiceCharges'];
                    $PublishedPrice = $PublishedPrice + $priceBreakup['PublishedPrice'];
                    $CGSTAmount = $CGSTAmount + $priceBreakup['GST']['CGSTAmount'] + $priceBreakup['GST']['IGSTAmount'] + $priceBreakup['GST']['SGSTAmount'];
                    $OfferedPrice = $OfferedPrice + $priceBreakup['OfferedPrice'];
                    $CommEarned = $CommEarned + $priceBreakup['AgentCommission'] + $priceBreakup['Discount'];
                    $TDS = $TDS + $priceBreakup['TDS'];
                    $totalAmount = ($OfferedPrice + $TDS);
                }



                $fareBreakup = [
                    "FareBreakup" => [
                        "BaseFare" => ["Value" => $BaseFare, "LabelText" => "Base Fare"],
                        "Taxes" => ["Value" => $Tax + $OtherCharges, "LabelText" => "Taxes"],
                        "ServiceAndOtherCharge" => ["Value" => $ServiceCharges, "LabelText" => "Other & Service Charges"],
                        "GST" => ["Value" => $CGSTAmount, "LabelText" => "GST (+)"],
                        "Discount" => ["Value" => 0, "LabelText" => "Discount (-)"]
                    ],
                    /*  "TotalAmount" => ["Value" => $PublishedPrice, "LabelText" => "Pay Amount"], */
                    "OfferedFare" => ["Value" => $totalAmount, "LabelText" => "Pay Amount"],
                ];
            }


            $fareSummary = '<div class="card-header bg-transparent p-3">
                        <h6 class="mb-0"> Fare Summary</h6>
                     </div><div class="card-body">';

            if (isset($fareBreakup['FareBreakup']) && !empty($fareBreakup['FareBreakup'])) {
                foreach ($fareBreakup['FareBreakup'] as $fare) {
                    $labelText = htmlspecialchars($fare['LabelText'] ?? '', ENT_QUOTES, 'UTF-8');
                    $value = htmlspecialchars($fare['Value'] ?? '0', ENT_QUOTES, 'UTF-8');

                    $fareSummary .= '
                        
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span>' . $labelText . '</span>
                                    <span>' . $currencySymbol . ' ' . $value . '</span>
                                </div>';
                }
            }

            $fareSummary .= '</div>'; // Close card body 

            // Add total and offered fare


            $offerLabelText = htmlspecialchars($fareBreakup['OfferedFare']['LabelText'] ?? '', ENT_QUOTES, 'UTF-8');
            $offerValue = htmlspecialchars($fareBreakup['OfferedFare']['Value'] ?? '0', ENT_QUOTES, 'UTF-8');

            $fareSummary .= '
                <div class="card-footer bg-transparent p-3">
                    
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span><strong>' . $offerLabelText . '</strong></span>
                            <span><span><strong>' . $currencySymbol . ' ' . $offerValue . '</strong></span></span> 
                        </div>
                   
                </div>
            </div>';
        }
        if ($deleteCode) {
            $message = array("StatusCode" => 0, "Message" => 'PromoCode Delete Successfully', "Class" => "error_popup", "Reload" => "true", "FareBreakUpData" => $fareSummary, 'CouponID' => '');
        } else {
            $message = array("StatusCode" => 2, "Message" => 'Unable to Delete PromoCode', "Class" => "error_popup", "Reload" => "true", 'CouponID' => $inputData['CouponId']);
        }
        return $this->response->setJSON($message);
    }
}

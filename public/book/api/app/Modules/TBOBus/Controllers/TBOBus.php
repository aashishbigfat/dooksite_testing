<?php

namespace Modules\TBOBus\Controllers;

use App\Controllers\BaseController;
use App\Modules\TBOBus\Models\TBOBusModel;
use Modules\TBOBus\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;


class TBOBus extends BaseController
{

    public function __construct()
    {
        helper('Modules\TBOBus\Helpers\bus');
        $this->GetTimeZone = app_timezone();
        $this->TokenId = TBOBus::Authenticate();
    }

    /**
     * ----------------------------------------------
     * TBO Authenticate Function And Generate Token
     * ---------------------------------------------
     */

    private function Authenticate()
    {

        $TBOBusModel = new TBOBusModel();
        $request = \Config\Services::request();
        $apiconfig = new APIConfig;
        $credential = $apiconfig->tbo_credential;
        if ($credential['Mode'] == 'Live') {
            $ClientId = 'tboprod';
            $UserName = $credential['UserName'];
            $Password = $credential['Password'];
            $LoginType = 2;
            $this->Authenticate_URL = 'https://api.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate';
            $this->BusService_URL = 'https://api.travelboutiqueonline.com/BusAPI_V10/BusService.svc';
            $this->Mode = 'Live';
        } else {
            $ClientId = 'ApiIntegrationNew';
            $UserName = $credential['UserName'];
            $Password = $credential['Password'];
            $LoginType = 2;
            $this->Authenticate_URL = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
            $this->BusService_URL = 'http://api.tektravels.com/BookingEngineService_Bus/Busservice.svc';
            $this->Mode = 'Test';
        }

        $token_id = null;
        $db_token_id=$TBOBusModel->fetch_auth_token(strtotime(Time::today($this->GetTimeZone)),$this->Mode);
        if (empty($db_token_id)) {
            $requestdata = array(
                'EndUserIp' => $request->getIPAddress(),
                'ClientId' => $ClientId,
                'UserName' => $UserName,
                'Password' => $Password,
                'LoginType' => $LoginType
            );
            $url = $this->Authenticate_URL;
            $response = TBO_Request($url, $requestdata);
            if ($response['Status'] == 1) {
                $token_id = $response['TokenId'];
            }
            /*--------------Start Insert API Logs------------------*/
            $insertlog = array(
                'id' => 1,
                'token_id' => $token_id,
                'api_mode' => $this->Mode,
                'request' => json_encode($requestdata),
                'response' => json_encode($response),
                'created' => strtotime(Time::today($this->GetTimeZone))
            );
            $TBOBusModel->insert_update_data('tbo_auth_token', $insertlog);
            /*--------------End Insert API Logs------------------*/
        } else {
            $token_id = $db_token_id['token_id'];
        }
        return $token_id;
    }

    public function Search(array $input, $tts_search_token, array $userauthdata,$Btype)
    {
        
        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'DateOfJourney' => $input['DateOfJourney'],
            'OriginId' => $input['OriginId'],
            'DestinationId' => $input['DestinationId'],
            'PreferredCurrency' => 'INR'
        );
        $url = "$this->BusService_URL/rest/Search/";
        $response = TBO_Request($url, $request);

        $TBOBusModel = new TBOBusModel();
        $custom_index = array();
        $TTS_Result = array();

        if ($response['BusSearchResult']['Error']['ErrorCode'] == 0) {

            $search_city_name = $TBOBusModel->get_city_name([$input['OriginId'], $input['DestinationId']]);
            $search_city_name = array_column($search_city_name, 'city_name', 'city_id');
            $input['Origin'] = $search_city_name[$input['OriginId']];
            $input['Destination'] = $search_city_name[$input['DestinationId']];

            $markUpDiscountExtraparametr['btype']   = strtoupper($Btype);
            $super_admin_markup = $TBOBusModel->super_admin_markup($userauthdata['web_partner_class_id'], $markUpDiscountExtraparametr);
            $super_admin_discount = $TBOBusModel->super_admin_discount($userauthdata['web_partner_class_id'], $markUpDiscountExtraparametr);
            $super_admin_gst_state_code = $TBOBusModel->super_admin_gst_state_code()['gst_state_code'];

            $trace_id = $response['BusSearchResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';

            $BusResults = $response['BusSearchResult']['BusResults'];
            if ($BusResults) {
                $ResultIndexArray = array();
                foreach ($BusResults as $list) {

                    $BusPrice = get_bus_fare($super_admin_markup, $super_admin_discount, $list['BusPrice'], $userauthdata, $super_admin_gst_state_code);

                    $ResultIndex = $list['ResultIndex'];

                    $TTS_Result[] = array(
                        'ResultIndex' => $ResultIndex,
                        'ArrivalTime' => $list['ArrivalTime'],
                        'AvailableSeats' => $list['AvailableSeats'],
                        'DepartureTime' => $list['DepartureTime'],
                        'RouteId' => $list['RouteId'],
                        'BusType' => trim(ucwords(strtolower($list['BusType']))),
                        'ServiceName' => $list['ServiceName'],
                        'TravelName' => trim(ucwords(strtolower($list['TravelName']))),
                        'IdProofRequired' => $list['IdProofRequired'],
                        'IsDropPointMandatory' => $list['IsDropPointMandatory'],
                        'LiveTrackingAvailable' => $list['LiveTrackingAvailable'],
                        'MTicketEnabled' => $list['MTicketEnabled'],
                        'MaxSeatsPerTicket' => $list['MaxSeatsPerTicket'],
                        'OperatorId' => $list['OperatorId'],
                        'PartialCancellationAllowed' => $list['PartialCancellationAllowed'],
                        'BoardingPointsDetails' => $list['BoardingPointsDetails'],
                        'DroppingPointsDetails' => $list['DroppingPointsDetails'],
                        'BusPrice' => $BusPrice,
                        'CancellationPolicies' => $list['CancellationPolicies']
                    );

                    $ResultIndexArray[$ResultIndex] = array('IdProofRequired' => $list['IdProofRequired'], 'IsDropPointMandatory' => $list['IsDropPointMandatory']);
                }

                $custom_index['CommonData'] = array('TraceId' => $trace_id, 'Supplier' => 'TBO', 'ResultIndex' => $ResultIndexArray);
            }
        } else {
            $trace_id = '';
            $ErrorCode = $response['BusSearchResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['BusSearchResult']['Error']['ErrorMessage'];
        }
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'Search', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return array('Response' => $tts_response, 'CustomIndex' => $custom_index, 'input' => $input);
    }

    public function GetBusSeatLayOut(array $input, array $common_data, array $userauthdata,$Btype)
    {
        $tts_search_token = $input['SearchTokenId'];
        $TraceId = $common_data['TraceId'];
        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'TraceId' => $TraceId,
            'ResultIndex' => $input['ResultIndex'],
        );
        $url = "$this->BusService_URL/rest/GetBusSeatLayOut/";
        $response = TBO_Request($url, $request);

        $TBOBusModel = new TBOBusModel();
        $custom_index = array();
        $TTS_Result = array();
        $supplier_seat_layout = array();

        if ($response['GetBusSeatLayOutResult']['Error']['ErrorCode'] == 0) {
            $trace_id = $response['GetBusSeatLayOutResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';
            $SeatLayoutDetails = $response['GetBusSeatLayOutResult']['SeatLayoutDetails'];

            $markUpDiscountExtraparametr['btype']   = strtoupper($Btype);
            $super_admin_markup = $TBOBusModel->super_admin_markup($userauthdata['web_partner_class_id'], $markUpDiscountExtraparametr);
            $super_admin_discount = $TBOBusModel->super_admin_discount($userauthdata['web_partner_class_id'], $markUpDiscountExtraparametr);
            $super_admin_gst_state_code = $TBOBusModel->super_admin_gst_state_code()['gst_state_code'];


            $tts_seat_layout = array();
            if ($SeatLayoutDetails['SeatLayout']) {
                $tts_seat_layout['NoOfColumns'] = $SeatLayoutDetails['SeatLayout']['NoOfColumns'];
                $tts_seat_layout['NoOfRows'] = $SeatLayoutDetails['SeatLayout']['NoOfRows'];
                foreach ($SeatLayoutDetails['SeatLayout']['SeatDetails'] as $key => $seatlayout) {
                    foreach ($seatlayout as $seat) {
                        $supplier_seat_layout[$seat['SeatName']] = $seat;

                        $price = get_bus_fare($super_admin_markup, $super_admin_discount, $seat['Price'], $userauthdata, $super_admin_gst_state_code);
                        $seat['Price'] = $price;
                        $seat['SeatFare'] = $price['PublishedPrice'];
                        $tts_seat_layout['SeatDetails'][$key][] = $seat;
                    }
                }
            }
            $HTMLLayout = str_replace(array('TBSelectedSeats.value,', 'TBSelectedSeatsPrice.value,'), array('this,', ''), html_entity_decode($SeatLayoutDetails['HTMLLayout']));
            $TTS_Result = array(
                'AvailableSeats' => $SeatLayoutDetails['AvailableSeats'],
                'HTMLLayout'     => $HTMLLayout,
                'SeatLayout' => $tts_seat_layout
            );
        } else {
            $trace_id = '';
            $ErrorCode = $response['GetBusSeatLayOutResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['GetBusSeatLayOutResult']['Error']['ErrorMessage'];
        }

        $SelectedIndex = array();
        if ($common_data['ResultIndex'][$input['ResultIndex']]) {
            $SelectedIndex = $common_data['ResultIndex'][$input['ResultIndex']];
            $SelectedIndex['ResultIndex'] = $input['ResultIndex'];
        }
        $custom_index['CommonData'] = array('TraceId' => $TraceId, 'Supplier' => 'TBO', 'SupplierSeatLayout' => $supplier_seat_layout, 'SelectedIndex' => $SelectedIndex);
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'GetBusSeatLayOut', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
    }

    public function GetBoardingPointDetails(array $input, array $common_data, array $userauthdata)
    {
        $tts_search_token = $input['SearchTokenId'];
        $TraceId = $common_data['TraceId'];
        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'TraceId' => $TraceId,
            'ResultIndex' => $input['ResultIndex'],
        );

        $url = "$this->BusService_URL/rest/GetBoardingPointDetails/";
        $response = TBO_Request($url, $request);
        if ($response['GetBusRouteDetailResult']['Error']['ErrorCode'] == 0) {
            $trace_id = $response['GetBusRouteDetailResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';
            $TTS_Result = array(
                'BoardingPointsDetails' => $response['GetBusRouteDetailResult']['BoardingPointsDetails'],
                'DroppingPointsDetails' => $response['GetBusRouteDetailResult']['DroppingPointsDetails']
            );
        } else {
            $trace_id = '';
            $ErrorCode = $response['GetBusRouteDetailResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['GetBusRouteDetailResult']['Error']['ErrorMessage'];
            $TTS_Result = array();
        }
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel = new TBOBusModel();
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'GetBoardingPointDetails', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return $tts_response;
    }

    public function Block(array $input, array $common_data, array $userauthdata,$Btype)
    {
        $SelectedIndex = $common_data['SelectedIndex'];
        if ($SelectedIndex['IsDropPointMandatory']) {
            $message = "DroppingPointId field is Required";
            api_custom_message(400, $message, false);
        }

        $Passenger_array = array();
        if ($input['Passenger']) {
            foreach ($input['Passenger'] as $key => $Passenger) {
                if (isset($Passenger['LeadPassenger'])) {
                    if ($key == 0) {
                        if ($Passenger['LeadPassenger'] != true) {
                            $message = "First Passenger is a lead passenger. lead passenger value is true.";
                            api_custom_message(400, $message, false);
                        }
                    } else {
                        if ($Passenger['LeadPassenger'] != false) {
                            $message = "Second passenger is not a lead passenger.";
                            api_custom_message(400, $message, false);
                        }
                    }
                } else {
                    $message = "LeadPassenger field is Required";
                    api_custom_message(400, $message, false);
                }

                if (array_key_exists($Passenger['SeatName'], $common_data['SupplierSeatLayout'])) {
                    if ($SelectedIndex['IdProofRequired'] && empty($Passenger['IdType'])  && empty($Passenger['IdNumber'])) {
                        $message = "IdType and IdNumber field is Required";
                        api_custom_message(400, $message, false);
                    }
                    $Passenger['Seat'] = $common_data['SupplierSeatLayout'][$Passenger['SeatName']];
                    unset($Passenger['SeatName']);
                    $Passenger_array[] = $Passenger;
                } else {
                    $message = "Invalid SeatName in Request";
                    api_custom_message(400, $message, false);
                }
            }
        }

        $tts_search_token = $input['SearchTokenId'];
        $TraceId = $common_data['TraceId'];
        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'TraceId' => $TraceId,
            'ResultIndex' => $input['ResultIndex'],
            'BoardingPointId' => $input['BoardingPointId'],
            'DroppingPointId' => $input['DroppingPointId'],
            'Passenger' => $Passenger_array,
        );

        $url = "$this->BusService_URL/rest/Block/";
        $response = TBO_Request($url, $request);

        $TBOBusModel = new TBOBusModel();
        $custom_index = array();
        $TTS_Result = array();
        $supplier_seat_layout = array();
        $TTS_Invoice_Amount = 0;
        $SuperAdminFareBreakup = array();
        $WebPartnerFareBreakup = array();
        $markUpDiscountExtraparametr['btype']   = strtoupper($Btype);
        if ($response['BlockResult']['Error']['ErrorCode'] == 0) {

            $super_admin_markup = $TBOBusModel->super_admin_markup($userauthdata['web_partner_class_id'],$markUpDiscountExtraparametr);
            $super_admin_discount = $TBOBusModel->super_admin_discount($userauthdata['web_partner_class_id'],$markUpDiscountExtraparametr);
            $super_admin_gst_state_code = $TBOBusModel->super_admin_gst_state_code()['gst_state_code'];

            $trace_id = $response['BlockResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';
            $BlockResult = $response['BlockResult'];
           
            $passenger_list = array();
            if ($BlockResult['Passenger']) {
                foreach ($BlockResult['Passenger'] as $Passenger) {
                    $SeatName = $Passenger['Seat']['SeatName'];
                    $supplier_seat_layout[$SeatName] = $Passenger['Seat'];

                    $Busprice = get_bus_fare($super_admin_markup, $super_admin_discount, $Passenger['Seat']['Price'], $userauthdata, $super_admin_gst_state_code,"BlockSeat");
                    $price =  $Busprice['Fare'];
                    $Passenger['Seat']['Price'] = $price;
                    $Passenger['Seat']['SeatFare'] = $price['PublishedPrice'];
                    $passenger_list[] = $Passenger;
                    $Busprice['SuperAdminFareBreakup']['SeatName'] =$SeatName;
                    $Busprice['WebPartnerFareBreakup']['SeatName'] =$SeatName;
                    array_push($SuperAdminFareBreakup,$Busprice['SuperAdminFareBreakup']);
                    array_push($WebPartnerFareBreakup,$Busprice['WebPartnerFareBreakup']);
                    $TTS_Invoice_Amount += round_value($price['OfferedPrice'] + $price['TDS']);
                }
            }

            $TTS_Result = array(
                'IsPriceChanged' => $BlockResult['IsPriceChanged'],
                'ArrivalTime' => $BlockResult['ArrivalTime'],
                'BusType' => $BlockResult['BusType'],
                'DepartureTime' => $BlockResult['DepartureTime'],
                'TravelName' => $BlockResult['TravelName'],
                'BoardingPointdetails' => $BlockResult['BoardingPointdetails'],
                'CancelPolicy' => $BlockResult['CancelPolicy'],
                'Passenger' => $passenger_list
            );
        } else {
            $trace_id = '';
            $ErrorCode = $response['BlockResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['BlockResult']['Error']['ErrorMessage'];
        }

        $custom_index['CommonData'] = array('TraceId' => $TraceId, 'Supplier' => 'TBO', 'SupplierSeatLayout' => $supplier_seat_layout, 'TTS_Invoice_Amount' => $TTS_Invoice_Amount, 'SelectedIndex' => $SelectedIndex,'SuperAdminFareBreakup'=>$SuperAdminFareBreakup,'WebPartnerFareBreakup'=>$WebPartnerFareBreakup);
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'Block', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
    }

    public function Book(array $input, array $common_data, array $userauthdata, $saveInfo)
    {


        $tts_search_token = $input['SearchTokenId'];
        $TraceId = $common_data['TraceId'];
        $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
        $booking_lastid = $saveInfo['booking_ref_no'];
        $acc_ref_number = $saveInfo['acc_ref_number'];
        $payment_status = $saveInfo['payment_status'];
        $TBOBusModel = new TBOBusModel();
        $custom_index = array();
        $TTS_Result = array();
        $ticket_no = '';
        $travel_operator_pnr = '';
        $booking_status = 'Failed';
        $supplier_booking_id = '';
        $Passenger_array  =  array();
        if ($payment_status == "Successful") {
            if ($input['Passenger']) {
                foreach ($input['Passenger'] as $key => $Passenger) {
                    $Passenger['Seat'] = $common_data['SupplierSeatLayout'][$Passenger['SeatName']];
                    unset($Passenger['SeatName']);
                    $Passenger_array[] = $Passenger;
                }
            }
            $request = array(
                'EndUserIp' => $input['UserIp'],
                'TokenId' => $this->TokenId,
                'TraceId' => $TraceId,
                'ResultIndex' => $input['ResultIndex'],
                'BoardingPointId' => $input['BoardingPointId'],
                'DroppingPointId' => $input['DroppingPointId'],
                'Passenger' => $Passenger_array,
            );
            $url = "$this->BusService_URL/rest/Book/";
            $response = TBO_Request($url, $request);


            if ($response['BookResult']['Error']['ErrorCode'] == 0) {
                $trace_id = $response['BookResult']['TraceId'];
                $ErrorCode = 0;
                $ErrorMessage = '';
                $BookResult = $response['BookResult'];

                $ticket_no = $BookResult['TicketNo'];
                $travel_operator_pnr = $BookResult['TravelOperatorPNR'];
                $supplier_booking_id = $BookResult['BusId'];
                if ($BookResult['TicketNo'] || $BookResult['TravelOperatorPNR']) {
                    $booking_status = 'Confirmed';

                    /*------------------ Call GetBookingDetail -------------------*/
                    $GetBookingDetailArray = TBOBus::GetBookingDetail($input, $supplier_booking_id, $userauthdata);
                    if ($GetBookingDetailArray['Error']['ErrorCode'] == 0) {
                        if ($GetBookingDetailArray['Result']['Passenger']) {
                            foreach ($GetBookingDetailArray['Result']['Passenger'] as $pax) {
                                $update_pax_data = array('seat_id' => $pax['Seat']['SeatId']);
                                $TBOBusModel->updateUserData('bus_booking_travelers', array('bus_booking_id' => $booking_lastid, 'seat_name' => $pax['Seat']['SeatName']), $update_pax_data);
                            }
                        }
                    }
                    /*------------------ Call GetBookingDetail -------------------*/
                }

                $TTS_Result = array(
                    'BookingStatus' => $booking_status,
                    'InvoiceAmount' => $TTS_Invoice_Amount,
                    'InvoiceNumber' => $acc_ref_number,
                    'BookingID' => $booking_lastid,
                    'TicketNo' => $ticket_no,
                    'TravelOperatorPNR' => $travel_operator_pnr
                );
            } else {
                $trace_id = '';
                $ErrorCode = $response['BookResult']['Error']['ErrorCode'];
                $ErrorMessage = $response['BookResult']['Error']['ErrorMessage'];
            }

            /*------------------ Update Data ----------------------------*/
        } else {
            $ErrorCode = 400;
            $ErrorMessage = "Technical Problem Occured";
        }
        $book_update_data = array(
            'ticket_no' => $ticket_no,
            'travel_operator_pnr' => $travel_operator_pnr,
            'booking_status' => $booking_status,
            'supplier_booking_id' => $supplier_booking_id
        );
        $TBOBusModel->updateUserData('bus_booking_list', ['id' => $booking_lastid], $book_update_data);


        $custom_index['CommonData'] = array('TraceId' => $TraceId, 'Supplier' => 'TBO');
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'Book', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
    }

    public function GetBookingDetail(array $input, $busid, array $userauthdata)
    {
        $tts_search_token = $input['SearchTokenId'];

        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'BusId' => $busid,
            'IsBaseCurrencyRequired' => false,
        );

        $url = "$this->BusService_URL/rest/GetBookingDetail/";
        $response = TBO_Request($url, $request);
        if ($response['GetBookingDetailResult']['Error']['ErrorCode'] == 0) {
            $trace_id = $response['GetBookingDetailResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';
            $TTS_Result = $response['GetBookingDetailResult']['Itinerary'];
        } else {
            $trace_id = '';
            $ErrorCode = $response['GetBookingDetailResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['GetBookingDetailResult']['Error']['ErrorMessage'];
            $TTS_Result = array();
        }
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel = new TBOBusModel();
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'GetBookingDetail', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return $tts_response;
    }

    public function SendChangeRequest(array $input, $busid, array $userauthdata)
    {
        $tts_search_token = $input['SearchTokenId'];
        $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'BusId' => $busid,
            'RequestType' => 11,
            'SeatId' => $input['SeatId'],
            'Remarks' => $input['Remarks'],
        );

        $url = "$this->BusService_URL/rest/SendChangeRequest/";
        $response = TBO_Request($url, $request);
        if ($response['SendChangeRequestResult']['Error']['ErrorCode'] == 0) {
            $trace_id = $response['SendChangeRequestResult']['TraceId'];
            $ErrorCode = 0;
            $ErrorMessage = '';
            $TTS_Result = array(
                ''

            );
        } else {
            $trace_id = '';
            $ErrorCode = $response['SendChangeRequestResult']['Error']['ErrorCode'];
            $ErrorMessage = $response['SendChangeRequestResult']['Error']['ErrorMessage'];
            $TTS_Result = array();
        }
        /*--------------Start Insert API Logs------------------*/
        $TBOBusModel = new TBOBusModel();
        $TBOBusModel->insert_bus_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'SendChangeRequest', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => $TTS_Result
        );
        return $response;
    }

    public function GetBusCityList()
    {
        $request = \Config\Services::request();
        $requestdata = array(
                                'IpAddress' => $request->getIPAddress(),
                                'ClientId' => 'tboprod',
                                'TokenId' => $this->TokenId,
                            );
       // $url ='http://api.tektravels.com/SharedServices/StaticData.svc/rest/GetBusCityList';
        $url ='https://api.travelboutiqueonline.com/SharedAPI/StaticData.svc/rest/GetBusCityList';
        $response = TBO_Request($url, $requestdata);
        if(isset($response['BusCities']))
        {
                 $TBOBusModel = new TBOBusModel();
                foreach($response['BusCities'] as $BusCities)
                {
                    $data_insert=array(
                                        'city_id'=>$BusCities['CityId'],
                                        'city_name'=>$BusCities['CityName']
                                      );
                    $TBOBusModel->insert_city_list($data_insert);
                }
        }
    }
}

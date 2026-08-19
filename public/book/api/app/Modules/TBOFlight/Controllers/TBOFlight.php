<?php

namespace Modules\TBOFlight\Controllers;

use App\Controllers\BaseController;
use App\Modules\TBOFlight\Models\TBOFlightModel;
use Modules\TBOFlight\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;


class TBOFlight extends BaseController
{

   public function __construct()
   {
      helper('Modules\TBOFlight\Helpers\flight');
      $this->GetTimeZone = app_timezone();
      $this->TokenId = TBOFlight::Authenticate();
   }

   /**
    * ----------------------------------------------
    * TBO Authenticate Function And Generate Token
    * ---------------------------------------------
    */

   private function Authenticate()
   {

      $TBOFlightModel = new TBOFlightModel();
      $request = \Config\Services::request();
      $apiconfig = new APIConfig;
      $credential = $apiconfig->tbo_credential;
      if ($credential['Mode'] == 'Live') {
         $ClientId = 'tboprod';
         $UserName = $credential['UserName'];
         $Password = $credential['Password'];
         $LoginType = 2;
         $this->Authenticate_URL = 'https://api.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate';
         $this->AirService_URL = 'https://tboapi.travelboutiqueonline.com/AirAPI_V10/AirService.svc';
         $this->AirBooking_URL = 'https://booking.travelboutiqueonline.com/AirAPI_V10/AirService.svc';
         $this->Mode = 'Live';
      } else {
         $ClientId = 'ApiIntegrationNew';
         $UserName = $credential['UserName'];
         $Password = $credential['Password'];
         $LoginType = 2;
         $this->Authenticate_URL = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
         $this->AirService_URL = 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc';
         $this->AirBooking_URL = 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc';
         $this->Mode = 'Test';
      }
      $token_id = null;
      $db_token_id = $TBOFlightModel->fetch_auth_token(strtotime(Time::today($this->GetTimeZone)), $this->Mode);
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
            'service' => "Flight",
            'request' => json_encode($requestdata),
            'response' => json_encode($response),
            'created' => strtotime(Time::today())
         );
         $TBOFlightModel->insert_update_data('tbo_auth_token', $insertlog);
         /*--------------End Insert API Logs------------------*/
      } else {
         $token_id = $db_token_id['token_id'];
      }
      return $token_id;
   }

   public function Search(array $input, $tts_search_token, array $userauthdata)
   {
      $class = $input['CabinClass'];
      $segments = array();
      if ($input['AirSegments']) {
         foreach ($input['AirSegments'] as $airsegment) {
            $segments[] = array(
               'Origin' => $airsegment['Origin'],
               'Destination' => $airsegment['Destination'],
               'FlightCabinClass' => $class,
               'PreferredDepartureTime' => $airsegment['PreferredTime'],
               'PreferredArrivalTime' => $airsegment['PreferredTime'],
            );
         }
      }

      $directflight = false;
      if (isset($input['DirectFlight'])) {
         $directflight = $input['DirectFlight'];
      }


      $preferredairlines = null;
      $sources = null;
      if (isset($input['PreferredCarriers']) && !empty($input['PreferredCarriers'])) {
         $airlineSource = array();
         $sourceAirlines = array();
         $preferredairlinesSource = array();
         foreach ($input['PreferredCarriers'] as $PreferredAirline) {
            $sourceAirlines = get_flight_source($PreferredAirline);
            if ($sourceAirlines != 'ALL') {
               if ($PreferredAirline == "UK" || $PreferredAirline == "AI") {
                  array_push($preferredairlinesSource, $PreferredAirline);
               }
               array_push($airlineSource, $sourceAirlines);
            }
         }
         if (!empty($preferredairlinesSource)) {
            $preferredairlines = $preferredairlinesSource;
         }
         $sources = $airlineSource;
      }



      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'AdultCount' => intval($input['Adult']),
         'ChildCount' => intval($input['Child']),
         'InfantCount' => intval($input['Infant']),
         'DirectFlight' => (bool) $directflight,
         'OneStopFlight' => false,
         'JourneyType' => intval($input['JourneyType']),
         'PreferredAirlines' => $preferredairlines,
         'Segments' => $segments,
         'Sources' => $sources
      );

      $url = "$this->AirService_URL/rest/Search/";
      return array('Supplier' => 'TBO', 'URL' => $url, 'Request' => json_encode($request));

   }

   public function ConvertSearchResponse($input, $response, $convert_response, $custom_index, $common_data)
   {
      // increase allowed memory size
      ini_set('memory_limit', '256M');

      $airline_list = $common_data['airline_list'];
      $airport_list = $common_data['airport_list'];

      $selectedMarkupDataInfo = array();
      $selectedDiscountDataInfo = array();
      if ($response) {
         $response_array = json_decode($response, true);
         if ($response_array['Response']['Error']['ErrorCode'] == 0) {
            $super_admin_markup = $common_data['super_admin_markup'];
            $super_admin_discount = $common_data['super_admin_discount'];
            $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
            $userauthdata = $common_data['userauthdata'];

            $TraceId = $response_array['Response']['TraceId'];
            // foreach($response_array['Response']['Results'] as $jkey=>$result)
            // {        
            //       foreach($result as $list)
            //       { 
            //             $FareId='T'.$list['ResultIndex'];
            $journeyType = "OB";
            foreach ($response_array['Response']['Results'] as $jkey => $result) {
               foreach ($result as $resultkey => $list) {
                  if ($jkey == 1) {
                     $journeyType = "IB";
                  }
                  $FareId = 'T' . $journeyType . $resultkey;
                  $custom_index[$FareId] = array('ResultIndex' => $list['ResultIndex'], 'TraceId' => $TraceId, 'Supplier' => 'TBO');

                  $previous_fare = array();
                  if (count($list['Segments']) == 1) {
                     $first_segment = current($list['Segments'][0]);
                     $last_segment = end($list['Segments'][0]);
                  } else {
                     $first_segment = current($list['Segments']);
                     $first_segment = current($first_segment);

                     $last_segment = end($list['Segments']);
                     $last_segment = end($last_segment);
                  }
                  $CabinClass = '';
                  $CabinClass = get_cabin_class_name($first_segment['CabinClass']);
                  if (isset($list['FareClassification']['Type'])) {
                     $FareTypeIdentifier = get_flight_fare_type($list['FareClassification']['Type']);
                     $FareType = $FareTypeIdentifier['fareType'];
                     $FareTypeColor = $FareTypeIdentifier['color'];
                  } else {
                     $FareTypeIdentifier = get_flight_fare_type('Publish');
                     $FareType = $FareTypeIdentifier['fareType'];
                     $FareTypeColor = $FareTypeIdentifier['color'];
                  }
                  $extraParam = array();
                  $extraParam['FareType'] = $FareType;
                  $extraParam['FareTypeColor'] = $FareTypeColor;
                  $extraParam['btype'] = $common_data['btype'];
                  $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                  $FlightPrice = get_flight_fare($input, $super_admin_markup, $super_admin_discount, $list['Fare'], $list['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $list['AirlineCode'], $CabinClass, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam);
                  $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                  $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];

                  $dep_time = substr(str_replace("T", "-", $first_segment['Origin']['DepTime']), 0, -3);
                  $arr_time = substr(str_replace("T", "-", $last_segment['Destination']['ArrTime']), 0, -3);
                  $custom_key = trim($first_segment['Airline']['AirlineCode']) . '-' . trim($first_segment['Airline']['FlightNumber']) . '-' . trim($dep_time) . '-' . trim($arr_time);

                  $main_segment = array();
                  $ttsbaggageinfo = array();
                  foreach ($list['Segments'] as $mkey => $Segment) {
                     $ttssegment = array();
                     foreach ($Segment as $segkey => $item) {
                        /*---Start Temporary Store Airline and Airport Data  ------*/
                        $airline_list[$item['Airline']['AirlineCode']] = ucwords(strtolower($item['Airline']['AirlineName']));
                        $airport_list[$item['Origin']['Airport']['AirportCode']] = array(
                           'code' => $item['Origin']['Airport']['AirportCode'],
                           'name' => $item['Origin']['Airport']['AirportName'],
                           'city_code' => $item['Origin']['Airport']['CityCode'],
                           'city_name' => $item['Origin']['Airport']['CityName'],
                           'country_name' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                           'country_code' => $item['Origin']['Airport']['CountryCode']
                        );

                        $airport_list[$item['Destination']['Airport']['AirportCode']] = array(
                           'code' => $item['Destination']['Airport']['AirportCode'],
                           'name' => $item['Destination']['Airport']['AirportName'],
                           'city_code' => $item['Destination']['Airport']['CityCode'],
                           'city_name' => $item['Destination']['Airport']['CityName'],
                           'country_name' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                           'country_code' => $item['Destination']['Airport']['CountryCode']
                        );
                        /*---End Temporary Store Airline and Airport Data  ------*/

                        $Sector = $item['Origin']['Airport']['AirportCode'] . '-' . $item['Destination']['Airport']['AirportCode'];
                        $ttsbaggageinfo[$mkey][$segkey] = array(
                           'NoOfSeatAvailable' => check_isset($item, 'NoOfSeatAvailable'),
                           'Sector' => $Sector,
                           'CheckIn' => $item['Baggage'],
                           'Cabin' => $item['CabinBaggage']
                        );

                        $TripIndicator = 1;
                        if (isset($item['TripIndicator'])) {
                           $TripIndicator = $item['TripIndicator'];
                        }
                        $TechStopPoint = array();
                        if (isset($item['StopOver']) && $item['StopOver']) {
                           $TechStopPoint = array(
                              'Code' => $item['StopPoint'],
                              'Name' => isset($airport_list[$item['StopPoint']]) ? $airport_list[$item['StopPoint']] : ""
                           );
                        }
                        $ttssegment[$segkey] = array(
                           'TripIndicator' => $TripIndicator,
                           'SegmentIndicator' => $item['SegmentIndicator'],
                           'Duration' => $item['Duration'],
                           'TechStopPoint' => $TechStopPoint,
                           'Craft' => $item['Craft'],
                           'Airline' => array(
                              'AirlineCode' => $item['Airline']['AirlineCode'],
                              'AirlineName' => ucwords(strtolower($item['Airline']['AirlineName'])),
                              'FlightNumber' => (int) $item['Airline']['FlightNumber'],
                              'FareClass' => $item['Airline']['FareClass'],
                              'OperatingCarrier' => $item['Airline']['OperatingCarrier']
                           ),
                           'Origin' => array(
                                 'AirportCode' => $item['Origin']['Airport']['AirportCode'],
                                 'AirportName' => $item['Origin']['Airport']['AirportName'],
                                 'CityCode' => $item['Origin']['Airport']['CityCode'],
                                 'CityName' => $item['Origin']['Airport']['CityName'],
                                 'CountryCode' => $item['Origin']['Airport']['CountryCode'],
                                 'CountryName' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                                 'Terminal' => $item['Origin']['Airport']['Terminal'],
                                 'DepartTime' => $item['Origin']['DepTime']
                              ),
                           'Destination' => array(
                              'AirportCode' => $item['Destination']['Airport']['AirportCode'],
                              'AirportName' => $item['Destination']['Airport']['AirportName'],
                              'CityCode' => $item['Destination']['Airport']['CityCode'],
                              'CityName' => $item['Destination']['Airport']['CityName'],
                              'CountryCode' => $item['Destination']['Airport']['CountryCode'],
                              'CountryName' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                              'Terminal' => $item['Destination']['Airport']['Terminal'],
                              'ArrivalTime' => $item['Destination']['ArrTime']
                           )
                        );


                     }
                     $main_segment[$mkey] = $ttssegment;
                  }

                  $FareList = array(
                     'FareId' => $FareId,
                     'IsRefundable' => $list['IsRefundable'],
                     'AirlineRemark' => $list['AirlineRemark'],
                     'FareType' => $FareType,
                     'FareTypeColor' => $FareTypeColor,
                     'SeatBaggage' => $ttsbaggageinfo,
                     'FareClass' => $first_segment['Airline']['FareClass'],
                     'CabinClass' => $CabinClass,
                     'Fare' => $FlightPrice['Fare'],
                     'FareBreakdown' => $FlightPrice['FareBreakdown'],
                     'PublishedPrice' => $FlightPrice['Fare']['PublishedPrice'],
                     'OfferedPrice' => $FlightPrice['Fare']['OfferedPrice'],
                     'Source' => "TBO",
                  );
                  if (!isset($convert_response[$jkey][$custom_key]['Segments'])) {
                     $convert_response[$jkey][$custom_key]['Segments'] = $main_segment;
                  }
                  if (isset($convert_response[$jkey][$custom_key]['FareList'])) {
                     $previous_fare = $convert_response[$jkey][$custom_key]['FareList'];
                  }
                  array_push($previous_fare, $FareList);
                  $convert_response[$jkey][$custom_key]['FareList'] = $previous_fare;
               }
            }
         }
      }
      return array('convert_response' => $convert_response, 'airline_list' => $airline_list, 'airport_list' => $airport_list, 'custom_index' => $custom_index);
   }

   public function GetCalendarFare(array $input, $tts_search_token, array $userauthdata)
   {
      $class = $input['CabinClass'];
      $segments = array();
      if ($input['AirSegments']) {
         foreach ($input['AirSegments'] as $airsegment) {
            $segments[] = array(
               'Origin' => $airsegment['Origin'],
               'Destination' => $airsegment['Destination'],
               'FlightCabinClass' => $class,
               'PreferredDepartureTime' => $airsegment['PreferredTime'],
               'PreferredArrivalTime' => $airsegment['PreferredTime'],
            );
         }
      }
      $preferredairlines = null;
      if (isset($input['PreferredCarriers'])) {
         $preferredairlines = $input['PreferredCarriers'];
      }
      $sources = null;

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'JourneyType' => intval($input['JourneyType']),
         'PreferredAirlines' => $preferredairlines,
         'Segments' => $segments,
         'Sources' => $sources
      );

      $url = "$this->AirService_URL/rest/GetCalendarFare/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $results = $response['Response']['SearchResults'];
         if ($results) {
            foreach ($results as $key => $result) {
               $TTS_Result[$key] = array(
                  'AirlineCode' => $result['AirlineCode'],
                  'AirlineName' => $result['AirlineName'],
                  'DepartureDate' => $result['DepartureDate'],
                  'IsLowestFareOfMonth' => $result['IsLowestFareOfMonth'],
                  'Fare' => $result['Fare']
               );
            }
         }

      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'CalendarFare', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result' => $TTS_Result
      );

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }

   public function FareRule(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'TraceId' => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex']
      );
      $url = "$this->AirService_URL/rest/FareRule/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';

         if ($response['Response']['FareRules']) {
            foreach ($response['Response']['FareRules'] as $key => $rules) {
               $TTS_Result[$key] = array(
                  'Airline' => $rules['Airline'],
                  'Origin' => $rules['Origin'],
                  'Destination' => $rules['Destination'],
                  'FareBasisCode' => $rules['FareBasisCode'],
                  'FareRestriction' => $rules['FareRestriction'],
                  'FareRuleDetail' => $rules['FareRuleDetail'],
               );
            }
         }

      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareRule', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result' => $TTS_Result
      );
      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }

   public function FareQuote(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $super_admin_markup = $common_data['super_admin_markup'];
      $super_admin_discount = $common_data['super_admin_discount'];
      $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
      $InputRequest = $common_data['InputRequest'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'TraceId' => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
      );


      $url = "$this->AirService_URL/rest/FareQuote/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      $IsPriceChanged = false;
      $FlightDetailChangeInfo = null;
      $selectedMarkupDataInfo = array();
      $selectedDiscountDataInfo = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $TraceId = $response['Response']['TraceId'];
         $IsPriceChanged = $response['Response']['IsPriceChanged'];
         $FlightDetailChangeInfo = $response['Response']['FlightDetailChangeInfo'];

         $Results = $response['Response']['Results'];
         $main_segment = array();
         if (count($Results['Segments']) == 1) {
            $first_segment = current($Results['Segments'][0]);
            $last_segment = end($Results['Segments'][0]);
         } else {
            $first_segment = current($Results['Segments']);
            $first_segment = current($first_segment);

            $last_segment = end($Results['Segments']);
            $last_segment = end($last_segment);
         }
         foreach ($Results['Segments'] as $mkey => $Segment) {
            $ttssegment = array();
            foreach ($Segment as $segkey => $item) {

               $TripIndicator = 1;
               if (isset($item['TripIndicator'])) {
                  $TripIndicator = $item['TripIndicator'];
               }

               $CabinClass = get_cabin_class_name($item['CabinClass']);
               $TechStopPoint = array();
               if (isset($item['StopOver']) && $item['StopOver']) {
                  $TechStopPoint = array(
                     'Code' => $item['StopPoint'],
                     'Name' => isset($airport_list[$item['StopPoint']]) ? $airport_list[$item['StopPoint']] : ""
                  );
               }
               $ttssegment[$segkey] = array(
                  'TripIndicator' => $TripIndicator,
                  'SegmentIndicator' => $item['SegmentIndicator'],
                  'CheckInBaggage' => $item['Baggage'],
                  'CabinBaggage' => $item['CabinBaggage'],
                  'CabinClass' => $CabinClass,
                  'Duration' => $item['Duration'],
                  'TechStopPoint' => $TechStopPoint,
                  'Craft' => $item['Craft'],
                  'Airline' => array(
                     'AirlineCode' => $item['Airline']['AirlineCode'],
                     'AirlineName' => ucwords(strtolower($item['Airline']['AirlineName'])),
                     'FlightNumber' => (int) $item['Airline']['FlightNumber'],
                     'FareClass' => $item['Airline']['FareClass'],
                     'OperatingCarrier' => $item['Airline']['OperatingCarrier']
                  ),
                  'Origin' => array(
                     'AirportCode' => $item['Origin']['Airport']['AirportCode'],
                     'AirportName' => $item['Origin']['Airport']['AirportName'],
                     'CityCode' => $item['Origin']['Airport']['CityCode'],
                     'CityName' => $item['Origin']['Airport']['CityName'],
                     'CountryCode' => $item['Origin']['Airport']['CountryCode'],
                     'CountryName' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                     'Terminal' => $item['Origin']['Airport']['Terminal'],
                     'DepartTime' => $item['Origin']['DepTime']
                  ),
                  'Destination' => array(
                        'AirportCode' => $item['Destination']['Airport']['AirportCode'],
                        'AirportName' => $item['Destination']['Airport']['AirportName'],
                        'CityCode' => $item['Destination']['Airport']['CityCode'],
                        'CityName' => $item['Destination']['Airport']['CityName'],
                        'CountryCode' => $item['Destination']['Airport']['CountryCode'],
                        'CountryName' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                        'Terminal' => $item['Destination']['Airport']['Terminal'],
                        'ArrivalTime' => $item['Destination']['ArrTime']
                     )
               );
            }
            $main_segment[$mkey] = $ttssegment;
         }

         if (isset($Results['FareClassification']['Type'])) {
            $FareTypeIdentifier = get_flight_fare_type($Results['FareClassification']['Type']);
            $FareType = $FareTypeIdentifier['fareType'];
            $FareTypeColor = $FareTypeIdentifier['color'];
         } else {
            $FareTypeIdentifier = get_flight_fare_type('Publish');
            $FareType = $FareTypeIdentifier['fareType'];
            $FareTypeColor = $FareTypeIdentifier['color'];
         }
         $extraParam = array();
         $extraParam['FareType'] = $FareType;
         $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
         $extraParam['btype'] = $common_data['btype'];
         $FlightPrice = get_flight_fare($InputRequest, $super_admin_markup, $super_admin_discount, $Results['Fare'], $Results['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $Results['AirlineCode'], $CabinClass, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');
         if ($Results['IsPanRequiredAtBook'] || $Results['IsPanRequiredAtTicket']) {
            $IsPanRequired = true;
         } else {
            $IsPanRequired = false;
         }
         if ($Results['IsPassportRequiredAtBook'] || $Results['IsPassportRequiredAtTicket']) {
            $IsPassportRequired = true;
         } else {
            $IsPassportRequired = false;
         }

         $TTS_Result = array(
            'ResultIndex' => $input['ResultIndex'],
            'IsLCC' => $Results['IsLCC'],
            'IsRefundable' => $Results['IsRefundable'],
            'IsPanRequiredAtBook' => $IsPanRequired,
            'IsPassportRequiredAtBook' => $IsPassportRequired,
            'GSTAllowed' => $Results['GSTAllowed'],
            'IsGSTMandatory' => $Results['IsGSTMandatory'],
            'AirlineRemark' => $Results['AirlineRemark'],
            'FareType' => $FareType,
            'FareTypeColor' => $FareTypeColor,
            'Fare' => $FlightPrice['Fare'],
            'FareBreakdown' => $FlightPrice['FareBreakdown'],
            'Segments' => $main_segment,
            'LastTicketDate' => $Results['LastTicketDate'],
            'TicketAdvisory' => $Results['TicketAdvisory'],
            'ValidatingAirline' => $Results['ValidatingAirline']
         );

         $TTS_Invoice_Amount = floatval($FlightPrice['Fare']['OfferedPrice']) + floatval($FlightPrice['Fare']['TDS']);

         $custom_index[$input['ResultIndex']] = array('TTS_Invoice_Amount' => $TTS_Invoice_Amount, 'ResultIndex' => $Results['ResultIndex'], 'IsLCC' => $Results['IsLCC'], 'IsPanRequiredAtBook' => $IsPanRequired, 'IsPassportRequiredAtBook' => $IsPassportRequired, 'GSTAllowed' => $Results['GSTAllowed'], 'IsGSTMandatory' => $Results['IsGSTMandatory'], 'AirlineCode' => $Results['AirlineCode'], 'FareBreakdown' => $Results['FareBreakdown'], 'SuperAdminFareBreakup' => $FlightPrice['SuperAdminFareBreakup'], 'WebPartnerFareBreakup' => $FlightPrice['WebPartnerFareBreakup'], 'TraceId' => $TraceId, 'Supplier' => 'TBO');

      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareConfirmation', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'FlightDetailChangeInfo' => $FlightDetailChangeInfo,
         'IsPriceChanged' => $IsPriceChanged,
         'Result' => $TTS_Result
      );

      if ($ErrorCode != 0) {
         unset($tts_response['IsPriceChanged']);
      }

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }

   public function SSR(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'TraceId' => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
      );


      $url = "$this->AirService_URL/rest/SSR/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $SSRInfo = $response['Response'];
         $SSRData = array();
         $tts_Mealdata = array();
         $tts_Baggagedata = array();
         if ($IsLCC) {
            if (isset($SSRInfo['Baggage']) && $SSRInfo['Baggage']) {
               $BagaageData = array();
               foreach ($SSRInfo['Baggage'] as $key => $Baggages) {
                  foreach ($Baggages as $Baggage) {

                     $tts_key_ssr = $key . $Baggage['Origin'] . $Baggage['Destination'] . trim($Baggage['Code']);
                     $Baggage['CPrice'] = $Baggage['Price'];
                     $tts_Baggagedata[$tts_key_ssr] = $Baggage;

                     if (isset($Baggage['Text'])) {
                        $AirlineDescription = $Baggage['Text'];
                     } else {
                        $AirlineDescription = "Excess Baggage -" . $Baggage['Weight'] . "Kg";
                     }

                     $BagaageData[$key][] = array(
                        'Key' => $tts_key_ssr,
                        'AirlineCode' => $Baggage['AirlineCode'],
                        'FlightNumber' => $Baggage['FlightNumber'],
                        'WayType' => get_ssr_waytype($Baggage['WayType']),
                        'Code' => $Baggage['Code'],
                        'Description' => $Baggage['Description'],
                        "AirlineDescription" => $AirlineDescription,
                        'Weight' => $Baggage['Weight'],
                        'Price' => $Baggage['Price'],
                        'Origin' => $Baggage['Origin'],
                        'Destination' => $Baggage['Destination']
                     );

                  }
               }
               $TTS_Result['Baggage'] = $BagaageData;
            }
            if (isset($SSRInfo['MealDynamic']) && $SSRInfo['MealDynamic']) {
               $MealData = array();
               foreach ($SSRInfo['MealDynamic'] as $key => $MealDynamic) {
                  foreach ($MealDynamic as $Meal) {

                     $tts_key_ssr = $key . $Meal['Origin'] . $Meal['Destination'] . trim($Meal['Code']);
                     $Meal['CPrice'] = $Meal['Price'];
                     $tts_Mealdata[$tts_key_ssr] = $Meal;

                     $MealData[$key][] = array(
                        'Key' => $tts_key_ssr,
                        'AirlineCode' => $Meal['AirlineCode'],
                        'FlightNumber' => $Meal['FlightNumber'],
                        'WayType' => get_ssr_waytype($Meal['WayType']),
                        'Code' => $Meal['Code'],
                        'Description' => $Meal['Description'],
                        'AirlineDescription' => $Meal['AirlineDescription'],
                        'Quantity' => $Meal['Quantity'],
                        'Price' => $Meal['Price'],
                        'Origin' => $Meal['Origin'],
                        'Destination' => $Meal['Destination']
                     );
                  }
               }
               $TTS_Result['Meal'] = $MealData;
            }
         } else {
            if (isset($SSRInfo['Meal']) && $SSRInfo['Meal']) {
               $MealData = array();
               foreach ($SSRInfo['Meal'] as $key => $Meal) {

                  $tts_key_ssr = '00' . trim($Meal['Code']);
                  $Meal['CPrice'] = 0;
                  $tts_Mealdata[$tts_key_ssr] = $Meal;

                  $MealData[$key] = array(
                     'Key' => $tts_key_ssr,
                     'WayType' => get_ssr_waytype(2),
                     'Code' => $Meal['Code'],
                     'Description' => 1,
                     'AirlineDescription' => $Meal['Description'],
                     'Quantity' => 1,
                     'Price' => 0,
                     'Origin' => '',
                     'Destination' => ''
                  );
                  $SSRData['Meal'][$Meal['Code']] = $Meal;
               }
               $TTS_Result['Meal'] = $MealData;
            }
            if (isset($SSRInfo['SeatPreference'])) {
               $TTS_Result['SeatPreference'] = $SSRInfo['SeatPreference'];
            }
         }
         if (count($TTS_Result) == 0) {
            $ErrorCode = 2;
            $ErrorMessage = 'data not available';
         }
         $custom_index[$input['ResultIndex']] = array('Meal' => $tts_Mealdata, 'Baggage' => $tts_Baggagedata);
      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'SSR', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result' => $TTS_Result
      );

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }

   public function Book(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];
      $SSRData = isset($common_data['SSRData']) ? $common_data['SSRData'] : array();
      $flight_offline = $common_data['FlightOffline'];
      $account_log_lastid = $common_data['SaveData']['account_log_lastid'];
      $booking_lastid = $common_data['SaveData']['booking_lastid'];

      $FareBreakdowns = array();
      if ($common_data['indexinfo']['FareBreakdown']) {
         foreach ($common_data['indexinfo']['FareBreakdown'] as $PaxFare) {
            $FareBreakdowns[$PaxFare['PassengerType']] = $PaxFare;
         }
      }

      $Passengers = array();
      if ($input['Passengers']) {
         foreach ($input['Passengers'] as $key => $value) {

            $pax_type = $value['PaxType'];
            $Passengers[$key] = array(
               'Title' => $value['Title'],
               'FirstName' => $value['FirstName'],
               'LastName' => $value['LastName'],
               'PaxType' => $value['PaxType'],
               'DateOfBirth' => $value['DateOfBirth'],
               'Gender' => $value['Gender'],
               'PassportNo' => $value['PassportNo'],
               'PassportExpiry' => $value['PassportExpiry'],
               'Nationality' => isset($value['Nationality']) ? $value['Nationality'] : null,
               'PAN' => isset($value['PAN']) ? $value['PAN'] : null,
               'AddressLine1' => $value['AddressLine1'],
               'AddressLine2' => $value['AddressLine2'],
               'City' => $value['City'],
               'CountryCode' => $value['CountryCode'],
               'CountryName' => $value['CountryName'],
               'ContactNo' => $value['ContactNo'],
               'Email' => $value['Email'],
               'IsLeadPax' => $value['IsLeadPax'],
               'FFAirline' => $value['FFAirline'],
               'FFNumber' => $value['FFNumber'],
               'GSTCompanyAddress' => $value['GSTCompanyAddress'],
               'GSTCompanyContactNumber' => $value['GSTCompanyContactNumber'],
               'GSTCompanyName' => $value['GSTCompanyName'],
               'GSTNumber' => $value['GSTNumber'],
               'GSTCompanyEmail' => $value['GSTCompanyEmail'],
               'Fare' => array(
                  'BaseFare' => $FareBreakdowns[$pax_type]['BaseFare'],
                  'Tax' => $FareBreakdowns[$pax_type]['Tax'],
                  'TransactionFee' => 0,
                  'YQTax' => $FareBreakdowns[$pax_type]['YQTax'],
                  'AdditionalTxnFeeOfrd' => $FareBreakdowns[$pax_type]['AdditionalTxnFeeOfrd'],
                  'AdditionalTxnFeePub' => $FareBreakdowns[$pax_type]['AdditionalTxnFeePub'],
                  'AirTransFee' => 0,
               ),
            );
            if ($IsLCC) {
               if (isset($value['Meal']) and (!empty($value['Meal']))) {
                  $Meal = array();
                  foreach ($value['Meal'] as $MealDynamic) {
                     if (isset($SSRData['Meal'][$MealDynamic['Key']]) && $SSRData) {
                        $MealInfo = $SSRData['Meal'][$MealDynamic['Key']];
                        unset($MealInfo['CPrice']);
                        $Meal[] = $MealInfo;
                     }
                  }
                  $Passengers[$key]['MealDynamic'] = $Meal;
               }
               if (isset($value['Baggage']) and (!empty($value['Baggage']))) {
                  $Baggage = array();
                  foreach ($value['Baggage'] as $BaggageData) {
                     if (isset($SSRData['Baggage'][$BaggageData['Key']]) && $SSRData) {
                        $BaggageInfo = $SSRData['Baggage'][$BaggageData['Key']];
                        unset($BaggageInfo['CPrice']);
                        $Baggage[] = $BaggageInfo;
                     }
                  }
                  $Passengers[$key]['Baggage'] = $Baggage;
               }
            } else {
               if (isset($value['Meal']) and (!empty($value['Meal']))) {
                  $Meal = array();
                  foreach ($value['Meal'] as $MealDynamic) {
                     if (isset($SSRData['Meal'][$MealDynamic['Key']]) && $SSRData) {
                        $MealInfo = $SSRData['Meal'][$MealDynamic['Key']];
                        $Meal[] = $MealInfo;
                     }
                  }
                  $Passengers[$key]['Meal'] = $Meal;
               }
            }
         }
      }

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'TraceId' => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
         'Passengers' => $Passengers,
      );
      if ($IsLCC) {
         $service = 'Ticket';
         $url = "$this->AirBooking_URL/rest/Ticket/";
      } else {
         $service = 'Book';
         $url = "$this->AirBooking_URL/rest/Book/";
      }
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $custom_index = array();
      $getbooking_detail = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $result = $response['Response']['Response'];

         $PNR = $result['PNR'];
         $BookingId = $result['BookingId'];

         $next_request = array('UserIp' => $input['UserIp'], 'TraceId' => $TraceId, 'BookingId' => $BookingId, 'PNR' => $PNR, 'SearchTokenId' => $tts_search_token);
         if ($IsLCC) {
            $getbooking_detail = $result;
         } else {
            // Flight Hold Condition
            if ($flight_offline && $flight_offline['is_hold'] == 'Hold') {
               $getbooking_detail = $result;
            } else {
               $gds_ticket_response = TBOFlight::GDSTicket($next_request, $userauthdata);
               if ($gds_ticket_response['Response']['Error']['ErrorCode'] == 0) {
                  $getbooking_detail = $gds_ticket_response['Response']['Response'];
               } else {
                  $ErrorCode = $gds_ticket_response['Response']['Error']['ErrorCode'];
                  $ErrorMessage = $gds_ticket_response['Response']['Error']['ErrorMessage'];
               }
            }
         }
      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }
      if ($ErrorCode == 0) {
         TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         $booking_data = $TBOFlightModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId'], 'id' => $booking_lastid]);
         $tts_response = ConvertBookingDetail($input, $booking_data);
      } else {
         if ($getbooking_detail) {
            TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         } else {
            $booking_status = 'Processing';
            $booking_update_data = array('booking_status' => $booking_status);
            $TBOFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $TBOFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], $booking_update_data);  /* change By harish */
         }

         $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result' => array()
         );
      }
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $service, 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $tts_response;
   }

   public function UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid)
   {
      $TBOFlightModel = new TBOFlightModel();
      $Passengers = $getbooking_detail['FlightItinerary']['Passenger'];
      $origin = $getbooking_detail['FlightItinerary']['Origin'];
      $destination = $getbooking_detail['FlightItinerary']['Destination'];
      $pnr = $getbooking_detail['PNR'];
      $airline_code = $getbooking_detail['FlightItinerary']['AirlineCode'];
      $flight_number = $getbooking_detail['FlightItinerary']['Segments'][0]['Airline']['FlightNumber'];
      $departure_date = explode("T", $getbooking_detail['FlightItinerary']['Segments'][0]['Origin']['DepTime'])[0];
      $fare_rule = $getbooking_detail['FlightItinerary']['FareRules'];
      $segments = $getbooking_detail['FlightItinerary']['Segments'];

      $airline_pnr = array();
      if ($segments) {
         foreach ($segments as $segment) {
            $jkey = $segment['TripIndicator'];
            $skey = $segment['SegmentIndicator'];
            $airline_pnr[$jkey][$skey] = $segment['AirlinePNR'];
         }
      }

      $pax_update_data = array();

      if (isset($getbooking_detail['FlightItinerary']['Passenger'][0]['Ticket']) && $getbooking_detail['FlightItinerary']['Passenger'][0]['Ticket']['TicketNumber']) {
         $booking_status = 'Confirmed';
      } else if ($getbooking_detail['PNR']) {
         $booking_status = 'Hold';
      } else {
         $booking_status = 'Processing';
      }
      if ($booking_status == 'Confirmed') {
         $Confirmationprefix = $TBOFlightModel->getDataRowType("super_admin_website_setting", array(), "flight_confirmation_counter,flight_confirmation_prefix,id");
         $BookingConfirmationNumber = GenerateConfirmationNumber("Flight", $Confirmationprefix['flight_confirmation_prefix'], ($Confirmationprefix['flight_confirmation_counter'] + 1));
         $TBOFlightModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("flight_confirmation_counter" => ($Confirmationprefix['flight_confirmation_counter'] + 1)));
         $TBOFlightModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $booking_lastid, "service" => "flight", 'transaction_type' => "debit", 'action_type' => "booking"], ["booking_confirmation_number" => $BookingConfirmationNumber]);
      }
      foreach ($Passengers as $key => $Passenger) {
         $ticket_id = "";
         $ticket_number = "";
         $validating_airline = "";
         if (isset($Passenger['Ticket'])) {
            $ticket_id = $Passenger['Ticket']['TicketId'];
            $ticket_number = $Passenger['Ticket']['TicketNumber'];
            $validating_airline = $Passenger['Ticket']['ValidatingAirline'];
         }
         $pax_update_data = array(
            /* 'flight_booking_id' => $booking_lastid,
            'first_name'        => trim($Passenger['FirstName']),
            'pax_type'          => get_paxtype_name($Passenger['PaxType']), */
            'pax_id' => $Passenger['PaxId'],
            'ticket_id' => $ticket_id,
            'ticket_number' => $ticket_number,
            "validating_airline" => $validating_airline,
            "booking_status" => $booking_status
         );
         $TBOFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid, "first_name" => trim($Passenger['FirstName']), 'pax_type' => get_paxtype_name($Passenger['PaxType'])], $pax_update_data);
      }
      /*    $TBOFlightModel->updatePaxData('flight_booking_travelers',$pax_update_data,'first_name'); */
      $booking_update_data = array(
         'is_price_changed' => $getbooking_detail['IsPriceChanged'],
         'is_time_changed' => $getbooking_detail['IsTimeChanged'],
         'supplier_booking_id' => $getbooking_detail['BookingId'],
         'booking_status' => $booking_status,
         'fare_rule' => json_encode($fare_rule),
         'pnr' => $pnr,
         'airline_pnr' => json_encode($airline_pnr)
      );
      $TBOFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);

      $PaxName = $Passengers[0]['FirstName'] . ' ' . $Passengers[0]['LastName'] . ' X ' . count($Passengers);
      $Sector = $origin . '-' . $destination;
      $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date, 'AirlineString' => $airline_code . $flight_number, 'TicketNo' => $pnr);
      $account_update_data = array('service_log' => json_encode($service_log));
      $TBOFlightModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
   }

   private function GDSTicket(array $input, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $input['TraceId'];
      $BookingId = $input['BookingId'];
      $PNR = $input['PNR'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'TraceId' => $TraceId,
         'BookingId' => $BookingId,
         'PNR' => $PNR
      );
      $url = "$this->AirBooking_URL/rest/Ticket/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Ticket', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;
   }

   public function GetBookingDetails(array $input, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $PNR = $input['PNR'];

      if (isset($input['ImportPNR'])) {

         $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'PNR' => $PNR,
            'LastName' => $input['LastName']
         );

      } else {

         $TraceId = $input['TraceId'];
         $BookingId = $input['BookingId'];
         $request = array(
            'EndUserIp' => $input['UserIp'],
            'TokenId' => $this->TokenId,
            'BookingId' => $BookingId,
            'PNR' => $PNR,
            'TraceId' => $TraceId
         );

      }

      $url = "$this->AirBooking_URL/rest/GetBookingDetails/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetBookingDetails', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      return $response;
   }


   public function ReleasePNRRequest(array $input, array $userauthdata)
   {
      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'BookingId' => $BookingId,
         'Source' => $Source,
      );

      $url = "$this->AirBooking_URL/rest/ReleasePNRRequest/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'ReleasePNRRequest', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;

   }
   public function SendCancelRequest(array $input, $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $Sectors = array();
      $TicketId = array();
      $PassengerDetails = $common_data['PassengerDetails'];
      $PassengerDetails = array_column($PassengerDetails, 'ticket_id', 'id');
      if (isset($input['PaxId'])) {
         $nopax = count($PassengerDetails);
         if ($nopax >= count($input['PaxId'])) {

         } else {
            $message = "Invalid Pax Count.Total Passanger $nopax";
            api_custom_message(400, $message, false);
         }
         foreach ($input['PaxId'] as $paxid) {
            if (isset($PassengerDetails[$paxid])) {
               array_push($TicketId, $PassengerDetails[$paxid]);
            } else {
               $message = "Invalid PaxId Detail";
               api_custom_message(400, $message, false);
            }
         }
      }

      $SegmentsDetails = json_decode($common_data['segments'], true);

      if ($input['RequestType'] == 'FullCancellation') {
         $RequestType = 1;
      }
      if ($input['RequestType'] == 'PartialCancellation') {
         $RequestType = 2;
      }
      $BookingId = $common_data['supplier_booking_id'];
      $CancellationType = 2;

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'BookingId' => $BookingId,
         'RequestType' => $RequestType,
         'CancellationType' => $CancellationType,
         'Remarks' => $input['Remark'],
      );

      if ($input['RequestType'] == 'PartialCancellation') {
         $request['Sectors'] = $input['Sectors'];
         $request['TicketId'] = $TicketId;
      }
      $url = "$this->AirBooking_URL/rest/SendChangeRequest/";
      $response = TBO_Request($url, $request);
      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      if (isset($response['Response']['Error'])) {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];

      } else {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $ticket_info = $response['Response']['TicketCRInfo'];

         if ($ticket_info) {
            $tts_update_data = array();
            $PassengerDetails = array_flip($PassengerDetails);
            foreach ($ticket_info as $item) {
               $TTSPaxId = $PassengerDetails[$item['TicketId']];
               $canceldata = array(
                  'PaxId' => $TTSPaxId,
                  'Remarks' => $item['Remarks']
               );
               array_push($TTS_Result, $canceldata);
               $update_data = array(
                  'id' => $TTSPaxId,
                  'booking_status' => "Cancelled",
                  'flight_booking_id' => $input['BookingId'],
                  'supplier_data' => json_encode($item),
               );
               array_push($tts_update_data, $update_data);
            }
            $BookingStatus = "";
            $TBOFlightModel->updatePaxData('flight_booking_travelers', $tts_update_data, 'id');
            $BookingTravelersCount = $TBOFlightModel->getDataResultType("flight_booking_travelers", array("flight_booking_id" => $input['BookingId']), "id,flight_booking_id,booking_status");
            $PaxBookingStatus = array_unique(array_column($BookingTravelersCount, 'booking_status'));
            $PaxBookingStatusCount = count($PaxBookingStatus);
            if ($PaxBookingStatusCount == 1) {

               if (isset($PaxBookingStatus[0]) && $PaxBookingStatus[0] == "Cancelled") {
                  $BookingStatus = "Cancelled";
               }
            } else {
               $BookingStatus = "PartialCancelled";
            }
            if ($BookingStatus != "") {
               $TBOFlightModel->updateUserData('flight_booking_list', array("id" => $input['BookingId']), array("booking_status" => $BookingStatus));
            }

         } else {
            $ErrorCode = 1;
            $ErrorMessage = 'Cancellation failed from supplier side.';
         }
      }
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'SendChangeRequest', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result' => $TTS_Result
      );
      return $tts_response;
   }
   public function GetChangeRequestStatus(array $input, array $userauthdata)
   {
      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'ChangeRequestId' => $ChangeRequestId
      );

      $url = "$this->AirBooking_URL/rest/GetChangeRequestStatus/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetChangeRequestStatus', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;

   }
   public function GetCancellationCharges(array $input, array $userauthdata)
   {
      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId' => $this->TokenId,
         'BookingId' => $BookingId,
         'RequestType' => $RequestType,
         'BookingMode' => $BookingMode
      );

      $url = "$this->AirBooking_URL/rest/GetCancellationCharges/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetCancellationCharges', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;

   }

   public function ImportPNR(array $input, $tts_search_token, array $userauthdata)
   {

      $input['ImportPNR'] = true;
      $input['SearchTokenId'] = $tts_search_token;
      $response = TBOFlight::GetBookingDetails($input, $userauthdata);
      $TTS_Result = array();
      if ($response) {
         if ($response['Response']['Error']['ErrorCode'] == 0) {
            $ErrorCode = 0;
            $ErrorMessage = '';
            $FlightItinerary = $response['Response']['FlightItinerary'];
            if ($FlightItinerary['NonRefundable']) {
               $IsRefundable = false;
            } else {
               $IsRefundable = true;
            }
            if (isset($FlightItinerary['FareType'])) {
               $FareTypeIdentifier = get_flight_fare_type($FlightItinerary['FareClassification']['Type']);
               $FareType = $FareTypeIdentifier['fareType'];
               $FareTypeColor = $FareTypeIdentifier['color'];
            } else {
               $FareTypeIdentifier = get_flight_fare_type('Publish');
               $FareType = $FareTypeIdentifier['fareType'];
               $FareTypeColor = $FareTypeIdentifier['color'];
            }

            $FareRules = array();
            if ($FlightItinerary['FareRules']) {
               foreach ($FlightItinerary['FareRules'] as $rules) {
                  $ttsrules = array(
                     'Origin' => $rules['Origin'],
                     'Destination' => $rules['Destination'],
                     'Airline' => $rules['Airline'],
                     'FareBasisCode' => $rules['FareBasisCode'],
                     'FareRuleDetail' => $rules['FareRuleDetail']
                  );
                  array_push($FareRules, $ttsrules);
               }
            }

            $AgentCommission = floatval($FlightItinerary['Fare']['Discount']) + floatval($FlightItinerary['Fare']['PLBEarned']) + floatval($FlightItinerary['Fare']['IncentiveEarned']) + floatval($FlightItinerary['Fare']['CommissionEarned']);

            $OtherCharges = floatval($FlightItinerary['Fare']['OtherCharges']) + floatval($FlightItinerary['Fare']['ServiceFee']) + floatval($FlightItinerary['Fare']['AdditionalTxnFeePub']);

            $TTS_brakup = array(
               'BaseFare' => $FlightItinerary['Fare']['BaseFare'],
               'Tax' => $FlightItinerary['Fare']['Tax'],
               'TaxBreakup' => $FlightItinerary['Fare']['TaxBreakup'],
               'YQTax' => $FlightItinerary['Fare']['YQTax'],
               'OtherCharges' => $OtherCharges,
               'Discount' => 0,
               'PublishedPrice' => $FlightItinerary['Fare']['PublishedFare'],
               'OfferedPrice' => $FlightItinerary['Fare']['OfferedFare'],
               'AgentCommission' => $AgentCommission,
               'ServiceCharges' => 0,
               'TDS' => 0,
               'GST' => array(
                  "CGSTAmount" => 0,
                  "CGSTRate" => 0,
                  "IGSTAmount" => 0,
                  "IGSTRate" => 18,
                  "SGSTAmount" => 0,
                  "SGSTRate" => 0,
                  "TaxableAmount" => 0
               ),
               'TotalBaggageCharges' => $FlightItinerary['Fare']['TotalBaggageCharges'],
               'TotalMealCharges' => $FlightItinerary['Fare']['TotalMealCharges'],
               'TotalSeatCharges' => $FlightItinerary['Fare']['TotalSeatCharges'],
            );


            $Passenger = array();
            if ($FlightItinerary['Passenger']) {
               foreach ($FlightItinerary['Passenger'] as $PaxTraveler) {
                  $DateOfBirth = null;
                  $PAN = null;
                  $PassportNo = null;
                  $PassportExpiry = null;
                  $PassportIssue = null;
                  $Nationality = null;
                  $AddressLine2 = null;
                  $TicketNumber = null;
                  $TicketId = null;
                  $ValidatingAirline = null;
                  if (isset($PaxTraveler['DateOfBirth'])) {
                     $DateOfBirth = $PaxTraveler['DateOfBirth'];
                  }
                  if (isset($PaxTraveler['PAN'])) {
                     $PAN = $PaxTraveler['PAN'];
                  }
                  if (isset($PaxTraveler['PassportNo'])) {
                     $PassportNo = $PaxTraveler['PassportNo'];
                  }
                  if (isset($PaxTraveler['PassportExpiry'])) {
                     $PassportExpiry = $PaxTraveler['PassportExpiry'];
                  }
                  if (isset($PaxTraveler['AddressLine2'])) {
                     $AddressLine2 = $PaxTraveler['AddressLine2'];
                  }
                  if (isset($PaxTraveler['Ticket'])) {
                     $TicketId = $PaxTraveler['Ticket']['TicketId'];
                     $TicketNumber = $PaxTraveler['Ticket']['TicketNumber'];
                     $ValidatingAirline = $PaxTraveler['Ticket']['ValidatingAirline'];
                  }
                  $CountryName = null;
                  if (isset($PaxTraveler['CountryName'])) {
                     $CountryName = $PaxTraveler['CountryName'];
                  }

                  $AgentCommission = floatval($PaxTraveler['Fare']['Discount']) + floatval($PaxTraveler['Fare']['PLBEarned']) + floatval($PaxTraveler['Fare']['IncentiveEarned']) + floatval($PaxTraveler['Fare']['CommissionEarned']);

                  $OtherCharges = floatval($PaxTraveler['Fare']['OtherCharges']) + floatval($PaxTraveler['Fare']['ServiceFee']) + floatval($PaxTraveler['Fare']['AdditionalTxnFeePub']);
                  $Fare = array(
                     'BaseFare' => $PaxTraveler['Fare']['BaseFare'],
                     'Tax' => $PaxTraveler['Fare']['Tax'],
                     'TaxBreakup' => $PaxTraveler['Fare']['TaxBreakup'],
                     'YQTax' => $PaxTraveler['Fare']['YQTax'],
                     'ServiceCharges' => 0,
                     'OtherCharges' => $OtherCharges,
                     'Discount' => 0,
                     'AgentCommission' => $AgentCommission,
                     'TDS' => 0,
                     'GSTAmount' => 0,
                     'PublishedPrice' => $PaxTraveler['Fare']['PublishedFare'],
                     'OfferedPrice' => $PaxTraveler['Fare']['OfferedFare'],
                     'BaggageCharges' => $PaxTraveler['Fare']['TotalBaggageCharges'],
                     'MealCharges' => $PaxTraveler['Fare']['TotalMealCharges'],
                     'SeatCharges' => $PaxTraveler['Fare']['TotalSeatCharges'],
                  );

                  $pax = array(
                     'PaxId' => $PaxTraveler['PaxId'],
                     'Title' => $PaxTraveler['Title'],
                     'FirstName' => $PaxTraveler['FirstName'],
                     'LastName' => $PaxTraveler['LastName'],
                     'PaxType' => $PaxTraveler['PaxType'],
                     'Gender' => $PaxTraveler['Gender'],
                     'DateOfBirth' => $DateOfBirth,
                     'PAN' => $PAN,
                     'PassportNo' => $PassportNo,
                     'PassportExpiry' => $PassportExpiry,
                     'PassportIssue' => $PassportIssue,
                     'Nationality' => $Nationality,
                     'IsLeadPax' => $PaxTraveler['IsLeadPax'],
                     'Email' => $PaxTraveler['Email'],
                     'ContactNo' => $PaxTraveler['ContactNo'],
                     'AddressLine1' => $PaxTraveler['AddressLine1'],
                     'AddressLine2' => $AddressLine2,
                     'City' => $PaxTraveler['City'],
                     'CountryCode' => $PaxTraveler['CountryCode'],
                     'CountryName' => $CountryName,
                     'FFAirline' => $PaxTraveler['FFAirlineCode'],
                     'FFNumber' => $PaxTraveler['FFNumber'],
                     'TicketId' => $TicketId,
                     'TicketNumber' => $TicketNumber,
                     'ValidatingAirline' => $ValidatingAirline,
                     'Baggage' => array(),
                     'Meal' => array(),
                     'Seat' => array(),
                     'Fare' => $Fare,
                     'GSTCompanyAddress' => '',
                     'GSTCompanyName' => '',
                     'GSTNumber' => '',
                     "GSTCompanyEmail" => '',
                     'GSTCompanyContactNumber' => '',
                  );

                  array_push($Passenger, $pax);
               }
            }

            $main_segment = array();
            $group_check = array();
            if ($FlightItinerary['Segments']) {

               foreach ($FlightItinerary['Segments'] as $segkey => $segment) {
                  $TripIndicator = 1;
                  if (isset($item['TripIndicator'])) {
                     $TripIndicator = $item['TripIndicator'];
                  }
                  $AirlinePNR = '';
                  if (isset($segment['AirlinePNR'])) {
                     $AirlinePNR = $segment['AirlinePNR'];
                  }

                  $jkey = $TripIndicator - 1;
                  $CabinClass = get_cabin_class_name($segment['CabinClass']);
                  $TechStopPoint = array();
                  if (isset($segment['StopOver']) && $segment['StopOver']) {
                     $TechStopPoint = array(
                        'Code' => $segment['StopPoint'],
                        'Name' => isset($airport_list[$segment['StopPoint']]) ? $airport_list[$segment['StopPoint']] : ""
                     );
                  }
                  $main_segment[$jkey][$segkey] = array(
                     'TripIndicator' => $TripIndicator,
                     'SegmentIndicator' => $segment['SegmentIndicator'],
                     'CheckInBaggage' => $segment['Baggage'],
                     'CabinBaggage' => $segment['CabinBaggage'],
                     'CabinClass' => $CabinClass,
                     'Duration' => $segment['Duration'],
                     'TechStopPoint' => $TechStopPoint,
                     'Craft' => $segment['Craft'],
                     'AirlinePNR' => $AirlinePNR,
                     'Airline' => array(
                        'AirlineCode' => $segment['Airline']['AirlineCode'],
                        'AirlineName' => $segment['Airline']['AirlineName'],
                        'FlightNumber' => (int) $segment['Airline']['FlightNumber'],
                        'FareClass' => $segment['Airline']['FareClass'],
                        'OperatingCarrier' => $segment['Airline']['OperatingCarrier']
                     ),
                     'Origin' => array(
                        'AirportCode' => $segment['Origin']['Airport']['AirportCode'],
                        'AirportName' => $segment['Origin']['Airport']['AirportName'],
                        'CityCode' => $segment['Origin']['Airport']['CityCode'],
                        'CityName' => $segment['Origin']['Airport']['CityName'],
                        'CountryCode' => $segment['Origin']['Airport']['CountryCode'],
                        'CountryName' => ucwords(strtolower($segment['Origin']['Airport']['CountryName'])),
                        'Terminal' => $segment['Origin']['Airport']['Terminal'],
                        'DepartTime' => $segment['Origin']['DepTime']
                     ),
                     'Destination' => array(
                        'AirportCode' => $segment['Destination']['Airport']['AirportCode'],
                        'AirportName' => $segment['Destination']['Airport']['AirportName'],
                        'CityCode' => $segment['Destination']['Airport']['CityCode'],
                        'CityName' => $segment['Destination']['Airport']['CityName'],
                        'CountryCode' => $segment['Destination']['Airport']['CountryCode'],
                        'CountryName' => ucwords(strtolower($segment['Destination']['Airport']['CountryName'])),
                        'Terminal' => $segment['Destination']['Airport']['Terminal'],
                        'ArrivalTime' => $segment['Destination']['ArrTime']
                     )
                  );

               }
            }


            $TTS_Result = array(
               'SupplierBookingId' => $FlightItinerary['BookingId'],
               'JourneyType' => $FlightItinerary['JourneyType'],
               'TripIndicator' => $FlightItinerary['TripIndicator'],
               'PNR' => $FlightItinerary['PNR'],
               'IsDomestic' => $FlightItinerary['IsDomestic'],
               'IsManual' => $FlightItinerary['IsManual'],
               'IsLCC' => $FlightItinerary['IsLCC'],
               'IsRefundable' => $IsRefundable,
               'FareType' => $FareType,
               'Origin' => $FlightItinerary['Origin'],
               'Destination' => $FlightItinerary['Destination'],
               'AirlineCode' => $FlightItinerary['AirlineCode'],
               'LastTicketDate' => isset($FlightItinerary['LastTicketDate']) ? $FlightItinerary['LastTicketDate'] : "",
               'ValidatingAirlineCode' => $FlightItinerary['ValidatingAirlineCode'],
               'AirlineRemark' => $FlightItinerary['AirlineRemark'],
               'Fare' => $TTS_brakup,
               'Passenger' => $Passenger,
               'Segments' => $main_segment,
               'FareRules' => $FareRules
            );

         } else {
            $ErrorCode = $response['Response']['Error']['ErrorCode'];
            $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
         }
      } else {
         $ErrorCode = 1;
         $ErrorMessage = 'No Details Found';
      }



      $tts_response = array(
         'UserIp' => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result' => $TTS_Result
      );
      return $tts_response;




   }

}

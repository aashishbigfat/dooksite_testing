<?php

namespace Modules\TBOHotel\Controllers;

use App\Controllers\BaseController;
use App\Modules\TBOHotel\Models\TBOHotelModel;
use Modules\TBOHotel\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;


class TBOHotel extends BaseController
{
   
    public function __construct()
    {
       helper('Modules\TBOHotel\Helpers\hotel');
       $this->GetTimeZone=app_timezone();
       $this->TokenId=TBOHotel::Authenticate();  
    }

    /**
     * ----------------------------------------------
     * TBO Authenticate Function And Generate Token
     * ---------------------------------------------
     */

    private function Authenticate() {

      $TBOHotelModel=new TBOHotelModel();
      $request   = \Config\Services::request();
      $apiconfig= new APIConfig;
      $credential=$apiconfig->tbo_hotel_credential;
      if($credential['Mode']=='Live')
      {
           $ClientId  = 'tboprod';
           $UserName  = $credential['UserName'];
           $Password  = $credential['Password'];
           $LoginType = 2;
           $this->Authenticate_URL = 'https://api.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate';
           $this->HotelService_URL = 'https://api.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc';
           $this->HotelService_URL1= 'https://booking.travelboutiqueonline.com/HotelAPI_V10/HotelService.svc';
           $this->Mode = 'Live';
      } else {
           $ClientId  = 'ApiIntegrationNew';
           $UserName  = $credential['UserName'];
           $Password  = $credential['Password'];
           $LoginType = 2;
           $this->Authenticate_URL = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
           $this->HotelService_URL = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc';
           $this->HotelService_URL1= 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc';
           $this->Mode = 'Test';
      }

      $token_id=null;
      $db_token_id=$TBOHotelModel->fetch_auth_token(strtotime(Time::today($this->GetTimeZone)),$this->Mode);
      if(empty($db_token_id))
      {
            $requestdata=array(
                              'EndUserIp' => $request->getIPAddress(),
                              'ClientId'  => $ClientId,
                              'UserName'  => $UserName,
                              'Password'  => $Password,
                              'LoginType' => $LoginType
                           );
            $url = $this->Authenticate_URL;
            $response= TBO_Request($url,$requestdata);
            if($response['Status'] == 1) {
               $token_id = $response['TokenId'];
            }
            /*--------------Start Insert API Logs------------------*/
            $insertlog=array(
                              'id'       => 2,
                              'token_id' => $token_id,
                              'api_mode' => $this->Mode,
                              'service' => "Hotel",
                              'request'  => json_encode($requestdata),
                              'response' => json_encode($response),
                              'created'  => strtotime(Time::today($this->GetTimeZone))
                           );
            $TBOHotelModel->insert_update_data('tbo_auth_token',$insertlog);
            /*--------------End Insert API Logs------------------*/
      } else {
            $token_id=$db_token_id['token_id'];
      }
      return $token_id;
    }
    public function Search(array $input,$tts_search_token,array $userauthdata)
    {
      $RoomGuests=array();
      if($input['RoomGuests']) {
         foreach($input['RoomGuests'] as $RoomGuest) {
            if(isset($RoomGuest['ChildAge'])) {
                     $RoomGuests[] = array(
                        'NoOfAdults'=>$RoomGuest['Adult'],
                        'NoOfChild'=>$RoomGuest['Child'],
                        'ChildAge'=>$RoomGuest['ChildAge']
                  );
            } else {
               $message='ChildAge tag not present in request';
               api_custom_message(400,$message,false);
            }
         }
      }
      
      $CheckInDate = date('d/m/Y', strtotime($input['CheckInDate']));
      $url ="$this->HotelService_URL/rest/GetHotelResult/";
      $request = array(
                           'CheckInDate'       => $CheckInDate,
                           'NoOfNights'        => $input['NoOfNights'],
                           'CountryCode'       => $input['CountryCode'],
                           'CityId'            => $input['DestinationCityId'],
                           'ResultCount'       => $input['ResultCount'],
                           'PreferredCurrency' => 'INR',
                           'GuestNationality'  => $input['GuestNationality'],
                           'NoOfRooms'         => $input['NoOfRooms'],
                           'MaxRating'         => intval($input['MaxRating']),
                           'MinRating'         => intval($input['MinRating']),
                           'ReviewScore'       => 0,
                           'IsNearBySearchAllowed'=>false,
                           'EndUserIp'         => $input['UserIp'],
                           'TokenId'           => $this->TokenId,
                           'RoomGuests'        => $RoomGuests
                     );
         $SearhData = array('Supplier'=>'TBO','URL'=>$url,'Request'=>json_encode($request),"TokenId"=> $this->TokenId);
        return  $SearhData;
   }
    public function ConvertSearchResponse($input,$response,$convert_response,$custom_index,$common_data)
    {
      $TTS_Result=array();
         $selectedMarkupDataInfo=array();
         $super_admin_markup=$common_data['super_admin_markup'];
         $super_admin_discount=$common_data['super_admin_discount'];
         $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
         $star_rating_array=array();
         $userauthdata=$common_data['userauthdata'];
       
            $custom_index=array();
            $TTS_Result=array();
            $response = json_decode($response, true);

            if($response['HotelSearchResult']['Error']['ErrorCode']==0)
            {  
               $trace_id=$response['HotelSearchResult']['TraceId'];
               $ErrorCode=0;
               $ErrorMessage='';
               $HotelResults=$response['HotelSearchResult']['HotelResults'];
               if($HotelResults)
               {
                  if($input['CountryCode']=='IN')
                  {
                     $region_type='domestic';
                  } else {
                     $region_type='international';
                  }
                  $star_rating_array=array();
                  if($super_admin_discount){
                     $super_admin_discount = array_filter($super_admin_discount,function ($value) {
                        $discountSupplier  =  explode(",",$value["supplier"]);
                       return (in_array("TBO",$discountSupplier));
                   }); 
                    $super_admin_discount = reset($super_admin_discount);
                 } 
                  $TBOCustomIndex  =  array();
                  foreach($HotelResults as $list)
                  {
                     $admin_markup_filter_Data=get_markup_value("TBO",$super_admin_markup,$star_rating_array,$list['StarRating'],$input['NoOfNights'],$input['NoOfRooms'],$selectedMarkupDataInfo);
                     $admin_markup =   $admin_markup_filter_Data['markup_data'];
                     $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
                     $HotelPrice=get_hotel_fare($admin_markup,$super_admin_discount,$list['Price'],$userauthdata,$super_admin_gst_state_code);
                     $ResultIndex="TBO".$list['ResultIndex'];              
                     $TTS_Result[]=array(
                                          'IsHotDeal' => false,
                                          'ResultIndex' =>$ResultIndex,
                                          'HotelCode' => $list['HotelCode'],
                                          'HotelName' => $list['HotelName'],
                                          'HotelCategory' => $list['HotelCategory'],
                                          'StarRating' => $list['StarRating'],
                                          'HotelDescription' => $list['HotelDescription'],
                                          'HotelPromotion' => $list['HotelPromotion'],
                                          'HotelPolicy' => $list['HotelPolicy'],
                                          'Price'=>$HotelPrice,
                                          'HotelPicture' => $list['HotelPicture'],
                                          'HotelAddress' => $list['HotelAddress'],
                                          'HotelContactNo' => $list['HotelContactNo'],
                                          'HotelMap' => $list['HotelMap'],
                                          'Latitude' => $list['Latitude'],
                                          'Longitude' => $list['Longitude'],
                                          'HotelLocation' => $list['HotelLocation']
                                       );
                                       $custom_index[$ResultIndex] =  array("ResultIndex"=>$list['ResultIndex'],'Supplier'=>'TBO',"StarRating"=>$list['StarRating'],'TraceId'=>$trace_id);           
                  }
                  
                  /* $custom_index=array('TraceId'=>$trace_id,'RegionType'=>$region_type,'NoOfNights'=>$request['NoOfNights'],'NoOfRooms'=>$request['NoOfRooms'],'GuestNationality'=>$request['GuestNationality'],"CustomIndex"=>$TBOCustomIndex);
      */          }
            }
            return array('convert_response'=>$TTS_Result,'custom_index'=>$custom_index);
    }

    public function GetHotelInfo(array $input,array $common_data,array $userauthdata)
    {
         $tts_search_token=$input['SearchTokenId'];
         $TraceId=$common_data['CustomIndex']['TraceId'];
         $request=array(
            'EndUserIp'    => $input['UserIp'],
            'TokenId'      => $this->TokenId,
            'TraceId'      => $TraceId,
            'ResultIndex'  => $common_data['CustomIndex']['ResultIndex'],
            'HotelCode'    => $input['HotelCode']
         );

         $url ="$this->HotelService_URL/rest/GetHotelInfo/";
         $response= TBO_Request($url,$request);

         $TBOHotelModel=new TBOHotelModel();
         $TTS_Result=array();
         $custom_index=array();
         if($response['HotelInfoResult']['Error']['ErrorCode']==0)
         {  
            $trace_id=$response['HotelInfoResult']['TraceId'];
            $ErrorCode=0;
            $ErrorMessage='';
      
            $HotelDetails=$response['HotelInfoResult']['HotelDetails'];
            $TTS_Result=array(
                     'HotelName'=>$HotelDetails['HotelName'],
                     'StarRating'=>$HotelDetails['StarRating'],
                     'HotelURL'=>$HotelDetails['HotelURL'],
                     'Description'=>$HotelDetails['Description'],
                     'Attractions'=>$HotelDetails['Attractions'],
                     'HotelFacilities'=>$HotelDetails['HotelFacilities'],
                     'HotelPolicy'=>$HotelDetails['HotelPolicy'],
                     'SpecialInstructions'=>$HotelDetails['SpecialInstructions'],
                     'HotelPicture'=>$HotelDetails['HotelPicture'],
                     'Images'=>$HotelDetails['Images'],
                     'Address'=>$HotelDetails['Address'],
                     'CountryName'=>$HotelDetails['CountryName'],
                     'PinCode'=>$HotelDetails['PinCode'],
                     'HotelContactNo'=>$HotelDetails['HotelContactNo'],
                     'FaxNumber'=>$HotelDetails['FaxNumber'],
                     'Email'=>$HotelDetails['Email'],
                     'Latitude'=>$HotelDetails['Latitude'],
                     'Longitude'=>$HotelDetails['Longitude'],
                     'RoomData'=>$HotelDetails['RoomData'],
                     'RoomFacilities'=>$HotelDetails['RoomFacilities'],
                     'Services'=>$HotelDetails['Services']
                );

            $custom_index['CommonData']=array('TraceId'=>$trace_id,'Supplier'=>'TBO','RegionType'=>$common_data['RegionType'],'NoOfNights'=>$common_data['NoOfNights'],'NoOfRooms'=>$common_data['NoOfRooms'],'StarRating'=>$HotelDetails['StarRating'],'GuestNationality'=>$common_data['GuestNationality'],"ResultIndex"=>$common_data['CustomIndex']['ResultIndex']);

         } else {
            $trace_id='';
            $ErrorCode=$response['HotelInfoResult']['Error']['ErrorCode'];
            $ErrorMessage=$response['HotelInfoResult']['Error']['ErrorMessage'];
         }
          /*--------------Start Insert API Logs------------------*/
          $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetHotelInfo',strtotime(Time::now($this->GetTimeZone)));
          /*--------------End Insert API Logs--------------------*/
          $tts_response = array(
                                  'UserIp'         => $input['UserIp'],
                                  'SearchTokenId'  => $tts_search_token,
                                  'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                  'Result'         => $TTS_Result
                               );
          return array('Response'=>$tts_response,'CustomIndex'=>$custom_index); 
    }

    public function GetHotelRoom(array $input,array $common_data,array $userauthdata)
    {
      $selectedMarkupDataInfo =  array();
      $TraceId=$common_data['CustomIndex']['TraceId'];
         $tts_search_token=$input['SearchTokenId'];
         $region_type=$common_data['RegionType'];
         $star_rating=$common_data['CustomIndex']['StarRating'];
         $no_of_night=$common_data['NoOfNights'];
         $no_of_rooms=$common_data['NoOfRooms'];
        
         $super_admin_markup=$common_data['super_admin_markup'];
         $super_admin_discount=$common_data['super_admin_discount'];
         $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
         $common_data=array('TraceId'=>$TraceId,'Supplier'=>'TBO','RegionType'=>$common_data['RegionType'],'NoOfNights'=>$common_data['NoOfNights'],'NoOfRooms'=>$common_data['NoOfRooms'],'StarRating'=>$common_data['CustomIndex']['StarRating'],'GuestNationality'=>$common_data['GuestNationality'],"ResultIndex"=>$common_data['CustomIndex']['ResultIndex']);
       
         $request=array(
                           'EndUserIp'    => $input['UserIp'],
                           'TokenId'      => $this->TokenId,
                           'TraceId'      => $TraceId,
                           'ResultIndex'  => $common_data['ResultIndex'],
                           'HotelCode'    => $input['HotelCode']
                      );

         $url ="$this->HotelService_URL/rest/GetHotelRoom/";
         $response= TBO_Request($url,$request);
         $TBOHotelModel=new TBOHotelModel();
         $TTS_Result=array();
         $custom_index=array();
         if($response['GetHotelRoomResult']['Error']['ErrorCode']==0)
         {  
            $trace_id=$response['GetHotelRoomResult']['TraceId'];
            $ErrorCode=0;
            $ErrorMessage='';
      
            $RoomDetails=$response['GetHotelRoomResult'];

            $HotelRoomsDetails=array();
            if($RoomDetails['HotelRoomsDetails'])
            {
              
               $star_rating_array=array();
               foreach($RoomDetails['HotelRoomsDetails'] as $Room)
               {
                     $admin_markup_filter_Data=get_markup_value("TBO",$super_admin_markup,$star_rating_array,$star_rating,$no_of_night,$no_of_rooms,$selectedMarkupDataInfo);
                     $admin_markup =   $admin_markup_filter_Data['markup_data'];
                     $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
                     $HotelPrice=get_hotel_fare($admin_markup,$super_admin_discount,$Room['Price'],$userauthdata,$super_admin_gst_state_code);
                     unset($Room['DayRates']);
                     $Room['Price']=$HotelPrice;
                     $HotelRoomsDetails[]=$Room;
               }
            }
            
            $TTS_Result=array(
                              'IsUnderCancellationAllowed'=>$RoomDetails['IsUnderCancellationAllowed'],
                              'IsPolicyPerStay'=>$RoomDetails['IsPolicyPerStay'],
                              'HotelRoomsDetails'=>$HotelRoomsDetails,
                              'RoomCombinations'=>$RoomDetails['RoomCombinations'],
                             );

            $common_data['RoomDetails']=$RoomDetails['HotelRoomsDetails'];
            $custom_index=$common_data;

         } else {
            $trace_id='';
            $ErrorCode=$response['GetHotelRoomResult']['Error']['ErrorCode'];
            $ErrorMessage=$response['GetHotelRoomResult']['Error']['ErrorMessage'];
         }
          /*--------------Start Insert API Logs------------------*/
          $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetHotelRoom',strtotime(Time::now($this->GetTimeZone)));
          /*--------------End Insert API Logs--------------------*/
          $tts_response = array(
                                  'UserIp'         => $input['UserIp'],
                                  'SearchTokenId'  => $tts_search_token,
                                  'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                  'Result'         => $TTS_Result
                               );
          return array('Response'=>$tts_response,'CustomIndex'=>$custom_index); 
    }

    public function BlockRoom(array $input,array $common_data,array $userauthdata)
    {
         $selectedMarkupDataInfo   =  array();
         $tts_search_token=$input['SearchTokenId'];
         $TraceId=$common_data['TraceId'];
         $region_type=$common_data['RegionType'];
         $star_rating=$common_data['StarRating'];
         $no_of_night=$common_data['NoOfNights'];
         $no_of_rooms=$common_data['NoOfRooms'];
         $super_admin_markup=$common_data['super_admin_markup'];
         $super_admin_discount=$common_data['super_admin_discount'];
         $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
         $HotelRoomsDetails=array();
         if(is_array($input['HotelRoomsDetails'])) {
            if($input['HotelRoomsDetails'])
            {
               foreach($input['HotelRoomsDetails'] as $RoomsIndex)
               {
                     foreach($common_data['RoomDetails'] as $RoomDetails)
                     {
                        if($RoomsIndex['RoomIndex']==$RoomDetails['RoomIndex'])
                        {
                              $RoomDetails['SmokingPreference']=GetSmokingPreference($RoomDetails['SmokingPreference']);
                              $HotelRoomsDetails[]=$RoomDetails;
                        }
                     }
               }
            }
         } else {
            api_custom_message(400,'HotelRoomsDetails incorrect format',false);
         }

         $request = array(
                           'EndUserIp'        => $input['UserIp'],
                           'TokenId'          => $this->TokenId,
                           'TraceId'          => $TraceId,
                           'ResultIndex'      => $common_data['ResultIndex'],
                           'HotelCode'        => $input['HotelCode'],
                           'HotelName'        => $input['HotelName'],
                           'GuestNationality' => $common_data['GuestNationality'],
                           'NoOfRooms'        => $input['NoOfRooms'],
                           'ClientReferenceNo'=> 0,
                           'IsVoucherBooking' => true,
                           'HotelRoomsDetails'=> $HotelRoomsDetails
                        );

         $url ="$this->HotelService_URL/rest/BlockRoom/";
         $response= TBO_Request($url,$request);
         $TBOHotelModel=new TBOHotelModel();
         $TTS_Result=array();
         $custom_index=array();
         $TTS_Invoice_Amount=0;
         if($response['BlockRoomResult']['Error']['ErrorCode']==0)
         {  
            $trace_id=$response['BlockRoomResult']['TraceId'];
            $ErrorCode=0;
            $ErrorMessage='';
          $webPartnerFarebreakup  =  array();
          $superAdminFarebreakup  =  array();
            $Blockresponse=$response['BlockRoomResult'];
            $CustomHotelRoomsDetails=array();

            $IsPassportMandatory=false; $IsPANMandatory=false;

            if($Blockresponse['HotelRoomsDetails'])
            {
               $star_rating_array=array();
              

               foreach($Blockresponse['HotelRoomsDetails'] as $roomkey=>$Room)
               {
                  $admin_markup_filter_Data=get_markup_value("TBO",$super_admin_markup,$star_rating_array,$star_rating,$no_of_night,$no_of_rooms,$selectedMarkupDataInfo);
                     $admin_markup =   $admin_markup_filter_Data['markup_data'];
                     $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
                  $HotelPriceBreakup=get_hotel_fare($admin_markup,$super_admin_discount,$Room['Price'],$userauthdata,$super_admin_gst_state_code,"blockroom");
                  unset($Room['DayRates']);
                  $HotelPrice  = $HotelPriceBreakup['WebPartnerBreakup']; 
                  $webPartnerFarebreakup[$roomkey]   =  $HotelPrice;
                  $superAdminFarebreakup[$roomkey]   =  $HotelPriceBreakup['SuperAdminBreakup'];
                  $TTS_Invoice_Amount+=round_value($HotelPrice['OfferedPrice']+$HotelPrice['TDS']);

                  $Room['Price']=$HotelPrice;                  
                  $IsPassportMandatory=$Room['IsPassportMandatory'];
                  $IsPANMandatory=$Room['IsPANMandatory'];
                  $CustomHotelRoomsDetails[]=$Room;

               }
            }

            $HotelNorms='';
            if(isset($Blockresponse['HotelNorms']))
            {
               $HotelNorms=$Blockresponse['HotelNorms'];
            }

            $TTS_Result=array(
                               'IsCancellationPolicyChanged'=>$Blockresponse['IsCancellationPolicyChanged'],
                               'IsHotelPolicyChanged'=>$Blockresponse['IsHotelPolicyChanged'],
                               'IsPriceChanged'=>$Blockresponse['IsPriceChanged'],
                               'IsPackageFare'=>$Blockresponse['IsPackageFare'],
                               'IsDepartureDetailsMandatory'=>$Blockresponse['IsDepartureDetailsMandatory'],
                               'IsPackageDetailsMandatory'=>$Blockresponse['IsPackageDetailsMandatory'],
                               'AvailabilityType'=>$Blockresponse['AvailabilityType'],
                               'GSTAllowed'=>$Blockresponse['GSTAllowed'],
                               'HotelNorms'=>$HotelNorms,
                               'HotelName'=>$Blockresponse['HotelName'],
                               'AddressLine1'=>$Blockresponse['AddressLine1'],
                               'AddressLine2'=>$Blockresponse['AddressLine2'],
                               'StarRating'=>$Blockresponse['StarRating'],
                               'HotelPolicyDetail'=>$Blockresponse['HotelPolicyDetail'],
                               'Latitude'=>$Blockresponse['Latitude'],
                               'Longitude'=>$Blockresponse['Longitude'],
                               'BookingAllowedForRoamer'=>$Blockresponse['BookingAllowedForRoamer'],
                               'AncillaryServices'=>$Blockresponse['AncillaryServices'],
                               'HotelRoomsDetails'=>$CustomHotelRoomsDetails,
                               'ValidationsAtConfirm'=>isset($Blockresponse['ValidationsAtConfirm'])?$Blockresponse['ValidationsAtConfirm']:""
                             );
      
            unset($common_data['RoomDetails']);
            $common_data['BlockRooms']=$Blockresponse['HotelRoomsDetails'];
            $common_data['IsPackageFare']=$Blockresponse['IsPackageFare'];
            $common_data['IsPackageDetailsMandatory']=$Blockresponse['IsPackageDetailsMandatory'];
            $common_data['IsPassportMandatory']=$IsPassportMandatory;
            $common_data['IsPANMandatory']=$IsPANMandatory;
            $common_data['TTS_Invoice_Amount']=$TTS_Invoice_Amount;
            $common_data['WebPartnerFarebreakup']=$webPartnerFarebreakup;
            $common_data['SuperAdminFarebreakup']=$superAdminFarebreakup;
            $custom_index=$common_data;
         } else {
            $trace_id='';
            $ErrorCode=$response['BlockRoomResult']['Error']['ErrorCode'];
            $ErrorMessage=$response['BlockRoomResult']['Error']['ErrorMessage'];
         }
          /*--------------Start Insert API Logs------------------*/
          $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'BlockRoom',strtotime(Time::now($this->GetTimeZone)));
          /*--------------End Insert API Logs--------------------*/
          $tts_response = array(
                                  'UserIp'         => $input['UserIp'],
                                  'SearchTokenId'  => $tts_search_token,
                                  'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                  'Result'         => $TTS_Result
                               );
          return array('Response'=>$tts_response,'CustomIndex'=>$custom_index);

    }

    public function Book(array $input,$common_data,array $userauthdata,array $saveInfo)
    {
      $tts_search_token=$input['SearchTokenId'];
      $TraceId=$common_data['TraceId'];
      $IsPackageFare=$common_data['IsPackageFare'];
		$IsPackageDetailsMandatory=$common_data['IsPackageDetailsMandatory'];
      $TTS_Invoice_Amount=$common_data['TTS_Invoice_Amount'];
      $booking_lastid=$saveInfo['booking_ref_no'];
      $acc_ref_number=$saveInfo['acc_ref_number'];
      $payment_status=$saveInfo['payment_status'];
      $HotelRoomsDetails=array();
      if(is_array($input['HotelRoomsDetails'])) {
         if($input['HotelRoomsDetails'])
         {
            foreach($input['HotelRoomsDetails'] as $RoomsIndex)
            {
                  foreach($common_data['BlockRooms'] as $RoomDetails)
                  {
                     if($RoomsIndex['RoomIndex']==$RoomDetails['RoomIndex'])
                     {
                           $RoomDetails['SmokingPreference']=GetSmokingPreference($RoomDetails['SmokingPreference']);
                           $RoomDetails['HotelPassenger']=$RoomsIndex['HotelPassenger'];
                           $HotelRoomsDetails[]=$RoomDetails;
                     }
                  }
            }
         }
      } else {
         api_custom_message(400,'HotelRoomsDetails incorrect format',false);
      }
      $request = array(
                        'EndUserIp'        => $input['UserIp'],
                        'TokenId'          => $this->TokenId,
                        'TraceId'          => $TraceId,
                        'ResultIndex'      => $common_data['ResultIndex'],
                        'HotelCode'        => $input['HotelCode'],
                        'HotelName'        => $input['HotelName'],
                        'GuestNationality' => $common_data['GuestNationality'],
                        'NoOfRooms'        => $input['NoOfRooms'],
                        'ClientReferenceNo'=> 0,
                        'IsVoucherBooking' => true,
                        'HotelRoomsDetails'=> $HotelRoomsDetails,
                        "IsPackageFare"    => $IsPackageFare,
                        "IsPackageDetailsMandatory"=>$IsPackageDetailsMandatory
                     );

      if($IsPackageDetailsMandatory)
      {
         $request['ArrivalTransport']=array(
                                             'ArrivalTransportType'=>'0',
                                             'TransportInfoId'=>'Ab 777',
                                             'Time'=>"2019-05-21T18:18:00"
                                          );	
      }
      $confirmation_no=''; $booking_status='Failed'; $supplier_booking_id='';$is_price_changed=false;
      $TBOHotelModel=new TBOHotelModel();
      $custom_index=array();
      $TTS_Result=array();
      if($payment_status=="Successful"){
      $url ="$this->HotelService_URL1/rest/Book/";

      $response= TBO_Request($url,$request);
      if($response['BookResult']['Error']['ErrorCode']==0)
      {
         $trace_id=$response['BookResult']['TraceId'];
         $ErrorCode=0;
         $ErrorMessage='';
         $BookResult=$response['BookResult'];

         $confirmation_no=$BookResult['ConfirmationNo'];
         $supplier_booking_id=$BookResult['BookingId'];
         $is_price_changed=$BookResult['IsPriceChanged'];
         if($BookResult['ConfirmationNo'])
         {
            $booking_status='Confirmed';
         } 
         if($booking_status=='Confirmed'){
            $Confirmationprefix          =  $TBOHotelModel->getDataRowType("super_admin_website_setting",array(),"hotel_confirmation_counter,hotel_confirmation_prefix,id");
            $BookingConfirmationNumber  =  GenerateConfirmationNumber("Hotel",$Confirmationprefix['hotel_confirmation_prefix'],($Confirmationprefix['hotel_confirmation_counter']+1));
            $TBOHotelModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("hotel_confirmation_counter"=>($Confirmationprefix['hotel_confirmation_counter']+1)));
            $TBOHotelModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $booking_lastid,"service"=>"hotel",'transaction_type'=>"debit",'action_type'=>"booking"],["booking_confirmation_number"=>$BookingConfirmationNumber]);
            }
         $TTS_Result=array(
                              'BookingStatus'=>$booking_status,
                              'InvoiceAmount'=>$TTS_Invoice_Amount,
                              'InvoiceNumber'=>$acc_ref_number,
                              'BookingID'=>$booking_lastid,
                              'ConfirmationNo'=>$confirmation_no,
                              'IsPriceChanged'=>$is_price_changed,
                           );
      } else {
         $trace_id='';
         $ErrorCode=$response['BookResult']['Error']['ErrorCode'];
         $ErrorMessage=$response['BookResult']['Error']['ErrorMessage'];
      }
 /*--------------Start Insert API Logs------------------*/
 $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'Book',strtotime(Time::now($this->GetTimeZone)));
 /*--------------End Insert API Logs--------------------*/
   }
   else{
      $ErrorCode =400;
      $ErrorMessage ="Technical Problem Occured";
   }
       /*------------------ Update Data ----------------------------*/
     

       $book_update_data=array(
                                'confirmation_no'=>$confirmation_no,
                                'booking_status'=>$booking_status,
                                'supplier_booking_id'=>$supplier_booking_id,
                                'is_price_changed'=>$is_price_changed
                             );  
       $TBOHotelModel->updateUserData('hotel_booking_list',['id'=>$booking_lastid],$book_update_data);

       $custom_index['CommonData']=array('TraceId'=>$TraceId,'Supplier'=>'TBO','SupplierBookingId'=>$supplier_booking_id);
     
      $tts_response = array(
                              'UserIp'         => $input['UserIp'],
                              'SearchTokenId'  => $tts_search_token,
                              'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                              'Result'         => $TTS_Result
                           );
      return array('Response'=>$tts_response,'CustomIndex'=>$custom_index);
    }

    public function GenerateVoucher(array $input,array $BookingId,array $userauthdata)
    {

      $tts_search_token=$input['SearchTokenId'];
      $request = array(
                        'EndUserIp'   => $input['UserIp'],
                        'TokenId'     => $this->TokenId,
                        'BookingId'   => $BookingId
                     );
      
      $url ="$this->HotelService_URL1/rest/GenerateVoucher/";
      $response= TBO_Request($url,$request);
      $TBOHotelModel=new TBOHotelModel();
      $TTS_Result=array();

    }

    public function GetBookingDetail(array $input,array $book_data,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
      $request = array(
                        'EndUserIp'   => $input['UserIp'],
                        'TokenId'     => $this->TokenId,
                        'ConfirmationNo'=>$input['ConfirmationNo'],
                        'FirstName'=>$input['FirstName'],
                        'LastName'=>$input['LastName']
                     );

      $url ="$this->HotelService_URL1/rest/GetBookingDetail/";
      $response= TBO_Request($url,$request);
      $TBOHotelModel=new TBOHotelModel();
      $TTS_Result=array();
      if($response['GetBookingDetailResult']['Error']['ErrorCode']==0)
      {
         $trace_id=$response['GetBookingDetailResult']['TraceId'];
         $ErrorCode=0;
         $ErrorMessage='';
         $BookDetails=$response['GetBookingDetailResult'];

         $booking_status='Processing';
         if($BookDetails['ConfirmationNo'])
         {
            $booking_status='Confirmed';
         } 
        
         $update_data=array(
                              'booking_status'=>$booking_status,
                              'confirmation_no'=>$BookDetails['ConfirmationNo'],
                              'supplier_booking_id'=>$BookDetails['BookingId'],
                           );
            
         $TBOHotelModel->updateUserData('hotel_booking_list',['id'=>$book_data['id']],$update_data);

         $TTS_Result=array(
                              'ConfirmationNo'=>$input['ConfirmationNo'],
                              'Message'=>'Data has been updated successfully'
                          );

      } else {
         $trace_id='';
         $ErrorCode=$response['GetBookingDetailResult']['Error']['ErrorCode'];
         $ErrorMessage=$response['GetBookingDetailResult']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetBookingDetail',strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      $tts_response = array(
                              'UserIp'         => $input['UserIp'],
                              'SearchTokenId'  => $tts_search_token,
                              'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                              'Result'         => $TTS_Result
                           );
      return $tts_response;
    
    }

    public function SendChangeRequest(array $input,$BookingId,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
      $TBOHotelModel=new TBOHotelModel();

      $ErrorCode=0;
      $ErrorMessage='';
      $TTS_Result=array();

      /*--------If Check Cancel Already Exist -------------*/
      $CancelData=$TBOHotelModel->check_cancel_data($userauthdata['web_partner_id'],$input['BookingId']);
      if($CancelData)
      {
         $TTS_Result=array(
                              'CancelRequestId'=>(int) $CancelData['id'],
                              'CancelRequestStatus'=>$CancelData['supplier_cancel_status'],
                              'CancelRemarks'=>$CancelData['response_remark']
                          );
      } else {

               /*--------Cancel Request Offline -------------*/
               if(isset($input['Offline']))
               {
                     $CancelRequestStatus='InProgress';
                     $save_cancel_data=array(
                                                'web_partner_id'=>$userauthdata['web_partner_id'],
                                                'hotel_booking_id'=>$input['BookingId'],
                                                'user_ip'=>$input['UserIp'],
                                                'remarks'=>$input['Remarks'],
                                                'tts_search_token'=>$tts_search_token,
                                                'supplier_cancel_id'=>'',
                                                'supplier_cancel_status'=>$CancelRequestStatus,
                                                'api_supplier'=>'Offline'
                                          );
         
                     $cancel_list_lastid=$TBOHotelModel->insertData('hotel_cancellation',$save_cancel_data);
         
                     $TTS_Result=array(
                                          'CancelRequestId'=>(int) $cancel_list_lastid,
                                          'CancelRequestStatus'=>$CancelRequestStatus,
                                          'CancelRemarks'=>null
                                    );

               } else {
                        /*--------Cancel Request Online -------------*/
                        $request = array(
                                          'EndUserIp'   => $input['UserIp'],
                                          'TokenId'     => $this->TokenId,
                                          'BookingId'   => $BookingId,
                                          'RequestType' => 4,
                                          'Remarks'     => $input['Remarks']
                                       );
                        
                        $url ="$this->HotelService_URL1/rest/SendChangeRequest/";
                        $response= TBO_Request($url,$request);
                        $TBOHotelModel=new TBOHotelModel();
                     
                        if($response['HotelChangeRequestResult']['Error']['ErrorCode']==0)
                        {
                           $trace_id=$response['HotelChangeRequestResult']['TraceId'];
                           $ErrorCode=0;
                           $ErrorMessage='';
                           $CancelRequestId=$response['HotelChangeRequestResult']['ChangeRequestId'];
                           $CancelRequestStatus=GetCancelStatus($response['HotelChangeRequestResult']['ChangeRequestStatus']);

                           $save_cancel_data=array(
                                                      'web_partner_id'=>$userauthdata['web_partner_id'],
                                                      'hotel_booking_id'=>$input['BookingId'],
                                                      'user_ip'=>$input['UserIp'],
                                                      'remarks'=>$input['Remarks'],
                                                      'tts_search_token'=>$tts_search_token,
                                                      'supplier_cancel_id'=>$CancelRequestId,
                                                      'supplier_cancel_status'=>$CancelRequestStatus,
                                                      'api_supplier'=>'TBO'
                                                );

                           $cancel_list_lastid=$TBOHotelModel->insertData('hotel_cancellation',$save_cancel_data);

                           $TTS_Result=array(
                                                'CancelRequestId'=>(int) $cancel_list_lastid,
                                                'CancelRequestStatus'=>$CancelRequestStatus,
                                                'CancelRemarks'=>''
                                          );

                        } else {
                           $trace_id='';
                           $ErrorCode=$response['HotelChangeRequestResult']['Error']['ErrorCode'];
                           $ErrorMessage=$response['HotelChangeRequestResult']['Error']['ErrorMessage'];
                        }

                        /*--------------Start Insert API Logs------------------*/
                        $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'SendChangeRequest',strtotime(Time::now($this->GetTimeZone)));
                        /*--------------End Insert API Logs--------------------*/
                  }
       }

      $tts_response = array(
                              'UserIp'         => $input['UserIp'],
                              'SearchTokenId'  => $tts_search_token,
                              'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                              'Result'         => $TTS_Result
                           );
      return $tts_response;
    }

    public function GetChangeRequestStatus(array $input,$ChangeRequestId,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
   
      $request = array(
                        'EndUserIp'         => $input['UserIp'],
                        'TokenId'           => $this->TokenId,
                        'ChangeRequestId'   => $ChangeRequestId
                     );
      $url ="$this->HotelService_URL1/rest/GetChangeRequestStatus/";
      $response= TBO_Request($url,$request);
      $TBOHotelModel=new TBOHotelModel();
      $TTS_Result=array();
      if($response['HotelChangeRequestStatusResult']['Error']['ErrorCode']==0)
      {
         $trace_id=$response['HotelChangeRequestStatusResult']['TraceId'];
         $ErrorCode=0;
         $ErrorMessage='';
         $TTS_Result=array(
                              'CancellationChargeBreakUp'=>$response['HotelChangeRequestStatusResult']['CancellationChargeBreakUp'],
                              'TotalServiceCharge'=>$response['HotelChangeRequestStatusResult']['TotalServiceCharge'],
                              'ChangeRequestId'=>$input['CancelRequestId'],
                              'ChangeRequestStatus'=>GetCancelStatus($response['HotelChangeRequestStatusResult']['ChangeRequestStatus'])
                          );

      } else {

         $trace_id='';
         $ErrorCode=$response['HotelChangeRequestStatusResult']['Error']['ErrorCode'];
         $ErrorMessage=$response['HotelChangeRequestStatusResult']['Error']['ErrorMessage'];

      }

       /*--------------Start Insert API Logs------------------*/
       $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetChangeRequestStatus',strtotime(Time::now($this->GetTimeZone)));
       /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
                              'UserIp'         => $input['UserIp'],
                              'SearchTokenId'  => $tts_search_token,
                              'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                              'Result'         => $TTS_Result
                           );
      return $tts_response;

    }



}

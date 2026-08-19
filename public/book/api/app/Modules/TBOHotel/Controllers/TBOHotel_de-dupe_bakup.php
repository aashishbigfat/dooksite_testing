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
      $credential=$apiconfig->tbo_credential;
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
      $db_token_id=$TBOHotelModel->fetch_auth_token(strtotime(Time::today()));
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
                              'id'       => 1,
                              'token_id' => $token_id,
                              'api_mode' => $this->Mode,
                              'request'  => json_encode($requestdata),
                              'response' => json_encode($response),
                              'created'  => strtotime(Time::today())
                           );
            $TBOHotelModel->insert_update_data('tbo_auth_token',$insertlog);
            /*--------------End Insert API Logs------------------*/
      } else {
            $token_id=$db_token_id['token_id'];
      }
      return $token_id;
    }

    public function GetHotelResult(array $input,$tts_search_token,array $userauthdata)
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
            
            $request = array(
                                 'CheckInDate'       => $CheckInDate,
                                 'NoOfNights'        => $input['NoOfNights'],
                                 'CountryCode'       => $input['CountryCode'],
                                 'CityId'            => $input['DestinationCityId'],
                                 'IsTBOMapped'       => 'true',
                                 'ResultCount'       => $input['ResultCount'],
                                 'PreferredCurrency' => 'INR',
                                 'GuestNationality'  => $input['GuestNationality'],
                                 'NoOfRooms'         => $input['NoOfRooms'],
                                 'MaxRating'         => $input['MaxRating'],
                                 'MinRating'         => $input['MinRating'],
                                 'ReviewScore'       => 0,
                                 'IsNearBySearchAllowed'=>false,
                                 'EndUserIp'         => $input['UserIp'],
                                 'TokenId'           => $this->TokenId,
                                 'RoomGuests'        => $RoomGuests
                           );

            $url ="$this->HotelService_URL/rest/GetHotelResult/";
            $response= TBO_Request($url,$request);

            $TBOHotelModel=new TBOHotelModel();
            $custom_index=array();
            $TTS_Result=array();
           

            if($response['HotelSearchResult']['Error']['ErrorCode']==0)
            {  
               $trace_id=$response['HotelSearchResult']['TraceId'];
               $ErrorCode=0;
               $ErrorMessage='';
         
               $SupplierHotelCodesArray=array();
               $HotelResults=$response['HotelSearchResult']['HotelResults'];
               if($HotelResults)
               {
                  if($input['CountryCode']=='IN')
                  {
                     $region_type='domestic';
                  } else {
                     $region_type='international';
                  }
               
                  $super_admin_markup=$TBOHotelModel->super_admin_markup($userauthdata['web_partner_class_id'],$region_type);
                  $super_admin_discount=$TBOHotelModel->super_admin_discount($userauthdata['web_partner_class_id'],$region_type);
                  $super_admin_gst_state_code=$TBOHotelModel->super_admin_gst_state_code()['gst_state_code'];
                  $star_rating_array=array();
                  if($super_admin_markup)
                  {
                     $star_rating_array =array_column($super_admin_markup, 'star_rating');
                  } 
                  
                  foreach($HotelResults as $list)
                  {
                     if(isset($list['SupplierHotelCodes']))
                     {
                        $supplier_key=$list['ResultIndex'].'_'.$list['HotelCode'];
                        $SupplierHotelCodesArray[$supplier_key]=$list['SupplierHotelCodes'];
                     }

                     $admin_markup=get_markup_value($super_admin_markup,$star_rating_array,$list['StarRating'],$request);
                     $HotelPrice=get_hotel_fare($admin_markup,$super_admin_discount,$list['Price'],$userauthdata,$super_admin_gst_state_code);
            
                     $ResultIndex=$list['ResultIndex'];              
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
                                        
                  }
                  $custom_index['CommonData']=array('TraceId'=>$trace_id,'Supplier'=>'TBO','SupplierHotelCodes'=>$SupplierHotelCodesArray,'RegionType'=>$region_type,'Request'=>$request);
               }
            } else {
               $trace_id='';
               $ErrorCode=$response['HotelSearchResult']['Error']['ErrorCode'];
               $ErrorMessage=$response['HotelSearchResult']['Error']['ErrorMessage'];
            }


            /*--------------Start Insert API Logs------------------*/
            $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetHotelResult',strtotime(Time::now()));
            /*--------------End Insert API Logs--------------------*/
            $tts_response = array(
                                    'UserIp'         => $input['UserIp'],
                                    'SearchTokenId'  => $tts_search_token,
                                    'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                    'Result'         => $TTS_Result
                                 );
            return array('Response'=>$tts_response,'CustomIndex'=>$custom_index); 
    }

    public function GetHotelInfo(array $input,array $common_data,array $userauthdata)
    {
         $tts_search_token=$input['SearchTokenId'];
         $TraceId=$common_data['TraceId'];
         $supplier_key=$input['ResultIndex'].'_'.$input['HotelCode'];
         $Finalresponse=array();
         if(array_key_exists($supplier_key,$common_data['SupplierHotelCodes']))
         {
            $supplierlist=$common_data['SupplierHotelCodes'][$supplier_key];
            foreach($supplierlist as $list)
            {
               $request[] = array(
                                    'EndUserIp'    => $input['UserIp'],
                                    'TokenId'      => $this->TokenId,
                                    'TraceId'      => $TraceId,
                                    'ResultIndex'  => $input['ResultIndex'],
                                    'HotelCode'    => $input['HotelCode'],
                                    'CategoryId'   => $list['CategoryId'],
                              );
            }
            $url ="$this->HotelService_URL/rest/GetHotelInfo/";
            $response= TBO_MultiCurl_Request($url,$request);
           // pr($response);
            $Finalresponse=json_decode($response[0],true);
         } else {
            $request=array(
                                    'EndUserIp'    => $input['UserIp'],
                                    'TokenId'      => $this->TokenId,
                                    'TraceId'      => $TraceId,
                                    'ResultIndex'  => $input['ResultIndex'],
                                    'HotelCode'    => $input['HotelCode']
                           );

            $url ="$this->HotelService_URL/rest/GetHotelInfo/";
            $response= TBO_Request($url,$request);
            $Finalresponse=$response;
         }
         $TBOHotelModel=new TBOHotelModel();
         $TTS_Result=array();
         if($Finalresponse['HotelInfoResult']['Error']['ErrorCode']==0)
         {  
            $trace_id=$Finalresponse['HotelInfoResult']['TraceId'];
            $ErrorCode=0;
            $ErrorMessage='';
      
            $HotelDetails=$Finalresponse['HotelInfoResult']['HotelDetails'];
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
         } else {
            $trace_id='';
            $ErrorCode=$Finalresponse['HotelInfoResult']['Error']['ErrorCode'];
            $ErrorMessage=$Finalresponse['HotelInfoResult']['Error']['ErrorMessage'];
         }
          /*--------------Start Insert API Logs------------------*/
          $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetHotelInfo',strtotime(Time::now()));
          /*--------------End Insert API Logs--------------------*/
          $tts_response = array(
                                  'UserIp'         => $input['UserIp'],
                                  'SearchTokenId'  => $tts_search_token,
                                  'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                  'Result'         => $TTS_Result
                               );
          return $tts_response;
    }

    public function GetHotelRoom(array $input,array $common_data,array $userauthdata)
    {

         $tts_search_token=$input['SearchTokenId'];
         $TraceId=$common_data['TraceId'];
         $region_type=$common_data['RegionType'];
         $supplier_key=$input['ResultIndex'].'_'.$input['HotelCode'];

         $Finalresponse=array();
         if(array_key_exists($supplier_key,$common_data['SupplierHotelCodes']))
         {
            $supplierlist=$common_data['SupplierHotelCodes'][$supplier_key];
            unset($supplierlist['StarRating']);
            foreach($supplierlist as $list)
            {
               $request[] = array(
                                    'EndUserIp'    => $input['UserIp'],
                                    'TokenId'      => $this->TokenId,
                                    'TraceId'      => $TraceId,
                                    'ResultIndex'  => $input['ResultIndex'],
                                    'HotelCode'    => $input['HotelCode'],
                                    'CategoryId'   => $list['CategoryId'],
                              );
            }
            $url ="$this->HotelService_URL/rest/GetHotelRoom/";
            $response= TBO_MultiCurl_Request($url,$request);
           // pr($response);
            $Finalresponse=json_decode($response[0],true);
         } else {
            $request=array(
                                    'EndUserIp'    => $input['UserIp'],
                                    'TokenId'      => $this->TokenId,
                                    'TraceId'      => $TraceId,
                                    'ResultIndex'  => $input['ResultIndex'],
                                    'HotelCode'    => $input['HotelCode']
                           );

            $url ="$this->HotelService_URL/rest/GetHotelRoom/";
            $response= TBO_Request($url,$request);
            $Finalresponse=$response;
         }
         $TBOHotelModel=new TBOHotelModel();
         $TTS_Result=array();
         if($Finalresponse['GetHotelRoomResult']['Error']['ErrorCode']==0)
         {  
            $trace_id=$Finalresponse['GetHotelRoomResult']['TraceId'];
            $ErrorCode=0;
            $ErrorMessage='';
      
            $RoomDetails=$Finalresponse['GetHotelRoomResult'];

            if($RoomDetails['RoomCombinations'])
            {
               $super_admin_markup=$TBOHotelModel->super_admin_markup($userauthdata['web_partner_class_id'],$region_type);
               $super_admin_discount=$TBOHotelModel->super_admin_discount($userauthdata['web_partner_class_id'],$region_type);
               $super_admin_gst_state_code=$TBOHotelModel->super_admin_gst_state_code()['gst_state_code'];
               $star_rating_array=array();
               if($super_admin_markup)
               {
                  $star_rating_array =array_column($super_admin_markup, 'star_rating');
               } 

               foreach($RoomDetails['RoomCombinations'] as $Room)
               {

               }
            }

            $HotelRoomsDetails=array();
            $TTS_Result=array(
                              'IsUnderCancellationAllowed'=>$RoomDetails['IsUnderCancellationAllowed'],
                              'IsPolicyPerStay'=>$RoomDetails['IsPolicyPerStay'],
                              'HotelRoomsDetails'=>$HotelRoomsDetails,
                              'RoomCombinations'=>$RoomDetails['RoomCombinations'],
                             );
         } else {
            $trace_id='';
            $ErrorCode=$Finalresponse['GetHotelRoomResult']['Error']['ErrorCode'];
            $ErrorMessage=$Finalresponse['GetHotelRoomResult']['Error']['ErrorMessage'];
         }
          /*--------------Start Insert API Logs------------------*/
          $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'],$tts_search_token,$request,$response,$trace_id,'GetHotelRoom',strtotime(Time::now()));
          /*--------------End Insert API Logs--------------------*/
          $tts_response = array(
                                  'UserIp'         => $input['UserIp'],
                                  'SearchTokenId'  => $tts_search_token,
                                  'Error'          => array("ErrorCode" =>$ErrorCode, "ErrorMessage" =>$ErrorMessage),
                                  'Result'         => $TTS_Result
                               );
          return $tts_response;
       
    }

    public function BlockRoom(array $input,array $common_data,array $userauthdata)
    {
         $tts_search_token=$input['SearchTokenId'];
         $TraceId=$common_data['TraceId'];
         $request = array(
                           'EndUserIp'        => $input['UserIp'],
                           'TokenId'          => $this->TokenId,
                           'TraceId'          => $TraceId,
                           'CategoryId'       => $input['CategoryId'],
                           'ResultIndex'      => $input['ResultIndex'],
                           'HotelCode'        => $input['HotelCode'],
                           'HotelName'        => $input['HotelName'],
                           'GuestNationality' => $input['GuestNationality'],
                           'NoOfRooms'        => $input['NoOfRooms'],
                           'ClientReferenceNo'=> 0,
                           'IsVoucherBooking' => true,
                           'HotelRoomsDetails'=> $input['HotelRoomsDetails']
                        );

         //return $request;
         $url ="$this->HotelService_URL/rest/BlockRoom/";
         return $response= TBO_Request($url,$request);
    }

    public function Book(array $input,$tts_search_token,array $userauthdata)
    {
       
    }

    public function GetBookingDetail(array $input,$tts_search_token,array $userauthdata)
    {
       
    }

    public function SendChangeRequest(array $input,$tts_search_token,array $userauthdata)
    {
       
    }



}

<?php

namespace Modules\CRSHotel\Controllers;

use App\Controllers\BaseController;
use App\Modules\CRSHotel\Models\CRSHotelModel;
use Modules\CRSHotel\Config\Validation;
use CodeIgniter\I18n\Time;

class CRSHotel extends BaseController
{

  public function __construct()
  {
    helper('Modules\CRSHotel\Helpers\crs_hotel');
    $this->LastCancellationDay = 1;
  }
  public function Search(array $input,$tts_search_token,array $userauthdata)
    {
        $SearhData = array('Supplier'=>'CRS','Request'=>json_encode($input));
        return  $SearhData;
   }
   public function ConvertSearchResponse($input,$response,$convert_response,$custom_index,$common_data)
   {
    $super_admin_markup=$common_data['super_admin_markup'];
    $super_admin_discount=$common_data['super_admin_discount'];
    $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
    $star_rating_array=array();
    $userauthdata=$common_data['userauthdata'];
    $CRSHotelModel =  new CRSHotelModel();
    $AvailableHotels  =  $CRSHotelModel->checkHotelAvailiblity($input, $userauthdata['web_partner_id']);
    if ($AvailableHotels) {
      $propertyType  =  $CRSHotelModel->getPropertyType($userauthdata['web_partner_id']);
      $hotelAmenity  =  $CRSHotelModel->getHotelRoomAmenity($userauthdata['web_partner_id'], "hotel");
      $amenityName =  $hotelAmenity['amenityName'];
      $amenityIcon =    $hotelAmenity['amenityIcon'];
      $checkIn =  $input['CheckInDate'];
      $checkOut =  $input['CheckOutDate'];
      $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
      $Hotels  =  array();
      if ($input['CountryCode'] == 'IN') {
        $region_type = 'domestic';
      } else {
        $region_type = 'international';
      }
      $star_rating_array = array();
      if ($super_admin_discount) {
        $super_admin_discount = array_filter($super_admin_discount, function ($value) {
          return ($value["supplier"] == "CRS");
        });
        $super_admin_discount = reset($super_admin_discount);
      }
      $selectedMarkupDataInfo  =  array();
      $CRSCommonHotelData  =  array();
      
      foreach ($AvailableHotels as $key => $AvailableHotel) {
        $HotelPropertType  =  isset($propertyType[$AvailableHotel['hotel_property_type_id']]) ? $propertyType[$AvailableHotel['hotel_property_type_id']] : "";
        $getHotelAmenity =  explode(",", $AvailableHotel['hotel_amenities']);
        $hotelamenities  =  array();
        if ($getHotelAmenity) {
          foreach ($getHotelAmenity as $hotelAmenity) {
            if (isset($amenityName[$hotelAmenity])) {
              $hotelamenities[] =  array("Name" => isset($amenityName[$hotelAmenity]) ? $amenityName[$hotelAmenity] : "", "Icon" => isset($amenityIcon[$hotelAmenity]) ? root_url . 'uploads/hotel/' . $amenityIcon[$hotelAmenity] : "");
            }
          }
        }
        $ResultIndex =  "CRS_" . $AvailableHotel['id'];
        $RooMPrice              = getCalculateMinHotelPrice($AvailableHotel['roomPriceData'], $input, $allStayDates, "HotelPrice");
        $admin_markup_filter_Data = get_markup_value("CRS",$super_admin_markup, $star_rating_array, $AvailableHotel['hotel_star_rating'], $input['NoOfNights'], $input['NoOfRooms'],$selectedMarkupDataInfo);
        $admin_markup =   $admin_markup_filter_Data['markup_data'];
        $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
        $price     =  get_crs_hotel_fare($admin_markup, $super_admin_discount, $RooMPrice, $userauthdata, $super_admin_gst_state_code);
        $AvailableHotel['RoomPrice'] =  $RooMPrice;
        $Hotels =   array(
          "IsHotDeal" => false,
          "IsCRSDeal" => true,
          "ResultIndex" => $ResultIndex,
          "HotelCode" => $AvailableHotel['id'],
          "HotelName" =>  $AvailableHotel["hotel_name"],
          "HotelCategory" => "",
          "StarRating" => intval($AvailableHotel["hotel_star_rating"]),
          "HotelDescription" => strip_tags($AvailableHotel["hotel_description"]),
          "HotelPromotion" => strip_tags($AvailableHotel["hotel_promotion"]),
          "HotelPolicy" => "",
          "HotelPropertType" => $HotelPropertType != "" ? $HotelPropertType : "",
          "HotelAmenities" => $hotelamenities,
          "HotelReviewRatings" => $AvailableHotel['review_rating'] != "" ? $AvailableHotel['review_rating'] : "",
          "HotelReviewUrl" => $AvailableHotel['review_url'] != "" ? $AvailableHotel['review_url'] : "",
          "Price" => $price,
          "HotelPicture" => $AvailableHotel['hotel_images'] != "" ? root_url . 'uploads/hotel/thumbnail/' . $AvailableHotel['hotel_images'] : "",
          "HotelAddress" =>  $AvailableHotel["address"] . "," . $AvailableHotel["city_name"] . "," . $AvailableHotel["state"] . "," . $AvailableHotel["country_name"] . "," . $AvailableHotel["postal_code"],
          "HotelContactNo" => "",
          "HotelMap" => null,
          "Latitude" => $AvailableHotel["latitude"],
          "Longitude" => $AvailableHotel["longitude"],
          "HotelLocation" => $AvailableHotel["location_area"]
        );
        array_push($convert_response,$Hotels) ;
        $custom_index[$ResultIndex] =  array("HotelId" => $AvailableHotel['id'], "Supplier" => "CRS", "StarRating" => intval($AvailableHotel["hotel_star_rating"]));
      }
    } 
    return array('convert_response'=>$convert_response,'custom_index'=>$custom_index);
   }
  public function GetHotelResult(array $input, $tts_search_token, array $userauthdata, $super_admin_markup, $super_admin_discount, $super_admin_gst_state_code)
  {
    $CRSHotelModel =  new CRSHotelModel();
    $AvailableHotels  =  $CRSHotelModel->checkHotelAvailiblity($input, $userauthdata['web_partner_id']);
    if ($AvailableHotels) {
      $propertyType  =  $CRSHotelModel->getPropertyType($userauthdata['web_partner_id']);
      $hotelAmenity  =  $CRSHotelModel->getHotelRoomAmenity($userauthdata['web_partner_id'], "hotel");
      $amenityName =  $hotelAmenity['amenityName'];
      $amenityIcon =    $hotelAmenity['amenityIcon'];
      $checkIn =  $input['CheckInDate'];
      $checkOut =  $input['CheckOutDate'];
      $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
      $Hotels  =  array();
      if ($input['CountryCode'] == 'IN') {
        $region_type = 'domestic';
      } else {
        $region_type = 'international';
      }
      $star_rating_array = array();
      if ($super_admin_discount) {
        $super_admin_discount = array_filter($super_admin_discount, function ($value) {
          return ($value["supplier"] == "CRS");
        });
        $super_admin_discount = reset($super_admin_discount);
      }
      $selectedMarkupDataInfo  =  array();
      $CRSCommonHotelData  =  array();
      $CRSCustomIndex  =  array();
      foreach ($AvailableHotels as $key => $AvailableHotel) {
        $HotelPropertType  =  isset($propertyType[$AvailableHotel['hotel_property_type_id']]) ? $propertyType[$AvailableHotel['hotel_property_type_id']] : "";
        $getHotelAmenity =  explode(",", $AvailableHotel['hotel_amenities']);
        $hotelamenities  =  array();
        if ($getHotelAmenity) {
          foreach ($getHotelAmenity as $hotelAmenity) {
            if (isset($amenityName[$hotelAmenity])) {
              $hotelamenities[] =  array("Name" => isset($amenityName[$hotelAmenity]) ? $amenityName[$hotelAmenity] : "", "Icon" => isset($amenityIcon[$hotelAmenity]) ? root_url . 'uploads/hotel/' . $amenityIcon[$hotelAmenity] : "");
            }
          }
        }
        $ResultIndex =  "CRS_" . $AvailableHotel['id'];
        $RooMPrice              = getCalculateMinHotelPrice($AvailableHotel['roomPriceData'], $input, $allStayDates, "HotelPrice");
        $admin_markup_filter_Data = get_markup_value("CRS",$super_admin_markup, $star_rating_array, $AvailableHotel['hotel_star_rating'], $input['NoOfNights'], $input['NoOfRooms'],$selectedMarkupDataInfo);
        $admin_markup =   $admin_markup_filter_Data['markup_data'];
        $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
        $price     =  get_crs_hotel_fare($admin_markup, $super_admin_discount, $RooMPrice, $userauthdata, $super_admin_gst_state_code);
        $AvailableHotel['RoomPrice'] =  $RooMPrice;
        $Hotels[$key] =   array(
          "IsHotDeal" => false,
          "IsCRSDeal" => true,
          "ResultIndex" => $ResultIndex,
          "HotelCode" => $AvailableHotel['id'],
          "HotelName" =>  $AvailableHotel["hotel_name"],
          "HotelCategory" => "",
          "StarRating" => intval($AvailableHotel["hotel_star_rating"]),
          "HotelDescription" => strip_tags($AvailableHotel["hotel_description"]),
          "HotelPromotion" => strip_tags($AvailableHotel["hotel_promotion"]),
          "HotelPolicy" => "",
          "HotelPropertType" => $HotelPropertType != "" ? $HotelPropertType : "",
          "HotelAmenities" => $hotelamenities,
          "HotelReviewRatings" => $AvailableHotel['review_rating'] != "" ? $AvailableHotel['review_rating'] : "",
          "HotelReviewUrl" => $AvailableHotel['review_url'] != "" ? $AvailableHotel['review_url'] : "",
          "Price" => $price,
          "HotelPicture" => $AvailableHotel['hotel_images'] != "" ? root_url . 'uploads/hotel/thumbnail/' . $AvailableHotel['hotel_images'] : "",
          "HotelAddress" =>  $AvailableHotel["address"] . "," . $AvailableHotel["city_name"] . "," . $AvailableHotel["state"] . "," . $AvailableHotel["country_name"] . "," . $AvailableHotel["postal_code"],
          "HotelContactNo" => "",
          "HotelMap" => null,
          "Latitude" => $AvailableHotel["latitude"],
          "Longitude" => $AvailableHotel["longitude"],
          "HotelLocation" => $AvailableHotel["location_area"]
        );
        $CRSCustomIndex[$ResultIndex] =  array("HotelId" => $AvailableHotel['id'], "Supplier" => "CRS", "StarRating" => intval($AvailableHotel["hotel_star_rating"]));
      }
      $CRSCommonHotelData = array('RegionType' => $region_type, 'NoOfNights' => $input['NoOfNights'], 'NoOfRooms' => $input['NoOfRooms'], 'GuestNationality' => $input['GuestNationality'], "CustomIndex" => $CRSCustomIndex);
      $result =   array("Result" => $Hotels, "CommonHotelData" => $CRSCommonHotelData);
      return $result;
    } else {
      return array();
    }
  }
  public function GetHotelInfo(array $input, array $common_data, array $userauthdata)
  {
    $tts_search_token = $input['SearchTokenId'];
    $CRSHotelModel =  new CRSHotelModel();
    $HotelInfo  =  $CRSHotelModel->getHotelInfo($common_data['CustomIndex']['HotelId'], $userauthdata['web_partner_id']);
    $TTS_Result =  array();
    if ($HotelInfo) {
      $propertyType  =  $CRSHotelModel->getPropertyType($userauthdata['web_partner_id']);
      $hotelAmenity  =  $CRSHotelModel->getHotelRoomAmenity($userauthdata['web_partner_id'], "hotel");
      $ErrorCode = 0;
      $ErrorMessage = '';
      $HotelPolicy = "";
      if ($HotelInfo['check_in_time'] != "") {
        $HotelPolicy =  $HotelPolicy . "Check in After " . $HotelInfo['check_in_time'];
      }
      if ($HotelInfo['check_in_time'] != "") {
        $HotelPolicy =  $HotelPolicy . "|Check out Before " . $HotelInfo['check_out_time'];
      }
      $HotelPropertType  =  isset($propertyType[$HotelInfo['hotel_property_type_id']]) ? $propertyType[$HotelInfo['hotel_property_type_id']] : "";
      $getHotelAmenity =  explode(",", $HotelInfo['hotel_amenities']);
      $hotelamenities  =  array();
      $amenityName =  $hotelAmenity['amenityName'];
      $amenityIcon =    $hotelAmenity['amenityIcon'];
      if ($getHotelAmenity) {
        foreach ($getHotelAmenity as $hotelAmenity) {
          if (isset($amenityName[$hotelAmenity])) {
            $hotelamenities[] =  array("Name" => isset($amenityName[$hotelAmenity]) ? $amenityName[$hotelAmenity] : "", "Icon" => isset($amenityIcon[$hotelAmenity]) ? root_url . 'uploads/hotel/' . $amenityIcon[$hotelAmenity] : "");
          }
        }
      }
      $image        = $HotelInfo['hotel_images'] != "" ? root_url . 'uploads/hotel/thumbnail/' . $HotelInfo['hotel_images'] : "";
      $TTS_Result = array(
        'HotelName' => $HotelInfo['hotel_name'],
        'StarRating' => intval($HotelInfo["hotel_star_rating"]),
        'HotelURL' => null,
        'Description' =>  strip_tags($HotelInfo["hotel_description"]),
        'Attractions' => null,
        "HotelPromotion" => strip_tags($HotelInfo["hotel_promotion"]),
        'HotelFacilities' => array(),
        'HotelPolicy' => $HotelPolicy != "" ? $HotelPolicy : null,
        'SpecialInstructions' => null,
        'HotelPicture' => null,
        'Images' => $image != "" ? array($image) : array(),
        "Address" =>  $HotelInfo["address"] . "," . $HotelInfo["city_name"] . "," . $HotelInfo["state"] . " , " . $HotelInfo["country_name"] . "," . $HotelInfo["postal_code"],
        'CountryName' =>  $HotelInfo["country_name"],
        'PinCode' => $HotelInfo["postal_code"],
        'HotelContactNo' => null,
        "HotelPropertType" => $HotelPropertType != "" ? $HotelPropertType : null,
        "HotelAmenities" => $hotelamenities,
        "HotelReviewRatings" => $HotelInfo['review_rating'] != "" ? $HotelInfo['review_rating'] : null,
        "HotelReviewUrl" => $HotelInfo['review_url'] != "" ? $HotelInfo['review_url'] : null,
        'FaxNumber' => null,
        'Email' => null,
        'Latitude' => $HotelInfo['latitude'],
        'Longitude' => $HotelInfo['longitude'],
        'RoomData' => null,
        'RoomFacilities' => null,
        'Services' => null
      );

      $custom_index['CommonData'] = array('Supplier' => 'CRS', 'RegionType' => $common_data['RegionType'], 'NoOfNights' => $common_data['NoOfNights'], 'NoOfRooms' => $common_data['NoOfRooms'], 'StarRating' => $HotelInfo['hotel_star_rating'], 'GuestNationality' => $common_data['GuestNationality'], "HotelId" => $common_data['CustomIndex']['HotelId']);
    } else {

      $ErrorCode = 5;
      $ErrorMessage = "Not Detail Found";
    }

    $tts_response = array(
      'UserIp'         => $input['UserIp'],
      'SearchTokenId'  => $tts_search_token,
      'Error'          => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
      'Result'         => $TTS_Result
    );
    return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
  }
  public function GetHotelRoom(array $input,array $common_data,array $userauthdata)
  {
    $super_admin_markup=$common_data['super_admin_markup'];
    $super_admin_discount=$common_data['super_admin_discount'];
    $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
    $common_data = array('Supplier' => 'CRS', 'RegionType' => $common_data['RegionType'], 'NoOfNights' => $common_data['NoOfNights'], 'NoOfRooms' => $common_data['NoOfRooms'], 'StarRating' => $common_data['CustomIndex']['StarRating'], 'GuestNationality' => $common_data['GuestNationality'], "HotelId" => $common_data['CustomIndex']['HotelId']);
$selectedMarkupDataInfo = array();
    $tts_search_token = $input['SearchTokenId'];
    $star_rating = $common_data['StarRating'];
    $no_of_night = $common_data['NoOfNights'];
    $no_of_rooms = $common_data['NoOfRooms'];
   
    $custom_index = array();
    $CRSHotelModel =  new CRSHotelModel();
    $searchData  =   $CRSHotelModel->getSearchData(array("tts_search_token" => $tts_search_token), "request");
    $searchRequest  = json_decode($searchData['request'], true);
    $searchRequest['HotelId'] =  $common_data['HotelId'];
    $AvailableRoom  =   $CRSHotelModel->getHotelRooms($searchRequest, $userauthdata['web_partner_id']);

    if ($AvailableRoom) {
      $roomAmenity  =  $CRSHotelModel->getHotelRoomAmenity($userauthdata['web_partner_id'], "room");
      $amenityName =  $roomAmenity['amenityName'];
      $amenityIcon =    $roomAmenity['amenityIcon'];
      $roomIndex   =  1;
      $roomCombination =  array();
      $roomIndexRoomIdPairArray  =  array();
      $checkIn =  $searchRequest['CheckInDate'];
      $checkOut =  $searchRequest['CheckOutDate'];
      $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
      $HotelRoomDetails  =  array();
      $star_rating_array =  array();
      foreach ($AvailableRoom as $rooms) {
        $getRoomAmenity =  explode(",", $rooms['room_amenities']);
        $roomAmenities  =  array();
        $roomIndexRoomId  = "";
        if ($getRoomAmenity) {
          foreach ($getRoomAmenity as $roomAmenity) {
            if (isset($amenityName[$roomAmenity])) {
              $roomAmenities[] =  array("Name" => isset($amenityName[$roomAmenity]) ? $amenityName[$roomAmenity] : "", "Icon" => isset($amenityIcon[$roomAmenity]) ? root_url . 'uploads/hotel/' . $amenityIcon[$roomAmenity] : "");
            }
          }
        }
        $roomIndexCombination =  array();
        foreach ($searchRequest['RoomGuests'] as $RoomGuests) {
          $lastcancellationDate  =  getDateBeforeAfter(date('Y-m-d'), $this->LastCancellationDay, "sub");
          $lastVoucherDate  =  getDateBeforeAfter($checkIn, $rooms['min_voucher_day'], "sub");
          $RooMPrice        =  getCalculateRoomPrice($rooms['roomPriceData'], $RoomGuests, $allStayDates);
          $admin_markup_filter_Data = get_markup_value("CRS",$super_admin_markup, $star_rating_array, $star_rating, $no_of_night, 1,$selectedMarkupDataInfo);
          $admin_markup =   $admin_markup_filter_Data['markup_data'];
          $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
          $price     =  get_crs_hotel_fare($admin_markup, $super_admin_discount, $RooMPrice, $userauthdata, $super_admin_gst_state_code);
          $HotelRoomDetails[]   =  array(
            "AvailabilityType" => "Confirm",
            "ChildCount" => $RoomGuests['Child'],
            "RequireAllPaxDetails" => true,
            "RoomId" => $rooms['id'],
            "RoomStatus" => 0,
            "RoomIndex" => $roomIndex,
            "RoomTypeCode" => "",
            "RoomDescription" => strip_tags($rooms['room_description']),
            "RoomTypeName" => $rooms['room_title'],
            "RatePlanCode" => $rooms['id'] . "|" . $common_data['HotelId'],
            "RatePlan" => 0,
            "RatePlanName" => "",
            "InfoSource" => "FixedCombination",
            "SequenceNo" => "",
            "IsPerStay" => false,
            "SupplierPrice" => null,
            "Price" => $price,
            "RoomPromotion" => "",
            "Amenities" => array(),
            "RoomAmenities" => $roomAmenities,
            "Amenity" => array(),
            "SmokingPreference" => "NoPreference",
            "BedTypes" => array(),
            "HotelSupplements" => array(),
            "LastCancellationDate" =>  $lastcancellationDate . "T23:59:59",
            "CancellationPolicies" => array(),
            "LastVoucherDate" =>  $lastVoucherDate . "T23:59:59",
            "CancellationPolicy" => $rooms['room_cancellation'] != "" ? strip_tags($rooms['room_cancellation']) : null,
            "Inclusion" => array(),
            "IsPassportMandatory" =>  $rooms['passport_required'] == "1" ? true : false,
            "IsPANMandatory" => $rooms['pan_required'] == "1" ? true : false

          );
          $roomIndexRoomId =   $roomIndexRoomId . $roomIndex;
          array_push($roomIndexCombination, $roomIndex);
          $roomIndex  =  ($roomIndex + 1);
        }
        $roomIndexRoomIdPairArray[$roomIndexRoomId] = $rooms['id'];
        $RoomIndexArray['RoomIndex'] = $roomIndexCombination;
        array_push($roomCombination, $RoomIndexArray);
      }
      $response['IsUnderCancellationAllowed'] = false;
      $response['IsPolicyPerStay'] =  false;
      $response['HotelRoomsDetails'] =  $HotelRoomDetails;
      $response['RoomCombinations'] =  array("InfoSource" => "FixedCombination", "IsPolicyPerStay" => false, "RoomCombination" => $roomCombination);
      $common_data['RoomDetails'] = $response['HotelRoomsDetails'];
      $common_data["roomIndexRoomIdPair"] = $roomIndexRoomIdPairArray;
      $custom_index = $common_data;
      $ErrorCode =  0;
      $ErrorMessage =  "";
    } else {

      $ErrorCode = 5;
      $ErrorMessage = "No Room Details";
    }
    $tts_response = array(
      'UserIp'         => $input['UserIp'],
      'SearchTokenId'  => $tts_search_token,
      'Error'          => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
      'Result'         => $response
    );
    return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
  }
  public function BlockRoom(array $input,array $common_data,array $userauthdata)
  {
    $tts_search_token = $input['SearchTokenId'];
    $star_rating = $common_data['StarRating'];
    $no_of_night = $common_data['NoOfNights'];
    $no_of_rooms = $common_data['NoOfRooms'];
    $HotelRoomsDetails = array();
    $super_admin_markup=$common_data['super_admin_markup'];
    $super_admin_discount=$common_data['super_admin_discount'];
    $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
    $selectedRoomIndex  = "";
    $selectedMarkupDataInfo =  array();
    if (is_array($input['HotelRoomsDetails'])) {
      if ($input['HotelRoomsDetails']) {
        foreach ($input['HotelRoomsDetails'] as $RoomsIndex) {
          foreach ($common_data['RoomDetails'] as $RoomDetails) {
            if ($RoomsIndex['RoomIndex'] == $RoomDetails['RoomIndex']) {
              $selectedRoomIndex = $selectedRoomIndex . $RoomsIndex['RoomIndex'];
              $HotelRoomsDetails[] = $RoomDetails;
            }
          }
        }
      }
    } else {
      api_custom_message(400, 'HotelRoomsDetails incorrect format', false);
    }
    $custom_index = array();
    $TTS_Invoice_Amount = 0;
    $CRSHotelModel =  new CRSHotelModel();
    $searchData  =   $CRSHotelModel->getSearchData(array("tts_search_token" => $tts_search_token), "request");
    $searchRequest  = json_decode($searchData['request'], true);
    $searchRequest['HotelId'] =  $common_data['HotelId'];
    $roomIndexRoomIdPair           =  $common_data['roomIndexRoomIdPair'];
    $RoomId    =  $roomIndexRoomIdPair[$selectedRoomIndex];
    if ($RoomId) {
      $AvailableRoom  =   $CRSHotelModel->getHotelRooms($searchRequest, $userauthdata['web_partner_id'], $RoomId);
      $HotelInfo  =   $CRSHotelModel->getHotelInfo($common_data['HotelId'], $userauthdata['web_partner_id']);
      $image        = $HotelInfo['hotel_images'] != "" ? root_url . 'uploads/hotel/thumbnail/' . $HotelInfo['hotel_images'] : "";
      if ($AvailableRoom) {
        $TTS_Result = array();
        $ErrorCode = 0;
        $ErrorMessage = '';
        $IsPassportMandatory = false;
        $IsPANMandatory = false;
        $webPartnerFarebreakup  =  array();
        $superAdminFarebreakup  =  array();
        $star_rating_array = array();
        $roomAmenity  =  $CRSHotelModel->getHotelRoomAmenity($userauthdata['web_partner_id'], "room");
        $amenityName =  $roomAmenity['amenityName'];
        $amenityIcon =    $roomAmenity['amenityIcon'];
        $checkIn =  $searchRequest['CheckInDate'];
        $checkOut =  $searchRequest['CheckOutDate'];
        $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
        $HotelRoomDetails  =  array();
        foreach ($AvailableRoom as $rooms) {
          $getRoomAmenity =  explode(",", $rooms['room_amenities']);
          $roomAmenities  =  array();
          if ($getRoomAmenity) {
            foreach ($getRoomAmenity as $roomAmenity) {
              if (isset($amenityName[$roomAmenity])) {
                $roomAmenities[] =  array("Name" => isset($amenityName[$roomAmenity]) ? $amenityName[$roomAmenity] : "", "Icon" => isset($amenityIcon[$roomAmenity]) ? root_url . 'uploads/hotel/' . $amenityIcon[$roomAmenity] : "");
              }
            }
          }
          foreach ($searchRequest['RoomGuests'] as $guestkey => $RoomGuests) {
            $lastcancellationDate  =  getDateBeforeAfter(date('Y-m-d'), $this->LastCancellationDay, "sub");
            $lastVoucherDate  =  getDateBeforeAfter($checkIn, $rooms['min_voucher_day'], "sub");
            $RooMPrice        =  getCalculateRoomPrice($rooms['roomPriceData'], $RoomGuests, $allStayDates);
            $admin_markup_filter_Data = get_markup_value("CRS",$super_admin_markup, $star_rating_array, $star_rating, $no_of_night, 1,$selectedMarkupDataInfo);
            $admin_markup =   $admin_markup_filter_Data['markup_data'];
            $selectedMarkupDataInfo =   $admin_markup_filter_Data['selectedMarkupDataInfo'];
            $HotelPriceBreakup     =  get_crs_hotel_fare($admin_markup, $super_admin_discount, $RooMPrice, $userauthdata, $super_admin_gst_state_code,"blockroom");
            $price  = $HotelPriceBreakup['WebPartnerBreakup']; 
            $webPartnerFarebreakup[$guestkey]   =  $price;
            $superAdminFarebreakup[$guestkey]   =  $HotelPriceBreakup['SuperAdminBreakup'];
            $HotelRoomDetails[]   =  array(
              "AvailabilityType" => "Confirm",
              "ChildCount" => $RoomGuests['Child'],
              "RequireAllPaxDetails" => true,
              "RoomId" => $rooms['id'],
              "RoomStatus" => 0,
              "RoomIndex" => $HotelRoomsDetails[$guestkey]['RoomIndex'],
              "InfoSource" => "FixedCombination",
              "RoomTypeCode" => "",
              "RoomDescription" => strip_tags($rooms['room_description']),
              "RoomTypeName" => $rooms['room_title'],
              "RatePlanCode" => $rooms['id'] . "|" . $common_data['HotelId'],
              "RatePlan" => 0,
              "RatePlanName" => "",
              "SequenceNo" => "",
              "IsPerStay" => false,
              "SupplierPrice" => null,
              "Price" => $price,
              "RoomPromotion" => "",
              "Amenities" => array(),
              "RoomAmenities" => $roomAmenities,
              "Amenity" => array(),
              "SmokingPreference" => "NoPreference",
              "BedTypes" => array(),
              "HotelSupplements" => array(),
              "LastCancellationDate" =>  $lastcancellationDate . "T23:59:59",
              "CancellationPolicies" => array(),
              "LastVoucherDate" =>  $lastVoucherDate . "T23:59:59",
              "CancellationPolicy" => $rooms['room_cancellation'] != "" ? strip_tags($rooms['room_cancellation']) : null,
              "Inclusion" => array(),
              "IsPassportMandatory" =>  $rooms['passport_required'] == "1" ? true : false,
              "IsPANMandatory" => $rooms['pan_required'] == "1" ? true : false
            );
            $TTS_Invoice_Amount += round_value($price['OfferedPrice'] + $price['TDS']);
          }
          $IsPassportMandatory = $rooms['passport_required'] == "1" ? true : false;
          $IsPANMandatory = $rooms['pan_required'] == "1" ? true : false;
        }
        $HotelPolicy = "";
        if ($HotelInfo['check_in_time'] != "") {
          $HotelPolicy =  $HotelPolicy . "Check in After " . $HotelInfo['check_in_time'];
        }
        if ($HotelInfo['check_in_time'] != "") {
          $HotelPolicy =  $HotelPolicy . "|Check out Before " . $HotelInfo['check_out_time'];
        }
        $TTS_Result = array(
          "IsCancellationPolicyChanged" => false,
          "IsHotelPolicyChanged" => false,
          "IsPriceChanged" => false,
          "IsPackageFare" => false,
          "IsDepartureDetailsMandatory" => false,
          "IsPackageDetailsMandatory" => false,
          "AvailabilityType" => "Confirm",
          "GSTAllowed" => false,
          'HotelName' => $HotelInfo['hotel_name'],
          'StarRating' => intval($HotelInfo["hotel_star_rating"]),
          'Description' =>  strip_tags($HotelInfo["hotel_description"]),
          "HotelNorms" => $HotelPolicy != "" ? $HotelPolicy : null,
          'HotelPolicyDetail' => $HotelPolicy != "" ? $HotelPolicy : null,
          'HotelPicture' => $image,
          "AddressLine1" =>  $HotelInfo["address"] . "," . $HotelInfo["city_name"] . "," . $HotelInfo["state"] . " , " . $HotelInfo["country_name"] . "," . $HotelInfo["postal_code"],
          "AddressLine2" => "",
          'Latitude' => $HotelInfo['latitude'],
          'Longitude' => $HotelInfo['longitude'],
          "BookingAllowedForRoamer" => false,
          "AncillaryServices" => array(),
          "HotelRoomsDetails" => $HotelRoomDetails,
          "ValidationsAtConfirm" => array(),
        );


        $common_data['BlockRooms'] = $HotelRoomDetails;
        $common_data['IsPassportMandatory'] = $IsPassportMandatory;
        $common_data['IsPANMandatory'] = $IsPANMandatory;
        $common_data['TTS_Invoice_Amount'] = $TTS_Invoice_Amount;
        $common_data['WebPartnerFarebreakup']=$webPartnerFarebreakup;
        $common_data['SuperAdminFarebreakup']=$superAdminFarebreakup;
        $custom_index['CommonData'] = $common_data;
      } else {
        $ErrorCode = 5;
        $ErrorMessage = "Rooms is not available";
      }
    } else {
      $ErrorCode = 5;
      $ErrorMessage = "Rooms is not available";
    }
    $tts_response = array(
      'UserIp'         => $input['UserIp'],
      'SearchTokenId'  => $tts_search_token,
      'Error'          => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
      'Result'         => $TTS_Result
    );
    return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
  }
  public function Book(array $input, $common_data, array $userauthdata, array $saveInfo)
  {
    $tts_search_token = $input['SearchTokenId'];
    $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
    $HotelRoomsDetails = array();
    $selectedRoomIndex  = "";
    $booking_lastid = $saveInfo['booking_ref_no'];
    $acc_ref_number = $saveInfo['acc_ref_number'];
    $payment_status = $saveInfo['payment_status'];
    if (is_array($input['HotelRoomsDetails'])) {
      if ($input['HotelRoomsDetails']) {
        foreach ($input['HotelRoomsDetails'] as $RoomsIndex) {
          if (isset($common_data['BlockRooms'])) {
            foreach ($common_data['BlockRooms'] as $RoomDetails) {
              if ($RoomsIndex['RoomIndex'] == $RoomDetails['RoomIndex']) {
                $selectedRoomIndex = $selectedRoomIndex . $RoomsIndex['RoomIndex'];
                $RoomDetails['HotelPassenger'] = $RoomsIndex['HotelPassenger'];
                $HotelRoomsDetails[] = $RoomDetails;
              }
            }
          }
        }
        $roomIndexRoomIdPair           =  $common_data['roomIndexRoomIdPair'];
        $RoomId    =  $roomIndexRoomIdPair[$selectedRoomIndex];
        if (empty($HotelRoomsDetails) && $RoomId == "") {
          api_custom_message(400, 'HotelRoomsDetails incorrect format', false);
        }
      }
    } else {
      api_custom_message(400, 'HotelRoomsDetails incorrect format', false);
    }
    $CRSHotelModel =  new CRSHotelModel();
    $searchData  =   $CRSHotelModel->getSearchData(array("tts_search_token" => $tts_search_token), "request");
    $searchRequest  = json_decode($searchData['request'], true);
    $searchRequest['HotelId'] =  $common_data['HotelId'];
    $TTS_Result =  array();
    $custom_index =  array();
    if ($payment_status == "Successful") {
      $checkAvaibility          =  $CRSHotelModel->getBookHotelRooms($searchRequest, $userauthdata['web_partner_id'], $RoomId);
      if ($checkAvaibility) {
        $booking_status = 'Hold';
        $CRSHotelModel->UpdateRoomAvailability($searchRequest, $userauthdata['web_partner_id'], $RoomId);
        $TTS_Result = array(
          'BookingStatus' => $booking_status,
          'InvoiceAmount' => $TTS_Invoice_Amount,
          'InvoiceNumber' => $acc_ref_number,
          'BookingID' => $booking_lastid,
          'ConfirmationNo' => '',
          'IsPriceChanged' => false,
        );
        $ErrorCode = 0;
        $ErrorMessage = "";
      } else {
        $booking_status = 'Failed';
        $ErrorCode = 400;
        $ErrorMessage = "Sold out";
      }
    } else {
      $booking_status = 'Failed';
      $ErrorCode = 400;
      $ErrorMessage = "Technical Problem Occured";
    }
    if($booking_status=='Confirmed'){
      $Confirmationprefix =  $CRSHotelModel->getDataRowType("super_admin_website_setting",array(),"hotel_confirmation_counter,hotel_confirmation_prefix,id");
      $BookingConfirmationNumber  =  GenerateConfirmationNumber("Hotel",$Confirmationprefix['hotel_confirmation_prefix'],($Confirmationprefix['hotel_confirmation_counter']+1));
      $CRSHotelModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("hotel_confirmation_counter"=>($Confirmationprefix['hotel_confirmation_counter']+1)));
      $CRSHotelModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $booking_lastid,"service"=>"hotel",'transaction_type'=>"debit",'action_type'=>"booking"],["booking_confirmation_number"=>$BookingConfirmationNumber]);
      }
    $book_update_data = array(
      'confirmation_no' => '',
      'booking_status' => $booking_status,
      'supplier_booking_id' => '',
      'is_price_changed' => false
    );
    $CRSHotelModel->updateUserData('hotel_booking_list', ['id' => $booking_lastid], $book_update_data);
    $custom_index['CommonData'] = array('Supplier' => 'CRS', 'BookingId' => $booking_lastid);
    $tts_response = array(
      'UserIp'         => $input['UserIp'],
      'SearchTokenId'  => $tts_search_token,
      'Error'          => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
      'Result'         => $TTS_Result
    );
    return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
  }
  public function SendChangeRequest(array $input, $BookingId, array $userauthdata)
  {
    $tts_search_token = $input['SearchTokenId'];
    $CRSHotelModel = new CRSHotelModel();

    $ErrorCode = 0;
    $ErrorMessage = '';
    $TTS_Result = array();

    /*--------If Check Cancel Already Exist -------------*/
    $CancelData = $CRSHotelModel->check_cancel_data($userauthdata['web_partner_id'], $input['BookingId']);
    if ($CancelData) {
      $TTS_Result = array(
        'CancelRequestId' => (int) $CancelData['id'],
        'CancelRequestStatus' => $CancelData['supplier_cancel_status'],
        'CancelRemarks' => $CancelData['response_remark']
      );
    } else {

      /*--------Cancel Request Offline -------------*/
      if (isset($input['Offline'])) {
        $CancelRequestStatus = 'InProgress';
        $save_cancel_data = array(
          'web_partner_id' => $userauthdata['web_partner_id'],
          'hotel_booking_id' => $input['BookingId'],
          'user_ip' => $input['UserIp'],
          'remarks' => $input['Remarks'],
          'tts_search_token' => $tts_search_token,
          'supplier_cancel_id' => '',
          'supplier_cancel_status' => $CancelRequestStatus,
          'api_supplier' => 'Offline'
        );

        $cancel_list_lastid = $CRSHotelModel->insertData('hotel_cancellation', $save_cancel_data);

        $TTS_Result = array(
          'CancelRequestId' => (int) $cancel_list_lastid,
          'CancelRequestStatus' => $CancelRequestStatus,
          'CancelRemarks' => null
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

        $url = "$this->HotelService_URL1/rest/SendChangeRequest/";
        $response = TBO_Request($url, $request);
        $TBOHotelModel = new TBOHotelModel();

        if ($response['HotelChangeRequestResult']['Error']['ErrorCode'] == 0) {
          $trace_id = $response['HotelChangeRequestResult']['TraceId'];
          $ErrorCode = 0;
          $ErrorMessage = '';
          $CancelRequestId = $response['HotelChangeRequestResult']['ChangeRequestId'];
          $CancelRequestStatus = GetCancelStatus($response['HotelChangeRequestResult']['ChangeRequestStatus']);

          $save_cancel_data = array(
            'web_partner_id' => $userauthdata['web_partner_id'],
            'hotel_booking_id' => $input['BookingId'],
            'user_ip' => $input['UserIp'],
            'remarks' => $input['Remarks'],
            'tts_search_token' => $tts_search_token,
            'supplier_cancel_id' => $CancelRequestId,
            'supplier_cancel_status' => $CancelRequestStatus,
            'api_supplier' => 'TBO'
          );

          $cancel_list_lastid = $TBOHotelModel->insertData('hotel_cancellation', $save_cancel_data);

          $TTS_Result = array(
            'CancelRequestId' => (int) $cancel_list_lastid,
            'CancelRequestStatus' => $CancelRequestStatus,
            'CancelRemarks' => ''
          );
        } else {
          $trace_id = '';
          $ErrorCode = $response['HotelChangeRequestResult']['Error']['ErrorCode'];
          $ErrorMessage = $response['HotelChangeRequestResult']['Error']['ErrorMessage'];
        }

        /*--------------Start Insert API Logs------------------*/
        $TBOHotelModel->insert_hotel_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $trace_id, 'SendChangeRequest', strtotime(Time::now($this->GetTimeZone)));
        /*--------------End Insert API Logs--------------------*/
      }
    }

    $tts_response = array(
      'UserIp'         => $input['UserIp'],
      'SearchTokenId'  => $tts_search_token,
      'Error'          => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
      'Result'         => $TTS_Result
    );
    return $tts_response;
  }
}

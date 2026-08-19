<?php

namespace App\Modules\CRSHotel\Models;

use CodeIgniter\Model;

class CRSHotelModel extends Model
{
  function getDataRowType($tableName,$whereCondition,$field)
  {
    $builder =  $this->db->table($tableName)->select($field);
    if($whereCondition)
    {
    $builder->where($whereCondition);
    }
   return $builder->get()->getRowArray();
  }
  function checkHotelAvailiblity($searchData, $web_partner_id)
  {
    $HotelResults =  array();
    $FindTotalNumberPax  =  getMinMaxPaxsRoom($searchData['RoomGuests']);
    $occupancyType  =  getOccupancyType($FindTotalNumberPax['max']);
    $checkIn =  $searchData['CheckInDate'];
    $checkOut =  $searchData['CheckOutDate'];
    $stayNights  =  getHotelDateDiffrence($checkIn, $checkOut);
    $builder  =  $this->db->table('hotel_extranet_list');
    $builder->select("hotel_extranet_list.id,city_id,hotel_code,hotel_property_type_id,hotel_amenities,hotel_name,hotel_star_rating,hotel_images,hotel_promotion,hotel_description,address,state,city_name,postal_code,country_name,location_area,latitude,longitude,check_in_time,check_out_time,review_provider,review_rating,review_url, concat('[', group_concat(JSON_OBJECT('id', hotel_extranet_room.id) separator ','), ']') as room_id");
    $builder->join("hotel_extranet_room", "hotel_extranet_room.hotel_extranet_id=hotel_extranet_list.id");
    $builder->where(["hotel_extranet_list.status" => "active", "hotel_extranet_list.city_id" => $searchData['DestinationCityId']]);
    $builder->groupStart();
    $builder->where("hotel_extranet_list.hotel_star_rating>=", $searchData["MinRating"]);
    $builder->where("hotel_extranet_list.hotel_star_rating<=", $searchData["MaxRating"]);
    $builder->groupEnd();
    $builder->groupStart()->where(["hotel_extranet_list.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_list.web_partner_id" => NULL])->groupEnd();
    $builder->groupStart()->where(["hotel_extranet_room.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room.web_partner_id" => NULL])->groupEnd();
    $builder->where(["hotel_extranet_room.status" => "active", "hotel_extranet_room.min_stay<=" => $stayNights]);
    $builder->whereIn("hotel_extranet_room.occupancy_type" , $occupancyType);
    $builder->orderBy("hotel_extranet_list.id", "Desc");
    $builder->groupBy("hotel_extranet_room.hotel_extranet_id");
    $HotelResults  =  $builder->get()->getResultArray();
    $noOfRooms     =  count($searchData['RoomGuests']);
    if ($HotelResults) {
      $availablehotelRoom =  array();
      foreach ($HotelResults as $HotelResult) {
        $RoomId =  array();
        $RoomId  =  json_decode($HotelResult['room_id'], true);
        $RoomId =  array_column($RoomId, "id");
        $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
        $HotelRoombuilder  =  $this->db->table('hotel_extranet_room_availability');
        foreach ($allStayDates as $stayDate) {
          $dateDayMonthYear     =  getExHotelDayMonthYear($stayDate);
          $CheckValidtyDates  =  array("year" => $dateDayMonthYear['year'], "month" => $dateDayMonthYear["month"], "d" . $dateDayMonthYear['day'] . ">=" => $noOfRooms);
         
          $HotelRoombuilder->where($CheckValidtyDates);
          $HotelRoombuilder->select('hotel_extranet_room_availability.hotel_extranet_room_id');
          $HotelRoombuilder->whereIn("hotel_extranet_room_availability.hotel_extranet_room_id", $RoomId);
          $HotelRoombuilder->groupStart()->where(["hotel_extranet_room_availability.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_availability.web_partner_id" => NULL])->groupEnd();
          $HotelRoombuilder->groupBy("hotel_extranet_room_availability.hotel_extranet_room_id");
          $queryResponse    =  $HotelRoombuilder->get()->getResultArray();
          if ($queryResponse) {
            $availablehotelRoom[$HotelResult['id']][$stayDate] =  $queryResponse;
          } else {
            break;
          }
        }
      }
      if ($availablehotelRoom) {
        $filterHotelsRooms   =  getAvailableHotelRoom($availablehotelRoom, $HotelResults, $allStayDates);
        if ($filterHotelsRooms) {
          $checkinStrtotime  =  strtotime($checkIn);
          $checkoutStrtotime  =  strtotime($checkOut);
          $HotelRoomPricebuilder  =  $this->db->table('hotel_extranet_room_price');
          foreach ($filterHotelsRooms as $availableHotelRoomKey => $filterHotelRoom) {
            $HotelRoomPricebuilder->select('hotel_extranet_room_price.id,hotel_extranet_room_price.hotel_extranet_room_id,hotel_extranet_room_price.adult_price,hotel_extranet_room_price.child_price,hotel_extranet_room_price.mon,hotel_extranet_room_price.tue,hotel_extranet_room_price.wed,hotel_extranet_room_price.thu,hotel_extranet_room_price.fri,hotel_extranet_room_price.sat,hotel_extranet_room_price.sun');
            $HotelRoomPricebuilder->where("start_date<=", $checkinStrtotime);
            $HotelRoomPricebuilder->where("end_date>=", $checkoutStrtotime);
            $HotelRoomPricebuilder->whereIn("hotel_extranet_room_price.hotel_extranet_room_id", $filterHotelRoom['room_id']);
            $HotelRoomPricebuilder->groupStart()->where(["hotel_extranet_room_price.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_price.web_partner_id" => NULL])->groupEnd();
            $HotelRoomPricebuilder->groupBy("hotel_extranet_room_price.hotel_extranet_room_id");
            $roomPricedata              =  $HotelRoomPricebuilder->get()->getResultArray();
            if ($roomPricedata) {
              $filterHotelRoom['roomPriceData']  =  $roomPricedata;
              $filterHotelsRooms[$availableHotelRoomKey] = $filterHotelRoom;
            } else {
              unset($filterHotelsRooms[$availableHotelRoomKey]);
            }
          }
          return $filterHotelsRooms;
        } else {
          return array();
        }
      } else {
        return array();
      }
    } else {
      return array();
    }
  }
  function getPropertyType($web_partner_id)
  {
    $propertyType =  array();
    $builder  =  $this->db->table("hotel_extranet_property_type");
    $builder->select("property_type,id");
    $builder->where("status", "active");
    $response  =  $builder->get()->getResultArray();
    if ($propertyType) {
      $propertyType =  array_column($response, "property_type", "id");
    }
    return  $propertyType;
  }
  function getHotelRoomAmenity($web_partner_id, $amenityType)
  {
    $amenityName =  array();
    $amenityIcon =  array();
    $builder  =  $this->db->table("hotel_extranet_amenity");
    $builder->select("amenity_title,id,amenity_icon");
    $builder->where("status", "active");
    $builder->groupStart();
    $builder->where("amenity_type", $amenityType);
    $builder->orWhere("amenity_type", "both");
    $builder->groupEnd();
    $response  =  $builder->get()->getResultArray();
    if ($response) {
      $amenityName =  array_column($response, "amenity_title", "id");
      $amenityIcon =  array_column($response, "amenity_icon", "id");
    }
    return  array("amenityName" => $amenityName, "amenityIcon" => $amenityIcon);
  }
  function getHotelInfo($hotelId, $web_partner_id)
  {
    $builder  =  $this->db->table('hotel_extranet_list');
    $builder->select("hotel_extranet_list.id,city_id,hotel_code,hotel_property_type_id,hotel_amenities,hotel_name,hotel_star_rating,hotel_images,hotel_promotion,hotel_description,address,state,city_name,postal_code,country_name,location_area,latitude,longitude,check_in_time,check_out_time,review_provider,review_rating,review_url");
    $builder->where(["hotel_extranet_list.status" => "active", "hotel_extranet_list.id" => $hotelId]);
    $builder->groupStart()->where(["hotel_extranet_list.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_list.web_partner_id" => NULL])->groupEnd();
    return $builder->get()->getRowArray();
  }
  function getSearchData($arrayCondition, $selectedColumn)
  {
    $db = \Config\Database::connect('api');
    $builder  =  $db->table("tts_hotel_log");
    $builder->select($selectedColumn);
    $builder->where($arrayCondition);
    return $builder->get()->getRowArray();
  }
  function getHotelRooms($searchData, $web_partner_id, $RoomId =  null)
  {
    $HotelRoomResults =  array();
    $FindTotalNumberPax  =  getMinMaxPaxsRoom($searchData['RoomGuests']);
    $occupancyType  =  getOccupancyType($FindTotalNumberPax['max']);
    $checkIn =  $searchData['CheckInDate'];
    $checkOut =  $searchData['CheckOutDate'];
    $stayNights  =  getHotelDateDiffrence($checkIn, $checkOut);
    $builder  =  $this->db->table('hotel_extranet_room');
    $builder->select("hotel_extranet_room.id,hotel_extranet_room.room_title,hotel_extranet_room.occupancy_type,hotel_extranet_room.room_description,hotel_extranet_room.room_cancellation,hotel_extranet_room.room_gallery,hotel_extranet_room.room_amenities,hotel_extranet_room.min_voucher_day,,hotel_extranet_list.pan_required,hotel_extranet_list.passport_required");
    $builder->join("hotel_extranet_list", "hotel_extranet_room.hotel_extranet_id=hotel_extranet_list.id");
    $builder->where(["hotel_extranet_list.status" => "active", "hotel_extranet_list.id" => $searchData['HotelId']]);
    if ($RoomId !=  null) {
      $builder->where("hotel_extranet_room.id", $RoomId);
    }
    $builder->groupStart()->where(["hotel_extranet_list.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_list.web_partner_id" => NULL])->groupEnd();
    $builder->groupStart()->where(["hotel_extranet_room.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room.web_partner_id" => NULL])->groupEnd();
    $builder->where(["hotel_extranet_room.status" => "active", "hotel_extranet_room.min_stay<=" => $stayNights, "hotel_extranet_room.hotel_extranet_id" => $searchData['HotelId']]);
    $builder->whereIn("hotel_extranet_room.occupancy_type" , $occupancyType);
    $HotelRoomResults  =  $builder->get()->getResultArray();
    $noOfRooms     =  count($searchData['RoomGuests']);
    if ($HotelRoomResults) {
      $notavailableselectedhotelRoomId = array();
      foreach ($HotelRoomResults as $RoomResults) {
        $checkinStrtotime  =  strtotime($checkIn);
        $checkoutStrtotime  =  strtotime($checkOut);
        $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
        $HotelRoombuilder  =  $this->db->table('hotel_extranet_room_availability');
        foreach ($allStayDates as $stayDate) {
          $dateDayMonthYear     =  getExHotelDayMonthYear($stayDate);
          $CheckValidtDates  =  array("year" => $dateDayMonthYear['year'], "month" => $dateDayMonthYear["month"], "d" . $dateDayMonthYear['day'] . ">=" => $noOfRooms);

          $HotelRoombuilder->select('hotel_extranet_room_availability.hotel_extranet_room_id');
          $HotelRoombuilder->where($CheckValidtDates);
          $HotelRoombuilder->where("hotel_extranet_room_availability.hotel_extranet_room_id", $RoomResults['id']);
          $HotelRoombuilder->groupStart()->where(["hotel_extranet_room_availability.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_availability.web_partner_id" => NULL])->groupEnd();
          $queryResponse    =  $HotelRoombuilder->get()->getRowArray();
          if ($queryResponse) {
          } else {
            $notavailableselectedhotelRoomId[] =  $RoomResults['id'];
            break;
          }
        }
      }
      if ($HotelRoomResults) {
        $filterHotelsRooms   = array();
        $checkinStrtotime  =  strtotime($checkIn);
        $checkoutStrtotime  =  strtotime($checkOut);
        $HotelRoomPricebuilder  =  $this->db->table('hotel_extranet_room_price');
        foreach ($HotelRoomResults as $availableHotelRoomKey => $filterHotelRoom) {
          if (!in_array($filterHotelRoom['id'], $notavailableselectedhotelRoomId)) {
            $HotelRoomPricebuilder->select('hotel_extranet_room_price.id,hotel_extranet_room_price.hotel_extranet_room_id,hotel_extranet_room_price.adult_price,hotel_extranet_room_price.child_price,hotel_extranet_room_price.mon,hotel_extranet_room_price.tue,hotel_extranet_room_price.wed,hotel_extranet_room_price.thu,hotel_extranet_room_price.fri,hotel_extranet_room_price.sat,hotel_extranet_room_price.sun');
            $HotelRoomPricebuilder->where("start_date<=", $checkinStrtotime);
            $HotelRoomPricebuilder->where("end_date>=", $checkoutStrtotime);
            $HotelRoomPricebuilder->where("hotel_extranet_room_price.hotel_extranet_room_id", $filterHotelRoom['id']);
            $HotelRoomPricebuilder->groupStart()->where(["hotel_extranet_room_price.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_price.web_partner_id" => NULL])->groupEnd();
            $roomPricedata              =  $HotelRoomPricebuilder->get()->getResultArray();
            if ($roomPricedata) {
              $filterHotelRoom['roomPriceData']  =  $roomPricedata;
              $filterHotelsRooms[$availableHotelRoomKey] = $filterHotelRoom;
            } else {
              unset($filterHotelsRooms[$availableHotelRoomKey]);
            }
          }
        }
        return $filterHotelsRooms;
      } else {
        return array();
      }
    } else {
      return array();
    }
  }
  function get_block_data($tts_search_token, $selected_index)
  {
    $db = \Config\Database::connect('api');
    $builder = $db->table('tts_hotel_log');
    $builder->select('response');
    $builder->where(['tts_search_token' => $tts_search_token, 'selected_index' => $selected_index, 'service' => 'blockroom']);
    $builder->orderBy("id", "DESC");
    return $builder->get()->getRowArray();
  }
  function get_auth_user_account_balance($web_partner_id)
  {
    return  $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
  }
  function insertData($tableName, $insertData)
  {
    $this->db->table($tableName)->insert($insertData);
    return $this->db->insertID();
  }
  function getBookHotelRooms($searchData, $web_partner_id, $RoomId)
  {
    $HotelRoomResults =  array();
    $FindTotalNumberPax  =  getMinMaxPaxsRoom($searchData['RoomGuests']);
    $occupancyType  =  getOccupancyType($FindTotalNumberPax['max']);
    $checkIn =  $searchData['CheckInDate'];
    $checkOut =  $searchData['CheckOutDate'];
    $stayNights  =  getHotelDateDiffrence($checkIn, $checkOut);
    $builder  =  $this->db->table('hotel_extranet_room');
    $builder->select("hotel_extranet_room.id,hotel_extranet_room.room_title,hotel_extranet_room.occupancy_type,hotel_extranet_room.room_description,hotel_extranet_room.room_cancellation,hotel_extranet_room.room_gallery,hotel_extranet_room.room_amenities,hotel_extranet_room.min_voucher_day,,hotel_extranet_list.pan_required,hotel_extranet_list.passport_required");
    $builder->join("hotel_extranet_list", "hotel_extranet_room.hotel_extranet_id=hotel_extranet_list.id");
    $builder->where(["hotel_extranet_list.status" => "active", "hotel_extranet_list.id" => $searchData['HotelId']]);
    $builder->where("hotel_extranet_room.id", $RoomId);
    $builder->groupStart()->where(["hotel_extranet_list.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_list.web_partner_id" => NULL])->groupEnd();
    $builder->groupStart()->where(["hotel_extranet_room.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room.web_partner_id" => NULL])->groupEnd();
    $builder->where(["hotel_extranet_room.status" => "active", "hotel_extranet_room.min_stay<=" => $stayNights, "hotel_extranet_room.hotel_extranet_id" => $searchData['HotelId']]);
    $builder->whereIn("hotel_extranet_room.occupancy_type" , $occupancyType);
    $HotelRoomResults  =  $builder->get()->getResultArray();
    $noOfRooms     =  count($searchData['RoomGuests']);
    if ($HotelRoomResults) {
      $roomavailable  =  1;
      foreach ($HotelRoomResults as $RoomResults) {
        $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
        $HotelRoombuilder  =  $this->db->table('hotel_extranet_room_availability');
        foreach ($allStayDates as $stayDate) {
          $dateDayMonthYear     =  getExHotelDayMonthYear($stayDate);
          $CheckValidtDates  =  array("year" => $dateDayMonthYear['year'], "month" => $dateDayMonthYear["month"], "d" . $dateDayMonthYear['day'] . ">=" => $noOfRooms);
          $HotelRoombuilder->select('hotel_extranet_room_availability.hotel_extranet_room_id');
          $HotelRoombuilder->where($CheckValidtDates);
          $HotelRoombuilder->where("hotel_extranet_room_availability.hotel_extranet_room_id", $RoomResults['id']);
          $HotelRoombuilder->groupStart()->where(["hotel_extranet_room_availability.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_availability.web_partner_id" => NULL])->groupEnd();
          $queryResponse    =  $HotelRoombuilder->get()->getRowArray();
          if ($queryResponse) {
            $roomavailable  =  1;
          } else {
            $roomavailable  =  0;
            break;
          }
        }
      }
      return  $roomavailable;
    } else {
      return 0;
    }
  }
  function updateUserData($tableName,$whereCondition,$updateData)
  {
    $this->db->table($tableName)->where($whereCondition)->update($updateData);
  }
  function UpdateRoomAvailability($searchData, $web_partner_id, $RoomId)
  {
    $checkIn =  $searchData['CheckInDate'];
    $checkOut =  $searchData['CheckOutDate'];
    $allStayDates     =  getAllDateBwTwoDates($checkIn, $checkOut);
        $HotelExtranetRoomAvailabilityBuilder  =  $this->db->table('hotel_extranet_room_availability');
        $noOfRooms     =  count($searchData['RoomGuests']);
        foreach ($allStayDates as $stayDate) {
          $dateDayMonthYear     =  getExHotelDayMonthYear($stayDate);
          $day  =   $dateDayMonthYear['day'];
          $CheckValidtDates  =  array("year" => $dateDayMonthYear['year'], "month" => $dateDayMonthYear["month"]);
           $HotelExtranetRoomAvailabilityBuilder->select('d'.$day); 
          $HotelExtranetRoomAvailabilityBuilder->where($CheckValidtDates);
          $HotelExtranetRoomAvailabilityBuilder->where("hotel_extranet_room_availability.hotel_extranet_room_id", $RoomId);
          $HotelExtranetRoomAvailabilityBuilder->groupStart()->where(["hotel_extranet_room_availability.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_availability.web_partner_id" => NULL])->groupEnd();
           $queryResponse    =  $HotelExtranetRoomAvailabilityBuilder->get()->getRowArray(); 
           if($queryResponse){
           $HotelExtranetRoomAvailabilityBuilder->where($CheckValidtDates);
           $HotelExtranetRoomAvailabilityBuilder->where("hotel_extranet_room_availability.hotel_extranet_room_id", $RoomId);
           $HotelExtranetRoomAvailabilityBuilder->groupStart()->where(["hotel_extranet_room_availability.web_partner_id" => $web_partner_id])->orWhere(["hotel_extranet_room_availability.web_partner_id" => NULL])->groupEnd();
          $queryResponse    =  $HotelExtranetRoomAvailabilityBuilder->update(array("d$day"=>($queryResponse['d'.$day]-$noOfRooms)));
           }
        }
  }
  function get_city_name($city_id)
  {
      return  $this->db->table("hotel_city_list")->select('destination')->where('city_id', $city_id)->get()->getRowArray();
  }
  function check_cancel_data($web_partner_id,$hotel_booking_id)
    {
        return  $this->db->table("hotel_cancellation")->select('id,supplier_cancel_status,response_remark')->where(['web_partner_id'=>$web_partner_id,'hotel_booking_id'=>$hotel_booking_id])->get()->getRowArray();
    }
}

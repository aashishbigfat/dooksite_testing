<?php
use CodeIgniter\I18n\Time; 
function getMinMaxPaxsRoom($roomGuest)
{
  $GuestCount =  array();
  if ($roomGuest) {
    $roomGuestCount  =  array();
    foreach ($roomGuest as $key => $guest) {
      $roomGuestCount[$key] = $guest['Adult'] + $guest['Child'];
    }
    $GuestCount['min'] =  min($roomGuestCount);
    $GuestCount['max'] =  max($roomGuestCount);
  }
  return $GuestCount;
}

function getOccupancyType($maxGuestInRoom)
{
  $OccupancyType = "";
  switch ($maxGuestInRoom) {
    case "1":
      $OccupancyType = array("Single","Double","Triple","Quad");
      break;
    case "2":
      $OccupancyType = array("Double","Triple","Quad");
      break;
    case "3":
      $OccupancyType = array("Triple","Quad");
      break;
    case "4":
      $OccupancyType =  array("Quad");
      break;
  }
  return   $OccupancyType;
}
function  getHotelDateDiffrence($checkIn,$checkOut)
{
    $checkIn = Time::parse($checkIn); 
    $checkOut    = Time::parse($checkOut); 
    $diff  =  $checkIn->difference($checkOut);
   return $diff->days; 
    
}
function getAllDateBwTwoDates($checkIn,$checkOut){
  $period = new DatePeriod(
 new DateTime(($checkIn)),
 new DateInterval('P1D'),
 new DateTime(($checkOut))
);
foreach ($period   as $key => $value) {
$date[]  =     $value->format('Y-M-d') ;      
}
return $date;	 	
}
function getDayDigit($day){
  $month_array =  array("01"=>"1","02"=>"2","03"=>"3","04"=>"4","05"=>"5","06"=>"6","07"=>"7","08"=>"8","09"=>"9");
  if(array_key_exists($day,$month_array)){
   
      return  $month_array[$day]; 
    }
    else{
      return $day;
    }
    
  }
  function getExHotelDayMonthYear($date)
{
		$dateExplode  =  explode("-",$date);
    $day  =  getDayDigit($dateExplode[2]);
		$dates=array('day'=>$day,'month'=>$dateExplode[1],'year'=>$dateExplode[0]);
		return $dates;
}
function getAvailableHotelRoom($availablehotelRoom,$hotelResults,$allStayDates){
  $FilterAvailableHotelRoom  =  array();
     foreach($hotelResults as $hotelResult){      
           if(isset($availablehotelRoom[$hotelResult['id']]))
           {
            if(count($allStayDates)==count($availablehotelRoom[$hotelResult['id']]))
            {
             $NoRoomOptions  =  json_decode($hotelResult['room_id'],true); 
            $AvalibaleRoom   =  array();
            $RoomOptionValue   = "";
            foreach($NoRoomOptions as $NoRoomOption){
              foreach($availablehotelRoom[$hotelResult['id']]  as $availableroomData)
              {
              $CheckRoom  =  array_column($availableroomData,"hotel_extranet_room_id");
              if(in_array($NoRoomOption['id'],$CheckRoom)){
                $RoomOptionValue =  $NoRoomOption['id'];
              }
              else{
                $RoomOptionValue = "";
                  break;
              }
              }
              if($RoomOptionValue!=""){
              $AvalibaleRoom[] =  $RoomOptionValue;
              }
            }
            }
            else {
              $AvalibaleRoom   =  array();
            }
           }
           else{
            $AvalibaleRoom   =  array();
           }
           if($AvalibaleRoom){
            $hotelResult['room_id'] =  $AvalibaleRoom;
            $FilterAvailableHotelRoom[] =  $hotelResult;
           }
     }
     return $FilterAvailableHotelRoom;
}

function getCalculateMinHotelPrice($RoomsPrice,$searchData,$stayDates,$forprice)
{
  $roomPrice  = array();  
  foreach($RoomsPrice as $RoomPrice)
  {
    $TotalpriceRoom  = 0;
    $PriceRoom = 0;
       foreach($stayDates as $stayDate){
         $dayText  =  date("D",strtotime($stayDate));
          $dayText =  strtolower($dayText);
          $PriceRoom = 0;
        foreach($searchData['RoomGuests'] as $RoomGuests)
        {
          $AdultRoomPrice = 0;
        $ChildRoomPrice = 0;
          $NoAdult  =  $RoomGuests['Adult'];
          $NoChild  =  $RoomGuests['Child'];
           if($NoAdult<=2)
           {
                 $AdultRoomPrice          = $RoomPrice[$dayText];
           }
            if($NoAdult>2){
             $remainingRoomAdult  =  $NoAdult-2;
             $AdultRoomPrice =  $RoomPrice[$dayText]+ ($RoomPrice['adult_price']*$remainingRoomAdult);
           }
           if($NoChild>0)
           {
            $ChildRoomPrice =  ($RoomPrice['child_price']*$NoChild);
           }
           $PriceRoom =  $PriceRoom+($AdultRoomPrice+ $ChildRoomPrice);
        }
        $TotalpriceRoom =  ($TotalpriceRoom+$PriceRoom);
       }
       $roomPrice[$RoomPrice['hotel_extranet_room_id']] =  $TotalpriceRoom;
  }
  return min($roomPrice);
}
function getCalculateRoomPrice($RoomsPrice,$RoomGuests,$stayDates)
{
  $roomPrice  = array();  
  foreach($RoomsPrice as $RoomPrice)
  {
    $TotalpriceRoom  = 0;
    $PriceRoom = 0;
       foreach($stayDates as $stayDate){
         $dayText  =  date("D",strtotime($stayDate));
          $dayText =  strtolower($dayText);
          $PriceRoom = 0;
          $AdultRoomPrice = 0;
        $ChildRoomPrice = 0;
          $NoAdult  =  $RoomGuests['Adult'];
          $NoChild  =  $RoomGuests['Child'];
           if($NoAdult<=2)
           {
                 $AdultRoomPrice          = $RoomPrice[$dayText];
           }
            if($NoAdult>2){
             $remainingRoomAdult  =  $NoAdult-2;
             $AdultRoomPrice =  $RoomPrice[$dayText]+ ($RoomPrice['adult_price']*$remainingRoomAdult);
           }
           if($NoChild>0)
           {
            $ChildRoomPrice =  ($RoomPrice['child_price']*$NoChild);
           }
           $PriceRoom =  $PriceRoom+($AdultRoomPrice+ $ChildRoomPrice);
        
        $TotalpriceRoom =  ($TotalpriceRoom+$PriceRoom);
       }
      
  }
  return  $TotalpriceRoom;
}


/**
 * ----------------------------------------------------------------------------
 * Get Hotel Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function get_crs_hotel_fare($markup_data,$discount_data, $price,array $userauthdata,$super_admin_gst_state_code,$requestType  =  "") {

  $RoomPrice=0; $Tax=0;  $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;
  $markup_value=0; $discount_value=0;$extra_discount=0; $commission=0;
  if($markup_data)
  {
      if(isset($markup_data['markup_value']))
      {
         $markup_value=$markup_data['markup_value'];
      }
      /* display markup tag */
      if($markup_data['display_markup']=='in_tax')
      {
          $Tax=$markup_value;
      } elseif($markup_data['display_markup']=='in_service_charge'){
          $ServiceCharges=$markup_value;
      }   
  }

  $GST=array();
  $GST=gst_calculate('Hotel',$userauthdata['gst_state_code'],$super_admin_gst_state_code,$ServiceCharges);
    $PublishedPrice=round_value($price+$markup_value+$GST['TotalGSTAmount']);
  unset($GST['TotalGSTAmount']);

  if($discount_data)
  {
       /*  discount apply for AgentCommission */
       $discount_value=round_value(($price*$discount_data['value'])/100);
       if($discount_data['extra_discount'])
       {
          $extra_discount=round_value($discount_data['extra_discount']);
       } else {
          $extra_discount=0;
       }
       $discount_value=round_value($discount_value+$extra_discount);

        /* discount check max limit */
        if($discount_data['max_limit']) {
              if(round_value($discount_data['max_limit']) <= $discount_value)
              {
                  $discount_value=round_value($discount_data['max_limit']);
              }
         }
  }
  /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
  $OfferedPrice=round_value(($PublishedPrice)-($discount_value));
  if ($OfferedPrice > 0)
  { 

  } elseif ($OfferedPrice == 0){
      $commission=0;
      $extra_discount=0;
      $discount_value=0;
      $OfferedPrice=round_value(($price+$markup_value));

  } elseif ($OfferedPrice < 0) {

      $commission=0;
      $extra_discount=0;
      $discount_value=0;
      $OfferedPrice=round_value(($price+$markup_value));
  }

   $RoomPrice=$price;
   $OtherCharges=0;
   $Discount=$discount_value;
   $AgentCommission=$commission;

   /*-- calculate tds-- */
   $TDS=tds_calculate($discount_value);
  /* -- Calculate GST on Markup ---*/
 

 
   $tts_crs_hotel_breakup=array(
                              'RoomPrice'=>$RoomPrice,
                              'Tax'=>$Tax,
                              'OtherCharges'=>$OtherCharges,
                              'Discount'=>$Discount,
                              'PublishedPrice'=>$PublishedPrice,
                              'OfferedPrice'=>$OfferedPrice,
                              'AgentCommission'=>$AgentCommission,
                              'ServiceCharges'=>$ServiceCharges,
                              'TDS'=>$TDS,
                              'GST'=>$GST
                           );
                           if($requestType=="blockroom") {
                            $superAdminTaxes  =  0;
                            $superAdminGST =   array(
                              "CGSTAmount"=> 0,
                              "CGSTRate"=> 0,
                              "CessAmount"=> 0,
                              "CessRate"=> 0,
                              "IGSTAmount"=> 0,
                              "IGSTRate"=> 0,
                              "SGSTAmount"=> 0,
                              "SGSTRate"=> 0,
                              "TaxableAmount"=> 0,
                              "TotalGSTAmount"=> 0
                        );
                            $tts_hotel_superadmin_breakup=array(
                               'RoomPrice'=>$RoomPrice,
                               'Tax'=>$superAdminTaxes,
                               'OtherCharges'=>$OtherCharges,
                               'Discount'=>0,
                               'PublishedPrice'=>$RoomPrice,
                               'OfferedPrice'=>$RoomPrice,
                               'AgentCommission'=>0,
                               'ServiceCharges'=>0,
                               'AgentMarkUp'=>0,
                               'TCS'=>0,
                               'TDS'=>0,
                               'GST'=>$superAdminGST,
                               "SUP_Markup"=>$markup_value,
                               "SUP_Discount"=>$Discount,
                               "SUP_Commission"=>$commission,
                               "SUP_DisplayMarkup"=>isset($markup_data['display_markup'])?$markup_data['display_markup']:"in_tax",
                            );
                            return  array("WebPartnerBreakup"=>$tts_crs_hotel_breakup,"SuperAdminBreakup"=>$tts_hotel_superadmin_breakup);
                           }

   return $tts_crs_hotel_breakup;
}


function getDateBeforeAfter($date,$days,$beforeAfterText)
{
  $date = Time::parse($date); 
  if($beforeAfterText == "add"){
  $date   = $date->addDays($days);
  return explode(" ", $date)[0];
  }
  if($beforeAfterText == "sub"){
    $date =   $date->subDays($days);
    return explode(" ", $date)[0];
    }
}

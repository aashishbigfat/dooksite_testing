<?php

/**
 * ----------------------------------------------
 * TBO API Request (Return Json Format)
 * ---------------------------------------------
 */
function TBO_Request($url,$data) {
    $request_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
/*     curl_setopt($ch, CURLOPT_TIMEOUT, 60); */
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: gzip,deflate','Cache-Control: no-cache','Pragma: no-cache', 'Content-Length: ' . strlen($request_json)));
    $response = curl_exec($ch);
    
    // Check for errors and display the error message
    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        api_custom_message(408,$error_message,false);
    }
    curl_close($ch);
    return json_decode($response,true);
}

/**
 * ------------------------------------------------
 * TBO API Multi Curl Request (Return Json Format) 
 * ------------------------------------------------
 */

 function TBO_MultiCurl_Request($url,array $request)
 {
    if($request)
    {
        $response=array();
        foreach($request as $key=>$item)
        {
            $request_json=json_encode($item);
            $chs[$key] = curl_init();
            curl_setopt($chs[$key], CURLOPT_URL,$url);
            curl_setopt($chs[$key], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chs[$key], CURLOPT_ENCODING, 'gzip');
            curl_setopt($chs[$key], CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($chs[$key], CURLOPT_TIMEOUT, 60);
            curl_setopt($chs[$key], CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($chs[$key], CURLOPT_POSTFIELDS, $request_json);
            curl_setopt($chs[$key], CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: gzip,deflate','Cache-Control: no-cache','Pragma: no-cache', 'Content-Length: ' . strlen($request_json)));
        }

        //create the multiple cURL handle
        $mh = curl_multi_init();

        //add the handles
        foreach ($chs as &$ch) {
        curl_multi_add_handle($mh,$ch);
        }
        $active = null;
        //execute the handles
        do {
        $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($chs as $url=>&$ch) {
        $response[] = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch); 
        }
        curl_multi_close($mh);
        return $response;
    }
 }




/**
 * ----------------------------------------------------------------------------
 * Get Hotel Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function get_hotel_fare($markup_data,$discount_data,array $price,array $userauthdata,$super_admin_gst_state_code,$requestType  =  "") {

    $RoomPrice=0; $Tax=0;  $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;
    $Tax=$price['Tax'];

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
            $Tax=$price['Tax']+$markup_value;
        } elseif($markup_data['display_markup']=='in_service_charge'){
            $ServiceCharges=$markup_value;
        }   
    }

    $GST=array();
    $GST=gst_calculate('Hotel',$userauthdata['gst_state_code'],$super_admin_gst_state_code,$ServiceCharges);
    $PublishedPrice=round_value($price['PublishedPrice']+$markup_value+$GST['TotalGSTAmount']);
    unset($GST['TotalGSTAmount']);

    if($discount_data)
    {
         /*  discount apply for AgentCommission */
         $commission=round_value(($price['AgentCommission']*$discount_data['value'])/100);
         if($discount_data['extra_discount'])
         {
            $extra_discount=round_value($discount_data['extra_discount']);
         } else {
            $extra_discount=0;
         }
         $discount_value=round_value($commission+$extra_discount);

          /* discount check max limit */
          if($discount_data['max_limit']) {
                if(round_value($discount_data['max_limit']) <= $discount_value)
                {
                    $discount_value=round_value($discount_data['max_limit']);
                }
           }
    }
    /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
    $OfferedPrice=round_value(($PublishedPrice)-($commission+$extra_discount));
    if ($OfferedPrice > 0)
    { 

    } elseif ($OfferedPrice == 0){
        $commission=0;
        $extra_discount=0;
        $discount_value=0;
        $OfferedPrice=round_value(($price['PublishedPrice']+$markup_value));

    } elseif ($OfferedPrice < 0) {

        $commission=0;
        $extra_discount=0;
        $discount_value=0;
        $OfferedPrice=round_value(($price['PublishedPrice']+$markup_value));
    }

     $RoomPrice=$price['RoomPrice'];
     $OtherCharges=$price['OtherCharges'];
     $Discount=$extra_discount;
     $AgentCommission=$commission;

     /*-- calculate tds-- */
     $TDS=tds_calculate($discount_value);
    /* -- Calculate GST on Markup ---*/
   
     $Tax=$Tax+$price['ExtraGuestCharge']+$price['ChildCharge']+$price['ServiceCharge'];
   
     $tts_hotel_breakup=array(
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
                             $superAdminTaxes  =  $price['Tax']+$price['ExtraGuestCharge']+$price['ChildCharge']+$price['ServiceCharge'];
                             $price['GST']['TotalGSTAmount'] =    $price['TotalGSTAmount'];
                             $tts_hotel_superadmin_breakup=array(
                                'RoomPrice'=>$RoomPrice,
                                'Tax'=>$superAdminTaxes,
                                'OtherCharges'=>$OtherCharges,
                                'Discount'=>$price['Discount'],
                                'PublishedPrice'=>$price['PublishedPriceRoundedOff'],
                                'OfferedPrice'=>$price['OfferedPriceRoundedOff'],
                                'AgentCommission'=>$price['AgentCommission'],
                                'ServiceCharges'=>$price['ServiceCharge'],
                                'AgentMarkUp'=>$price['AgentMarkUp'],
                                'ServiceTax'=>$price['ServiceTax'],
                                'TCS'=>$price['TCS'],
                                'TDS'=>$price['TDS'],
                                'GST'=>$price['GST'],
                                "SUP_Markup"=>$markup_value,
                                "SUP_Discount"=>$Discount,
                                "SUP_Commission"=>$commission,
                                "SUP_DisplayMarkup"=>isset($markup_data['display_markup'])?$markup_data['display_markup']:"in_tax",
                             );
                             return  array("WebPartnerBreakup"=>$tts_hotel_breakup,"SuperAdminBreakup"=>$tts_hotel_superadmin_breakup);
                            }

     return $tts_hotel_breakup;
}



function GetSmokingPreference($smoking)
{
    $value=0;
    switch ($smoking) {
        case "NoPreference":
          $value=0;
          break;
        case "Smoking":
          $value=1;
          break;
        case "NonSmoking":
          $value=2;
          break;
        case "Either":
          $value=3;
          break;
        default:
         $value=0;
      }
    return $value;
}

function tag_exist($value)
{
    $return="";
    if(isset($value))
    {
        $return=$value;
    }
    return $return;
}

function GetCancelStatus($status)
{
    $value='Pending';
    switch ($status) {
        case "0":
          $value='NotSet';
          break;
        case "1":
          $value='Pending';
          break;
        case "2":
          $value='InProgress';
          break;
        case "3":
          $value='Processed';
          break;
        case "4":
          $value='Rejected';
          break;
        default:
         $value=0;
      }
    return $value;
}
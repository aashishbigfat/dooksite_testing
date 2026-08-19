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
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
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
 * ----------------------------------------------------------------------------
 * Get Bus Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

 function get_bus_fare($markup_data,$discount_data,array $price,array $userauthdata,$super_admin_gst_state_code,$service=null) {

    $BasePrice=0; $Tax=0;  $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;
    $Tax=$price['Tax'];

    $markup_value=0; $discount_value=0;$extra_discount=0; $commission=0;
    if($markup_data)
    {
        if($markup_data['markup_type']=='percent')
        {
           /*   markup apply for BasePrice */
                $markup_value=round_value(($price['BasePrice']*abs($markup_data['value']))/100);
            /*   markup check max limit  */
            if($markup_data['max_limit']) {
                if(round_value($markup_data['max_limit']) <= $markup_value)
                {
                   $markup_value=round_value(abs($markup_data['max_limit']));
                }
            }
        } elseif($markup_data['markup_type']=='fixed') {

                   $markup_value=round_value($markup_data['value']);
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
    $GST=gst_calculate('Bus',$userauthdata['gst_state_code'],$super_admin_gst_state_code,$ServiceCharges);
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

     $BasePrice=$price['BasePrice'];
     $OtherCharges=$price['OtherCharges'];
     $Discount=$extra_discount;
     $AgentCommission=$commission;

     /*-- calculate tds-- */
     $TDS=tds_calculate($discount_value);
    /* -- Calculate GST on Markup ---*/
   
   

     $tts_bus_breakup=array(
                                'BasePrice'=>$BasePrice,
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
                             $SuperAdminFareBreakup=array();
                             $WebPartnerFareBreakup=array();
                             if($service=='BlockSeat')
                             {
                                 $SupplierCommission=$price['AgentCommission']+$price['Discount'];
                                 $display_markup='';
                                 if(isset($markup_data['display_markup']))
                                 {
                                     $display_markup=$markup_data['display_markup'];
                                 }
                                 $discount_type='';
                                 if(isset($discount_data['discount_type']))
                                 {
                                     $discount_type=$discount_data['discount_type'];
                                 }
                                 $SuperAdminFareBreakup=array(
                                                                 'BaseFare'             => $price['BasePrice'],
                                                                 'Tax'                  => $price['Tax'],
                                                                 'OtherCharges'         => floatval($price['OtherCharges']),
                                                                 'Discount'             => $SupplierCommission,
                                                                 'PublishedPrice'       => $price['PublishedPrice'],
                                                                 'OfferedPrice'         => $price['OfferedPrice'],
                                                                 'AgentMarkUp'         => $price['AgentMarkUp'],
                                                                 'TDS'         => $price['TDS'],
                                                                 'GST'         => $price['GST'],
                                                                 'SUP_Markup'           => $markup_value,
                                                                 'SUP_DisplayMarkup'    => $display_markup,
                                                                 'SUP_Discount'         => $commission,
                                                                 'SUP_ExtraDiscount'    => $extra_discount,
                                                                 'SUP_DiscountType'     => $discount_type                                      
                                                             );
                                 $WebPartnerFareBreakup=$tts_bus_breakup;
                                 return array('Fare'=>$tts_bus_breakup,'SuperAdminFareBreakup'=>$SuperAdminFareBreakup,'WebPartnerFareBreakup'=>$WebPartnerFareBreakup);
                             }
     return $tts_bus_breakup;
}




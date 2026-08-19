<?php

use CodeIgniter\I18n\Time;

/**
 * ------------------------------------------------
 * API Multi Curl Request
 * ------------------------------------------------
 */
function Hotel_MultiCurl_Request(array $request)
 {
    if($request)
    {
        $response=array();
        $errno_array=array();
        $i=0;
            foreach($request as $key=>$subitem)
            {
                if($subitem['Supplier']=='TBO')
                {
                    $subkey=$i;
                    $url = $subitem['URL'];
                    $request_json=$subitem['Request'];
                    $chs[$subkey] = curl_init();
                    curl_setopt($chs[$subkey], CURLOPT_URL,$url);
                    curl_setopt($chs[$subkey], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chs[$subkey], CURLOPT_ENCODING, 'gzip');
                 /*    curl_setopt($chs[$subkey], CURLOPT_CONNECTTIMEOUT, 10); */
                  /*   curl_setopt($chs[$subkey], CURLOPT_TIMEOUT, 60); */
                    curl_setopt($chs[$subkey], CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($chs[$subkey], CURLOPT_POSTFIELDS, $request_json);
                    curl_setopt($chs[$subkey], CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: gzip,deflate','Cache-Control: no-cache','Pragma: no-cache', 'Content-Length: ' . strlen($request_json)));
                }
            
               
             
                if($subitem['Supplier']=='TRIPJACK')
                {
                    $subkey=$i;
                    $url = $subitem['URL'];
                    $request_json=$subitem['Request'];
                    $apikey=$subitem['ApiKey'];
                    $chs[$subkey] = curl_init();
                    curl_setopt($chs[$subkey], CURLOPT_URL,$url);
                    curl_setopt($chs[$subkey], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chs[$subkey], CURLOPT_ENCODING, 'gzip');
                  /*   curl_setopt($chs[$subkey], CURLOPT_CONNECTTIMEOUT, 10); */
                    //curl_setopt($chs[$subkey], CURLOPT_TIMEOUT, 60);
                    curl_setopt($chs[$subkey], CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($chs[$subkey], CURLOPT_POSTFIELDS, $request_json);
                    curl_setopt($chs[$subkey], CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: gzip,deflate','Cache-Control: no-cache','Pragma: no-cache','apikey: '.$apikey.'', 'Content-Length: ' . strlen($request_json)));
                }
                $i++;
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
        $q=0;
            foreach($request as $subitem)
            {
                $subkey=$q;
                $response[$subkey]=array(
                                            'Supplier'=>$subitem['Supplier'],
                                            'Request'=>$subitem['Request'],
                                            'Response'=>curl_multi_getcontent($chs[$subkey]),
                                        );
                curl_multi_remove_handle($mh, $chs[$subkey]); 
                $q++;
            }
        curl_multi_close($mh);
        return $response;
   } else {
       return array();
   }
}
function get_markup_value($supplier, array $markup_data, array $star_rating_data, $star_rating, $no_of_night, $no_of_rooms, $selectedmarkupInfo)
{

    if ($markup_data) {
        $sel_markup_data = array();
        if (isset($selectedmarkupInfo[$star_rating])) {
            $sel_markup_data = $selectedmarkupInfo[$star_rating];
            $markup_data = $sel_markup_data;
        }
        if (empty($sel_markup_data)) {
            foreach ($markup_data as $markup) {
                $supplierArray = explode(",", $markup['supplier']);
                $starRatingArray = explode(",", $markup['star_rating']);
                if (in_array($supplier, $supplierArray) && in_array($star_rating, $starRatingArray)) {
                    $selectedmarkupInfo[$star_rating] = $markup;
                    $sel_markup_data = $markup;
                    break;
                }
            }
        }
        if ($sel_markup_data) {
            if ($sel_markup_data['hotel_markup_type'] == 'per_night') {
                $markup_value = round_value($sel_markup_data['value'] * $no_of_night);
            }
            if ($sel_markup_data['hotel_markup_type'] == 'per_room') {
                $markup_value = round_value($sel_markup_data['value'] * $no_of_rooms);
            }
            $sel_markup_data['markup_value'] = $markup_value;
        }

        $markup_data = $sel_markup_data;
    }
    return array("markup_data" => $markup_data, "selectedMarkupDataInfo" => $selectedmarkupInfo);
}

/**
 * ----------------------------------------------
 * Create GetBookingDetail For Hotel Custom
 * ---------------------------------------------
 */
function ConvertBookingDetail(array $input, array $data)
{

    $TTS_Result = array(

        'BookingId' => (int)$data['id'],
        'ConfirmationNo' => $data['confirmation_no'],
        'BookingStatus' => $data['booking_status'],
        'IsPriceChanged' => (bool)$data['is_price_changed'],
        'HotelRoomsDetails' => json_decode($data['hotel_rooms_details'], true),
        'City' => $data['city'],
        'DestinationCityId' => (int)$data['city_id'],
        'CheckInDate' => $data['check_in_date'],
        'CheckOutDate' => $data['check_out_date'],
        'NoOfNights' => (int)$data['no_of_nights'],
        'NoOfRooms' => (int)$data['no_of_rooms'],
        'GuestNationality' => $data['guest_nationality'],
        'CountryCode' => $data['country_code'],
        'IsDomestic' => (bool)$data['is_domestic'],
        'HotelCode' => (int)$data['hotel_code'],
        'HotelName' => $data['hotel_name'],
        'StarRating' => (int)$data['star_rating'],
        'AddressLine1' => $data['address1'],
        'AddressLine2' => $data['address2'],
        'Latitude' => $data['latitude'],
        'Longitude' => $data['longitude'],
        'HotelNorms' => $data['hotel_norms'],
        'HotelPolicyDetail' => $data['hotel_policy_detail'],
        'LastCancellationDate' => $data['last_cancellation_date'],
        'LastVoucherDate' => $data['last_voucher_date'],
        'InvoiceNumber' => $data['AccountDetails']['acc_ref_number'],
        'InvoiceAmount' => (float)$data['AccountDetails']['debit'],
        'InvoiceCreatedOn' => api_date_format_with_time($data['AccountDetails']['created']),
    );

    $tts_response = array(
        'UserIp' => $input['UserIp'],
        'SearchTokenId' => $input['SearchTokenId'],
        'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
        'Result' => $TTS_Result
    );

    return $tts_response;
}


if (!function_exists('custom_money_format')) {

    function custom_money_format($number)
    {


        if (is_numeric($number) && floor($number) != $number) {
            return number_format($number, 2);
        } else {
            return number_format($number);
        }

    }
}
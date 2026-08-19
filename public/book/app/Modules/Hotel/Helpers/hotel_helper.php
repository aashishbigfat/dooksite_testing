<?php

use CodeIgniter\I18n\Time;

/**
 * ----------------------------------------------
 *  API Request (Return Json Format)
 * ---------------------------------------------
 */


 if (!function_exists('TTSRequest')) {
    function TTSRequest($data, $url, $service)
    {
        $request_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Username:' . credential['Username'] . '', 'Password:' . credential['Password'] . '', 'Btype:' . credential['Btype'] . ''));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}

/**
 * ----------------------------------------------
 *  API Request GET (Return Json Format)
 * ---------------------------------------------
 */
function RequestWithoutAuth($data, $url)
{
    $request_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
function  getNoguest($roomGuest)
{
    $totalGuest  = 0;
    if ($roomGuest) {
        foreach ($roomGuest as $Guest) {
            $totalGuest = $totalGuest + $Guest['Adult'] + $Guest['Child'];
        }
    }
    return  $totalGuest;
}

function getDateDiffrence($checkIn, $checkOut)
{
    $checkIn = Time::parse($checkIn);
    $checkOut    = Time::parse($checkOut);
    $diff  =  $checkIn->difference($checkOut);
    return $diff->days;
}
function hotel_date_format($date)
{
    $date =  date("d-m-Y", strtotime($date));
    $date = explode('-', $date);
    return $creat_newdate = $date[2] . '-' . $date[1] . '-' . $date[0];
}
function adult_child_count($Searchdata)
{
    $room2adult = 0;
    $room3adult = 0;
    $room4adult = 0;
    $room5adult = 0;
    $room6adult = 0;
    if (!empty($Searchdata['adult_2'])) {
        $room2adult = $Searchdata['adult_2'];
    }
    if (!empty($Searchdata['adult_3'])) {
        $room3adult = $Searchdata['adult_3'];
    }
    if (!empty($Searchdata['adult_4'])) {
        $room4adult = $Searchdata['adult_4'];
    }
    if (!empty($Searchdata['adult_5'])) {
        $room5adult = $Searchdata['adult_5'];
    }
    if (!empty($Searchdata['adult_6'])) {
        $room6adult = $Searchdata['adult_6'];
    }
    $adult = $Searchdata['adult_1'] + $room2adult + $room3adult + $room4adult + $room5adult + $room6adult;
    $room1child = 0;
    $room2child = 0;
    $room3child = 0;
    $room4child = 0;
    $room5child = 0;
    $room6child = 0;
    if (!empty($Searchdata['child_1'])) {
        $room1child = $Searchdata['child_1'];
    }
    if (!empty($Searchdata['child_2'])) {
        $room2child = $Searchdata['child_2'];
    }
    if (!empty($Searchdata['child_3'])) {
        $room3child = $Searchdata['child_3'];
    }
    if (!empty($Searchdata['child_4'])) {
        $room4child = $Searchdata['child_4'];
    }
    if (!empty($Searchdata['child_5'])) {
        $room5child = $Searchdata['child_5'];
    }
    if (!empty($Searchdata['child_6'])) {
        $room6child = $Searchdata['child_6'];
    }
    $child = $room1child + $room2child + $room3child + $room4child + $room5child + $room6child;
    $pax = $adult + $child;
    return $adult_child = array('adult' => $adult, 'child' => $child, 'total_p' => $pax);
}

function createhotelfilterarray($array)
{
    $response = [];
    if ($array) {
        foreach ($array as $key => $value) {
            $obj['key'] = $key;
            $obj['label'] = $value;
            $obj['isChecked'] = false;
            $obj['isTempChecked'] = false;
            array_push($response, $obj);
        }
    }
    return $response;
}
function check_int($int)
{
    if ($int > 0) {
        $imageurl = site_url('webroot/image/change-price-up.png');
        return array('sign' => '+', 'text' => 'increased', 'image' => $imageurl);
    } elseif ($int < 0) {
        $imageurl = site_url('webroot/image/change-price-down.png');
        return  array('sign' => '-', 'text' => 'decreased', 'image' => $imageurl);
    } elseif ($int == 0) {
        $imageurl = site_url('webroot/image/bell-512.png');
        return  array('sign' => '=', 'text' => 'equals', 'image' => $imageurl);
    }
}
function tag_exist($value)
{
    $return = "";
    if (isset($value)) {
        $return = $value;
    }
    return $return;
}
function gethotelStarRating($rating)
{

    switch ($rating) {
        case 0:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 5;
            break;
        case 1:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 1;
            break;
        case 2:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 2;
            break;
        case 3:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 3;
            break;
        case 4:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 4;
            break;
        case 5:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 5;
            break;
        case 6:
            $hotelRating['min'] = 1;
            $hotelRating['max'] = 5;
            break;
        case 7:
            $hotelRating['min'] = 2;
            $hotelRating['max'] = 5;
            break;
        case 8:
            $hotelRating['min'] = 3;
            $hotelRating['max'] = 5;
            break;
        case 9:
            $hotelRating['min'] = 4;
            $hotelRating['max'] = 5;
            break;
        case 10:
            $hotelRating['min'] = 5;
            $hotelRating['max'] = 5;
            break;
        default:
            $hotelRating['min'] = 0;
            $hotelRating['max'] = 5;
    }
    return $hotelRating;
}

/**
 * ----------------------------------------------------------------------------
 * Get Hotel Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function get_hotel_fare_test($markup_data, $discount_data, array $price, $requestType  =  "")
{
    $Tax = $price['Tax'];
    $markup_value = 0;
    $discount_value = 0;
    $ServiceCharges = $price['ServiceCharges'];
    $display_markup  =  "in_tax";
    if ($markup_data) {
        if (isset($markup_data['markup_value'])) {
            $markup_value = $markup_data['markup_value'];
        }
        /* display markup tag */
        if ($markup_data['display_markup'] == 'in_tax') {
            $Tax = $price['Tax'] + $markup_value;
        } elseif ($markup_data['display_markup'] == 'in_service_charge') {
            $ServiceCharges = $ServiceCharges + $markup_value;
            $display_markup =  "in_service_charge";
        }
    }
    if ($discount_data && isset($discount_data['value'])) {
        /*  discount apply for AgentCommission */
        $discount_value = round_value((($price['AgentCommission']) * $discount_data['value']) / 100);
    }
    $PublishedPrice = round_value($price['PublishedPrice'] + $markup_value - $discount_value);
    $price['PublishedPrice']  =   $PublishedPrice;
    $price['Tax']  =   $Tax;
    $price['ServiceCharges']  =   $ServiceCharges;
    $price['WebPMarkUp']  =   $markup_value;
    $price['WebPDiscount']  =   $discount_value;
    $price['DisplayMarkup']  =   $display_markup;
    return $price;
}

function get_markup_value($markup_data, $discount_data, $no_of_night, $no_of_rooms, $star_rating, $selectedmarkupInfo, $selectedDiscountDataInfo)
{
    if ($markup_data) {

        $sel_markup_data = array();
        if (isset($selectedmarkupInfo[$star_rating])) {
            $sel_markup_data = $selectedmarkupInfo[$star_rating];
            $markup_data = $sel_markup_data;
        }
        if (empty($sel_markup_data)) {
            foreach ($markup_data as $markup) {
                $starRatingArray = explode(",", $markup['star_rating']);
                if (in_array($star_rating, $starRatingArray)) {
                    $selectedmarkupInfo[$star_rating] = $markup;
                    $sel_markup_data = $markup;
                    break;
                }
            }
        }
        $markup_data = $sel_markup_data;
    }

    return array("markup_data" => $markup_data, 'discount_data' => $discount_data, "selectedMarkupDataInfo" => $selectedmarkupInfo, 'selectedDiscountDataInfo' => $selectedDiscountDataInfo);
}
function get_hotel_fare($input, $noofNights, $NoofRooms, $customer_gst_number, $webpartner_gst_code, $webpMarkup, $webpDiscount, $hotelPrice, $selectedMarkupDataInfo, $selectedDiscountDataInfo)
{
    $filterMarkupDiscount         =  get_markup_value($webpMarkup, $webpDiscount, $noofNights, $NoofRooms, $input['StarRating'], $selectedMarkupDataInfo, $selectedDiscountDataInfo);
    $webpMarkup =  $filterMarkupDiscount['markup_data'];
    $webpDiscount =  $filterMarkupDiscount['discount_data'];
    $selectedMarkupDataInfo =  $filterMarkupDiscount['selectedMarkupDataInfo'];
    $selectedDiscountDataInfo =  $filterMarkupDiscount['selectedDiscountDataInfo'];

    $webAgentCommision = 0;
    $extra_discount = 0;
    $discount = 0;
    $webp_markup = 0;
    $webp_discount_value = 0;
    $markup_value = 0;
    $agentTax = 0;
    $agentServiceCharges = 0;
    $agentPublishPrice = 0;
    $webpAddInTax = 0;
    $webAddInServiceCharge = 0;
    $agentOfferedPrice = 0;
    if (!empty($webpMarkup)) {
        if ($webpMarkup['hotel_markup_type'] == "per_room") {
            $webp_markup = $webpMarkup['value'] * $NoofRooms;
        }
        if ($webpMarkup['hotel_markup_type'] == "per_night") {
            $webp_markup = $webpMarkup['value'] * $noofNights;
        }
        if ($webpMarkup['display_markup'] == "in_tax") {
            $webpAddInTax = $webp_markup;
        }
        if ($webpMarkup['display_markup'] == "in_service_charge") {
            $webAddInServiceCharge = $webp_markup;
        }
    }

    if (!empty($webpDiscount) && $webpDiscount != null) {
        $webpdiscount =  ($webpDiscount['value'] / 100) * $hotelPrice['OfferedPrice'];

        if ($webpdiscount > $webpDiscount['max_limit']) {
            $webpdiscount = $webpDiscount['max_limit'];
        }
        $webp_discount_value = $discount;
        $extra_discount = $webpDiscount['extra_discount'];
    }

    $agentgst =  gst_calculate('Hotel', $customer_gst_number, $webpartner_gst_code, $webAddInServiceCharge);
    $totalgst = $agentgst['TotalGSTAmount'];
    /*-- calculate tds-- */
    $agentTds = tds_calculate($webp_discount_value + $extra_discount);
    $agentTax = $hotelPrice['Tax'] + $webpAddInTax;
    $webAgentCommision += $webp_discount_value;
    $discount += $extra_discount;
    $agentServiceCharges += $webAddInServiceCharge;
    $agentPublishPrice += $hotelPrice['PublishedPrice'] + $webpAddInTax + $webAddInServiceCharge + $totalgst;
    $agentOfferedPrice = round_value(($agentPublishPrice) - ($webp_discount_value + $extra_discount));
    if ($agentOfferedPrice > 0) {
    } elseif ($agentOfferedPrice == 0) {
        $discount = 0;
        $agentOfferedPrice = round_value(($hotelPrice['PublishedPrice'] + $webp_markup + $totalgst));
    } elseif ($agentOfferedPrice < 0) {

        $discount = 0;
        $agentOfferedPrice = round_value(($hotelPrice['PublishedPrice'] + $webp_markup + $totalgst));
    }
    unset($agentgst['TotalGSTAmount']);

    $webPartnerFareBreakUp  =  $hotelPrice;
    $webPartnerFareBreakUp['WebPMarkUp'] =  $webp_markup;
    $webPartnerFareBreakUp['WebPDiscount'] =  $webp_discount_value;
    $webPartnerFareBreakUp['WebPDisplayMarkup'] =  isset($webpMarkup['display_markup']) ? $webpMarkup['display_markup'] : "in_tax";
    $CustomerFareBreakUp = array(
        'RoomPrice' => $hotelPrice['RoomPrice'],
        'Tax' => $agentTax,
        'OtherCharges' => $hotelPrice['OtherCharges'] + $hotelPrice['ServiceCharges'],
        'Discount' => $extra_discount,
        'PublishedPrice' => $agentPublishPrice,
        'OfferedPrice' => $agentOfferedPrice,
        'AgentCommission' => $webAgentCommision,
        'ServiceCharges' => $agentServiceCharges,
        'TDS' => $agentTds,
        'GST' => $agentgst,
    );

    return ['WebPartnerFareBreakUp' => $webPartnerFareBreakUp, 'CustomerFareBreakUp' => $CustomerFareBreakUp, "selectedMarkupDataInfo" => $selectedMarkupDataInfo, "selectedDiscountDataInfo" => $selectedDiscountDataInfo];
}

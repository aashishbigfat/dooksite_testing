<?php

use CodeIgniter\I18n\Time;

/**
 * ----------------------------------------------
 * Create GetBookingDetail For Bus Custom
 * ---------------------------------------------
 */
function Get_Gender($val)
{
    $Gender = 'male';
    if ($val == 1) {
        $Gender = 'male';
    }
    if ($val == 2) {
        $Gender = 'female';
    }
    return $Gender;
}

function ConvertBookingDetail(array $input, array $data)
{

    $BasePrice = 0;
    $Tax = 0;
    $OtherCharges = 0;
    $Discount = 0;
    $PublishedPrice = 0;
    $OfferedPrice = 0;
    $AgentCommission = 0;
    $ServiceCharges = 0;
    $TDS = 0;
    $Passenger = array();
    if ($data['PassengerDetails']) {
        $GST = [];
        foreach ($data['PassengerDetails'] as $pax) {
            $seat_info = json_decode($pax['seat_info'], true);
            $Passenger[] = array(
                'LeadPassenger' => true,
                'Title' => $pax['title'],
                'FirstName' => $pax['first_name'],
                'LastName' => $pax['last_name'],
                'Age' => $pax['age'],
                'Gender' => $pax['title'],
                'Phoneno' => $pax['mobile_number'],
                'Email' => $pax['email_id'],
                'IdNumber' => $pax['id_number'],
                'IdType' => $pax['id_type'],
                'Address' => $pax['address'],
                'SeatName' => $pax['seat_name'],
                'SeatId' => $pax['seat_id'],
                'Seat' => $seat_info
            );

            $BasePrice += $seat_info['Price']['BasePrice'];
            $Tax += $seat_info['Price']['Tax'];
            $OtherCharges += $seat_info['Price']['OtherCharges'];
            $Discount += $seat_info['Price']['Discount'];
            $PublishedPrice += $seat_info['Price']['PublishedPrice'];
            $OfferedPrice += $seat_info['Price']['OfferedPrice'];
            $AgentCommission += $seat_info['Price']['AgentCommission'];
            $ServiceCharges += $seat_info['Price']['ServiceCharges'];
            $TDS += $seat_info['Price']['TDS'];

            foreach ($seat_info['Price']['GST'] as $key => $value) {
                $GST[$key] = $value + (isset($GST[$key]) ? $GST[$key] : 0.0);
            }
        }
    }

    $Price = array(
        'BasePrice' => $BasePrice,
        'Tax' => $Tax,
        'OtherCharges' => $OtherCharges,
        'Discount' => $Discount,
        'PublishedPrice' => $PublishedPrice,
        'OfferedPrice' => $OfferedPrice,
        'AgentCommission' => $AgentCommission,
        'ServiceCharges' => $ServiceCharges,
        'TDS' => $TDS,
        'GST' => $GST
    );


    $DepartureTime = bus_get_date_time($data['departure_time']);
    $ArrivalTime = bus_get_date_time($data['arrival_time']);
    $Duration = bus_get_duration_minutes($DepartureTime, $ArrivalTime);

    $TTS_Result = array(

        'BookingId' => (int)$data['id'],
        'TicketNo' => $data['ticket_no'],
        'TravelOperatorPNR' => $data['travel_operator_pnr'],

        'Origin' => $data['origin_city'],
        'OriginID' => (int)$data['origin_id'],
        'Destination' => $data['destination_city'],
        'DestinationId' => (int)$data['destination_id'],
        'DateOfJourney' => $data['date_of_journey'],
        'NoOfSeats' => (int)$data['no_of_seats'],

        'DepartureTime' => $DepartureTime,
        'ArrivalTime' => $ArrivalTime,
        'Duration' => $Duration,
        'BusType' => $data['bus_type'],
        'TravelName' => $data['bus_name'],

        'Passenger' => $Passenger,
        'BoardingPointdetails' => json_decode($data['boarding_points'], true),
        'DroppingPointdetails' => json_decode($data['dropping_points'], true),
        'CancelPolicy' => json_decode($data['cancellation_policies'], true),
        'Price' => $Price,

        'InvoiceNumber' => $data['AccountDetails']['acc_ref_number'],
        'InvoiceAmount' => (float)$data['AccountDetails']['debit'],
        'InvoiceCreatedOn' => datetime_utc_to_ist(api_date_format_with_time($data['AccountDetails']['created'])),

    );

    $tts_response = array(
        'UserIp' => $input['UserIp'],
        'SearchTokenId' => $input['SearchTokenId'],
        'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
        'Result' => $TTS_Result
    );

    return $tts_response;
}


function bus_get_date_time($date)
{
    $myTime = new Time($date);
    return $myTime->format('Y-m-d\TH:i:s');
}

function bus_get_duration_minutes($depart_date, $arrival_date)
{
    $depart_date = str_replace('T', ' ', $depart_date);
    $arrival_date = str_replace('T', ' ', $arrival_date);

    $DepTime = Time::parse($depart_date, 'Asia/Kolkata');
    $ArrTime = Time::parse($arrival_date, 'Asia/Kolkata');
    $diff = $DepTime->difference($ArrTime);
    return $diff->getMinutes();
}

function bus_custom_date_formate($dateTime, $type = null)
{
    return datetime_utc_to_ist(api_date_format_with_time(strtotime($dateTime)), 'd M Y');
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

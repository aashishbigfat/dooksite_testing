<?php

namespace App\Modules\WebPartnerAccount\Models;

use CodeIgniter\Model;

class CustomerAccountLogModel extends Model
{
    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function credit_notes($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['customer_account_log.created >='=> $from_date,'customer_account_log.created <='=> $to_date];
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")->where($array)
            ->where(['customer_account_log.action_type' => 'refund', 'customer_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
            ->join('flight_booking_list', "flight_booking_list.id = customer_account_log.booking_ref_no", 'Left')
            ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = customer_account_log.id", 'Right')
            ->orderBy("customer_account_log.id", "DESC")->paginate(40);

        return $data;
    }


    function credit_notes_search($web_partner_id, $data)
    {

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];

                $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
                customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['customer_account_log.action_type' => 'refund', 'customer_account_log.web_partner_id' => $web_partner_id])
                    ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = customer_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = customer_account_log.id", 'Right')
                    ->where($array)
                    ->orderBy("customer_account_log.id", "DESC")->paginate(40);

                return $data;

            } else {
                $array = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];

                $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
                customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['customer_account_log.action_type' => 'refund', 'customer_account_log.web_partner_id' => $web_partner_id])
                    ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = customer_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = customer_account_log.id", 'Right')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("customer_account_log.id", "DESC")->paginate(40);

                return $data;
            }
        } else {

            $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
            customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*, flight_booking_travelers.id as TravelerId,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                ->where(['customer_account_log.action_type' => 'refund', 'customer_account_log.web_partner_id' => $web_partner_id])
                ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
                ->join('flight_booking_list', "flight_booking_list.id = customer_account_log.booking_ref_no", 'Left')
                ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = customer_account_log.id", 'Right')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("customer_account_log.id", "DESC")->paginate(40);

            return $data;
        }
    }

    public function credit_notes_hotel($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['customer_account_log.created >='=> $from_date,'customer_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,hotel_booking_list.agent_fare_break_up,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,hotel_booking_list.country_code,
       hotel_booking_list.id as hotelBookingId,hotel_booking_list.booking_ref_number,hotel_booking_list.api_supplier,hotel_booking_list.hotel_name,hotel_booking_list.city,
        hotel_booking_list.supplier_booking_id,hotel_booking_list.confirmation_no,hotel_booking_list.lead_passenger_name,hotel_booking_list.amendment_charges,
        hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.booking_status,hotel_booking_list.hotel_rooms_details,
        hotel_booking_list.is_domestic,hotel_booking_list.payment_status, hotel_booking_list.total_price,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.credit,customer_account_log.debit,hotel_booking_list.booking_source,hotel_booking_list.customer_fare_break_up,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")->where($array)
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.web_partner_id' => $web_partner_id])->whereIn('hotel_booking_list.booking_source',['Wl_b2b','Wl_b2c'])
            ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.wl_agent_id', 'Left')
            ->join('hotel_booking_list', "hotel_booking_list.refund_account_id = customer_account_log.id", 'Right')
            ->orderBy("customer_account_log.id", "DESC")->paginate(40);

        return $builder ;
    }

    public function credit_notes_hotel_search($web_partner_id,$data)
    {

        $arrayValue = array("booking_ref_number" => "hotel_booking_list.booking_ref_number", "first_name" => "hotel_booking_list.lead_passenger_name", "last_name" => "hotel_booking_list.lead_passenger_name",   "booking_status" => "hotel_booking_list.booking_status", "payment_status" => "hotel_booking_list.payment_status");
        $bookingSource = ["B2B"=>"Wl_b2b","B2C"=>"Wl_b2c"];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        //web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
        $builder = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,hotel_booking_list.id as hotelBookingId,hotel_booking_list.booking_ref_number,hotel_booking_list.api_supplier,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
        hotel_booking_list.supplier_booking_id,customer_account_log.booking_ref_no,hotel_booking_list.agent_fare_break_up,hotel_booking_list.booking_source,
        hotel_booking_list.lead_passenger_name,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.customer_fare_break_up,
        hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.confirmation_no,hotel_booking_list.hotel_name,
        hotel_booking_list.is_domestic,hotel_booking_list.payment_status,hotel_booking_list.booking_status,
        hotel_booking_list.total_price,hotel_booking_list.amendment_charges,hotel_booking_list.hotel_rooms_details,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,customer_account_log.created")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
            ->join('hotel_booking_list', "hotel_booking_list.refund_account_id = customer_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }

        if(isset($bookingSource[$data['booking_source']])){
            $builder->where("hotel_booking_list.booking_source",$bookingSource[$data['booking_source']]);
        }else{
            $builder->whereIn('hotel_booking_list.booking_source',['Wl_b2b','Wl_b2c']);
        }

        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['hotel_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }

        return $builder->orderBy("customer_account_log.id", "DESC")->paginate(40);
    }

    public function credit_notes_holiday($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['customer_account_log.created >='=> $from_date,'customer_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,holiday_booking_list.api_supplier,holiday_booking_list.confirmation_no,
        holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.travel_date,holiday_booking_list.total_price,
       holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.amendment_charges,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,holiday_booking_list.booking_source,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'holiday','customer_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = web_partner_account_log.id", 'Right')->whereIn('holiday_booking_list.booking_source',['Wl_b2b','Wl_b2c'])
            ->orderBy("customer_account_log.id", "DESC")->paginate(40);

        return $data ;
    }

    public function credit_notes_holiday_search($web_partner_id,$data)
    {
        $arrayValue = array("booking_ref_number" => "holiday_booking_list.booking_ref_number", "first_name" => "holiday_booking_travelers.first_name", "last_name" => "holiday_booking_travelers.last_name",   "booking_status" => "holiday_booking_list.booking_status", "payment_status" => "holiday_booking_list.payment_status");
        $bookingSource = ["B2B"=>"Wl_b2b","B2C"=>"Wl_b2c"];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,holiday_booking_list.api_supplier,
        holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.travel_date, 
       holiday_booking_list.confirmation_no, holiday_booking_list.total_price,holiday_booking_list.booking_status,
       holiday_booking_list.payment_status,holiday_booking_list.amendment_charges,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,holiday_booking_list.booking_source,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'holiday','customer_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = customer_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['holiday_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if(isset($bookingSource[$data['booking_source']])){
            $builder->where("holiday_booking_list.booking_source",$bookingSource[$data['booking_source']]);
        }else{
            $builder->whereIn('holiday_booking_list.booking_source',['Wl_b2b','Wl_b2c']);
        }

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }

        return $builder->orderBy("customer_account_log.id", "DESC")->paginate(40);
    }

    public function credit_notes_bus($web_partner_id)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['customer_account_log.created >='=> $from_date,'customer_account_log.created <='=> $to_date];

        $bookingSource = ["B2B"=>"Wl_b2b","B2C"=>"Wl_b2c"];
        
        
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
        bus_booking_list.id as BookingId,bus_booking_list.booking_ref_number,
       
        bus_booking_list.bus_name,
        bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,
        
       bus_booking_list.confirmation_no,bus_booking_list.total_price,
       
       bus_booking_list.payment_status,bus_booking_list.booking_status,
       bus_booking_list.total_price,
        bus_booking_travelers.*,bus_booking_travelers.id as TravelerId,
  
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'bus','customer_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')


            ->join('bus_booking_list', "bus_booking_list.id = customer_account_log.booking_ref_no", 'Left')->whereIn('bus_booking_list.booking_source',['Wl_b2b','Wl_b2c'])
            ->join('bus_booking_travelers', "bus_booking_travelers.refund_account_id = bus_booking_list.id", 'Right')

            ->orderBy("bus_booking_list.id", "DESC")->paginate(40);

        return $data ;
    }


    public function credit_notes_bus_search($web_partner_id,$data)
    {
        $arrayValue = array("booking_ref_number" => "bus_booking_list.booking_ref_number", "first_name" => "bus_booking_travelers.first_name", "last_name" => "bus_booking_travelers.last_name",   "booking_status" => "bus_booking_list.booking_status", "payment_status" => "bus_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
        bus_booking_list.id as BookingId,bus_booking_list.booking_ref_number,bus_booking_list.travel_operator_pnr,bus_booking_list.ticket_no,
        bus_booking_list.bus_name, bus_booking_list.confirmation_no,bus_booking_list.total_price,
        bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,
       bus_booking_list.payment_status,bus_booking_list.booking_status, bus_booking_list.total_price,
        bus_booking_travelers.*,bus_booking_travelers.id as TravelerId,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'bus','customer_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('bus_booking_list', "bus_booking_list.id = customer_account_log.booking_ref_no", 'Left')
            ->join('bus_booking_travelers', "bus_booking_travelers.refund_account_id = customer_account_log.id", 'Right');



        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        }

        if(isset($bookingSource[$data['booking_source']])){
            $builder->where("bus_booking_list.booking_source",$bookingSource[$data['booking_source']]);
        }else{
            $builder->whereIn('bus_booking_list.booking_source',['Wl_b2b','Wl_b2c']);
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['bus_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }

        return $builder->orderBy("agent_account_log.id", "DESC")->paginate(40);
    }
}
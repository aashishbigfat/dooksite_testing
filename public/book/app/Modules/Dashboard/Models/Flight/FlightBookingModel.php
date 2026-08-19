<?php

namespace App\Modules\Dashboard\Models\Flight;

use CodeIgniter\Model;

class FlightBookingModel extends Model
{
    protected $table = 'flight_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_booking_list_all($web_partner_id,$wl_customer_id,$userType)
    {
        return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
        flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,
        flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')
            ->where([
                'flight_booking_list.web_partner_id' => $web_partner_id,
                'flight_booking_list.wl_customer_id' => $wl_customer_id,
                'flight_booking_list.booking_source' => $userType
            ])
            ->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);
    }

    public function flight_booking_upcomming_list($web_partner_id,$wl_customer_id,$userType){
        $year = date('Y');
        $month = date('m');
        return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
        flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,
        flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')

            ->where("MONTH(flight_booking_list.departure_date) = {$month} AND YEAR(flight_booking_list.departure_date) = {$year}")
            ->where(['flight_booking_list.web_partner_id' => $web_partner_id,'flight_booking_list.wl_customer_id'=>$wl_customer_id,'flight_booking_list.booking_source'=>$userType,'flight_booking_list.booking_status'=>'Confirmed'])


            ->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')

            ->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);
    }

    public function flight_booking_cancelled_list($web_partner_id,$wl_customer_id,$userType)
    {
        return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
        flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,
        flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->whereIn('flight_booking_list.booking_status',['Cancelled','PartialCancelled'])
            ->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')
            ->where(['flight_booking_list.web_partner_id' => $web_partner_id,'flight_booking_list.wl_customer_id'=>$wl_customer_id,'flight_booking_list.booking_source'=>$userType])

            ->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);
    }

  
    public function flight_booking_detail($web_partner_id,$booking_refrence_number,$userId,$userType)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
         ->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left');
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray(); 
        if($query) { 
        $builder = $this->db->table('flight_booking_list');
        $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
        ->join("web_partner_account_log","web_partner_account_log.booking_ref_no=flight_booking_list.id");
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id,"web_partner_account_log.service"=>"flight"]);
        $query['paymentInfo'] = $builder->get()->getResultArray(); 
        }
        return  $query;
    }



    function search_bookings($data,$web_partner_id,$wl_customer_id)
    {
        $arrayValue  =  array("booking_ref_number"=>"booking_ref_number","first_name"=>"flight_booking_travelers.first_name","last_name"=>"flight_booking_travelers.last_name","ticket_number"=>"ticket_number","pnr"=>"flight_booking_list.pnr","booking_status"=>"flight_booking_list.booking_status","payment_status"=>"flight_booking_list.payment_status","web_partner_fare_break_up"=>"flight_booking_list.web_partner_fare_break_up");

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
                return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
                flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')
                    ->where("flight_booking_list.web_partner_id",$web_partner_id)->where("flight_booking_list.wl_customer_id",$wl_customer_id)
                ->where($array)->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);

            } else {
                $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
                return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
                flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')
                    ->where("flight_booking_list.web_partner_id",$web_partner_id)->where("flight_booking_list.wl_customer_id",$wl_customer_id)
                ->where($array)->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,
            flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("admin_users","admin_users.id=flight_booking_list.agent_staff_id",'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id",'left')
                ->where("flight_booking_list.web_partner_id",$web_partner_id)->where("flight_booking_list.wl_customer_id",$wl_customer_id)
            ->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("flight_booking_list.id") ->orderBy("flight_booking_list.id","DESC")->paginate(40);
        }

    }
    function getBookingWithVariableFieldNameData($booking_refrence_number,$web_partner_id,$fieldName)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    public function flight_amendment_itinerary_detail($web_partner_id,$booking_refrence_number)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_booking_list.id,flight_booking_list.pnr,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth,'booking_status',flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray(); 
        return  $query;
    }
    public function amendment_list($web_partner_id, $booking_reference_number){
        $result =   $this->db->table('flight_amendment')->select("flight_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users","admin_users.id=flight_amendment.agent_staff_id",'left')
            ->where(["flight_amendment.web_partner_id"=>$web_partner_id,"flight_amendment.booking_ref_no"=>$booking_reference_number])
            ->get()->getResultArray();
        return $result;
    }
    public function flight_amendment_detail($web_partner_id,$amendment_id)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_amendment.*,flight_booking_list.id as flightbookingid,flight_booking_list.pnr,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,flight_booking_list.default_currency,flight_booking_list.booking_currency,flight_booking_list.currency_rate,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth,'booking_status',flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo");
        $builder->where(['flight_amendment.id' => $amendment_id, 'flight_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('flight_amendment', "flight_amendment.booking_ref_no = flight_booking_list.id");
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray(); 
        return  $query;
    }

    public function pax_details($pax_id){
        $builder = $this->db->table('flight_booking_travelers');
        $builder->select("title,first_name,last_name");
        $builder->whereIn('flight_booking_travelers.id', $pax_id);

        $builder->groupBy('flight_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return  $query;
    }
}
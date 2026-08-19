<?php

namespace App\Modules\Dashboard\Models\Cruise;

use CodeIgniter\Model;

class CruiseBookingModel extends Model
{
    protected $table = 'cruise_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_booking_list_all($web_partner_id,$wl_customer_id,$userType)
    {
        return  $this->select('cruise_booking_list.id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.no_of_travellers,cruise_booking_list.departure_port,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,cruise_booking_list.no_of_nights, cruise_booking_list.customer_fare_break_up,
        cruise_booking_list.booking_status,cruise_booking_list.created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up, cruise_booking_list.sailing_date,cruise_booking_list.tts_search_token,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(cruise_booking_travelers.title," ",cruise_booking_travelers.first_name," ",cruise_booking_travelers.last_name) as lead_passenger_name')
            ->join("admin_users","admin_users.id=cruise_booking_list.agent_staff_id",'left')
            ->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id",'left')
            ->where([
                'cruise_booking_list.web_partner_id' => $web_partner_id,
                'cruise_booking_list.wl_customer_id' => $wl_customer_id,
                'cruise_booking_list.booking_source' => $userType
            ])
            ->groupBy("cruise_booking_list.id") ->orderBy("cruise_booking_list.id","DESC")->paginate(40);
    }

    public function cruise_booking_upcomming_list($web_partner_id,$wl_customer_id,$userType){
        $year = date('Y');
        $month = date('m');
        return  $this->select('cruise_booking_list.id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.no_of_travellers,cruise_booking_list.departure_port,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,cruise_booking_list.no_of_nights, cruise_booking_list.customer_fare_break_up,
        cruise_booking_list.booking_status,cruise_booking_list.created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up, cruise_booking_list.sailing_date,cruise_booking_list.tts_search_token,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(cruise_booking_travelers.title," ",cruise_booking_travelers.first_name," ",cruise_booking_travelers.last_name) as lead_passenger_name')

            ->where("MONTH(cruise_booking_list.sailing_date) = {$month} AND YEAR(cruise_booking_list.sailing_date) = {$year}")
            ->where(['cruise_booking_list.web_partner_id' => $web_partner_id,'cruise_booking_list.wl_customer_id'=>$wl_customer_id,'cruise_booking_list.booking_source'=>$userType,'cruise_booking_list.booking_status'=>'Confirmed'])


            ->join("admin_users","admin_users.id=cruise_booking_list.agent_staff_id",'left')
            ->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id",'left')

            ->groupBy("cruise_booking_list.id") ->orderBy("cruise_booking_list.id","DESC")->paginate(40);
    }

    public function cruise_booking_cancelled_list($web_partner_id,$wl_customer_id,$userType)
    {
        return  $this->select('cruise_booking_list.id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.no_of_travellers,cruise_booking_list.departure_port,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,cruise_booking_list.no_of_nights, cruise_booking_list.customer_fare_break_up,
        cruise_booking_list.booking_status,cruise_booking_list.created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up, cruise_booking_list.sailing_date,cruise_booking_list.tts_search_token,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(cruise_booking_travelers.title," ",cruise_booking_travelers.first_name," ",cruise_booking_travelers.last_name) as lead_passenger_name')
            ->whereIn('cruise_booking_list.booking_status',['Cancelled','PartialCancelled'])
            ->join("admin_users","admin_users.id=cruise_booking_list.agent_staff_id",'left')
            ->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id",'left')
            ->where(['cruise_booking_list.web_partner_id' => $web_partner_id,'cruise_booking_list.wl_customer_id'=>$wl_customer_id,'cruise_booking_list.booking_source'=>$userType])

            ->groupBy("cruise_booking_list.id") ->orderBy("cruise_booking_list.id","DESC")->paginate(40);
    }

  
    public function cruise_booking_detail($web_partner_id,$booking_refrence_number,$userId,$userType)
    {
        $builder = $this->db->table('cruise_booking_list');
        $builder->select("cruise_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', cruise_booking_travelers.id,'booking_status',cruise_booking_travelers.booking_status,'ticket_number',cruise_booking_travelers.ticket_number,'title',cruise_booking_travelers.title,'first_name',cruise_booking_travelers.first_name,'last_name',cruise_booking_travelers.last_name,'pax_type',cruise_booking_travelers.pax_type,'gendar',cruise_booking_travelers.gendar,'date_of_birth',cruise_booking_travelers.date_of_birth,'pan_number',cruise_booking_travelers.pan_number,'passport_number',cruise_booking_travelers.passport_number,'passport_expiry',cruise_booking_travelers.passport_expiry,'lead_pax',cruise_booking_travelers.lead_pax,'email_id',cruise_booking_travelers.email_id,'mobile_number',cruise_booking_travelers.mobile_number,'address_1',cruise_booking_travelers.address_1,'address_2',cruise_booking_travelers.address_2,'city',cruise_booking_travelers.city,'country_code',cruise_booking_travelers.country_code,'country_name',cruise_booking_travelers.country_name,'ff_airline',cruise_booking_travelers.ff_airline,'ff_number',cruise_booking_travelers.ff_number,'baggage',cruise_booking_travelers.baggage,'meal',cruise_booking_travelers.meal,'fare',cruise_booking_travelers.fare,'date_of_birth',cruise_booking_travelers.date_of_birth) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
         ->join("admin_users","admin_users.id=cruise_booking_list.agent_staff_id",'left');
        $builder->where(['cruise_booking_list.booking_ref_number' => $booking_refrence_number, 'cruise_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id");
        $builder->groupBy('cruise_booking_list.id');
        $query = $builder->get()->getRowArray(); 
        if($query) { 
        $builder = $this->db->table('customer_account_log');
        $builder->select("customer_account_log.id,customer_account_log.acc_ref_number,customer_account_log.debit,customer_account_log.credit,customer_account_log.service,customer_account_log.remark,customer_account_log.service_log,customer_account_log.transaction_id,customer_account_log.payment_mode,customer_account_log.transaction_type,customer_account_log.action_type,customer_account_log.created")
        ->join("customer_account_log","customer_account_log.booking_ref_no=cruise_booking_list.id");
        $builder->where(['customer_account_log.booking_ref_no' => $query['id'], 'cruise_booking_list.web_partner_id' => $web_partner_id,"customer_account_log.service"=>"cruise"]);
        $query['paymentInfo'] = $builder->get()->getResultArray(); 
        }
        return  $query;
    }

    public function amendment_list($web_partner_id, $booking_reference_number){
        $result =   $this->db->table('cruise_amendment')->select("cruise_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users","admin_users.id=cruise_amendment.agent_staff_id",'left')
            ->where(["cruise_amendment.web_partner_id"=>$web_partner_id,"cruise_amendment.booking_ref_no"=>$booking_reference_number])
            ->get()->getResultArray();
        return $result;
    }
    public function pax_details($pax_id){
        $builder = $this->db->table('cruise_booking_travelers');
        $builder->select("title,first_name,last_name");
        $builder->whereIn('cruise_booking_travelers.id', $pax_id);

        $builder->groupBy('cruise_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return  $query;
    }
}
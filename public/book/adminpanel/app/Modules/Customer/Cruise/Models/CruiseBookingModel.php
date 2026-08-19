<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;
  
class CruiseBookingModel extends Model
{
    protected $table = 'cruise_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function cruise_booking_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00'); 
        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59'); 
        $array = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date]; 
        $builder =  $this->select('cruise_booking_list.id,cruise_booking_list.web_partner_id,cruise_booking_list.booking_source,cruise_booking_list.webpartner_assign_user,cruise_booking_list.cruise_line_name,agent.company_name,agent.company_id,cruise_booking_list.ship_name,cruise_booking_list.departure_port,cruise_booking_list.sailing_date,cruise_booking_list.payment_mode,cruise_booking_list.payment_status,cruise_booking_list.booking_status,cruise_booking_list.update_ticket_by,cruise_booking_list.total_price,cruise_booking_list.created,cruise_booking_travelers.email_id,cruise_booking_travelers.mobile_number,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,cruise_booking_list.booking_ref_number,cruise_booking_list.tts_search_token,cruise_booking_list.assign_user,cruise_booking_list.is_manual,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')
            ->join('cruise_booking_travelers', 'cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id', 'Left')  
            ->join("agent_users", "agent_users.id=cruise_booking_list.wl_agent_staff_id", 'left')
            ->join('agent', "cruise_booking_list.wl_agent_id = agent.id", 'left')
            ->join('admin_users', "admin_users.id = cruise_booking_list.webpartner_assign_user", 'left')
            ->where(['cruise_booking_list.web_partner_id'=>$web_partner_id]);

        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "Processing") {
            $builder->where(["cruise_booking_list.booking_status" => "Processing"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "Cancelled") {
            $builder->where(["cruise_booking_list.booking_status" => "Cancelled"])->orWhere(["cruise_booking_list.booking_status" => "PartialCancelled"]);
        }
        return $builder->groupBy("cruise_booking_list.id")->orderBy("cruise_booking_list.id", "DESC")->paginate(30);
            
    } 


    function search_data($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    { 
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "cruise_booking_travelers.first_name", "last_name" => "cruise_booking_travelers.last_name", "web_partner_fare_break_up" => "cruise_booking_list.web_partner_fare_break_up", "booking_status" => "cruise_booking_list.booking_status", "payment_status" => "cruise_booking_list.payment_status");
        $bookinSource = ["B2B" => "Wl_b2b", "B2C" => "Wl_b2c"];
        $builder = $this->select('cruise_booking_list.*,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')
            ->join("agent_users", "agent_users.id=cruise_booking_list.wl_agent_staff_id", 'left')
            ->join('admin_users', "admin_users.id = cruise_booking_list.webpartner_assign_user", 'left')
            ->join('agent', "cruise_booking_list.wl_agent_id = agent.id", 'left');
            $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id", 'left');
        
            $builder->where("cruise_booking_list.web_partner_id", $web_partner_id);
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];
        if (isset($data['booking_source']) && $data['booking_source'] != "") {
            $array['cruise_booking_list.booking_source'] = $bookinSource[$data['booking_source']];
        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['cruise_booking_list.wl_agent_id'] = $data['tts_web_partner_info'];
        }
        if ($bookingType == "Processing") {
            $array['cruise_booking_list.booking_status'] = "Processing";
        }
        if ($bookingType == "Cancelled") {
            $array['cruise_booking_list.booking_status'] = "Cancelled";
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
        return $builder->groupBy("cruise_booking_list.id")->orderBy("cruise_booking_list.id", "DESC")->paginate(30);
    }


    
 

    function getBookingDetailData($booking_refrence_number,$web_partner_id)
    {
        $builder = $this->db->table('cruise_booking_list');
        $builder->select("cruise_booking_list.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,CONCAT(admin_users.first_name,' ',admin_users.last_name) as assign_user_name");
        $builder->where(['cruise_booking_list.booking_ref_number' => $booking_refrence_number,'cruise_booking_list.web_partner_id'=>$web_partner_id]) 
            ->join("agent_users", "agent_users.id=cruise_booking_list.wl_agent_staff_id", 'left')
            ->join('admin_users', "admin_users.id = cruise_booking_list.webpartner_assign_user", 'left');
        $builder->groupBy('cruise_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {

            if($query['booking_source'] == "Wl_b2c"){
                $builder = $this->db->table('cruise_booking_list');
                $builder->select("customer_account_log.*")
                    ->join("customer_account_log", "customer_account_log.booking_ref_no=cruise_booking_list.id");
                $builder->where(['cruise_booking_list.booking_ref_number' => $booking_refrence_number, "customer_account_log.service" => "cruise"]);
                $query['paymentInfo'] = $builder->get()->getResultArray();
            }else{
                $builder = $this->db->table('cruise_booking_list');
                $builder->select("agent_account_log.id,agent_account_log.acc_ref_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.action_type,agent_account_log.created")
                    ->join("agent_account_log", "agent_account_log.booking_ref_no=cruise_booking_list.id");
                $builder->where(['cruise_booking_list.booking_ref_number' => $booking_refrence_number, 'agent_account_log.service'=>'cruise']);
                $query['paymentInfo'] = $builder->get()->getResultArray();
            }

            $builder = $this->db->table('cruise_booking_list');
            $builder->select("cruise_booking_travelers.email_id,cruise_booking_travelers.mobile_number,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,
           cruise_booking_travelers.gendar,cruise_booking_travelers.dob,cruise_booking_travelers.lead_pax,cruise_booking_travelers.passport_no,cruise_booking_travelers.passport_expiry_date");
            $builder->where(['cruise_booking_list.id' => $query['id']]);
            $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id");
            $query['travellers'] = $builder->get()->getResultArray();
 

            $query['email'] ='';
            $query['mobile_no'] ='';
        }
        return $query;
    }


    public function amendment_list($booking_reference_number,$web_partner_id)
    {
        return  $this->db->table('cruise_amendment')->select("cruise_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join("agent_users","agent_users.id=cruise_amendment.agent_staff_id",'left')
            ->where(["cruise_amendment.booking_ref_no"=>$booking_reference_number,'cruise_amendment.web_partner_id'=>$web_partner_id])
            ->get()->getResultArray();
    }







    


   

    function getBookingConfirmationData($bookingid){

        $builder = $this->db->table('cruise_booking_list');
        $builder->select("cruise_booking_list.id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.cruise_ocean,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status, cruise_booking_list.cancellation_policy,cruise_booking_list.payment_policy,cruise_booking_list.cruise_ocean,cruise_booking_list.cruise_itinerary,cruise_booking_list.sailing_date,cruise_booking_list.departure_port,cruise_booking_list.cabin_name,cruise_booking_list.tts_search_token,
        cruise_booking_list.booking_status,cruise_booking_list.created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up");
        $builder->where(['cruise_booking_list.id' => $bookingid]);

        $builder->groupBy('cruise_booking_list.id');
        $query = $builder->get()->getRowArray();


        //get all travellers
        $builder = $this->db->table('cruise_booking_list');
        $builder->select("cruise_booking_travelers.email_id,cruise_booking_travelers.mobile_number,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,
        cruise_booking_travelers.pax_type,cruise_booking_travelers.gendar,cruise_booking_travelers.dob,
        cruise_booking_travelers.passport_no,cruise_booking_travelers.passport_expiry_date");
        $builder->where(['cruise_booking_list.id' => $bookingid]);
        $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id");

        $query['travellers'] = $builder->get()->getResultArray();

        return $query;
    }

    function getBulkData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getResultArray();
    }

    public function search_data_sales_report($data,$page){
       
        $builder = $this->select('cruise_booking_list.*,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
                                    web_partner.company_name,web_partner.company_id,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name')
            ->join("admin_users", "admin_users.id=cruise_booking_list.agent_staff_id", 'left')
            ->join('web_partner', "cruise_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = cruise_booking_list.assign_user", 'left');
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date];
        }
        
        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];
        
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['cruise_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }
        
        if (!empty($array)) {
            $builder->where($array);
        }
        
        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        
        if (isset($data['key']) && $data['key'] != "") {
            $builder->like(trim($data['key']), trim($data['value']));
        }
        return  $builder->groupBy("cruise_booking_list.id")->orderBy("cruise_booking_list.id", "DESC")->paginate(30);

    }

    public function data_sales_report($page){
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date];

        $builder =  $this->select('cruise_booking_list.id,cruise_booking_list.web_partner_id,cruise_booking_list.sailing_date,cruise_booking_list.payment_mode,cruise_booking_list.tts_search_token,cruise_booking_list.booking_ref_number,cruise_booking_list.cruise_line_name,cruise_booking_list.assign_user,cruise_booking_list.update_ticket_by,cruise_booking_list.ship_name,
        cruise_booking_list.payment_status,cruise_booking_list.booking_status,cruise_booking_list.total_price,cruise_booking_list.created,cruise_booking_list.is_manual,cruise_booking_list.web_partner_fare_break_up,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name')
            ->join("admin_users", "admin_users.id=cruise_booking_list.agent_staff_id", 'left')
            ->join('web_partner', "cruise_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = cruise_booking_list.assign_user", 'left')->where($array);

        $builder->where('cruise_booking_list.payment_status','Successful');
        $builder->whereNotIn("cruise_booking_list.booking_status", ['Failed','Processing']);
        return  $builder->groupBy("cruise_booking_list.id")->orderBy("cruise_booking_list.id", "DESC")->paginate($page);
    }
}
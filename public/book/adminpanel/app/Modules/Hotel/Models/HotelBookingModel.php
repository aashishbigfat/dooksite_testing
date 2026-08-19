<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelBookingModel extends Model
{
    protected $table = 'hotel_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function hotel_booking_list($web_partner_id,$userId,$userType,$bookingType = "all", $source = null)
    {  
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');
        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');
        $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date]; 
        
        $builder = $this->select('hotel_booking_list.webpartner_assign_user,agent.company_name,agent.company_id,hotel_booking_list.api_supplier, hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.booking_source,hotel_booking_list.is_manual,hotel_booking_list.webpartner_update_ticket_by,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.wl_agent_id,hotel_booking_list.wl_customer_id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name');
        $builder->join("agent_users", "agent_users.agent_id=hotel_booking_list.wl_agent_id", 'left');
        $builder->join('agent', "hotel_booking_list.wl_agent_id = agent.id", 'left');
        $builder->join('admin_users', "admin_users.id = hotel_booking_list.webpartner_assign_user", 'left')->where("hotel_booking_list.web_partner_id", $web_partner_id);
        $booking_source = ['Wl_b2c','Wl_b2b'];
        $builder->whereIn('hotel_booking_list.booking_source',$booking_source);
        if ($source != "dashboard") {
            $builder->where($array);
          
        }
        if ($bookingType == "Processing") {
            $builder->where(["hotel_booking_list.booking_status" => "Processing"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "Cancelled") {
            $builder->where(["hotel_booking_list.booking_status" => "Cancelled"]);
        }
        return $builder->groupBy("hotel_booking_list.id")->orderBy("hotel_booking_list.id", "DESC")->paginate(40);
    }

 
 
    function search_bookings($data,$web_partner_id)
    {   $booking_source = ['B2C'=>'Wl_b2c','B2B'=>'Wl_b2b'];
        if(!isset($data['booking_source'])  || empty($data['booking_source'])){
            $data['booking_source'] = 'B2B';  
        } 
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
           
            if ($data['key'] == 'date-range') {
                $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date,"hotel_booking_list.web_partner_id"=>$web_partner_id];

                return  $this->select('hotel_booking_list.webpartner_assign_user,agent.company_name,agent.company_id,hotel_booking_list.api_supplier,hotel_booking_list.booking_source, hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.is_manual,hotel_booking_list.webpartner_update_ticket_by,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.wl_agent_id,hotel_booking_list.wl_customer_id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')->join("admin_users","admin_users.id=hotel_booking_list.webpartner_assign_user",'left')->join("agent_users", "agent_users.agent_id=hotel_booking_list.wl_agent_id", 'left')->join('agent', "hotel_booking_list.wl_agent_id = agent.id", 'left')->where("hotel_booking_list.web_partner_id",$web_partner_id)
               
                ->where('hotel_booking_list.booking_source',$booking_source[$data['booking_source']])->where($array)->orderBy("hotel_booking_list.id","DESC")->paginate(40);
            } else {
                $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date,"hotel_booking_list.web_partner_id"=>$web_partner_id];
             
                return  $this->select('hotel_booking_list.webpartner_assign_user,agent.company_name,agent.company_id,hotel_booking_list.api_supplier,hotel_booking_list.booking_source,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.is_manual,hotel_booking_list.webpartner_update_ticket_by,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.wl_agent_id,hotel_booking_list.wl_customer_id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')->join("admin_users","admin_users.id=hotel_booking_list.webpartner_assign_user",'left')->join("agent_users", "agent_users.agent_id=hotel_booking_list.wl_agent_id", 'left')->join('agent', "hotel_booking_list.wl_agent_id = agent.id", 'left')->where("hotel_booking_list.web_partner_id",$web_partner_id)
                ->where('hotel_booking_list.booking_source',$booking_source[$data['booking_source']])->where($array)->like(trim($data['key']), trim($data['value']))->orderBy("hotel_booking_list.id","DESC")->paginate(40);
            }
        } else { 
            return  $this->select('hotel_booking_list.webpartner_assign_user,agent.company_name,agent.company_id,hotel_booking_list.api_supplier,hotel_booking_list.booking_source,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.is_manual,hotel_booking_list.webpartner_update_ticket_by,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.wl_agent_id,hotel_booking_list.wl_customer_id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')->join("admin_users","admin_users.id=hotel_booking_list.webpartner_assign_user",'left')->join("agent_users", "agent_users.agent_id=hotel_booking_list.wl_agent_id", 'left')->join('agent', "hotel_booking_list.wl_agent_id = agent.id", 'left')->where("hotel_booking_list.web_partner_id",$web_partner_id)
                ->like(trim($data['key']), trim($data['value'])) ->where('hotel_booking_list.booking_source',$booking_source[$data['booking_source']])->where("hotel_booking_list.web_partner_id",$web_partner_id)->orderBy("hotel_booking_list.id","DESC")->paginate(40);
        }
    }


    public function hotel_booking_detail($web_partner_id,$booking_refrence_number)
    {
        $query = $this->select("hotel_booking_list.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,CONCAT(customer.first_name,' ',customer.last_name) as customer_name,CONCAT(admin_users.first_name,' ',admin_users.last_name) as assign_user_name")
            ->join("agent_users", "agent_users.id=hotel_booking_list.wl_agent_id", 'left')
            ->join("customer", "customer.id=hotel_booking_list.wl_customer_id", 'left')
            ->join('admin_users', "admin_users.id = hotel_booking_list.webpartner_assign_user", 'left')
            ->groupBy('hotel_booking_list.id')
            ->where(["hotel_booking_list.web_partner_id"=>$web_partner_id,"hotel_booking_list.booking_ref_number"=>$booking_refrence_number])
             ->whereIn('hotel_booking_list.booking_source',['Wl_b2c','Wl_b2b'])
            ->get()
            ->getRowArray();
            
        if ($query) { 
            if($query['booking_source'] == "Wl_b2c"){
                $builder = $this->db->table('customer_account_log');
                $builder->select("customer_account_log.*");
                $builder->where(['customer_account_log.booking_ref_no' => $query['id'],"customer_account_log.web_partner_id"=>$web_partner_id, "customer_account_log.service" => "hotel"]);
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $customerBuilder = $this->db->table('customer');
                $customerBuilder->select("customer.customer_id,customer.email_id,customer.mobile_no,customer.first_name,customer.last_name");
                $customerBuilder->where(['customer.web_partner_id' => $query['web_partner_id'], "customer.id" => $query['wl_customer_id']]);
                $query['CustomerInfo'] = $customerBuilder->get()->getRowArray();
            }else{
                $builder = $this->db->table('agent_account_log');
                $builder->select("agent_account_log.*");
                $builder->where(['agent_account_log.booking_ref_no' => $query['id'],"agent_account_log.web_partner_id"=>$web_partner_id, "agent_account_log.service" => "hotel"]);
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('agent');
                $agentBuilder->select("agent.company_name,agent.company_id,agent_users.login_email,agent_users.mobile_no,agent_users.first_name,agent_users.last_name")
                        ->join('agent_users','agent_users.agent_id = agent.id');
                $agentBuilder->where(['agent.web_partner_id' => $query['web_partner_id'], "agent.id" => $query['wl_agent_id']]);
                $query['AgentInfo'] = $agentBuilder->get()->getRowArray();
            }
            $bookingNoteBuilder = $this->db->table('web_partner_booking_notes');
            $bookingNoteBuilder->select("web_partner_booking_notes.comment,web_partner_booking_notes.created,admin_users.first_name,admin_users.last_name,web_partner_booking_notes.add_by")
                ->join("admin_users", "admin_users.id=web_partner_booking_notes.wl_agent_staff_id");
            $bookingNoteBuilder->where(['web_partner_booking_notes.booking_ref_no' => $query['id'], "web_partner_booking_notes.service_type" => "hotel", "web_partner_booking_notes.add_by" => "webpartner"]);
            $query['BookingNotes'] = $bookingNoteBuilder->get()->getResultArray();
        }
        return $query;
    }

    
    function updateData($tableName, $whereClause, $data)
    {
        $this->db->table($tableName)->where($whereClause)->update($data);
    }


    function getData($tableName, $where, $singalRecord = 1, $whereApply = 1, $selectedColumnValue = null)
    {
        $builder = $this->db->table($tableName);

        if ($selectedColumnValue != null) {
            $builder->select($selectedColumnValue);
        }
        if ($whereApply) {
            $builder->where($where);
        }
        if ($singalRecord) {
            return $builder->get()->getRowArray();
        } else {
            return $builder->get()->getResultArray();
        }
    }

    public function amendment_list($web_partner_id, $booking_reference_number){
        return  $this->db->table('hotel_amendment')->select("hotel_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,CONCAT(customer.first_name,' ',customer.last_name) as customer_name")
            ->join("agent_users","agent_users.agent_id=hotel_amendment.wl_agent_id",'left')
            ->join("customer","customer.id=hotel_amendment.wl_customer_id",'left')
            ->where(["hotel_amendment.web_partner_id"=>$web_partner_id,"hotel_amendment.booking_ref_no"=>$booking_reference_number])
            ->get()->getResultArray();
    }
    
    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }
 
    
    function getDataRowType($tableName, $whereCondition, $field)
    {
        $builder = $this->db->table($tableName)->select($field);
        if ($whereCondition) {
            $builder->where($whereCondition);
        }
        return $builder->get()->getRowArray();
    }
    
    
   
    public function web_partner_available_balance($web_partner_id)
    {
        return $this->db->table('web_partner_account_log')->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function agent_user_available_balance($tableName, $key, $id, $web_partner_id)
    {
        return $this->db->table($tableName)->select('balance')->where($key, $id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }


    
    public function search_data_sales_report($web_partner_id,$data, $page)
    {
        $builder = $this->select('hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.agent_fare_break_up,hotel_booking_list.wl_customer_id,hotel_booking_list.wl_agent_id,hotel_booking_list.booking_source,hotel_booking_list.customer_fare_break_up,hotel_booking_list.webpartner_assign_user,hotel_booking_list.api_supplier,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.booking_ref_number,agent.company_name, agent.company_id,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel');
        $builder->join('agent','agent.id = hotel_booking_list.wl_agent_id','left');
        $builder->where("hotel_booking_list.web_partner_id",$web_partner_id);


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

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
        $builder->whereIn('hotel_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->where('hotel_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("hotel_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("hotel_booking_list.id")->orderBy("hotel_booking_list.id", "DESC")->paginate($page);
    }


    public function data_sales_report($web_partner_id,$page)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];

        $builder = $this->select('hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.agent_fare_break_up,hotel_booking_list.wl_customer_id,hotel_booking_list.wl_agent_id,hotel_booking_list.booking_source,hotel_booking_list.customer_fare_break_up,hotel_booking_list.webpartner_assign_user,hotel_booking_list.api_supplier,hotel_booking_list.supplier_booking_id,hotel_booking_list.id,hotel_booking_list.booking_ref_number,agent.company_name, agent.company_id,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel');
        $builder->join('agent','agent.id = hotel_booking_list.wl_agent_id','left');
        $builder->where($array)->where("hotel_booking_list.web_partner_id",$web_partner_id);

        $builder->whereIn('hotel_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->where('hotel_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("hotel_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("hotel_booking_list.id")->orderBy("hotel_booking_list.id", "DESC")->paginate($page);
    }


    public function hotel_booking_info($web_partner_id,$booking_refrence_number,$userId,$userType)
    {
        return  $this->select('hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.hotel_norms,hotel_booking_list.hotel_policy_detail,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_rooms_details,hotel_booking_list.booking_source,hotel_booking_list.agent_fare_break_up,hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.customer_fare_break_up,hotel_booking_list.contact_number,hotel_booking_list.contact_email_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
        ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
        ->where(["hotel_booking_list.web_partner_id"=>$web_partner_id,"hotel_booking_list.booking_ref_number"=>$booking_refrence_number])
        ->get()->getRowArray();
    }
   
 
  

    function updateUserData($tableName,$whereCondition,$updateData)
    {
       return $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }




    
}
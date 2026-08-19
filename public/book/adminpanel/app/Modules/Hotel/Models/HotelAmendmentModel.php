<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelAmendmentModel extends Model
{
    protected $table = 'hotel_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function hotel_amendment_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['hotel_amendment.created >=' => $from_date, 'hotel_amendment.created <=' => $to_date];

        $builder = $this->select('hotel_amendment.*,hotel_amendment.id As AmendmentId,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')

            ->join('agent', "hotel_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.id=hotel_amendment.wl_agent_id", 'left')
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=hotel_amendment.wl_customer_id", 'left')
            ->where("hotel_amendment.web_partner_id", $web_partner_id);

        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "approved") {
            $builder->where(["hotel_amendment.amendment_status" => "approved"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "requested") {
            $builder->where(["hotel_amendment.amendment_status" => "requested"]);
        }

        if ($bookingType == "rejected") {
            $builder->where(["hotel_amendment.amendment_status" => "rejected"]);
        }
        if ($bookingType == "processing") {
            $builder->where(["hotel_amendment.amendment_status" => "processing"]);
        }


        return $builder->groupBy("hotel_amendment.id")->orderBy("hotel_amendment.id", "DESC")->paginate(30);
    }



    function search_data($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $arrayValue = array('lead_passenger_name' => 'hotel_booking_list.lead_passenger_name', 'booking_ref_number' => 'hotel_booking_list.booking_ref_number', 'amendment_type' => 'hotel_amendment.amendment_type', 'booking_status' => 'hotel_booking_list.booking_status', 'amendment_status' => 'hotel_amendment.amendment_status', 'id' => 'hotel_amendment.id');

        $builder = $this->select('hotel_amendment.*,hotel_amendment.id As AmendmentId,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')


            ->join('agent', "hotel_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.id=hotel_amendment.wl_agent_staff_id", 'left')
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("customer", "customer.id=hotel_amendment.wl_customer_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left');
        $builder->where("hotel_amendment.web_partner_id", $web_partner_id);

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['hotel_amendment.created >=' => $from_date, 'hotel_amendment.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['hotel_amendment.wl_agent_id'] = $data['tts_web_partner_info'];
        }
        if ($bookingType == "requested") {
            $array['hotel_amendment.amendment_status'] = "requested";
        }
        if ($bookingType == "approved") {
            $array['hotel_amendment.amendment_status'] = "approved";
        }
        if ($bookingType == "rejected") {
            $array['hotel_amendment.amendment_status'] = "rejected";
        }
        if ($bookingType == "processing") {
            $array['hotel_amendment.amendment_status'] = "processing";
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
        return $builder->groupBy("hotel_amendment.id")->orderBy("hotel_amendment.id", "DESC")->paginate(30);
    }


    function getcurrentcurrencyrates($currency, $web_partner_id)
    {
        return $this->db->table('currency')->select("*")->where(["currency" => $currency, "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }



    public function amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('hotel_amendment');
        $builder = $this->select('hotel_amendment.*,hotel_booking_list.id As hotelBookingid,hotel_booking_list.city, hotel_booking_list.supplier_fare_break_up,hotel_booking_list.conveniencefee, hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.amendment_charges,hotel_booking_list.wl_agent_id,
        hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.booking_currency,hotel_booking_list.currency_rate,hotel_booking_list.default_currency,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.amendment_charges, hotel_booking_list.booking_channel,hotel_booking_list.web_partner_id,hotel_booking_list.wl_customer_id,
        hotel_booking_list.customer_fare_break_up,hotel_booking_list.agent_fare_break_up,hotel_booking_list.booking_source,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.total_price,hotel_booking_list.booking_status,,hotel_booking_list.api_supplier,hotel_booking_list.issue_supplier,
        hotel_booking_list.last_cancellation_date,hotel_booking_list.webpartner_assign_user,hotel_booking_list.amendment_id,
        hotel_booking_list.hotel_name,hotel_booking_list.hotel_rooms_details,hotel_booking_list.booking_ref_number,hotel_booking_list.confirmation_no,hotel_amendment.amendment_type,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name,
        customer.customer_id,customer.email_id as customeremailid,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->where(['hotel_amendment.id' => $amendment_id, 'hotel_amendment.web_partner_id' => $web_partner_id])
            ->join('agent', "hotel_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.agent_id=hotel_amendment.wl_agent_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=hotel_amendment.wl_customer_id", 'left')
            ->join("admin_users", "admin_users.id=hotel_booking_list.webpartner_assign_user", 'left');

        $query = $builder->get()->getRowArray();


        if ($query) {
            if ($query['booking_source'] == "Wl_b2b") {
                $builder = $this->db->table('agent_account_log');
                $builder->select("agent_account_log.id,agent_account_log.acc_ref_number,agent_account_log.invoice_number,agent_account_log.currency_symbol,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.balance,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.extra_param,agent_account_log.action_type,agent_account_log.created");
                $builder->where(['agent_account_log.web_partner_id' => $web_partner_id, "agent_account_log.service" => "hotel"]);
                $builder->where('find_in_set("' . $query['hotelBookingid'] . '", agent_account_log.booking_ref_no) <> 0');
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('agent');  
                $agentBuilder->select("agent.company_name,agent_users.login_email,agent_users.mobile_no,agent_users.first_name,agent_users.last_name,agent.company_id");
                $agentBuilder->join("agent_users", "agent.id = agent_users.agent_id");
                $agentBuilder->where(['agent.web_partner_id' => $query['web_partner_id'], "agent.id" => $query['wl_agent_id']]);
                $query['AgentInfo'] = $agentBuilder->get()->getRowArray();
            }
            if ($query['booking_source'] == "Wl_b2c") {

                $builder = $this->db->table('customer_account_log');
                $builder->select("customer_account_log.id,customer_account_log.acc_ref_number,customer_account_log.invoice_number,customer_account_log.currency_symbol,customer_account_log.debit,customer_account_log.credit,customer_account_log.service,customer_account_log.balance,customer_account_log.remark,customer_account_log.service_log,customer_account_log.transaction_id,customer_account_log.extra_param,customer_account_log.payment_mode,customer_account_log.transaction_type,customer_account_log.action_type,customer_account_log.created");
                $builder->where(['customer_account_log.web_partner_id' => $web_partner_id, "customer_account_log.service" => "hotel"]);
                $builder->where('find_in_set("' . $query['hotelBookingid'] . '", customer_account_log.booking_ref_no) <> 0');

                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('customer');
                $agentBuilder->select("customer.customer_id,customer.email_id,customer.mobile_no,customer.first_name,customer.last_name");
                $agentBuilder->where(['customer.web_partner_id' => $query['web_partner_id'], "customer.id" => $query['wl_customer_id']]);
                $query['CustomerInfo'] = $agentBuilder->get()->getRowArray();
            }
            /* $builder = $this->db->table('visa_booking_travelers');
            $builder->select("visa_booking_travelers.*,CONCAT(visa_booking_travelers.dial_code,' ',visa_booking_travelers.mobile_number) as PHONE_NO,CONCAT(visa_booking_travelers.title,' ',visa_booking_travelers.first_name,' ',visa_booking_travelers.last_name) as NAME");
            $builder->where(['visa_booking_travelers.visa_booking_id' => $query['id']]);
            $query['travellers'] = $builder->get()->getResultArray(); */
        }


        return $query;
    }





    function hotel_amendment_detail_by_id($amendment_id, $web_partner_id)
    {
        $builder = $this->db->table('hotel_amendment');
        $builder->select('hotel_amendment.*,hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.agent_fare_break_up,
        hotel_booking_list.hotel_rooms_details,hotel_booking_list.id as hotelBookingid,hotel_booking_list.booking_ref_number,hotel_booking_list.amendment_charges,
        hotel_booking_list.api_supplier,hotel_booking_list.room_guests,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.customer_fare_break_up,hotel_booking_list.booking_channel,hotel_booking_list.last_voucher_date,
        hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,
        hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,
        hotel_booking_list.room_guests,hotel_booking_list.hotel_norms,hotel_booking_list.hotel_policy_detail,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.created as bookingcreated,hotel_booking_list.contact_email_id,hotel_booking_list.contact_number,
        hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left');
        $builder->where(["hotel_amendment.id" => $amendment_id,"amendment_status" => "approved", "hotel_amendment.web_partner_id" => $web_partner_id]);
        $query = $builder->get()->getRowArray();

        return $query;
    }


    function hotel_booking_by_id($web_partner_id,$booking_id){
        return $this->db->table('hotel_booking_list')->select('id,wl_agent_id,wl_customer_id,web_partner_fare_break_up,amendment_charges,agent_fare_break_up,customer_fare_break_up')->where('web_partner_id',$web_partner_id)->where('id',$booking_id)->where('refund_account_id',NULL)->get()->getRowArray();
    }

    function hotel_amendment_by_id($web_partner_id,$booking_id){
        return $this->db->table('hotel_booking_list')->select('id,wl_agent_id,wl_customer_id,web_partner_fare_break_up,amendment_charges,agent_fare_break_up,customer_fare_break_up')->where('web_partner_id',$web_partner_id)->where('id',$booking_id)->where('refund_account_id',NULL)->where('amendment_id !=',NULL)->get()->getRowArray();
    }


    function agent_user_gst_state_code($tableName, $user_id, $web_partner_id)
    {
        $builder = $this->db->table($tableName);
        $builder->where("id", $user_id);
        $builder->where('web_partner_id', $web_partner_id);
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }





    function updateUserData($tableName, $whereCondition, $updateData)
    {
        return $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }


















    /**
     * hotel_booking_list.id,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_name,hotel_booking_list.booking_ref_number,hotel_booking_list.confirmation_no,hotel_amendment.amendment_type
     * 
     * 
     * 
     */


    public function hotel_refund_list($web_partner_id, $bookingType = 'all', $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['hotel_amendment.refund_date >=' => $from_date, 'hotel_amendment.refund_date <=' => $to_date];

        $builder = $this->select('hotel_amendment.*,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,
        hotel_booking_list.api_supplier, hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.booking_source,
        hotel_booking_list.supplier_booking_id,hotel_booking_list.is_domestic,hotel_booking_list.city,hotel_booking_list.amendment_charges,
        hotel_booking_list.no_of_nights,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.hotel_code,
        hotel_booking_list.no_of_rooms,hotel_booking_list.last_cancellation_date,hotel_booking_list.country_code,hotel_booking_list.hotel_rooms_details,
        hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.hotel_name, hotel_booking_list.booking_channel,
        hotel_booking_list.total_price,hotel_booking_list.confirmation_no,hotel_booking_list.last_voucher_date,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->where(['hotel_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
            ->join('agent', "hotel_amendment.wl_agent_id = agent.id", 'left');
        if ($source != 'dashboard') {
            //$builder->where($array);
        }

        return $builder->whereIn("hotel_amendment.refund_status", array('Open', 'Close'))->orderBy("hotel_amendment.id", "DESC")->paginate(40);
    }


    function search_hotel_refund_list($web_partner_id, $data)
    {
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray = ['hotel_amendment.refund_date >=' => $from_date, 'hotel_amendment.refund_date <=' => $to_date];
        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['hotel_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "hotel_amendment.id", "refund_status" => "hotel_amendment.refund_status", 'confirmation_no' => 'hotel_booking_list.confirmation_no');


        $builder = $this->select('hotel_amendment.*,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,
        hotel_booking_list.api_supplier, hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.booking_source,
        hotel_booking_list.supplier_booking_id,hotel_booking_list.is_domestic,hotel_booking_list.city,hotel_booking_list.amendment_charges,
        hotel_booking_list.no_of_nights,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.hotel_code,
        hotel_booking_list.no_of_rooms,hotel_booking_list.last_cancellation_date,hotel_booking_list.country_code,hotel_booking_list.hotel_rooms_details,
        hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.hotel_name, hotel_booking_list.booking_channel,
        hotel_booking_list.total_price,hotel_booking_list.confirmation_no,hotel_booking_list.last_voucher_date, agent.company_name,agent.company_id,
       CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->where(['hotel_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
            ->join('agent', "hotel_amendment.wl_agent_id = agent.id", 'left')

            ->whereIn("hotel_amendment.refund_status", array('Open', 'Close'));

        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("hotel_amendment.id")->orderBy("hotel_amendment.id", "DESC")->paginate(40);
    }

    public function insertData($tableName,$data){
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }
}



<?php

namespace App\Modules\GroupFlight\Models;

use CodeIgniter\Model;

class GroupFlightAmendmentModel extends Model
{
    protected $table = 'group_flight_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_amendment_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['group_flight_amendment.created >=' => $from_date, 'group_flight_amendment.created <=' => $to_date];

        $builder = $this->select('group_flight_amendment.*,group_flight_booking_list.id as flightBokkingid,group_flight_booking_list.booking_ref_number,group_flight_booking_list.fare_type,group_flight_booking_list.airline_remark,group_flight_booking_list.api_supplier,
        group_flight_booking_list.supplier_booking_id,group_flight_booking_list.journey_type,group_flight_booking_list.origin,group_flight_booking_list.destination,group_flight_booking_list.departure_date,
        group_flight_booking_list.is_domestic,group_flight_booking_list.is_refundable,group_flight_booking_list.validating_airline_code,group_flight_booking_list.payment_status,group_flight_booking_list.booking_status,
        group_flight_booking_list.total_price,group_flight_booking_list.pnr,group_flight_booking_list.last_ticket_date,group_flight_booking_list.booking_channel,group_flight_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(admin_users.first_name," ",admin_users.last_name) as admin_staff_name,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->join("admin_users", "admin_users.id=group_flight_amendment.agent_staff_id", 'left')
            ->join('agent', "group_flight_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.id=group_flight_amendment.wl_agent_staff_id", 'left')
            ->join("group_flight_booking_list", "group_flight_booking_list.id=group_flight_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=group_flight_amendment.wl_customer_id", 'left')
            ->where("group_flight_amendment.web_partner_id", $web_partner_id);



        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "approved") {
            $builder->where(["group_flight_amendment.amendment_status" => "approved"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "requested") {
            $builder->where(["group_flight_amendment.amendment_status" => "requested"]);
        }

        if ($bookingType == "rejected") {
            $builder->where(["group_flight_amendment.amendment_status" => "rejected"]);
        }
        if ($bookingType == "processing") {
            $builder->where(["group_flight_amendment.amendment_status" => "processing"]);
        }


        return $builder->groupBy("group_flight_amendment.id")->orderBy("group_flight_amendment.id", "DESC")->paginate(30);
    }





    function search_data($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $arrayValue = array("booking_ref_number" => "group_flight_booking_list.booking_ref_number", "ticket_number" => "group_flight_booking_travelers.ticket_number", "pnr" => "group_flight_booking_list.pnr", "booking_status" => "group_flight_booking_list.booking_status", "amendment_status" => "flight_amendment.amendment_status", "id" => "flight_amendment.id");

        $builder = $this->select('group_flight_amendment.*,group_flight_booking_list.id as flightBokkingid,group_flight_booking_list.booking_ref_number,group_flight_booking_list.fare_type,group_flight_booking_list.airline_remark,group_flight_booking_list.api_supplier,
        group_flight_booking_list.supplier_booking_id,group_flight_booking_list.journey_type,group_flight_booking_list.origin,group_flight_booking_list.destination,group_flight_booking_list.departure_date,
        group_flight_booking_list.is_domestic,group_flight_booking_list.is_refundable,group_flight_booking_list.validating_airline_code,group_flight_booking_list.payment_status,group_flight_booking_list.booking_status,
        group_flight_booking_list.total_price,group_flight_booking_list.pnr,group_flight_booking_list.last_ticket_date,group_flight_booking_list.booking_channel,group_flight_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(admin_users.first_name," ",admin_users.last_name) as admin_staff_name,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->join("admin_users", "admin_users.id=group_flight_amendment.agent_staff_id", 'left')
            ->join('agent', "group_flight_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.id=group_flight_amendment.wl_agent_staff_id", 'left')
            ->join("customer", "customer.id=group_flight_amendment.wl_customer_id", 'left')
            ->join("group_flight_booking_list", "group_flight_booking_list.id=group_flight_amendment.booking_ref_no", 'left');
        $builder->where("group_flight_amendment.web_partner_id", $web_partner_id);

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['group_flight_amendment.created >=' => $from_date, 'group_flight_amendment.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['group_flight_amendment.wl_agent_id'] = $data['tts_web_partner_info'];
        }
        if ($bookingType == "requested") {
            $array['group_flight_amendment.amendment_status'] = "requested";
        }
        if ($bookingType == "approved") {
            $array['group_flight_amendment.amendment_status'] = "approved";
        }
        if ($bookingType == "rejected") {
            $array['group_flight_amendment.amendment_status'] = "rejected";
        }
        if ($bookingType == "processing") {
            $array['group_flight_amendment.amendment_status'] = "processing";
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
        return $builder->groupBy("group_flight_amendment.id")->orderBy("group_flight_amendment.id", "DESC")->paginate(30);
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }



    function flight_amendment_detail_by_id($amendment_id, $web_partner_id)
    {
        return $this->select("*")->where(["id" => $amendment_id, "amendment_status" => "approved", "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }

    function getcurrentcurrencyrates($currency, $web_partner_id)
    {
        return $this->db->table('currency')->select("*")->where(["currency" => $currency, "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }

    function flight_booking_travelers_detail($flight_booking_id, $selectedPaxId)
    {
        return $this->db->table("group_flight_booking_travelers")->select("*")->where(["flight_booking_id" => $flight_booking_id, "refund_account_id" => null])->whereIn("id", $selectedPaxId)->get()->getResultArray();
    }

    public function agent_user_available_balance($tableName, $key, $user_id, $web_partner_id)
    {
        return $this->db->table($tableName)->select('balance')->where($key, $user_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    function updateWithTableData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }

    public function flight_refund_list($web_partner_id, $bookingType = 'all', $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['group_flight_amendment.refund_date >=' => $from_date, 'group_flight_amendment.refund_date <=' => $to_date];

        $builder = $this->select('group_flight_amendment.*,group_flight_booking_list.id as flightBokkingid,group_flight_booking_list.booking_ref_number,group_flight_booking_list.fare_type,group_flight_booking_list.airline_remark,group_flight_booking_list.api_supplier,
        group_flight_booking_list.supplier_booking_id,group_flight_booking_list.journey_type,group_flight_booking_list.origin,group_flight_booking_list.destination,group_flight_booking_list.departure_date,
        group_flight_booking_list.is_domestic,group_flight_booking_list.is_refundable,group_flight_booking_list.validating_airline_code,group_flight_booking_list.payment_status,group_flight_booking_list.booking_status,group_flight_booking_list.booking_source,
        group_flight_booking_list.total_price,group_flight_booking_list.pnr,group_flight_booking_list.last_ticket_date,group_flight_booking_list.booking_channel,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        ')->where(['group_flight_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=group_flight_amendment.agent_staff_id", 'left')
            ->join("group_flight_booking_list", "group_flight_booking_list.id=group_flight_amendment.booking_ref_no", 'left')
            ->join('agent', "group_flight_amendment.wl_agent_id = agent.id", 'left');

        $builder->whereIn("refund_status", array('Open', 'Close'));

        if ($source != 'dashboard') {
            $builder->where($array);
        }

        return $builder->orderBy("group_flight_amendment.id", "DESC")->paginate(40);
    }

    function agent_user_gst_state_code($tableName, $user_id, $web_partner_id)
    {
        $builder = $this->db->table($tableName);
        $builder->where("id", $user_id);
        $builder->where('web_partner_id', $web_partner_id);
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function get_flight_detail($flight_booking_id, $web_partner_id, $select)
    {
        return $this->db->table('group_flight_booking_list')->select($select)->where('id', $flight_booking_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    function search_flight_refund_list($web_partner_id, $data)
    {
        if ($data['key']) {
            unset($data['from_date'], $data['to_date']);
        }
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            //$Datearray = ['group_flight_amendment.modified >=' => $from_date, 'group_flight_amendment.modified <=' => $to_date];

            $Datearray = ['group_flight_amendment.refund_date >=' => $from_date, 'group_flight_amendment.refund_date <=' => $to_date];
        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['group_flight_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }


        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "group_flight_amendment.id", "refund_status" => "group_flight_amendment.refund_status", 'pnr' => 'group_flight_booking_list.pnr');

        $builder = $this->select('group_flight_amendment.*,group_flight_booking_list.id as flightBokkingid,group_flight_booking_list.booking_ref_number,group_flight_booking_list.fare_type,group_flight_booking_list.airline_remark,group_flight_booking_list.api_supplier,
        group_flight_booking_list.supplier_booking_id,group_flight_booking_list.journey_type,group_flight_booking_list.origin,group_flight_booking_list.destination,group_flight_booking_list.departure_date,
        group_flight_booking_list.is_domestic,group_flight_booking_list.is_refundable,group_flight_booking_list.validating_airline_code,group_flight_booking_list.payment_status,group_flight_booking_list.booking_status,group_flight_booking_list.booking_source,
        group_flight_booking_list.total_price,group_flight_booking_list.pnr,group_flight_booking_list.last_ticket_date,group_flight_booking_list.booking_channel,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        ')->where(['group_flight_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=group_flight_amendment.agent_staff_id", 'left')
            ->join("group_flight_booking_list", "group_flight_booking_list.id=group_flight_amendment.booking_ref_no", 'left')
            ->join('agent', "group_flight_amendment.wl_agent_id = agent.id", 'left');
        $builder->whereIn("refund_status", array('Open', 'Close'));
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {

            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("group_flight_amendment.id")->orderBy("group_flight_amendment.refund_date", "DESC")->paginate(40);
    }

    public function amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_amendment.*,flight_booking_list.id as flightbookingid,flight_booking_list.supplier_id,flight_booking_list.pnr,flight_booking_list.booking_source,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'amendment_charges',flight_booking_travelers.amendment_charges,'agent_fare',flight_booking_travelers.agent_fare,'customer_fare',flight_booking_travelers.customer_fare,'date_of_birth',flight_booking_travelers.date_of_birth,'booking_status',flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name,customer.email_id as customerEmailId,agent_users.login_email as agentEmailId,supplier_users.login_email as supplierEmail");
        $builder->where(['flight_amendment.id' => $amendment_id, 'flight_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('flight_amendment', "flight_amendment.booking_ref_no = flight_booking_list.id");
        $builder->join('admin_users', "admin_users.id = flight_amendment.agent_staff_id", "left");
        $builder->join("agent_users", "agent_users.agent_id=flight_amendment.wl_agent_id", 'Left');
        $builder->join("customer", "customer.id=flight_amendment.wl_customer_id", 'Left');
        $builder->join("supplier_users", "flight_booking_list.supplier_id = supplier_users.supplier_id", 'left');
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        $flightBookingTravelersbuilder = $this->db->table('flight_booking_travelers');
        $query['travellers'] = $flightBookingTravelersbuilder->select("*")->where(['flight_booking_travelers.flight_booking_id' => $query['booking_ref_no']])->get()->getResultArray();
        return $query;
    }


    public function pax_travellers_details($pax_ids)
    {
        $builder = $this->db->table('flight_booking_travelers');
        $builder->select("title,first_name,last_name,ticket_number");
        if (is_array($pax_ids)) {
            $builder->whereIn('flight_booking_travelers.id', $pax_ids);
        } else {
            $builder->where('flight_booking_travelers.id', $pax_ids);
        }
        $builder->groupBy('flight_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return $query;
    }
}

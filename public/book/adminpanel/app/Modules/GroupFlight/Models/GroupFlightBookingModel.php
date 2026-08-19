<?php

namespace App\Modules\GroupFlight\Models;

use CodeIgniter\Model;
use DateTime;

class GroupFlightBookingModel extends Model
{
    protected $table = 'group_flight_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function updateData($tableName, $whereClause, $data)
    {
        return $this->db->table($tableName)->where($whereClause)->update($data);
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    function getCalenderList($web_partner_id, $data)
    {
        if (!array_key_exists("to_date", $data)) {
            $year = date('Y');
        } else {
            $year = date('Y', strtotime($data['to_date']));
        }


        if (!array_key_exists("to_date", $data)) {
            $to_date = new DateTime('now');
            $to_date->modify('last day of this month');
            $to_date = $to_date->format('d M Y');
            $data['to_date'] = $to_date;
        }

        if (!array_key_exists("from_date", $data)) {
            $data['from_date'] = date('d M Y');
        }


        if (!array_key_exists("month", $data)) {
            $data['month'] = date('m');
        }


        $builder = $this->db->table("group_flight_booking_list");

        if (($data['from_date']) && $data['to_date']) {

            $from_date = date('Y-m-d', strtotime($data['from_date']));
            $to_date = date('Y-m-d', strtotime($data['to_date']));

            $array = ['group_flight_booking_list.departure_date >=' => $from_date, 'group_flight_booking_list.departure_date <=' => $to_date, "group_flight_booking_list.trip_indicator" => 1];

            $builder->select("group_flight_booking_list.id,group_flight_booking_list.booking_ref_number,group_flight_booking_list.resultIndex,group_flight_booking_list.origin,group_flight_booking_list.booking_source,group_flight_booking_list.destination,group_flight_booking_list.journey_type,
            group_flight_booking_list.trip_indicator,group_flight_booking_list.book_request,group_flight_booking_list.booking_status,group_flight_booking_list.departure_date,group_flight_booking_list.segments,
            group_flight_booking_list.tts_search_token,group_flight_booking_travelers.title,group_flight_booking_travelers.first_name,group_flight_booking_travelers.last_name,group_flight_booking_list.is_domestic,
                    
            concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal) separator ','), ']') as travelersInfo
            ")
                ->where(['group_flight_booking_list.web_partner_id' => $web_partner_id]);
            $builder->whereIn('group_flight_booking_list.booking_status', ['Confirmed', 'Hold']);
            $builder->where($array);
            $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id", 'Left');

            $builder->orderBy("group_flight_booking_list.departure_date", "ASC");
            $builder->groupBy('group_flight_booking_list.id');
            $query = $builder->get()->getResultArray();
            return $query;
        } elseif ($data['month']) {
            $builder->select("group_flight_booking_list.id,group_flight_booking_list.booking_ref_number,group_flight_booking_list.resultIndex,group_flight_booking_list.origin,group_flight_booking_list.booking_source,group_flight_booking_list.destination,group_flight_booking_list.journey_type,
            group_flight_booking_list.trip_indicator,group_flight_booking_list.book_request,group_flight_booking_list.booking_status,group_flight_booking_list.departure_date,group_flight_booking_list.segments,
            group_flight_booking_list.tts_search_token,group_flight_booking_travelers.title,group_flight_booking_travelers.first_name,group_flight_booking_travelers.last_name,group_flight_booking_list.is_domestic,
                    
            concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal) separator ','), ']') as travelersInfo
            ")->where(['group_flight_booking_list.web_partner_id' => $web_partner_id]);
            $builder->where("MONTH(departure_date) = {$data['month']} AND YEAR(departure_date) = {$year}");
            $builder->where(["group_flight_booking_list.trip_indicator" => 1]);
            $builder->whereIn('group_flight_booking_list.booking_status', ['Confirmed', 'Hold']);
            $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id", 'Left');
            $builder->orderBy("group_flight_booking_list.departure_date", "ASC");
            $builder->groupBy('group_flight_booking_list.id');
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    function getData($tableName, $where, $singalRecord = 1, $whereApply  =  1, $selectedColumnValue  = null)
    {
        $builder   = $this->db->table($tableName);

        if ($selectedColumnValue != null) {
            $builder->select($selectedColumnValue);
        }
        if ($whereApply) {
            $builder->where($where);
        }
        if ($singalRecord) {
            return   $builder->get()->getRowArray();
        } else {
            return   $builder->get()->getResultArray();
        }
    }

    function getDataemail($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }
    function getReturnBookingDetail($web_partner_id, $tts_search_token, $data)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select("flight_booking_list.id,flight_booking_list.trip_indicator,flight_booking_list.segments,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.booking_source,flight_booking_list.destination,flight_booking_list.booking_status,flight_booking_list.departure_date");
        $builder->where(["trip_indicator" => 2, "tts_search_token" => $tts_search_token, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();

        return $query;
    }

    public function agent_user_available_balance($tableName, $key, $user_id, $web_partner_id)
    {
        return $this->db->table($tableName)->select('balance')->where($key, $user_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function flight_booking_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');
        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');
        $array = [' group_flight_booking_list.created >=' => $from_date, ' group_flight_booking_list.created <=' => $to_date];
        $builder = $this->select('group_flight_booking_list.id, group_flight_booking_list.lead_pax, group_flight_booking_list.booking_ref_number,  group_flight_booking_list.booking_currency, group_flight_booking_list.currency_rate,  group_flight_booking_list.webpartner_update_ticket_by, group_flight_booking_list.issue_supplier, group_flight_booking_list.webpartner_assign_user, group_flight_booking_list.is_manual, group_flight_booking_list.update_ticket_by, group_flight_booking_list.fare_type, group_flight_booking_list.airline_remark, group_flight_booking_list.api_supplier, group_flight_booking_list.wl_customer_id, group_flight_booking_list.wl_agent_id,
         group_flight_booking_list.supplier_booking_id, group_flight_booking_list.journey_type, group_flight_booking_list.origin, group_flight_booking_list.booking_source, group_flight_booking_list.destination, group_flight_booking_list.departure_date, group_flight_booking_list.created, group_flight_booking_list.inventory_source,
         group_flight_booking_list.is_domestic, group_flight_booking_list.is_refundable, group_flight_booking_list.validating_airline_code, group_flight_booking_list.payment_status, group_flight_booking_list.booking_status, 
         group_flight_booking_list.total_price, group_flight_booking_list.agent_staff_id, group_flight_booking_list.pnr, group_flight_booking_list.last_ticket_date, group_flight_booking_list.booking_channel, group_flight_booking_list.web_partner_fare_break_up,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')
            ->join('admin_users', "admin_users.id =  group_flight_booking_list.webpartner_assign_user", 'left')
            ->join('agent', " group_flight_booking_list.wl_agent_id = agent.id", 'left')->where(" group_flight_booking_list.web_partner_id", $web_partner_id);
        $builder->whereIn('booking_source', ['Wl_b2b', 'Wl_b2c']);

        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "Processing") {
            $builder->where([" group_flight_booking_list.booking_status" => "Processing"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "Cancelled") {
            $builder->where([" group_flight_booking_list.booking_status" => "Cancelled"])->orWhere([" group_flight_booking_list.booking_status" => "PartialCancelled"]);
        }
        return $builder->groupBy(" group_flight_booking_list.id")->orderBy(" group_flight_booking_list.id", "DESC")->paginate(30);
    }

    function search_bookings($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "group_flight_booking_travelers.first_name", "last_name" => "group_flight_booking_travelers.last_name", "ticket_number" => "ticket_number", "pnr" => "group_flight_booking_list.pnr", "web_partner_fare_break_up" => "group_flight_booking_list.web_partner_fare_break_up", "booking_status" => "group_flight_booking_list.booking_status", "payment_status" => "group_flight_booking_list.payment_status", "booking_currency" => "group_flight_booking_list.booking_currency");
        $bookinSource = ["B2B" => "Wl_b2b", "B2C" => "Wl_b2c"];
        $builder = $this->select('group_flight_booking_list.id,group_flight_booking_list.lead_pax, group_flight_booking_list.booking_currency,group_flight_booking_list.currency_rate, group_flight_booking_list.booking_ref_number,group_flight_booking_list.issue_supplier,group_flight_booking_list.webpartner_update_ticket_by,group_flight_booking_list.webpartner_assign_user,group_flight_booking_list.is_manual,group_flight_booking_list.update_ticket_by,group_flight_booking_list.fare_type,group_flight_booking_list.airline_remark,group_flight_booking_list.api_supplier,group_flight_booking_list.wl_customer_id,group_flight_booking_list.wl_agent_id,
            group_flight_booking_list.supplier_booking_id,group_flight_booking_list.journey_type,group_flight_booking_list.origin,group_flight_booking_list.booking_source,group_flight_booking_list.destination,group_flight_booking_list.departure_date,group_flight_booking_list.created,group_flight_booking_list.inventory_source,
            group_flight_booking_list.is_domestic,group_flight_booking_list.is_refundable,group_flight_booking_list.validating_airline_code,group_flight_booking_list.payment_status,group_flight_booking_list.booking_status, 
            group_flight_booking_list.total_price,group_flight_booking_list.agent_staff_id,group_flight_booking_list.pnr,group_flight_booking_list.last_ticket_date,group_flight_booking_list.booking_channel,group_flight_booking_list.web_partner_fare_break_up,
            agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')
            ->join("agent_users", "agent_users.id=group_flight_booking_list.wl_agent_staff_id", 'left')
            ->join('admin_users', "admin_users.id = group_flight_booking_list.webpartner_assign_user", 'left')
            ->join('agent', "group_flight_booking_list.wl_agent_id = agent.id", 'left');
        if ((isset($data['key']) && $data['key'] == "first_name") || isset($data['key']) && $data['key'] != "last_name") {
            $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id", 'left');
        }
        $builder->where("group_flight_booking_list.web_partner_id", $web_partner_id);
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['group_flight_booking_list.created >=' => $from_date, 'group_flight_booking_list.created <=' => $to_date];
        }
        $builder->whereIn('booking_source', ['Wl_b2b', 'Wl_b2c']);
        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];
        if (isset($data['booking_source']) && $data['booking_source'] != "") {
            $array['group_flight_booking_list.booking_source'] = $bookinSource[$data['booking_source']];
        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['group_flight_booking_list.wl_agent_id'] = $data['tts_web_partner_info'];
        }
        if ($bookingType == "Processing") {
            $array['group_flight_booking_list.booking_status'] = "Processing";
        }
        if ($bookingType == "Cancelled") {
            $array['group_flight_booking_list.booking_status'] = "Cancelled";
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
        return $builder->groupBy("group_flight_booking_list.id")->orderBy("group_flight_booking_list.id", "DESC")->paginate(30);
    }


    public function flight_booking_detail($web_partner_id, $booking_refrence_number, $userId, $userType)
    {
        $builder = $this->db->table('group_flight_booking_list');
        $builder->select("group_flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'ticket_id',group_flight_booking_travelers.ticket_id,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal, 'seat',group_flight_booking_travelers.seat,'fare',group_flight_booking_travelers.fare,'agentfare',group_flight_booking_travelers.agent_fare,'customerfare',group_flight_booking_travelers.customer_fare,'date_of_birth',group_flight_booking_travelers.date_of_birth) separator ','), ']') as travelersInfo,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,CONCAT(admin_users.first_name,' ',admin_users.last_name) as assign_user_name,customer.email_id as customerEmailId,agent_users.login_email as agentEmailId")
            ->join("agent_users", "agent_users.agent_id=group_flight_booking_list.wl_agent_id", 'left')
            ->join("admin_users", "admin_users.id=group_flight_booking_list.agent_staff_id", 'left')
            ->join("customer", "customer.id=group_flight_booking_list.wl_customer_id", 'left');
        $builder->where(['group_flight_booking_list.booking_ref_number' => $booking_refrence_number, 'group_flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id");
        $builder->groupBy('group_flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            if ($query['booking_source'] == "Wl_b2b") {
                $builder = $this->db->table('agent_account_log');
                $builder->select("agent_account_log.id,agent_account_log.acc_ref_number,agent_account_log.currency_symbol, agent_account_log.invoice_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.balance,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.extra_param,agent_account_log.action_type,agent_account_log.created");
                $builder->where(['agent_account_log.web_partner_id' => $web_partner_id, "agent_account_log.service" => "flight"]);
                $builder->where('find_in_set("' . $query['id'] . '", agent_account_log.booking_ref_no) <> 0');
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('agent');
                $agentBuilder->select("agent.company_name,agent_users.login_email,agent_users.mobile_no,agent_users.first_name,agent_users.last_name,agent.company_id");
                $agentBuilder->join("agent_users", "agent.id = agent_users.agent_id");
                $agentBuilder->where(['agent.web_partner_id' => $query['web_partner_id'], "agent.id" => $query['wl_agent_id']]);
                $query['AgentInfo'] = $agentBuilder->get()->getRowArray();
            }
            if ($query['booking_source'] == "Wl_b2c") {
                $builder = $this->db->table('customer_account_log');
                $builder->select("customer_account_log.id,customer_account_log.acc_ref_number,customer_account_log.currency_symbol, customer_account_log.invoice_number,customer_account_log.debit,customer_account_log.credit,customer_account_log.service,customer_account_log.balance,customer_account_log.remark,customer_account_log.service_log,customer_account_log.transaction_id,customer_account_log.extra_param,customer_account_log.payment_mode,customer_account_log.transaction_type,customer_account_log.action_type,customer_account_log.created");
                $builder->where(['customer_account_log.web_partner_id' => $web_partner_id, "customer_account_log.service" => "flight"]);
                $builder->where('find_in_set("' . $query['id'] . '", customer_account_log.booking_ref_no) <> 0');
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('customer');
                $agentBuilder->select("customer.customer_id,customer.email_id,customer.mobile_no,customer.first_name,customer.last_name");
                $agentBuilder->where(['customer.web_partner_id' => $query['web_partner_id'], "customer.id" => $query['wl_customer_id']]);
                $query['CustomerInfo'] = $agentBuilder->get()->getRowArray();
            }
        }
        return $query;
    }

    public function flight_booking_list_sales_report($web_partner_id, $bookingType = "all", $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');
        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');
        $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
        $builder = $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.booking_source,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.wl_customer_id,flight_booking_list.wl_agent_id,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,agent.company_name, agent.company_id,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.web_partner_fare_break_up,flight_booking_list.agent_fare_break_up,flight_booking_list.customer_fare_break_up,
       CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->where($array)->where("flight_booking_list.web_partner_id", $web_partner_id)
            ->join('agent', "flight_booking_list.wl_agent_id = agent.id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left');
        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereIn('flight_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(30);
    }

    function search_bookings_sales_report($web_partner_id, $data, $bookinglistype = "all")
    {
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "flight_booking_travelers.first_name", "last_name" => "flight_booking_travelers.last_name", "ticket_number" => "ticket_number", "pnr" => "flight_booking_list.pnr", "web_partner_fare_break_up" => "flight_booking_list.web_partner_fare_break_up", "booking_status" => "flight_booking_list.booking_status", "payment_status" => "flight_booking_list.payment_status");
        $builder = $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.booking_source,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.wl_customer_id,flight_booking_list.wl_agent_id,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,agent.company_name, agent.company_id,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.web_partner_fare_break_up,flight_booking_list.agent_fare_break_up,flight_booking_list.customer_fare_break_up,
       CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->where("flight_booking_list.web_partner_id", $web_partner_id)
            ->join('agent', "flight_booking_list.wl_agent_id = agent.id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['flight_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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
        $builder->whereIn('flight_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }

    function getBookingConfirmationData($bookingid)
    {
        $builder = $this->db->table("group_flight_booking_list");
        $builder->select("group_flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'validating_airline',group_flight_booking_travelers.validating_airline,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal) separator ','), ']') as travelersInfo");
        $builder->where(['group_flight_booking_list.id' => $bookingid]);
        $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id");
        $builder->groupBy('group_flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $fieldName)
    {
        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.booking_ref_number' => $bookingRefNumber]);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    function get_data($tableName, $where, $fieldName)
    {
        $builder  =  $this->db->table($tableName);
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }

    function get_auth_supplier_account_balance($supplier_id)
    {
        return $this->db->table("supplier_account_log")->select('balance')->where('supplier_id', $supplier_id)->orderBy("id", "DESC")->get()->getRowArray();
    }


    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }


    function getBookingWithVariableFieldNameData($booking_refrence_number, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    public function flight_amendment_itinerary_detail($web_partner_id, $booking_refrence_number)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_booking_list.id,flight_booking_list.pnr,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal,'fare',group_flight_booking_travelers.fare,'date_of_birth',group_flight_booking_travelers.date_of_birth,'booking_status',group_flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    public function amendment_list($web_partner_id, $booking_reference_number, $booking_source)
    {
        if ($booking_source == "Wl_b2b") {
            $result =   $this->db->table('group_flight_amendment')->select("group_flight_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
                ->join("agent_users", "agent_users.agent_id=group_flight_amendment.wl_agent_id", 'left')->where('primary_user', 1)
                ->where(["group_flight_amendment.web_partner_id" => $web_partner_id, "group_flight_amendment.booking_ref_no" => $booking_reference_number])
                ->get()->getResultArray();
        } else if ($booking_source == "Wl_b2c") {
            $result =   $this->db->table('group_flight_amendment')->select("group_flight_amendment.*,CONCAT(customer.first_name,' ',customer.last_name) as staff_name")
                ->join("customer", "customer.id=group_flight_amendment.wl_customer_id", 'left')
                ->where(["group_flight_amendment.web_partner_id" => $web_partner_id, "group_flight_amendment.booking_ref_no" => $booking_reference_number])
                ->get()->getResultArray();
        } else {
            $result = array();
        }
        return $result;
    }

    public function flight_amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('group_flight_booking_list');
        $builder->select("group_flight_amendment.*,group_flight_booking_list.id as flightbookingid,group_flight_booking_list.supplier_id,group_flight_booking_list.pnr,group_flight_booking_list.booking_source,group_flight_booking_list.is_domestic,group_flight_booking_list.tts_search_token,group_flight_booking_list.booking_ref_number,group_flight_booking_list.search_request,group_flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal,'fare',group_flight_booking_travelers.fare,'amendment_charges',group_flight_booking_travelers.amendment_charges,'agent_fare',group_flight_booking_travelers.agent_fare,'customer_fare',group_flight_booking_travelers.customer_fare,'date_of_birth',group_flight_booking_travelers.date_of_birth,'booking_status',group_flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name");
        $builder->where(['group_flight_amendment.id' => $amendment_id, 'group_flight_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('group_flight_amendment', "group_flight_amendment.booking_ref_no = group_flight_booking_list.id");
        $builder->join('admin_users', "admin_users.id = group_flight_amendment.agent_staff_id", "left");
        $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id");
        $builder->groupBy('group_flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    public function amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('group_flight_booking_list');
        $builder->select("group_flight_amendment.*,group_flight_booking_list.id as flightbookingid,group_flight_booking_list.supplier_id,group_flight_booking_list.pnr,group_flight_booking_list.booking_source,group_flight_booking_list.is_domestic,group_flight_booking_list.tts_search_token,group_flight_booking_list.booking_ref_number,group_flight_booking_list.search_request,group_flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal,'fare',group_flight_booking_travelers.fare,'amendment_charges',group_flight_booking_travelers.amendment_charges,'agent_fare',group_flight_booking_travelers.agent_fare,'customer_fare',group_flight_booking_travelers.customer_fare,'date_of_birth',group_flight_booking_travelers.date_of_birth,'booking_status',group_flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name,customer.email_id as customerEmailId,agent_users.login_email as agentEmailId,supplier_users.login_email as supplierEmail");
        $builder->where(['group_flight_amendment.id' => $amendment_id, 'group_flight_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('group_flight_amendment', "group_flight_amendment.booking_ref_no = group_flight_booking_list.id");
        $builder->join('admin_users', "admin_users.id = group_flight_amendment.agent_staff_id", "left");
        $builder->join("agent_users", "agent_users.agent_id=group_flight_amendment.wl_agent_id", 'Left');
        $builder->join("customer", "customer.id=group_flight_amendment.wl_customer_id", 'Left');
        $builder->join("supplier_users", "group_flight_booking_list.supplier_id = supplier_users.supplier_id", 'left');
        $builder->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = group_flight_booking_list.id");
        $builder->groupBy('group_flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        $flightBookingTravelersbuilder = $this->db->table('group_flight_booking_travelers');
        $query['travellers'] = $flightBookingTravelersbuilder->select("*")->where(['group_flight_booking_travelers.flight_booking_id' => $query['booking_ref_no']])->get()->getResultArray();
        return $query;
    }

    public function pax_details($pax_id)
    {
        $builder = $this->db->table('group_flight_booking_travelers');
        $builder->select("title,first_name,last_name,ticket_number");
        $builder->whereIn('group_flight_booking_travelers.id', $pax_id);

        $builder->groupBy('group_flight_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return $query;
    }


    public function pax_travellers_details($pax_ids)
    {
        $builder = $this->db->table('group_flight_booking_travelers');
        $builder->select("title,first_name,last_name,ticket_number");
        if (is_array($pax_ids)) {
            $builder->whereIn('group_flight_booking_travelers.id', $pax_ids);
        } else {
            $builder->where('group_flight_booking_travelers.id', $pax_ids);
        }
        $builder->groupBy('group_flight_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return $query;
    }





    public function flight_booking_list_fare_detail($fare_detail_id)
    {
        return $this->select("flight_booking_list.login_user,flight_booking_list.id,flight_booking_list.booking_currency,flight_booking_list.currency_rate,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,flight_booking_list.segments,flight_booking_list.is_manual,flight_booking_list.webpartner_assign_user,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.inventory_source,flight_booking_list.booking_source,flight_booking_list.wl_agent_id,flight_booking_list.lead_pax,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.is_lcc,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.wl_customer_id,
        CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name,CONCAT(b.first_name,' ',b.last_name) as login_user_name,
        web_partner.company_name,web_partner.company_id,concat('[', group_concat(JSON_OBJECT('id', group_flight_booking_travelers.id,'booking_status',group_flight_booking_travelers.booking_status,'ticket_number',group_flight_booking_travelers.ticket_number,'title',group_flight_booking_travelers.title,'first_name',group_flight_booking_travelers.first_name,'last_name',group_flight_booking_travelers.last_name,'pax_type',group_flight_booking_travelers.pax_type,'gendar',group_flight_booking_travelers.gendar,'date_of_birth',group_flight_booking_travelers.date_of_birth,'pan_number',group_flight_booking_travelers.pan_number,'passport_number',group_flight_booking_travelers.passport_number,'passport_expiry',group_flight_booking_travelers.passport_expiry,'lead_pax',group_flight_booking_travelers.lead_pax,'email_id',group_flight_booking_travelers.email_id,'mobile_number',group_flight_booking_travelers.mobile_number,'address_1',group_flight_booking_travelers.address_1,'address_2',group_flight_booking_travelers.address_2,'city',group_flight_booking_travelers.city,'country_code',group_flight_booking_travelers.country_code,'country_name',group_flight_booking_travelers.country_name,'ff_airline',group_flight_booking_travelers.ff_airline,'ff_number',group_flight_booking_travelers.ff_number,'baggage',group_flight_booking_travelers.baggage,'meal',group_flight_booking_travelers.meal) separator ','), ']') as travelersInfo")->where('fare_crs_details_id', $fare_detail_id)
            ->join("admin_users", "admin_users.id=flight_booking_list.agent_staff_id", 'left')
            ->join('super_admin_users b', "flight_booking_list.login_user = b.id", 'left')
            ->join('group_flight_booking_travelers', "group_flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left')
            ->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }
}

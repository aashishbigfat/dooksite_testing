<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class AmendmentModel extends Model
{
    protected $table = 'cruise_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function amendment_list($web_partner_id,$bookingType = 'all', $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['cruise_amendment.created >=' => $from_date, 'cruise_amendment.created <=' => $to_date];


        $builder = $this->select('cruise_amendment.*,cruise_booking_list.id as Booking_id,cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,
        cruise_booking_list.booking_status,cruise_booking_list.created as booking_created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.sailing_date,web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name, CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name')
            ->join('super_admin_users', 'super_admin_users.id = cruise_amendment.sup_staff_id', 'Left')
            ->join("admin_users", "admin_users.id=cruise_amendment.agent_staff_id", 'left')
            ->join("cruise_booking_list", "cruise_booking_list.id=cruise_amendment.booking_ref_no", 'left')
            ->join('web_partner', "cruise_amendment.web_partner_id = web_partner.id", 'left')->where('cruise_amendment.web_partner_id',$web_partner_id);
        if ($source != 'dashboard') {
            $builder->where($array);
        }


        return $builder->orderBy("cruise_amendment.id", "DESC")->paginate(40);;
    }

    function search_bookings($data,$web_partner_id)
    { pr($web_partner_id);exit;
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray = ['cruise_amendment.created >=' => $from_date, 'cruise_amendment.created <=' => $to_date];
        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['cruise_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }
        $builder =   $this->select('cruise_amendment.*,cruise_booking_list.id as Booking_id,cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,
        cruise_booking_list.booking_status,cruise_booking_list.created as booking_created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.sailing_date,web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name')
            ->join('super_admin_users', 'super_admin_users.id = cruise_amendment.sup_staff_id', 'Left')
            ->join("admin_users", "admin_users.id=cruise_amendment.agent_staff_id", 'left')
            ->join("cruise_booking_list", "cruise_booking_list.id=cruise_amendment.booking_ref_no", 'left')
            ->join('web_partner', "cruise_amendment.web_partner_id = web_partner.id", 'left')
            ->where('cruise_amendment.web_partner_id',$web_partner_id);
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($data['key']) && $data['key'] != "") {
            $builder->like(trim($data['key']), trim($data['value']));
        }
        return $builder->groupBy("cruise_amendment.id")->orderBy("cruise_amendment.id", "DESC")->paginate(40);
    }


    function updateData($updateData, $Where)
    {
        return $this->select("*")->where($Where)->set($updateData)->update();
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    function updateWithTableData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }

    public function amendment_detail($amendment_id,$web_partner_id)
    {
        $builder = $this->db->table('cruise_amendment');
        $builder = $this->select('cruise_amendment.*,cruise_booking_list.id as Booking_id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,cruise_booking_list.no_of_travellers,
        cruise_booking_list.booking_status,cruise_booking_list.created as booking_created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.sailing_date, web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,cruise_booking_list.super_admin_fare_break_up,cruise_booking_list.amendment_charges,
        CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,')
            ->join('super_admin_users', 'super_admin_users.id = cruise_amendment.sup_staff_id', 'Left')
            ->join("admin_users", "admin_users.id=cruise_amendment.agent_staff_id", 'left')
            ->join("cruise_booking_list", "cruise_booking_list.id=cruise_amendment.booking_ref_no", 'left')
            ->join('web_partner', "cruise_amendment.web_partner_id = web_partner.id", 'left')->where('cruise_amendment.web_partner_id',$web_partner_id);
        $builder->where(['cruise_amendment.id' => $amendment_id]);
        $query = $builder->get()->getRowArray();
        if ($query) {
            $builder = $this->db->table('cruise_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=cruise_booking_list.id");
            $builder->where(['cruise_booking_list.id' => $query['Booking_id'], "web_partner_account_log.service" => "cruise"]);
            $query['paymentInfo'] = $builder->get()->getResultArray();
            $flightBookingTravelersbuilder = $this->db->table('cruise_booking_travelers');
            $query['travelersInfo'] = $flightBookingTravelersbuilder->select("*")->where(['cruise_booking_travelers.cruise_booking_id' => $query['Booking_id']])->get()->getResultArray();
        }
        return $query;
    }

    function amendment_detail_by_id($amendment_id,$web_partner_id)
    {
        $builder = $this->db->table('cruise_amendment');
        return $builder->select("*")->where(["id" => $amendment_id,"amendment_status" => "approved","web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }



    function webpartner_gst_state_code($web_partner_id)
    {
        $builder = $this->db->table('web_partner');
        $builder->where("id", $web_partner_id);
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    public function refund_list($web_partner_id, $bookingType = 'all', $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['cruise_amendment.created >=' => $from_date, 'cruise_amendment.created <=' => $to_date];

        $builder =  $this->select('cruise_amendment.*,cruise_booking_list.id as Booking_id,
       
       cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,
        cruise_booking_list.booking_status,cruise_booking_list.created as booking_created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.sailing_date,
       
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
            ->join('super_admin_users', 'super_admin_users.id = cruise_amendment.sup_staff_id', 'Left')
            ->join("admin_users", "admin_users.id=cruise_amendment.agent_staff_id", 'left')
            ->join("cruise_booking_list", "cruise_booking_list.id=cruise_amendment.booking_ref_no", 'left')
            ->join('web_partner', "cruise_amendment.web_partner_id = web_partner.id", 'left')->where(['cruise_amendment.web_partner_id'=>$web_partner_id])
            ->whereIn("refund_status", array('Open', 'Close'));
        if ($source != 'dashboard') {
            $builder->where($array);
        }

        return $builder->orderBy("cruise_amendment.id", "DESC")->paginate(40);


    }

    function search_refund_list($data,$web_partner_id)
    {
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray = ['cruise_amendment.modified >=' => $from_date, 'cruise_amendment.modified <=' => $to_date];

        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['cruise_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }


        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "cruise_amendment.id", "refund_status" => "cruise_amendment.refund_status", 'pnr' => 'flight_booking_list.pnr');
        $builder =  $this->select('cruise_amendment.*,cruise_booking_list.id as Booking_id,
       
       cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,
        cruise_booking_list.booking_status,cruise_booking_list.created as booking_created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.sailing_date,
       
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
            ->join('super_admin_users', 'super_admin_users.id = cruise_amendment.sup_staff_id', 'Left')
            ->join("admin_users", "admin_users.id=cruise_amendment.agent_staff_id", 'left')
            ->join("cruise_booking_list", "cruise_booking_list.id=cruise_amendment.booking_ref_no", 'left')
            ->join('web_partner', "cruise_amendment.web_partner_id = web_partner.id", 'left')->where('cruise_amendment.web_partner_id',$web_partner_id);
        $builder->whereIn("refund_status", array('Open', 'Close'));
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {
            $builder->groupStart();
            $builder->where($Datearray);
            $Datearray = ['cruise_amendment.refund_date >=' => $from_date, 'cruise_amendment.refund_date <=' => $to_date];
            $builder->orWhere($Datearray);
            $builder->groupEnd();
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("cruise_amendment.id")->orderBy("cruise_amendment.id", "DESC")->paginate(40);
    }

    public function web_partner_available_balance($web_partner_id)
    {
        return $this->db->table('web_partner_account_log')->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }


    function get_list_by_table_name($table,$field,$where)
    {
        $builder = $this->db->table($table);
        return $builder->select($field)->where($where)->get()->getRowArray();
    }

}



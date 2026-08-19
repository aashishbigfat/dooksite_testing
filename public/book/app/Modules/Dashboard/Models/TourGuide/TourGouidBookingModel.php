<?php

namespace App\Modules\Dashboard\Models\TourGuide;


use CodeIgniter\Model;

class TourGouidBookingModel extends Model
{
    protected $table = 'tourguide_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function tour_guide_booking_list_all($web_partner_id, $wl_customer_id, $userType)
    {

        return $this->select('id, booking_ref_number, guide_name, tour_guide_email_id, city, language_known, tour_guide_country, tour_guide_info, monument_info, payment_status, booking_source, web_partner_id, monument_title, monument_rating, monument_duration, monument_city, payment_mode, booking_status, total_price, travel_date, start_time, end_time, total_adult, total_child, title, assign_user, first_name, last_name, mobile_number, email_id, special_request, web_partner_fare_break_up,created,booking_currency,currency_rate,default_currency')
            ->where('web_partner_id', $web_partner_id)
            ->where('wl_customer_id', $wl_customer_id)
            ->where('booking_source', $userType)
            ->orderBy("tourguide_booking_list.id", "DESC")->paginate(40);
    }


    public function tour_guide_booking_upcomming_list($web_partner_id, $wl_customer_id, $userType)
    {
        $date = date('Y-m-d');
        return $this->select('tourguide_booking_list.*')
            ->where(" tourguide_booking_list.travel_date > ", $date)
            ->where([
                'tourguide_booking_list.web_partner_id' => $web_partner_id,
                'tourguide_booking_list.wl_customer_id' => $wl_customer_id,
                'tourguide_booking_list.booking_source' => $userType
            ])
            ->orderBy("tourguide_booking_list.id", "DESC")->paginate(40);
    }

    public function tour_guide_booking_cancel_list_all($web_partner_id, $wl_customer_id, $userType)
    {

        return $this->select('tourguide_booking_list.*')
            ->whereIn('tourguide_booking_list.booking_status', ['Cancelled', 'PartialCancelled'])
            ->where([
                'tourguide_booking_list.web_partner_id' => $web_partner_id,
                'tourguide_booking_list.wl_customer_id' => $wl_customer_id,
                'tourguide_booking_list.booking_source' => $userType
            ])
            ->orderBy("tourguide_booking_list.id", "DESC")->paginate(40);
    }




    function updateData($tableName, $whereClause, $data)
    {
        $this->db->table($tableName)->where($whereClause)->update($data);
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    function getDataRowType($tableName, $whereCondition, $field)
    {
        $builder =  $this->db->table($tableName)->select($field);
        if ($whereCondition) {
            $builder->where($whereCondition);
        }
        return $builder->get()->getRowArray();
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


    public function getBookingDetailData($ref_id, $web_partner_id)
    {
        $builder = $this->db->table('tourguide_booking_list');
        $builder->select("tourguide_booking_list.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users", "admin_users.id=tourguide_booking_list.agent_staff_id", 'left');
        $builder->where(['tourguide_booking_list.booking_ref_number' => $ref_id, 'tourguide_booking_list.web_partner_id' => $web_partner_id]);

        $builder->groupBy('tourguide_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $builder = $this->db->table('tourguide_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created,tourguide_booking_list.booking_currency,tourguide_booking_list.currency_rate,tourguide_booking_list.default_currency")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=tourguide_booking_list.id");
            $builder->where(['tourguide_booking_list.booking_ref_number' => $ref_id, 'tourguide_booking_list.web_partner_id' => $web_partner_id, "web_partner_account_log.service" => "tourguide"]);
            $query['paymentInfo'] = $builder->get()->getResultArray();
        }
        return  $query;
    }


    public function amendment_list($web_partner_id)
    {
        return $this->db->table('tourguide_amendment')
            ->select('tourguide_amendment.*, tourguide_booking_list.id as Booking_id, tourguide_booking_list.guide_name, tourguide_booking_list.guide_type, tourguide_booking_list.booking_ref_number, tourguide_booking_list.payment_status,
            tourguide_booking_list.booking_status, tourguide_booking_list.created as booking_created, tourguide_booking_list.total_price, tourguide_booking_list.web_partner_fare_break_up, tourguide_booking_list.travel_date, tourguide_booking_list.booking_source, tourguide_booking_list.monument_duration,
            CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->where('tourguide_amendment.web_partner_id', $web_partner_id)
            ->join("admin_users", "admin_users.id = tourguide_amendment.wl_customer_id", 'left')
            ->join("tourguide_booking_list", "tourguide_booking_list.id = tourguide_amendment.booking_ref_no", 'left')
            ->orderBy("tourguide_amendment.id", "DESC")
            ->get()
            ->getResultArray();
    }
}

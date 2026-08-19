<?php

namespace App\Modules\Dashboard\Models\Visa;

use CodeIgniter\Model;

class VisaModel extends Model
{
    protected $table = 'visa_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function visa_booking_list_all($web_partner_id, $wl_customer_id, $userType)
    {
        return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')->where(['visa_booking_list.web_partner_id' => $web_partner_id, 'visa_booking_list.wl_customer_id' => $wl_customer_id, 'visa_booking_list.booking_source' => $userType])->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
            ->orderBy("visa_booking_list.id", "DESC")->paginate(40);
    }




    public function visa_booking_upcomming_list($web_partner_id, $wl_customer_id, $userType)
    {
        $date = date('d-m-Y');
        return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')
            ->where(['visa_booking_list.web_partner_id' => $web_partner_id, 'visa_booking_list.wl_customer_id' => $wl_customer_id, 'visa_booking_list.booking_source' => $userType, 'visa_booking_list.booking_status' => 'Confirmed'])
            ->where('visa_booking_list.date_of_journey >=', $date)
            ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
            ->orderBy("visa_booking_list.id", "DESC")->paginate(40);
    }


    public function visa_booking_cancelled_list($web_partner_id, $wl_customer_id, $userType)
    {

        return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')
            ->whereIn('visa_booking_list.booking_status', ['Cancelled', 'PartialCancelled'])
            ->where(['visa_booking_list.web_partner_id' => $web_partner_id, 'visa_booking_list.wl_customer_id' => $wl_customer_id, 'visa_booking_list.booking_source' => $userType])

            ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
            ->orderBy("visa_booking_list.id", "DESC")->paginate(40);
    }





    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }


    function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name')->get()->getResultArray();
    }


    function insertBatchData($tableName, $insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }


    function getData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }

    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }


    function getBookingConfirmationData($bookingid, $web_partner_id)
    {

        $builder = $this->db->table('visa_booking_list');
        $builder->select("visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up");
        $builder->where(['visa_booking_list.id' => $bookingid, 'visa_booking_list.web_partner_id' => $web_partner_id]);

        $builder->groupBy('visa_booking_list.id');
        $query = $builder->get()->getRowArray();

        //get all travellers
        $builder = $this->db->table('visa_booking_list');
        $builder->select("visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,visa_booking_travelers.gendar,visa_booking_travelers.dob,");
        $builder->where(['visa_booking_list.id' => $bookingid, 'visa_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('visa_booking_travelers', "visa_booking_travelers.visa_booking_id = visa_booking_list.id");
        $query['travellers'] = $builder->get()->getResultArray();

        return $query;
    }

    function getBookingDetailData($ref, $web_partner_id)
    {

        $builder = $this->db->table('visa_booking_list');
        $builder->select("visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up,visa_booking_list.tts_search_token,visa_booking_list.booking_channel,visa_booking_list.date_of_journey,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name");
        $builder->where(['visa_booking_list.booking_ref_number' => $ref, 'visa_booking_list.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=visa_booking_list.agent_staff_id", 'left');
        $builder->groupBy('visa_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {

            $builder = $this->db->table('visa_booking_list');
            $builder->select("visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
       visa_booking_travelers.gendar,visa_booking_travelers.dob,visa_booking_travelers.lead_pax,
        ");
            $builder->where(['visa_booking_list.id' => $query['id'], 'visa_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('visa_booking_travelers', "visa_booking_travelers.visa_booking_id = visa_booking_list.id");
            $query['travellers'] = $builder->get()->getResultArray();

            $builder = $this->db->table('visa_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=visa_booking_list.id");
            $builder->where(['visa_booking_list.booking_ref_number' => $ref, 'visa_booking_list.web_partner_id' => $web_partner_id, 'web_partner_account_log.service' => 'visa']);
            $query['paymentInfo'] = $builder->get()->getResultArray();



            $query['email'] = '';
            $query['mobile_no'] = '';
        }
        return $query;
    }





    function search_data($data, $web_partner)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['visa_booking_list.created >=' => $from_date, 'visa_booking_list.created <=' => $to_date];

                return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')->where("visa_booking_list.web_partner_id", $web_partner)
                    ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
                    ->where($array)->orderBy("visa_booking_list.id", "DESC")->paginate(40);
            } else {
                $array = ['visa_booking_list.created >=' => $from_date, 'visa_booking_list.created <=' => $to_date];
                return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')

                    ->where("visa_booking_list.web_partner_id", $web_partner)
                    ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("visa_booking_list.id", "DESC")->paginate(40);
            }
        } else {


            return $this->select('visa_booking_list.id, visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.no_of_travellers,visa_booking_list.processing_time,visa_booking_list.booking_ref_number,visa_booking_list.payment_status,
        visa_booking_list.booking_status,visa_booking_list.created,visa_booking_list.total_price,visa_booking_list.web_partner_fare_break_up, visa_booking_list.date_of_journey,visa_booking_list.tts_search_token,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,visa_booking_list.default_currency,visa_booking_list.booking_currency,visa_booking_list.currency_rate,
        visa_booking_travelers.gendar,visa_booking_travelers.dob,
        ')

                ->where("visa_booking_list.web_partner_id", $web_partner)
                ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("visa_booking_list.id", "DESC")->paginate(40);
        }
    }

    function getBookingWithVariableFieldNameData($bookingid, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("visa_booking_list");
        $builder->select($fieldName);
        $builder->where(['visa_booking_list.id' => $bookingid, 'visa_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("visa_booking_list");
        $builder->select($fieldName);
        $builder->where(['visa_booking_list.booking_ref_number' => $bookingRefNumber, 'visa_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function super_admin_booking_pre_fix_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('pre_fix');
        return $builder->get()->getRowArray();
    }
    public function amendment_list($web_partner_id, $booking_reference_number)
    {
        return $this->db->table('visa_amendment')->select("visa_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users", "admin_users.id=visa_amendment.agent_staff_id", 'left')
            ->where(["visa_amendment.web_partner_id" => $web_partner_id, "visa_amendment.booking_ref_no" => $booking_reference_number])
            ->get()->getResultArray();
    }
}

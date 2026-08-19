<?php

namespace App\Modules\Dashboard\Models\Holiday;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table = 'holiday_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_booking_list_all($web_partner_id,$wl_customer_id,$userType)
    {

        return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
            ->where(['holiday_booking_list.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_customer_id'=>$wl_customer_id,'holiday_booking_list.booking_source'=>$userType])
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
    }

    public function holiday_booking_upcomming_list($web_partner_id,$wl_customer_id,$userType)
    {
        $year = date('Y');
        $month = date('m');



        return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
            ->where("MONTH(holiday_booking_list.travel_date) = {$month} AND YEAR(holiday_booking_list.travel_date) >= {$year}")
            ->where(['holiday_booking_list.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_customer_id'=>$wl_customer_id,'holiday_booking_list.booking_source'=>$userType,'holiday_booking_list.booking_status'=>'Confirmed'])

            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
    }


    public function holiday_booking_cancelled_list($web_partner_id,$wl_customer_id,$userType)
    {
        return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
            ->whereIn('holiday_booking_list.booking_status',['Cancelled','PartialCancelled'])
            ->where(['holiday_booking_list.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_customer_id'=>$wl_customer_id,'holiday_booking_list.booking_source'=>$userType])

            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
    }


    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function super_admin_booking_pre_fix_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('pre_fix');
        return $builder->get()->getRowArray();
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

    function getBookingConfirmationData($bookingid, $web_partner_id)
    {

        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up");
        $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id]);

        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();

        //get all travellers
        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
        holiday_booking_travelers.pax_type,holiday_booking_travelers.gendar,holiday_booking_travelers.age,holiday_booking_travelers.child_need_bed,holiday_booking_travelers.pan_number,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_travelers.passport_number,holiday_booking_travelers.child_need_bed");
        $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id");

        $query['travellers'] = $builder->get()->getResultArray();

        return $query;
    }

    function getBookingWithVariableFieldNameData($bookingid, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.booking_ref_number' => $bookingRefNumber, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }







    function getBookingDetailData($ref, $web_partner_id)
    {

        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.tts_search_token,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.booking_channel,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name");
        $builder->where(['holiday_booking_list.booking_ref_number' => $ref, 'holiday_booking_list.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=holiday_booking_list.agent_staff_id", 'left');
        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $query['package_details'] = json_decode($query['package_details'], true);
            //get all travellers
            $builder = $this->db->table('holiday_booking_list');
            $builder->select("holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate,
            holiday_booking_travelers.pax_type,holiday_booking_travelers.gendar,holiday_booking_travelers.age,holiday_booking_travelers.child_need_bed,holiday_booking_travelers.pan_number,holiday_booking_travelers.lead_pax,
            holiday_booking_travelers.passport_number,holiday_booking_travelers.child_need_bed");
            $builder->where(['holiday_booking_list.id' => $query['id'], 'holiday_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id");
            $query['travellers'] = $builder->get()->getResultArray();

            $builder = $this->db->table('holiday_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created,holiday_booking_list.default_currency,holiday_booking_list.booking_currency,holiday_booking_list.currency_rate")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=holiday_booking_list.id");
            $builder->where(['holiday_booking_list.booking_ref_number' => $ref, 'holiday_booking_list.web_partner_id' => $web_partner_id,'web_partner_account_log.service'=>'holiday']);
            $query['paymentInfo'] = $builder->get()->getResultArray();



            $query['email'] ='';
            $query['mobile_no'] ='';
        }
        return $query;
    }

    public function amendment_list($web_partner_id, $booking_reference_number){
        $result =   $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users","admin_users.id=holiday_amendment.agent_staff_id",'left')
            ->where(["holiday_amendment.web_partner_id"=>$web_partner_id,"holiday_amendment.booking_ref_no"=>$booking_reference_number,'amendment_id'=>null])
            ->get()->getResultArray();

        if ($result){
            foreach ($result as $key=>$data){
                    $reply_data = HolidayModel::amendment_list_reply($web_partner_id, $booking_reference_number, $data['id']);
                    $result[$key]['admin_reply'] = $reply_data;
            }
        }

        return $result;
    }

    public function amendment_list_reply($web_partner_id, $booking_reference_number,$amendment_id){
        return  $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users","admin_users.id=holiday_amendment.agent_staff_id",'left')
            ->where(["holiday_amendment.web_partner_id"=>$web_partner_id,"holiday_amendment.booking_ref_no"=>$booking_reference_number,'amendment_id'=>$amendment_id])
            ->get()->getResultArray();
    }

}



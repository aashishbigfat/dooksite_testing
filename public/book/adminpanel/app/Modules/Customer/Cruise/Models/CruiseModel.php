<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseModel extends Model
{
    protected $table = 'cruise_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    function getBookingDetailData($ref)
    {
        //cruise_booking_list.confirmation_no,
        $builder = $this->db->table('cruise_booking_list');
        $builder->select("cruise_booking_list.id, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.no_of_travellers,cruise_booking_list.no_of_nights,cruise_booking_list.booking_ref_number,cruise_booking_list.payment_status,cruise_booking_list.confirmation_no,
        cruise_booking_list.booking_status,cruise_booking_list.created,cruise_booking_list.total_price,cruise_booking_list.web_partner_fare_break_up,cruise_booking_list.tts_search_token,cruise_booking_list.departure_port,cruise_booking_list.sailing_date,cruise_booking_list.assign_user,cruise_booking_list.web_partner_id,
        
        CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name");
        $builder->where(['cruise_booking_list.booking_ref_number' => $ref])
            ->join("admin_users", "admin_users.id=cruise_booking_list.agent_staff_id", 'left');
        $builder->groupBy('cruise_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {

            $builder = $this->db->table('cruise_booking_list');
            $builder->select("cruise_booking_travelers.email_id,cruise_booking_travelers.mobile_number,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,
           cruise_booking_travelers.gendar,cruise_booking_travelers.dob,cruise_booking_travelers.lead_pax,cruise_booking_travelers.passport_no,cruise_booking_travelers.passport_expiry_date");
            $builder->where(['cruise_booking_list.id' => $query['id']]);
            $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id");
            $query['travellers'] = $builder->get()->getResultArray();

            $builder = $this->db->table('cruise_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=cruise_booking_list.id");
            $builder->where(['cruise_booking_list.booking_ref_number' => $ref, 'web_partner_account_log.service'=>'cruise']);
            $query['paymentInfo'] = $builder->get()->getResultArray();



            $query['email'] ='';
            $query['mobile_no'] ='';
        }
        return $query;
    }

    function getData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function updateUserData($tableName,$whereCondition,$updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

}
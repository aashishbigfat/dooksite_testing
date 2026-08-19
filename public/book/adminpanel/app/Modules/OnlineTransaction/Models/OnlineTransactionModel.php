<?php

namespace App\Modules\OnlineTransaction\Models;

use CodeIgniter\Model;


class OnlineTransactionModel extends Model
{
    protected $table = 'web_partner_payment_transaction';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function online_transaction_list($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['web_partner_payment_transaction.created >=' => $from_date, 'web_partner_payment_transaction.created <=' => $to_date];

        return $this->select('web_partner_payment_transaction.transaction_id,web_partner_payment_transaction.order_id,web_partner_payment_transaction.booking_prefix,web_partner_payment_transaction.payment_mode,web_partner_payment_transaction.convenience_fee,
        web_partner_payment_transaction.created,web_partner_payment_transaction.id,web_partner_payment_transaction.customer_name,web_partner_payment_transaction.payment_source,web_partner_payment_transaction.mobile_number,web_partner_payment_transaction.email_id,web_partner_payment_transaction.payment_getway_name,web_partner_payment_transaction.booking_ref_number,
        web_partner_payment_transaction.payment_status,web_partner_payment_transaction.service,web_partner_payment_transaction.amount,
        web_partner_payment_transaction.booking_ref_no,agent.company_name,agent_users.login_email,CONCAT(customer.first_name," ",customer.last_name) as customer_user_name, CONCAT(admin_users.first_name, " ", admin_users.last_name) AS Staffname , customer.id as customer_id, web_partner_payment_transaction.web_partner_remark')
            ->join('agent', 'agent.id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('agent_users', 'agent_users.agent_id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('admin_users', "admin_users.id = web_partner_payment_transaction.web_partner_user_id", 'left')
            ->join('customer', 'customer.id = web_partner_payment_transaction.wl_customer_id', 'Left')->where($array)
            ->where(["web_partner_payment_transaction.web_partner_id" => $web_partner_id])
            ->orderBy('web_partner_payment_transaction.id', 'DESC')->paginate(40);

    }

 


    public function transaction_details($id,$web_partner_id)
    {
        return $this->select('web_partner_payment_transaction.transaction_id,web_partner_payment_transaction.web_partner_id,web_partner_payment_transaction.payment_mode,web_partner_payment_transaction.payment_mode,web_partner_payment_transaction.order_id,web_partner_payment_transaction.booking_prefix,web_partner_payment_transaction.convenience_fee,web_partner_payment_transaction.payment_request,web_partner_payment_transaction.payment_response,
        web_partner_payment_transaction.created,web_partner_payment_transaction.id,web_partner_payment_transaction.customer_name,web_partner_payment_transaction.payment_source,web_partner_payment_transaction.mobile_number,web_partner_payment_transaction.email_id,web_partner_payment_transaction.payment_getway_name,web_partner_payment_transaction.booking_ref_number,
        web_partner_payment_transaction.payment_status,web_partner_payment_transaction.service,web_partner_payment_transaction.amount,
        web_partner_payment_transaction.booking_ref_no,agent.company_name,agent.company_id,admin_users.first_name,admin_users.last_name,admin_users.login_email,CONCAT(customer.first_name," ",customer.last_name) as customer_user_name,web_partner_payment_transaction.web_partner_remark')
            ->join('agent', 'agent.id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('admin_users', 'admin_users.id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('customer', 'customer.id = web_partner_payment_transaction.wl_customer_id', 'Left')
            ->where("web_partner_payment_transaction.id", $id)
            ->where(["web_partner_payment_transaction.web_partner_id" => $web_partner_id])
            ->orderBy('web_partner_payment_transaction.id', 'DESC')->get()->getRowArray();
    }


 

    function search_data($data,$web_partner_id)
    {

        $arrayValue = array("transaction_id" => "web_partner_payment_transaction.transaction_id", "order_id" => "web_partner_payment_transaction.order_id", "service" => "web_partner_payment_transaction.service", "payment_status" => "web_partner_payment_transaction.payment_status");
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray =  ['web_partner_payment_transaction.created >=' => $from_date, 'web_partner_payment_transaction.created <=' => $to_date];
        } else {
            $from_date = strtotime(date('Y-m-d', strtotime("-15 days")) . '00:00');
            $to_date = strtotime(date('Y-m-d') . '23:59');
            $Datearray =  ['web_partner_payment_transaction.created >=' => $from_date, 'web_partner_payment_transaction.created <=' => $to_date];
        }

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['web_partner_payment_transaction.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if (isset($data['status']) && $data['status'] != "") {
            $array['web_partner_payment_transaction.payment_status'] = $data['status'];
        }

        if (isset($data['service']) && $data['service'] != "") {
            $array['web_partner_payment_transaction.service'] = $data['service'];
        }

      
        $builder =  $this->select('web_partner_payment_transaction.transaction_id,web_partner_payment_transaction.order_id,web_partner_payment_transaction.booking_prefix,web_partner_payment_transaction.payment_mode,web_partner_payment_transaction.convenience_fee,
            web_partner_payment_transaction.created,web_partner_payment_transaction.id,web_partner_payment_transaction.customer_name,web_partner_payment_transaction.payment_source,web_partner_payment_transaction.mobile_number,web_partner_payment_transaction.email_id,web_partner_payment_transaction.payment_getway_name,web_partner_payment_transaction.booking_ref_number,
            web_partner_payment_transaction.payment_status,web_partner_payment_transaction.service,web_partner_payment_transaction.amount,
            web_partner_payment_transaction.booking_ref_no,agent.company_name,admin_users.login_email,CONCAT(admin_users.first_name, " ", admin_users.last_name) AS Staffname ,CONCAT(customer.first_name," ",customer.last_name) as customer_user_name,customer.id as customer_id, web_partner_payment_transaction.web_partner_remark')
            ->join('agent', 'agent.id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('admin_users', 'admin_users.id = web_partner_payment_transaction.wl_agent_id', 'Left')
            ->join('customer', 'customer.id = web_partner_payment_transaction.wl_customer_id', 'Left')
            ->where(["web_partner_payment_transaction.web_partner_id" => $web_partner_id]);
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($data['key']) && $data['key'] != "") {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        if (isset($data['export_excel']) && $data['export_excel'] == 1) {
            return $builder->orderBy('web_partner_payment_transaction.id', 'DESC')->get()->getResultArray();
        } else {
            return $builder->orderBy('web_partner_payment_transaction.id', 'DESC')->paginate(40);
        }
    }


    public function online_transaction_list_dashboard($per_page)
    {
        return $this->select('super_admin_payment_transaction.transaction_id,super_admin_payment_transaction.convenience_fee,super_admin_payment_transaction.booking_prefix,super_admin_payment_transaction.order_id,
        super_admin_payment_transaction.created,super_admin_payment_transaction.id,
        super_admin_payment_transaction.customer_name,super_admin_payment_transaction.mobile_number,super_admin_payment_transaction.email_id,super_admin_payment_transaction.payment_getway_name,
        super_admin_payment_transaction.payment_status,super_admin_payment_transaction.service,super_admin_payment_transaction.amount,super_admin_users.login_email as super_admin_login_email,
        super_admin_payment_transaction.booking_ref_no,web_partner.company_name,web_partner.company_id,admin_users.login_email,super_admin_users.first_name,super_admin_users.last_name,super_admin_payment_transaction.super_admin_remark')
            ->join('web_partner', 'web_partner.id = super_admin_payment_transaction.web_partner_id', 'Left')
            ->join('admin_users', 'admin_users.id = super_admin_payment_transaction.user_id', 'Left')
            ->join('super_admin_users', 'super_admin_users.id = super_admin_payment_transaction.super_admin_user_id', 'Left')
            ->orderBy('super_admin_payment_transaction.id', 'DESC')->paginate($per_page);
    }


    public function GetAvailableBalance($tableName, $selectColumn, $whereCondition, $orderBy, $limit,$web_partner_id)
    {
        $query = $this->db->table($tableName)
            ->select($selectColumn)->where(["$tableName.web_partner_id" => $web_partner_id]);;

        if ($whereCondition) {
            $query->where($whereCondition);
        }

        if ($orderBy) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->getRowArray();
    }

 
    function InsertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    function updateData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }
}

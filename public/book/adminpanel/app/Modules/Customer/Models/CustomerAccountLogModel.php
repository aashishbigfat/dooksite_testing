<?php

namespace App\Modules\Customer\Models;

use CodeIgniter\Model;

class CustomerAccountLogModel extends Model
{
    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($customer_id, $web_partner_id)
    {
        return $this->select('id,customer_id,credit,debit,balance,remark,created')->where('customer_id', $customer_id)->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->paginate(10);
    }



    public function account_logs($customer_id, $web_partner_id, $searchdata = array())
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('customer')->select('id,first_name,last_name,title,customer_id');
        $subquery = $subquery->getCompiledSelect();
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');


        $array = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        if ((isset($searchdata['from_date']) && $searchdata['from_date'] != "") && (isset($searchdata['to_date']) && $searchdata['to_date'] != "")) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        }
        if (isset($searchdata['search-text']) && $searchdata['key-value'] != "") {
            $customer_id = $searchdata['key-value'];
        }

        if (isset($searchdata['key']) && $searchdata['key'] != '') {
            if ($searchdata['key'] == 'invoice_number') {
                $array['customer_account_log.invoice_number'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'service') {
                $array['customer_account_log.service'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'credit') {
                $array['customer_account_log.transaction_type'] = 'credit';
                $array['customer_account_log.credit'] = $searchdata['value'];
            }
            if ($searchdata['key'] == 'balance') {
                $array['customer_account_log.balance'] = 'balance';
            }

            if ($searchdata['key'] == 'debit') {
                $array['customer_account_log.transaction_type'] = 'debit';
                $array['customer_account_log.debit'] = $searchdata['value'];
            }
        }
        $array['customer_account_log.web_partner_id'] = $web_partner_id;
        $array['customer_account_log.customer_id'] = $customer_id;
        return $this->select('customer_account_log.*,CONCAT(customer.title," ",customer.first_name," ",customer.last_name) as customerName,customer.customer_id,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = customer_account_log.user_id', 'Left')
            ->join("($subquery) customer", 'customer.id = customer_account_log.customer_id', 'Left')->where($array)
            ->orderBy("customer_account_log.id", "DESC")->paginate(40);
    }

    public function available_balance($customer_id, $web_partner_id)
    {
        return $this->select('balance')->where('customer_id', $customer_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();

    }

    public function customer_account_log($id, $web_partner_id)
    {

        return $this->select('*')->where('web_partner_id', $web_partner_id)->where("customer_id", $id)->delete();

    }
    public function servicesId($servicename, $booking_ref_number, $web_partner_id)
    {
        $tableName = $servicename . "_booking_list";
        return $this->db->table($tableName)->select('id')->where("booking_ref_number", $booking_ref_number)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }
    public function view_remark_detail($id, $web_partner_id)
    {
        $array['customer_account_log.web_partner_id'] = $web_partner_id;
        $array['customer_account_log.id'] = $id;
        return $this->select('customer_account_log.customer_id,customer_account_log.id,customer_account_log.transaction_type,customer_account_log.web_partner_id,customer_account_log.remark,customer_account_log.extra_param,customer_account_log.payment_mode,customer_account_log.transaction_id,customer_account_log.service_log,customer_account_log.action_type,customer_account_log.service,customer_account_log.invoice_number,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = customer_account_log.user_id', 'Left')
            ->where($array)->get()->getRowArray();
    }
}
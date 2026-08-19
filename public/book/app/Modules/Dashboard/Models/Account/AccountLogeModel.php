<?php

namespace App\Modules\Dashboard\Models\Account;

use CodeIgniter\Model;

class AccountLogeModel extends Model
{

    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function agent_account_list_all($web_partner_id, $wl_customer_id,)
    {
        return  $this->select(' id,acc_ref_number,booking_ref_no,invoice_number,action_type,transaction_type,credit,debit,balance,payment_mode,created,currency_symbol,currency,convertion_rate')
            ->where(['web_partner_id' => $web_partner_id,'customer_id' =>$wl_customer_id,])->orderBy("id", "DESC")->paginate(10);
    }
    
    public function view_remark_detail($id, $web_partner_id)
    {
        $array['customer_account_log.web_partner_id'] = $web_partner_id;
        $array['customer_account_log.id'] = $id;
        return $this->select('customer_account_log.id,customer_account_log.transaction_type,customer_account_log.web_partner_id,customer_account_log.remark,customer_account_log.extra_param,customer_account_log.payment_mode,customer_account_log.transaction_id,customer_account_log.service_log,customer_account_log.action_type,customer_account_log.service,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = customer_account_log.user_id', 'Left')
            ->where($array)->get()->getRowArray();
    }

}

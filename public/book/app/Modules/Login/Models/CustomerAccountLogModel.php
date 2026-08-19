<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class CustomerAccountLogModel extends Model
{
    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($web_partner_id,$wl_customer_id)
    {
        return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created')
            ->where('web_partner_id', $web_partner_id)->where('customer_id',$wl_customer_id)->orderBy("id","DESC")->paginate(40);
    }

    public function account_logs($web_partner_id,$wl_customer_id)
    {
        return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created')
            ->where('web_partner_id', $web_partner_id)->where('customer_id',$wl_customer_id)->orderBy("id","DESC")->paginate(40);
    }

    public function available_balance($web_partner_id,$wl_customer_id){
        return  $this->select('balance')->where('web_partner_id', $web_partner_id)->where('customer_id',$wl_customer_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}



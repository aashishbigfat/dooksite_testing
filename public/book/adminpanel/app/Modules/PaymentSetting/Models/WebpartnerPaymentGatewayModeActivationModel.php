<?php

namespace App\Modules\PaymentSetting\Models;

use CodeIgniter\Model;

class WebpartnerPaymentGatewayModeActivationModel extends Model
{
    protected $table = 'web_partner_payment_gateway_mode_activation';
    protected $primaryKey = 'id';
    protected $protectFields = false;


    public function getPaymentGatewayMode($web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id'=>$web_partner_id,'activation_status'=>'active'])->orderBy("id", "DESC")->paginate(40);
    }

    public function edit_payment_gateway_mode($id, $web_partner_id)
    {
        return $this->db->table("web_partner_payment_gateway_mode_activation")
            ->select('payment_gateway,payment_mode,remarks,status,credentials')->where('id', $id)->where(['web_partner_id'=>$web_partner_id,'activation_status'=>'active'])
            ->get()->getRowArray();
    }



    public function getPaymentGatewayMode_status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->db->table("web_partner_payment_gateway_mode_activation")->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id,'activation_status'=>'active'])->set($data)->update();
    }
}

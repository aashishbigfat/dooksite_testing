<?php

namespace App\Modules\PaymentGateway\Models;

use CodeIgniter\Model;


class PaymentModel extends Model
{
    protected $table = 'web_partner_payment_gateway_mode_activation';
    protected $primarykey = 'id';
    protected $protectFields = false;

 
    function gatewayList($web_partner_id)
    {
        return $this->select('id, payment_gateway, payment_mode, status, remarks, created, modified')
            ->where(['web_partner_id' => $web_partner_id])
            ->paginate(30);
    }


    function gatewayDetail($id)
    {
        return $this->select('*')->where("id", $id)->get()->getRowArray();
    }
}
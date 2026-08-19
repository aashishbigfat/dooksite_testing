<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class CustomerModel extends Model
{
    protected $table = 'customer';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function customer_detail($web_partner_id,$id)
    {
        return $this->select('*')->where("web_partner_id", $web_partner_id)->where("id", $id)->get()->getRowArray();
    }

    public function customer_check_old_password($web_partner_id,$id,$password)
    {
        return $this->select('*')->where("web_partner_id", $web_partner_id)->where("id", $id)->where('password',$password)->get()->getRowArray();
    }


}



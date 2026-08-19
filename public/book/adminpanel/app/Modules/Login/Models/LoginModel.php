<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'admin_users';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function InsertData($data){
        $this->db->table("temp_otp_logs")->insert($data);
        return $this->db->insertId();
    }

    public function getData($id){
        return $this->db->table("temp_otp_logs")->select('web_partner_id,username,otp,otp_expiery')->where(['id'=>$id,'btype'=>"Admin",'service'=>"Login"])->get()->getRowArray();
    }

    public function DeleteData($id){
        return $this->db->table("temp_otp_logs")->where('id',$id)->delete();
    }

    public function updateUserDetail($id,$data){
        return $this->db->table("admin_users")->where('id',$id)->update($data);
    }
    
}
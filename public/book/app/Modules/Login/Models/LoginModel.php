<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'customer';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function gst_state_list()
    {
        return $this->db->table('gst_city')->select('gst_state_name,gst_state_code')->get()->getResultArray();
    }

    public function country_list()
    {
        return $this->db->table('countries')->select('id,name')->get()->getResultArray();
    }

    public function state_list($country_id)
    {
        return $this->db->table('states')->select('id,name')->where('country_id',$country_id)->get()->getResultArray();
    }

    public function city_list($state_id)
    {
        return $this->db->table('cities')->select('id,name')->where('state_id',$state_id)->get()->getResultArray();
    }


    public function country_list_row($country_id)
    {
        return $this->db->table('countries')->select('id,name')->where('id',$country_id)->get()->getRowArray();
    }

    public function state_list_row($state_id)
    {
        return $this->db->table('states')->select('id,name')->where('id',$state_id)->get()->getRowArray();
    }

    public function city_list_row($city_id)
    {
        return $this->db->table('cities')->select('id,name')->where('id',$city_id)->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }


    function deleteData($tableName,$id)
    {
        return $this->db->table($tableName)->where("id",$id)->delete();

    }
    public function verify_otp($data)
    {
        $OTPVerfity = $this->db->table("temp_otp_logs")->where(['otp' => $data['OTP'], "btype" => $data['BType'], "service" => $data['Service']])->get()->getRowArray();
        return $OTPVerfity;
    }


    public function googleusercheck($email, $google_token, $oauth_provider, $web_partner_id) {
        $emailExists = $this->db->table("customer")
                                ->select('*')
                                ->where("email_id", $email)
                                ->get()
                                ->getRowArray();
                             
        if (empty($emailExists)) {
            return $this->db->table("customer")
                            ->select('*')
                            ->where(["google_token" => $google_token, "oauth_provider" => $oauth_provider, "web_partner_id" => $web_partner_id])
                            ->get()
                            ->getRowArray();
        } else {
           return $emailExists;
        }
    }


    
}


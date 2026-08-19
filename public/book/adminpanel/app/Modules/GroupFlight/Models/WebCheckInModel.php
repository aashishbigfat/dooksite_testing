<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class WebCheckInModel extends Model
{
    protected $table = 'web_check_in';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function web_check_in_list($web_partner_id)
    {
        return $this->select('id,web_partner_id,airline_name,airline_code,image,url,created,modified')->where(['web_partner_id' => $web_partner_id])->paginate(40);
    }


    public function web_check_in_details($id, $web_partner_id)
    {
        return $this->select('id,web_partner_id,airline_name,airline_code,image,url,created,modified')->where("id", $id)->where(['web_partner_id' => $web_partner_id])->get()->getRowArray();
    }

    public function CheckUniqueurl($value, $web_partner_id)
    {
        return $this->db->table("web_check_in")->select('*')->where(['url' => $value, 'web_partner_id' => $web_partner_id])->get()->getResultArray();
    }
    public function CheckUniqueairlinename($airline_name, $web_partner_id)
    {
        return $this->db->table("web_check_in")->select('*')->where(['airline_name' => $airline_name, 'web_partner_id' => $web_partner_id])->get()->getResultArray();
    }

    function search_data($data, $web_partner_id)
    {

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,web_partner_id,airline_name,airline_code,image,url,created,modified')->orderBy('id', 'DESC')->where($array)
                    ->where(['web_partner_id' => $web_partner_id])->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,web_partner_id,airline_name,airline_code,image,url,created,modified')->orderBy('id', 'DESC')->where($array)
                    ->where(['web_partner_id' => $web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('id,web_partner_id,airline_name,airline_code,image,url,created,modified')->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->where(['web_partner_id' => $web_partner_id])->paginate(40);
        }
    }

    public function remove_web_check_in($id)
    {
        return $this->select('*')->where("id", $id)->delete();
    }

    public function delete_image($id, $web_partner_id)
    {
        return $this->select('image')->where("id", $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }
}



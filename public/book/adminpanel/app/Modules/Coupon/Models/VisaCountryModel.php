<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class VisaCountryModel extends Model
{
    protected $table = 'visa_country_list';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function get_country_code($web_partner_id)
    {
        return  $this->select('id as CountryId,country_code,country_name as CountryName')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

    public function visa_country_list($web_partner_id)
    {
        return  $this->select('id,country_name,country_code,country_image,processing_time,starting_price,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

    public function country_details($id,$web_partner_id)
    {
        return  $this->select('id,country_name,country_code,country_image,processing_time,starting_price,status')->where(['web_partner_id'=>$web_partner_id])->where("id",$id)->get()->getRowArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('country_image')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }


    public function remove_country($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,country_name,country_code,country_image,processing_time,starting_price,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,country_name,country_code,country_image,processing_time,starting_price,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('id,country_name,country_code,country_image,processing_time,starting_price,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }
}
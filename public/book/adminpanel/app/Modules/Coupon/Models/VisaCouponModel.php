<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class VisaCouponModel extends Model   
{
    protected $table = 'coupon_visa';
    protected $primarykey = 'id';
    protected $protectFields = false;


  


    public function visa_coupon_list($web_partner_id)
    {
        return $this->table('coupon_visa')
        ->select('coupon_visa.*, visa_country_list.country_name')
        ->join('visa_country_list', 'coupon_visa.visa_country_id = visa_country_list.id', 'left')
        ->where('coupon_visa.web_partner_id', $web_partner_id)
        ->orderBy('visa_country_list.id', 'DESC')
        ->paginate(40);
    }

    public function visa_coupon_details_list($id,$web_partner_id)
    {
        return $this->table('coupon_visa')
        ->select('coupon_visa.*, visa_country_list.country_name')
        ->join('visa_country_list', 'coupon_visa.visa_country_id = visa_country_list.id', 'left')
        ->where('coupon_visa.web_partner_id', $web_partner_id)
        ->where('coupon_visa.id', $id)
        ->orderBy('visa_country_list.id', 'DESC')
        ->get()->getRowArray();
    }



    public function remove_coupon($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('coupon_visa.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("coupon_visa.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('coupon_visa.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("coupon_visa.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('coupon_visa.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("coupon_visa.id","DESC")->paginate(40);


        }
    }

    public function visa_type_list($web_partner_id)
    {
        return  $this->db->table("visa_type")->select('id,visa_title')->where('web_partner_id',$web_partner_id)->orderBy("id","DESC")->get()->getResultArray();
    }

    public function getCouponCode($code,$web_partner_id){
        return $this->select('coupon_visa.id')->where('code',$code)->where('web_partner_id',$web_partner_id)->get()->getResultArray();
    }
}
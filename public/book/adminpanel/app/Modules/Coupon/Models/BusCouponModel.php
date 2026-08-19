<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class BusCouponModel extends Model
{
    protected $table = 'coupon_bus';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function discount_list($web_partner_id)
    {
        return $this->select('coupon_bus.*')->where('web_partner_id',$web_partner_id)

            ->orderBy("id", "DESC")->paginate(40);
    }

    public function remove_discount($ids,$web_partner_id)
    {
        return $this->select('*')->whereIn("id", $ids)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function discount_details($id)
    {
        return $this->select('coupon_bus.*')->where('coupon_bus.id', $id)
            ->orderBy("id", "DESC")->get()->getRowArray();
    }

    public function getCouponCode($code,$web_partner_id){
        return $this->select('id')->where('code',$code)->where('web_partner_id',$web_partner_id)->get()->getResultArray();
    }


    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['coupon_bus.created >=' => $from_date, 'coupon_bus.created <=' => $to_date];
                return $this->select('coupon_bus.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_bus.id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['coupon_bus.created >=' => $from_date, 'coupon_bus.created <=' => $to_date];
                return $this->select('coupon_bus.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_bus.id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('coupon_bus.*')->where('web_partner_id',$web_partner_id)
                ->orderBy('coupon_bus.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }


    public function bus_coupon_detail_list($id,$web_partner_id)
    {
        return $this->select('coupon_bus.*')->where('id',$id)->where('web_partner_id',$web_partner_id)

            ->orderBy("id", "DESC")->get()->getRowArray();
    }

}



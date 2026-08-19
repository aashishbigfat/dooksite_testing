<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class HotelCouponModel extends Model
{
    protected $table = 'coupon_hotel';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function discount_list($web_partner_id)
    {
        return $this->select('coupon_hotel.*')->where('web_partner_id',$web_partner_id)

            ->orderBy("id", "DESC")->paginate(40);
    }

    public function remove_discount($ids,$web_partner_id)
    {
        return $this->select('*')->whereIn("id", $ids)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    public function discount_details($id)
    {
        return $this->select('coupon_hotel.*')->where('coupon_hotel.id', $id)
            ->orderBy("id", "DESC")->get()->getRowArray();
    }

    function getCouponCode($code,$web_partner_id){
        return $this->select('id')->where('code',$code)->where('web_partner_id',$web_partner_id)->get()->getResultArray();
    }

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['coupon_hotel.created >=' => $from_date, 'coupon_hotel.created <=' => $to_date];
                return $this->select('coupon_hotel.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_hotel.id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['coupon_hotel.created >=' => $from_date, 'coupon_hotel.created <=' => $to_date];
                return $this->select('coupon_hotel.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_hotel.id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('coupon_hotel.*')->where('web_partner_id',$web_partner_id)
                ->orderBy('coupon_hotel.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }



    public function hotel_coupon_detail_list($id,$web_partner_id)
    {
        return $this->select('coupon_hotel.*')->where('id',$id)->where('web_partner_id',$web_partner_id)

            ->orderBy("id", "DESC")->get()->getRowArray();
    }


}



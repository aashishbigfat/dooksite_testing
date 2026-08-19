<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class FlightCouponModel extends Model
{
    protected $table = 'coupon_flight';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function discount_list($web_partner_id)
    {
        return $this->select('coupon_flight.*')->where('web_partner_id',$web_partner_id)

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

    public function getCouponCode($code,$web_partner_id){
        return $this->select('id')->where('code',$code)->where('web_partner_id',$web_partner_id)->get()->getResultArray();
    }

    public function discount_details($id)
    {

        return $this->select('coupon_flight.*')->where('coupon_flight.id', $id)

            ->orderBy("id", "DESC")->get()->getRowArray();

    }


    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['coupon_flight.created >=' => $from_date, 'coupon_flight.created <=' => $to_date];
                return $this->select('coupon_flight.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_flight.id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['coupon_flight.created >=' => $from_date, 'coupon_flight.created <=' => $to_date];
                return $this->select('coupon_flight.*')->where('web_partner_id',$web_partner_id)

                    ->orderBy('coupon_flight.id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('coupon_flight.*')->where('web_partner_id',$web_partner_id)
                ->orderBy('coupon_flight.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }



    public function coupon_flight_detail($id,$web_partner_id)
    {
        return $this->select('coupon_flight.*')->where('id',$id)->where('web_partner_id',$web_partner_id)

            ->orderBy("id", "DESC")->get()->getRowArray();
    }

}



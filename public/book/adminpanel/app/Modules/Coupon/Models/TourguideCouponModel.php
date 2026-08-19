<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class TourguideCouponModel extends Model   
{
    protected $table = 'coupon_tour_guide';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function tourguide_coupon_list($web_partner_id)
    {
        return  $this->select('coupon_tour_guide.*' )->where('web_partner_id',$web_partner_id)

            ->orderBy("coupon_tour_guide.id","DESC")->paginate(40);
    }

    public function tourguide_coupon_details($id,$web_partner_id)
    {
        return  $this->select('coupon_tour_guide.*')->where("coupon_tour_guide.id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
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

                return  $this->select('coupon_tour_guide.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("coupon_tour_guide.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('coupon_tour_guide.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("coupon_tour_guide.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('coupon_tour_guide.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("coupon_tour_guide.id","DESC")->paginate(40);


        }
    }
}
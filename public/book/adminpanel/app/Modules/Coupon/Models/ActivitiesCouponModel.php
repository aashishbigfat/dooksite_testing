<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class ActivitiesCouponModel extends Model   
{
    protected $table = 'coupon_activitiy';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function activities_coupon_list($web_partner_id)
    {
        return  $this->select('coupon_activitiy.*' )->where('web_partner_id',$web_partner_id)

            ->orderBy("coupon_activitiy.id","DESC")->paginate(40);
    }

    public function activities_markup_details($id,$web_partner_id)
    {
        return  $this->select('coupon_activitiy.*')->where("coupon_activitiy.id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
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

                return  $this->select('coupon_activitiy.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("coupon_activitiy.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('coupon_activitiy.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("coupon_activitiy.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('coupon_activitiy.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("coupon_activitiy.id","DESC")->paginate(40);


        }
    }


    public function activities_coupon_detail_list($id,$web_partner_id)
    {
        return  $this->select('coupon_activitiy.id,coupon_activitiy.destination_name,coupon_activitiy.activities_name,coupon_activitiy.categories_name,coupon_activitiy.code,coupon_activitiy.use_limit,coupon_activitiy.valid_from,coupon_activitiy.valid_to,coupon_activitiy.coupon_desc,coupon_activitiy.coupon_visible,coupon_activitiy.activity_date_from,coupon_activitiy.activity_date_to,coupon_activitiy.created,coupon_activitiy.status,coupon_activitiy.max_limit,coupon_activitiy.value,coupon_activitiy.coupon_type' )->where('id',$id)->where('web_partner_id',$web_partner_id)

            ->orderBy("coupon_activitiy.id","DESC")->get()->getRowArray();
    }
}
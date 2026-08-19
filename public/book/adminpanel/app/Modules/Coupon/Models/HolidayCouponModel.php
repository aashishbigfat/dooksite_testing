<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class HolidayCouponModel extends Model   
{
    protected $table = 'coupon_holiday';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holidaycoupon_list($web_partner_id)
    {
        return  $this->select('coupon_holiday.*' )->where('web_partner_id',$web_partner_id)

            ->orderBy("coupon_holiday.id","DESC")->paginate(40);
    }

    
    public function holiday_coupon_details($id,$web_partner_id)
    {
        return  $this->select('coupon_holiday.*' )->where('id',$id)->where('web_partner_id',$web_partner_id)

            ->orderBy("coupon_holiday.id","DESC")->get()->getRowArray();
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

    
    function getDataArray($tableName, $where, $singalRecord = 1, $whereApply = 1, $selectedColumnValue = null)
    {
        $builder = $this->db->table($tableName);

        if ($selectedColumnValue != null) {
            $builder->select($selectedColumnValue);
        }
        if ($whereApply) {
            $builder->where($where);
        }
        if ($singalRecord) {
            return $builder->get()->getRowArray();
        } else {
            return $builder->get()->getResultArray();
        }
    }

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select(' coupon_holiday.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy(" coupon_holiday.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('coupon_holiday.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("coupon_holiday.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('coupon_holiday.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("coupon_holiday.id","DESC")->paginate(40);
        }
    }
}
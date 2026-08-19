<?php

namespace App\Modules\Coupon\Models;
use CodeIgniter\Model;

class CouponLogModel extends Model
{

    protected $table = 'coupon_log';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function couponlog_list($web_partner_id)
    {
        return $this->select('coupon_log.*')->where('web_partner_id',$web_partner_id)->orderBy("id", "DESC")->paginate(40);
    }

    public function search_data($data ,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['coupon_log.created >=' => $from_date, 'coupon_log.created <=' => $to_date];
                return $this->select('coupon_log.*')->where('web_partner_id',$web_partner_id) ->orderBy('coupon_log.id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['coupon_log.created >=' => $from_date, 'coupon_log.created <=' => $to_date];
                return $this->select('coupon_log.*')->where('web_partner_id',$web_partner_id) ->orderBy('coupon_log.id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('coupon_log.*')->where('web_partner_id',$web_partner_id)->orderBy('coupon_log.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }
}

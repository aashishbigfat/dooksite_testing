<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class UmrahCouponModel extends Model
{
    protected $table = 'coupon_umrah_package as cup';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function umrahcoupon_list($web_partner_id, $search_data)
    {
        $where_array = [];
        $like_array = [];

        if (!empty($search_data)) {
            if (!empty($search_data['from_date'])) {
                $from_date = strtotime(date('Y-m-d', strtotime($search_data['from_date'])) . '00:00');
                $where_array['cup.created >= '] = $from_date;
            }
            if (!empty($search_data['to_date'])) {
                $to_date = strtotime(date('Y-m-d', strtotime($search_data['to_date'])) . '23:59');
                $where_array['cup.created <= '] = $to_date;
            }
            if (!empty($search_data['key']) && $search_data['key'] != 'date-range') {
                $like_array[trim($search_data['key'])] = trim($search_data['value']);
            }
        }

        $builder = $this->select('cup.*')->where('web_partner_id', $web_partner_id);

        if (!empty($where_array)) {
            $builder->where($where_array);
        }

        if (!empty($like_array)) {
            $builder->like($like_array);
        }

        return $builder->orderBy("cup.id", "DESC")->paginate(40);
    }



    public function umrah_coupon_details($id, $web_partner_id)
    {
        return $this->select('cup.*')->where('id', $id)->where('web_partner_id', $web_partner_id)
            ->orderBy("cup.id", "DESC")->get()->getRowArray();
    }


    public function remove_coupon($id, $web_partner_id)
    {
        return $this->select('*')->whereIn("id", $id)->where('web_partner_id', $web_partner_id)->delete();
    }

    public function status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id', $web_partner_id)->set($data)->update();
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


}

<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class CarCouponModel extends Model
{
    protected $table = 'coupon_car';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function couponList($web_partner_id, $search_data)
    {
        $where_array = [];
        $like_array = [];

        if (!empty($search_data)) {
            if (!empty($search_data['from_date'])) {
                $from_date = strtotime(date('Y-m-d', strtotime($search_data['from_date'])) . '00:00');
                $where_array['created >= '] = $from_date;
            }
            if (!empty($search_data['to_date'])) {
                $to_date = strtotime(date('Y-m-d', strtotime($search_data['to_date'])) . '23:59');
                $where_array['created <= '] = $to_date;
            }

            if (!empty($search_data['key']) && $search_data['key'] != 'date-range') {
                $like_array[trim($search_data['key'])] = trim($search_data['value']);
            }
        }

        $builder = $this->select('coupon_car.*')
            ->where(["web_partner_id" => $web_partner_id]);

        if (!empty($where_array)) {
            $builder->where($where_array);
        }

        if (!empty($like_array)) {
            $builder->like($like_array);
        }

        return $builder->orderBy("coupon_car.id", "DESC")
            ->paginate(40);
    }


    public function getCouponCode($code, $web_partner_id)
    {
        return $this->select('id')
            ->where(['code' => $code, 'web_partner_id' => $web_partner_id])
            ->get()->getResultArray();
    }


    public function insertData($insertData)
    {
        $this->db->table('coupon_car')->insert($insertData);

        $isInserted = $this->db->affectedRows();
        if ($isInserted) {
            return $this->db->insertID();
        }
        return false;
    }


    public function car_coupon_detail_list($id, $web_partner_id)
    {
        return $this->select('coupon_car.*')
            ->where(['web_partner_id' => $web_partner_id, "id" => $id])
            ->get()->getRowArray();
    }


    public function status_change($ids, $data, $web_partner_id)
    {
        $this->db()->table('coupon_car')
            ->where(['web_partner_id' => $web_partner_id])
            ->whereIn('id', $ids)->update($data);

        $isUpdated = $this->db->affectedRows();
        if ($isUpdated === false) {
            return false;
        }
        return true;
    }



    public function remove_coupon($ids, $web_partner_id)
    {
        if (!is_array($ids) || empty($ids)) {
            return false;
        }

        $this->db->table('coupon_car')->where(['web_partner_id' => $web_partner_id])->whereIn('id', $ids)->delete();

        $isDeleted = $this->db->affectedRows();
        if ($isDeleted === false) {
            return false;
        }
        return true;
    }


}



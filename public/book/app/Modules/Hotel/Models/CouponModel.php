<?php



namespace App\Modules\Hotel\Models;



use CodeIgniter\Model;



class CouponModel extends Model

{

    protected $table = 'coupon_hotel';

    protected $primarykey = 'id';

    protected $protectFields = false;



    function getCouponList($input, $web_partner_id)

    {

        if (isset($input['RegionType']) && $input['RegionType'] == "domestic") {
            $input['RegionType'] = 1;
        } else {
            $input['RegionType'] = 0;
        }

        $builder = $this->db->table('coupon_hotel');

        $builder->select('id,code,coupon_desc');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('find_in_set("' . $input['RegionType'] . '", region_type) <> 0');

        $builder->where('find_in_set("' . $input['StarRating'] . '", star_rating) <> 0');

        $builder->where('status', 'active');


        $builder->where('use_limit>', 0);

        $builder->where('coupon_visible', '1');

        $builder->groupStart();

        $builder->where('check_in_date_from<=', strtotime($input['CheckInDate']));

        $builder->where('check_out_date_to>=', strtotime($input['CheckOutDate']));

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('valid_from<=', strtotime(date('Y-m-d')));

        $builder->where('valid_to>=', strtotime(date('Y-m-d')));

        $builder->groupEnd();

        return  $result = $builder->get()->getResultArray();
    }

    function getDataByCode($input,$web_partner_id)
    {
        if (isset($input['RegionType']) && $input['RegionType'] == "domestic") {
            $input['RegionType'] = 1;
        } else {
            $input['RegionType'] = 0;
        }

        $builder = $this->db->table('coupon_hotel');

        $builder->select('id,code,coupon_desc,coupon_type,value,max_limit');
        $builder->where('find_in_set("' . $input['RegionType'] . '", region_type) <> 0');

        $builder->where('find_in_set("' . $input['StarRating'] . '", star_rating) <> 0');

        $builder->where('status', 'active');

        $builder->where('web_partner_id', $web_partner_id);

        $builder->where('use_limit>', 0);

        $builder->where('code', $input['code']);

        $builder->groupStart();

        $builder->where('check_in_date_from<=', strtotime($input['CheckInDate']));

        $builder->where('check_out_date_to>=', strtotime($input['CheckOutDate']));

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('valid_from<=', strtotime(date('Y-m-d')));

        $builder->where('valid_to>=', strtotime(date('Y-m-d')));

        $builder->groupEnd();

        return  $result = $builder->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function getData($tableName, $whereCondtion, $select)
    {
        return  $this->db->table($tableName)->select($select)->where($whereCondtion)->get()->getRowArray();
    }



    function updateData($tableName, $whereCondition, $updateData)

    {

        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }







    function getCouponByToken($token,$web_partner_id)
    {

        $builder = $this->db->table("coupon_log");

        $builder->select('id,coupon_code,couponInfo');

        $builder->where(['token' => $token, 'use_for' => 'Hotel','web_partner_id'=>$web_partner_id]);

        $query = $builder->get()->getRowArray();

        return $query;
    }





    public function remove_promo_log($search_token_log,$web_partner_id)

    {

        $builder = $this->db->table("coupon_log");

        return  $builder->select('*')->where('token', $search_token_log)->where('web_partner_id',$web_partner_id)->delete();
    }
}

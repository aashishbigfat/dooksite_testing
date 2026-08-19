<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class WebPartnerHotelDiscountModel extends Model
{
    protected $table = 'web_partner_hotel_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function getHoteldiscount($web_partner_id,$input){
        $builder = $this->db->table('web_partner_hotel_discount');
        $builder->select('region_type,max_limit,value,extra_discount');
        $builder->where('find_in_set("' . $input['RegionType'] . '", region_type) <> 0');
        $builder->where('discount_for', "B2C");
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('status', 'active');
        return $result = $builder->get()->getRowArray();
    }
}
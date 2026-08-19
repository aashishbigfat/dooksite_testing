<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class WebPartnerHotelMarkupModel extends Model
{
    protected $table = 'web_partner_hotel_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function getHotelmarkup($web_partner_id,$input){
        $builder = $this->db->table('web_partner_hotel_markup');
        $builder->select('region_type,star_rating,hotel_markup_type,value,display_markup');
        $builder->where('find_in_set("' . $input['RegionType'] . '", region_type) <> 0');
        $builder->where('markup_for', "B2C");
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('status', 'active');
        return $result = $builder->get()->getResultArray();
    }

}
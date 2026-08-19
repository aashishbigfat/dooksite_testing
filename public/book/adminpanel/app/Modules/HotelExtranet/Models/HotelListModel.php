<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class HotelListModel extends Model
{
    protected $table = 'hotel_extranet_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function hotel_list($web_partner_id)
    {
        return  $this->select('hotel_extranet_list.id,hotel_extranet_list.supplier_id,hotel_extranet_list.hotel_name,hotel_extranet_list.hotel_star_rating,hotel_extranet_list.hotel_city,hotel_extranet_list.status,hotel_extranet_list.created,hotel_extranet_list.modified,hotel_city_list.destination,hotel_extranet_property_type.property_type,suppliers.company_id,suppliers.company_name')
            ->where(['hotel_extranet_list.web_partner_id' => $web_partner_id])
            ->join('hotel_city_list', 'hotel_city_list.id = hotel_extranet_list.city_id', 'Left')
            ->join("suppliers", "suppliers.id = hotel_extranet_list.supplier_id", 'left')
            ->join('hotel_extranet_property_type', 'hotel_extranet_property_type.id = hotel_extranet_list.hotel_property_type_id', 'Left')->where(['hotel_extranet_property_type.web_partner_id' => $web_partner_id])
            ->orderBy("id", "DESC")->paginate(40);
    }

    public function get_hotel_name($hotel_id, $web_partner_id)
    {
        return  $this->select('hotel_name')->where(["id" => $hotel_id, 'web_partner_id' => $web_partner_id])->get()->getRowArray();
    }

    function search_data($data, $web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['hotel_extranet_list.created >=' => $from_date, 'hotel_extranet_list.created <=' => $to_date];
                return $this->select('hotel_extranet_list.id,hotel_extranet_list.supplier_id,hotel_extranet_list.hotel_name,hotel_extranet_list.hotel_star_rating,hotel_extranet_list.hotel_city,hotel_extranet_list.status,hotel_extranet_list.created,hotel_extranet_list.modified,hotel_city_list.destination,hotel_extranet_property_type.property_type,suppliers.company_id,suppliers.company_name')
                    ->join("suppliers", "suppliers.id = hotel_extranet_list.supplier_id", 'left')
                    ->where(['hotel_extranet_list.web_partner_id' => $web_partner_id])
                    ->join('hotel_city_list', 'hotel_city_list.id = hotel_extranet_list.city_id', 'Left')
                    ->join('hotel_extranet_property_type', 'hotel_extranet_property_type.id = hotel_extranet_list.hotel_property_type_id', 'Left')->where(['hotel_extranet_property_type.web_partner_id' => $web_partner_id])
                    ->where($array)->orderBy("id", "DESC")->paginate(40);
            } else {
                $array = ['hotel_extranet_list.created >=' => $from_date, 'hotel_extranet_list.created <=' => $to_date];
                return $this->select('hotel_extranet_list.id,hotel_extranet_list.supplier_id,hotel_extranet_list.hotel_name,hotel_extranet_list.hotel_star_rating,,hotel_extranet_list.hotel_city,hotel_extranet_list.status,hotel_extranet_list.created,hotel_extranet_list.modified,hotel_city_list.destination,hotel_extranet_property_type.property_type,suppliers.company_id,suppliers.company_name')
                    ->join("suppliers", "suppliers.id = hotel_extranet_list.supplier_id", 'left')
                    ->where(['hotel_extranet_list.web_partner_id' => $web_partner_id])
                    ->join('hotel_city_list', 'hotel_city_list.id = hotel_extranet_list.city_id', 'Left')
                    ->join('hotel_extranet_property_type', 'hotel_extranet_property_type.id = hotel_extranet_list.hotel_property_type_id', 'Left')->where(['hotel_extranet_property_type.web_partner_id' => $web_partner_id])
                    ->where($array)->like(trim($data['key']), trim($data['value']))->orderBy("id", "DESC")->paginate(40);
            }
        } else {
            return $this->select('hotel_extranet_list.id,hotel_extranet_list.hotel_name,hotel_extranet_list.supplier_id,hotel_extranet_list.hotel_star_rating,,hotel_extranet_list.hotel_city,hotel_extranet_list.status,hotel_extranet_list.created,hotel_extranet_list.modified,hotel_city_list.destination,hotel_extranet_property_type.property_type,suppliers.company_id,suppliers.company_name')
                ->join("suppliers", "suppliers.id = hotel_extranet_list.supplier_id", 'left')
                ->where(['hotel_extranet_list.web_partner_id' => $web_partner_id])
                ->join('hotel_city_list', 'hotel_city_list.id = hotel_extranet_list.city_id', 'Left')
                ->join('hotel_extranet_property_type', 'hotel_extranet_property_type.id = hotel_extranet_list.hotel_property_type_id', 'Left')->where(['hotel_extranet_property_type.web_partner_id' => $web_partner_id])
                ->like(trim($data['key']), trim($data['value']))->orderBy("id", "DESC")->paginate(40);
        }
    }

    public function remove_hotel($id, $web_partner_id)
    {
        return  $this->select('*')->whereIn("id", $id)->where(['web_partner_id' => $web_partner_id])->delete();
    }
    public function status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id' => $web_partner_id])->set($data)->update();
    }

    public function hotel_details($id, $web_partner_id)
    {
        return  $this->select('hotel_extranet_list.id,hotel_extranet_list.web_partner_id,hotel_extranet_list.city_id,hotel_extranet_list.hotel_code,hotel_extranet_list.city_id, hotel_extranet_list.hotel_city,hotel_extranet_list.pan_required,hotel_extranet_list.passport_required,hotel_extranet_list.hotel_property_type_id,hotel_extranet_list.hotel_name,hotel_extranet_list.hotel_star_rating,hotel_extranet_list.hotel_images,hotel_extranet_list.hotel_promotion,hotel_extranet_list.hotel_description,hotel_extranet_list.address,hotel_extranet_list.city_name,,hotel_extranet_list.state,hotel_extranet_list.hotel_amenities,hotel_extranet_list.postal_code,hotel_extranet_list.country_name,hotel_extranet_list.location_area,hotel_extranet_list.latitude,hotel_extranet_list.longitude,hotel_extranet_list.check_in_time,hotel_extranet_list.check_out_time,hotel_extranet_list.review_provider,hotel_extranet_list.review_rating,hotel_extranet_list.review_url,hotel_extranet_list.status,hotel_city_list.destination,hotel_extranet_list.trading_hotel')
            ->join('hotel_city_list', 'hotel_city_list.id = hotel_extranet_list.city_id', 'Left')
            ->where(["hotel_extranet_list.web_partner_id" => $web_partner_id, "hotel_extranet_list.id" => $id])
            ->get()
            ->getRowArray();
    }

    public function city_list_select($data)
    {
        return  $this->db->table("hotel_city_list")->select('id,city_id,destination,country')->like("destination", $data)->limit(20)->get()->getResultArray();;
    }

    public function delete_image($id, $web_partner_id)
    {
        return  $this->select('hotel_images')->where("id", $id)->where(['web_partner_id' => $web_partner_id])->get()->getRowArray();
    }
}

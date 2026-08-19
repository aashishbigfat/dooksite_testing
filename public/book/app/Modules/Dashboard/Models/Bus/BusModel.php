<?php

namespace App\Modules\Dashboard\Models\Bus;

use CodeIgniter\Model;

class BusModel extends Model
{

    public function offers_list()
    {
        return $this->db->table('super_admin_offers')->select('id,title,description,service,url,image')
            ->where('status','active')->where('service','bus')->limit(9)->orderBy('id', 'DESC')->get()->getResultArray();
    }
    function admin_markup($web_partner_class_id)
    {
        $builder =$this->db->table('super_admin_bus_markup');
        $builder->select('markup_type,value,display_markup,max_limit');
        $builder->where('web_partner_class_id',$web_partner_class_id);
        $builder->where('status', 'active');
        return $builder->get()->getRowArray();
    }

    function admin_discount($web_partner_class_id)
    {
        $builder =$this->db->table('super_admin_bus_discount');
        $builder->select('value,extra_discount,max_limit');
        $builder->where('web_partner_class_id',$web_partner_class_id);
        $builder->where('status', 'active');
        return $builder->get()->getRowArray();
    }

    function admin_gst_state_code()
    {
        $builder =$this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function insertBatchData($tableName, $insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }

    function get_city_name(array $city_id_array)
    {
        return  $this->db->table("bus_city_list")->select('city_name,city_id')->whereIn('city_id', $city_id_array)->get()->getResultArray();
    }

    function get_search_request($tts_search_token)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('request,response');
        $builder->where(['tts_search_token'=>$tts_search_token,'service'=>'search']);
        return $builder->get()->getRowArray();
    }

    function get_log_data($tts_search_token,$service,$selected_index)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('response');
        $builder->where(['tts_search_token'=>$tts_search_token,'service'=>$service,'selected_index'=>$selected_index]);
        $builder->orderBy("id","DESC");
        return $builder->get()->getRowArray();
    }

    function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name')->get()->getResultArray();
    }
    function get_log_data_variable_field($tts_search_token,$service,$selected_index,$field)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select($field);
        $builder->where(['tts_search_token'=>$tts_search_token,'service'=>$service,'selected_index'=>$selected_index]);
        $builder->orderBy("id","DESC");
        return $builder->get()->getRowArray();
    }
    function getBusBookingData($bus_booking_id,$web_partner_id){
        $builder = $this->db->table('bus_booking_list');
            $builder->select("bus_booking_list.*,created,concat('[', group_concat(JSON_OBJECT('id', bus_booking_travelers.id,'title',bus_booking_travelers.title,'first_name',bus_booking_travelers.first_name,'last_name',bus_booking_travelers.last_name,'age',bus_booking_travelers.age,'email_id',bus_booking_travelers.email_id,'mobile_number',bus_booking_travelers.mobile_number,'lead_pax',bus_booking_travelers.lead_pax,'gendar',bus_booking_travelers.gendar,'id_type',bus_booking_travelers.id_type,'id_number',bus_booking_travelers.id_number,'seat_name',bus_booking_travelers.seat_name,'address',bus_booking_travelers.address,'age',bus_booking_travelers.age) separator ','), ']') as travelersInfo");
            $builder->where(['bus_booking_list.id' => $bus_booking_id, 'bus_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('bus_booking_travelers', "bus_booking_travelers.bus_booking_id =$bus_booking_id");
            $builder->groupBy('bus_booking_list.id');
            $query = $builder->get()->getRowArray();
            return $query;
    }
    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }
}



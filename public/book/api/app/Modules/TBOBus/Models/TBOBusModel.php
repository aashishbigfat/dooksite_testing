<?php

namespace App\Modules\TBOBus\Models;

use CodeIgniter\Model;

class TBOBusModel extends Model
{
    function fetch_auth_token($date,$api_mode)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tbo_auth_token');
        $builder->select('token_id');
        $builder->where('api_mode', $api_mode);
        $builder->where('created', $date);
        return $builder->get()->getRowArray();
    }
    
    function insert_update_data($table_name,$data)
    {
        $db= \Config\Database::connect('api');
        $db->table($table_name)->replace($data);
    }

    function insert_data($table_name,$data)
    {
        $db= \Config\Database::connect('api');
        $db->table($table_name)->insert($data);
    }

    function insert_bus_logs($web_partner_id,$tts_search_token,$request,$response,$trace_id,$service,$created)
    {
        $insertlog=array(
                            'web_partner_id'   => $web_partner_id,
                            'tts_search_token' => $tts_search_token,
                            'request'          => json_encode($request),
                            'response'         => json_encode($response),
                            'trace_id'         => $trace_id,
                            'service'          => $service,
                            'created'          => $created
                        );
        $db= \Config\Database::connect('api');
        $db->table('tbo_bus_log')->insert($insertlog);
    }

    function super_admin_markup($web_partner_class_id,$extraParameter)
    {
        $builder =$this->db->table('super_admin_bus_markup');
        $builder->select('markup_type,value,display_markup,max_limit');
        if($extraParameter['btype']=="B2B"){
            $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
            }
            $builder->where('markup_for', $extraParameter['btype']);
        $builder->where('status', 'active');
        return $builder->get()->getRowArray();
    }

    function super_admin_discount($web_partner_class_id,$extraParameter)
    {
        $builder =$this->db->table('super_admin_bus_discount');
        $builder->select('value,extra_discount,max_limit');
        if($extraParameter['btype']=="B2B"){
            $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
            }
            $builder->where('discount_for', $extraParameter['btype']);
        $builder->where('status', 'active');
        return $builder->get()->getRowArray();
    }

    function super_admin_gst_state_code()
    {
        $builder =$this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }
    function get_city_name(array $city_id_array)
    {
        return  $this->db->table("bus_city_list")->select('city_name,city_id')->whereIn('city_id', $city_id_array)->get()->getResultArray();
    }
    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }
 
    function insert_city_list($data)
    {
        $this->db->table('bus_city_list')->insert($data);
    }
    
}



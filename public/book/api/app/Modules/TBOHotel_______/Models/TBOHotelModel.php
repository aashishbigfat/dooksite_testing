<?php

namespace App\Modules\TBOHotel\Models;

use CodeIgniter\Model;

class TBOHotelModel extends Model
{

    function fetch_auth_token($date,$api_mode)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tbo_auth_token');
        $builder->select('token_id');
        $builder->where('api_mode', $api_mode);
        $builder->where('created', $date);
        $builder->where('service', 'Hotel');
        return $builder->get()->getRowArray();
    }
    function getDataRowType($tableName,$whereCondition,$field)
  {
    $builder =  $this->db->table($tableName)->select($field);
    if($whereCondition)
    {
    $builder->where($whereCondition);
    }
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

    function insert_hotel_logs($web_partner_id,$tts_search_token,$request,$response,$trace_id,$service,$created)
    {
        $insertlog=array(
                            'web_partner_id'   => $web_partner_id,
                            'tts_search_token' => $tts_search_token,
                            'request'          => json_encode($request),
                            'response'         => json_encode($response),
                            'service'          => $service,
                            'api_supplier'          => 'TBO',
                            'created'          => $created
                        );
        $db= \Config\Database::connect('api');
        $db->table('common_hotel_log')->insert($insertlog);
    }

    function super_admin_markup($web_partner_class_id,$region_type)
    {
        $builder =$this->db->table('super_admin_hotel_markup');
        $builder->select('hotel_markup_type,value,display_markup,star_rating');
        $builder->where('web_partner_class_id',$web_partner_class_id);
        $builder->where('region_type',$region_type);
        $builder->where('status', 'active');
        return $builder->get()->getResultArray();
    }

    function super_admin_discount($web_partner_class_id,$region_type)
    {
        $builder =$this->db->table('super_admin_hotel_discount');
        $builder->select('value,extra_discount,max_limit');
        $builder->where('web_partner_class_id',$web_partner_class_id);
        $builder->where('region_type',$region_type);
        $builder->where('status', 'active');
        return $builder->get()->getRowArray();
    }

    function super_admin_gst_state_code()
    {
        $builder =$this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

   
    function get_auth_user_account_balance($web_partner_id)
    {
        return  $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->get()->getRowArray();
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

    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function check_cancel_data($web_partner_id,$hotel_booking_id)
    {
        return  $this->db->table("hotel_cancellation")->select('id,supplier_cancel_status,response_remark')->where(['web_partner_id'=>$web_partner_id,'hotel_booking_id'=>$hotel_booking_id])->get()->getRowArray();
    }

}



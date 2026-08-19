<?php 

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
  function getDataFromApi($whereClause,$gettingColumn){
      $apiDb = \Config\Database::connect('api');
      $builder  =  $apiDb->table("tts_hotel_log");
      $builder->select($gettingColumn);
      $builder->orderBy("id","DESC");
      return $builder->where($whereClause)->get()->getRowArray();
  }

    public function offers_list()
    {
        return $this->db->table('super_admin_offers')->select('id,title,description,service,url,image')
            ->where('status','active')->where('service','hotel')->limit(9)->orderBy('id', 'DESC')->get()->getResultArray();
    }
  function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name')->get()->getResultArray();
    }
    function get_city_name($city_id)
    {
        return  $this->db->table("hotel_city_list")->select('destination')->where('city_id', $city_id)->get()->getRowArray();
    }
    
    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function getData($tableName,$whereClause,$gettingColumn){
        $builder  =  $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id","DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }

    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }
    function super_admin_booking_pre_fix_code()
    {
        $builder =$this->db->table('super_admin_website_setting');
        $builder->select('hotel_pre_fix');
      $data   =   $builder->get()->getRowArray();
      $data['pre_fix'] =  $data['hotel_pre_fix'];
      unset($data['hotel_pre_fix']);
      return $data;
    }
    function getBlockHotel($cityId)
    {
        $blockHotelCode  =  array();
        $builder =$this->db->table('hotel_block_list');
        $builder->select('hotel_code');
        $builder->where('city_id',trim($cityId));
        $data   =  $builder->get()->getResultArray();
       if($data){
        $blockHotelCode =  array_column($data,"hotel_code");
       }
       return $blockHotelCode;
    }
}
?>
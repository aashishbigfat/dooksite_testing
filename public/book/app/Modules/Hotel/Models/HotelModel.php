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

    
 public function offers_list($web_partner_id)
    {
        return $this->db->table('web_partner_offers')
            ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'hotel')
            ->groupStart() 
            ->where(["web_partner_offers.web_partner_id"=>$web_partner_id]) 
            ->orWhere(["web_partner_offers.web_partner_id"=>0]) 
            ->groupEnd() 
            ->limit(4)->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();    
    }


    public function get_feedback_model_list($web_partner_id)
    {
        $data = $this->db->table('customer_feedback')
            ->select('*')
            ->where('status','active')
            ->where('web_partner_id',$web_partner_id)
            ->orderBy('id', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();

        return $data;
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
    function  service_booking_pre_fix_code($web_partner_id)
    {
        if(whitelabel['is_direct_website']=="inactive"){
            $builder = $this->db->table('super_admin_website_setting');
            }
            else{
                $builder = $this->db->table('web_partner');
                $builder->where('id',$web_partner_id);
            }
        $builder->select('hotel_pre_fix');
        return $builder->get()->getRowArray();
    }
    function getHotelMarkup($web_partner_id, $region_type)
    {
        $markup = array();
        $builder = $this->db->table('web_partner_hotel_markup');
        $builder->select('hotel_markup_type,value,display_markup,star_rating');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('region_type', $region_type);
        $builder->where('status', 'active');
        $builder->where('markup_for', 'B2C');
        $markupdata =  $builder->get()->getResultArray();
        if($markupdata){
            $markup =  $markupdata;
            }
            return  $markup;
    }
    function getHotelDiscount($web_partner_id, $region_type)
    {
        $discount = array();
        $builder = $this->db->table('web_partner_hotel_discount');
        $builder->select('value,max_limit,extra_discount');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('region_type', $region_type);
        $builder->where('status', 'active');
        $builder->where('discount_for', 'B2C');
        $discountData =   $builder->get()->getResultArray();
        if($discountData){
        $discount =  $discountData;
        }
        return  $discount;
    }
    function admin_notification()
    {
        return $this->db->table('slider')->where('status', 'active')->where('image_category', 'Admin-Notification')->get()->getResultArray();
    }
        public function blog_list($web_partner_id)
    {
        return $this->db->table('blog_post')->select('id,post_title,post_slug,post_desc,posted_by,post_images,created')->where(['web_partner_id'=>$web_partner_id])
            ->where('status', 'active')->limit(4)->orderBy('id', 'DESC')->get()->getResultArray();
    }
}

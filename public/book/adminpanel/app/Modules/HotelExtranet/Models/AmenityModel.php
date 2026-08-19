<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class AmenityModel extends Model
{
    protected $table = 'hotel_extranet_amenity';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function amenity_list($web_partner_id)
    {
        return  $this->select('hotel_extranet_amenity.id,hotel_extranet_amenity.amenity_title,hotel_extranet_amenity.amenity_type,hotel_extranet_amenity.amenity_icon,hotel_extranet_amenity.status,hotel_extranet_amenity.created,hotel_extranet_amenity.modified,suppliers.company_id,suppliers.company_name')
        ->join("suppliers", "suppliers.id = hotel_extranet_amenity.supplier_id", 'left')
        ->where(['hotel_extranet_amenity.web_partner_id'=>$web_partner_id])->orderBy("hotel_extranet_amenity.id","DESC")->paginate(40);
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['hotel_extranet_amenity.created >='=> $from_date,'hotel_extranet_amenity.created <='=> $to_date];
                return  $this->select('hotel_extranet_amenity.id,hotel_extranet_amenity.amenity_title,hotel_extranet_amenity.amenity_type,hotel_extranet_amenity.amenity_icon,hotel_extranet_amenity.status,hotel_extranet_amenity.created,hotel_extranet_amenity.modified,suppliers.company_id,suppliers.company_name')
                ->join("suppliers", "suppliers.id = hotel_extranet_amenity.supplier_id", 'left')
                ->where(['hotel_extranet_amenity.web_partner_id'=>$web_partner_id])->where($array)->orderBy("hotel_extranet_amenity.id","DESC")->paginate(40);
            } else {
                $array=['hotel_extranet_amenity.created >='=> $from_date,'hotel_extranet_amenity.created <='=> $to_date];
                return  $this->select('hotel_extranet_amenity.id,hotel_extranet_amenity.amenity_title,hotel_extranet_amenity.amenity_type,hotel_extranet_amenity.amenity_icon,hotel_extranet_amenity.status,hotel_extranet_amenity.created,hotel_extranet_amenity.modified,suppliers.company_id,suppliers.company_name')
                ->join("suppliers", "suppliers.id = hotel_extranet_amenity.supplier_id", 'left')
                ->where(['hotel_extranet_amenity.web_partner_id'=>$web_partner_id])->where($array)->like(trim($data['key']),trim($data['value']))->orderBy("hotel_extranet_amenity.id","DESC")->paginate(40);
            }
        } else {
            return  $this->select('hotel_extranet_amenity.id,hotel_extranet_amenity.amenity_title,hotel_extranet_amenity.amenity_type,hotel_extranet_amenity.amenity_icon,hotel_extranet_amenity.status,hotel_extranet_amenity.created,hotel_extranet_amenity.modified,suppliers.company_id,suppliers.company_name')
            ->join("suppliers", "suppliers.id = hotel_extranet_amenity.supplier_id", 'left')
            ->where(['hotel_extranet_amenity.web_partner_id'=>$web_partner_id])->like(trim($data['key']),trim($data['value']))->orderBy("hotel_extranet_amenity.id","DESC")->paginate(40);

        }
    }

	public function remove_amenity($id,$web_partner_id)
    {
        return $this->whereIn('id', $id)->where('web_partner_id', $web_partner_id)->delete();
    }
	 
    public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

	public function amenity_details($id,$web_partner_id)
    {
        return  $this->select('id,amenity_title,amenity_type,amenity_icon,status')->where(['web_partner_id'=>$web_partner_id])->where("id",$id)->get()->getRowArray();
    }

    public function amenity_select()
    {
        return  $this->select('id,amenity_title')->get()->getResultArray();
    }
    public function amenity_room_select($web_partner_id)
    {
        return  $this->select('id,amenity_title')->whereIn('amenity_type',['both','room'])->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

    public function amenity_hotel_select($web_partner_id)
    {
        return  $this->select('id,amenity_title')->whereIn('amenity_type',['both','hotel'])->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('amenity_icon')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }
}



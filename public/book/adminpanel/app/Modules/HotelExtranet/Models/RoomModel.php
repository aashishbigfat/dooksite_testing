<?php

namespace App\Modules\HotelExtranet\Models;
 
use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'hotel_extranet_room';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function room_list($hotel_id,$web_partner_id)
    {
        return  $this->select('id,room_title,occupancy_type,min_stay,status,room_quantity,created,modified')->where(["hotel_extranet_id"=> $hotel_id,'web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }
    public function get_room_title($id,$web_partner_id){
        return $this->select('id,room_title')->where("id", $id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

	 public function remove_room($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
	 public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
	  public function room_details($id,$web_partner_id)
    {
        return  $this->select('id,room_title,occupancy_type,room_description,room_cancellation,min_stay,room_amenities,room_quantity,room_gallery,status')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function delete_image($id)
    {
        return  $this->select('room_gallery')->where("id",$id)->get()->getRowArray();
    }
}



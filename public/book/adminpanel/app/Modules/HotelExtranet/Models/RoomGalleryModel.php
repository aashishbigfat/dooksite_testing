<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class RoomGalleryModel extends Model
{
    protected $table = 'hotel_extranet_room_gallery';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function room_gallery($room_id,$web_partner_id)
    {
        return  $this->select('id,room_gallery,image_title,created,modified')->where(['hotel_extranet_room_id'=> $room_id,'web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

    public function holiday_gallery_details($id)
    {
        return  $this->select('id,image_title,room_gallery')->where("id",$id)->get()->getRowArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('room_gallery')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_room_gallery($ids,$web_partner_id)
    {
        return  $this->select('*')->where(["id"=>$ids,'web_partner_id'=>$web_partner_id])->delete();
    }

}



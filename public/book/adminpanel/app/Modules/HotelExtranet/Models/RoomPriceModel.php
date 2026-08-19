<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class RoomPriceModel extends Model
{
    protected $table = 'hotel_extranet_room_price';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function room_price_list($room_id, $web_partner_id)
    {
        return  $this->select('hotel_extranet_room_price.id,hotel_extranet_room_price.start_date,hotel_extranet_room_price.end_date,hotel_extranet_room_price.adult_price,hotel_extranet_room_price.child_price,hotel_extranet_room_price.mon,hotel_extranet_room_price.tue,hotel_extranet_room_price.wed,hotel_extranet_room_price.thu,hotel_extranet_room_price.fri,hotel_extranet_room_price.sat,hotel_extranet_room_price.sun,hotel_extranet_room_price.created,hotel_extranet_room_price.modified,hotel_extranet_room.room_title')
            ->where("hotel_extranet_room_id", $room_id)
            ->where("hotel_extranet_room_price.web_partner_id", $web_partner_id)
            ->join('hotel_extranet_room', 'hotel_extranet_room.id = hotel_extranet_room_price.hotel_extranet_room_id', 'Left')->where("hotel_extranet_room.web_partner_id", $web_partner_id)
            ->orderBy("hotel_extranet_room_price.id", "DESC")->paginate(40);
    }


    public function remove_room_price($id,$web_partner_id)
    {
        return  $this->select('*')->where(["id"=> $id,'web_partner_id'=>$web_partner_id])->delete();
    }
    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->set($data)->update();
    }
    public function room_price_details($id, $web_partner_id)
    {
        return  $this->select('id,start_date,end_date,adult_price,child_price,mon,tue,wed,thu,fri,sat,sun')->where(["id" => $id, 'web_partner_id' => $web_partner_id])->get()->getRowArray();
    }
}

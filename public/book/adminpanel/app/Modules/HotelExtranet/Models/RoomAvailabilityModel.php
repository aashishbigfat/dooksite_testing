<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class RoomAvailabilityModel extends Model
{
    protected $table = 'hotel_extranet_room_availability';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function get_room_availabilities($hotel_extranet_room_id ,$year,$web_partner_id)
    {
        return  $this->select('*')->where(["hotel_extranet_room_id"=>$hotel_extranet_room_id,"year"=>$year,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
}



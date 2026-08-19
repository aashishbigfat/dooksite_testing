<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class AddonModel extends Model
{
    protected $table = 'hotel_extranet_addon_services';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function addon_list($hotel_id,$web_partner_id)
    {
        return  $this->select('id,service_name,price,status,created,modified')->where(['hotel_extranet_id'=>$hotel_id,'web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

	 public function remove_addon($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
	 public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
	  public function addon_details($id,$web_partner_id)
    {
        return  $this->select('id,service_name,price,status,')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

}



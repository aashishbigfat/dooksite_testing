<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseOceanModel extends Model
{
    protected $table = 'cruise_ocean';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_ocean_list($web_partner_id)
    {
        return  $this->select('id,ocean_name,status,created,modified')
            ->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

	 public function remove_cruise_ocean($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
	 public function cruise_ocean_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
	  public function cruise_ocean_details($id,$web_partner_id)
    {
        return  $this->select('id,ocean_name,status,')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function cruise_ocean_select($web_partner_id)
    {
        return  $this->select('id,ocean_name')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
    
}



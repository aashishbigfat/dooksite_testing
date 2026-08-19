<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruisePortModel extends Model
{
    protected $table = 'cruise_ports';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_port_list($web_partner_id)
    {
        return  $this->select('cruise_ports.id,cruise_ports.port_name,cruise_ports.status,cruise_ports.created,cruise_ports.modified,cruise_ocean.ocean_name')
            ->join('cruise_ocean', 'cruise_ocean.id = cruise_ports.cruise_ocean_id','Left')->where(['cruise_ports.web_partner_id'=>$web_partner_id])
            ->orderBy("cruise_ports.id","DESC")->paginate(40);
    }

	public function remove_cruise_port($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
	public function cruise_port_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
	public function cruise_port_details($id,$web_partner_id)
    {
        return  $this->select('id,port_name,status,cruise_ocean_id')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function cruise_port_select($cruise_ocean_id,$web_partner_id)
    {
        return  $this->select('id,port_name')->where("cruise_ocean_id",$cruise_ocean_id)->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

    public function cruise_port_select_all($web_partner_id)
    {
        return  $this->select('id,port_name')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
    
}



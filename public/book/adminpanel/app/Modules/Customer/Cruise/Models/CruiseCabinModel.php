<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseCabinModel extends Model
{
    protected $table = 'cruise_cabin';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_cabin_list($web_partner_id)
    {
        return  $this->select('cruise_cabin.id,cruise_cabin.cabin_slug,cruise_cabin.cabin_name,cruise_cabin.status,cruise_cabin.created,cruise_cabin.modified,cruise_ship.ship_name')
            ->join('cruise_ship', 'cruise_ship.id = cruise_cabin.cruise_ship_id','Left')->where("cruise_cabin.web_partner_id",$web_partner_id)
            ->orderBy('id', 'DESC')->paginate(40);
    }

    public function cruise_cabin_details($id,$web_partner_id)
    {
        return  $this->select('id,cabin_name,cabin_slug,status,cruise_ship_id,cabin_slug')->where("id",$id)->where("web_partner_id",$web_partner_id)->get()->getRowArray();
    }
    public function cruise_cabin_select($web_partner_id)
    {
        return  $this->select('id,cabin_name')->where("web_partner_id",$web_partner_id)->get()->getResultArray();
    }

    public function cruise_cabin_select_ship_id($cruise_ship_id,$web_partner_id){
        return  $this->select('id,cabin_name')->where("web_partner_id",$web_partner_id)->where("cruise_ship_id",$cruise_ship_id)->get()->getResultArray();
    }

    function search_data($data)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['cruise_cabin.created >='=> $from_date,'cruise_cabin.created <='=> $to_date];

                return  $this->select('cruise_cabin.id,cruise_cabin.cabin_slug,cruise_cabin.cabin_name,cruise_cabin.status,cruise_cabin.created,cruise_cabin.modified,cruise_ship.ship_name')
                    ->join('cruise_ship', 'cruise_ship.id = cruise_cabin.cruise_ship_id','Left')
                    ->where($array)->orderBy('id', 'DESC')->paginate(40);

            } else {
                $array=['cruise_cabin.created >='=> $from_date,'cruise_cabin.created <='=> $to_date];

                return  $this->select('cruise_cabin.id,cruise_cabin.cabin_slug,cruise_cabin.cabin_name,cruise_cabin.status,cruise_cabin.created,cruise_cabin.modified,cruise_ship.ship_name')
                    ->join('cruise_ship', 'cruise_ship.id = cruise_cabin.cruise_ship_id','Left')
                    ->where($array)->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
            }
        } else {
            return  $this->select('cruise_cabin.id,cruise_cabin.cabin_slug,cruise_cabin.cabin_name,cruise_cabin.status,cruise_cabin.created,cruise_cabin.modified,cruise_ship.ship_name')
                ->join('cruise_ship', 'cruise_ship.id = cruise_cabin.cruise_ship_id','Left')
                ->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);

        }
    }

    public function remove_cruise_cabin($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function cruise_cabin_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}



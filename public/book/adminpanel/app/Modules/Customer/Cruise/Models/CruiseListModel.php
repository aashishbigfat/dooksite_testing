<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseListModel extends Model
{
    protected $table = 'cruise_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_list($web_partner_id)
    {
        return  $this->select('cruise_list.id,cruise_list.departure_port_id,cruise_list.no_of_nights,cruise_list.status,cruise_list.starting_price,cruise_list.cruise_ocean_id,cruise_list.cruise_itinerary,cruise_list.adult_passport,cruise_list.cruise_line_id,cruise_list.child_passport,cruise_list.infant_passport,cruise_list.created,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_ocean.ocean_name')
            ->join('cruise_line', 'cruise_line.id = cruise_list.cruise_line_id','Left')
            ->join('cruise_ship', 'cruise_ship.id = cruise_list.cruise_ship_id','Left')
            ->join('cruise_ports', 'cruise_ports.id = cruise_list.departure_port_id','Left')
            ->join('cruise_ocean', 'cruise_ocean.id = cruise_list.cruise_ocean_id','Left')
            ->where(['cruise_list.web_partner_id'=>$web_partner_id])
            ->orderBy('id', 'DESC')->paginate(40);
    }

    public function cruise_list_details($id,$web_partner_id)
    {
        return  $this->select('id,departure_port_id,no_of_nights,status,status,starting_price,cruise_ocean_id,cruise_itinerary,adult_passport,child_passport,infant_passport,cruise_line_id,cruise_ship_id')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function cruise_list_select()
    {
        return  $this->select('id,departure_port')->get()->getResultArray();
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['cruise_list.created >='=> $from_date,'cruise_list.created <='=> $to_date];

                return  $this->select('cruise_list.id,cruise_list.departure_port_id,cruise_list.no_of_nights,cruise_list.status,cruise_list.starting_price,cruise_list.cruise_itinerary,cruise_list.adult_passport,cruise_list.cruise_line_id,cruise_list.child_passport,cruise_list.infant_passport,cruise_list.created,cruise_line.cruise_line_name,,cruise_ship.ship_name,cruise_ports.port_name,cruise_ocean.ocean_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_list.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = cruise_list.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = cruise_list.departure_port_id','Left')
                    ->join('cruise_ocean', 'cruise_ocean.id = cruise_list.cruise_ocean_id','Left')
                    ->where(['cruise_list.web_partner_id'=>$web_partner_id])
                    ->where($array)->orderBy('id', 'DESC')->paginate(40);
            } else {
                $array=['cruise_list.created >='=> $from_date,'cruise_list.created <='=> $to_date];
                return  $this->select('cruise_list.id,cruise_list.departure_port_id,cruise_list.no_of_nights,cruise_list.status,cruise_list.starting_price,cruise_list.cruise_itinerary,cruise_list.adult_passport,cruise_list.cruise_line_id,cruise_list.child_passport,cruise_list.infant_passport,cruise_list.created,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_ocean.ocean_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_list.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = cruise_list.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = cruise_list.departure_port_id','Left')
                    ->join('cruise_ocean', 'cruise_ocean.id = cruise_list.cruise_ocean_id','Left')
                    ->where(['cruise_list.web_partner_id'=>$web_partner_id])
                    ->where($array)->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
            }
        } else {
            return  $this->select('cruise_list.id,cruise_list.departure_port_id,cruise_list.no_of_nights,cruise_list.status,cruise_list.starting_price,cruise_list.cruise_itinerary,cruise_list.adult_passport,cruise_list.cruise_line_id,cruise_list.child_passport,cruise_list.infant_passport,cruise_list.created,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_ocean.ocean_name')
                ->join('cruise_line', 'cruise_line.id = cruise_list.cruise_line_id','Left')
                ->join('cruise_ship', 'cruise_ship.id = cruise_list.cruise_ship_id','Left')
                ->join('cruise_ports', 'cruise_ports.id = cruise_list.departure_port_id','Left')
                ->join('cruise_ocean', 'cruise_ocean.id = cruise_list.cruise_ocean_id','Left')
                ->where(['cruise_list.web_partner_id'=>$web_partner_id])
                ->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
        }
    }

    public function remove_cruise_list($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function cruise_list_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}



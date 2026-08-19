<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class WebPartnerCruiseMarkupModel extends Model
{
    protected $table = 'web_partner_cruise_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_markup_list($web_partner_id)
    {
        return  $this->select('web_partner_cruise_markup.id,web_partner_cruise_markup.markup_type,web_partner_cruise_markup.agent_class,web_partner_cruise_markup.markup_for,web_partner_cruise_markup.value,web_partner_cruise_markup.max_limit,web_partner_cruise_markup.display_markup,web_partner_cruise_markup.status,web_partner_cruise_markup.created,web_partner_cruise_markup.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
            ->join('cruise_line', 'cruise_line.id = web_partner_cruise_markup.cruise_line_id','Left')
            ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_markup.cruise_ship_id','Left')
            ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_markup.departure_port_id','Left')
            ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_markup.cabin_id','Left')
            ->where('web_partner_cruise_markup.web_partner_id',$web_partner_id)
            ->orderBy("id","DESC")->paginate(40);
    }



    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','markup_for'=>'web_partner_cruise_markup.markup_for','cruise_ship_id'=>'cruise_ship.ship_name','markup_type'=>'web_partner_cruise_markup.markup_type','cabin_id'=>'cruise_cabin.cabin_name','departure_port_id'=>'cruise_ports.port_name'];
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_cruise_markup.created >=' => $from_date, 'web_partner_cruise_markup.created <=' => $to_date];
                return $this->table('web_partner_cruise_markup')
                    ->select('web_partner_cruise_markup.id,web_partner_cruise_markup.markup_type,web_partner_cruise_markup.agent_class,web_partner_cruise_markup.markup_for,web_partner_cruise_markup.value,web_partner_cruise_markup.max_limit,web_partner_cruise_markup.display_markup,web_partner_cruise_markup.status,web_partner_cruise_markup.created,web_partner_cruise_markup.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = web_partner_cruise_markup.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_markup.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_markup.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_markup.cabin_id','Left')
                    ->where('web_partner_cruise_markup.web_partner_id',$web_partner_id)->where($array)
                    ->orderBy("web_partner_cruise_markup.id","DESC")->paginate(40);
            } else {
                $array = ['web_partner_cruise_markup.created >=' => $from_date, 'web_partner_cruise_markup.created <=' => $to_date];
                
                return $this->table('web_partner_cruise_markup')
                    ->select('web_partner_cruise_markup.id,web_partner_cruise_markup.markup_type,web_partner_cruise_markup.agent_class,web_partner_cruise_markup.markup_for,web_partner_cruise_markup.value,web_partner_cruise_markup.max_limit,web_partner_cruise_markup.display_markup,web_partner_cruise_markup.status,web_partner_cruise_markup.created,web_partner_cruise_markup.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = web_partner_cruise_markup.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_markup.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_markup.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_markup.cabin_id','Left')
                    ->where('web_partner_cruise_markup.web_partner_id',$web_partner_id)->where($array)->like($where[trim($data['key'])], trim($data['value']))
                    ->orderBy("web_partner_cruise_markup.id","DESC")->paginate(40);
                
                 
            }
        } else { 
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','markup_for'=>'web_partner_cruise_markup.markup_for','cruise_ship_id'=>'cruise_ship.ship_name','markup_type'=>'web_partner_cruise_markup.markup_type','cabin_id'=>'cruise_cabin.cabin_name','departure_port_id'=>'cruise_ports.port_name'];
           
            return $this->table('web_partner_cruise_markup')
                ->select('web_partner_cruise_markup.id,web_partner_cruise_markup.markup_type,web_partner_cruise_markup.agent_class,web_partner_cruise_markup.markup_for,web_partner_cruise_markup.value,web_partner_cruise_markup.max_limit,web_partner_cruise_markup.display_markup,web_partner_cruise_markup.status,web_partner_cruise_markup.created,web_partner_cruise_markup.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                ->join('cruise_line', 'cruise_line.id = web_partner_cruise_markup.cruise_line_id','Left')
                ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_markup.cruise_ship_id','Left')
                ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_markup.departure_port_id','Left')
                ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_markup.cabin_id','Left')
                ->where('web_partner_cruise_markup.web_partner_id',$web_partner_id)->like($where[trim($data['key'])], trim($data['value']))
                ->orderBy("web_partner_cruise_markup.id","DESC")->paginate(40); 

          
        }
    }
 

    public function status_change($ids,$data,$web_partner_id){
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    public function remove_markup($ids,$web_partner_id){
        return  $this->select('*')->whereIn("id",$ids)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function web_partner_cruise_markup_details($id,$web_partner_id)
    {
        $query  =  $this->select('id,web_partner_id,markup_for,agent_class,cruise_line_id,cruise_ship_id,departure_port_id,cabin_id,markup_type,value,max_limit,display_markup,status')
        ->where(["id"=>$id,'web_partner_id'=>$web_partner_id])
        ->get()
        ->getRowArray();
        return $query;
    }
 

}
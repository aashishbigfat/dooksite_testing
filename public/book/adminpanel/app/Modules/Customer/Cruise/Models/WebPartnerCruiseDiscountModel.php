<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class WebPartnerCruiseDiscountModel extends Model
{
    protected $table = 'web_partner_cruise_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_discount_list($web_partner_id)
    {
        return  $this->select('web_partner_cruise_discount.id,web_partner_cruise_discount.web_partner_id,web_partner_cruise_discount.agent_class,web_partner_cruise_discount.discount_for,web_partner_cruise_discount.departure_port_id,web_partner_cruise_discount.discount_type,web_partner_cruise_discount.value,web_partner_cruise_discount.max_limit,web_partner_cruise_discount.status,web_partner_cruise_discount.created,web_partner_cruise_discount.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
            ->join('cruise_line', 'cruise_line.id = web_partner_cruise_discount.cruise_line_id','Left')
            ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_discount.cruise_ship_id','Left')
            ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_discount.departure_port_id','Left')
            ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_discount.cabin_id','Left')
            ->where(['web_partner_cruise_discount.web_partner_id'=>$web_partner_id])
            ->orderBy("id","DESC")->paginate(40);
    }
 
    public function web_partner_cruise_discount_details($id,$web_partner_id)
    {
        $query  =  $this->select('id,web_partner_id,discount_for,agent_class,cruise_line_id,cruise_ship_id,departure_port_id,cabin_id,discount_type,value,max_limit,status')
        ->where(["id"=>$id,'web_partner_id'=>$web_partner_id])
        ->get()
        ->getRowArray();
        return $query;
    }
    
   
    public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
    

    public function remove_discount($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
 

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','discount_for'=>'web_partner_cruise_discount.discount_for','cruise_ship_id'=>'cruise_ship.ship_name','discount_type'=>'web_partner_cruise_discount.discount_type','cabin_id'=>'cruise_cabin.cabin_name','departure_port_id'=>'cruise_ports.port_name'];
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_cruise_discount.created >=' => $from_date, 'web_partner_cruise_discount.created <=' => $to_date];
                return $this->table('web_partner_cruise_discount')
                    ->select('web_partner_cruise_discount.id,web_partner_cruise_discount.discount_type,web_partner_cruise_discount.agent_class,web_partner_cruise_discount.discount_for,web_partner_cruise_discount.value,web_partner_cruise_discount.max_limit,web_partner_cruise_discount.status,web_partner_cruise_discount.created,web_partner_cruise_discount.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = web_partner_cruise_discount.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_discount.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_discount.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_discount.cabin_id','Left')
                    ->where('web_partner_cruise_discount.web_partner_id',$web_partner_id)->where($array)
                    ->orderBy("web_partner_cruise_discount.id","DESC")->paginate(40);
            } else {
                $array = ['web_partner_cruise_discount.created >=' => $from_date, 'web_partner_cruise_discount.created <=' => $to_date];
                
                return $this->table('web_partner_cruise_discount')
                    ->select('web_partner_cruise_discount.id,web_partner_cruise_discount.discount_type,web_partner_cruise_discount.agent_class,web_partner_cruise_discount.discount_for,web_partner_cruise_discount.value,web_partner_cruise_discount.max_limit,web_partner_cruise_discount.status,web_partner_cruise_discount.created,web_partner_cruise_discount.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = web_partner_cruise_discount.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_discount.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_discount.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_discount.cabin_id','Left')
                    ->where('web_partner_cruise_discount.web_partner_id',$web_partner_id)->where($array)->like($where[trim($data['key'])], trim($data['value']))
                    ->orderBy("web_partner_cruise_discount.id","DESC")->paginate(40);
                
                 
            }
        } else { 
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','discount_for'=>'web_partner_cruise_discount.discount_for','cruise_ship_id'=>'cruise_ship.ship_name','discount_type'=>'web_partner_cruise_discount.discount_type','cabin_id'=>'cruise_cabin.cabin_name','departure_port_id'=>'cruise_ports.port_name'];
           
            return $this->table('web_partner_cruise_discount')
                ->select('web_partner_cruise_discount.id,web_partner_cruise_discount.discount_type,web_partner_cruise_discount.agent_class,web_partner_cruise_discount.discount_for,web_partner_cruise_discount.value,web_partner_cruise_discount.max_limit,web_partner_cruise_discount.status,web_partner_cruise_discount.created,web_partner_cruise_discount.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                ->join('cruise_line', 'cruise_line.id = web_partner_cruise_discount.cruise_line_id','Left')
                ->join('cruise_ship', 'cruise_ship.id = web_partner_cruise_discount.cruise_ship_id','Left')
                ->join('cruise_ports', 'cruise_ports.id = web_partner_cruise_discount.departure_port_id','Left')
                ->join('cruise_cabin', 'cruise_cabin.id = web_partner_cruise_discount.cabin_id','Left')
                ->where('web_partner_cruise_discount.web_partner_id',$web_partner_id)->like($where[trim($data['key'])], trim($data['value']))
                ->orderBy("web_partner_cruise_discount.id","DESC")->paginate(40); 

          
        }
    }
}
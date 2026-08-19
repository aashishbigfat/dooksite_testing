<?php

namespace App\Modules\Coupon\Models;

use CodeIgniter\Model;

class CruiseCouponModel extends Model
{
    protected $table = 'coupon_cruise';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_coupon_list($web_partner_id)
    {
        return  $this->select('coupon_cruise.id,coupon_cruise.code,coupon_cruise.value,coupon_cruise.max_limit,coupon_cruise.status,coupon_cruise.created,coupon_cruise.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
            ->join('cruise_line', 'cruise_line.id = coupon_cruise.cruise_line_id','Left')
            ->join('cruise_ship', 'cruise_ship.id = coupon_cruise.cruise_ship_id','Left')
            ->join('cruise_ports', 'cruise_ports.id = coupon_cruise.departure_port_id','Left')
            ->join('cruise_cabin', 'cruise_cabin.id = coupon_cruise.cabin_id','Left')
            ->where('coupon_cruise.web_partner_id',$web_partner_id)
            ->orderBy("id","DESC")->paginate(40);
    }



    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','markup_for'=>'coupon_cruise.markup_for','cruise_ship_id'=>'cruise_ship.ship_name','markup_type'=>'coupon_cruise.markup_type','cabin_id'=>'cruise_cabin.cabin_name','departure_port_id'=>'cruise_ports.port_name'];
            if ($data['key'] == 'date-range') {
                $array = ['coupon_cruise.created >=' => $from_date, 'coupon_cruise.created <=' => $to_date];
                return $this->table('coupon_cruise')
                    ->select('coupon_cruise.id,coupon_cruise.code,coupon_cruise.value,coupon_cruise.max_limit,coupon_cruise.status,coupon_cruise.created,coupon_cruise.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = coupon_cruise.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = coupon_cruise.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = coupon_cruise.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = coupon_cruise.cabin_id','Left')
                    
                    ->where('coupon_cruise.web_partner_id',$web_partner_id)->where($array)
                    ->orderBy("coupon_cruise.id","DESC")->paginate(40);
            } else {
                $array = ['coupon_cruise.created >=' => $from_date, 'coupon_cruise.created <=' => $to_date];
                
                return $this->table('coupon_cruise')
                    ->select('coupon_cruise.id,coupon_cruise.code,coupon_cruise.value,coupon_cruise.max_limit,coupon_cruise.status,coupon_cruise.created,coupon_cruise.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                    ->join('cruise_line', 'cruise_line.id = coupon_cruise.cruise_line_id','Left')
                    ->join('cruise_ship', 'cruise_ship.id = coupon_cruise.cruise_ship_id','Left')
                    ->join('cruise_ports', 'cruise_ports.id = coupon_cruise.departure_port_id','Left')
                    ->join('cruise_cabin', 'cruise_cabin.id = coupon_cruise.cabin_id','Left')
                   
                    ->where('coupon_cruise.web_partner_id',$web_partner_id)->where($array)->like($where[trim($data['key'])], trim($data['value']))
                    ->orderBy("coupon_cruise.id","DESC")->paginate(40);
                
                 
            }
        } else { 
            $where = ['cruise_line_id'=>'cruise_line.cruise_line_name','markup_for'=>'coupon_cruise.markup_for','cruise_ship_id'=>'cruise_ship.ship_name','markup_type'=>'coupon_cruise.markup_type','departure_port_id'=>'cruise_ports.port_name'];
           
            return $this->table('coupon_cruise')
                ->select('coupon_cruise.id,coupon_cruise.code,coupon_cruise.value,coupon_cruise.max_limit,coupon_cruise.status,coupon_cruise.created,coupon_cruise.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
                ->join('cruise_line', 'cruise_line.id = coupon_cruise.cruise_line_id','Left')
                ->join('cruise_ship', 'cruise_ship.id = coupon_cruise.cruise_ship_id','Left')
                ->join('cruise_ports', 'cruise_ports.id = coupon_cruise.departure_port_id','Left')
                ->join('cruise_cabin', 'cruise_cabin.id = coupon_cruise.cabin_id','Left')
              
                ->where('coupon_cruise.web_partner_id',$web_partner_id)->like($where[trim($data['key'])], trim($data['value']))
                ->orderBy("coupon_cruise.id","DESC")->paginate(40); 

          
        }
    }
 

    public function status_change($ids,$data,$web_partner_id){
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    public function remove_cruise($ids,$web_partner_id){
        return  $this->select('*')->whereIn("id",$ids)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function coupon_cruise_details($id,$web_partner_id)
    {
        $query  =  $this->select('id,web_partner_id,markup_for,agent_class,cruise_line_id,cruise_ship_id,departure_port_id,cabin_id,markup_type,value,max_limit,display_markup,status')
        ->where(["id"=>$id,'web_partner_id'=>$web_partner_id])
        ->get()
        ->getRowArray();
        return $query;
    }


    public function getCouponCodeExists($code,$web_partner_id){

        return $this->select('id')->where('code',$code)->where('web_partner_id',$web_partner_id)->get()->getResultArray();
    }



    public function cruise_coupon_detail_list($id,$web_partner_id)
    {
        return  $this->select('coupon_cruise.travel_date,coupon_cruise.id,coupon_cruise.travel_from,coupon_cruise.coupon_type,coupon_cruise.use_limit,coupon_cruise.code,coupon_cruise.value,coupon_cruise.max_limit,coupon_cruise.status,coupon_cruise.created,coupon_cruise.valid_from,coupon_cruise.valid_to,coupon_cruise.coupon_desc,coupon_cruise.coupon_visible,coupon_cruise.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name,cruise_cabin.cabin_name')
            ->join('cruise_line', 'cruise_line.id = coupon_cruise.cruise_line_id','Left')
            ->join('cruise_ship', 'cruise_ship.id = coupon_cruise.cruise_ship_id','Left')
            ->join('cruise_ports', 'cruise_ports.id = coupon_cruise.departure_port_id','Left')
            ->join('cruise_cabin', 'cruise_cabin.id = coupon_cruise.cabin_id','Left')
            ->where('coupon_cruise.web_partner_id',$web_partner_id)
            ->where('coupon_cruise.id',$id)
            ->orderBy("id","DESC")->get()->getRowArray();
    }
 

}
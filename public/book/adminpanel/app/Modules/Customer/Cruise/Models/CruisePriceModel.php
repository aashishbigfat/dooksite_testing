<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruisePriceModel extends Model
{
    protected $table = 'cruise_price';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function price_list($cruise_list_id)
    {
        return  $this->select('cruise_price.id,cruise_price.selling_date,cruise_price.twin_pax_price,cruise_price.single_pax_price,cruise_price.port_charges,cruise_price.max_pax_stay,cruise_price.book_online,cruise_price.status,cruise_price.created,cruise_price.modified,cruise_cabin.cabin_name,cruise_ports.port_name')
            ->join('cruise_cabin', 'cruise_cabin.id = cruise_price.cruise_cabin_id','left')
            ->join('cruise_list', 'cruise_list.id = cruise_price.cruise_list_id','left')
            ->join('cruise_ports', 'cruise_ports.id = cruise_list.departure_port_id','left')
            ->where('cruise_price.cruise_list_id',$cruise_list_id)->orderBy('cruise_price.id', 'DESC')->paginate(40);
    }


    public function price_details($id)
    {
        return  $this->select('id,selling_date,cruise_cabin_id,cruise_list_id,twin_pax_price,single_pax_price,port_charges,book_online,available_cabin,max_pax_stay,status')->where("id",$id)->get()->getRowArray();
    }

    public function remove_price($id)
    {
        return  $this->select('*')->whereIn("id",$id)->delete();
    }


    public function price_status_change($ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

}



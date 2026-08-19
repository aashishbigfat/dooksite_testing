<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseShipGalleryModel extends Model
{
    protected $table = 'cruise_ship_gallery';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_ship_gallery_list($web_partner_id)
    {
        return  $this->select('cruise_ship_gallery.id,cruise_ship_gallery.images,cruise_ship_gallery.image_title,cruise_ship_gallery.status,cruise_ship_gallery.created,cruise_ship_gallery.modified,cruise_ship.ship_name')
            ->join('cruise_ship', 'cruise_ship.id = cruise_ship_gallery.cruise_ship_id','Left')->where(['cruise_ship_gallery.web_partner_id'=>$web_partner_id])
            ->orderBy('id', 'DESC')->paginate(40);
    }

    public function cruise_ship_gallery_details($id,$web_partner_id)
    {
        return  $this->select('id,images,image_title,status,cruise_ship_id')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['cruise_ship_gallery.created >='=> $from_date,'cruise_ship_gallery.created <='=> $to_date];


                return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                    ->where($array)->orderBy('id', 'DESC')->paginate(40);
            } else {
                $array=['cruise_ship_gallery.created >='=> $from_date,'cruise_ship_gallery.created <='=> $to_date];

                return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                    ->where($array)->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
            }
        } else {
            return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                ->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);

        }
    }

    public function remove_cruise_ship_gallery($id, $web_partner_id)
    {
        return  $this->select('*')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->delete();
    }

    public function delete_image($id, $web_partner_id)
    {
        return  $this->select('images')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }
    public function cruise_ship_gallery_status_change($ids, $data, $web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}



<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelMarkupModel extends Model
{
    protected $table = 'web_partner_hotel_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function hotel_markup_list($web_partner_id)
    {
        return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
            ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);
    }

    public function hotel_markup_details($id,$web_partner_id)
    {
        return $this->select('*')->where("id", $id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
    }


    public function remove_markup($id,$web_partner_id)
    {
        return $this->select('*')->whereIN("id", $id)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    // public function web_partner_class()
    // {

    //     return $this->db->table("web_partner_class")->select('id,class_name')->orderBy("id", "DESC")->get()->getResultArray();


    // }

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_hotel_markup.created >=' => $from_date, 'web_partner_hotel_markup.created <=' => $to_date];

                return $this->select('web_partner_hotel_markup.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

            } else {
                $array = ['super_admin_hotel_markup.created >=' => $from_date, 'super_admin_hotel_markup.created <=' => $to_date];

                return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

            }
        } else {

            return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

        }
    }

     
}
<?php

namespace App\Modules\HotelExtranet\Models;

use CodeIgniter\Model;

class PropertyTypeModel extends Model
{
    protected $table = 'hotel_extranet_property_type';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function property_type_list($web_partner_id)
    {
        return  $this->select('hotel_extranet_property_type.id,hotel_extranet_property_type.property_type,hotel_extranet_property_type.status,hotel_extranet_property_type.created,hotel_extranet_property_type.modified,suppliers.company_id,suppliers.company_name')
        ->join("suppliers", "suppliers.id = hotel_extranet_property_type.supplier_id", 'left')
        ->where(['hotel_extranet_property_type.web_partner_id'=>$web_partner_id])->orderBy("hotel_extranet_property_type.id","DESC")->paginate(40);
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['hotel_extranet_property_type.created >='=> $from_date,'hotel_extranet_property_type.created <='=> $to_date];

                return  $this->select('hotel_extranet_property_type.id,hotel_extranet_property_type.property_type,hotel_extranet_property_type.status,hotel_extranet_property_type.created,hotel_extranet_property_type.modified,suppliers.company_id,suppliers.company_name') ->join("suppliers", "suppliers.id = hotel_extranet_property_type.supplier_id", 'left')->where(['hotel_extranet_property_type.web_partner_id'=>$web_partner_id])->where($array)->orderBy("hotel_extranet_property_type.id","DESC")->paginate(40);
            } else {
                $array=['hotel_extranet_property_type.created >='=> $from_date,'hotel_extranet_property_type.created <='=> $to_date];

                return  $this->select('hotel_extranet_property_type.id,hotel_extranet_property_type.property_type,hotel_extranet_property_type.status,hotel_extranet_property_type.created,hotel_extranet_property_type.modified,suppliers.company_id,suppliers.company_name') ->join("suppliers", "suppliers.id = hotel_extranet_property_type.supplier_id", 'left')->where(['hotel_extranet_property_type.web_partner_id'=>$web_partner_id])->where($array)->like(trim($data['key']),trim($data['value']))->orderBy("hotel_extranet_property_type.id","DESC")->paginate(40);
            }
        } else {
            return  $this->select('hotel_extranet_property_type.id,hotel_extranet_property_type.property_type,hotel_extranet_property_type.status,hotel_extranet_property_type.created,hotel_extranet_property_type.modified,suppliers.company_id,suppliers.company_name') ->join("suppliers", "suppliers.id = hotel_extranet_property_type.supplier_id", 'left')->where(['hotel_extranet_property_type.web_partner_id'=>$web_partner_id])->like(trim($data['key']),trim($data['value']))->orderBy("hotel_extranet_property_type.id","DESC")->paginate(40);
        }
    }

	public function remove_property_type($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

	public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

    public function property_type_details($id,$web_partner_id)
    {
        return  $this->select('id,property_type,status,')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function property_type_select($web_partner_id)
    {
        return  $this->select('id,property_type')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
    
}



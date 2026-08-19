<?php

namespace App\Modules\Cruise\Models;

use CodeIgniter\Model;

class CruiseLineModel extends Model
{
    protected $table = 'cruise_line';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_line_list($web_partner_id)
    {
        return  $this->select('id,cruise_line_name,cruise_line_name_slug,status,cruise_line_image,modified,created')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->paginate(40);
    }

    public function cruise_line_details($id,$web_partner_id)
    {
        return  $this->select('id,cruise_line_name,cruise_line_name_slug,status,cruise_line_image')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }
    public function cruise_line_select($web_partner_id)
    {
        return  $this->select('id,cruise_line_name')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,cruise_line_name,cruise_line_name_slug,status,cruise_line_image,modified,created')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,cruise_line_name,cruise_line_name_slug,status,cruise_line_image,modified,created')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('id,cruise_line_name,cruise_line_name_slug,status,cruise_line_image,modified,created')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_cruise_line($id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('cruise_line_image')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }


    public function cruise_line_status_change($ids, $data, $web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }


}



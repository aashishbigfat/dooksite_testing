<?php

namespace App\Modules\Feedback\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table = 'customer_feedback';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function feedback_list($web_partner_id)
    {
        return  $this->select('id,name,email,phone,status,image,feedback_date,created')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->paginate(40);
    }

    public function feedback_details($id,$web_partner_id)
    {
        return  $this->select('id,name,email,phone,status,image,feedback_date,created,description')->where('web_partner_id',$web_partner_id)->where("id",$id)->get()->getRowArray();
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
                return  $this->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_feedback($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where('web_partner_id',$web_partner_id)->delete();
    }
    

    public function feedback_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('image')->where('web_partner_id',$web_partner_id)->where("id",$id)->get()->getRowArray();
    }

}



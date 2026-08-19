<?php

namespace App\Modules\TourGuide\Models;

use CodeIgniter\Model;

class TourGuideModel extends Model
{
    protected $table = 'tourguide';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function tour_guide_list($web_partner_id)
    {
        return $this->select('id,guide_name,tag_line,location_id,email,profile_image,description,per_day_rate,language_known,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->paginate(40);
    }

    public function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,guide_name,tag_line,email,profile_image,description,per_day_rate,language_known,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,guide_name,tag_line,email,profile_image,description,per_day_rate,language_known,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('id,guide_name,tag_line,email,profile_image,description,per_day_rate,language_known,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function delete_image($id,$web_partner_id)
    {
        return $this->select('profile_image,document')->where(['id'=> $id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_tourguide($ids, $web_partner_id)
    {
        if (!is_array($ids)) {
            $ids = [$ids]; // Convert to an array if it's not already one.
        }
    
        return $this->select('*')
                    ->whereIn('id', $ids)
                    ->where(['web_partner_id' => $web_partner_id])
                    ->delete();
    }
    public function tourguide_status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

    public function tourguide_details($id,$web_partner_id)
    {
        return $this->select('id,guide_name,description,tag_line,rating,profile_image,email,mobile_no,city,location_id,country_name,booking_starting_time,booking_ending_time,language_known,guide_type,gender,verified,response_time,per_day_rate,status,document')->where(['web_partner_id'=>$web_partner_id])->where("id", $id)->get()->getRowArray();
    }

    public function get_country_details(){
        return $this->db->table('countries')->select('name')->get()->getResultArray();
    }

    

    public function search_data_sales_report($data,$page){
        $builder = $this->db->table('tourguide_booking_list.*,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
                                    web_partner.company_name,web_partner.company_id,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name')
            ->join("admin_users", "admin_users.id=tourguide_booking_list.agent_staff_id", 'left')
            ->join('web_partner', "tourguide_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = tourguide_booking_list.assign_user", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['tourguide_booking_list.created >=' => $from_date, 'tourguide_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['tourguide_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        $builder->where('tourguide_booking_list.payment_status','Successful');
        $builder->whereNotIn("tourguide_booking_list.booking_status", ['Failed','Processing']);
        return  $builder->groupBy("tourguide_booking_list.id")->orderBy("tourguide_booking_list.id", "DESC")->paginate($page);
    }

    function getBulkData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getResultArray();
    }


    public function tour_destination_list_select_ajax($data,$web_partner_id)
    {
        return  $this->select('id,guide_name as value')->where(["web_partner_id"=>$web_partner_id])->like("guide_name",$data)->get()->getResultArray();
    }


 
}

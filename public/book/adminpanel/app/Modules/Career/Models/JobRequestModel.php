<?php

namespace App\Modules\Career\Models;

use CodeIgniter\Model;

class JobRequestModel extends Model
{
    protected $table = 'career_job_request';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function job_application_list($web_partner_id)
    {
        return $this->db->table('career_job_request')
            ->select('career_job_request.title, career_job_request.web_partner_id, career_job_request.id, career_job_request.email, career_job_request.mobile, career_job_request.first_name, career_job_request.last_name, career_job_request.city, career_job_request.notice_period, career_job_request.current_organization, career_job_request.total_experience, career_job_request.current_salary, career_job_request.created, career_job_request.resume_file, career_job_post.job_title, career_category.job_category')
            ->where('career_job_request.web_partner_id', $web_partner_id)
            ->join('career_job_post', 'career_job_post.category_id = career_job_request.job_post_id', 'left')
            ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')
            ->groupBy('career_job_request.id')
            ->orderBy('career_job_request.job_post_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('resume_file')->where("id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
    }

    public function remove_job_application($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where('web_partner_id',$web_partner_id)->delete();
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
                return $this->db->table('career_job_request')
                ->select('career_job_request.title, career_job_request.web_partner_id, career_job_request.id, career_job_request.email, career_job_request.mobile, career_job_request.first_name, career_job_request.last_name, career_job_request.city, career_job_request.notice_period, career_job_request.current_organization, career_job_request.total_experience, career_job_request.current_salary, career_job_request.created, career_job_request.resume_file, career_job_post.job_title, career_category.job_category')
                ->where('career_job_request.web_partner_id', $web_partner_id)
                ->where($array)
                ->join('career_job_post', 'career_job_request.job_post_id = career_job_post.category_id', 'left')
                ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')
                ->groupBy('career_job_request.id')
                ->orderBy('career_job_request.job_post_id', 'DESC')
                ->get()
                ->getResultArray();


            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return $this->db->table('career_job_request')
                ->select('career_job_request.title, career_job_request.web_partner_id, career_job_request.id, career_job_request.email, career_job_request.mobile, career_job_request.first_name, career_job_request.last_name, career_job_request.city, career_job_request.notice_period, career_job_request.current_organization, career_job_request.total_experience, career_job_request.current_salary, career_job_request.created, career_job_request.resume_file, career_job_post.job_title, career_category.job_category')
                ->where('career_job_request.web_partner_id', $web_partner_id)
                ->join('career_job_post', 'career_job_request.job_post_id = career_job_post.category_id', 'left')
                ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')
                ->groupBy('career_job_request.id')
                ->orderBy('career_job_request.job_post_id', 'DESC')
                ->where($array)->like(trim($data['key']),trim($data['value']))
                ->get()
                ->getResultArray();
            }
        } else {
          
            return $this->db->table('career_job_request')
            ->select('career_job_request.title, career_job_request.web_partner_id, career_job_request.id, career_job_request.email, career_job_request.mobile, career_job_request.first_name, career_job_request.last_name, career_job_request.city, career_job_request.notice_period, career_job_request.current_organization, career_job_request.total_experience, career_job_request.current_salary, career_job_request.created, career_job_request.resume_file, career_job_post.job_title, career_category.job_category')
            ->where('career_job_request.web_partner_id', $web_partner_id)
            ->join('career_job_post', 'career_job_request.job_post_id = career_job_post.category_id', 'left')
            ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')
            ->groupBy('career_job_request.id')
            ->orderBy('career_job_request.job_post_id', 'DESC')
            ->like(trim($data['key']),trim($data['value']))
            ->get()
            ->getResultArray();
        }
    }
    
}



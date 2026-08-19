<?php

namespace App\Modules\Career\Models;

use CodeIgniter\Model;

class CareerModel extends Model
{
    protected $table = 'career_job_post';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function career_list($web_partner_id)
    {
        return $this->db->table('career_job_post')
            ->select('career_job_post.id,career_job_post.job_title,career_job_post.job_type,career_job_post.location,career_job_post.offer_salary,career_job_post.status,career_job_post.created,career_job_post.modified, career_category.job_category')
            ->where('career_job_post.web_partner_id', $web_partner_id)
            ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')
            ->orderBy('career_job_post.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function career_details($id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
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
                return $this->db->table('career_job_post')
            ->select('career_job_post.id,career_job_post.job_title,career_job_post.job_type,career_job_post.location,career_job_post.offer_salary,career_job_post.status,career_job_post.created,career_job_post.modified, career_category.job_category')
            ->where('career_job_post.web_partner_id', $web_partner_id)
            ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')->get()->getResultArray();
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return $this->db->table('career_job_post')
                ->select('career_job_post.id,career_job_post.job_title,career_job_post.job_type,career_job_post.location,career_job_post.offer_salary,career_job_post.status,career_job_post.created,career_job_post.modified, career_category.job_category')
                ->where('career_job_post.web_partner_id', $web_partner_id)
                ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')->where($array)->like(trim($data['key']),trim($data['value']))->get()->getResultArray();
            }
        } else {
            return $this->db->table('career_job_post')
            ->select('career_job_post.id,career_job_post.job_title,career_job_post.job_type,career_job_post.location,career_job_post.offer_salary,career_job_post.status,career_job_post.created,career_job_post.modified, career_category.job_category')
            ->where('career_job_post.web_partner_id', $web_partner_id)
            ->join('career_category', 'career_category.id = career_job_post.category_id', 'left')->like(trim($data['key']),trim($data['value']))->get()->getResultArray();
        }
    }

    public function remove_career($id,$web_partner_id)
    {

        return  $this->select('*')->whereIn("id",$id)->where('web_partner_id',$web_partner_id)->delete();

    }

    public function career_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
    

    public function career_categories_list($web_partner_id)
    {
        return $this->db->table('career_category')->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function insertData($table, $data)
    {
        $builder = $this->db->table($table);
        $result = $builder->insert($data);
        return $result;
    }
    public function updateData($table,$where,$data)
    {
        return $this->db->table($table)->where($where)->update($data);
    }

    public function career_search_data($data,$web_partner_id)
    {
        $query = $this->db->table('career_category')->select('*')->orderBy('id', 'DESC');
    
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
    
            if ($data['key'] == 'date-range') {
                $query->where('created >=', $from_date)->where('created <=', $to_date);
            } else {
                $query->where('created >=', $from_date)
                      ->where('created <=', $to_date)
                      ->where('web_partner_id',$web_partner_id)
                      ->like(trim($data['key']), trim($data['value']));
            }
        } else {
            $query->where('web_partner_id',$web_partner_id)->like($data['key'], $data['value']);
        }
    
        return $query->get()->getResultArray();
    }
    

    public function career_categories_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->db->table('career_category')->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
    public function remove_career_categories($id,$web_partner_id)
    {
        return $this->db->table('career_category')->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();

    }

    public function categories_list_details($id,$web_partner_id)
    {
        return $this->db->table('career_category')->select('id,job_category,slug_url,status')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }
}



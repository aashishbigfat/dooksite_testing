<?php

namespace App\Modules\Feedback\Models;
use CodeIgniter\Model; 

class FeedbackModel extends Model
{

    protected $table = 'customer_feedback';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function offers_list()
    {
        $data['flight'] =  $this->db->table('super_admin_offers')
        ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'flight')
            ->limit(4)->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        $data['hotel'] =  $this->db->table('super_admin_offers')
        ->select('id,title,description,service,url,image')
            ->where('status', 'active')->where('service', 'hotel')->limit(4)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        $data['activity'] =  $this->db->table('super_admin_offers')
        ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'activity')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        $data['bus'] =  $this->db->table('super_admin_offers')
        ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'bus')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        $data['holiday'] =  $this->db->table('super_admin_offers')
        ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'holiday')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        return  $data;
    }

    
    public function get_feedback_model_list($web_partner_id)
    {
        $data = $this->db->table('customer_feedback')
            ->select('*')
            ->where('status','active')
            ->orderBy('id', 'DESC')
            ->where('web_partner_id',$web_partner_id) 
            ->get()
            ->getResultArray();

        return $data;
    }
    public function get_feedback_model_listRead_more($web_partner_id)
    {
        $data = $this->db->table('customer_feedback')
            ->select('*')
            ->where('status','active')
            ->where('web_partner_id',$web_partner_id)
            ->orderBy('id', 'DESC') 
            ->get()
            ->getResultArray();

        return $data;
    }
 
 
}

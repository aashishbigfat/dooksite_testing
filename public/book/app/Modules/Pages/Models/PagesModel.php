<?php

namespace App\Modules\Pages\Models;

use CodeIgniter\Model;

class PagesModel extends Model
{
    protected $table = 'pages';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function get_page_details($slug, $web_partner_id)
    {   
        return $this->select('*')->where(["slug_url" => $slug, "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }



    public function InsertData($tableName, $data)
    {
        return $this->db->table($tableName)->insert($data);
    }

    public function getEmail($email, $web_partner_id)
    {
        return $this->db->table('newsletter')->where(['email' => $email, 'web_partner_id' => $web_partner_id])->get()->getResult();
    }
    public function webcheckin_list($web_partner_id)
    {
        return $this->db->table('web_check_in')->where(['web_partner_id' => $web_partner_id])->get()->getResultArray();
    }

    // public function offers_list($web_partner_id)
    // {
    //     return $this->db->table('web_partner_offers')
    //         ->select('id,title,description,service,url,image')
    //         ->where('status', 'active')
    //         ->where('service', 'flight')
    //         ->groupStart() 
    //         ->where(["web_partner_offers.web_partner_id"=>$web_partner_id]) 
    //         ->orWhere(["web_partner_offers.web_partner_id"=>0]) 
    //         ->groupEnd() 
    //         ->orderBy('id', 'DESC')
    //         ->get()
    //         ->getResultArray();    
    // }


    public function offers_list($web_partner_id)
    {
        $responseArray = array();
        $datas = $this->db->table('web_partner_offers')->select('id,title,description,service,url,image')
            ->where('status', 'active')->where(["web_partner_offers.web_partner_id" => $web_partner_id])->orderBy('id', 'DESC')->get()->getResultArray();
        if ($datas) {
            foreach ($datas as $value) {
                if (isset($responseArray[$value['service']])) {
                    array_push($responseArray[$value['service']], $value);
                } else {
                    $responseArray[$value['service']][0] = $value;
                }
            }

        }
        return $responseArray;
    }

}



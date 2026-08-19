<?php

namespace App\Modules\Query\Models;

use CodeIgniter\Model;

class WeddingqueryModel extends Model
{
    protected $table = 'wedding_query';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function wedding_query_list($web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id' => $web_partner_id])->paginate(40);
    }

    function search_data($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['wedding_query.created >=' => $from_date, 'wedding_query.created <=' => $to_date];
                return $this->select('*')->where('web_partner_id', $web_partner_id)->where($array)->paginate(40);
            } else {
                $array = ['wedding_query.created >=' => $from_date, 'wedding_query.created <=' => $to_date];
                return $this->select('*')->where('web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->where('web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function weddingquery_list_details($id, $web_partner_id)
    {
        return $this->db->table('wedding_query')->select('*')->where(["web_partner_id" => $web_partner_id, "id" => $id])->get()->getRowArray();
    }

    public function remove_query($ids, $web_partner_id)
    {
        return $this->select('*')->where("id", $ids)->where(['web_partner_id' => $web_partner_id])->delete();

    }
}



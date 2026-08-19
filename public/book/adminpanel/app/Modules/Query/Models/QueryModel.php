<?php

namespace App\Modules\Query\Models;

use CodeIgniter\Model;

class QueryModel extends Model
{
    protected $table = 'contact_us';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function query_list($web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id' => $web_partner_id])->orderBy("id", "DESC")->paginate(40);
    }

    function search_data($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['contact_us.created >=' => $from_date, 'contact_us.created <=' => $to_date];
                return $this->select('contact_us.*')
                    ->where('contact_us.web_partner_id', $web_partner_id)->where($array)->paginate(40);
            } else {
                $array = ['contact_us.created >=' => $from_date, 'contact_us.created <=' => $to_date];
                return $this->select('contact_us.*')
                    ->where('contact_us.web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('contact_us.*')
                ->where('contact_us.web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }
    public function remove_query($ids, $web_partner_id)
    {

        return $this->select('*')->where("id", $ids)->where(['web_partner_id' => $web_partner_id])->delete();

    }
}



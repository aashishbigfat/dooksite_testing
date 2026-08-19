<?php

namespace App\Modules\Currency\Models;

use CodeIgniter\Model;

class CurrencyModel extends Model
{
    protected $table = 'currency';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function currency_list($web_partner_id)
    {
    return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->paginate(40);
    }

    public function currency_details($id,$web_partner_id)
    {
        return $this->select('id,currency,convertion_rate,currency_name, country,currency_symbol,default_currency,status,decimal_point,created,modified')->where(["web_partner_id"=> $web_partner_id,"id"=>$id])->get()->getRowArray();
    }

    function search_data($data, $web_partner_id)
    {
      
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id' => $web_partner_id])->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id' => $web_partner_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->where(['web_partner_id' => $web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

   

    public function remove_currency($ids, $web_partner_id)
    {
        return $this->select('*')->whereIn("id", $ids)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function currency_status_change($web_partner_id, $ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }

    function get_currency()
    {
        return $this->db->table('countries')->select('id,name,currency,currency_name,currency_symbol')->get()->getResultArray();
    }


    public function get_unique_country($country,$web_partner_id)
    {
        return $this->select('id,currency,convertion_rate,currency_name, country,currency_symbol,default_currency,status,decimal_point,created,modified')->where(["country"=>$country,"web_partner_id"=> $web_partner_id])->get()->getResultArray();
    }


    // public function getgsm($value,$web_partner_id)
    // {
    //   return $this->select('id,type,value,status,created,modified')->where(['type' => 'GSM'])->where(['value'=>$value,'web_partner_id'=>$web_partner_id])->paginate(40);
    // }


   


    public function change_default_Status($web_partner_id, $ids, $data)
    {
       
        return $this->select('*')->where('web_partner_id', $web_partner_id)->where('id', $ids)->set($data)->update();
    }

    public function check_default_status($web_partner_id, $ids) {
        return $this->select("default_currency as defaultStatus")
        ->where('web_partner_id', $web_partner_id)->where('id', $ids)
        ->get()
        ->getRowArray();
    }
    function updateData($tableName, $whereClause, $data)
    {
        $this->db->table($tableName)->where($whereClause)->update($data);
    }

   


}



<?php

namespace App\Modules\Customer\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class CustomerModel extends Model
{
    protected $table = 'customer';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function customer_list($web_partner_id)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('customer_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('customer_account_log')->orderBy('id', 'DESC')->groupBy('customer_id');
        });
        $subquery = $builder->getCompiledSelect();


        return $this->select('customer.id,customer.customer_id,customer.title,customer.first_name,customer.last_name,customer.email_id,customer.email_verify,customer.dial_code,customer.mobile_no,customer.mobile_verify,customer.status,customer.created,customer.modified,customer_account_log.balance')
            ->join("($subquery) customer_account_log", 'customer_account_log.customer_id = customer.id', 'left')
            ->orderBy('customer.id', 'DESC')
            ->where('customer.web_partner_id', $web_partner_id)
            ->paginate(40);
    }

    public function customer_export($web_partner_id, $data)
    {

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['customer.created >=' => $from_date, 'customer.created <=' => $to_date];

            $db = \Config\Database::connect();
            $builder = $db->table('customer_account_log');
            $builder->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('customer_account_log')->orderBy('id', 'DESC')->groupBy('customer_id');
            });
            $subquery = $builder->getCompiledSelect();

            return $this->select('customer.*,customer_account_log.balance')
                ->join("($subquery) customer_account_log", 'customer_account_log.customer_id = customer.id', 'left')
                ->orderBy('customer.id', 'DESC')
                ->where('customer.web_partner_id', $web_partner_id)
                ->where($array)->get()->getResultArray();
        } else {
            return $this->select('*')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->get()->getResultArray();
        }
    }


    function search_data($web_partner_id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('customer_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('customer_account_log')->orderBy('id', 'DESC')->groupBy('customer_id');
        });
        $subquery = $builder->getCompiledSelect();
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['customer.created >=' => $from_date, 'customer.created <=' => $to_date];

                return $this->select('customer.id,customer.customer_id,customer.title,customer.first_name,customer.last_name,customer.email_id,customer.email_verify,customer.dial_code,customer.mobile_no,customer.mobile_verify,customer.status,customer.created,customer.modified,customer_account_log.balance')
                    ->join("($subquery) customer_account_log", 'customer_account_log.customer_id = customer.id', 'left')
                    ->orderBy('customer.id', 'DESC')
                    ->where('customer.web_partner_id', $web_partner_id)->where($array)
                    ->paginate(40);
            } else {
                $array = ['customer.created >=' => $from_date, 'customer.created <=' => $to_date];

                return $this->select('customer.id,customer.customer_id,customer.title,customer.first_name,customer.last_name,customer.email_id,customer.email_verify,customer.dial_code,customer.mobile_no,customer.mobile_verify,customer.status,customer.created,customer.modified,customer_account_log.balance')
                    ->join("($subquery) customer_account_log", 'customer_account_log.customer_id = customer.id', 'left')
                    ->orderBy('customer.id', 'DESC')
                    ->where('customer.web_partner_id', $web_partner_id)->where($array)->where($array)->like(trim($data['key']), trim($data['value']))
                    ->paginate(40);
            }
        } else {
            return $this->select('customer.id,customer.customer_id,customer.title,customer.first_name,customer.last_name,customer.email_id,customer.email_verify,customer.dial_code,customer.mobile_no,customer.mobile_verify,customer.status,customer.created,customer.modified,customer_account_log.balance')
                ->join("($subquery) customer_account_log", 'customer_account_log.customer_id = customer.id', 'left')
                ->orderBy('customer.id', 'DESC')
                ->where('customer.web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))
                ->paginate(40);
        }
    }

/*     public function customerinfo($data, $web_partner_id)
    {
        return $this->select('id,title,first_name,last_name,email_id,mobile_no')->where('web_partner_id', $web_partner_id)
            ->like(trim('first_name'), trim($data))->orderBy('id', 'DESC')
            ->orLike(trim('last_name'), trim($data))->orderBy('id', 'DESC')
            ->get()->getResultArray();
    } */



    public function customerinfo($data, $web_partner_id)
    {
        return $this->select('id, title, first_name, last_name, email_id, mobile_no')
            ->where('web_partner_id', $web_partner_id)
            ->groupStart()
                ->like(trim('first_name'), trim($data))
                ->orLike(trim('last_name'), trim($data))
                ->orGroupStart()
                    ->like(trim('email_id'), trim($data)) 
                    ->orLike(trim('mobile_no'), trim($data))
                ->groupEnd()
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }
    

    public function delete_image($id, $web_partner_id)
    {
        return $this->select('profile_pic')->where("id", $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function customer_details($id, $web_partner_id)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->where("id", $id)->get()->getRowArray();
    }

    public function remove_customer($id, $web_partner_id)
    {

        return $this->select('*')->where('web_partner_id', $web_partner_id)->where("id", $id)->delete();

    }

    public function customer_status_change($web_partner_id, $ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }
    function checkRegisterCustomer($email, $web_partner_id, $customer_id = null)
    {
        $builder = $this->db->table("customer")->select("customer.id as customer_id")->where(["email_id" => $email, "web_partner_id" => $web_partner_id]);
        if ($customer_id != null) {
            $builder->where(["id!=" => $customer_id]);
        }
        return $builder->get()->getNumRows();
    }
}
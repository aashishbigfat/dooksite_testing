<?php

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function api_webpartner_setting($web_partner_id)
    {
        return $this->db->table("api_webpartner_setting")->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    function updateUserData($tableName, $whereClause, $data)
    {
        return $this->db->table($tableName)->where($whereClause)->update($data);
    }


    public function default_currency($web_partner_id)
    {
        return $this->db->table("currency")->select('*')->where(['web_partner_id' => $web_partner_id, 'default_currency' => 'active', 'status' => 'active'])->orderBy("id", "DESC")->get()->getRowArray();
    }



    public function getAllCurrency($web_partner_id)
    {
        return $this->db->table("currency")->select('*')->where(['web_partner_id' => $web_partner_id, 'status' => 'active'])->orderBy("id", "DESC")->get()->getResultArray();
    }



    public function webpartner_whitelabel_details_bydomain($webpartner_url)
    {
        return $this->db->table("whitelabel_webpartner_setting")->select('*')->whereIn('domain_name', $webpartner_url)->orderBy("id", "DESC")->get()->getRowArray();
    }



    public function getWebPartnerDetails($web_partner_id)
    {
        $query = $this->db->table('web_partner')
            ->select('web_partner.*, api_webpartner_setting.*')
            ->join('api_webpartner_setting', 'api_webpartner_setting.web_partner_id = web_partner.id', 'left')
            ->where('web_partner.status', 'active')
            ->where('web_partner.id', $web_partner_id)
            ->orderBy('web_partner.id', 'DESC')->get()->getRowArray();
        return $query;
    }



    public function gettingCountryCodeWithCountryName()
    {
        return $this->db->table("countries")->select('name as CountryName,iso2 as CountryCode')->orderBy("name", "ASC")->get()->getResultArray();
    }


    public function web_partner_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function get_payment_detail($order_id)
    {
        return $this->db->table("super_admin_payment_transaction")->select('id,web_partner_id,user_id')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }



    public function get_user_detail($user_id, $web_partner_id)
    {
        return $this->db->table("admin_users")->select('*')->where(array('id' => $user_id, 'web_partner_id' => $web_partner_id))->limit(1)->get()->getRowArray();
    }



    public function admin_website_setting($whitelabel_web_partner_id)
    {
        $Query = $this->db->table("web_partner")->select('*')->where('id', $whitelabel_web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
        $whitelabel_webpartner_setting = $this->db->table("whitelabel_webpartner_setting")->select('email_setting,payment_gateway_setting,sms_setting')->orderBy("id", "DESC")->get()->getRowArray();
        $Query['email_setting'] = $whitelabel_webpartner_setting['email_setting'];
        $Query['payment_gateway_setting'] = $whitelabel_webpartner_setting['payment_gateway_setting'];
        $Query['sms_setting'] = $whitelabel_webpartner_setting['sms_setting'];
        return $Query;
    }




    function getApiFlighFareType()
    {
        $fareTypes = array();
        $builder = $this->db->table('api_flight_fare_type');
        $builder->select('supplier_fare_type,api_fare_type');
        $result = $builder->get()->getResultArray();
        if ($result) {
            $fareTypes = array_column($result, 'api_fare_type', 'supplier_fare_type');
        }
        return $fareTypes;
    }



    function getInvoiceSuffixData($whereCondition, $otherParameter)
    {
        $field = '';
        if ($otherParameter['checkTaxableInvoce'] == 1) {
            $field = 'taxable_couter as couter,taxable_prefix as prefix,financial_year';
        } else {
            $field = 'nontaxable_couter as couter,nontaxable_prefix as prefix,financial_year';
        }

        $builder = $this->db->table('invoice_suffix_list')->select($field);
        $builder->where($whereCondition);
        $data = $builder->get()->getRowArray();
        if ($data) {
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
            return $data;
        } else {
            $insertData['web_partner_id'] = $otherParameter['web_partner_id'];
            $insertData['financial_year'] = $otherParameter['financialYear'];
            $insertData['service'] = $otherParameter['service'];
            $insertData['taxable_prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
            $insertData['nontaxable_prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
            $insertData['taxable_couter'] = 1;
            $insertData['nontaxable_couter'] = 1;
            $insertData['invoice_type'] = $otherParameter['invoice_type'];
            $this->db->table('invoice_suffix_list')->insert($insertData);
            if ($otherParameter['checkTaxableInvoce'] == 1) {
                $data['prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            } else {
                $data['prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            }
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
        }
        return $data;
    }



    public function getRowData($table, $field = '*', $condn = [], $orderBy = null, $groupBy = null, $joinTable = null, $joinOn = null, $joinField = null, $joinType = null)
    {
        $builder = $this->db->table($table)->select($field);

        if (!empty($condn)) {
            $builder->where($condn);
        }

        if ($joinTable !== null && $joinOn !== null) {
            $joinType = ($joinType === null) ? 'left' : $joinType;
            $builder->join($joinTable, $joinOn, $joinType);

            if ($joinField !== null) {
                $builder->select($joinField);
            }
        }

        if(!empty($orderBy)) {
            $builder->orderBy($orderBy);
        }
        if(!empty($groupBy)) {
            $builder->groupBy($groupBy);
        }
        $result = $builder->get()->getRowArray();

        return $result;
    }




    public function getArrayData($table, $field = '*', $condn = [], $joinTable = null, $joinOn = null, $joinField = null, $joinType = null)
    {
        $builder = $this->db->table($table)->select($field);

        if (!empty($condn)) {
            $builder->where($condn);
        }

        if ($joinTable !== null && $joinOn !== null) {
            $joinType = ($joinType === null) ? 'left' : $joinType;
            $builder->join($joinTable, $joinOn, $joinType);

            if ($joinField !== null) {
                $builder->select($joinField);
            }
        }

        $result = $builder->get()->getResultArray();

        return $result;
    }



    public function insertData($table, $insertData)
    {
        $this->db->table($table)->insert($insertData);

        $isInserted = $this->db->affectedRows();
        if ($isInserted) {
            return $this->db->insertID();
        }
        return false;
    }



    public function insertBatchData($table, $insertData)
    {
        $this->db->table($table)->insertBatch($insertData);
        $isInserted = $this->db->affectedRows();
        if ($isInserted) {
            return $this->db->insertID();
        }
        return false;
    }



    public function updateData($table, $whereClause, $data)
    {
        $this->db->table($table)->where($whereClause)->update($data);

        $isUpdated = $this->db->affectedRows();
        if ($isUpdated === false) {
            return false;
        }
        return true;
    }



    public function updateBatchData($table, $data, $index)
    {
        $this->db->table($table)->updateBatch($data, $index);

        $isUpdated = $this->db->affectedRows();

        if ($isUpdated === false) {
            return false;
        }
        return true;
    }



    public function deleteData($table, $whereClause)
    {
        $this->db->table($table)->where($whereClause)->delete();

        $isDeleted = $this->db->affectedRows();
        if ($isDeleted === false) {
            return false;
        }
        return true;
    }


    public function deleteBatchData($table, $data, $index)
    {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        $this->db->table($table)->whereIn($index, $data)->delete();

        $isDeleted = $this->db->affectedRows();

        if ($isDeleted === false) {
            return false;
        }
        return true;
    }





    function getDataRowType($tableName, $whereCondition, $field)
    {
        $builder = $this->db->table($tableName)->select($field);
        if ($whereCondition) {
            $builder->where($whereCondition);
        }
        return $builder->get()->getRowArray();
    }


    function insertIntoLogs($table, $emamilLogsData)
    {
        $query = $this->db->table($table)->select('id')->where(['booking_info' => $emamilLogsData['booking_info'], 'service' => $emamilLogsData['service']])->get()->getRowArray();
        if (!$query) {
            $this->db->table($table)->insert($emamilLogsData);
            return $this->db->insertID();
        } else {
            return true;
        }
    }
}

<?php

namespace App\Modules\SupplierManagement\Models;

use CodeIgniter\Model;

class ApiSupplierFlightMgtModel extends Model
{
    protected $table = 'api_supplier_flight_mgt';
    protected $primarykey = 'id';
    protected $protectFields = false;



    
    public function api_supplier($web_partner_id)
    {
        return $this->db->table("api_supplier")
            ->select('id,supplier_name, status, flight, hotel, bus, credentials, account_id,created,modified')->where('web_partner_id',$web_partner_id)->where('activation_status','active')
            ->get()->getResultArray();
    }
    public function edit_api_supplier($id,$web_partner_id)
    {
        return $this->db->table("api_supplier")
            ->select('supplier_name,flight,hotel,bus,credentials')->where('id',$id)->where('web_partner_id',$web_partner_id)->where('activation_status','active')
            ->get()->getRowArray();
    }


    public function api_supplier_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->db->table("api_supplier")->select('*')->where('web_partner_id',$web_partner_id)->where('activation_status','active')->whereIn('id', $ids)->set($data)->update();
    }

    public function supplier_mgt_list()
    {
        return  $this->select('api_supplier_flight_mgt.id,api_supplier_flight_mgt.allowed_airline,api_supplier_flight_mgt.excluded_airline,
        api_supplier_flight_mgt.air_type,api_supplier_flight_mgt.search_type,api_supplier_flight_mgt.created,api_supplier.supplier_name,api_supplier_flight_mgt.status
        ')->join('api_supplier', 'api_supplier_flight_mgt.api_supplier_id=api_supplier.id', 'Left')->orderBy('id', 'DESC')->paginate(40);
    }

    public function supplier_list()
    {

        return  $this->db->table("api_supplier")->select('id,supplier_name,flight')->orderBy("id", "DESC")->get()->getResultArray();
    }
    public function api_account_id()
    {
        return  $this->db->table("api_account_id")->select('id,api_supplier_name,username,status,password')->orderBy("id", "DESC")->get()->getResultArray();
    }


    public function supplier_list_deal()
    {

        return  $this->db->table("api_supplier")->select('id,supplier_name')->where('is_deal', 'Yes')->orderBy("id", "DESC")->get()->getResultArray();
    }
    function updateData($tableName, $where, $data)
    {

        return $this->db->table($tableName)->where($where)->update($data);
    }


    function ApiSupplierStatus($id, $updateData)
    {
        $builder = $this->db->table("api_supplier");
        $builder->where('id', $id)->set($updateData)->update();
    }

    function search_data($data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['api_supplier_flight_mgt.created >=' => $from_date, 'api_supplier_flight_mgt.created <=' => $to_date];


                return  $this->select('api_supplier_flight_mgt.id,api_supplier_flight_mgt.allowed_airline,api_supplier_flight_mgt.excluded_airline,
        api_supplier_flight_mgt.air_type,api_supplier_flight_mgt.search_type,api_supplier_flight_mgt.created,api_supplier.supplier_name,api_supplier_flight_mgt.status
        ')->join('api_supplier', 'api_supplier_flight_mgt.api_supplier_id=api_supplier.id', 'Left')->where($array)->orderBy('id', 'DESC')->paginate(40);
            } else {
                $array = ['api_supplier_flight_mgt.created >=' => $from_date, 'api_supplier_flight_mgt.created <=' => $to_date];


                return  $this->select('api_supplier_flight_mgt.id,api_supplier_flight_mgt.allowed_airline,api_supplier_flight_mgt.excluded_airline,
                    api_supplier_flight_mgt.air_type,api_supplier_flight_mgt.search_type,api_supplier_flight_mgt.created,api_supplier.supplier_name,api_supplier_flight_mgt.status
                    ')->join('api_supplier', 'api_supplier_flight_mgt.api_supplier_id=api_supplier.id', 'Left')->where($array)->like(trim($data['key']), trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
            }
        } else {

            return  $this->select('api_supplier_flight_mgt.id,api_supplier_flight_mgt.allowed_airline,api_supplier_flight_mgt.excluded_airline,
        api_supplier_flight_mgt.air_type,api_supplier_flight_mgt.search_type,api_supplier_flight_mgt.created,api_supplier.supplier_name,api_supplier_flight_mgt.status
        ')->join('api_supplier', 'api_supplier_flight_mgt.api_supplier_id=api_supplier.id', 'Left')->like(trim($data['key']), trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
        }
    }

    public function remove($id)
    {
        return  $this->select('*')->whereIn("id", $id)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);

        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }


    public function details($id)
    {
        return  $this->select('*')->where("id", $id)->get()->getRowArray();
    }
 



















}

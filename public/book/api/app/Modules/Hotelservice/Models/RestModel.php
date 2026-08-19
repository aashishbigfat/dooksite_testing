<?php

namespace App\Modules\Hotelservice\Models;

use CodeIgniter\I18n\Time;

use CodeIgniter\Model;

class RestModel extends Model
{

    function get_api_supplier()
    {
        $builder = $this->db->table('api_supplier');
        $builder->select('supplier_name,hotel');
        $builder->where('status', 'active');
        $builder->where('hotel', 'active');
        $array = $builder->get()->getResultArray();
        return array_column($array, 'supplier_name', 'supplier_name');
    }
    function insertBatchData($table, $data)
    {
        $db = \Config\Database::connect('api');
        $db->table($table)->insertBatch($data);
    }

    function insert_tts_hotel_logs($web_partner_id, $tts_search_token, $request, $response, $service, $selected_index = null, $tts_index_response = null)
    {
        $this->GetTimeZone = app_timezone();

        $insertlog = array(
            'web_partner_id' => $web_partner_id,
            'tts_search_token' => $tts_search_token,
            'request' => json_encode($request),
            'response' => json_encode($response),
            'selected_index' => $selected_index,
            'tts_index_response' => json_encode($tts_index_response),
            'service' => $service,
            'created' => strtotime(Time::now($this->GetTimeZone))
        );
        $db = \Config\Database::connect('api');
        $db->table('tts_hotel_log')->insert($insertlog);
    }

    function verify_tts_search_token($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_hotel_log');
        $builder->select('tts_search_token,tts_index_response,created');
        $builder->where($array_condition);
        $builder->orderBy("id", "Desc");
        return $builder->get()->getRowArray();
    }

    function book_record_exists($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_hotel_log');
        $builder->select('tts_search_token');
        $builder->where($array_condition);
        return $builder->get()->getRowArray();
    }

    function get_auth_user_account_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function verify_booking_detail($array_condition)
    {
        $hotelBookingDetail = $this->db->table('hotel_booking_list')->select('*')->where($array_condition)->get()->getRowArray();
        if ($hotelBookingDetail) {
            $AccountDetails = $this->db->table('web_partner_account_log')->select('acc_ref_number,debit,created')->where(['booking_ref_no' => $hotelBookingDetail['id'], 'service' => 'hotel'])->get()->getRowArray();
            $hotelBookingDetail['AccountDetails'] = $AccountDetails;
        }
        return $hotelBookingDetail;
    }

    function get_book_detail($array_condition)
    {
        return $this->db->table('hotel_booking_list')->select('id,web_partner_id,supplier_booking_id,confirmation_no,tts_search_token')->where($array_condition)->get()->getRowArray();
    }

    function get_amendment_detail($array_condition)
    {
        return $this->db->table('hotel_amendment')->select('id,amendment_type,bookng_id')->where($array_condition)->get()->getRowArray();
    }

    function get_cancel_detail($array_condition)
    {
        return $this->db->table('hotel_cancellation')->select('id,supplier_cancel_id')->where($array_condition)->get()->getRowArray();
    }

    function super_admin_markup($web_partner_class_id, $region_type, $extraParameter,$ApiSupplier)
    {
        $builder = $this->db->table('super_admin_hotel_markup');
        $builder->select('hotel_markup_type,value,display_markup,star_rating,supplier');
        if($extraParameter['btype']=="B2B"){
            $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
            }
            $builder->where('markup_for', $extraParameter['btype']);
        $builder->where('find_in_set("' . $region_type . '", region_type) <> 0');
        $builder->where('status', 'active');
        if($ApiSupplier!=""){
        $builder->where('find_in_set("' . $ApiSupplier . '", supplier) <> 0');
        }
        return $builder->get()->getResultArray();
    }

    function super_admin_discount($web_partner_class_id, $region_type, $extraParameter,$ApiSupplier)
    {
        $builder = $this->db->table('super_admin_hotel_discount');
        $builder->select('value,extra_discount,max_limit,supplier');
        if($extraParameter['btype']=="B2B"){
            $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
            }
            $builder->where('discount_for', $extraParameter['btype']);
        $builder->where('find_in_set("' . $region_type . '", region_type) <> 0');
        $builder->where('status', 'active');
        if($ApiSupplier!=""){
        $builder->where('find_in_set("' . $ApiSupplier . '", supplier) <> 0');
        }
        return $builder->get()->getResultArray();
    }

    function super_admin_gst_state_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function super_admin_booking_pre_fix_code()
    {
        $builder =$this->db->table('super_admin_website_setting');
        $builder->select('hotel_pre_fix');
      $data   =   $builder->get()->getRowArray();
      $data['pre_fix'] =  $data['hotel_pre_fix'];
      unset($data['hotel_pre_fix']);
      return $data;
    }

    function getbookingDetailByToken($tts_search_token, $fields)
    {
        $builder = $this->db->table('hotel_booking_list');
        $builder->where("tts_search_token", $tts_search_token);
        $builder->select($fields);
        return $builder->get()->getRowArray();
    }

    function get_search_request($tts_search_token)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_hotel_log');
        $builder->select('request');
        $builder->where(['tts_search_token' => $tts_search_token, 'service' => 'search']);
        return $builder->get()->getRowArray();
    }

    function get_city_name($city_id)
    {
        return $this->db->table("hotel_city_list")->select('destination')->where('city_id', $city_id)->get()->getRowArray();
    }

    function get_block_data($tts_search_token, $selected_index)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_hotel_log');
        $builder->select('response');
        $builder->where(['tts_search_token' => $tts_search_token, 'selected_index' => $selected_index, 'service' => 'blockroom']);
        $builder->orderBy("id", "DESC");
        return $builder->get()->getRowArray();
    }

    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function get_acc_ref_number($serviceId, $web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('acc_ref_number')->where(['booking_ref_no' => $serviceId, "service" => "hotel", "web_partner_id" => $web_partner_id, "transaction_type" => "debit"])->get()->getRowArray();
    }

    function GetpartnerInfo($tableName, $Where, $fieldName)
    {
        return $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();

    }

    function GetAmendmentInfo($tableName, $Where, $fieldName)
    {
        return $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();
    }

    function get_detail_by_refno($refno)
    {
        $builder = $this->db->table('hotel_booking_list');
        $builder->select('booking_ref_number,tts_search_token,api_supplier');
        $builder->where("booking_ref_number", $refno);
        return $builder->get()->getRowArray();
    }

    function get_supplier_logs($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('common_hotel_log');
        $builder->select('request,response,service');
        $builder->where($array_condition);
        return $builder->get()->getResultArray();
    }
    function super_admin_detail($fieldName)
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select($fieldName);
        return $builder->get()->getRowArray();
    }
    function getWebpartnerBookingAccountInfo($fieldName, $where)
    {
        $builder = $this->db->table('web_partner_account_log');
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }

    function getaccountLogCreditNote($web_partner_id, $refund_account_id)
    {
        return $this->db->table('web_partner_account_log')->select('id,web_partner_id,acc_ref_number,created')->where('web_partner_id', $web_partner_id)->where('id', $refund_account_id)->get()->getRowArray();
    }
}



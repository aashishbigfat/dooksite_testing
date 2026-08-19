<?php

namespace App\Modules\Busservice\Models;

use CodeIgniter\I18n\Time;

use CodeIgniter\Model;

class RestModel extends Model
{

    function get_api_supplier($name)
    {

        $builder = $this->db->table('api_supplier');
        $builder->select('supplier_name');
        $builder->where('supplier_name', $name);
        $builder->where('status', 'active');
        $builder->where('bus', 'active');
        return $builder->get()->getRowArray();
    }

    function insert_tts_bus_logs($web_partner_id, $tts_search_token, $request, $response, $service, $selected_index = null, $tts_index_response = null)
    {
        $insertlog = array(
            'web_partner_id' => $web_partner_id,
            'tts_search_token' => $tts_search_token,
            'request' => json_encode($request),
            'response' => json_encode($response),
            'selected_index' => $selected_index,
            'tts_index_response' => json_encode($tts_index_response),
            'service' => $service,
            'created' => strtotime(Time::now(app_timezone()))
        );
        $db = \Config\Database::connect('api');
        $db->table('tts_bus_log')->insert($insertlog);
    }

    function verify_tts_search_token($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('tts_search_token,tts_index_response,created');
        $builder->where($array_condition);
        $builder->orderBy("id", "DESC");
        return $builder->get()->getRowArray();
    }

    function book_record_exists($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('tts_search_token');
        $builder->where($array_condition);
        return $builder->get()->getRowArray();
    }

    function get_auth_user_account_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    function verify_booking_detail($array_condition)
    {
        $busBookingDetail = $this->db->table('bus_booking_list')->select('*')->where($array_condition)->get()->getRowArray();
        if ($busBookingDetail) {
            $BusPassengerDetails = $this->db->table('bus_booking_travelers')->select('*')->where('bus_booking_id', $busBookingDetail['id'])->get()->getResultArray();
            $busBookingDetail['PassengerDetails'] = $BusPassengerDetails;
            $AccountDetails = $this->db->table('web_partner_account_log')->select('acc_ref_number,debit,created')->where(['booking_ref_no' => $busBookingDetail['id'], 'web_partner_id' => $array_condition['web_partner_id'], 'service' => 'bus'])->get()->getRowArray();
            $busBookingDetail['AccountDetails'] = $AccountDetails;
        }
        return $busBookingDetail;
    }

    function get_book_detail($array_condition)
    {
        $busBookingDetail = $this->db->table('bus_booking_list')->select('id,web_partner_id,supplier_booking_id')->where($array_condition)->get()->getRowArray();
        if ($busBookingDetail) {
            $BusPassengerDetails = $this->db->table('bus_booking_travelers')->select('seat_name,seat_id')->where('bus_booking_id', $busBookingDetail['id'])->get()->getResultArray();
            $busBookingDetail['PassengerDetails'] = $BusPassengerDetails;
        }
        return $busBookingDetail;
    }

    function get_supplier_logs($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tbo_bus_log');
        $builder->select('request,response,service');
        $builder->where($array_condition);
        return $builder->get()->getResultArray();
    }
    
    function getBusBookingData($bus_booking_id, $tts_search_token)
    {
        $builder = $this->db->table('bus_booking_list');
        $builder->select("bus_booking_list.*,created,concat('[', group_concat(JSON_OBJECT('id', bus_booking_travelers.id,'title',bus_booking_travelers.title,'first_name',bus_booking_travelers.first_name,'last_name',bus_booking_travelers.last_name,'age',bus_booking_travelers.age,'email_id',bus_booking_travelers.email_id,'mobile_number',bus_booking_travelers.mobile_number,'lead_pax',bus_booking_travelers.lead_pax,'gendar',bus_booking_travelers.gendar,'id_type',bus_booking_travelers.id_type,'id_number',bus_booking_travelers.id_number,'seat_name',bus_booking_travelers.seat_name,'address',bus_booking_travelers.address,'age',bus_booking_travelers.age) separator ','), ']') as travelersInfo");
        $builder->where(['bus_booking_list.id' => $bus_booking_id, 'bus_booking_list.tts_search_token' => $tts_search_token]);
        $builder->join('bus_booking_travelers', "bus_booking_travelers.bus_booking_id =$bus_booking_id");
        $builder->groupBy('bus_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }
    function GetpartnerInfo($tableName, $Where, $fieldName)
    {
        return  $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();
    }
    function getbookingDetailByToken($tts_search_token,$fields)
    {
        $builder =$this->db->table('bus_booking_list');
        $builder->where("tts_search_token",$tts_search_token);
        $builder->select($fields);
        return $builder->get()->getRowArray();
    }
    function get_acc_ref_number($serviceId,$web_partner_id)
    {
        return  $this->db->table("web_partner_account_log")->select('acc_ref_number')->where(['booking_ref_no'=>$serviceId,"service"=>"bus","web_partner_id"=>$web_partner_id,"transaction_type"=>"debit"])->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }
    function insertBatchData($tableName, $insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }

    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }
    
    function get_search_request($tts_search_token)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('request');
        $builder->where(['tts_search_token'=>$tts_search_token,'service'=>'search']);
        return $builder->get()->getRowArray();
    }

    function get_city_name(array $city_id_array)
    {
        return  $this->db->table("bus_city_list")->select('city_name,city_id')->whereIn('city_id', $city_id_array)->get()->getResultArray();
    }

    function get_boarding_dropping_points($tts_search_token)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('response');
        $builder->where(['tts_search_token'=>$tts_search_token,'service'=>'boardingpoint']);
        $builder->orderBy("id","DESC");
        return $builder->get()->getRowArray();
    }

    function get_block_data($tts_search_token,$selected_index)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tts_bus_log');
        $builder->select('response');
        $builder->where(['tts_search_token'=>$tts_search_token,'selected_index'=>$selected_index,'service'=>'blockseat']);
        $builder->orderBy("id","DESC");
        return $builder->get()->getRowArray();
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


    function get_booking_info($array_condition)
    {
        return $this->db->table('bus_booking_list')->select('id,web_partner_id,tts_search_token')->where($array_condition)->get()->getRowArray();
    }

    function getTravellerData($traveller_id)
    {
        return $this->db->table('bus_booking_travelers')->select('*')->where('id', $traveller_id)->get()->getRowArray();
    }

    function GetAmendmentInfo($tableName, $Where, $fieldName)
    {
        $checkamendmentStatus =1;
        $paxids  =  $Where['pax_id'];
        foreach($paxids as $paxId) {
            $builder  =  $this->db->table($tableName);
            $builder->select($fieldName);
            $builder->where(["booking_ref_no"=>$Where['booking_ref_no']]);
            $builder->where(["amendment_status!="=>"rejected"]);
            $builder->where('find_in_set("' . $paxId . '", pax_id) <> 0');
            $data   =  $builder->get()->getRowArray();
            if(!empty($data))
            {
                $checkamendmentStatus =0;
                break;
            }
        }
        return $checkamendmentStatus;
    }
    function getaccountLogCreditNote($web_partner_id,$refund_account_id)
    {
        return $this->db->table('web_partner_account_log')->select('id,web_partner_id,acc_ref_number,created')->where('web_partner_id', $web_partner_id)->where('id', $refund_account_id)->get()->getRowArray();
    }


}

<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelBookingModel extends Model
{
    protected $table = 'hotel_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function hotel_booking_list($web_partner_id,$userId,$userType)
    {
        return  $this->select('hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')->where("hotel_booking_list.web_partner_id",$web_partner_id)
            ->orderBy("hotel_booking_list.id","DESC")->paginate(40);
    }

    public function hotel_booking_info($web_partner_id,$booking_refrence_number,$wl_customer_id,$userType)
    {
        return  $this->select('hotel_booking_list.id,hotel_booking_list.customer_fare_break_up,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.hotel_norms,hotel_booking_list.hotel_policy_detail,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.conveniencefee,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_rooms_details,hotel_booking_list.contact_number,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.contact_email_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
        ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
        ->where(["hotel_booking_list.web_partner_id"=>$web_partner_id,"hotel_booking_list.booking_ref_number"=>$booking_refrence_number,'hotel_booking_list.wl_customer_id'=>$wl_customer_id,'hotel_booking_list.booking_source'=>$userType])
        ->get()->getRowArray();
    }
    public function hotel_booking_detail($web_partner_id,$booking_refrence_number,$userId,$userType)
    {
       $hotelDetail =   $this->select("hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.hotel_norms,hotel_booking_list.hotel_policy_detail,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_rooms_details,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.contact_number,hotel_booking_list.contact_email_id,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
        ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
        ->where(["hotel_booking_list.web_partner_id"=>$web_partner_id,"hotel_booking_list.booking_ref_number"=>$booking_refrence_number])
        ->groupBy('hotel_booking_list.id')
        ->get()->getRowArray();
        if($hotelDetail){
            $hotelDetail['paymentInfo'] =  $this->db->table('web_partner_account_log')->select("*")->where(["web_partner_account_log.web_partner_id"=>$web_partner_id,"web_partner_account_log.booking_ref_no"=>$hotelDetail['id'],"web_partner_account_log.service"=>"hotel"])->get()->getResultArray();
            $hotelDetail['paymentInfo'] = json_encode($hotelDetail['paymentInfo']);
        }
        return  $hotelDetail;
    }

    public function amendment_list($web_partner_id, $booking_reference_number){
        return  $this->db->table('hotel_amendment')->select("hotel_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users","admin_users.id=hotel_amendment.agent_staff_id",'left')
            ->where(["hotel_amendment.web_partner_id"=>$web_partner_id,"hotel_amendment.booking_ref_no"=>$booking_reference_number])
            ->get()->getResultArray();
    }



    function search_data($data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];

                return  $this->select('hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
                    ->where($array)
                    ->orderBy("hotel_booking_list.id","DESC")->paginate(40);

            } else {
                $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];

                return  $this->select('hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("hotel_booking_list.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.booking_currency, hotel_booking_list.currency_rate, hotel_booking_list.default_currency,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("hotel_booking_list.id","DESC")->paginate(40);
        }

    }


    function insertIntoLogs($table,$emamilLogsData){
        $query = $this->db->table($table)->select('id')->where('booking_info',$emamilLogsData['booking_info'])->get()->getRowArray();
        if(!$query){
            $this->db->table($table)->insert($emamilLogsData);
            return $this->db->insertID();
        }else{
            return true;
        }
    }


}
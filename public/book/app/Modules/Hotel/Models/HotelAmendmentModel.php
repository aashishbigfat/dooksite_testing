<?php

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelAmendmentModel extends Model
{
    protected $table = 'hotel_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function hotel_amendment_list($web_partner_id,$wl_customer_id)
    {
        return $this->select('hotel_amendment.id As AmendmentId,hotel_amendment.amendment_type,hotel_amendment.amendment_status,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_amendment.created,hotel_amendment.remark_from_web_partner,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->where(['hotel_amendment.web_partner_id' => $web_partner_id,'hotel_amendment.wl_customer_id' => $wl_customer_id])
            ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
            ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
            ->join('web_partner', "hotel_amendment.web_partner_id = web_partner.id", 'left')->orderBy("hotel_amendment.id", "DESC")->paginate(40);
    }

    function search_data($web_partner_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['hotel_amendment.created >=' => $from_date, 'hotel_amendment.created <=' => $to_date,'hotel_amendment.web_partner_id' => $web_partner_id];


                return $this->select('hotel_amendment.id As AmendmentId,hotel_amendment.amendment_type,hotel_amendment.amendment_status,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_amendment.created,
                hotel_amendment.remark_from_web_partner,
                web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                    ->where($array)
                    ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
                    ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "hotel_amendment.web_partner_id = web_partner.id", 'left')->orderBy("hotel_amendment.id", "DESC")->paginate(40);

            } else {
                $array = ['hotel_amendment.created >=' => $from_date, 'hotel_amendment.created <=' => $to_date,'hotel_amendment.web_partner_id' => $web_partner_id];
                $dataArray = ['lead_passenger_name'=>'hotel_booking_list.lead_passenger_name','booking_ref_number'=>'hotel_booking_list.booking_ref_number','amendment_type'=>'hotel_amendment.amendment_type','booking_status'=>'hotel_booking_list.booking_status','amendment_status'=>'hotel_amendment.amendment_status','id'=>'hotel_amendment.id'];
                return $this->select('hotel_amendment.id As AmendmentId,hotel_amendment.amendment_type,hotel_amendment.amendment_status,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_amendment.created,hotel_amendment.remark_from_web_partner,
                web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                    ->where($array)->like($dataArray[trim($data['key'])], trim($data['value']))->where(['hotel_amendment.web_partner_id' => $web_partner_id])
                    ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
                    ->join("hotel_booking_list", "hotel_booking_list.id=flight_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);


            }
        } else {
            $dataArray = ['lead_passenger_name'=>'hotel_booking_list.lead_passenger_name','booking_ref_number'=>'hotel_booking_list.booking_ref_number','amendment_type'=>'hotel_amendment.amendment_type','booking_status'=>'hotel_booking_list.booking_status','amendment_status'=>'hotel_amendment.amendment_status','id'=>'hotel_amendment.id'];
            return $this->select('hotel_amendment.id As AmendmentId,hotel_amendment.amendment_type,hotel_amendment.amendment_status,hotel_booking_list.id as hotelBokkingid,hotel_booking_list.booking_ref_number,hotel_booking_list.hotel_name,hotel_booking_list.lead_passenger_name,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.city,hotel_booking_list.country_code,hotel_booking_list.booking_status,hotel_amendment.created,hotel_amendment.remark_from_web_partner,
            web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                ->where(['hotel_amendment.web_partner_id' => $web_partner_id]) ->like($dataArray[trim($data['key'])], trim($data['value']))
                ->join("admin_users", "admin_users.id=hotel_amendment.agent_staff_id", 'left')
                ->join("hotel_booking_list", "hotel_booking_list.id=hotel_amendment.booking_ref_no", 'left')
                ->join('web_partner', "hotel_amendment.web_partner_id = web_partner.id", 'left')->orderBy("hotel_amendment.id", "DESC")->paginate(40);
        }
    }

    public function hotel_amendment_detail($web_partner_id,$wl_customer_id,$amendment_id)
    {
        $builder = $this->db->table('hotel_booking_list');
        $builder->select("hotel_booking_list.id As hotelBookingid,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_amendment.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.customer_fare_break_up,hotel_booking_list.room_guests,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_name,hotel_booking_list.hotel_rooms_details,hotel_booking_list.booking_ref_number,hotel_booking_list.confirmation_no,hotel_amendment.amendment_type,hotel_amendment.remark_from_user,hotel_amendment.amendment_status,hotel_amendment.remark_from_web_partner,hotel_amendment.id");
        $builder->where(['hotel_amendment.id' => $amendment_id, 'hotel_amendment.web_partner_id' => $web_partner_id,'hotel_amendment.wl_customer_id' => $wl_customer_id]);
        $builder->join('hotel_amendment', "hotel_amendment.booking_ref_no = hotel_booking_list.id");
        $builder->groupBy('hotel_booking_list.id');
        $query = $builder->get()->getRowArray(); 
        return  $query;

    }
    /**
     * hotel_booking_list.id,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_name,hotel_booking_list.booking_ref_number,hotel_booking_list.confirmation_no,hotel_amendment.amendment_type
     * 
     * 
     * 
     */
}



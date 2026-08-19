<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightAmendmentModel extends Model
{
    protected $table = 'flight_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_amendment_list($web_partner_id,$wl_customer_id)
    {
        return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
            ->where(['flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_customer_id' => $wl_customer_id])
            ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);
    }

    function search_data($web_partner_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date,'flight_amendment.web_partner_id' => $web_partner_id];


                return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                    flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                    flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                    flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                    web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                    ->where($array)
                    ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
                    ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);

            } else {
                $array = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date,'flight_amendment.web_partner_id' => $web_partner_id];

                return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                    flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                    flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                    flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                    web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                    ->where($array)->like(trim($data['key']), trim($data['value']))->where(['flight_amendment.web_partner_id' => $web_partner_id])
                    ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
                    ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);


            }
        } else {

            return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name')
                ->where(['flight_amendment.web_partner_id' => $web_partner_id]) ->like(trim($data['key']), trim($data['value']))
                ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
                ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);
        }
    }
}



<?php

namespace App\Modules\Dashboard\Models\BikeTour;

use CodeIgniter\Model;

class BikeTourBookingModel extends Model
{
    protected $table = 'biketour_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function biketour_booking_list_all($web_partner_id, $wl_customer_id, $userType)
    {
        return $this->select('biketour_booking_list.*,CONCAT(customer.first_name," ",customer.last_name) as staff_name')
            ->join("customer", "customer.id=biketour_booking_list.wl_customer_id", 'left')
            ->where([
                'biketour_booking_list.web_partner_id' => $web_partner_id,
                'biketour_booking_list.wl_customer_id' => $wl_customer_id,
                'biketour_booking_list.booking_source' => $userType
            ])
            ->groupBy("biketour_booking_list.id")->orderBy("biketour_booking_list.id", "DESC")->paginate(40);
    }

    public function biketour_booking_upcoming_list($web_partner_id, $wl_customer_id, $userType)
    {
        $dates = date('Y-m-d');

        return $this->select('biketour_booking_list.*, CONCAT(customer.first_name, " ", customer.last_name) as staff_name')
            ->where("biketour_booking_list.pickup_date >=", $dates)
            ->where([
                'biketour_booking_list.web_partner_id' => $web_partner_id,
                'biketour_booking_list.wl_customer_id' => $wl_customer_id,
                'biketour_booking_list.booking_source' => $userType,
                'biketour_booking_list.booking_status' => 'Confirmed'
            ])
            ->join("customer", "customer.id = biketour_booking_list.wl_customer_id", 'left')
            ->groupBy("biketour_booking_list.id")
            ->orderBy("biketour_booking_list.id", "DESC")
            ->paginate(40);
    }



    public function biketour_booking_cancelled_list($web_partner_id, $wl_customer_id, $userType)
    {
        return $this->select('biketour_booking_list.*, CONCAT(customer.first_name, " ", customer.last_name) as staff_name')
            ->whereIn('biketour_booking_list.booking_status', ['Cancelled', 'PartialCancelled'])
            ->join("customer", "customer.id = biketour_booking_list.wl_customer_id", 'left')
            ->where([
                'biketour_booking_list.web_partner_id' => $web_partner_id,
                'biketour_booking_list.wl_customer_id' => $wl_customer_id,
                'biketour_booking_list.booking_source' => $userType
            ])
            ->groupBy('biketour_booking_list.id')
            ->orderBy('biketour_booking_list.id', 'DESC')
            ->paginate(40);
    }


    public function biketour_booking_detail($web_partner_id, $booking_refrence_number, $userId, $userType)
    {
        $builder = $this->db->table('biketour_booking_list');
        $builder->select("biketour_booking_list.*,CONCAT(customer.first_name,' ',customer.last_name) as staff_name")
            ->join("customer", "customer.id=biketour_booking_list.wl_customer_id", 'left');
        $builder->where(['biketour_booking_list.booking_ref_number' => $booking_refrence_number, 'biketour_booking_list.web_partner_id' => $web_partner_id]);
        $builder->groupBy('biketour_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $builder = $this->db->table('biketour_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=biketour_booking_list.id");
            $builder->where(['biketour_booking_list.booking_ref_number' => $booking_refrence_number, 'biketour_booking_list.web_partner_id' => $web_partner_id, "web_partner_account_log.service" => "flight"]);
            $query['paymentInfo'] = $builder->get()->getResultArray();
        }
        return $query;
    }



    function search_bookings($data, $web_partner_id, $wl_customer_id)
    {
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "flight_booking_travelers.first_name", "last_name" => "flight_booking_travelers.last_name", "ticket_number" => "ticket_number", "pnr" => "biketour_booking_list.pnr", "booking_status" => "biketour_booking_list.booking_status", "payment_status" => "biketour_booking_list.payment_status", "web_partner_fare_break_up" => "biketour_booking_list.web_partner_fare_break_up");

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['biketour_booking_list.created >=' => $from_date, 'biketour_booking_list.created <=' => $to_date];
                return $this->select('biketour_booking_list.id,biketour_booking_list.booking_ref_number,biketour_booking_list.journey_type,biketour_booking_list.web_partner_fare_break_up,
                biketour_booking_list.origin,biketour_booking_list.destination,biketour_booking_list.departure_date,biketour_booking_list.created,biketour_booking_list.is_domestic,biketour_booking_list.is_refundable,biketour_booking_list.validating_airline_code,biketour_booking_list.payment_status,biketour_booking_list.booking_status,biketour_booking_list.total_price,biketour_booking_list.wl_customer_id,biketour_booking_list.pnr,biketour_booking_list.last_ticket_date,biketour_booking_list.booking_channel,CONCAT(customer.first_name," ",customer.last_name) as staff_name,')->join("customer", "customer.id=biketour_booking_list.wl_customer_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = biketour_booking_list.id", 'left')
                    ->where("biketour_booking_list.web_partner_id", $web_partner_id)->where("biketour_booking_list.wl_customer_id", $wl_customer_id)
                    ->where($array)->groupBy("biketour_booking_list.id")->orderBy("biketour_booking_list.id", "DESC")->paginate(40);

            } else {
                $array = ['biketour_booking_list.created >=' => $from_date, 'biketour_booking_list.created <=' => $to_date];
                return $this->select('biketour_booking_list.id,biketour_booking_list.booking_ref_number,biketour_booking_list.journey_type,biketour_booking_list.web_partner_fare_break_up,
                biketour_booking_list.origin,biketour_booking_list.destination,biketour_booking_list.departure_date,biketour_booking_list.created,biketour_booking_list.is_domestic,biketour_booking_list.is_refundable,biketour_booking_list.validating_airline_code,biketour_booking_list.payment_status,biketour_booking_list.booking_status,biketour_booking_list.total_price,biketour_booking_list.wl_customer_id,biketour_booking_list.pnr,biketour_booking_list.last_ticket_date,biketour_booking_list.booking_channel,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("customer", "customer.id=biketour_booking_list.wl_customer_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = biketour_booking_list.id", 'left')
                    ->where("biketour_booking_list.web_partner_id", $web_partner_id)->where("biketour_booking_list.wl_customer_id", $wl_customer_id)
                    ->where($array)->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("biketour_booking_list.id")->orderBy("biketour_booking_list.id", "DESC")->paginate(40);
            }
        } else {

            return $this->select('biketour_booking_list.id,biketour_booking_list.booking_ref_number,biketour_booking_list.journey_type,biketour_booking_list.web_partner_fare_break_up,
            biketour_booking_list.origin,biketour_booking_list.destination,biketour_booking_list.departure_date,biketour_booking_list.created,biketour_booking_list.is_domestic,biketour_booking_list.is_refundable,biketour_booking_list.validating_airline_code,biketour_booking_list.payment_status,biketour_booking_list.booking_status,biketour_booking_list.total_price,biketour_booking_list.wl_customer_id,biketour_booking_list.pnr,biketour_booking_list.last_ticket_date,biketour_booking_list.booking_channel,CONCAT(customer.first_name," ",customer.last_name) as staff_name')
                ->join("customer", "customer.id=biketour_booking_list.wl_customer_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = biketour_booking_list.id", 'left')
                ->where("biketour_booking_list.web_partner_id", $web_partner_id)->where("biketour_booking_list.wl_customer_id", $wl_customer_id)
                ->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("biketour_booking_list.id")->orderBy("biketour_booking_list.id", "DESC")->paginate(40);
        }

    }
    function getBookingWithVariableFieldNameData($booking_refrence_number, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("biketour_booking_list");
        $builder->select($fieldName);
        $builder->where(['biketour_booking_list.booking_ref_number' => $booking_refrence_number, 'biketour_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    public function flight_amendment_itinerary_detail($web_partner_id, $booking_refrence_number)
    {
        $builder = $this->db->table('biketour_booking_list');
        $builder->select("biketour_booking_list.id,biketour_booking_list.pnr,biketour_booking_list.is_domestic,biketour_booking_list.tts_search_token,biketour_booking_list.booking_ref_number,biketour_booking_list.search_request,biketour_booking_list.segments");
        $builder->where(['biketour_booking_list.booking_ref_number' => $booking_refrence_number, 'biketour_booking_list.web_partner_id' => $web_partner_id]);
        $builder->groupBy('biketour_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }
    public function amendment_list($web_partner_id, $wl_customer_id)
    {
        $result = $this->db->table('biketour_amendment')
            ->select("biketour_amendment.*,biketour_booking_list.booking_ref_number, CONCAT(customer.first_name, ' ', customer.last_name) as staff_name")
            ->join("customer", "customer.id = biketour_amendment.wl_customer_id", 'left')
            ->join("biketour_booking_list", "biketour_booking_list.id = biketour_amendment.booking_ref_no", 'left')
            ->where([
                "biketour_amendment.web_partner_id" => $web_partner_id,
                "biketour_amendment.wl_customer_id" => $wl_customer_id
            ])
            ->get()
            ->getResultArray();

        return $result;
    }

    public function biketour_amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('biketour_booking_list');
        $builder->select("biketour_amendment.*,biketour_booking_list.id as flightbookingid,biketour_booking_list.pnr,biketour_booking_list.is_domestic,biketour_booking_list.tts_search_token,biketour_booking_list.booking_ref_number");
        $builder->where(['biketour_amendment.id' => $amendment_id, 'biketour_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('biketour_amendment', "biketour_amendment.booking_ref_no = biketour_booking_list.id");
        $builder->groupBy('biketour_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }


}
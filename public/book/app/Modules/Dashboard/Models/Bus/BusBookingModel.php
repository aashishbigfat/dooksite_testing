<?php

namespace App\Modules\Dashboard\Models\Bus;

use CodeIgniter\Model;

class BusBookingModel extends Model
{
    protected $table = 'bus_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function bus_booking_list($web_partner_id,$wl_customer_id,$userType)
    {
        return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.wl_customer_id,bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate,
        bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,
        bus_booking_travelers.first_name,bus_booking_travelers.last_name')
            ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')
            ->where('bus_booking_list.web_partner_id',$web_partner_id)->where('bus_booking_list.wl_customer_id',$wl_customer_id)

            ->where(['bus_booking_list.web_partner_id' => $web_partner_id,'bus_booking_list.wl_customer_id'=>$wl_customer_id,'bus_booking_list.booking_source'=>$userType])

            ->orderBy("bus_booking_list.id", "DESC")->groupBy("bus_booking_list.id")->paginate(40);
    }

    public function bus_booking_upcomming_list($web_partner_id,$wl_customer_id,$userType)
    {
        $year = date('Y');
        $month = date('m');
        return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.wl_customer_id,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate,
        bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,
        bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,
        bus_booking_travelers.first_name,bus_booking_travelers.last_name')

            ->where("MONTH(bus_booking_list.date_of_journey) = {$month} AND YEAR(bus_booking_list.date_of_journey) = {$year}")->where('bus_booking_list.booking_status','Confirmed')

            ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')

            ->where(['bus_booking_list.web_partner_id' => $web_partner_id,'bus_booking_list.wl_customer_id'=>$wl_customer_id,'bus_booking_list.booking_source'=>$userType,'bus_booking_list.booking_status'=>'Confirmed'])

            ->orderBy("bus_booking_list.id", "DESC")->groupBy("bus_booking_list.id")->paginate(40);
    }

    public function bus_booking_cancelled_list($web_partner_id,$wl_customer_id,$userType)
    {

        return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.wl_customer_id,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate,bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,
        bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,
        bus_booking_travelers.first_name,bus_booking_travelers.last_name')

            ->whereIn('bus_booking_list.booking_status',['Cancelled','PartialCancelled'])

            ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')

            ->where(['bus_booking_list.web_partner_id' => $web_partner_id,'bus_booking_list.wl_customer_id'=>$wl_customer_id,'bus_booking_list.booking_source'=>$userType])

            ->orderBy("bus_booking_list.id", "DESC")->groupBy("bus_booking_list.id")->paginate(40);
    }



    function search_data($data,$web_partner_id,$wl_customer_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['bus_booking_list.created >=' => $from_date, 'bus_booking_list.created <=' => $to_date];

                return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.wl_customer_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,bus_booking_travelers.first_name,bus_booking_travelers.last_name,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate')
                    ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')
                    ->where($array)->where('bus_booking_list.web_partner_id',$web_partner_id)->where('bus_booking_list.wl_customer_id',$wl_customer_id)
                    ->orderBy("bus_booking_list.id", "DESC")->paginate(40);

            } else {
                $array = ['bus_booking_list.created >=' => $from_date, 'bus_booking_list.created <=' => $to_date];
                $dataArray = array('first_name'=>'bus_booking_travelers.first_name','last_name'=>'bus_booking_travelers.last_name','ticket_no'=>'bus_booking_list.ticket_no','travel_operator_pnr'=>'bus_booking_list.travel_operator_pnr','booking_status'=>'bus_booking_list.booking_status','payment_status'=>'bus_booking_list.payment_status');

                return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.wl_customer_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,bus_booking_travelers.first_name,bus_booking_travelers.last_name,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate')
                    ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')
                    ->like($dataArray[trim($data['key'])], trim($data['value']))->where('bus_booking_list.web_partner_id',$web_partner_id)->where('bus_booking_list.wl_customer_id',$wl_customer_id)
                    ->orderBy("bus_booking_list.id", "DESC")->groupBy("bus_booking_list.id")->paginate(40);
            }
        } else {
            $dataArray = array('first_name'=>'bus_booking_travelers.first_name','last_name'=>'bus_booking_travelers.last_name','ticket_no'=>'bus_booking_list.ticket_no','travel_operator_pnr'=>'bus_booking_list.travel_operator_pnr','booking_status'=>'bus_booking_list.booking_status','payment_status'=>'bus_booking_list.payment_status');

            return $this->select('bus_booking_list.id,bus_booking_list.web_partner_id,bus_booking_list.wl_customer_id,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.api_supplier,bus_booking_list.payment_mode,bus_booking_list.payment_status,bus_booking_list.booking_ref_number,bus_booking_list.booking_status,bus_booking_list.total_price,bus_booking_list.created,bus_booking_travelers.email_id,bus_booking_travelers.mobile_number,bus_booking_travelers.title,bus_booking_travelers.first_name,bus_booking_travelers.last_name,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate')
                ->join('bus_booking_travelers', 'bus_booking_travelers.bus_booking_id = bus_booking_list.id', 'Left')
                ->like($dataArray[trim($data['key'])], trim($data['value']))->where('bus_booking_list.web_partner_id',$web_partner_id)->where('bus_booking_list.wl_customer_id',$wl_customer_id)
                ->orderBy("bus_booking_list.id", "DESC")->groupBy("bus_booking_list.id")->paginate(40);
        }

    }

    public function bus_booking_detail($web_partner_id,$wl_customer_id,$booking_refrence_number){
        $busDetail =   $this->select("bus_booking_list.id,bus_booking_list.booking_ref_number,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.bus_name,bus_booking_list.created,bus_booking_list.bus_type,bus_booking_list.default_currency,bus_booking_list.booking_currency,bus_booking_list.currency_rate,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.boarding_points,bus_booking_list.dropping_points,bus_booking_list.departure_time,bus_booking_list.cancellation_policies,bus_booking_list.arrival_time,bus_booking_list.ticket_no,bus_booking_list.no_of_seats,bus_booking_list.booking_status,bus_booking_list.total_price,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name,CONCAT(bus_booking_travelers.title,' ',bus_booking_travelers.first_name,' ',bus_booking_travelers.last_name) as passenger_name,bus_booking_travelers.email_id as contact_email_id,bus_booking_travelers.mobile_number as contact_number")
        ->join("admin_users","admin_users.id=bus_booking_list.agent_staff_id",'left')
        ->join("bus_booking_travelers","bus_booking_travelers.bus_booking_id=bus_booking_list.id",'left')
        ->where(["bus_booking_list.web_partner_id"=>$web_partner_id,"bus_booking_list.booking_ref_number"=>$booking_refrence_number,'bus_booking_list.wl_customer_id'=>$wl_customer_id])
        ->groupBy('bus_booking_list.id')
        ->get()->getRowArray();
        if($busDetail){
            $busDetail['paymentInfo'] =  $this->db->table('customer_account_log')->select("*")->where(["customer_account_log.web_partner_id"=>$web_partner_id,"customer_account_log.booking_ref_no"=>$busDetail['id'],"customer_account_log.service"=>"bus"])->get()->getResultArray();
            $busDetail['paymentInfo'] = json_encode($busDetail['paymentInfo']);

            $agentBuilder = $this->db->table('bus_booking_travelers');
            $agentBuilder->select("id,bus_booking_id,title,first_name,last_name,age,email_id,mobile_number,lead_pax,gendar,id_type,id_number,address,seat_name,seat_id,seat_info");
            $agentBuilder->where(["bus_booking_id" => $busDetail['id']]);
            $busDetail['TravellerInfo'] = $agentBuilder->get()->getResultArray();
            $busDetail['TravellerInfo'] = json_encode($busDetail['TravellerInfo']);
        }
        return  $busDetail;
    }
}
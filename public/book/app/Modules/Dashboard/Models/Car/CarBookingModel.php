<?php

namespace App\Modules\Dashboard\Models\Car;

use CodeIgniter\Model;

class CarBookingModel extends Model
{
    protected $table = 'car_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function car_booking_list($web_partner_id,$wl_customer_id,$userType)
    {
        return $this->select('car_booking_list.*')
            ->where('car_booking_list.web_partner_id',$web_partner_id)->where('car_booking_list.wl_customer_id',$wl_customer_id)
            ->where(['car_booking_list.web_partner_id' => $web_partner_id,'car_booking_list.wl_customer_id'=>$wl_customer_id,'car_booking_list.booking_source'=>$userType])
            ->orderBy("car_booking_list.id", "DESC")->paginate(40);
    }

    public function car_booking_upcomming_list($web_partner_id,$wl_customer_id,$userType)
    {
        $year = date('Y');
        $month = date('m');
        return $this->select('car_booking_list.*')
            ->where("MONTH(car_booking_list.departure_date) = {$month} AND YEAR(car_booking_list.departure_date) = {$year}")->where('car_booking_list.booking_status','Confirmed')
            ->where(['car_booking_list.web_partner_id' => $web_partner_id,'car_booking_list.wl_customer_id'=>$wl_customer_id,'car_booking_list.booking_source'=>$userType,'car_booking_list.booking_status'=>'Confirmed'])
            ->orderBy("car_booking_list.id", "DESC")->paginate(40);
    }

    public function car_booking_cancelled_list($web_partner_id,$wl_customer_id,$userType)
    {
        return $this->select('car_booking_list.*')
            ->whereIn('car_booking_list.booking_status',['Cancelled','PartialCancelled'])
            ->where(['car_booking_list.web_partner_id' => $web_partner_id,'car_booking_list.wl_customer_id'=>$wl_customer_id,'car_booking_list.booking_source'=>$userType])
            ->orderBy("car_booking_list.id", "DESC")->paginate(40);
    }
}
<?php

namespace App\Modules\Dashboard\Models\Activity;

use CodeIgniter\Model;

class ActivityBookingModel extends Model
{

    protected $table = 'activities_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function activities_booking_list_all($web_partner_id, $wl_customer_id, $userType)
    {
        return  $this->select(' activities_booking_list.id, activities_booking_list.wl_customer_id,activities_booking_list.web_partner_fare_break_up,  activities_booking_list.activity_name,activities_booking_list.default_currency,activities_booking_list.booking_currency,activities_booking_list.currency_rate, activities_booking_list.location,  activities_booking_list.book_date,  activities_booking_list.no_of_travellers,activities_booking_list.payment_status,  activities_booking_list.booking_status,  activities_booking_list.total_price,
         activities_booking_list.agent_staff_id,  activities_booking_list.booking_channel, activities_booking_list.booking_ref_number,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT( activities_booking_travelers.title," ", activities_booking_travelers.first_name," ", activities_booking_travelers.last_name) as lead_passenger_name')
            ->join("admin_users", "admin_users.id=activities_booking_list.agent_staff_id", 'left')
            ->join(' activities_booking_travelers', " activities_booking_travelers.activity_booking_id = activities_booking_list.id", 'left')
            ->where([
                'activities_booking_list.web_partner_id' => $web_partner_id,
                'activities_booking_list.wl_customer_id' => $wl_customer_id,
                'activities_booking_list.booking_source' => $userType
            ])
            ->groupBy("activities_booking_list.id")->orderBy("activities_booking_list.id", "DESC")->paginate(40);
    }

    public function activities_booking_upcoming_list($web_partner_id, $wl_customer_id, $userType)
    {
        $date = date('Y-m-d');

        return  $this->select(' activities_booking_list.id, activities_booking_list.wl_customer_id,
         activities_booking_list.web_partner_fare_break_up,  activities_booking_list.activity_name,activities_booking_list.default_currency,activities_booking_list.booking_currency,activities_booking_list.currency_rate, 
         activities_booking_list.location,  activities_booking_list.book_date,  activities_booking_list.no_of_travellers, 
         activities_booking_list.payment_status,  activities_booking_list.booking_status,  activities_booking_list.total_price,
         activities_booking_list.agent_staff_id,  activities_booking_list.booking_channel, activities_booking_list.booking_ref_number,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT( activities_booking_travelers.title," ", activities_booking_travelers.first_name," ", activities_booking_travelers.last_name) as lead_passenger_name')
            ->where(" activities_booking_list.book_date > ", $date)
            ->where([
                ' activities_booking_list.web_partner_id' => $web_partner_id,
                ' activities_booking_list.wl_customer_id' => $wl_customer_id,
                ' activities_booking_list.booking_source' => $userType
            ])
            ->join("admin_users", "admin_users.id= activities_booking_list.agent_staff_id", 'left')
            ->join(
                'activities_booking_travelers',
                "activities_booking_travelers.activity_booking_id =  activities_booking_list.id",
                'left'
            )
            ->groupBy(" activities_booking_list.id")->orderBy(" activities_booking_list.id", "DESC")->paginate(40);
    }

    public function activities_booking_cancelled_list($web_partner_id, $wl_customer_id, $userType)
    {
        return  $this->select('activities_booking_list.id,activities_booking_list.booking_ref_number,
        activities_booking_list.web_partner_fare_break_up, activities_booking_list.activity_name,activities_booking_list.default_currency,activities_booking_list.booking_currency,activities_booking_list.currency_rate, 
        activities_booking_list.location, activities_booking_list.book_date, activities_booking_list.no_of_travellers, 
        activities_booking_list.payment_status, activities_booking_list.booking_status, activities_booking_list.total_price,
        activities_booking_list.agent_staff_id, activities_booking_list.booking_channel,
        CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(activities_booking_travelers.title," ",
        activities_booking_travelers.first_name," ",activities_booking_travelers.last_name) as lead_passenger_name')
            ->whereIn('activities_booking_list.booking_status', ['Cancelled', 'PartialCancelled'])
            ->join("admin_users", "admin_users.id=activities_booking_list.agent_staff_id", 'left')
            ->join('activities_booking_travelers', "activities_booking_travelers.activity_booking_id = activities_booking_list.id", 'left')
            ->where(['activities_booking_list.web_partner_id' => $web_partner_id, 'activities_booking_list.wl_customer_id' => $wl_customer_id, 'activities_booking_list.booking_source' => $userType])

            ->groupBy("activities_booking_list.id")->orderBy("activities_booking_list.id", "DESC")->paginate(40);
    }
}

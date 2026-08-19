<?php

namespace App\Modules\Home\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{

    protected $table = 'holiday_destination';
    protected $primarykey = 'id';
    protected $protectFields = false;



    // public function get_feedback_model_list($web_partner_id)
    // {
    //     $data = $this->db->table('customer_feedback')
    //         ->select('*')
    //         ->where('status', 'active')
    //         ->where('web_partner_id', $web_partner_id)
    //         ->orderBy('id', 'DESC')
    //         ->limit(4)
    //         ->get()
    //         ->getResultArray();

    //     return $data;
    // }



    function admin_notification($web_partner_id)
    {
        return $this->db->table('slider')
            ->where('status', 'active')
            ->where('image_category', 'Admin-Notification')
            ->where(['web_partner_id' => $web_partner_id])
            ->get()
            ->getResultArray();
    }


    public function blog_list($web_partner_id)
    {
        return $this->db->table('blog_post')
            ->select('id,post_title,post_slug,post_desc,posted_by,post_images,created')
            ->where(['web_partner_id' => $web_partner_id])
            ->where('status', 'active')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }


    public function get_top_routes_list($web_partner_id)
    {
        return $this->db->table('flight_top_routes')
            ->select('*')
            ->where(['status' => 'active', 'web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }



    public function GetHolidayDestinations_domestic($web_partner_id)
    {
        return $this->db->table('holiday_destination')
            ->select('id,destination_name,destination_slug,destination_image,starting_price')
            ->where(['status' => 'active', 'show_on_home' => 1, 'destination_type' => 'domestic', 'web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();
    }

    public function GetHolidayDestinations_international($web_partner_id)
    {
        return $this->db->table('holiday_destination')
            ->select('id,destination_name,destination_slug,destination_image,starting_price')
            ->where(['status' => 'active', 'show_on_home' => 1, 'destination_type' => 'international', 'web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();
    }


    public function GetHolidayDestinations($web_partner_id)
    {
        $query = "
            SELECT 
                id, destination_name, destination_slug, destination_image, starting_price, destination_type
            FROM 
                holiday_destination
            WHERE 
                status = 'active'
                AND show_on_home = 1
                AND web_partner_id = ?
                AND (destination_type = 'domestic' OR destination_type = 'international')
            ORDER BY 
                destination_type DESC, id DESC
            LIMIT 40
        ";

        $results = $this->db->query($query, [$web_partner_id])->getResultArray();

        $domesticDestinations = [];
        $internationalDestinations = [];

        foreach ($results as $result) {
            if ($result['destination_type'] === 'domestic' && count($domesticDestinations) < 8) {
                $domesticDestinations[] = $result;
            } elseif ($result['destination_type'] === 'international' && count($internationalDestinations) < 8) {
                $internationalDestinations[] = $result;
            }
        }

        return [
            'domestic' => $domesticDestinations,
            'international' => $internationalDestinations
        ];
    }



    public function GetHolidayDestinations_domestic_Show_all($web_partner_id)
    {
        return $this->select('id,destination_name,destination_slug,destination_image,starting_price')
            ->where(['status' => 'active', 'show_on_home' => 1, 'destination_type' => 'domestic', 'web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')
            ->paginate(12);
    }

    public function GetHolidayDestinations_international_Show_all($web_partner_id)
    {
        return $this->select('id,destination_name,destination_slug,destination_image,starting_price')
            ->where(['status' => 'active', 'show_on_home' => 1, 'destination_type' => 'international', 'web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')
            ->paginate(12);
    }

    public function GetVisaCountryList($web_partner_id)
    {

        return $this->db->table('visa_detail')->select('visa_type.visa_title as VisaTitle , visa_type.id as VisaTypeId,visa_country_list.id,visa_country_list.country_name,visa_country_list.country_code,visa_country_list.country_image,visa_country_list.processing_time,visa_country_list.starting_price')
            ->join('visa_type', 'visa_type.id = visa_detail.visa_list_id')
            ->join('visa_country_list', 'visa_country_list.id = visa_detail.visa_country_id')

            ->groupBy('visa_detail.visa_country_id')
            /* ->groupStart() */
            ->where(["visa_detail.web_partner_id" => $web_partner_id])
            ->where(['visa_country_list.status' => 'active', 'visa_country_list.web_partner_id' => $web_partner_id])
            /*  ->groupEnd() */
            ->where(['visa_detail.status' => 'active'])
            ->orderBy('visa_country_list.id', 'DESC')->limit(8)->get()
            ->getResultArray();
    }

    public function get_trending_hotel_list($web_partner_id)
    {
        return $this->db->table('hotel_extranet_list')
            ->select('hotel_extranet_list.id,hotel_extranet_list.hotel_name,hotel_extranet_list.hotel_city,hotel_extranet_list.hotel_star_rating,hotel_extranet_list.review_rating,hotel_extranet_list.hotel_images,hotel_extranet_list.city_id,hotel_extranet_list.country_name,hotel_extranet_room.min_stay,hotel_extranet_list.address')
            ->join('hotel_extranet_room', 'hotel_extranet_room.hotel_extranet_id = hotel_extranet_list.id')
            ->where(['hotel_extranet_list.status' => 'active', 'hotel_extranet_list.trading_hotel' => 'yes', 'hotel_extranet_list.web_partner_id' => $web_partner_id])
            ->limit(8)
            ->orderBy('hotel_extranet_list.id', 'DESC')
            ->groupBy('hotel_extranet_list.id')
            ->get()
            ->getResultArray();
    }

    public function GetTopTransfercar($web_partner_id)
    {
        $builder = $this->db->table('car_info');
        $builder->select("
        car_info.id, car_info.car_name, car_info.seat_capacity, car_info.car_type, 
        car_info.per_km_charge, car_info.per_km_charge_after_ride, car_info.driver_stay_charge, 
        car_info.car_img, car_info.car_doors, car_info.no_of_cars, 
        car_city_route.transfer_type, car_city_route.car_price, car_city_route.transfer_type, 
        car_city_route.pickup, car_city_route.drop_off,  car_city_route.car_city_id, 
        car_city_route.drop_city_id, car_city_route.car_airport_id, 
        car_airport.airport_name, 
        pickup.city_name AS pickup_city_name, 
        dropoff.city_name AS dropoff_city_name");

        $builder->join("car_city_route", "car_city_route.car_info_id = car_info.id");

        $builder->join("car_airport", "car_airport.id = car_city_route.car_airport_id", "left");


        $builder->join("car_city AS pickup", "pickup.id = car_city_route.car_city_id", "left");
        $builder->join("car_city AS dropoff", "dropoff.id = car_city_route.drop_city_id", "left");


        $builder->where([
            'car_info.status' => 'active',
            'car_info.web_partner_id' => $web_partner_id,
            'car_city_route.show_on_home' => 1,
            'car_city_route.status' => 'active',
        ]);
        $builder->where("car_info.no_of_cars >", 0);
        $builder->where("car_city_route.car_price >", 0);

        $builder->groupBy('car_info.id');
        $builder->orderBy('car_info.id', 'DESC');
        $builder->limit(6);

        $result = $builder->get()->getResultArray();

        return $result;
    }

    public function GetTopTransfercarall($web_partner_id)
    {
        return $this->db->table('car_info')
            ->select('car_info.id,car_info.car_name,car_info.seat_capacity,car_info.car_type,car_info.per_km_charge,car_info.per_km_charge_after_ride,car_info.driver_stay_charge,car_info.car_img,car_info.car_doors,')->where(['car_info.status' => 'active', 'car_info.web_partner_id' => $web_partner_id])
            ->orderBy('car_info.id', 'DESC')->groupBy('car_info.id')->get()->getResultArray();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'api_webpartner_setting';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function login(array $login_credential,$service,$ip)
    {
        $service_name="";
        $servicelists=['airservice'=>'flight_api','hotelservice'=>'hotel_api','busservice'=>'bus_api','visaservice'=>'visa_api','cruiseservice'=>"cruise_api",'carservice'=>"car_api",'holidayservice'=>"holiday_api"];
        if (array_key_exists($service, $servicelists))
        {
            $service_name=$servicelists[$service];
        }
        $apibulder  =   $this->select('api_webpartner_setting.web_partner_id,api_webpartner_setting.api_username,api_webpartner_setting.api_password,api_webpartner_setting.display_supplier,web_partner.web_partner_class_id,web_partner.gst_state_code')
        ->join('web_partner', 'web_partner.id = api_webpartner_setting.web_partner_id', 'left')
        ->where("api_webpartner_setting.api_username",$login_credential['Username'])
        ->where('api_webpartner_setting.api_password', $login_credential['Password'])
        ->where('api_webpartner_setting.'.$service_name, 'active');
         if($ip!="13.233.118.235")
         {
           // $apibulder->where('find_in_set("'.$ip.'", whitelisted_ip) <> 0');
         }
         return $apibulder->get()->getRowArray();
    }
    
}



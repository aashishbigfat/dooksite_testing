<?php

namespace Modules\Home\Controllers;


use App\Modules\Home\Models\HomeModel;
use App\Controllers\BaseController;
use Modules\Home\Config\Validation;
use App\Modules\CarExtranet\Models\WebPartnerCarMarkup;
use App\Modules\CarExtranet\Models\WebPartnerCarDiscount;
use App\Modules\Visa\Models\VisaMarkupModel;
use App\Modules\Visa\Models\VisaDiscountModel;

use App\Models\CommonModel;

class Home extends BaseController
{
    protected $title;
    protected $web_partner_details;
    protected $wl_customer_id;
    protected $wl_customer_info;
    protected $Services;
    protected $web_partner_id;
    protected $HolidayServices;
    protected $customer_gst_number;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->title = "Home";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        $this->customer_gst_number = '';
        $this->wl_customer_info = array();
        if (isset(session()->get('wl_customer')['id'])) {
            $this->wl_customer_id = session()->get('wl_customer')['id'];
            $this->wl_customer_info = session()->get('wl_customer');
        }

        $this->Services = API_REQUEST_URL . '/airservice/rest/';
        $this->HolidayServices = API_REQUEST_URL . '/holidayservice/rest/';

        helper('Modules\Home\Helpers\home');
      
    }

    public function index()
    {
        $HomeModel = new HomeModel();
        $CommonModel = new CommonModel();  
        $notification_list = $HomeModel->admin_notification($this->web_partner_id); 
        $top_routes_list = $HomeModel->get_top_routes_list($this->web_partner_id); 
        $custom_routes_list = array();
        foreach ($top_routes_list as $key => $routes_list) {

            $convertedrouteslistPrice = convertCurrencyRate($routes_list['price']);
            $custom_routes_list[$key]['price'] = $convertedrouteslistPrice['ConvertedPrice'];
            $custom_routes_list[$key]['CurrencySymbol'] = $convertedrouteslistPrice['CurrencySymbol'];
            $origin = explode("(", $routes_list['origin']);
            $custom_routes_list[$key]['city_origin'] = $origin[0];
            $destination = explode("(", $routes_list['destination']);
            $custom_routes_list[$key]['city_destination'] = $destination[0];
            if ($routes_list['journeytype'] == 'oneway') {
                $arrowType = 'to';
                $journeytype = 'Oneway';
                $seperator = '-';
            } else {
                $arrowType = '<i class="fa fa-exchange" aria-hidden="true"></i>';
                $journeytype = 'Roundtrip';
                $seperator = '<i class="fa fa-exchange" aria-hidden="true"></i>';
            }
            $custom_routes_list[$key]['arrowType'] = $arrowType;
            $custom_routes_list[$key]['seperator'] = $seperator;
            $custom_routes_list[$key]['OriginCode'] = $routes_list['origin_code'];
            $custom_routes_list[$key]['image'] = $routes_list['image'];
            $custom_routes_list[$key]['DestinationCode'] = $routes_list['destination_code'];
            $custom_routes_list[$key]['url']['journeytype'] = $journeytype;
            $custom_routes_list[$key]['url']['origin'] = $routes_list['origin'];
            $custom_routes_list[$key]['url']['destination'] = $routes_list['destination'];
            $custom_routes_list[$key]['url']['adults'] = $routes_list['adult'];

            $custom_routes_list[$key]['url']['child'] = $routes_list['child'];
            $custom_routes_list[$key]['url']['infant'] = $routes_list['infant'];
            if (!empty($routes_list['depart_date'])) {
                $custom_routes_list[$key]['url']['departdate'] = $routes_list['depart_date'];
            } else {
                $custom_routes_list[$key]['url']['departdate'] = date('d M Y');
            }

            if ($routes_list['journeytype'] == 'round-trip' && !empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = $routes_list['return_date'];
            } else if ($routes_list['journeytype'] == 'round-trip' && empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = date('Y-m-d H:i:s', strtotime($custom_routes_list[$key]['url']['departdate'] . ' +1 day'));
            } else {
                $custom_routes_list[$key]['url']['returndate'] = '';
            }
            $custom_routes_list[$key]['url']['cabinclass'] = $routes_list['cabin_class'];
            if ($routes_list['direct_flight'] == 'true') {
                $custom_routes_list[$key]['url']['direct_flight'] = 1;
            } else {
                $custom_routes_list[$key]['url']['direct_flight'] = 0;
            }
            $custom_routes_list[$key]['url']['preferred_carriers'] = '';
        }



        $trending_hotel = $HomeModel->get_trending_hotel_list($this->web_partner_id); 
        foreach ($trending_hotel as $key => $hotel) {
            $locbreak = explode('-', $hotel['hotel_city']);
            $citydom = $locbreak[0] . '_' . $hotel['city_id'] . '_' . 'IN';
            $url['location'] = urlencode(str_replace('-', ' ', $hotel['hotel_city']));
            $url['cityDom'] = $citydom;
            $url['room'] = 1;
            $url['checkIn'] = date('d M y');
            $url['checkOut'] = date('d M y', strtotime('+' . $hotel['min_stay'] . 'days'));
            $url['rating'] = $hotel['hotel_star_rating'];
            $url['adult_1'] = 2;
            $url['child_1'] = 0;
            $url['nationalitycode'] = 'IN';
            $trending_hotel[$key]['url'] = http_build_query($url);
        }
        $offers_list = $CommonModel->offers_list($this->web_partner_id); 
        $offers_list['home'] = isset($offers_list['bestoffer']) ? $offers_list['bestoffer'] : [];
        unset($offers_list['bestoffer']);
        $desired_order = ['home', 'flight', 'hotel', 'holiday', 'bus', 'visa', 'car', 'activities', 'tourguide', 'hajj', 'umrah'];
        $ordered_list = [];
        foreach ($desired_order as $key) {
            if (isset($offers_list[$key])) {
                $ordered_list[$key] = $offers_list[$key];
            }
        }
        $offers_list = $ordered_list;
        foreach ($offers_list as $key => $value) {
            if (!isset($ordered_list[$key])) {
                $ordered_list[$key] = $value;
            }
        }
       
        $data = [
            'notification_list' => $notification_list,
          /*   'holiday_list' => $holiday_list,
            'holiday_themes_list' => $holiday_themes_list, */
            'trending_hotel' => $trending_hotel,
            'offers_list' => $ordered_list,
           /*  'TopTransfercar' => $homepage_car_list,
            'DomesticHolidayDestinations' => $holidayDestination['domestic'],
            'InternationalHolidayDestinations' => $holidayDestination['international'],
            'VisaCountryList' => $visaCountrywithMarkup, */
            'blog_list' => $this->session->get('bloglist'),
            'feedbac_list' => $this->session->get('feedback'),
            'slider_list' => $this->session->get('sliderList'),
            'top_routes_list' => $custom_routes_list,
            'view' => "Home\Views\index",
        ];
        return view('template/default-layout', $data);
    }
 

    public function Trending_Indian_Holidays_Destinations()
    {
        $HomeModel = new HomeModel();
        $DomesticHolidayDestinations = $HomeModel->GetHolidayDestinations_domestic_Show_all($this->web_partner_id);

        if ($DomesticHolidayDestinations) {
            foreach ($DomesticHolidayDestinations as $key => $destination) {
                $convertedPrice = convertCurrencyRate($destination['starting_price']);
                $DomesticHolidayDestinations[$key]['starting_price'] = $convertedPrice['ConvertedPrice'];
                $DomesticHolidayDestinations[$key]['CurrencySymbol'] = $convertedPrice['CurrencySymbol'];
            }
        }

        $data = [
            'title' => $this->title,
            'pager' => $HomeModel->pager,
            'DomesticHolidayDestinations' => $DomesticHolidayDestinations,
            "view" => "Home\Views/trendingIndianpages"
        ];

        return view('template/default-layout', $data);
    }

    public function Trending_International_Holidays_Destinations()
    {
        $HomeModel = new HomeModel();

        $InternationalHolidayDestinations = $HomeModel->GetHolidayDestinations_international_Show_all($this->web_partner_id, $this->request);
        if ($InternationalHolidayDestinations) {
            foreach ($InternationalHolidayDestinations as $key => $international) {
                $convertedPrice = convertCurrencyRate($international['starting_price']);
                $InternationalHolidayDestinations[$key]['starting_price'] = $convertedPrice['ConvertedPrice'];
                $InternationalHolidayDestinations[$key]['CurrencySymbol'] = $convertedPrice['CurrencySymbol'];
            }
        }
        $data = [
            'title' => $this->title,
            'InternationalHolidayDestinations' => $InternationalHolidayDestinations,
            'pager' => $HomeModel->pager,
            "view" => "Home\Views/trendingInternationalpages"
        ];

        return view('template/default-layout', $data);
    }


    public function changewebsiteCurrency()
    {
        $currencyCode = $this->request->getPost('currencyCode');
        $websiteCurrencies = $this->session->get('website_currencies');
        $website_currency = GetDefaultWebsiteCurrency($websiteCurrencies, $currencyCode);

        if ($website_currency) {
            $this->session->set('selected_website_currency', $website_currency);
            $message = array("StatusCode" => 0, "Message" => "Currency updated successfully", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "AInvalid currency code", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }
}

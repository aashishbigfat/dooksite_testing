<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\CommonModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */


    protected $helpers = ['Common', 'form', 'url', 'cookie'];


    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $validation;
    protected $SmsTemplate;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        date_default_timezone_set("UTC");
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();

        ini_set("memory_limit", "128M");
        ini_set('serialize_precision', -1);
        $date_format = 'd M y';
        defined('DateFormat') || define('DateFormat', $date_format);
        $CommonModel = new CommonModel();

        $site_url = $_SERVER['HTTP_HOST'];
        $webpartner_url = getWLurl($site_url);
        $domain_name = ["https://www." . $webpartner_url, "https://" . $webpartner_url, $webpartner_url, "http://" . $webpartner_url, "http://www." . $webpartner_url, "http://" . $webpartner_url . "/"];
        $whitelabel_details = $CommonModel->webpartner_whitelabel_details_bydomain_b2c($domain_name);


        // pr($whitelabel_details);
        // die;
        if (isset($whitelabel_details) && isset($whitelabel_details['b2c_business']) && $whitelabel_details['b2c_business'] == 'active') {
            // Define whitelabel details as constant
            defined('whitelabel') || define('whitelabel', $whitelabel_details);
            $web_partner_details = $CommonModel->getWebPartnerDetails($whitelabel_details['web_partner_id']);
           
            $web_partner_details['id'] = $whitelabel_details['web_partner_id'];
            $web_partner_redirect_url = $web_partner_details['redirect_url'];
      
            if (isset($web_partner_details['whitelabel_user']) && $web_partner_details['whitelabel_user'] == "active") {
                // Define web_partner_details as constant
                defined('web_partner_details') || define('web_partner_details', $web_partner_details);
                defined('web_partner_redirect_url') || define('web_partner_redirect_url', $web_partner_redirect_url);
               
                $api_webpartner_setting  = $web_partner_details;
                if (is_array($api_webpartner_setting) && !empty($api_webpartner_setting)) {
                    $auth_data = [
                        'Username' => $api_webpartner_setting['api_username'],
                        'Password' => $api_webpartner_setting['api_password'],
                        'Btype' => 'Web'
                    ];
                } else {
                    $auth_data = [
                        'Username' => null,
                        'Password' => null,
                        'Btype' => 'Web'
                    ];
                }
                // Define credential as constant
                defined('credential') || define('credential', $auth_data);
            } else {
                // Display inactive account view
                return view('template/inactive_account');
            }
        } else {
            if (isset($whitelabel_details) && isset($whitelabel_details['b2b_business']) && $whitelabel_details['b2b_business'] == 'active') {
                // Redirect to agent URL if b2b business is active
                $tts_redirect_url = site_url('/agent');
                header("Location: {$tts_redirect_url}");
                exit();
            } else {
                // Display inactive account view
                return view('template/inactive_account');
            }
        }

        // Start the session if it's not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Load or initialize session data
        if ($whitelabel_details) {

            /* if ($whitelabel_details['multi_currency'] == "active") { */
               /*  $websiteCurrencies = $this->session->get('website_currencies');
                if (!$websiteCurrencies) { */
                    $websiteCurrencies = $CommonModel->getWebisteCurrency($whitelabel_details['web_partner_id']); 
                    $this->session->set('website_currencies', $websiteCurrencies);
             /*    } */
                $selectedWebsiteCurrency = $this->session->get('selected_website_currency');
                if (!$selectedWebsiteCurrency) {
                    $defaultWebsiteCurrency = GetDefaultWebsiteCurrency($websiteCurrencies);
                    if ($defaultWebsiteCurrency) {
                        $this->session->set('selected_website_currency', $defaultWebsiteCurrency);
                    }
                }
           /*  } */



            $tts_pages = $this->session->get("tts_pages");

            if (empty($tts_pages) && null === $this->session->get('tts_pages')) {
                $tts_pages = $CommonModel->pages_list($whitelabel_details['web_partner_id']);
                $this->session->set('tts_pages', $tts_pages);
            }

            // Define tts_pages as constant
            defined('tts_pages') || define('tts_pages', $tts_pages);

            // super_admin_website_setting
            $super_admin_website_setting = $web_partner_details;
            $Query['email_setting'] = $whitelabel_details['email_setting'];
            $Query['payment_gateway_setting'] = $whitelabel_details['payment_gateway_setting'];
            $Query['sms_setting'] = $whitelabel_details['sms_setting'];
            $this->session->set('super_admin_website_setting', $super_admin_website_setting);
            defined('super_admin_website_setting') || define('super_admin_website_setting', $super_admin_website_setting);



            // slider list 
            $sliderList = $this->session->get("sliderList");
            if (empty($sliderList) && null === $this->session->get('sliderList')) {
                $sliderList = $CommonModel->slider_list($whitelabel_details['web_partner_id']);
                $this->session->set('sliderList', $sliderList);
            }

            //blog list 
            $bloglist = $this->session->get("bloglist");

            if (empty($bloglist) && null === $this->session->get('bloglist')) {
                $bloglist = $CommonModel->blog_list($whitelabel_details['web_partner_id']);
                $this->session->set('bloglist', $bloglist);
            }

            //offerlist 
            $offerlist = $this->session->get("offerlist");
            if (empty($offerlist) &&  null === $this->session->get('offerlist')) {
                $offerlist = $CommonModel->offers_list($whitelabel_details['web_partner_id']);
                $this->session->set('offerlist', $offerlist);
            }

            //feedback 
            $feedback = $this->session->get("feedback");
            if (empty($feedback) && null === $this->session->get('feedback')) {
                $feedback = $CommonModel->get_feedback_model_list($whitelabel_details['web_partner_id']);
                $this->session->set('feedback', $feedback);
            }

            // HolidayMenu
            $HolidayMenu = $this->session->get("HolidayMenu");
            if (empty($HolidayMenu) && null === $this->session->get('HolidayMenu')) {
                $HolidayMenu = $CommonModel->holidayshowMenu($whitelabel_details['web_partner_id']);
                $this->session->set('HolidayMenu', $HolidayMenu);
            }

            $menudata = [];
            if ($HolidayMenu) {
                foreach ($HolidayMenu as $item) {
                    $menudata[$item['destination_country']][] = $item;
                }
            }

            // Define MENUDATA as constant
            defined('MENUDATA') || define('MENUDATA', $menudata);

            // HolidayThemes
            $HolidayThemes = $this->session->get("HolidayThemes");
            if (empty($HolidayThemes)) {
                $HolidayThemes = $CommonModel->holidayshowMenuThemes($whitelabel_details['web_partner_id']);
                $this->session->set('HolidayThemes', $HolidayThemes);
            }

            // Define HOLIDAYTHEMES as constant
            defined('HOLIDAYTHEMES') || define('HOLIDAYTHEMES', $HolidayThemes);
        }

        // Default SMS templates
        $this->SmsTemplate = [
            "FlightBookingFailed" => "",
            "FlightBookingHold" => "",
            "FlightBookingConfirm" => "",
        ];
    }
}

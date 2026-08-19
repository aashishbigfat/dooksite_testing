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
    public $SmsTemplate;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here. 

        date_default_timezone_set("UTC");
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();

        ini_set("memory_limit", "128M");
        ini_set('serialize_precision', -1);
        $date_format = 'd M y';
        $CommonModel = new CommonModel();
        defined('DateFormat') || define('DateFormat', $date_format);


        $fareTypes = $CommonModel->getApiFlighFareType();
        defined('ApiFlighFareType') || define('ApiFlighFareType', $fareTypes);

        $site_url = $_SERVER['HTTP_HOST'];
        $webpartner_url = getWLurl($site_url);

        $domain_name = ["https://www." . $webpartner_url, "https://" . $webpartner_url, $webpartner_url, "http://" . $webpartner_url, "http://www." . $webpartner_url, "http://" . $webpartner_url . "/"];

        $whitelabel_details = $CommonModel->webpartner_whitelabel_details_bydomain($domain_name);
        
        if (isset($whitelabel_details) && isset($whitelabel_details['b2c_business'])  && $whitelabel_details['b2c_business'] == 'active' || isset($whitelabel_details['b2b_business']) && $whitelabel_details['b2b_business'] == 'active') {
            $web_partner_details = $CommonModel->getWebPartnerDetails($whitelabel_details['web_partner_id']);
            if (isset($web_partner_details['whitelabel_user']) && $web_partner_details['whitelabel_user'] == "active") {
                defined('whitelabel') || define('whitelabel', $whitelabel_details);
                $image_resolution_info = [];
                if (!empty($whitelabel_details['selected_template']) && !empty($whitelabel_details['image_info'])) {
                    $image_info = json_decode($whitelabel_details['image_info'], true);
                    if (!empty($image_info['Theme' . $whitelabel_details['selected_template']])) {
                        $image_resolution_info = $image_info['Theme' . $whitelabel_details['selected_template']];
                    }
                }
                defined('image_resolution_info') || define('image_resolution_info', $image_resolution_info);
                $whitelabel_web_partner_id = $whitelabel_details['web_partner_id'];
                $default_currency = $CommonModel->default_currency($whitelabel_web_partner_id);
                defined('defaultCurrency') || define('defaultCurrency', $default_currency['currency_symbol']);
                if (!$this->session->has('currencyinfo')) {
                    $getAllCurrency = $CommonModel->getAllCurrency($whitelabel_web_partner_id);
                    if ($getAllCurrency) {
                        $currencyData = [];
                        foreach ($getAllCurrency as $symbol) {
                            $currencyData[$symbol['currency']] = [
                                'decimal_point' => $symbol['decimal_point'],
                                'currency_symbol' => $symbol['currency_symbol'],
                                'convertion_rate' => $symbol['convertion_rate'],
                            ];
                        }
                        $this->session->set('currencyinfo', $currencyData);
                    }
                }
                if (isset(session()->get('admin_user')['web_partner_id'])) {
                    defined('DateFormat') || define('DateFormat', $date_format);
                }
                $super_admin_website_setting = $CommonModel->admin_website_setting($whitelabel_web_partner_id);

                defined('DepositeBalanceLimitAlert') || define('DepositeBalanceLimitAlert', 500000);
                $this->session->set('super_admin_website_setting', $super_admin_website_setting);
                defined('super_admin_website_setting') || define('super_admin_website_setting', $super_admin_website_setting);
                $this->SmsTemplate = array(
                    "FlightBookingFailed" => "",
                    "FlightBookingHold" => "",
                    "FlightBookingConfirm" => "",
                );
            } else {
                return view('template/inactive_account');
            }
        } else {
            return view('template/inactive_account');
        }
    }
}

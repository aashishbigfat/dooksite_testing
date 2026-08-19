<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Modules\Dashboard\Models\DashboardModel;
use App\Modules\Dashboard\Models\WebPartnerAccountLogModel;

class Dashboard extends BaseController
{

    protected $title; 
    protected $web_partner_id;
    protected $web_partner_details;
    protected $user_id; 

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Dashboard";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    }

    public function index()
    {
        $DashboardModel = new DashboardModel();
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        $WebPartnerAccountBal = $WebPartnerAccountLogModel->available_balance($this->web_partner_id);


        $payment_history = $DashboardModel->payment_history_pending($this->web_partner_id, $perPage = 10, $status = 'pending');

        $agent_registered_list = $DashboardModel->agent_registered_list($this->web_partner_id);
        $active_serveic =  get_active_whitelable_service();
        
        $service_array = array();  // Initialize
        $service_array_amendment = array();  // Initialize
        $service_total_booking_count = array(); // Initialize $service_total_booking_count
        $service_total_amendment_count = array(); // Initialize $service_total_amendment_count
        $service_array_refund_status = array(); // Initialize $service_array_refund_status
        /* pr($active_serveic);exit; */
        foreach ($active_serveic as $key => $service) {
         
            $dynamic_table = strtolower($service);
            $booking_result = $DashboardModel->getServiceCountInfo($dynamic_table . '_booking_list', array('web_partner_id' => $this->web_partner_id, 'booking_source <>' => 'Back_Office'), 'booking_status');
            $service_total_booking_count[$service] = count($booking_result);
            $values = array_column($booking_result, "booking_status");
            $service_array[$service] = array_count_values($values);
            if($service == "Umrah"){
                $dynamic_table = "umrah_package";
            }
            if($service == "Hajj"){
                $dynamic_table = "hajj_package";
            }
            $amendment_result = $DashboardModel->getServiceCountInfo($dynamic_table . '_amendment', array('web_partner_id' => $this->web_partner_id), 'amendment_status,refund_status');
            $service_total_amendment_count[$service] = count($amendment_result);
            $values_amendment_result = array_column($amendment_result, "amendment_status");
            $service_array_amendment[$service] = array_count_values($values_amendment_result);


            $refund_statuses = array_column($amendment_result, "refund_status");
            $refund_statuses = array_map(function ($value) {
                return is_scalar($value) ? strval($value) : '';
            }, $refund_statuses);
            $service_array_refund_status[$service] = array_count_values($refund_statuses);

        }
        unset($booking_result);
        unset($values);
        unset($amendment_result);
        unset($values_amendment_result);
        unset($values_amendment_refund_status);

        $data = [
            'payment_history' => $payment_history,
            'agent_registered_list' => $agent_registered_list,
            'available_balance' => $WebPartnerAccountBal,
            'totle_booking_cont' => $service_total_booking_count,
            'totle_amendment_cont' => $service_total_amendment_count,
            'commonData' => $service_array,
            'CommonDataAmendment' => $service_array_amendment,
            'CommonDatarefund_status' => $service_array_refund_status,
            'UserIp' => $this->request->getIpAddress(),
            'title' => $this->title,
            'view' => "Dashboard\Views\dashbaord"
        ];



        return view('template/sidebar-layout', $data);
    }
}

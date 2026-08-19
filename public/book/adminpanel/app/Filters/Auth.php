<?php

namespace App\Filters;

use App\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class Auth implements FilterInterface
{
    protected $session;
    protected $commonModel;

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session service
        $this->commonModel = new CommonModel(); // Initialize model service
    }

    public function before(RequestInterface $request, $arguments = null)
        {  

        // Handle special cases related to payment gateway responses
        $path = $request->getPath();
        if ($path == 'payment/response' || $path == 'payment/makepaymentesponse') {
            $orderNo = $request->getVar('orderNo');
            if ($orderNo) {
                $paymentDetail = $this->commonModel->get_payment_detail($orderNo);
                if ($paymentDetail) {
                    $user = $this->commonModel->get_user_detail($paymentDetail['user_id'], $paymentDetail['web_partner_id']);
                    $this->session->set('admin_user', $user);
                }
            }
        }

        // Check if the user is authenticated
        if (!$this->session->get('admin_user')) {
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Optional: Add logic to execute after the request
    }
}

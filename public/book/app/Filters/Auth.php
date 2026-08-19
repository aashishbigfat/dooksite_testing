<?php
namespace App\Filters;

use App\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        /*------Start Check Browser destroy session on payment getway -----*/
        if ($request->getPath() == 'payment/response' || $request->getPath() == 'payment/makepaymentesponse') {
            if ($request->getVar('orderNo') || $request->getVar('orderId') || $request->getVar('transactionId') || $request->getVar('razorpay_order_id')) {
                $orderNo = "";
                if (!empty($request->getVar('orderNo'))) {
                    $orderNo = $request->getVar('orderNo');
                } else if (!empty($request->getVar('orderId'))) {
                    $orderNo = $request->getVar('orderId');
                } else if (!empty($request->getVar('transactionId'))) {
                    $orderNo = $request->getVar('transactionId');
                } else if (!empty($request->getVar('razorpay_order_id'))) {
                    $orderNo = $request->getVar('razorpay_order_id');
                }
                
                $CommonModel = new CommonModel();
                $payment_detail = $CommonModel->get_payment_detail($orderNo);
                if ($payment_detail) {
                    $this->session = \Config\Services::session();
                    $user = $CommonModel->get_user_detail($payment_detail['wl_customer_id'], $payment_detail['web_partner_id']);
                    $this->session->set('wl_customer', $user);
                }
            }
        }
        /*------End Check Browser destroy session on payment getway -----*/
        if (!session()->get('wl_customer')) {
            return redirect()->to(base_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
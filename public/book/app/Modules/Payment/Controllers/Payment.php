<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;
use Modules\Payment\Controllers\CCAVENUE;
use Modules\Payment\Controllers\HDFC;
use Modules\Payment\Controllers\ICICI;
use Modules\Payment\Controllers\PHONEPE;
use Modules\Payment\Controllers\BILLDESK;
use Modules\Payment\Controllers\CASHFREE;
use Modules\Payment\Controllers\RAZORPAY;
use Modules\Payment\Controllers\PaymentEaseBuzz;
use Modules\Payment\Controllers\PAYU;
use Modules\Payment\Controllers\Amazon;
use Modules\Payment\Controllers\Paypal;


class Payment extends BaseController
{
    public $title;
    public $user_id;
    public $wl_customer_id;
    public $web_partner_details;
    public $web_partner_id;
    public $web_partner_class_id;
    public $payment_gateway_type;
    public $payment_gateway_status;
    public $payment_gateway_name;
    public $api_owner_type;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Payment";
        helper('Modules\Payment\Helpers\payment');
        $this->user_id = '';
        $this->wl_customer_id = '';
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->web_partner_class_id = web_partner_details['web_partner_class_id'];
        $this->payment_gateway_type = trim(whitelabel['payment_gateway_type']);
        $this->payment_gateway_status = trim(whitelabel['payment_gateway_status']);
        $this->payment_gateway_name = explode(',', trim(whitelabel['payment_gateway_name']));
        $this->api_owner_type = trim(whitelabel['api_owner']);

        if ($this->payment_gateway_status == "inactive") {
            return view('template/custom-error-layout', ['error_message' => 'Payment Methos Not Active']);
        }

        if (isset(session()->get('wl_customer')['id'])) {
            /*   $this->user_id = session()->get('wl_customer')['id']; */
            $this->wl_customer_id = session()->get('wl_customer')['id'];
        }
    }

    public function index()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['booking_id'];
        $SearchTokenId = isset($paymentdata['SearchTokenId']) ? $paymentdata['SearchTokenId'] : "";
        $PaymentModel = new PaymentModel();
        $total_price = 0;
        $CurrencySymbol = '';

        if ($service == 'flight') {
            $CurrencySymbol  = '';
            $booking_data = $PaymentModel->get_flight_booking_detail($service, $booking_id, $this->web_partner_id, $SearchTokenId);
            $currency_rate =  $booking_data['OB']['currency_rate'];
            if (empty($booking_data)) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($booking_data['OB']['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }


            if (isset($booking_data['OB']['customer_fare_break_up'])) {
                $AgentDataFare = json_decode($booking_data['OB']['customer_fare_break_up'], true);
                $customer_fare_break_up = convertBookingCurrencyRate($AgentDataFare, $booking_data['OB']['booking_currency'], $booking_data['OB']['default_currency'], $booking_data['OB']['currency_rate']);
                $booking_data['OB']['customer_fare_break_up'] = json_encode($customer_fare_break_up['ConvertPrice']);
            }

            if (isset($booking_data['IB']['customer_fare_break_up'])) {
                $AgentDataFare = json_decode($booking_data['IB']['customer_fare_break_up'], true);
                $customer_fare_break_up = convertBookingCurrencyRate($AgentDataFare, $booking_data['IB']['booking_currency'], $booking_data['IB']['default_currency'], $booking_data['IB']['currency_rate']);
                $booking_data['IB']['customer_fare_break_up'] = json_encode($customer_fare_break_up['ConvertPrice']);
            }

            $convertBookingCurrencyRate = convertBookingCurrencyRate($booking_data['OB']['total_price'], $booking_data['OB']['booking_currency'], $booking_data['OB']['default_currency'], $booking_data['OB']['currency_rate']);
            $total_price += $convertBookingCurrencyRate['ConvertPrice'];
            $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];

            if (isset($booking_data['IB']['total_price'])) {
                $convertBookingCurrencyRate = convertBookingCurrencyRate($booking_data['IB']['total_price'], $booking_data['IB']['booking_currency'], $booking_data['IB']['default_currency'], $booking_data['IB']['currency_rate']);
                $total_price += $convertBookingCurrencyRate['ConvertPrice'];
                $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];
            }
        } else {

            $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
            
            if (!$booking_data) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($booking_data['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }

            $AgentDataFare = json_decode($booking_data['customer_fare_break_up'], true);
            $customer_fare_break_up = convertBookingCurrencyRate($AgentDataFare, $booking_data['booking_currency'], $booking_data['default_currency'], $booking_data['currency_rate']);
            $booking_data['customer_fare_break_up'] = json_encode($customer_fare_break_up['ConvertPrice']);

            $currency_rate =  $booking_data['currency_rate'];

            if (isset($booking_data['coupon_info'])) {
                $CouponInfoDataFare = json_decode($booking_data['coupon_info'], true);
                $coupon_info_fare_break_up = convertBookingCurrencyRate($CouponInfoDataFare, $booking_data['booking_currency'], $booking_data['default_currency'], $booking_data['currency_rate']);
                $booking_data['coupon_info'] = json_encode($coupon_info_fare_break_up['ConvertPrice']);
            } else {
                $booking_data['coupon_info'] = null;
            }

            $convertBookingCurrencyRate = convertBookingCurrencyRate($booking_data['total_price'], $booking_data['booking_currency'], $booking_data['default_currency'], $booking_data['currency_rate']);
            $total_price = $convertBookingCurrencyRate['ConvertPrice'];
            $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];
        }

        $PaymentModes = Payment::payment_mode($service, $total_price);
        

        $data = [
            'title' => $this->title,
            'total_price' => $total_price,
            'CurrencySymbol' => $CurrencySymbol,
            'service' => $service,
            'booking_id' => $booking_id,
            'booking_data' => $booking_data,
            'payment_token' => $payment_token,
            "search_token_id" => $SearchTokenId,
            "currency_rate" => isset($currency_rate) ? $currency_rate : 1,
            'PaymentModes' => $PaymentModes,
            'view' => "Payment\Views\index",
        ];

        return view('template/default-layout', $data);
    }



    public function getPaymentsProceedAmounts()
    {
        $uri = service('uri');
        $paymentToken = trim($uri->getSegment(3));
        $paymentData = json_decode(base64_decode($paymentToken), true);
        if (!$paymentData) {
            return view('template/custom-error-layout', ['error_message' => 'Payment record not found']);
        }
        $service = $paymentData['service'] ?? '';
        $booking_id = $paymentData['id'] ?? '';
        $getPaymentMode = $paymentData['mode'] ?? '';
        $payableAmount = abs($paymentData['fare'] ?? 0);
        $searchTokenId = $paymentData['search_token_id'] ?? 0;
        $getCouponInfo = array();
        $PaymentModel = new PaymentModel();
        if ($service == 'flight') {
            $bookingids = array_values($paymentData['id']);
            $booking_id = implode(',', $bookingids);

            $bookingData = $PaymentModel->get_flight_booking_detail($service, $paymentData['id'], $this->web_partner_id, $searchTokenId);
            if (empty($bookingData)) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($bookingData['OB']['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'The Booking session expired please try again']);
            }

            $getActuallyPaidAmounts  = 0;
            $bookingPrice  = 0;
            $first_name = $bookingData['OB']['first_name'];
            $last_name = $bookingData['OB']['last_name'];
            $email_id = $bookingData['OB']['email_id'];
            $mobile_number = $bookingData['OB']['mobile_number'];
            $DefaultCurrency = $bookingData['OB']['default_currency'];
            $ConversionRate = $bookingData['OB']['currency_rate'];
            $BookingCurrency = $bookingData['OB']['booking_currency'];

            $CurrencySymbol = getBookingCurrencyIcon($bookingData['OB']['booking_currency']);
            $flightbooking_ref_number = array_column($bookingData, "booking_ref_number");
            $booking_ref_number = implode(",", $flightbooking_ref_number);
            $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $bookingData['OB']['origin'] . '-' . $bookingData['OB']['destination'] . '/ JourneyType' . $bookingData['OB']['journey_type'], 'TravelDate' => $bookingData['OB']['departure_date']));


            /*  foreach ($bookingData as $booking_data) {
                $bookingPrice = abs((float)$bookingPrice + $booking_data['total_price']);
            } */
            foreach ($bookingData as $booking_data) {
                $bookingPrice += (float)$booking_data['total_price'];
                $getActuallyPaidAmounts += (float)$booking_data['total_price'];
            }

            $bookingPrice = abs($bookingPrice);
            $getActuallyPaidAmounts = abs($getActuallyPaidAmounts);
        } else {
            $bookingData = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);

            if (!$bookingData) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($bookingData['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }


            $first_name = $bookingData['first_name'];
            $last_name = $bookingData['last_name'];
            $email_id = $bookingData['email_id'];
            $mobile_number = $bookingData['mobile_number'];
            $service_log = $bookingData['service_log'];
            $booking_ref_number = $bookingData['booking_ref_number'];
            $bookingPrice = abs((float) $bookingData['total_price']);
            $getActuallyPaidAmounts = abs((float) $bookingData['total_price']);
            $DefaultCurrency = $bookingData['default_currency'];
            $ConversionRate = $bookingData['currency_rate'];
            $BookingCurrency = $bookingData['booking_currency'];
            $getCouponInfo = $bookingData['coupon_info'];

            $CurrencySymbol = getBookingCurrencyIcon($bookingData['booking_currency']);
        }

        $webpartnerBalance = get_balance();
        if (isset($webpartnerBalance) && $webpartnerBalance < 0) {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }

        if ($getPaymentMode == 'wallet') {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        } else {
            $SetTableName = ($this->payment_gateway_type === "webpartner") ? "web_partner_payment_transaction" : "super_admin_payment_transaction";
            $getPaymentRecord = $PaymentModel->checkpayment_record($SetTableName, ['web_partner_id' => $this->web_partner_id, 'service' => $service, 'booking_ref_no' => $booking_id]);

            if (empty($getPaymentRecord)) {
                /*  -------------------------------- Convenience Fee Check -------------------------------- */
                $PaymentModes = Payment::payment_mode($paymentData['service'], $bookingPrice);
                /*   prd($PaymentModes);exit; */
                $convenienceFeeAmounts = array();
                if (!empty($PaymentModes)) {
                    $found = false;
                    if (isset($PaymentModes[$paymentData['mode']]) && $PaymentModes[$paymentData['mode']] && !empty($PaymentModes[$paymentData['mode']])) {
                        foreach ($PaymentModes[$paymentData['mode']]['SubModes'] as $subMode) {
                            $paymentgateway = isset($paymentData['gateway']) ? $paymentData['gateway'] : '';
                            if ($subMode['Gateway'] == $paymentgateway) {
                                $convenienceFeeAmounts = $subMode;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            return view('template/custom-error-layout', ['error_message' => ' !! The payment mode not detected please try again !!']);
                        }
                    }
                }



                if(isset($convenienceFeeAmounts['ValueType']) && $convenienceFeeAmounts['ValueType'] =='percentage'){ 
                    $ActuallyConvenienceFee = isset($convenienceFeeAmounts['Value']) ? abs($convenienceFeeAmounts['Value']) : 0;
                   $ActuallyConvenienceFee =  ($bookingPrice*$ActuallyConvenienceFee)/100; 
                }else{
                    $ActuallyConvenienceFee = isset($convenienceFeeAmounts['Value']) ? abs($convenienceFeeAmounts['Value']) : 0;
                }


              /*   $ActuallyConvenienceFee = isset($convenienceFeeAmounts['Value']) ? abs($convenienceFeeAmounts['Value']) : 0; */
              
                /*  -------------------------------- Convenience Fee Check -------------------------------- */


                /*  -------------------------------- Coupon Amount Check -------------------------------- */
                /* if (!empty($getCouponInfo) && $getCouponInfo != '[]' && $getCouponInfo != NULL) {
                    $get_coupon_info_amount = get_coupon_info_amount($getCouponInfo, $getActuallyPaidAmounts);
                    if (!empty($get_coupon_info_amount)) {
                        if ($get_coupon_info_amount['couponApplied'] == true) {
                            $getActuallyPaidAmounts = $get_coupon_info_amount['finalAmount'];
                        } else {
                            $getActuallyPaidAmounts = $get_coupon_info_amount['finalAmount'];
                        }
                    }
                } */
                /*  -------------------------------- Coupon Amount Check -------------------------------- */

                $credit_card_name = "";
                if ($paymentData['mode'] == "CRDC") {
                    $credit_card_name = get_card_name($paymentData['type']);
                }


                $convertBookingCurrencyRate = convertBookingCurrencyRate($bookingPrice, $BookingCurrency, $DefaultCurrency, $ConversionRate);
                $bookingPrice = $convertBookingCurrencyRate['ConvertPrice'];

                $getConveniencefeeamounts = 0;
                if($ActuallyConvenienceFee > 0){
                    $convertBookingCurrencyRate = convertBookingCurrencyRate($ActuallyConvenienceFee, $BookingCurrency, $DefaultCurrency, $ConversionRate);
                    $getConveniencefeeamounts = $convertBookingCurrencyRate['ConvertPrice'];
                }


                $SavePaymentMode = get_payment_mode($paymentData['mode']);
                $bookingPrice += $getConveniencefeeamounts;

                

                $setPaymentRequestData = array(
                    'FirstName' => $first_name,
                    'LastName' => $last_name,
                    'Email' => $email_id,
                    'MobileNumber' => $mobile_number,
                    'Currency' => 'INR',
                    'RedirectURL' => site_url('payment/response'),
                    'CancelURL' => site_url('payment/response'),
                    'NotifyURL' => site_url('payment/notifyurl'),
                    'callbackUrl' => site_url('payment/callback-url'),
                    'PaymentMode' => $getPaymentMode,
                    'Amount' => abs($bookingPrice),
                    'Service' => $service,
                    'BookingId' => $booking_id,
                    'convenience_fee' => $getConveniencefeeamounts,
                    'SavePaymentMode' => $SavePaymentMode,
                    'UserId' => $this->user_id,
                    'WebPartnerId' => $this->web_partner_id,
                    'ServiceLog' => $service_log,
                    'PaymentSource' => "Wl_b2c",
                    'wl_customer_id' => $this->wl_customer_id,
                    'CardName' => $credit_card_name,
                    'TransactionTable' => $SetTableName,
                    "ActuallyAmounts" => abs((float) $getActuallyPaidAmounts) + abs((float) $ActuallyConvenienceFee),
                    'ActuallyConvenienceFee' => abs($ActuallyConvenienceFee),
                    "DefaultCurrency" => $DefaultCurrency,
                    "ConversionRate" => $ConversionRate,
                    "BookingCurrency" => $BookingCurrency,
                    "CurrencySymbol" => $CurrencySymbol,
                    "AdditionalInfo1" => '',
                    "AdditionalInfo2" => '',
                    'Udf1' => $service,
                    'Udf2' => web_partner_details['company_name'],
                    'Udf3' => "",
                    'Udf4' => "",
                    'Udf5' => $SavePaymentMode
                );



                $checkPaymentGatewayName = isset($paymentData['gateway']) ? $paymentData['gateway'] : "NotAvailable";
                if ($checkPaymentGatewayName !== "NotAvailable") {
                    if ($checkPaymentGatewayName == "CASHFREE") {
                        $CASHFREE = new CASHFREE();
                        $cashfreeResponse = $CASHFREE->request($setPaymentRequestData);
                        if (isset($cashfreeResponse['Error']['ErrorCode']) && $cashfreeResponse['Error']['ErrorCode'] == 0) {
                            return redirect()->to($cashfreeResponse['Result']['paymentLink']);
                        } else {
                            return $this->getPaymentGatewayErrors($cashfreeResponse, $booking_ref_number);
                        }
                    } elseif ($checkPaymentGatewayName == "RAZORPAY") {
                        $RAZORPAY = new RAZORPAY();
                        $RAZORPAYResponse = $RAZORPAY->request($setPaymentRequestData);
                        if (isset($RAZORPAYResponse['Error']['ErrorCode']) && $RAZORPAYResponse['Error']['ErrorCode'] == 0) {
                            $data = ['title' => 'Payment', 'Paytm_details' => $RAZORPAYResponse['Result']['GatewayPaymentDetails'],];
                            return view('Modules\Payment\Views\razorpay\index', $data);
                        } else {
                            return $this->getPaymentGatewayErrors($RAZORPAYResponse);
                        }
                    } elseif ($checkPaymentGatewayName == "PHONEPE") {
                        $PHONEPE = new PHONEPE();
                        $PHONEPEResponse = $PHONEPE->request($setPaymentRequestData);
                        if (isset($PHONEPEResponse['Error']['ErrorCode']) && $PHONEPEResponse['Error']['ErrorCode'] == 0) {
                            return redirect()->to($PHONEPEResponse['Result']['url']);
                        } else {
                            return $this->getPaymentGatewayErrors($PHONEPEResponse);
                        }
                    } elseif ($checkPaymentGatewayName == "AMAZONPAY") {
                        $AmazonPayServices = new Amazon();
                        $gatwayResponseData = $AmazonPayServices->request($setPaymentRequestData);
                        if (isset($gatwayResponseData['Error']['ErrorCode']) && $gatwayResponseData['Error']['ErrorCode'] == 0) {
                            $data = ['title' => 'Payment', 'Paytm_details' => $gatwayResponseData['Result']['GatewayPaymentDetails'], 'URL' => $gatwayResponseData['Result']['URL'],];
                            return view('Modules\Payment\Views\amazon\index', $data);
                        } else {
                            return $this->getPaymentGatewayErrors($gatwayResponseData);
                        }
                    } elseif ($checkPaymentGatewayName == "PAYPAL") {
                        $PAYPAL = new Paypal();
                        $PAYPALResponse = $PAYPAL->request($setPaymentRequestData);
                        if (isset($PAYPALResponse['Error']['ErrorCode']) && $PAYPALResponse['Error']['ErrorCode'] == 0) {
                            return redirect()->to($PAYPALResponse['Result']['URL']);
                        } else {
                            return $this->getPaymentGatewayErrors($PAYPALResponse);
                        }
                    } elseif ($checkPaymentGatewayName == "BILLDESK") {
                        $BILLDESK = new BILLDESK();
                        $BILLDESKResponse = $BILLDESK->request($setPaymentRequestData);
                        if (isset($BILLDESKResponse['Error']['ErrorCode']) && $BILLDESKResponse['Error']['ErrorCode'] == 0) {
                            return redirect()->to($BILLDESKResponse['Result']['url']);
                        } else {
                            return $this->getPaymentGatewayErrors($BILLDESKResponse);
                        }
                    } elseif ($checkPaymentGatewayName == "CCAVENUE") {
                        $CCAVENUE = new CCAVENUE();
                        return $CCAVENUE->request($setPaymentRequestData);
                    } elseif ($checkPaymentGatewayName == "PAYU") {
                        $PAYU = new PAYU();
                        return $PAYU->request($setPaymentRequestData);
                    } elseif ($checkPaymentGatewayName == "ICICI") {
                        $ICICI = new ICICI();
                        return $ICICI->request($setPaymentRequestData);
                    } elseif ($checkPaymentGatewayName == "HDFC") {
                        $HDFC = new HDFC();
                        return $HDFC->request($setPaymentRequestData);
                    } elseif ($checkPaymentGatewayName == "EASEBUZZ") {
                        $EASEBUZZ = new PaymentEaseBuzz();
                        $EASEBUZZResponse = $EASEBUZZ->request($setPaymentRequestData);
                        if (isset($EASEBUZZResponse['Error']['ErrorCode']) && $EASEBUZZResponse['Error']['ErrorCode'] == 0) {
                            return redirect()->to($EASEBUZZResponse['Result']['url']);
                        } else {
                            return $this->getPaymentGatewayErrors($EASEBUZZResponse);
                        }
                    } elseif ($checkPaymentGatewayName == "EASEBUZZ") {
                        $PAYU = new PAYU();
                        return $PAYU->request($setPaymentRequestData);
                    }
                } else {
                    return view('template/custom-error-layout', ['error_message' => ' !! The payment gateway mismatch please try again !!']);
                }
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        }
    }


    private function getPaymentGatewayErrors($response, $booking_ref_number = null)
    {
        $errorMessage = $response['Error']['ErrorMessage'] ?? 'Unknown error occurred';
        $urlParam = http_build_query(['message' => $errorMessage, 'reference-no' => $booking_ref_number]);
        return redirect()->to(site_url('payment/payment-error?' . $urlParam));
    }






    public function proceed_payment_bkp_abhay()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(base64_decode($payment_token), true);

        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }

        $service = $paymentdata['service'];
        if ($service == "flight") {
            $booking_id = $paymentdata['id'];
            $payment_mode = $paymentdata['mode'];
            $payable_amount = abs($paymentdata['fare']);
            $SearchTokenId = $paymentdata['search_token_id'];
            $PaymentModel = new PaymentModel();
            $booking_data = $PaymentModel->get_flight_booking_detail($service, $booking_id, $this->web_partner_id, $SearchTokenId);
            $bookingPrice = 0;
            if (empty($booking_data)) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($booking_data['OB']['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }


            $actuallyPaid_amounts  = 0;
            if (((isset($booking_data['OB']['total_price']) && $booking_data['OB']['total_price'] <= abs($paymentdata['fare']))) || (isset($booking_data['IB']['total_price']) && $booking_data['IB']['total_price'] <= abs($paymentdata['fare']))) {
                $bookingids = array_values($paymentdata['id']);
                $booking_id = implode(',', $bookingids);
                $first_name = $booking_data['OB']['first_name'];
                $last_name = $booking_data['OB']['last_name'];
                $email_id = $booking_data['OB']['email_id'];
                $mobile_number = $booking_data['OB']['mobile_number'];

                $DefaultCurrency = $booking_data['OB']['default_currency'];
                $ConversionRate = $booking_data['OB']['currency_rate'];
                $BookingCurrency = $booking_data['OB']['booking_currency'];
                $CurrencySymbol = getBookingCurrencyIcon($booking_data['OB']['booking_currency']);

                $flightbooking_ref_number = array_column($booking_data, "booking_ref_number");
                $booking_ref_number = implode(",", $flightbooking_ref_number);
                $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $booking_data['OB']['origin'] . '-' . $booking_data['OB']['destination'] . '/ JourneyType' . $booking_data['OB']['journey_type'], 'TravelDate' => $booking_data['OB']['departure_date']));

                $actuallyPaid_amounts  = abs((float) $booking_data['OB']['total_price']);
                if (isset($booking_data['IB']['total_price']) && $booking_data['IB']['total_price']) {
                    $actuallyPaid_amounts  = $actuallyPaid_amounts  + abs((float) $booking_data['IB']['total_price']);
                }

                foreach ($booking_data as $booking_data) {
                    $bookingPrice = $bookingPrice + $booking_data['total_price'];
                }
            } else {
                return view('template/custom-error-layout', ['error_message' => ' !! The price mismatch please try again !!']);
            }
        } else {
            $service = $paymentdata['service'];
            $booking_id = $paymentdata['id'];
            $payment_mode = $paymentdata['mode'];
            $payable_amount = abs($paymentdata['fare']);

            $PaymentModel = new PaymentModel();
            $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);

            if (!$booking_data) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($booking_data['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }

            $actuallyPaid_amounts  = abs((float) $booking_data['total_price']);
            pr($paymentdata['fare']);
            prd($actuallyPaid_amounts);
            exit;


            if (!empty($actuallyPaid_amounts) &&  $actuallyPaid_amounts <= abs($paymentdata['fare'])) {
                $first_name = $booking_data['first_name'];
                $last_name = $booking_data['last_name'];
                $email_id = $booking_data['email_id'];
                $mobile_number = $booking_data['mobile_number'];
                $service_log = $booking_data['service_log'];
                $booking_ref_number = $booking_data['booking_ref_number'];
                $bookingPrice = $booking_data['total_price'];

                $DefaultCurrency = $booking_data['default_currency'];
                $ConversionRate = $booking_data['currency_rate'];
                $BookingCurrency = $booking_data['booking_currency'];
                $CurrencySymbol = getBookingCurrencyIcon($booking_data['booking_currency']);
            } else {
                return view('template/custom-error-layout', ['error_message' => ' !! The price mismatch please try again !!']);
            }
        }

        $webpartnerBalance = get_balance();
        if (isset($webpartnerBalance) && $webpartnerBalance < 0) {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }
        if ($payment_mode == 'wallet') {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        } else {

            $PaymentModel = new PaymentModel();

            $tableName = "super_admin_payment_transaction";
            if ($this->payment_gateway_type === "webpartner") {
                $tableName = "web_partner_payment_transaction";
            }
            $paymentrecord = $PaymentModel->checkpayment_record($tableName, ['web_partner_id' => $this->web_partner_id, 'service' => $service, 'booking_ref_no' => $booking_id]);

            if (empty($paymentrecord)) {

                /*  -------------------------------- Convenience Fee Check -------------------------------- */
                $PaymentModes = Payment::payment_mode($paymentdata['service'], $actuallyPaid_amounts);
                $convenienceFeeAmounts = array();
                if (!empty($PaymentModes)) {
                    $found = false;
                    if (isset($PaymentModes[$paymentdata['mode']]) && $PaymentModes[$paymentdata['mode']] && !empty($PaymentModes[$paymentdata['mode']])) {
                        foreach ($PaymentModes[$paymentdata['mode']]['SubModes'] as $subMode) {
                            $paymentgateway = isset($paymentdata['gateway']) ? $paymentdata['gateway'] : '';
                            if ($subMode['Gateway'] == $paymentgateway) {
                                $convenienceFeeAmounts = $subMode;
                                $found = true;
                                break;
                            }
                        }

                        if (!$found) {
                            return view('template/custom-error-layout', ['error_message' => ' !! The payment mode not detected please try again !!']);
                        }
                    }
                }

                $convenience_fee_amounts = isset($convenienceFeeAmounts['Value']) ? abs($convenienceFeeAmounts['Value']) : 0;

                /*  -------------------------------- Convenience Fee Check -------------------------------- */
                $credit_card_name = "";
                if ($paymentdata['mode'] == "CRDC") {
                    $credit_card_name = get_card_name($paymentdata['type']);
                }


                $SavePaymentMode = get_payment_mode($paymentdata['mode']);
                $actuallyPaid_amounts += $convenience_fee_amounts;

                $request = array(
                    'FirstName' => $first_name,
                    'LastName' => $last_name,
                    'Email' => $email_id,
                    'MobileNumber' => $mobile_number,
                    'Currency' => 'INR',
                    'RedirectURL' => site_url('payment/response'),
                    'CancelURL' => site_url('payment/response'),
                    'NotifyURL' => site_url('payment/notifyurl'),
                    'callbackUrl' => site_url('payment/callback-url'),
                    'PaymentMode' => $payment_mode,
                    'Amount' => abs($actuallyPaid_amounts),
                    'Service' => $service,
                    'BookingId' => $booking_id,
                    'convenience_fee' => $convenience_fee_amounts,
                    'SavePaymentMode' => $SavePaymentMode,
                    'UserId' => $this->user_id,
                    'WebPartnerId' => $this->web_partner_id,
                    'ServiceLog' => $service_log,
                    'PaymentSource' => "Wl_b2c",
                    'wl_customer_id' => $this->wl_customer_id,
                    'CardName' => $credit_card_name,
                    'TransactionTable' => $tableName,
                    "ActuallyAmounts" => $bookingPrice,
                    "DefaultCurrency" => $DefaultCurrency,
                    "ConversionRate" => $ConversionRate,
                    "BookingCurrency" => $BookingCurrency,
                    "CurrencySymbol" => $CurrencySymbol,
                    "AdditionalInfo1" => '',
                    "AdditionalInfo2" => '',
                    'Udf1' => $service,
                    'Udf2' => web_partner_details['company_name'],
                    'Udf3' => "",
                    'Udf4' => "",
                    'Udf5' => $SavePaymentMode
                );


                $payment_gateway = $paymentdata['gateway'];

                if ($payment_gateway == "CASHFREE") {
                    $CASHFREE = new CASHFREE();
                    $cashfreeResponse = $CASHFREE->request($request);
                    if (isset($cashfreeResponse['Error']['ErrorCode']) && $cashfreeResponse['Error']['ErrorCode'] == 0) {
                        return redirect()->to($cashfreeResponse['Result']['paymentLink']);
                    } else {
                        $Errormessag = $cashfreeResponse['Error']['ErrorMessage'];
                        $url_param = http_build_query(array('message' => $Errormessag, 'reference-no' => $booking_ref_number));
                        return redirect()->to(site_url('payment/payment-error?' . $url_param));
                    }
                } else if ($payment_gateway == "RAZORPAY") {
                    $RAZORPAY = new RAZORPAY();
                    $RAZORPAYResponse = $RAZORPAY->request($request);
                    if (isset($RAZORPAYResponse['Error']['ErrorCode']) && $RAZORPAYResponse['Error']['ErrorCode'] == 0) {
                        $data = [
                            'title' => 'Payment',
                            'Paytm_details' => $RAZORPAYResponse['Result']['GatewayPaymentDetails'],
                        ];
                        return view('Modules\Payment\Views\razorpay\index', $data);
                    } else {
                        $Errormessage = $RAZORPAYResponse['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "PHONEPE") {
                    $PHONEPE = new PHONEPE();
                    $PHONEPEResponse = $PHONEPE->request($request);

                    if (isset($PHONEPEResponse['Error']['ErrorCode']) && $PHONEPEResponse['Error']['ErrorCode'] == 0) {
                        return redirect()->to($PHONEPEResponse['Result']['url']);
                    } else {
                        $Errormessage = $PHONEPEResponse['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "AMAZONPAY") {
                    $AmazonPayServices = new Amazon();
                    $gatwayResponseData = $AmazonPayServices->request($request);
                    if (isset($gatwayResponseData['Error']['ErrorCode']) && $gatwayResponseData['Error']['ErrorCode'] == 0) {
                        $data = [
                            'title' => 'Payment',
                            'Paytm_details' => $gatwayResponseData['Result']['GatewayPaymentDetails'],
                            'URL' => $gatwayResponseData['Result']['URL'],
                        ];

                        return view('Modules\Payment\Views\amazon\index', $data);
                    } else {
                        $Errormessage =   $gatwayResponseData['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "PAYPAL") {
                    $PAYPAL = new Paypal();
                    $PAYPALResponse = $PAYPAL->request($request);

                    if (isset($PAYPALResponse['Error']['ErrorCode']) && $PAYPALResponse['Error']['ErrorCode'] == 0) {
                        return redirect()->to($PAYPALResponse['Result']['URL']);
                    } else {
                        $Errormessage = $PAYPALResponse['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "BILLDESK") {
                    $BILLDESK = new BILLDESK();
                    $BILLDESKResponse = $BILLDESK->request($request);

                    if (isset($BILLDESKResponse['Error']['ErrorCode']) && $BILLDESKResponse['Error']['ErrorCode'] == 0) {
                        return redirect()->to($BILLDESKResponse['Result']['url']);
                    } else {
                        $Errormessage = $BILLDESKResponse['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "CCAVENUE") {
                    $CCAVENUE = new CCAVENUE();
                    return $CCAVENUE->request($request);
                } else if ($payment_gateway == "ICICI") {
                    $ICICI = new ICICI();
                    return $ICICI->request($request);
                } else if ($payment_gateway == "HDFC") {
                    $HDFC = new HDFC();
                    return $HDFC->request($request);
                } else if ($payment_gateway == "EASEBUZZ") {
                    $EASEBUZZ = new PaymentEaseBuzz();
                    $EASEBUZZ = $EASEBUZZ->request($request);
                    if (isset($EASEBUZZ['Error']['ErrorCode']) && $EASEBUZZ['Error']['ErrorCode'] == 0) {
                        return redirect()->to($EASEBUZZ['Result']['url']);
                    } else {
                        $Errormessage = $EASEBUZZ['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                } else if ($payment_gateway == "PAYU") {
                    $PAYU = new PAYU();
                    return $PAYU->request($request);
                }
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        }
    }



    protected function FlightWallet($booking_ids, $paymentInfo, $paymentBy, $paymentRequest)
    {
        $PayableAmount = isset($paymentInfo['PaidAmount']) ? $paymentInfo['PaidAmount'] : 0;
        if ($paymentBy == "Wallet") {
            $PaymentModel = new PaymentModel();
            $PayableAmount = 0;
            $booking_data = $PaymentModel->get_flight_booking_detail("flight", $booking_ids, $this->web_partner_id, "");
            foreach ($booking_data as $bookingInfo) {
                $PayableAmount = $PayableAmount + $bookingInfo['total_price'];
               
                $extra_param = array();
                $customer_fare_break_up = json_decode($bookingInfo['customer_fare_break_up'], true);
                $extra_param['booking_ref_number'] = $bookingInfo['booking_ref_number'];
                $extra_param['customerBreakUpInfo'] = $customer_fare_break_up;
                $extra_param['webPartnerBreakUpInfo'] = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                $extra_param['convenienceFee'] = isset($customer_fare_break_up['convenienceFee']) ? $customer_fare_break_up['convenienceFee'] : 0;

                $booking_currency = isset($bookingInfo['booking_currency']) ? $bookingInfo['booking_currency'] : "INR";
                $currency_rate =  isset($bookingInfo['currency_rate']) ? $bookingInfo['currency_rate'] : 1;
                $default_currency =  isset($bookingInfo['default_currency']) ? $bookingInfo['default_currency'] : 'INR';
                $Currencyinfor['booking_currency'] =  $booking_currency;
                $Currencyinfor['default_currency'] =  $default_currency;
                $Currencyinfor['currency_rate'] = $currency_rate;
                $Currencyinfor['currency_symbol'] = getBookingCurrencyIcon($booking_currency);

                $first_name = $bookingInfo['first_name'];
                $last_name = $bookingInfo['last_name'];
                $PayableAmount = $bookingInfo['total_price'];


               /*  $convertBookingCurrencyRate = convertBookingCurrencyRate($PayableAmount, $booking_currency, $default_currency, $currency_rate);
                $PayableAmount = $convertBookingCurrencyRate['ConvertPrice'];
                $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol']; */
               
                $booking_id = $bookingInfo['id'];
                $service = "flight";
                $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $bookingInfo['origin'] . '-' . $bookingInfo['destination'] . '/ JourneyType' . $bookingInfo['journey_type'], 'TravelDate' => $bookingInfo['departure_date']));
                $customerWalletAmountStatus = Payment::flight_customer_wallet($PayableAmount, $booking_id, $service, $service_log, $extra_param, $Currencyinfor);
                if ($customerWalletAmountStatus['StatusCode'] != 0) {
                    return redirect()->to($customerWalletAmountStatus['Url']);
                }
            }
        }
        $booking_id = implode(",", $booking_ids);
        $return_url = site_url('flight/payment-status/');
        $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id, 'PayableAmount' => $PayableAmount)));
        return redirect()->to($return_url);
    }

    protected function wallet($PayableAmount, $booking_id, $service, $service_log, $paymentBy, $payment_request)
    {
        $walletbalance = get_balance_wl_customer();
        $PaymentModel = new PaymentModel();
        $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
        $booking_ref_number = isset($booking_data['booking_ref_number']) ? $booking_data['booking_ref_number'] : $booking_id;
        $extra_param = array();
        $customer_fare_break_up = json_decode($booking_data['customer_fare_break_up'], true);
        $extra_param['booking_ref_number'] = $booking_data['booking_ref_number'];
        $extra_param['customerBreakUpInfo'] = $customer_fare_break_up;
        if (isset($booking_data['web_partner_fare_break_up'])) {
            $extra_param['webPartnerBreakUpInfo'] = json_decode($booking_data['web_partner_fare_break_up'], true);
        }
        $extra_param['convenienceFee'] = isset($payment_request['convenience_fee']) ? $payment_request['convenience_fee'] : 0;
        if ($walletbalance >= $PayableAmount) {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('customer_account_log', ['booking_ref_no' => $booking_id, 'service' => $service, 'transaction_type' => 'debit']);
            if (empty($paymentrecord)) {

                $CurrencySymbol = getBookingCurrencyIcon($booking_data['booking_currency']);

                $balance = $walletbalance - $PayableAmount;
                $customer_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'customer_id' => $this->wl_customer_id,
                    'debit' => $PayableAmount,
                    'balance' => $balance,
                    'convertion_rate' => $booking_data['currency_rate'],
                    'currency' => $booking_data['booking_currency'],
                    'currency_symbol' => $CurrencySymbol,
                    'remark' => 'Ticket Created Through Portal',
                    'service' => $service,
                    'service_log' => $service_log,
                    'transaction_type' => 'debit',
                    'booking_ref_no' => $booking_id,
                    'extra_param' => json_encode($extra_param),
                    'action_type' => 'booking',
                    'created' => create_date()
                );

                $account_log_lastid = $PaymentModel->insertData('customer_account_log', $customer_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }

                $acc_ref_number = reference_number($account_log_lastid);
                $account_update_data = array('acc_ref_number' => $acc_ref_number);
                $PaymentModel->updateData('customer_account_log', ['id' => $account_log_lastid], $account_update_data);
                $conveniencefee = $payment_request['convenience_fee'];
                $wl_extra_info = array();
                $wl_extra_info['paymentStatus'] = $payment_status;
                $wl_extra_info['paymentGatewayType'] = $this->payment_gateway_type;
                $wl_extra_info['paymentGatewayName'] = $this->payment_gateway_name;
                $updatepaymentdata = array('payment_mode' => 'Wallet', "conveniencefee" => $conveniencefee, "wl_customer_id" => $this->wl_customer_id, "payment_status" => $payment_status, "wl_extra_info" => json_encode($wl_extra_info));
                if ($service == 'bus') {
                    $PaymentModel->updateData('bus_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('bus/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }
                if ($service == 'hotel') {
                    $PaymentModel->updateData('hotel_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('hotel/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }

                if ($service == 'visa') {
                    $PaymentModel->updateData('visa_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('visa/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }

                if ($service == 'car') {
                    $PaymentModel->updateData('car_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('car/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }
                if ($service == 'tourguide') {
                    $PaymentModel->updateData('tourguide_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('tourguide/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }
                if ($service == 'activities') {
                    $PaymentModel->updateData('activities_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('activities/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }

                if ($service == 'holiday') {
                    $PaymentModel->updateData('holiday_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('holiday/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }

                if ($service == 'cruise') {
                    $PaymentModel->updateData('cruise_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('cruise/payment-status/');
                    $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                }


                if ($payment_status == 'Failed') {
                    $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $booking_ref_number));
                    $return_url = site_url('payment/payment-error?' . $url_param);
                }
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_ref_number));
                $return_url = site_url('payment/payment-error?' . $url_param);
            }
        } else {
            $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $booking_ref_number));
            $return_url = site_url('payment/payment-error?' . $url_param);
        }
        return redirect()->to($return_url);
    }
    protected function flight_customer_wallet($PayableAmount, $booking_id, $service, $service_log, $extra_param, $Currencyinfor)
    {
        $walletbalance = get_balance_wl_customer(); 
        if ($walletbalance >= $PayableAmount) {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('customer_account_log', ['booking_ref_no' => $booking_id, 'service' => $service, 'transaction_type' => 'debit']);
            if (empty($paymentrecord)) {
                $balance = $walletbalance - $PayableAmount;
                $customer_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'customer_id' => $this->wl_customer_id,
                    'debit' => $PayableAmount,
                    'balance' => $balance,
                    'convertion_rate' => $Currencyinfor['currency_rate'],
                    'currency' => $Currencyinfor['booking_currency'],
                    'currency_symbol' => $Currencyinfor['currency_symbol'],
                    'remark' => 'Ticket Created Through Portal',
                    'service' => $service,
                    'service_log' => $service_log,
                    'transaction_type' => 'debit',
                    'booking_ref_no' => $booking_id,
                    'extra_param' => json_encode($extra_param),
                    'action_type' => 'booking',
                    'created' => create_date()
                );

                $account_log_lastid = $PaymentModel->insertData('customer_account_log', $customer_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }
                $acc_ref_number = reference_number($account_log_lastid);
                $account_update_data = array('acc_ref_number' => $acc_ref_number);
                $PaymentModel->updateData('customer_account_log', ['id' => $account_log_lastid], $account_update_data);
                $updatepaymentdata = array('payment_mode' => 'Wallet', 'payment_status' => $payment_status);
                $PaymentModel->updateData('flight_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                $url = "";
                if ($payment_status == 'Failed') {
                    $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $extra_param['booking_ref_number']));
                    $url = site_url('payment/payment-error?' . $url_param);
                    return array("StatusCode" => 1, "Url" => $url);
                }
                return array("StatusCode" => 0, "Url" => $url);
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $extra_param['booking_ref_number']));
                $url = site_url('payment/payment-error?' . $url_param);
                return array("StatusCode" => 1, "Url" => $url);
            }
        } else {
            $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $extra_param['booking_ref_number']));
            $url = site_url('payment/payment-error?' . $url_param);
            return array("StatusCode" => 1, "Url" => $url);
        }
    }

    public function response()
    {
        $method = strtoupper($this->request->getMethod());
        if ($method == 'POST' || $method == 'GET') {
            $response = $this->request->getPost();
            if ($method == 'GET') {
                $response = $this->request->getGet();

                if (empty($response['developer-verify']) || dev_decode($response['link_id']) !== $response['developer-verify']) {
                    $url_param = http_build_query([
                        'message' => 'Payment failed',
                        'reference-no' => isset($response['link_id']) ? dev_decode($response['link_id']) : 'N/A', // Ensure 'link_id' exists
                    ]);

                    return redirect()->to(site_url('payment/payment-error?' . $url_param));
                }

                $response['link_id'] = dev_decode($response['link_id']);

                if (isset($response['paymentId'], $response['token'], $response['PayerID'])) {
                    $paymentId = $response['paymentId'];
                    $token = $response['token'];
                    $PayerID = $response['PayerID'];
                    $PAYPAL = new Paypal();
                    $paymentVerified = $PAYPAL->verifyPayment($paymentId, $token, $PayerID);

                    $isResponseSuccess = (isset($paymentVerified['Error']['ErrorCode']) && $paymentVerified['Error']['ErrorCode'] == "0");
                    if ($isResponseSuccess && isset($paymentVerified['Result']['InvoiceNumber']) && !empty($paymentVerified['Result']['InvoiceNumber'])) {

                        $response['PaypalInvoiceNumber'] = $paymentVerified['Result']['InvoiceNumber'];
                        $response['Paypalpaymentresponse'] = json_decode($paymentVerified['Result']['getPaymentDetails']);
                    } else {
                        $Errormessage = $paymentVerified['Error']['ErrorMessage'];
                        return view('template/custom-error-layout', ['error_message' => $Errormessage]);
                    }
                }
            }


           /*  pr($response);exit; */

            $orderNumber = "";
            if (isset($response['orderNo'])) {
                $orderNumber = $response['orderNo'];
            } else if (isset($response['txnid'])) {
                $orderNumber = $response['txnid'];
            } else if (isset($response['transactionId'])) {
                $orderNumber = $response['transactionId'];
            } else if (isset($response['merchant_reference'])) {
                $orderNumber = $response['merchant_reference'];
            } else if (isset($response['orderId'])) {
                $orderNumber = $response['orderId'];
            } else if (isset($response['razorpay_order_id'])) {
                $orderNumber = $response['razorpay_order_id'];
            } else if (isset($response['link_id'])) {
                $orderNumber = $response['link_id'];
            } else if (isset($response['merchant_reference'])) {
                $orderNumber = $response['merchant_reference'];
            } else if (isset($response['PaypalInvoiceNumber'])) {
                $orderNumber = $response['PaypalInvoiceNumber'];
            }

          

            $PaymentModel = new PaymentModel();
            $payment_request = $PaymentModel->get_whitelabel_payment_detail($orderNumber, $this->web_partner_id);
         
            $this->wl_customer_id = (!empty($payment_request['wl_customer_id'])) ? $payment_request['wl_customer_id'] : '';

            if (trim($payment_request['payment_getway_name']) == "CASHFREE") {
                $WhiteLableGateway = new CASHFREE();
            } else if (trim($payment_request['payment_getway_name']) == "RAZORPAY") {
                $WhiteLableGateway = new RAZORPAY();
            } else if (trim($payment_request['payment_getway_name']) == "PHONEPE") {
                $WhiteLableGateway = new PHONEPE();
            } else if (trim($payment_request['payment_getway_name']) == "CCAVENUE") {
                $WhiteLableGateway = new CCAVENUE();
            } else if (trim($payment_request['payment_getway_name']) == "ICICI") {
                $WhiteLableGateway = new ICICI();
            } else if (trim($payment_request['payment_getway_name']) == "HDFC") {
                $WhiteLableGateway = new HDFC();
            } else if (trim($payment_request['payment_getway_name']) == "EASEBUZZ") {
                $WhiteLableGateway = new PaymentEaseBuzz();
            } else if (trim($payment_request['payment_getway_name']) == "PAYU") {
                $WhiteLableGateway = new PAYU();
            } else if (trim($payment_request['payment_getway_name']) == "AMAZONPAY") {
                $WhiteLableGateway = new Amazon();
            } else if (trim($payment_request['payment_getway_name']) == "PAYPAL") {
                $WhiteLableGateway = new Paypal();
            }
            $payment_response = $WhiteLableGateway->response($response); 
            $payment_request = $PaymentModel->get_whitelabel_payment_detail($orderNumber, $this->web_partner_id);

            if (isset($payment_response['codeError']) && $payment_response['codeError']) {
                return redirect()->to(site_url('flight?link_id=' . $payment_response['codeError']));
            }

            if (isset($payment_response['ErrorCode']) && $payment_response['ErrorCode'] == 4001) {
                $errorMessage = $payment_response['ErrorMessage'] ?? 'Unknown error';
                $urlParam = http_build_query([
                    'message' => $errorMessage,
                    'reference-no' => $payment_response['order_id']
                ]);
                return redirect()->to(site_url('payment/payment-error?' . $urlParam));
            }


            $booking_id = $payment_request['booking_ref_no'];
            $bookingIdrefrenceNumber = $payment_request['booking_prefix'] . $booking_id;
            $service = $payment_request['service'];
            $service_log = $payment_request['service_log'];
            $customerId = $this->wl_customer_id;

            if ($service == "flight") {
                $flightbookingIdrefrenceNumbers = explode(",", $booking_id);
                $flightBookingrefrennumberArray = array();
                if (!is_array($flightbookingIdrefrenceNumbers)) {
                    $flightbookingIdrefrenceNumbers = array($flightbookingIdrefrenceNumbers);
                }
                if ($flightbookingIdrefrenceNumbers) {
                    foreach ($flightbookingIdrefrenceNumbers as $bookingrefrenceNumber) {

                        $flightBookingrefrennumberArray[] = $payment_request['booking_prefix'] . $bookingrefrenceNumber;
                    }
                    $bookingIdrefrenceNumber = implode(",", $flightBookingrefrennumberArray);
                }
            }
            if ($payment_response['payment_status'] == 'Successful') {
                if (!isset(session()->get('wl_customer')['id'])) {
                    $customerId = Payment::CustomerLogin($payment_request);
                    $this->wl_customer_id = $customerId;
                } 

                $amount = $payment_request['amount'] - $payment_request['convenience_fee'];  
                $actually_amounts = $payment_request['actually_amounts'] - $payment_request['convenience_fee'];

                if ($service == "flight") {
                    $booking_data = $PaymentModel->get_flight_booking_detail("flight", $flightbookingIdrefrenceNumbers, $this->web_partner_id, "");
                    $getTotalPriceChecked = 0;
                    foreach ($booking_data as $bookingInfo) {
                        $getTotalPriceChecked += abs((float) $bookingInfo['total_price']);
                    }
                } else {
                    $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
                    $getTotalPriceChecked = abs((float) $booking_data['total_price']);
                }


                if (!empty($getTotalPriceChecked) && $getTotalPriceChecked < 0 && $getTotalPriceChecked < $actually_amounts) {
                    $url_param = http_build_query(array('message' => 'The price does not match. Please try again. Payment failed.', 'reference-no' => $bookingIdrefrenceNumber));
                    return redirect()->to(site_url('payment/payment-error?' . $url_param));
                }
 
                if ($this->payment_gateway_type == "superadmin" && $this->api_owner_type == "superadmin") {
                    $walletbalance = get_balance();
                    $balance = $walletbalance + $actually_amounts;
                    $web_partner_account_log = array(
                        'web_partner_id' => $this->web_partner_id,
                        'credit' => $actually_amounts,
                        'balance' => $balance,
                        'remark' => 'Online Booking Topup For Customer',
                        'transaction_id' => $payment_response['order_id'],
                        'payment_transaction_id' => $payment_request['id'],
                        'payment_mode' => $payment_request['payment_mode'],
                        'transaction_type' => 'credit',
                        'action_type' => 'recharge',
                        'service' => $payment_request['service'],
                        'booking_ref_no' => $payment_request['booking_ref_no'],
                        'extra_param' => json_encode(array("booking_ref_number" => $bookingIdrefrenceNumber, "convenienceFee" => $payment_request['convenience_fee'])),
                        'created' => create_date()
                    );
                    $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);
                }
                $customerwalletbalance = get_balance_wl_customer();
                $customerbalance = $customerwalletbalance + $actually_amounts;

                $customer_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'customer_id' => $customerId,
                    'credit' => $actually_amounts,
                    'balance' => $customerbalance,
                    'remark' => 'Online Booking Topup',
                    'transaction_id' => $payment_response['order_id'],
                    'payment_transaction_id' => $payment_request['id'],
                    'payment_mode' => $payment_request['payment_mode'],
                    'transaction_type' => 'credit',
                    'convertion_rate' => $payment_request['conversion_rate'],
                    'currency' => $payment_request['selected_currency'],
                    'currency_symbol' => $payment_request['currency_symbol'],
                    'service' => $payment_request['service'],
                    'booking_ref_no' => $payment_request['booking_ref_no'],
                    'extra_param' => json_encode(array("booking_ref_number" => $bookingIdrefrenceNumber, "convenienceFee" => $payment_request['convenience_fee'])),
                    'action_type' => 'recharge',
                    'created' => create_date()
                );

                $PaymentModel->insertData('customer_account_log', $customer_account_log);
                $paymentInfo = array("PaidAmount" => $payment_response['amount'], "convenience_fee" => $payment_request['convenience_fee'], "payment_status" => $payment_request['payment_status']);
                if ($service == "flight") {
                    $booking_data = $PaymentModel->get_flight_booking_detail("flight", $flightbookingIdrefrenceNumbers, $this->web_partner_id, "");
                    $NumberofBooking = count($flightbookingIdrefrenceNumbers);
                    foreach ($booking_data as $bookingInfo) {
                        $wl_extra_info = array();
                        $applyconvenience_fee = round_value(($payment_request['convenience_fee'] / $NumberofBooking));
                        $paymentStatus = $payment_request['payment_status'];
                        $webPartnerFareBreakup = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                        $customer_fare_break_up = json_decode($bookingInfo['customer_fare_break_up'], true);
                        $webPartnerFareBreakup['convenienceFee'] = $applyconvenience_fee;
                        $customer_fare_break_up['convenienceFee'] = $applyconvenience_fee;
                        $wl_extra_info['paymentGatewayType'] = $this->payment_gateway_type;
                        $wl_extra_info['paymentGatewayName'] = $this->payment_gateway_name;
                        /* $total_price  =   $bookingInfo['total_price']+$applyconvenience_fee; */
                        $updatepaymentdata = array('payment_mode' => 'Online', "web_partner_fare_break_up" => json_encode($webPartnerFareBreakup), "customer_fare_break_up" => json_encode($customer_fare_break_up), "wl_extra_info" => json_encode($wl_extra_info), 'wl_customer_id' => $customerId);
                        $PaymentModel->updateData('flight_booking_list', ['id' => $bookingInfo['id'], 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                    return Payment::FlightWallet($flightbookingIdrefrenceNumbers, $paymentInfo, "Wallet", $payment_request);
                } else {
                    return Payment::wallet($amount, $booking_id, $service, $service_log, "Wallet", $payment_request);
                }
            } else {
                if ($service == "flight") {
                    $booking_data = $PaymentModel->get_flight_booking_detail("flight", $flightbookingIdrefrenceNumbers, $this->web_partner_id, "");
                    $NumberofBooking = count($flightbookingIdrefrenceNumbers);
                    foreach ($booking_data as $bookingInfo) {
                        $wl_extra_info = array();
                        $applyconvenience_fee = round_value(($payment_request['convenience_fee'] / $NumberofBooking));
                        $paymentStatus = $payment_request['payment_status'];
                        $webPartnerFareBreakup = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                        $customer_fare_break_up = json_decode($bookingInfo['customer_fare_break_up'], true);
                        $webPartnerFareBreakup['convenienceFee'] = $applyconvenience_fee;
                        $customer_fare_break_up['convenienceFee'] = $applyconvenience_fee;
                        $wl_extra_info['paymentGatewayType'] = $this->payment_gateway_type;
                        $wl_extra_info['paymentGatewayName'] = $this->payment_gateway_name;
                        $updatepaymentdata = array('payment_mode' => 'Online', "payment_status" => $paymentStatus, "web_partner_fare_break_up" => json_encode($webPartnerFareBreakup), "customer_fare_break_up" => json_encode($customer_fare_break_up), "wl_extra_info" => json_encode($wl_extra_info), "customer_fare_break_up" => json_encode($customer_fare_break_up));
                        $PaymentModel->updateData('flight_booking_list', ['id' => $bookingInfo['id'], 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                } else {
                    $paymentStatus = $payment_request['payment_status'];
                    $wl_extra_info = array();
                    $wl_extra_info['paymentGatewayType'] = $this->payment_gateway_type;
                    $wl_extra_info['paymentGatewayName'] = $this->payment_gateway_name;
                    $conveniencefee = $payment_request['convenience_fee'];
                    $updatepaymentdata = array('payment_mode' => 'Online', "conveniencefee" => $conveniencefee, "payment_status" => $paymentStatus, "wl_extra_info" => json_encode($wl_extra_info));
                    if ($service == 'bus') {
                        $PaymentModel->updateData('bus_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                    if ($service == 'hotel') {
                        $PaymentModel->updateData('hotel_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }

                    if ($service == 'visa') {
                        $PaymentModel->updateData('visa_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }

                    if ($service == 'car') {
                        $PaymentModel->updateData('car_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }

                    if ($service == 'holiday') {
                        $PaymentModel->updateData('holiday_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }

                    if ($service == 'cruise') {
                        $PaymentModel->updateData('cruise_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                    if ($service == 'activities') {
                        $PaymentModel->updateData('activities_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                }

                $url_param = http_build_query(array('message' => 'Payment is failed', 'reference-no' => $bookingIdrefrenceNumber));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        }
    }


    public function notifyurl() {}
    public function callbackUrl() {}


    public function payment_error()
    {
        $message = $this->request->getVar('message');
        $reference_no = $this->request->getVar('reference-no');
        $data = [
            'title' => 'Payment Error',
            'message' => $message,
            'reference_no' => $reference_no,
            'view' => "Payment\Views\payment-error",
        ];
        return view('template/default-layout', $data);
    }
    public function CustomerLogin($payment_request)
    {
        $PaymentModel = new PaymentModel();
        $email = $payment_request['email_id'];
        $phone = $payment_request['mobile_number'];
        $customerData = $PaymentModel->checkCustomer($email, $phone, $this->web_partner_id, $this->web_partner_details, $this->wl_customer_id);
        $customerId = $customerData['customerId'];
        $user = $customerData['customerData'];
        $this->session->set('wl_customer', $user);
        return $customerId;
    }




    /* Payment Mode Function Starts */
    public function payment_mode($service, $amount)
    {
        $service = ucfirst($service);
        $PaymentModel = new PaymentModel();
        $paymentGatewayList = $PaymentModel->PaymentGateways($this->web_partner_id, $this->payment_gateway_type, $this->payment_gateway_name);

        $GatewayRemarks = array_column($paymentGatewayList, "remarks", "payment_gateway");
        $paymentGatewayList = array_column($paymentGatewayList, "payment_mode", "payment_gateway");

        $ConvenienceFeeList = $PaymentModel->ConvenienceFee($this->web_partner_id, $this->payment_gateway_type, $this->payment_gateway_name, $service, $amount);
        $result = [];
        $CFEE = [];

        if (isset($ConvenienceFeeList) && !empty($ConvenienceFeeList)) {
            $gatewayKeys = array_column($ConvenienceFeeList, 'payment_gateway');
            $missingKeys = array_values(array_diff(array_keys($paymentGatewayList), $gatewayKeys));
            $CFEE = $ConvenienceFeeList;

            foreach ($paymentGatewayList as $key => $paymentMode) {

                if (in_array($key, $missingKeys)) {
                    $modes = explode(',', $paymentMode);
                    $Structure = [
                        'payment_gateway' => $key,
                    ];
                    foreach ($modes as $key => $val) {
                        $Structure[$val . '_value'] = 0;
                        $Structure[$val . '_type'] = 'fixed';
                    }
                    $CFEE[] = $Structure;
                }
            }
        } else {
            foreach ($paymentGatewayList as $key => $paymentMode) {
                $modes = explode(',', $paymentMode);
                $Structure = [
                    'payment_gateway' => $key,
                ];

                foreach ($modes as $val) {
                    $Structure[$val . '_value'] = 0;
                    $Structure[$val . '_type'] = 'fixed';
                }
                $CFEE[] = $Structure;
            }
        }

        $DBCRDMode = array();
        $CRCRDMode = array();
        $NBKMode = array();
        $MOBPMode = array();
        $UPIMode = array();

        if ($CFEE) {
            foreach ($CFEE as $key => $paymentMode) {
                $PaymentActivateModes = array();

                if (isset($paymentGatewayList[$paymentMode['payment_gateway']])) {
                    $PaymentActivateModes = explode(",", $paymentGatewayList[$paymentMode['payment_gateway']]);
                }

                if (!empty($PaymentActivateModes) && in_array('debit_card', $PaymentActivateModes)) {
                    $DBCRDMode[$key] = array("Gateway" => $paymentMode['payment_gateway'], "ValueType" => $paymentMode['debit_card_type'], "Value" => $paymentMode['debit_card_value'], "Remark" => $GatewayRemarks[$paymentMode['payment_gateway']]);
                }

                if (!empty($PaymentActivateModes)) {
                    foreach ($PaymentActivateModes as $mode) {
                        if (strpos($mode, 'credit_card') !== false && isset($paymentMode[$mode . '_type'])) {
                            $CRCRDMode[$key]['Gateway'] = $paymentMode['payment_gateway'];
                            $CRCRDMode[$key]['Type'][$mode] = [
                                "ValueType" => $paymentMode[$mode . '_type'],
                                "Value" => $paymentMode[$mode . '_value'],
                                "Remark" => $GatewayRemarks[$paymentMode['payment_gateway']]
                            ];
                        }
                    }
                }


                if (!empty($PaymentActivateModes) && in_array('net_banking', $PaymentActivateModes)) {
                    $NBKMode[$key] = array("Gateway" => $paymentMode['payment_gateway'], "ValueType" => $paymentMode['net_banking_type'], "Value" => $paymentMode['net_banking_value'], "Remark" => $GatewayRemarks[$paymentMode['payment_gateway']]);
                }


                if (!empty($PaymentActivateModes) && in_array('mobile_wallet', $PaymentActivateModes)) {
                    $MOBPMode[$key] = array("Gateway" => $paymentMode['payment_gateway'], "ValueType" => $paymentMode['mobile_wallet_type'], "Value" => $paymentMode['mobile_wallet_value'], "Remark" => $GatewayRemarks[$paymentMode['payment_gateway']]);
                }

                if (!empty($PaymentActivateModes) && in_array('upi', $PaymentActivateModes)) {
                    $UPIMode[$key] = array("Gateway" => $paymentMode['payment_gateway'], "ValueType" => $paymentMode['upi_type'], "Value" => $paymentMode['upi_value'], "Remark" => $GatewayRemarks[$paymentMode['payment_gateway']]);
                }
            }


            $result['DBCRD'] = array("Mode" => "DBCRD", "Label" => "Debit Card", "SubModes" => array_values($DBCRDMode));
            $result['CRDC'] = array("Mode" => "CRDC", "Label" => "Credit Card", "SubModes" => array_values($CRCRDMode));
            $result['NBK'] = array("Mode" => "NBK", "Label" => "NetBanking", "SubModes" => array_values($NBKMode));
            $result['MOBP'] = array("Mode" => "MOBP", "Label" => "Mobile Wallet", "SubModes" => array_values($MOBPMode));
            $result['UPI'] = array("Mode" => "UPI", "Label" => "UPI", "SubModes" => array_values($UPIMode));
        }


        return $result;
    }
    /* Payment Mode Function Ends */
}

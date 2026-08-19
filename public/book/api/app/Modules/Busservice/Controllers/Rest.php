<?php

namespace Modules\Busservice\Controllers;

use App\Controllers\BaseController;
use App\Modules\Busservice\Models\RestModel;
use Modules\Busservice\Config\Validation;
use CodeIgniter\I18n\Time;

class Rest extends BaseController
{

    public function __construct()
    {
        helper('Modules\Busservice\Helpers\rest');

        $RestModel = new RestModel();
        if ($RestModel->get_api_supplier('TBO')) {
            $this->busmodules = new \Modules\TBOBus\Controllers\TBOBus();
        } else {
            $message = api_validation_message('supplier_inactive_error');
            api_custom_message(400, $message, false);
        }
    }

    public function Search()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->search_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            // Genrate TTS Search Token 
            $tts_search_token = generate_token();
            $response = $this->busmodules->Search($input, $tts_search_token, $this->userauthdata,$this->Btype);

            // Insert TTS Logs
            $RestModel = new RestModel();
            $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $tts_search_token, $response['input'], $response['Response'], 'search', null, $response['CustomIndex']);

            return $this->response->setContentType('application/json')->setJSON($response['Response']);
        }
    }

    public function SeatLayOut()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->seatlayout_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true)['CommonData'];
                $response = $this->busmodules->GetBusSeatLayOut($input, $common_data, $this->userauthdata,$this->Btype);

                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'seatlayout', $input['ResultIndex'], $response['CustomIndex']);

                return $this->response->setContentType('application/json')->setJSON($response['Response']);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function BoardingPoint()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->boardingpoint_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'seatlayout', 'selected_index' => $input['ResultIndex']]);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true)['CommonData'];
                $response = $this->busmodules->GetBoardingPointDetails($input, $common_data, $this->userauthdata);

                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'boardingpoint', $input['ResultIndex'], null);

                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function BlockSeat()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->blockseat_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'seatlayout', 'selected_index' => $input['ResultIndex']]);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true)['CommonData'];
                $response = $this->busmodules->Block($input, $common_data, $this->userauthdata,$this->Btype);
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'blockseat', $input['ResultIndex'], $response['CustomIndex']);

                return $this->response->setContentType('application/json')->setJSON($response['Response']);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function Book()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->book_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'blockseat', 'selected_index' => $input['ResultIndex']]);
            if ($verify_data) {
                /*-- Check Search Token Id is Valid for Single Request Book Method other wise Token will be expire ---  */
                $check_book_request = $RestModel->book_record_exists(['tts_search_token' => $input['SearchTokenId'], 'service' => 'book']);
                if (empty($check_book_request)) {
                    $BookingDetail = $RestModel->getbookingDetailByToken($input['SearchTokenId'], "id,payment_status");
                    $common_data = json_decode($verify_data['tts_index_response'], true)['CommonData'];
                    if (isset($BookingDetail['id'])) {
                        if (isset($BookingDetail['payment_status']) && $BookingDetail['payment_status'] == "Successful") {
                            $acc_ref_number_data = $RestModel->get_acc_ref_number($BookingDetail['id'], $this->userauthdata['web_partner_id']);
                            $acc_ref_number = isset($acc_ref_number_data['acc_ref_number']) ? $acc_ref_number_data['acc_ref_number'] : '';
                            $saveInfo = array("booking_ref_no" => $BookingDetail['id'], "acc_ref_number" => $acc_ref_number, "payment_status" => $BookingDetail['payment_status']);
                            $response = $this->busmodules->Book($input, $common_data, $this->userauthdata, $saveInfo);
                        } else {
                            $message = "Payment Not Done.";
                            return api_custom_message(108, $message);
                        }
                    } else {
                        $Auth_User_Balance = $RestModel->get_auth_user_account_balance($this->userauthdata['web_partner_id']);
                        if (check_web_partner_balance($Auth_User_Balance, $common_data['TTS_Invoice_Amount'])) {
                            $saveInfo = Rest::saveData($input, $common_data, $this->userauthdata);
                            $response = $this->busmodules->Book($input, $common_data, $this->userauthdata, $saveInfo);
                        }
                    }
                    // Insert TTS Logs
                    $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'book', $input['ResultIndex'], $response['CustomIndex']);
                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('expire_token_error');
                    return api_custom_message(5, $message, false);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    function saveData(array $input, $common_data, array $userauthdata)
    {
        $SelectedIndex = $common_data['SelectedIndex'];
        if ($SelectedIndex['IsDropPointMandatory']) {
            $message = "DroppingPointId field is Required";
            api_custom_message(400, $message, false);
        }
        $Passenger_array = array();
        if ($input['Passenger']) {
            foreach ($input['Passenger'] as $key => $Passenger) {
                if (isset($Passenger['LeadPassenger'])) {
                    if ($key == 0) {
                        if ($Passenger['LeadPassenger'] != true) {
                            $message = "First Passenger is a lead passenger. lead passenger value is true.";
                            api_custom_message(400, $message, false);
                        }
                    } else {
                        if ($Passenger['LeadPassenger'] != false) {
                            $message = "Second passenger is not a lead passenger.";
                            api_custom_message(400, $message, false);
                        }
                    }
                } else {
                    $message = "LeadPassenger field is Required";
                    api_custom_message(400, $message, false);
                }
                if (array_key_exists($Passenger['SeatName'], $common_data['SupplierSeatLayout'])) {
                    if ($SelectedIndex['IdProofRequired'] && empty($Passenger['IdType']) && empty($Passenger['IdNumber'])) {
                        $message = "IdType and IdNumber field is Required";
                        api_custom_message(400, $message, false);
                    }
                    $Passenger['Seat'] = $common_data['SupplierSeatLayout'][$Passenger['SeatName']];
                    unset($Passenger['SeatName']);
                    $Passenger_array[] = $Passenger;
                } else {
                    $message = "Invalid SeatName in Request";
                    api_custom_message(400, $message, false);
                }
            }
        }
        $tts_search_token = $input['SearchTokenId'];
        $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
        /*--------- Start For Payment Logs and Insert Data----------------*/
        $RestModel = new RestModel();
        /*--------- Start For Payment Logs and Insert Data----------------*/

        $BlockResult = $RestModel->get_block_data($tts_search_token, $input['ResultIndex']);
        if ($BlockResult) {
            $block_result_array = json_decode($BlockResult['response'], true);
            if ($block_result_array && $block_result_array['Error']['ErrorCode'] == 0) {
                /*--------------------------------Start Debit Webpartner Balance --------------------------------------*/

                $Auth_User_Balance = $RestModel->get_auth_user_account_balance($userauthdata['web_partner_id']);
                $balance = $Auth_User_Balance['balance'] - $TTS_Invoice_Amount;
                $web_partner_account_log = array(
                    'web_partner_id' => $userauthdata['web_partner_id'],
                    'debit' => $TTS_Invoice_Amount,
                    'balance' => $balance,
                    'remark' => 'Ticket Created Through API',
                    'service' => 'bus',
                    'transaction_type' => 'debit',
                    'action_type' => 'booking',
                    'created' => create_date()
                );
                $account_log_lastid = $RestModel->insertData('web_partner_account_log', $web_partner_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }
                $acc_ref_number = reference_number($account_log_lastid);
                /*-------------------------------End Debit Webpartner Balance --------------------------------------*/

                /*--------------------------------Start Save Booking Data  and Pax Data ------------------------------------------*/

                $search_request = $RestModel->get_search_request($tts_search_token);
                $search_request_array = json_decode($search_request['request'], true);

                $origin_city = $search_request_array['Origin'];
                $destination_city = $search_request_array['Destination'];


                $boarding_dropping_points = $RestModel->get_boarding_dropping_points($tts_search_token);
                $boarding_dropping_points_array = json_decode($boarding_dropping_points['response'], true);

                $dropping_points = array();
                if ($boarding_dropping_points_array['Result']['DroppingPointsDetails']) {
                    foreach ($boarding_dropping_points_array['Result']['DroppingPointsDetails'] as $DroppingPoints) {
                        if ($input['DroppingPointId'] == $DroppingPoints['CityPointIndex']) {
                            $dropping_points = $DroppingPoints;
                        }
                    }
                }

                $block_response = $block_result_array['Result'];
                $save_book_data = array(
                    'tts_search_token' => $tts_search_token,
                    'web_partner_id' => $userauthdata['web_partner_id'],
                    'origin_city' => $origin_city,
                    'origin_id' => $search_request_array['OriginId'],
                    'destination_city' => $destination_city,
                    'destination_id' => $search_request_array['DestinationId'],
                    'date_of_journey' => $search_request_array['DateOfJourney'],
                    'bus_name' => $block_response['TravelName'],
                    'bus_type' => $block_response['BusType'],
                    'departure_time' => $block_response['DepartureTime'],
                    'arrival_time' => $block_response['ArrivalTime'],
                    'no_of_seats' => count($block_response['Passenger']),
                    'boarding_points' => json_encode($block_response['BoardingPointdetails']),
                    'dropping_points' => json_encode($dropping_points),
                    'cancellation_policies' => json_encode($block_response['CancelPolicy']),
                    'api_supplier' => $common_data['Supplier'],
                    'payment_mode' => 'API_Wallet',
                    'payment_status' => $payment_status,
                    'booking_status' => 'Processing',
                    'total_price' => $TTS_Invoice_Amount,
                    'created' => create_date()
                );

                $booking_lastid = $RestModel->insertData('bus_booking_list', $save_book_data);
                if ($input['Passenger']) {
                    foreach ($input['Passenger'] as $key => $Passenger) {
                        $paxwise_seat = $block_response['Passenger'][$key]['Seat'];
                        $seat_info = array(
                            'IsLadiesSeat' => $paxwise_seat['IsLadiesSeat'],
                            'IsMalesSeat' => $paxwise_seat['IsMalesSeat'],
                            'IsUpper' => $paxwise_seat['IsUpper'],
                            'SeatFare' => $paxwise_seat['SeatFare'],
                            'SeatName' => $paxwise_seat['SeatName'],
                            'SeatStatus' => $paxwise_seat['SeatStatus'],
                            'SeatType' => $paxwise_seat['SeatType'],
                            'Price' => $paxwise_seat['Price']
                        );

                        $save_pax_data[$key] = array(
                            'bus_booking_id' => $booking_lastid,
                            'title' => $Passenger['Title'],
                            'first_name' => $Passenger['FirstName'],
                            'last_name' => $Passenger['LastName'],
                            'age' => $Passenger['Age'],
                            'email_id' => $Passenger['Email'],
                            'mobile_number' => $Passenger['Phoneno'],
                            'lead_pax' => $Passenger['LeadPassenger'],
                            'gendar' => Get_Gender($Passenger['Gender']),
                            'id_type' => $Passenger['IdType'],
                            'id_number' => $Passenger['IdNumber'],
                            'address' => $Passenger['Address'],
                            'seat_name' => $Passenger['SeatName'],
                            'seat_id' => '',
                            'seat_info' => json_encode($seat_info)
                        );
                    }
                }
                $RestModel->insertBatchData('bus_booking_travelers', $save_pax_data);
                $PaxName = $input['Passenger'][0]['FirstName'] . ' ' . $input['Passenger'][0]['LastName'] . ' X ' . count($input['Passenger']);
                $Sector = $origin_city . '-' . $destination_city;
                $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $search_request_array['DateOfJourney']);


                $account_update_data = array(
                    'acc_ref_number' => $acc_ref_number,
                    'booking_ref_no' => $booking_lastid,
                    'service_log' => json_encode($service_log)
                );
                $RestModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
                return array("booking_ref_no" => $booking_lastid, "acc_ref_number" => $acc_ref_number, "payment_status" => $payment_status);

            } else {
                $message = $block_result_array['Error']['ErrorMessage'];
                api_custom_message(400, $message, false);
            }
        } else {
            $message = "Invalid ResultIndex";
            api_custom_message(400, $message, false);
        }
        /*--------- End For Payment Logs and Insert Data----------------*/
    }

    public function GetBookingDetail()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->getbookingdetail_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_booking_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $response = ConvertBookingDetail($input, $verify_data);
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'getbookingdetail');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function CancelRequest()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->cancel_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->get_book_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $response = $this->busmodules->SendChangeRequest($input, $verify_data['supplier_booking_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'cancelrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }


    public function UpdateBookingDetail()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->getbookingdetail_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->get_book_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $response = $this->busmodules->GetBookingDetail($input, $verify_data['supplier_booking_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'updatebookingdetail');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    function generateTicketInvoice()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingId = $input['BookigId'];
            $BookingToken = $input['SearchTokenId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getBusBookingData($BookingId, $BookingToken);
            $logoFolderName = "logo";
            if (isset($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {
                $travelersInfo = json_decode($BookingDetail['travelersInfo'], true);
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,pan_number,state,company_gst_no,city,pincode,support_no,support_email,company_logo,pre_fix");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");
                $BookingDate = $BookingDetail['created'];


                $data['InvoiceNumber'] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "bus"))['acc_ref_number'];
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['BookingRefNumber'] = $BookingDetail['booking_ref_number'];
                $data['RefrenceNumber'] = $BookingDetail['booking_ref_number'];
                $data['OriginCity'] = $BookingDetail['origin_city'];
                $data['DestinationCity'] = $BookingDetail['destination_city'];
                $data['DateOfJourney'] = bus_custom_date_formate($BookingDetail['date_of_journey']);
                $data['BusName'] = $BookingDetail['bus_name'];
                $data['BusType'] = $BookingDetail['bus_type'];
                $data['DepartureTime'] = $BookingDetail['departure_time'];
                $data['ArrivalTime'] = $BookingDetail['arrival_time'];
                $data['TicketNo'] = $BookingDetail['ticket_no'];
                $data['TravelOperatorPnr'] = $BookingDetail['travel_operator_pnr'];
                $data['NoOfSeats'] = $BookingDetail['no_of_seats'];
                $data['BoardingPoints'] = json_decode($BookingDetail['boarding_points'], true);
                $data['DroppingPoints'] = json_decode($BookingDetail['dropping_points'], true);
                $data['CancellationPolicies'] = json_decode($BookingDetail['cancellation_policies'], true);
                $data['LeadPassenger'] = $travelersInfo[0]['title'] . " " . $travelersInfo[0]['first_name'] . " " . $travelersInfo[0]['last_name'];
                $data['PassengerEmail'] = $travelersInfo[0]['email_id'];
                $data['PassengerContactNumber'] = $travelersInfo[0]['mobile_number'];
                $data['TravelersInfo'] = $travelersInfo;
                $data['PaymentStatus'] = $BookingDetail['payment_status'];
                $data['BookingStatus'] = $BookingDetail['booking_status'];
                $data['TotalPrice'] = $BookingDetail['total_price'];
                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];

                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();
                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];


                if ($partnerInfo['company_logo'] != "") {
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $partnerInfo['company_logo'];
                }
                if ($partnerInfo['company_logo'] == "") {
                    $data['CompanyLogo'] = "";
                }


                $publishedFare = 0;
                $offeredFare = 0;
                $CommEarned = 0;
                $TDS = 0;
                $ApplyDiscount = 0;
                $ApplyMarkup = 0;
                $CGSTAmount = 0;
                $IGSTAmount = 0;
                $SGSTAmount = 0;
                $TaxableAmount = 0;


                $web_partner_fare_break_up = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                $super_admin_fare_up = json_decode($BookingDetail['super_admin_fare_break_up'], true);
                $GSTDATA = $web_partner_fare_break_up[0]['GST'];
                foreach ($web_partner_fare_break_up as $key => $HotelRooms) {
                    $GST = $HotelRooms['GST'];
                    $GSTDATA['CGSTAmount'] = $CGSTAmount + $GST['CGSTAmount'];
                    $GSTDATA['IGSTAmount'] = $IGSTAmount + $GST['IGSTAmount'];
                    $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                    $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                    $super_admin_fare_break_up = $super_admin_fare_up[$key];
                    $markup = isset($super_admin_fare_break_up['SUP_Markup']) ? $super_admin_fare_break_up['SUP_Markup'] : 0;
                    $discount = isset($super_admin_fare_break_up['SUP_Discount']) ? $super_admin_fare_break_up['SUP_Discount'] : 0;
                    $ApplyDisPlayMarkup = isset($super_admin_fare_break_up['SUP_DisplayMarkup']) ? $super_admin_fare_break_up['SUP_DisplayMarkup'] : 'in_tax';
                    $ApplyMarkup = $ApplyMarkup + $markup;
                    $ApplyDiscount = $ApplyDiscount + $discount;
                    $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                    $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                    $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                    $TDS = $TDS + $HotelRooms['TDS'];
                }

                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "PublishedPrice" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Gross"),
                        "CommEarned" => array("Value" => custom_money_format(round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                        "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $TDS)), "LabelText" => "Total Amount"),
                    "GSTDetails" => $GSTDATA,
                    "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                    "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                    "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
                );


                if ($HtmlType == "CustomerInvoice") {

                    $WebPMarkUp = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
                    $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;

                    #change customer breakup

                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "PublishedPrice" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Gross"),
                            "ServiceAndOtherCharge" => array("Value" => $WebPMarkUp, "LabelText" => "Other & Service Charges"),
                            /* "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)")*/
                        ),
                        "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $WebPMarkUp)), "LabelText" => "Total Amount"),
                        "GSTDetails" => $GSTDATA,
                        "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                        "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                        "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
                    );

                    $data['SuperAdminState'] = $partnerInfo['state'];
                    $data['SuperAdminCity'] = $partnerInfo['city'];
                    $data['SuperAdminCompanyName'] = $partnerInfo['company_name'];
                    $data['SuperAdminAddress'] = $partnerInfo['address'];
                    $data['SuperAdminPincode'] = $partnerInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $partnerInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $partnerInfo['support_email'];
                    $data['SuperAdminGstNo'] = $partnerInfo['company_gst_no'];

                    $data['SuperAdminCountry'] = $partnerInfo['country'];
                    $data['State'] = "";
                    $data['City'] = '';
                    $data['CompanyName'] = $data['LeadPassenger'];
                    $data['Address'] = isset($data['GstInfo']['address']) ? $data['GstInfo']['address'] : "";;
                    $data['Pincode'] = "";

                    $data['SupportEmail'] = $travelersInfo[0]['email_id'];
                    $data['SupportEmail'] = $travelersInfo[0]['mobile_number'];

                    $data['Country'] = '';
                    $data['GstNo'] = isset($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";

                    $data['PanNo'] = "";
                }

                $data['FareBreakUp'] = $FareBreakUp;

                $html = "";
                if ($HtmlType == "Voucher") {
                    $html = view('Modules\Busservice\Views\ticket', $data);
                } else if ($HtmlType == "Invoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Busservice\Views\invoice', $data);
                }
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }


    function generateCreditNote()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingId = $input['BookigId'];
            $BookingToken = $input['SearchTokenId'];
            $traveller_ref_number = $input['traveller_ref_number'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingToken, "*");


            if (isset($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {

                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,pan_number,state,company_gst_no,city,pincode,support_no,support_email,company_logo,pre_fix");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");


                $travellers = $RestModel->getTravellerData($traveller_ref_number);
                $CreditNote = $RestModel->getaccountLogCreditNote($BookingDetail['web_partner_id'], $travellers['refund_account_id']);
                $BookingDate = $BookingDetail['created'];
                $fare = json_decode($travellers['fare'], true);

                $data['travel_operator_pnr'] = $BookingDetail['travel_operator_pnr'];

                $data['travellers'] = $travellers;
                $data['CreditNoteDate'] = custom_date_format($CreditNote['created']);
                $data['CreditNoteNo'] = $CreditNote['acc_ref_number'];

                $data['InvoiceNumber'] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "visa"))['acc_ref_number'];
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['ConfirmationNo'] = $BookingDetail['confirmation_no'];


                $data['bus_name'] = $BookingDetail['bus_name'];
                $data['origin_city'] = $BookingDetail['origin_city'];
                $data['destination_city'] = $BookingDetail['destination_city'];
                $data['date_of_journey'] = $BookingDetail['date_of_journey'];


                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];
                $data['CompanyLogo'] = $partnerInfo['company_logo'];
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];

                $OtherCharges = 0;
                $Discount = 0;
                $TDS = 0;
                $GSTAmount = 0;
                $OfferedPrice = 0;
                $AgentCommission = 0;


                if (isset($fare['OtherCharges']) && $fare['OtherCharges'] != null) {
                    $OtherCharges = $fare['OtherCharges'];
                }
                if (isset($fare['Discount']) && $fare['Discount'] != null) {
                    $Discount = $fare['Discount'];
                }
                if (isset($fare['TDS']) && $fare['TDS'] != null) {
                    $TDS = $fare['TDS'];
                }
                if (isset($fare['AgentCommission']) && $fare['AgentCommission'] != null) {
                    $AgentCommission = $fare['AgentCommission'];
                }
                if (isset($fare['GSTAmount']) && $fare['GSTAmount'] != null) {
                    $GSTAmount = $fare['GSTAmount'];
                }
                if (isset($fare['OfferedPrice']) && $fare['OfferedPrice'] != null) {
                    $OfferedPrice = $fare['OfferedPrice'];
                }

                $ServiceAndOtherCharge = $OtherCharges + $fare['ServiceCharges'];



                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => round_value($fare['BasePrice']), "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => round_value($fare['Tax']), "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge, "LabelText" => "Other & Service Charges"),

                        "CommEarned" => array("Value" => round_value($AgentCommission + $Discount), "LabelText" => "Comm Earned (-)"),

                        "GSTAmount" => array("Value" => round_value($GSTAmount), "LabelText" => "GST Amount (+)"),
                        "TDS" => array("Value" => round_value($TDS), "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => $OfferedPrice + $TDS, "LabelText" => "Total Amount"),

                    "GSTDetails" => $fare['GST']
                );


                $data['FareBreakUp'] = $FareBreakUp;
                $html = View('Modules\Busservice\Views\bus-credit-note', $data);

                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }


    public function GetLogs()
    {
        $uri = service('uri');
        $searchtoken = $uri->getSegment(4);
        if ($searchtoken) {
            $RestModel = new RestModel();
            $logdata = $RestModel->get_supplier_logs(['tts_search_token' => $searchtoken]);
            if ($logdata) {

                $path = FCPATH . "writable/apilogs/bus/";
                if (!is_dir($path)) {
                    mkdir($path);
                }

                foreach ($logdata as $log) {
                    $key = $log['service'];
                    $request = $log['request'];
                    $myfile = fopen($path . "/Request_" . $key . ".json", "w") or die("Unable to open file!");
                    fwrite($myfile, $request);
                    fclose($myfile);

                    $response = $log['response'];
                    $myfile = fopen($path . "/Response_" . $key . ".json", "w") or die("Unable to open file!");
                    fwrite($myfile, $response);
                    fclose($myfile);
                }

                $tts_response = array(
                    'Search_Token' => $searchtoken,
                    'Error' => array("ErrorCode" => 0, "ErrorMessage" => ""),
                    'Result' => "Log file Successful created"
                );
                return $this->response->setContentType('application/json')->setJSON($tts_response);
            }
        }
    }

    public function GetBusCityList()
    {
        $response = $this->busmodules->GetBusCityList();
    }


    public function SubmitAmendment()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->amendment_validation($input));
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            if (isset($input['AmendmentId']) && $input['RequesterInfo']['Requester'] == "SuperAdmin") {
                $whereCondition = array("id" => $input['AmendmentId'], "booking_ref_no" => $input['BookingId']);
                $amendmentupdateData = array(
                    "amendment_status" => $input['AmendmentStatus'],
                    "remark_from_web_partner" => $input['Remarks'],
                    "sup_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                    "modified" => create_date(),
                );
                $RestModel->updateUserData("bus_amendment", $whereCondition, $amendmentupdateData);
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Status Successfully Updated"), "Result" => array("AmendmentId" => $input['AmendmentId']));
            } elseif ((isset($input['RequesterInfo']['wl_agent_id']) && $input['RequesterInfo']['Requester'] == "Whitelabel")) {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("bus_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId'], "pax_id" => $input['PaxId']), "id,amendment_status");
                    if ($AmendmentStatus) {
                        $amendmentsaveData = array(
                            "wl_agent_id" => $input['RequesterInfo']['wl_agent_id'],
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => isset($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],
                            "request" => json_encode(array("PaxId" => $input['PaxId'], "Sectors" => '')),
                            "pax_id" => implode(",", $input['PaxId']),
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("bus_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("bus_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId'], "pax_id" => $input['PaxId']), "id,amendment_status");
                    if ($AmendmentStatus) {
                        $amendmentsaveData = array(
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => isset($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],
                            "request" => json_encode(array("PaxId" => $input['PaxId'], "Sectors" => '')),
                            "pax_id" => implode(",", $input['PaxId']),
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("bus_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_bus_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            }

        }
    }
}

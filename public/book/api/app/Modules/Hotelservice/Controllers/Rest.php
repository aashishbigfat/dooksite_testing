<?php

namespace Modules\Hotelservice\Controllers;

use App\Controllers\BaseController;
use App\Modules\Hotelservice\Models\RestModel;
use Modules\Hotelservice\Config\Validation;
use CodeIgniter\I18n\Time;

class Rest extends BaseController
{

    public function __construct()
    {
        helper('Modules\Hotelservice\Helpers\rest');
        $RestModel = new RestModel();
        $this->GetTimeZone=app_timezone();
        $this->supplier_list = $RestModel->get_api_supplier();
        if ($this->supplier_list) {
            if (isset($this->supplier_list['TBO'])) {
                $this->TBO_Module = new \Modules\TBOHotel\Controllers\TBOHotel();
            }
            
            if (isset($this->supplier_list['CRS'])) {
                $this->CRS_Module = new \Modules\CRSHotel\Controllers\CRSHotel();
            }
            
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
            $multiresponse  =  array();
            $HotelResult  =  array();
            $HotelResultCommonData  =  array();
            $HotelSupplierIndex  =  array();
            if ($input['CountryCode'] == 'IN') {
                $region_type = 'domestic';
            } else {
                $region_type = 'international';
            }
            $multirequest = array();
            $multiresponse = array();
            $crsresponse = array();
            $convert_response = array();
            $custom_index = array();
            $ApiSupplier  =  array_values($this->supplier_list);
            $RestModel = new RestModel();
            if ($this->supplier_list) {
                foreach ($this->supplier_list as $module) {
                    $loadmodule = $module . '_Module';
                    $Request = $this->$loadmodule->Search($input, $tts_search_token, $this->userauthdata);
                    if ($Request) {
                        if ($module != 'CRS') {
                            array_push($multirequest, $Request);

                        } else {
                            $crsresponse['CRS'] = $Request;
                        }
                    }
                }
            }
            $markUpDiscountExtraparametr['btype']   = strtoupper($this->Btype);
          /*   pr($markUpDiscountExtraparametr['btype']);die; */
            $multiresponse = Hotel_MultiCurl_Request($multirequest);
            if (isset($crsresponse['CRS']) && !empty($crsresponse)) {
                $crsInfo = array("Supplier" => "CRS", "Request" => json_encode($input), "Response" => json_encode($crsresponse['CRS']));
                array_push($multiresponse, $crsInfo);
                unset($crsresponse['CRS']);
            }
     
            if ($multiresponse) {
                $common_data = array();
                $insert_data = array();
                $common_data['super_admin_markup']  = $RestModel->super_admin_markup($this->userauthdata['web_partner_class_id'], $region_type, $markUpDiscountExtraparametr,"");
                $common_data['super_admin_discount'] = $RestModel->super_admin_discount($this->userauthdata['web_partner_class_id'], $region_type,$markUpDiscountExtraparametr,"");
                $common_data['super_admin_gst_state_code'] = $RestModel->super_admin_gst_state_code()['gst_state_code'];
                $common_data['userauthdata'] = $this->userauthdata;
                foreach ($multiresponse  as $supplierKey => $item) {
                    $loadmodule = $item['Supplier'] . '_Module';
                    $Return_Response = $this->$loadmodule->ConvertSearchResponse($input, $item['Response'], $convert_response, $custom_index, $common_data);
                    if ($Return_Response) {
                        $convert_response = $Return_Response['convert_response'];
                        $custom_index = $Return_Response['custom_index'];
                    }
                    $hotel_supplier_log = array(
                        'web_partner_id' => $this->userauthdata['web_partner_id'],
                        'tts_search_token' => $tts_search_token,
                        'request' => $item['Request'],
                        'response' => $item['Response'],
                        'service' => 'Search',
                        'api_supplier' => $item['Supplier'],
                        'created' => strtotime(Time::now($this->GetTimeZone))
                    );
                    array_push($insert_data, $hotel_supplier_log);
                }
                $custom_index=array('RegionType'=>$region_type,'NoOfNights'=>$input['NoOfNights'],'NoOfRooms'=>$input['NoOfRooms'],'GuestNationality'=>$input['GuestNationality'],"CustomIndex"=>$custom_index);
                    
                $RestModel->insertBatchData('common_hotel_log', $insert_data);
                if (count($convert_response) != 0 && !empty($convert_response)) {
                    $ErrorCode = 0;
                    $ErrorMessage = '';
                } else {
                    $ErrorCode = 1;
                    $ErrorMessage = 'no result found';
                }
            } else {
                $ErrorCode = 1;
                $ErrorMessage = 'no result found';
            }

            $tts_response = array(
                'UserIp' => $input['UserIp'],
                'SearchTokenId' => $tts_search_token,
                'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                'Result' => $convert_response
            );
            // Insert TTS Logs
            $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $tts_search_token, $input, $tts_response, 'search', null, $custom_index);

              if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                   ob_start('ob_gzhandler');
               else ob_start();

            return $this->response->setContentType('application/json')->setJSON($tts_response);
        }
    }

    public function GetHotelInfo()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->hotel_info_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($common_data['CustomIndex'][$input['ResultIndex']])) {
                    $getSupplierInfo  =  $common_data['CustomIndex'][$input['ResultIndex']];
                    $common_data['CustomIndex'] = $getSupplierInfo;
                    $activeSupplier =  $getSupplierInfo['Supplier'] . "_Module";
                    $response = $this->$activeSupplier->GetHotelInfo($input, $common_data, $this->userauthdata);
                    // Insert TTS Logs
                    $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'gethotelinfo', $input['ResultIndex'], $response['CustomIndex']);

                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }
    public function GetHotelRoom()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->hotel_info_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($common_data['CustomIndex'][$input['ResultIndex']])) {
                    $getSupplierInfo  =  $common_data['CustomIndex'][$input['ResultIndex']];
                    $common_data['CustomIndex'] = $getSupplierInfo;
                    $activeSupplier =  $getSupplierInfo['Supplier'] . "_Module";
                    $region_type =   $common_data['RegionType'];
                    $ApiSupplier  =  $getSupplierInfo['Supplier'];
                    $markUpDiscountExtraparametr['btype']   = strtoupper($this->Btype);
                    $common_data['super_admin_markup'] = $RestModel->super_admin_markup($this->userauthdata['web_partner_class_id'], $region_type, $markUpDiscountExtraparametr, $ApiSupplier);
                    $super_admin_discount = $RestModel->super_admin_discount($this->userauthdata['web_partner_class_id'], $region_type,  $markUpDiscountExtraparametr,$ApiSupplier);
                    $common_data['super_admin_discount'] =  reset($super_admin_discount);
                    $common_data['super_admin_gst_state_code'] = $RestModel->super_admin_gst_state_code()['gst_state_code'];
                    $response = $this->$activeSupplier->GetHotelRoom($input, $common_data, $this->userauthdata);
                    // Insert TTS Logs
                    $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'getroominfo', $input['ResultIndex'], $response['CustomIndex']);

                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }
    public function BlockRoom()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->block_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'getroominfo', "selected_index" => $input['ResultIndex']]);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($common_data['Supplier'])) {
                    $activeSupplier =  $common_data['Supplier'] . "_Module";
                    $region_type =   $common_data['RegionType'];
                    $ApiSupplier  =  $common_data['Supplier'];
                    $markUpDiscountExtraparametr['btype']   = strtoupper($this->Btype);
                    $common_data['super_admin_markup'] = $RestModel->super_admin_markup($this->userauthdata['web_partner_class_id'], $region_type, $markUpDiscountExtraparametr,$ApiSupplier);
                    $super_admin_discount = $RestModel->super_admin_discount($this->userauthdata['web_partner_class_id'], $region_type,$markUpDiscountExtraparametr, $ApiSupplier);
                    $common_data['super_admin_discount'] =  reset($super_admin_discount);
                    $common_data['super_admin_gst_state_code'] = $RestModel->super_admin_gst_state_code()['gst_state_code'];
                    $response = $this->$activeSupplier->BlockRoom($input, $common_data, $this->userauthdata);
                    // Insert TTS Logs
                    $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'blockroom', $input['ResultIndex'], $response['CustomIndex']);
                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
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
        $book_validation                 =  $validate->book_validation($input);
        $this->validation->setRules($book_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {

            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['tts_search_token' => $input['SearchTokenId'], 'service' => 'blockroom', "selected_index" => $input['ResultIndex']]);
            if ($verify_data) {
                $common_data = json_decode($verify_data['tts_index_response'], true);
                /*---Start Again Validate-----------*/
                $book_validation                 =  $validate->book_validation($input,$common_data);
                $this->validation->setRules($book_validation);
                $rules = $this->validation->run($input);
                if (!$rules) {
                    $message = validation_string_message($this->validation->getErrors());
                    return api_custom_message(400, $message);
                } 
                /*--- End Again Validate-----------*/

                /*-- Check Search Token Id is Valid for Single Request Book Method other wise Token will be expire ---  */
                $check_book_request = $RestModel->book_record_exists(['tts_search_token' => $input['SearchTokenId'], 'service' => 'book']);
                if (empty($check_book_request)) {
                    $BookingDetail             =   $RestModel->getbookingDetailByToken($input['SearchTokenId'], "id,payment_status");
                    $activeSupplier =  $common_data['Supplier'] . "_Module";
                    if (isset($BookingDetail['id'])) {
                        if (isset($BookingDetail['payment_status']) && $BookingDetail['payment_status'] == "Successful") {
                            $acc_ref_number_data= $RestModel->get_acc_ref_number($BookingDetail['id'], $this->userauthdata['web_partner_id']);
                            $acc_ref_number= isset($acc_ref_number_data['acc_ref_number']) ? $acc_ref_number_data['acc_ref_number'] : '';
                            $saveInfo= array("booking_ref_no" => $BookingDetail['id'], "acc_ref_number" => $acc_ref_number, "payment_status" => $BookingDetail['payment_status']);
                            $response = $this->$activeSupplier->Book($input, $common_data, $this->userauthdata, $saveInfo);
                        } else {
                            $message = "Payment Not Done.";
                            return api_custom_message(400, $message);
                        }
                    } else {
                        $Auth_User_Balance = $RestModel->get_auth_user_account_balance($this->userauthdata['web_partner_id']);
                        if (check_web_partner_balance($Auth_User_Balance, $common_data['TTS_Invoice_Amount'],$this->Btype)) {
                            $saveInfo   = Rest::saveData($input, $common_data, $this->userauthdata);
                            $response = $this->$activeSupplier->Book($input, $common_data, $this->userauthdata, $saveInfo);
                        }
                    }
                    // Insert TTS Logs
                    $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'book', $input['ResultIndex'], $response['CustomIndex']);
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
        $tts_search_token = $input['SearchTokenId'];
        $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
        /*--------- Start For Payment Logs and Insert Data----------------*/
        $RestModel = new RestModel();
        $BlockResult = $RestModel->get_block_data($tts_search_token, $input['ResultIndex']);
        if ($BlockResult) {
            $block_result_array = json_decode($BlockResult['response'], true);
            if ($block_result_array['Error']['ErrorCode'] == 0) {
                $block_response = $block_result_array['Result'];
                /*--------------------------------Start Debit Webpartner Balance --------------------------------------*/

                $Auth_User_Balance = $RestModel->get_auth_user_account_balance($userauthdata['web_partner_id']);
                $balance = $Auth_User_Balance['balance'] - $TTS_Invoice_Amount;
                $web_partner_account_log = array(
                    'web_partner_id' => $userauthdata['web_partner_id'],
                    'debit' => $TTS_Invoice_Amount,
                    'balance' => $balance,
                    'remark' => 'Ticket Created Through API',
                    'service' => 'hotel',
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
                $search_city_name = $RestModel->get_city_name($search_request_array['DestinationCityId']);

                if ($common_data['RegionType'] == 'domestic') {
                    $is_domestic = true;
                } else {
                    $is_domestic = false;
                }

                $hotel_rooms_details = array();
                $totalpax = 0;
                if ($input['HotelRoomsDetails']) {
                    foreach ($input['HotelRoomsDetails'] as $RoomsIndex) {
                        foreach ($block_response['HotelRoomsDetails'] as $RoomDetails) {
                            if ($RoomsIndex['RoomIndex'] == $RoomDetails['RoomIndex']) {
                                $totalpax += count($RoomsIndex['HotelPassenger']);
                                $RoomDetails['HotelPassenger'] = $RoomsIndex['HotelPassenger'];
                                $hotel_rooms_details[] = $RoomDetails;
                            }
                        }
                    }
                }
                $last_cancellation_date = $hotel_rooms_details[0]['LastCancellationDate'];
                $last_voucher_date = $hotel_rooms_details[0]['LastVoucherDate'];
                $LeadPassengerName = $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['FirstName'] . ' ' . $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['LastName'];
                $ContactNumber = $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['Phoneno'];
                $ContactEmailId = $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['Email'];
                $save_book_data = array(
                    'tts_search_token' => $tts_search_token,
                    'web_partner_id' => $userauthdata['web_partner_id'],
                    'lead_passenger_name' => $LeadPassengerName,
                    'contact_number' => $ContactNumber,
                    'contact_email_id' =>  $ContactEmailId,
                    'city' => $search_city_name['destination'],
                    'city_id' => $search_request_array['DestinationCityId'],
                    'check_in_date' => $search_request_array['CheckInDate'],
                    'check_out_date' => $search_request_array['CheckOutDate'],
                    'no_of_nights' => $search_request_array['NoOfNights'],
                    'no_of_rooms' => $search_request_array['NoOfRooms'],
                    'room_guests' => json_encode($search_request_array['RoomGuests']),
                    'country_code' => $search_request_array['CountryCode'],
                    'guest_nationality' => $search_request_array['GuestNationality'],
                    'is_domestic' => $is_domestic,
                    'hotel_code' => $input['HotelCode'],
                    'hotel_name' => $block_response['HotelName'],
                    'resultIndex' => $input['ResultIndex'],
                    'star_rating' => $block_response['StarRating'],
                    'address1' => $block_response['AddressLine1'],
                    'address2' => $block_response['AddressLine2'],
                    'latitude' => $block_response['Latitude'],
                    'longitude' => $block_response['Longitude'],
                    'hotel_norms' => tag_exist($block_response['HotelNorms']),
                    'hotel_policy_detail' => tag_exist($block_response['HotelPolicyDetail']),
                    'last_cancellation_date' => $last_cancellation_date,
                    'last_voucher_date' => $last_voucher_date,
                    'hotel_rooms_details' => json_encode($hotel_rooms_details),
                    'api_supplier' =>  $common_data['Supplier'],
                    'super_admin_fare_break_up' =>  json_encode($common_data['SuperAdminFarebreakup']),
                    'web_partner_fare_break_up' =>  json_encode($common_data['WebPartnerFarebreakup']),
                    'payment_mode' => 'API_Wallet',
                    'payment_status' => $payment_status,
                    'booking_status' => 'Processing',
                    'booking_channel' => 'API',
                    'total_price' => $TTS_Invoice_Amount,
                    'created' => create_date()
                );
                $booking_lastid = $RestModel->insertData('hotel_booking_list', $save_book_data);
                /*------------------ Update Account Log Data ----------------------------*/
                $PaxName = $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['FirstName'] . ' ' . $input['HotelRoomsDetails'][0]['HotelPassenger'][0]['LastName'] . ' X ' . $totalpax;
                $service_log = array('PaxName' => $PaxName, 'City' => $search_city_name['destination'], 'CheckInDate' => $search_request_array['CheckInDate'], 'CheckOutDate' => $search_request_array['CheckOutDate']);
                $account_update_data = array(
                    'acc_ref_number' => $acc_ref_number,
                    'booking_ref_no' => $booking_lastid,
                    'service_log' => json_encode($service_log)
                );
                $RestModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
                 /*------------------ Update Account Log Data ----------------------------*/
                  /*------------------ Update Booking  Data ----------------------------*/
                  $super_admin__booking_pre_fix_code = $RestModel->super_admin_booking_pre_fix_code()['pre_fix'];
                  $booking_ref_number =   $super_admin__booking_pre_fix_code.$booking_lastid;
                 $booking_update_data = array(
                    'booking_ref_number' => $booking_ref_number,
                );
                $RestModel->updateUserData('hotel_booking_list', ['id' => $booking_lastid], $booking_update_data);
                 /*------------------ Update BookingData ----------------------------*/
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
    public function GenerateVoucher()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generatevoucher_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_booking_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $activeSupplier =  $verify_data['api_supplier'] . "_HotelModules";
                $response = $this->$activeSupplier->GenerateVoucher($input, $verify_data['supplier_booking_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'generatevoucher');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
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
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'getbookingdetail');
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
                $activeSupplier =  $verify_data['api_supplier'] . "_HotelModules";
                $response = $this->$activeSupplier->SendChangeRequest($input, $verify_data['supplier_booking_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'cancelrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }
    public function CancelRequestAmendment($input)
    {
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
                $activeSupplier =  $verify_data['api_supplier'] . "_HotelModules";
                $response = $this->$activeSupplier->SendChangeRequest($input, $verify_data['supplier_booking_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'cancelrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }
    public function RefundRequest()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->refund_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->get_cancel_detail(['id' => $input['CancelRequestId'], 'hotel_booking_id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $activeSupplier =  $verify_data['api_supplier'] . "_HotelModules";
                $response = $this->activeSupplier->GetChangeRequestStatus($input, $verify_data['supplier_cancel_id'], $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'refundrequest');
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
        $this->validation->setRules($validate->update_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_booking_detail(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId']]);
            if ($verify_data) {
                $activeSupplier =  $verify_data['api_supplier'] . "_HotelModules";
                $response = $this->$activeSupplier->GetBookingDetail($input, $verify_data, $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'updatebookingdetail');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    function generateVoucherInvoice()
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
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingToken, "*");
            if (isset($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {
                $hotel_rooms_details = json_decode($BookingDetail['hotel_rooms_details'], true);
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,pan_number,state,company_gst_no,city,pincode,support_no,support_email,company_logo,pre_fix");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");

                $BookingDate = $BookingDetail['created'];
                $data['InvoiceNumber'] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "hotel"))['acc_ref_number'];
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['RefrenceNumber'] = $BookingDetail['booking_ref_number'];
                $data['City'] = $BookingDetail['city'];
                $data['LeadPassenger'] = $hotel_rooms_details[0]['HotelPassenger'][0]['Title'] . " " . $hotel_rooms_details[0]['HotelPassenger'][0]['FirstName'] . " " . $hotel_rooms_details[0]['HotelPassenger'][0]['LastName'];
                $data['CheckInDate'] = $BookingDetail['check_in_date'];
                $data['CheckOutDate'] = $BookingDetail['check_out_date'];
                $data['BookingRefNumber'] = $BookingDetail['booking_ref_number'];
                $data['NoOfNights'] = $BookingDetail['no_of_nights'];
                $data['NoOfRooms'] = $BookingDetail['no_of_rooms'];
                $data['HotelName'] = $BookingDetail['hotel_name'];
                $data['Address1'] = $BookingDetail['address1'];
                $data['Address2'] = $BookingDetail['address2'];
                $data['HotelPolicyDetail'] = $BookingDetail['hotel_policy_detail'];
                $data['LastCancellationDate'] = $BookingDetail['last_cancellation_date'];
                $data['ConfirmationNo'] = $BookingDetail['confirmation_no'];
                $data['HotelRoomsDetails'] = $hotel_rooms_details;
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

                    #change customer breakup

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


                    $data['SuperAdminState'] = $partnerInfo['state'];
                    $data['SuperAdminCity'] = $partnerInfo['city'];
                    $data['SuperAdminCompanyName'] = $partnerInfo['company_name'];
                    $data['SuperAdminAddress'] = $partnerInfo['address'];
                    $data['SuperAdminPincode'] = $partnerInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $partnerInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $partnerInfo['support_email'];
                    $data['SuperAdminGstNo'] = $partnerInfo['company_gst_no'];
                    $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                    $data['SuperAdminCountry'] = $partnerInfo['country'];
                    $data['State'] = "";
                    $data['City'] = '';
                    $data['CompanyName'] = $data['LeadPassenger'];
                    $data['Address'] = isset($data['GstInfo']['address']) ? $data['GstInfo']['address'] : "";;
                    $data['Pincode'] = "";
                    $data['SupportNo'] = $BookingDetail['contact_number'];
                    $data['SupportEmail'] = $BookingDetail['contact_email_id'];
                    $data['Country'] = '';
                    $data['GstNo'] = isset($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";

                    $data['PanNo'] = "";
                }
                $data['FareBreakUp'] = $FareBreakUp;
                if ($HtmlType == "Voucher") {
                    $html = view('Modules\Hotelservice\Views\voucher', $data);
                } else if ($HtmlType == "Invoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Hotelservice\Views\invoice', $data);
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
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingToken, "*");
            if (isset($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {
                $hotel_rooms_details = json_decode($BookingDetail['hotel_rooms_details'], true);
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,pan_number,state,company_gst_no,city,pincode,support_no,support_email,company_logo,pre_fix");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");
                $CreditNote = $RestModel->getaccountLogCreditNote($BookingDetail['web_partner_id'], $BookingDetail['refund_account_id']);
                $BookingDate = $BookingDetail['created'];

                $data['CreditNoteDate'] = custom_date_format($CreditNote['created']);
                $data['CreditNoteNo'] = $CreditNote['acc_ref_number'];

                $data['InvoiceNumber'] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "hotel"))['acc_ref_number'];
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['RefrenceNumber'] = $BookingDetail['booking_ref_number'];
                $data['City'] = $BookingDetail['city'];
                $data['LeadPassenger'] = $hotel_rooms_details[0]['HotelPassenger'][0]['Title'] . " " . $hotel_rooms_details[0]['HotelPassenger'][0]['FirstName'] . " " . $hotel_rooms_details[0]['HotelPassenger'][0]['LastName'];
                $data['CheckInDate'] = $BookingDetail['check_in_date'];
                $data['CheckOutDate'] = $BookingDetail['check_out_date'];
                $data['BookingRefNumber'] = $BookingDetail['booking_ref_number'];
                $data['NoOfNights'] = $BookingDetail['no_of_nights'];
                $data['NoOfRooms'] = $BookingDetail['no_of_rooms'];
                $data['HotelName'] = $BookingDetail['hotel_name'];
                $data['Address1'] = $BookingDetail['address1'];
                $data['Address2'] = $BookingDetail['address2'];
                $data['HotelPolicyDetail'] = $BookingDetail['hotel_policy_detail'];
                $data['LastCancellationDate'] = $BookingDetail['last_cancellation_date'];
                $data['ConfirmationNo'] = $BookingDetail['confirmation_no'];
                $data['HotelRoomsDetails'] = $hotel_rooms_details;
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


                $data['FareBreakUp'] = $FareBreakUp;

                $html = View('Modules\Hotelservice\Views\hotel-credit-note', $data);

                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }

    public function SubmitAmendment()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->amendment_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->get_book_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
            if ($verify_data) {
                if ($input['Type'] == "cancellation" &&  ($verify_data['booking_status'] == "Confirmed" || $verify_data['booking_status'] == "Hold")) {
                    if ($verify_data['booking_status'] == "Confirmed" || $verify_data['booking_status'] == "Hold") {
                        $cancellationRequest  =  array(
                            'UserIp' => $this->request->getIpAddress(),
                            'BookingId' => $input['BookingId'],
                            'Remarks' => $input['Remarks'],
                            'SearchTokenId' =>  $verify_data['tts_search_token'],
                        );
                        $CancellationResponse    =  Rest::CancelRequestAmendment($cancellationRequest);
                        $amendment_status  =  "requested";
                        if ($CancellationResponse['Error']['ErrorCode'] != 0) {
                            $amendment_status = "rejected";
                        }
                        $amendmentsaveData  =  array(
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => $amendment_status,
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],
                            "created" => create_date(),

                        );
                        $amendmentId =    $RestModel->insertData("hotel_amendment", $amendmentsaveData);
                    } else {
                        $message = "Invalid Details.";
                        return api_custom_message(400, $message);
                    }
                } else {
                    if (isset($input['AmendmentId']) && $input['RequesterInfo']['Requester'] == "SuperAdmin") {
                        $whereCondition =  array("id" => $input['AmendmentId'], "booking_ref_no" => $input['BookingId']);
                        $amendmentupdateData  =  array(
                            "amendment_status" => $input['AmendmentStatus'],
                            "remark_from_web_partner" => $input['Remarks'],
                            "sup_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "modified" => create_date(),
                        );
                        $RestModel->updateUserData("hotel_amendment", $whereCondition, $amendmentupdateData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array("AmendmentId"=>$input['AmendmentId']));
                    } else {
                              $AmendmentData      =  $RestModel->GetAmendmentInfo("hotel_amendment", array("amendment_type"=>$input['Type'],"booking_ref_no"=>$input['BookingId'],"amendment_status"=>"requested"),"id,amendment_status");
                              if(empty($AmendmentData)) 
                              {
                        $amendmentsaveData  =  array(
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => "requested",
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],
                            "created" => create_date(),

                        );
                        $amendmentId  =  $RestModel->insertData("hotel_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array("AmendmentId"=>$amendmentId));
                    }
                    else{
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                    }
                  
                }
                // Insert TTS Logs
                $RestModel->insert_tts_hotel_logs($this->userauthdata['web_partner_id'],  $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
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
        $bookingrefno = $uri->getSegment(4);
        if ($bookingrefno) {
            $RestModel = new RestModel();
            $booking_detail = $RestModel->get_detail_by_refno($bookingrefno);
            $searchtoken = $booking_detail['tts_search_token'];
            $supplier = $booking_detail['api_supplier'];
            $ext = 'json';
            $booking_detail = $RestModel->get_detail_by_refno($bookingrefno);
            $logdata = $RestModel->get_supplier_logs(['tts_search_token' => $searchtoken,"api_supplier"=>$booking_detail['api_supplier']]);
            if ($logdata) {
                $path = FCPATH . "writable/apilogs/hotel/" . $bookingrefno;
                $destipath = FCPATH . "writable/apilogs/hotel/" . $bookingrefno . '.zip';
                if (!is_dir($path)) {
                    mkdir($path);
                }
                foreach ($logdata as $log) {
                    $key = $log['service'];
                    $request = $log['request'];
                    $myfile = fopen($path . "/Request_" . $key . "." . $ext, "w") or die("Unable to open file!");
                    fwrite($myfile, $request);
                    fclose($myfile);

                    $response = $log['response'];
                    $myfile = fopen($path . "/Response_" . $key . "." . $ext, "w") or die("Unable to open file!");
                    fwrite($myfile, $response);
                    fclose($myfile);
                }
                zipDir($path, $destipath);

                // http headers for zip downloads
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"$bookingrefno.zip\"");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($destipath));
                ob_end_flush();
                @readfile($destipath);

                $tts_response = array(
                    'Search_Token' => $searchtoken,
                    'Error' => array("ErrorCode" => 0, "ErrorMessage" => ""),
                    'Result' => "Log file Successful created"
                );
                return $this->response->setContentType('application/json')->setJSON($tts_response);
            }
        }
    }
}

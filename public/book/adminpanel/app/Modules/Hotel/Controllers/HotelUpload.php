<?php

namespace Modules\Hotel\Controllers;

use App\Modules\Hotel\Models\HotelCitiesModel;
use App\Modules\Hotel\Models\HotelUploadModel;
use App\Controllers\BaseController;
use Modules\Hotel\Config\HotelUploadValidation;


class HotelUpload extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;  
    protected $web_partner_details; 
    protected $admin_comapny_detail; 
    protected $tempstoredata; 
    protected $folder_name; 
    protected $superAdminAccess; 

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Hotel Upload";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        helper('Modules\Hotel\Helpers\hotel-upload');
        ini_set("memory_limit","64M");
        ini_set('serialize_precision', -1);
        $this->tempstoredata=array();
    }

    public function index()
    {
        if (permission_access_error("Hotel", "hotel_hotelupload_list")) {
        $hotel_detail=array();
        $HotelUploadModel = new HotelUploadModel();
        if($this->request->getGet('id'))
        {
            $id=$this->request->getGet('id');
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
            } else {
                $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
                $this->session->setFlashdata('Message', $message); 
                return redirect()->to(site_url('hotel-upload'));
            }
        }
        $offline_supplier=$HotelUploadModel->get_offline_supplier($this->web_partner_id);

        $data = [
            'title' => $this->title,
            'offline_supplier'=>$offline_supplier,
            'hotel_detail'=>$hotel_detail,
            'view' => "Hotel\Views\hotel-upload\index"
        ];
        return view('template/sidebar-layout', $data);
     }
  }

    public function hotel_info_save()
    {
        $data = $this->request->getPost(); 
        $validate = new HotelUploadValidation();
        if($data['bussiness_type']== "B2C"){
            unset($validate->hotel_validation['agent_info']);
        }
        if($data['bussiness_type']== "B2B"){
            unset($validate->hotel_validation['customer_info']);
        }
        $rules = $this->validate($validate->hotel_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HotelUploadModel = new HotelUploadModel();
            if(!isset($data['id'])) {
                $insertData=array(
                                    'web_partner_id'=>$this->web_partner_id,
                                    'service'=>'hotel',
                                    'data'=>json_encode($data),
                                    'created'=>create_date()
                            );
               $id=$HotelUploadModel->insertData('upload_ticket_temp',$insertData);
            } else {

               $id = $data['id']; 
               $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
               if($detail)
               {
                   $hotel_detail=json_decode($detail['data'],true);
                   if(isset($hotel_detail['room_data']))
                   {
                    $data['room_data']=$hotel_detail['room_data'];
                   }
                   
                   $updateData = array(
                                        'web_partner_id' => $this->web_partner_id,
                                        'service' => 'hotel',
                                        'data' => json_encode($data),
                                        'created' => create_date()
                                    );
                   $HotelUploadModel->updateData("upload_ticket_temp", ["id" => $data['id']], $updateData);
               }
             
            }
            $RedirectUrl  =  site_url('hotel-upload/room-information?id='.$id);
            $data_validation = array("StatusCode" => 3, "ErrorMessage" => '',"Redirect_Url"=>$RedirectUrl);
            return $this->response->setJSON($data_validation); 
        } 

    }

    public function room_information()
    {
        if($this->request->getGet('id'))
        {
            $id = $this->request->getGet('id'); 
            $HotelUploadModel = new HotelUploadModel();
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
           
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);

                $iddetail =$detail['id'];
           
                $data = [
                            'title' => $this->title,
                            'hotel_detail' => $hotel_detail,
                            'iddetail' => $iddetail,
                            'view' => 'Hotel\Views\hotel-upload\room-information'
                        ];
                return view('template/sidebar-layout', $data);
            } else {
                $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
                $this->session->setFlashdata('Message', $message); 
                return redirect()->to(site_url('hotel-upload'));
            }
        } else {
            $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
            $this->session->setFlashdata('Message', $message); 
            return redirect()->to(site_url('hotel-upload'));
        }  
    }

    public function addroom()
    {
        $HotelUploadModel = new HotelUploadModel();
        $room_counter = $this->request->getPost('room_counter') + 1; 
        $room_amenities=$HotelUploadModel->get_room_amenities($this->web_partner_id);
        $viewdata['room_amenities']=$room_amenities;
        $viewdata['room_counter']=$room_counter;


        if($this->tempstoredata)
        {
            $viewdata['hotel_detail']=$this->tempstoredata;
        } else {
            $id = $this->request->getPost('id'); 
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
                $this->tempstoredata=$hotel_detail;
                $viewdata['hotel_detail']=$this->tempstoredata;
            }
        }
       


        $roomView = view("Modules\Hotel\Views\hotel-upload\add-room",$viewdata); 
        
        $data = [
			"roomCounter" => $room_counter,
			"roomView" => $roomView,  
		];
        return $this->response->setJSON($data);
    }

    public function addpassanger()
    {
        $HotelUploadModel = new HotelUploadModel();
        $room_counter = $this->request->getPost('room_counter');
        $pax_type = $this->request->getPost('pax_type');  

        $adt_counter=0; $chd_counter=0;
        $viewdata['room_counter']=$room_counter;
        $viewdata['pax_type']=$pax_type;

        if($pax_type=="Adult")
        {
            $adt_counter = $this->request->getPost('adt_counter')+1; 
            $viewdata['adt_counter']=$adt_counter;
        }
        if($pax_type=="Child")
        {
            $chd_counter = $this->request->getPost('chd_counter')+1; 
            $viewdata['chd_counter']=$chd_counter;
        }

        $passport_required=false; $pan_required=false;
        if($this->request->getPost('passport_required'))
        {
            $passport_required=$this->request->getPost('passport_required');
        }
        if($this->request->getPost('pan_required'))
        {
            $pan_required=$this->request->getPost('pan_required');
        }

        $viewdata['passport_required']=$passport_required;
        $viewdata['pan_required']=$pan_required;

        if($this->tempstoredata)
        {
            $viewdata['hotel_detail']=$this->tempstoredata;
        } else {
            $id = $this->request->getPost('id'); 
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
                $this->tempstoredata=$hotel_detail;
                $viewdata['hotel_detail']=$this->tempstoredata;
            }
        }
     

        $passengerView = view("Modules\Hotel\Views\hotel-upload\add-passanger",$viewdata); 
        
        $data = [
			"roomCounter" => $room_counter,
            "paxType" => $pax_type,
			"adtCounter" => $adt_counter,
			"chdCounter" => $chd_counter,
			"passengerView" => $passengerView
            
		];
        return $this->response->setJSON($data);
    }

    public function room_info_save()
    {
        $data = $this->request->getPost(); 
        $validate = new HotelUploadValidation();
        $validationConfigArray = $validate->pax_validation($data);
        $this->validation->setRules($validationConfigArray);
        $rules = $this->validation->run($data);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
            
        } else {
           

            $uri = $this->request->getUri();   
            $id =  ($uri->getSegment(3));

            $HotelUploadModel = new HotelUploadModel();
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
                $hotel_detail['room_data']=$data['room'];
    
                $updateData = array(
                                     'web_partner_id' => $this->web_partner_id,
                                     'service' => 'hotel',
                                     'data' => json_encode($hotel_detail),
                                     'created' => create_date()
                                 );
                                 
                $HotelUploadModel->updateData("upload_ticket_temp", ["id" => $id], $updateData);
                $RedirectUrl  =  site_url('hotel-upload/review-detail?id='.$id);
            } else {
                $RedirectUrl=site_url('hotel-upload');
            }
            $data_validation = array("StatusCode" => 3, "ErrorMessage" => '',"Redirect_Url"=>$RedirectUrl);
            return $this->response->setJSON($data_validation); 
        }

    }

    public function review_detail()
    {
        if($this->request->getGet('id'))
        {
            $id = $this->request->getGet('id'); 
            $HotelUploadModel = new HotelUploadModel();
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
                $iddetail =$detail['id'];
           

                $data = [
                            'title' => $this->title,
                            'hotel_detail' => $hotel_detail,
                            'iddetail' => $iddetail,
                            'view' => 'Hotel\Views\hotel-upload\review-detail'
                        ];
                return view('template/sidebar-layout', $data);
            } else {
                $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
                $this->session->setFlashdata('Message', $message); 
                return redirect()->to(site_url('hotel-upload'));
            }
        } else {
            $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
            $this->session->setFlashdata('Message', $message); 
            return redirect()->to(site_url('hotel-upload'));
        } 
    }


    public function generate_hotel_voucher()
    {
        if($this->request->getGet('id'))
        {
            $id = $this->request->getGet('id'); 
            $HotelUploadModel = new HotelUploadModel();
            $detail=$HotelUploadModel->getData('upload_ticket_temp', ["id" => $id, "service" => "hotel", "web_partner_id" => $this->web_partner_id]);
          
            if($detail)
            {
                $hotel_detail=json_decode($detail['data'],true);
              /*   pr($hotel_detail);exit(); */

                $AgentInfo = array();
                $CustomerInfo = array(); 
                $wl_agent_id = '';
                $wl_customer_id = '';
                if($hotel_detail['bussiness_type'] == "B2B") {
                    $tableName = "agent";
                    $user_id = $hotel_detail['tts_agent_info_id'];
                    $AgentInfo = $HotelUploadModel->getData('agent', array("id" => $hotel_detail['tts_agent_info_id']),'company_name,web_partner_id,gst_number');
                    $wl_agent_id = $hotel_detail['tts_agent_info_id'];
                    $gst_info = (!empty($AgentInfo['gst_number'])) ? substr($AgentInfo['gst_number'], 0, 2) : 0;
                    $booking_source = 'Wl_b2b';
                } else {
                    $tableName = "customer";
                    $user_id = $hotel_detail['tts_customer_info_id'];
                    $CustomerInfo = $HotelUploadModel->getData('customer', array("id" => $hotel_detail['tts_customer_info_id']),'email_id,web_partner_id');
                    $wl_customer_id = $hotel_detail['tts_customer_info_id'];
                    $gst_info = array();
                    $booking_source = 'Wl_b2c';
                }
                
                $roomdetail=array();
                foreach($hotel_detail['room_data'] as $roomkey=>$item)
                {
                    $HotelPassenger=array();
                    foreach($item['pax'] as $paxkey=>$mainpaxitem)
                    { 
                        foreach($mainpaxitem as $key=>$paxitem)
                        {
                           
                            $PaxType=1;
                            if($paxkey=='Adult')
                            {
                                $PaxType=1;
                            }
                            if($paxkey=='Child')
                            {
                                $PaxType=2;
                            }
                            $LeadPassenger=false;
                            if($paxkey=='Adult' && $key==1)
                            {
                                $LeadPassenger=true;
                            }

                            $Age=0;
                            if(isset($paxitem['age']))
                            {
                                $Age=intval($paxitem['age']);
                            }

                            if (isset($paxitem['passport_number'])) {
                                $PassportNo = $paxitem['passport_number'];
                            } else {
                                $PassportNo = NULL;
                            }
                            if (isset($paxitem['nationality'])) {
                                $Nationality = $paxitem['nationality'];
                            } else {
                                $Nationality = NULL;
                            }
                            if (isset($paxitem['issue_date'])) {
                                $PassportIssueDate = date('Y-m-d',strtotime($paxitem['issue_date'])).'T00:00:00' ;
                            } else {
                                $PassportIssueDate = NULL;
                            }
                            if (isset($paxitem['expiry_date'])) {
                                $PassportExpDate =date('Y-m-d',strtotime($paxitem['expiry_date'])).'T00:00:00' ;
                            } else {
                                $PassportExpDate = NULL;
                            }
                            if (isset($paxitem['pan_number'])) {
                                $pancard = $paxitem['pan_number'];
                            } else {
                                $pancard = NULL;
                            }

                            $HotelPassenger=array(
                                            'Title'=>$paxitem['title'],
                                            'FirstName'=>$paxitem['first_name'],
                                            'MiddleName'=>'',
                                            'LastName'=>$paxitem['last_name'],
                                            'Phoneno'=>$hotel_detail['contact_number'],
                                            'Email'=>$hotel_detail['email_id'],
                                            'PaxType'=>$PaxType,
                                            'LeadPassenger'=>$LeadPassenger,
                                            'Age'=>$Age,
                                            'PAN'=>$pancard,
                                            'PassportNo'=>$PassportNo,
                                            'Nationality'=>$Nationality,
                                            'PassportIssueDate'=>$PassportIssueDate,
                                            'PassportExpDate'=>$PassportExpDate,
                                            'GSTCompanyAddress'=>null,
                                            'GSTCompanyContactNumber'=>null,
                                            'GSTCompanyEmail'=>null,
                                            'GSTCompanyName'=>null,
                                            'GSTNumber'=>null
                                            );
                        }
                    }

                    $roomdetail[]=array(
                                'AvailabilityType'=>'Confirm',
                                'AdultCount'=>0,
                                'ChildCount'=>0,
                                'RequireAllPaxDetails'=>true,
                                'RoomId'=>0,
                                'RoomStatus'=>0,
                                'RoomIndex'=>'',
                                'RoomTypeCode'=>'',
                                'RoomDescription'=>'',
                                'RoomTypeName'=>$item['room_name'],
                                'RatePlanCode'=>'',
                                'RatePlan'=>0,
                                'RatePlanName'=>'',
                                'InfoSource'=>'FixedCombination',
                                'SequenceNo'=>'',
                                'IsPerStay'=>false,
                                'SupplierPrice'=>null,
                                'Price'=>array(
                                            'RoomPrice'=>$item['room_price'],
                                            'Tax'=>$item['tax'],
                                            'OtherCharges'=>$item['othercharge'],
                                            'Discount'=>0,
                                            'PublishedPrice'=>0,
                                            'OfferedPrice'=>0,
                                            'AgentCommission'=>0,
                                            'ServiceCharges'=>0,
                                            'TDS'=>0,
                                            'GST'=>array(
                                                    'CGSTAmount'=>0,
                                                    'CGSTRate'=>0,
                                                    )
                                        ),
                                'RoomPromotion'=>'',
                                'Amenities'=>$item['room_amenities'],
                                'Amenity'=>$item['room_amenities'],
                                'SmokingPreference'=>'NoPreference',
                                'BedTypes'=>array(),
                                'HotelSupplements'=>array(),
                                'LastCancellationDate'=>'',
                                'SupplierSpecificData'=>'',
                                'CancellationPolicies'=>array(),
                                'LastVoucherDate'=>'',
                                'CancellationPolicy'=>$item['room_cancellation_policy'],
                                'Inclusion'=>array(),
                                //'IsPassportMandatory'=>$hotel_detail['passport_required'],
                                //'IsPANMandatory'=>$hotel_detail['pan_required'],
                                'HotelPassenger'=>$HotelPassenger
                            );

                }
                pr($roomdetail);exit();
                $hotel_booking = array(
                    'tts_search_token' => $SearchTokenId,
                    'web_partner_id' => $this->web_partner_id,
                    //'lead_passenger_name' => $leadPassengerName,
                    'contact_number' => $hotel_detail['contact_number'],
                    'contact_email_id' => $hotel_detail['email_id'],
                    'city' => $hotel_detail['hotel_city'],
                    'resultIndex' => $hotelblockRequest['ResultIndex'],
                    'city_id' => $hotel_detail['hotel_city_id'],
                    'check_in_date' => $hotel_detail['check_in_date'],
                    'check_out_date' => $hotel_detail['check_out_date'],
                    'no_of_nights' => $search_request_array['NoOfNights'],
                    'no_of_rooms' => $search_request_array['NoOfRooms'],
                    'room_guests' => json_encode($search_request_array['RoomGuests']),
                    'country_code' => $search_request_array['CountryCode'],
                    'guest_nationality' => $search_request_array['GuestNationality'],
                    'is_domestic' => $is_domestic,
                    'hotel_code' => $hotelblockRequest['hcode'],
                    'hotel_name' => $hotel_detail['hotel_name'],
                    'star_rating' => $hotel_detail['hotel_star_rating'],
                    'address1' => $block_response['AddressLine1'],
                    'address2' => $block_response['AddressLine2'],
                    'latitude' => $block_response['Latitude'],
                    'longitude' => $block_response['Longitude'],
                    'gst_info' => isset($hotelblockRequest['gst']) ? json_encode($hotelblockRequest['gst']) : null,
                    'hotel_norms' => tag_exist($block_response['HotelNorms']),
                    'hotel_policy_detail' => tag_exist($block_response['HotelPolicyDetail']),
                    'last_cancellation_date' => $last_cancellation_date,
                    'last_voucher_date' => $last_voucher_date,
                    'hotel_rooms_details' => json_encode($hotel_rooms_details),
                    'api_supplier' => $common_data['Supplier'],
                    'super_admin_fare_break_up' => json_encode($common_data['SuperAdminFarebreakup']),
                    'web_partner_fare_break_up' => json_encode($common_data['WebPartnerFarebreakup']),
                    'payment_mode' => 'Online',
                    'payment_status' => 'Processing',
                    'booking_status' => 'Processing',
                    'booking_channel' => 'Desktop',
                    'total_price' => $TTS_Invoice_Amount,
                    'agent_staff_id' => $this->user_id,
                    'created' => create_date()
                );

               
                
            } else {
                $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
                $this->session->setFlashdata('Message', $message); 
                return redirect()->to(site_url('hotel-upload'));
            }

        } else {
            $message = ["StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup"]; 
            $this->session->setFlashdata('Message', $message); 
            return redirect()->to(site_url('hotel-upload'));
        } 

    }
}
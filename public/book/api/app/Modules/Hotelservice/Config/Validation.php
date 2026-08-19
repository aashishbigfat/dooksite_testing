<?php

namespace Modules\Hotelservice\Config;

class Validation
{
        public $search_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'CheckInDate' => ['rules' => 'trim|required|valid_date[Y-m-d]'],
                'CheckOutDate' => ['rules' => 'trim|required|valid_date[Y-m-d]'],
                'NoOfNights' => ['rules' => 'trim|required|numeric|is_natural_no_zero'],
                'CountryCode' => ['rules' => 'trim|required|alpha'],
                'DestinationCityId' => ['rules' => 'trim|required|numeric'],
                'ResultCount' => ['rules' => 'trim'],
                'GuestNationality' => ['rules' => 'trim|required|alpha'],
                'NoOfRooms' => ['rules' => 'trim|required|numeric|is_natural_no_zero'],
                'RoomGuests.*.Adult' => [
                        'rules' => 'trim|required|numeric',
                        'errors' => [
                                'required' => 'Adult field is required.',
                                'numeric'  => 'Adult field must contain only numbers.'
                        ]
                ],
                'RoomGuests.*.Child' => [
                        'rules' => 'trim|required|numeric',
                        'errors' => [
                                'required' => 'Child field is required.',
                                'numeric'  => 'Child field must contain only numbers.'
                        ]
                ],
                'MaxRating' => ['rules' => 'trim|required|numeric'],
                'MinRating' => ['rules' => 'trim|required|numeric'],
        ];

        public $hotel_info_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'ResultIndex' => ['rules' => 'trim|required'],
                'HotelCode' => ['rules' => 'trim|required'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $room_info_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'ResultIndex' => ['rules' => 'trim|required'],
                'HotelCode' => ['rules' => 'trim|required'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $block_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'ResultIndex' => ['rules' => 'trim|required'],
                'HotelCode' => ['rules' => 'trim|required'],
                'HotelName' => ['rules' => 'trim|required'],
                'NoOfRooms' => ['rules' => 'trim|required'],
                'HotelRoomsDetails.*.RoomIndex' => [
                        'rules' => 'trim|required',
                        'errors' => [
                                'required' => 'RoomIndex field is required.'
                        ]
                ],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public function book_validation($data,$commonData=null) 
        {
                $book_validation =  array();
            $book_validation['UserIp'] = ['rules' => 'trim|required|valid_ip'];
            $book_validation['ResultIndex'] = ['rules' => 'trim|required'];
            $book_validation['HotelCode'] = ['rules' => 'trim|required'];
            $book_validation['HotelName'] = ['rules' => 'trim|required'];
            $book_validation['NoOfRooms'] = ['rules' => 'trim|required'];
            $book_validation['SearchTokenId'] = ['rules' => 'trim|required'];
            foreach($data['HotelRoomsDetails'] as $roomkey=>$HotelRoomsDetails)
                {
                $book_validation["HotelRoomsDetails.$roomkey.RoomIndex"] = ['rules' => 'trim|required'];
                foreach($HotelRoomsDetails['HotelPassenger'] as $paxkey=>$passsenger)
                {
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.Title"] = ['rules' => 'trim|required', 'errors' => ['required' => 'Title field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.FirstName"] = ['rules' => 'trim|required', 'errors' => ['required' => 'First Name field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.LastName"] = ['rules' => 'trim|required', 'errors' => ['required' => 'Last Name field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.MiddleName"] = ['rules' => 'trim'];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.Phoneno"] = ['rules' => 'trim|required', 'errors' => ['required' => 'Phone Number field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.Email"] = ['rules' => 'trim|required|valid_email', 'errors' => ['required' => 'Email field is required.', 'valid_email' => 'Please enter valid email id.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.PaxType"] = ['rules' => 'trim|required', 'errors' => ['required' => 'PaxType field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.LeadPassenger"] = ['rules' => 'trim|required', 'errors' => ['required' => 'LeadPassenger field is required.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.Age"] = ['rules' => 'trim|required|numeric', 'errors' => ['required' => 'Age field is required.', 'numeric' => 'Age field must contain only numbers.']];
                        if (isset($common_data['IsPANMandatory']) && $common_data['IsPANMandatory']) 
                        {
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.PAN"] = ['rules' => 'trim|required|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]', 'errors' => ['required' => 'PAN field is required.', 'regex_match'=>'Please enter valid PAN.']];
                        }
                        if (isset($common_data['IsPassportMandatory']) && $common_data['IsPassportMandatory']) 
                        {
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.PassportNo"] = ['rules' => 'trim|required', 'errors' => ['required' => 'PassportNo field is required.','regex_match'=>'Please enter valid PassportNo.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.PassportIssueDate"] = ['rules' => 'trim|required|valid_date[Y-m-d\T00:00:00]', 'errors' => ['required' => 'PassportIssueDate field is required.','valid_date'=>'PassportIssueDate date format invalid.']];
                        $book_validation["HotelRoomsDetails.$roomkey.HotelPassenger.$paxkey.PassportExpDate"] = ['rules' => 'trim|required|valid_date[Y-m-d\T00:00:00]', 'errors' => ['required' => 'PassportExpDate field is required.','valid_date'=>'PassportExpiry date format invalid.']];
                        }
                }
            }
           return $book_validation;
        }


        public $generatevoucher_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'BookingId' => ['rules' => 'trim|required|numeric'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $getbookingdetail_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'BookingId' => ['rules' => 'trim|required|numeric'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $cancel_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'BookingId' => ['rules' => 'trim|required|numeric'],
                'Remarks' => ['rules' => 'trim|required'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $refund_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'BookingId' => ['rules' => 'trim|required|numeric'],
                'CancelRequestId' => ['rules' => 'trim|required|numeric'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $update_validation = [
                'UserIp' => ['rules' => 'trim|required|valid_ip'],
                'ConfirmationNo' => ['rules' => 'trim|required'],
                'FirstName' => ['rules' => 'trim|required|alpha'],
                'LastName' => ['rules' => 'trim|required|alpha'],
                'SearchTokenId' => ['rules' => 'trim|required']
        ];

        public $generate_html_voucher_invoice = [
            'HtmlType' => ['rules' => 'trim|required|in_list[Voucher,Invoice,CustomerInvoice]'],
            'BookigId' => ['rules' => 'trim|required'],
            'SearchTokenId' => ['rules' => 'trim|required'],
            'UserType' => ['rules' => 'trim|required|in_list[WebPartner,Admin]'],
            'ViewService' => ['rules' => 'trim|required|in_list[Email,View,Pdf]'],
            'ViewSize' => ['rules' => 'trim'],
        ];

        public $amendment_validation = [
                'BookingId' => ['rules' => 'trim|required|numeric'],
                'AmendmentId' => ['rules' => 'trim|permit_empty|numeric'],
                /* 'AmendmentStatus' => ['rules' => 'trim|permit_empty|in_list[success,rejected'], */
                'Type' => ['rules' => 'trim|required|in_list[cancellation,full_refund,reissue,correction,reissue_quotation,cancellation_quotation]'],
                'Remarks' => ['rules' => 'trim|required'],
                'RequesterInfo.*' => ['rules' => 'trim|required'],
        ];
}

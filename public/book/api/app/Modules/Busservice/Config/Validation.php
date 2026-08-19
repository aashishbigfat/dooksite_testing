<?php

namespace Modules\Busservice\Config;

class Validation
{
    public $search_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'OriginId' => ['rules' => 'trim|required|numeric'],
        'DestinationId' => ['rules' => 'trim|required|numeric|differs[OriginId]'],
        'DateOfJourney' => ['rules' => 'trim|required|valid_date[Y-m-d]'],
    ];
    public $seatlayout_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'ResultIndex' => ['rules' => 'trim|required'],
    ];

    public $boardingpoint_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'ResultIndex' => ['rules' => 'trim|required'],
    ];

    public $blockseat_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'BoardingPointId' => ['rules' => 'trim|required'],
        'DroppingPointId' => ['rules' => 'trim'],

        'Passenger.*.LeadPassenger' => ['rules' => 'trim'],
        'Passenger.*.Title' => [
            'rules' => 'trim|required|in_list[Mr,Mrs,Dr,Er,Miss,Master,Ms]',
            'errors' => [
                'required' => 'Title field is required.',
            ],
        ],
        'Passenger.*.FirstName' => [
            'rules' => 'trim|required|alpha_space',
            'errors' => [
                'required' => 'FirstName field is required.',
                'alpha_space' => 'FirstName field may only contain alphabetical characters.',
            ],
        ],
        'Passenger.*.LastName' => [
            'rules' => 'trim|required|alpha_space',
            'errors' => [
                'required' => 'LastName field is required.',
                'alpha_space' => 'LastName field may only contain alphabetical characters.',
            ],
        ],
        'Passenger.*.Email' => [
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Email field is required.',
                'valid_email' => 'Email not valid.',
            ],
        ],
        'Passenger.*.Phoneno' => [
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Phoneno field is required.',
                'numeric' => 'Phone number must be numeric',
            ],
        ],
        'Passenger.*.Gender' => [
            'rules' => 'trim|required|in_list[1,2]',
            'errors' => [
                'required' => 'Gender field is required.',
                'in_list' => 'Gender value only in list[1,2]',
            ],
        ],
        'Passenger.*.IdType' => ['rules' => 'trim'],
        'Passenger.*.IdNumber' => ['rules' => 'trim'],
        'Passenger.*.Address' => [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Address field is required.',
            ],
        ],
        'Passenger.*.Age' => [
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Age field is required.',
                'numeric' => 'Age field must be numeric.',
            ],
        ],
        'Passenger.*.SeatName' => [
            'rules' => 'trim|required|alpha_numeric',
            'errors' => [
                'required' => 'SeatName field is required.',
                'alpha_numeric' => 'SeatName field may only contain alpha numeric characters.',
            ],
        ],
    ];

    public $book_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'BoardingPointId' => ['rules' => 'trim|required'],
        'DroppingPointId' => ['rules' => 'trim'],

        'Passenger.*.LeadPassenger' => ['rules' => 'trim'],
        'Passenger.*.Title' => [
            'rules' => 'trim|required|in_list[Mr,Mrs,Dr,Er,Miss,Master,Ms]',
            'errors' => [
                'required' => 'Title field is required.',
            ],
        ],
        'Passenger.*.FirstName' => [
            'rules' => 'trim|required|alpha_space',
            'errors' => [
                'required' => 'FirstName field is required.',
                'alpha_space' => 'FirstName field may only contain alphabetical characters.',
            ],
        ],
        'Passenger.*.LastName' => [
            'rules' => 'trim|required|alpha_space',
            'errors' => [
                'required' => 'LastName field is required.',
                'alpha_space' => 'LastName field may only contain alphabetical characters.',
            ],
        ],
        'Passenger.*.Email' => [
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Email field is required.',
                'valid_email' => 'Email not valid.',
            ],
        ],
        'Passenger.*.Phoneno' => [
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Phoneno field is required.',
                'numeric' => 'Phone number must be numeric',
            ],
        ],
        'Passenger.*.Gender' => [
            'rules' => 'trim|required|in_list[1,2]',
            'errors' => [
                'required' => 'Gender field is required.',
                'in_list' => 'Gender value only in list[1,2]',
            ],
        ],
        'Passenger.*.IdType' => ['rules' => 'trim'],
        'Passenger.*.IdNumber' => ['rules' => 'trim'],
        'Passenger.*.Address' => [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Address field is required.',
            ],
        ],
        'Passenger.*.Age' => [
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Age field is required.',
                'numeric' => 'Age field must be numeric.',
            ],
        ],
        'Passenger.*.SeatName' => [
            'rules' => 'trim|required|alpha_numeric',
            'errors' => [
                'required' => 'SeatName field is required.',
                'alpha_numeric' => 'SeatName field may only contain alpha numeric characters.',
            ],
        ],
    ];

    public $getbookingdetail_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'BookingId' => ['rules' => 'trim|required|numeric'],
    ];

    public $cancel_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'BookingId' => ['rules' => 'trim|required'],
        'SeatId' => ['rules' => 'trim'],
        'Remarks' => ['rules' => 'trim|required'],
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

    public function amendment_validation($input)
    {
        $amendment_validation = [
            'BookingId' => ['rules' => 'trim|required|numeric'],
            'Type' => ['rules' => 'trim|required|in_list[cancellation,full_refund]'],
            'Remarks' => ['rules' => 'trim|required'],
            'RequesterInfo.Requester' => ['rules' => 'trim|required|in_list[WebPartner,SuperAdmin,Whitelabel]']
        ];

        if ($input['RequesterInfo']['Requester'] == "SuperAdmin") {
            $amendment_validation['AmendmentStatus'] = ['rules' => 'trim|required|in_list[approved,rejected]'];
            $amendment_validation['AmendmentId'] = ['rules' => 'trim|required'];
        }
        if ($input['RequesterInfo']['Requester'] == "WebPartner" || $input['RequesterInfo']['Requester'] == "Whitelabel") {
            $amendment_validation['RequesterInfo.RequesterId'] = ['rules' => 'trim|required'];
        }
        /*$amendment_validation['Sectors.*.Origin'] = array(
            'rules' => 'trim|required|alpha|exact_length[3]',
            'errors' => [
                'required' => 'Origin field is required.',
                'alpha' => 'Origin field may only contain alphabetical characters.',
                'exact_length' => 'Origin field must be exactly 3 characters in length.',
            ],
        );*/

        /*$amendment_validation['Sectors.*.Destination'] = array(
            'rules' => 'trim|required|alpha|exact_length[3]',
            'errors' => [
                'required' => 'Destination field is required.',
                'alpha' => 'Destination field may only contain alphabetical characters.',
                'exact_length' => 'Destination field must be exactly 3 characters in length.',
            ],
        );*/
        $amendment_validation['PaxId.*'] = array(
            'rules' => 'required',
            'errors' => [
                'required' => 'PaxId field is required.'
            ],
        );
        return $amendment_validation;
    }
}

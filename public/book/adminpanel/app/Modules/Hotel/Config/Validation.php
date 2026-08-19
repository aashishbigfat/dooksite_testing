<?php

namespace Modules\Hotel\Config;

class Validation
{
    public $raiseAmendment = [
        "amendment_type" => [
            'rules' => 'required|in_list[cancellation,full_refund,cancellation_quotation,correction]',
            'errors' => [
                'required' => 'Please select Amendment Type'
            ],
        ],
        "remark" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Remark'
            ]
        ],
        "booking_ref_number" => ['rules' => 'required'],
    ];
    public function pax_validation($data)
    {
        $booking_validation = [];
        foreach ($data['pax'] as $key => $requestParameter) {
            foreach ($requestParameter as $key1 => $requestParameter1) {
                foreach ($requestParameter1 as $key2 => $requestParameter2) {


                    $booking_validation["pax.$key.$key1.$key2.title"] = [
                        'label' => 'title',
                        'rules' => 'required|in_list[Mr,Ms,Mrs,Miss,Master]',
                        'errors' => [
                            'required' => 'Please select title'
                        ],
                    ];
                    $booking_validation["pax.$key.$key1.$key2.first_name"] = [
                        'label' => 'first name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please enter first name'
                        ],
                    ];

                    $booking_validation["pax.$key.$key1.$key2.last_name"] = [
                        'label' => 'last name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please enter last name'
                        ],
                    ];
                    if ($key1 == "Child") {
                        $booking_validation["pax.$key.$key1.$key2.age"] = [
                            'label' => 'age',
                            'rules' => 'required|numeric',
                            'errors' => [
                                'required' => 'Please enter age',
                                'numeric' => 'Please enter valid age'
                            ],
                        ];
                    }

                    if ($data['pancard_requird']) {
                        $booking_validation["pax.$key.$key1.$key2.pancard"] = [
                            'label' => 'pan card number',
                            'rules' => 'required|regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]',
                            'errors' => [
                                'required' => 'Please enter pan card number',
                                'regex_match' => 'Please enter valid pan card number'
                            ],
                        ];
                    }
                    if ($data['passport_requird']) {
                        $booking_validation["pax.$key.$key1.$key2.passport_no"] = [
                            'label' => 'passport number',
                            'rules' => 'required|regex_match[/^[A-PR-WYa-pr-wy][1-9]\\d\\s?\\d{4}[1-9]$/]',
                            'errors' => [
                                'required' => 'Please enter passport number',
                                'regex_match' => 'Please enter valid passport number',
                            ],
                        ];
                        $booking_validation["pax.$key.$key1.$key2.passport_issue_date"] = [
                            'label' => 'passport issue date',
                            'rules' => 'required|valid_date[dd-m-yy]',
                            'errors' => [
                                'required' => 'Please enter passport issue date',
                                'valid_date' => 'Please select valid passport issue date'
                            ],
                        ];
                        $booking_validation["pax.$key.$key1.$key2.passport_expire_date"] = [
                            'label' => 'passport expire date',
                            'rules' => 'required|valid_date[dd-m-yy]',
                            'errors' => [
                                'required' => 'Please enter passport expire date',
                                'valid_date' => 'Please select valid passport expire date'
                            ],
                        ];
                    }
                }
            }
        }
        $booking_validation['ResultIndex'] = [
            'label' => 'ResultIndex',
            'rules' => 'required'
        ];
        $booking_validation['SearchTokenId'] = [
            'label' => 'SearchTokenId',
            'rules' => 'required'
        ];
        $booking_validation['hcode'] = [
            'label' => 'hcode',
            'rules' => 'required'
        ];
        $booking_validation['rtype'] = [
            'label' => 'rtype|in_list[blcrm]',
            'rules' => 'required'
        ];
        $booking_validation['email'] = [
            'label' => 'email',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ];
        $booking_validation['mobile_number'] = [
            'label' => 'mobile number',
            'rules' => 'required|numeric|min_length[7]|max_length[15]',
            'errors' => [
                'numeric' => 'Please enter valid mobile number',
                'required' => 'Please enter mobile number'
            ],
        ];
        $booking_validation['dial_code'] = [
            'label' => 'dial code',
            'rules' => 'required|numeric',
            'errors' => [
                'numeric' => 'Please select dial code'
            ],
        ];
        if ($data['add_gst_detail'] == 'true') {
            $booking_validation['gst.email'] = [
                'label' => 'email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Please enter email',
                    'valid_email' => 'Please enter valid email'
                ],
            ];
            $booking_validation['gst.name'] = [
                'label' => 'company name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter company name'
                ],
            ];

            $booking_validation['gst.phone'] = [
                'label' => 'mobile number',
                'rules' => 'required|numeric|min_length[7]|max_length[15]',
                'errors' => [
                    'required' => 'Please enter mobile number'
                ],
            ];

            $booking_validation['gst.address'] = [
                'label' => 'address',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter address'
                ],
            ];

            $booking_validation['gst.number'] = [
                'label' => 'gst number',
                'rules' => 'required|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
                'errors' => [
                    'required' => 'Please enter gst number'
                ],
            ];
        }
        return $booking_validation;
    }
    public $EmailVoucherValidation = [
        "email" => [
            'label' => 'email',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ]
    ];

    public function voucher_update_validation($data)
    {
        $booking_validation["supplier"] = [
            'label' => 'Supplier',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Supplier'
            ],
        ];
        $booking_validation["confirmation_number"] = [
            'label' => 'Confirmation Number',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter confirmation number'
            ],
        ];
        $booking_validation["booking_status"] = [
            'label' => 'Booking Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select booking status',
            ],
        ];
        return $booking_validation;
    }

    public $search_validation = [
        'slug' => [
            'label' => 'Holidays',
            'rules' => 'required',
            'errors' => [
                'required' => 'Destinations Themes Holidays'
            ],
        ],
    ];

    public $block_validation = [
        'travel_date' => [
            'label' => 'travel date',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select travel date'
            ],
        ],
        'txt_num_rooms' => [
            'label' => 'rooms',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select rooms'
            ],
        ],
    ];

    public $query_validation = [
        'name' => [
            'label' => 'name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter name'
            ],
        ],
        'mobile' => [
            'label' => 'mobile',
            'rules' => 'required|numeric|min_length[10]|max_length[18]',
            'errors' => [
                'required' => 'Please enter contact number'
            ],
        ],
        'email' => [
            'label' => 'email',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Please enter email'
            ],
        ],

        'travel_date' => [
            'label' => 'travel date',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select travel date'
            ],
        ],
        'no_of_nights' => [
            'label' => 'duration',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select duration'
            ],
        ],
        'no_of_person' => [
            'label' => 'person',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select person'
            ],
        ],
        'comment' => [
            'label' => 'comment',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter comment'
            ],
        ],
    ];


    public $hotel_markup_validation = [

        'agent_class.*' => [
            'label' => 'Agent class',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Agent class'
            ],
        ],
        'markup_for' => [
            'label' => 'Markup for',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select markup for'
            ],
        ],


        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],


        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'region_type.*' => [
            'label' => 'region type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select region type'
            ],
        ],

        'hotel_markup_type' => [
            'label' => 'hotel markup type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select hotel markup type'
            ],
        ],

        'star_rating.*' => [
            'label' => 'star rating',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select star rating'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $hotel_discount_validation = [


        'agent_class.*' => [
            'label' => 'agent class',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],
        'discount_for' => [
            'label' => 'Discount for',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select discount for'
            ],
        ],

        'region_type.*' => [
            'label' => 'region type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select region type'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'extra_discount' => [
            'label' => 'extra discount',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter extra discount'
            ],
        ],


        'max_limit' => [
            'label' => 'max limit',
            'rules' => 'permit_empty|numeric',
        ],


        'status' => [
            'label' => ' Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $status = [
        'status' => [
            'label' => ' Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];



    public $amendment_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'admin_remark' => [
            'label' => 'remark',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];


    public $amendment_refund_validation = [

        'amendment_id' => [
            'label' => 'AmendmentId',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter AmendmentId'
            ],
        ],
        'charge' => [
            'label' => 'charge',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter charge',
                'numeric' => 'Please enter valid charge',
                'greater_than_equal_to' => 'Please enter valid charge'
            ],
        ],
        'service_charge' => [
            'label' => 'service charge',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge',
                'numeric' => 'Please enter service valid charge',
                'greater_than_equal_to' => 'Please enter service valid charge'
            ],
        ],
        'service_charge_gst' => [
            'label' => 'service charge gst',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge gst',
                'numeric' => 'Please enter service valid charge gst',
                'greater_than_equal_to' => 'Please enter service valid charge gst'
            ],
        ],

    ];

    public $refund_close_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'account_remark' => [
            'label' => 'remark',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];



}

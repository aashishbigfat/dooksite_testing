<?php

namespace Modules\Hotel\Config;

class Validation
{
    public $raiseAmendment = [
        "amendment_type" => ['rules' => 'required|in_list[cancellation,full_refund,cancellation_quotation,correction]', 'errors' => [
            'required' => 'Please select Amendment Type'
        ],],
        "remark" => ['rules' => 'required', 'errors' => [
            'required' => 'Please enter Remark'
        ]],
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
                            'rules' => 'required',
                            'errors' => [
                                'required' => 'Please enter passport issue date', 
                            ],
                        ];
                        $booking_validation["pax.$key.$key1.$key2.passport_expire_date"] = [
                            'label' => 'passport expire date',
                            'rules' => 'required',
                            'errors' => [
                                'required' => 'Please enter passport expire date', 
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
    public $promocodeValidation = [
        "couponCode" => [
            'label' => 'couponCode',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter coupon code',
            ],
        ]
    ];
}

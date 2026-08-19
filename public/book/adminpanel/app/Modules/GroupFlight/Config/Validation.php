<?php

namespace Modules\GroupFlight\Config;

class Validation
{

    public $airport_validation = [
        'code' => [
            'label' => 'code',
            'rules' => "required|exact_length[3]|alpha|is_unique[flight_airports.code]",
            'errors' => [
                'required' => 'Please enter airport code'

            ],
        ],

        'name' => [
            'label' => 'airport name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter airport name'

            ],
        ],

        'city_name' => [
            'label' => 'city name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter city name'

            ],
        ],

        'city_code' => [
            'label' => 'city code',
            'rules' => 'required|exact_length[3]|alpha',
            'errors' => [
                'required' => 'Please enter city code'

            ],
        ],

        'country_name' => [
            'label' => 'country',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter country'
            ],
        ],

        'country_code' => [
            'label' => 'country code',
            'rules' => 'required|exact_length[2]|alpha',
            'errors' => [
                'required' => 'Please enter country code'

            ],
        ],
    ];

    public $airline_validation = [
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'required|is_unique[flight_airline_code.airline_code]|exact_length[2]|alpha_numeric',
            'errors' => [
                'required' => 'Please enter airline code'

            ],
        ],
        'airline_name' => [
            'label' => 'airline name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter airline name'

            ],
        ],

        'images' => [
            'label' => 'Airline Image',
            'rules' => 'uploaded[images]|max_size[images,100]|ext_in[images,png]|mime_in[images,image/png]|is_image[images]|max_dims[images,48,48]',
            'errors' => [
                'uploaded' => 'Please upload an image file',
                'max_size' => 'Image size should not be more than 100KB',
                'ext_in' => 'Only PNG image files are allowed',
                'mime_in' => 'Please upload a valid PNG image file',
                'is_image' => 'Uploaded file is not a valid image',
                'max_dims' => 'Image dimensions should be exactly 48x48 pixels'
            ],
        ],

    ];

    public $raiseAmendment = [
        "amendment_type" => [
            'rules' => 'required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]',
            'errors' => [
                'required' => 'Please select Amendment Type'
            ],
        ],
        "booking_ref_number" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter booking reference number'
            ]
        ],
    ];
    public $raiseAmendmentType = [
        "amendment_type" => [
            'rules' => 'required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]',
            'errors' => [
                'required' => 'Please select Amendment Type'
            ],
        ],
        "booking_ref_number" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter booking refrence number'
            ]
        ],
        "remark" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter remark'
            ]
        ],
        "passengers.*" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select passengers'
            ]
        ],
    ];

    public $flight_offline = [
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
            ],
        ],

        'from_airport_code' => [
            'label' => 'from airport',
            'rules' => 'required|max_length[3]',
            'errors' => [
                'required' => 'Please enter from airport'
            ],
        ],

        'departure_days' => [
            'label' => 'departure days',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter departure days'
            ],
        ],
        'supplier.*' => [
            'label' => 'supplier',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select supplier'
            ],
        ],
        'is_domestic.*' => [
            'label' => 'is_domestic',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select fare type'
            ],
        ],
        'faretype.*' => [
            'label' => 'faretype',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],
        'cabin_class.*' => [
            'label' => 'cabin_class',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],
        'to_airport_code' => [
            'label' => 'to airport',
            'rules' => 'required|max_length[3]',
            'errors' => [
                'required' => 'Please enter to airport'
            ],
        ],
        'booking_class' => [
            'label' => 'to airport',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],
        'is_hold' => [
            'label' => 'is hold',
            'rules' => 'max_length[10]',
        ],
        'is_offline' => [
            'label' => 'is offline',
            'rules' => 'max_length[10]',
        ],
        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];




    public function flight_discount_markup_validation($data)
    {
        $flight_discount_markup_validation = [
            'airline_code' => [
                'label' => 'airline code',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Please enter airline code'
                ],
            ],
            'from_airport_code' => [
                'label' => 'from airport',
                'rules' => 'required|max_length[250]',
                'errors' => [
                    'required' => 'Please enter from airport'
                ],
            ],
            'to_airport_code' => [
                'label' => 'to airport',
                'rules' => 'required|max_length[250]',
                'errors' => [
                    'required' => 'Please enter to airport'
                ],
            ],
            'booking_class' => [
                'label' => 'Booking Class',
                'rules' => 'required|max_length[250]',
                'errors' => [
                    'required' => 'Please enter booking class'
                ],
            ],


            'travel_date_from' => [
                'label' => 'from date',
                'rules' => 'required|max_length[25]',
                'errors' => [
                    'required' => 'Please select from date'
                ],
            ],
            'travel_date_to' => [
                'label' => 'to date',
                'rules' => 'required|max_length[25]',
                'errors' => [
                    'required' => 'Please select to date'
                ],
            ],
            'journey_type' => [
                'label' => 'journey type',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select journey type',

                ],
            ],

            'is_domestic' => [
                'label' => 'flight type',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select flight type',

                ],
            ],
            'cabin_class.*' => [
                'label' => 'cabin class',
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Please select cabin class'
                ],
            ],
            'faretype.*' => [
                'label' => 'fare type',
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Please select fare type'
                ],
            ],
            'value' => [
                'label' => 'value',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter value'
                ],
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required|in_list[active,inactive]',
                'errors' => [
                    'required' => 'Please select status',
                    'in_list' => 'Invalid Markup For value.',
                ],
            ],

        ];

        // Conditionally add validation rules

        if (isset($data['agent_class']) && $data['agent_class'] && !empty($data['agent_class']) && ((isset($data['markup_for']) && $data['markup_for']) == "B2B" || (isset($data['discount_for']) && $data['discount_for']) == "B2B")) {
            $flight_discount_markup_validation['agent_class.*'] = [
                'label' => 'Agent Class',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select agent class'
                ],
            ];
        }

        if ($data['max_limit'] || (isset($data['markup_type']) && $data['markup_type'] === "percent")) {
            $flight_discount_markup_validation['max_limit'] = [
                'label' => 'Maximum limit',
                'rules' => 'required|numeric|is_natural',
                'errors' => [
                    'required' => 'Please enter maximum limit',
                    'numeric' => 'Maximum limit must be a number',
                    'is_natural' => 'Maximum limit must be a natural number (0 or positive integer)'
                ],
            ];
        }

        if ((isset($data['markup_type']) && $data['markup_type']) && !empty($data['markup_type'])) {
            $flight_discount_markup_validation['markup_type'] = [
                'label' => 'markup type',
                'rules' => 'required|in_list[percent,fixed]',
                'errors' => [
                    'required' => 'Please select markup type',
                    'in_list' => 'Invalid Markup For value.',
                ],
            ];
        }

        if ($data['max_limit'] || (isset($data['discount_type']) && $data['discount_type'] === "percent")) {
            $flight_discount_markup_validation['max_limit'] = [
                'label' => 'Maximum limit',
                'rules' => 'required|numeric|is_natural',
                'errors' => [
                    'required' => 'Please enter maximum limit',
                    'numeric' => 'Maximum limit must be a number',
                    'is_natural' => 'Maximum limit must be a natural number (0 or positive integer)'
                ],
            ];
        }

        if ((isset($data['extra_discount']) && $data['extra_discount']) && !empty($data['extra_discount'])) {
            $flight_discount_markup_validation['extra_discount'] = [
                'label' => 'extra_discount',
                'rules' => 'required|numeric|is_natural',
                'errors' => [
                    'required' => 'Please enter extra discount',
                    'numeric' => 'extra discount must be a number',
                    'is_natural' => 'extra discount must be a natural number (0 or positive integer)'
                ],
            ];
        }
        if ((isset($data['discount_for']) && $data['discount_for']) && !empty($data['discount_for'])) {
            $flight_discount_markup_validation['discount_for'] = [
                'label' => 'markup for',
                'rules' => 'required|in_list[B2B,B2C]',
                'errors' => [
                    'required' => 'Please select markup for',
                    'in_list' => 'Invalid markup for type'
                ],
            ];
        }







        return $flight_discount_markup_validation;
    }












    public $status = [

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];
    public function ticket_update_validation($data)
    {
        $booking_validation = [];

        $booking_validation["supplier"] = [
            'label' => 'Supplier',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Supplier',
            ],
        ];

        foreach ($data['pax'] as $key => $requestParameter) {



            $booking_validation["pax.$key.pnr"] = [

                'label' => 'Pnr',

                'rules' => 'required|alpha_numeric|min_length[6]|max_length[10]',

                'errors' => [

                    'required' => 'Please enter pnr',

                    'alpha_numeric' => 'Pnr contain only alpha numeric character',

                    'exact_length' => 'Please enter  exact  six digit long pnr',
                    'min_length' => 'Please enter  min  6 digit long pnr',
                    'max_length' => 'Please enter  max  10 digit long pnr',

                ],

            ];

            $booking_validation["pax.$key.ticket_number"] = [

                'label' => 'Ticket Number',

                'rules' => 'required|alpha_numeric',

                'errors' => [

                    'required' => 'Please enter Ticket Number',

                    'alpha_numeric' => 'Ticket number contain only alpha numeric character',

                ],

            ];

            $booking_validation["pax.$key.booking_status"] = [
                'label' => 'Booking Status',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select booking status',
                ],
            ];
        }

        return $booking_validation;
    }
    public $EmailTicketValidation = [
        "email" => [
            'label' => 'email',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ]
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
        'charge.*.charge' => [
            'label' => 'charge',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter charge',
                'numeric' => 'Please enter valid charge',
                'greater_than_equal_to' => 'Please enter valid charge'
            ],
        ],
        'charge.*.service_charge' => [
            'label' => 'service charge',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge',
                'numeric' => 'Please enter service valid charge',
                'greater_than_equal_to' => 'Please enter service valid charge'
            ],
        ],
        'charge.*.service_charge_gst' => [
            'label' => 'service charge gst',
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge gst',
                'numeric' => 'Please enter service valid charge gst',
                'greater_than_equal_to' => 'Please enter service valid charge gst'
            ],
        ],
        'charge.*.pax_id' => [
            'label' => 'paxId',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter paxId'
            ],
        ]

    ];

    public $api_fairtype_validation = [
        'supplier_fare_type' => [
            'label' => 'Supplier Fare Type',
            'rules' => 'required|is_unique[api_flight_fare_type.supplier_fare_type]',
            'errors' => [
                'required' => 'Please enter Supplier Fare Type'

            ],
        ],
        'api_fare_type' => [
            'label' => 'API Fare Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter API Fare Type'
            ],
        ],
        'api_supplier' => [
            'label' => 'API Supplier',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter API Supplier'
            ],
        ],
    ];


    public $webcheckin_validation = [

        'airline_name' => [
            'label' => 'airline code',
            'rules' => 'required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline name code'
            ],
        ],
        'url' => [
            'label' => 'URL',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter URL'
            ],
        ],

        'image' => [
            'label' => 'Image',
            'rules' => 'uploaded[image]|max_size[image,500]|mime_in[image,image/jpg,image/jpeg,image/png]|is_image[image]|max_dims[image,200,200]',
            'errors' => [
                'uploaded' => 'Please upload an image file',
                'max_size' => 'Image size should not be more than 500KB',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png',
                'is_image' => 'Uploaded file is not a valid image',
                'max_dims' => 'Image dimensions should be exactly 200x200 pixels'
            ],
        ],

    ];
}

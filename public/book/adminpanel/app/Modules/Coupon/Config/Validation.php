<?php

namespace Modules\Coupon\Config;

class Validation
{

    public $flight_discount_markup_validation = [

        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'required|max_length[50]',
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

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'numeric',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],

        'is_domestic.*' => [
            'label' => 'flight type',
            'rules' => 'required|exact_length[1]',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],

        'journey_type.*' => [
            'label' => 'journey type',
            'rules' => 'required|max_length[20]',
            'errors' => [
                'required' => 'Please select journey type'
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

        'valid_from' => [
            'label' => 'Valid From',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'valid_to' => [
            'label' => 'Valid To',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],



        'cabin_class.*' => [
            'label' => 'cabin class',
            'rules' => 'required|max_length[20]',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],


        'coupon_type' => [
            'label' => 'Coupon Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Code'
            ],
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],
    ];

    public $status = [

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];

    public $hotel_coupon_validation = [

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'numeric',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],


        'region_type.*' => [
            'label' => 'Region type',
            'rules' => 'required|max_length[20]',
            'errors' => [
                'required' => 'Please select Region type'
            ],
        ],

        'star_rating.*' => [
            'label' => 'Star Rating',
            'rules' => 'required|max_length[20]',
            'errors' => [
                'required' => 'Please select Star rating'
            ],
        ],

        'check_in_date_from' => [
            'label' => 'check in date',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'check_out_date_to' => [
            'label' => 'check out date',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Code'
            ],
        ],

        'valid_from' => [
            'label' => 'Valid From',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'valid_to' => [
            'label' => 'Valid To',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],


        'coupon_type' => [
            'label' => 'Coupon Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],
    ];

    public $bus_coupon_validation = [

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'numeric',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],

        'travel_date_from' => [
            'label' => 'travel date from',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date_to' => [
            'label' => 'travel date to',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'valid_from' => [
            'label' => 'Valid From',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'valid_to' => [
            'label' => 'Valid To',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],


        'coupon_type' => [
            'label' => 'Coupon Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],

        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Code'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],
    ];


    public $holiday_coupon_validation = [


        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
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

        'theme_name.*' => [
            'label' => 'theme',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  theme'
            ],
        ],

        'destination_name.*' => [
            'label' => 'destination',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  destination'
            ],
        ],
        'holiday_package.*' => [
            'label' => 'holiday package',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  holiday package'
            ],
        ],
        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  code'
            ],
        ],
        'use_limit' => [
            'label' => 'Use limit',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Use limit'
            ],
        ],
        'coupon_desc' => [
            'label' => 'Coupon Desc',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Desc'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Visible'
            ],
        ],
    ];



    public $tourguide_coupon_validation = [


        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
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


        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  code'
            ],
        ],
        'use_limit' => [
            'label' => 'Use limit',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Use limit'
            ],
        ],
        'coupon_desc' => [
            'label' => 'Coupon Desc',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Desc'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Visible'
            ],
        ],
    ];




    public $activities_coupon_validation = [


        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
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


        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  code'
            ],
        ],
        'use_limit' => [
            'label' => 'Use limit',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Use limit'
            ],
        ],
        'coupon_desc' => [
            'label' => 'Coupon Desc',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Desc'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Visible'
            ],
        ],
    ];



    public $visa_coupon_validation = [


        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
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


        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  code'
            ],
        ],
        'use_limit' => [
            'label' => 'Use limit',
            'rules' => 'required|greater_than[0]',
            'errors' => [
                'required' => 'Please select Use limit',
                'greater_than' => 'Use limit must be at least 1'
            ],
        ],

        'coupon_desc' => [
            'label' => 'Coupon Desc',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Desc'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  Coupon Visible'
            ],
        ],

        'visa_country_id' => [
            'label' => 'country ',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select country'

            ],
        ],

        'visa_type_id.*' => [
            'label' => 'visa type id',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select visa type'

            ],
        ],

    ];


    public $car_coupon_validation = [
        'minm_order' => [
            'label' => 'Minm Order',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],
        'maxm_order' => [
            'label' => 'Maxm Order',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],
        'minm_car' => [
            'label' => 'Minm Cars',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],
        'maxm_car' => [
            'label' => 'Maxm Cars',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],

        'travel_date_from' => [
            'label' => 'travel date from',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date_to' => [
            'label' => 'travel date to',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'valid_from' => [
            'label' => 'Valid From',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'valid_to' => [
            'label' => 'Valid To',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],


        'coupon_type' => [
            'label' => 'Coupon Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],

        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Code'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],
    ];



    public $cruise_coupon_validation = [

        'departure_port_id' => [
            'label' => 'Cruise Departure Port',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Cruise Departure Port'
            ],
        ],

        'cruise_line_id' => [
            'label' => 'Cruise Line',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Cruise Line'
            ],
        ],

        'cruise_ship_id' => [
            'label' => 'Cruise Ship',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Cruise Ship'
            ],
        ],

        'cabin_id' => [
            'label' => 'Cruise Cabin',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select Cruise Cabin'
            ],
        ],

        'travel_from' => [
            'label' => 'travel date from',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'numeric',
            'errors' => [
                'numeric' => 'Please enter numeric value'
            ],
        ],

        'travel_from' => [
            'label' => 'travel date from',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date' => [
            'label' => 'travel date to',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'valid_from' => [
            'label' => 'Valid From',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'valid_to' => [
            'label' => 'Valid To',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],


        'coupon_type' => [
            'label' => 'Coupon Type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],

        'code' => [
            'label' => 'code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter Code'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'coupon_visible' => [
            'label' => 'Coupon Visible',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select value'
            ],
        ],
    ];



    public $umrah_coupon_validation = [
        'value' => [
            'label' => 'Value',
            'rules' => 'required|numeric',
        ],

        'travel_date_from' => [
            'label' => 'Travel Date From',
            'rules' => 'required',
        ],

        'travel_date_to' => [
            'label' => 'Travel Date To',
            'rules' => 'required',
        ],

        'valid_from' => [
            'label' => 'Booking From Date',
            'rules' => 'required',
        ],

        'valid_to' => [
            'label' => 'Booking To Date',
            'rules' => 'required',
        ],

        'code' => [
            'label' => 'Code',
            'rules' => 'required|is_unique[coupon_umrah_package.code]',
        ],

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'permit_empty|numeric',
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
        ],

        /* 'theme_name.*' => [
            'label' => 'Theme',
            'rules' => 'required',
        ],

        'destination_name.*' => [
            'label' => 'Destination',
            'rules' => 'required',
        ], */

        'umrah_package.*' => [
            'label' => 'Package Name',
            'rules' => 'required',
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
        ],

        // 'coupon_desc' => [
        //     'label' => 'Coupon Desc',
        //     'rules' => 'required',
        // ],

        'minm_order' => [
            'label' => 'Minm Order',
            'rules' => 'required',
        ],
        'maxm_order' => [
            'label' => 'Maxm Order',
            'rules' => 'required',
        ],
        'minm_pax' => [
            'label' => 'Minm Pax',
            'rules' => 'required',
        ],
        'maxm_pax' => [
            'label' => 'Maxm Pax',
            'rules' => 'required',
        ],

    ];


    /* Hajj Coupon Starts */
    public $hajj_coupon_validation = [

        'value' => [
            'label' => 'Value',
            'rules' => 'required|numeric',
        ],

        'travel_date_from' => [
            'label' => 'Travel Date From',
            'rules' => 'required',
        ],

        'travel_date_to' => [
            'label' => 'Travel Date To',
            'rules' => 'required',
        ],

        'valid_from' => [
            'label' => 'Booking From Date',
            'rules' => 'required',
        ],

        'valid_to' => [
            'label' => 'Booking To Date',
            'rules' => 'required',
        ],

        'code' => [
            'label' => 'Code',
            'rules' => 'required|is_unique[coupon_umrah_package.code]',
        ],

        'max_limit' => [
            'label' => 'Max Limit',
            'rules' => 'permit_empty|numeric',
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
        ],

        /* 'theme_name.*' => [
            'label' => 'Theme',
            'rules' => 'required',
        ],

        'destination_name.*' => [
            'label' => 'Destination',
            'rules' => 'required',
        ], */

        'package_list.*' => [
            'label' => 'Package Name',
            'rules' => 'required',
        ],

        'use_limit' => [
            'label' => 'Use Limit',
            'rules' => 'required|numeric',
        ],

        // 'coupon_desc' => [
        //     'label' => 'Coupon Desc',
        //     'rules' => 'required',
        // ],

        'minm_order' => [
            'label' => 'Minm Order',
            'rules' => 'required',
        ],
        'maxm_order' => [
            'label' => 'Maxm Order',
            'rules' => 'required',
        ],
        'minm_pax' => [
            'label' => 'Minm Pax',
            'rules' => 'required',
        ],
        'maxm_pax' => [
            'label' => 'Maxm Pax',
            'rules' => 'required',
        ],
    ];
    /* Hajj Coupon Ends */
}

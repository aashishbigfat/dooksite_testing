<?php
namespace Modules\Cruise\Config;


class Validation
{
    public $cruise_line_validation = [
        'cruise_line_name' => [
            'label' => 'cruise line name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please cruise line name'

            ],
        ],

        'cruise_line_name_slug' => [
            'label' => 'cruise line name slug',
            'rules' => 'trim|required|max_length[90]',
            'errors' => [
                'required' => 'Please enter cruise line name slug'

            ],
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],

        'cruise_line_image' => [
            'label' => 'Image',
            'rules' => 'uploaded[cruise_line_image]|max_size[cruise_line_image,1024]|mime_in[cruise_line_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],

        ],

    ];

    public function voucher_update_validation($data)
    {
                   $booking_validation["confirmation_number"] = [
                        'label' => 'Confirmation Number',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter confirmation number'
                        ],
                    ];
                   $booking_validation["booking_status"] = [
                        'label' => 'Booking Status',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please select booking status',
                        ],
                    ];
        return $booking_validation;
    }

    public $cruise_list_validation = [


        'cruise_ocean_id' => [
            'label' => 'cruise ocean',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ocean'
            ],
        ],

        'departure_port_id' => [
            'label' => 'departure port',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select departure port'
            ],
        ],

        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'
            ],
        ],

        'adult_passport' => [
            'label' => 'adult passport',
            'rules' => 'trim',
        ],

        'child_passport' => [
            'label' => 'child passport',
            'rules' => 'trim',
        ],




        'starting_price' => [
            'label' => 'starting price',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please starting price'
            ],
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],

        'cruise_itinerary.city.*' => [
            'label' => 'itinerary city',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter itinerary city'
            ],
        ],
        'cruise_itinerary.time_duration.*' => [
            'label' => 'itinerary Time / Duration',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Time / Duration'
            ],
        ],
        'cruise_itinerary.description.*' => [
            'rules' => 'trim|max_length[250]',
        ],

    ];


    public $cruise_ship_validation = [
        'ship_name' => [
            'label' => 'cruise ship name',
            'rules' => 'trim|required|max_length[90]',
            'errors' => [
                'required' => 'Please cruise ship name'

            ],
        ],

        'ship_name_slug' => [
            'label' => 'cruise ship name slug',
            'rules' => 'trim|required|max_length[100]',
            'errors' => [
                'required' => 'Please enter cruise line name slug'

            ],
        ],

        'ship_description' => [
            'label' => 'cruise ship description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter cruise ship description'
            ],
        ],

        'cancellation_policy' => [
            'label' => 'cancellation policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter cancellation policy'
            ],
        ],

        'payment_policy' => [
            'label' => 'payment policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter payment policy'
            ],
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],

        'ship_image' => [
            'label' => 'Image',
            'rules' => 'uploaded[ship_image]|max_size[ship_image,1024]|mime_in[ship_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],

        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'
            ],
        ],

    ];

    public $cruise_ship_gallery_validation = [
        'image_title' => [
            'label' => 'image title',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter image title'

            ],
        ],

        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'
            ],
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],

        'images' => [
            'label' => 'Image',
            'rules' => 'uploaded[images]|max_size[images,1024]|mime_in[images,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
    ];



    public $cruise_cabin_validation = [
        'cabin_name' => [
            'label' => 'cabin name',
            'rules' => 'trim|required|max_length[90]',
            'errors' => [
                'required' => 'Please enter cabin name'
            ],
        ],
        'cabin_slug' => [
            'label' => 'cabin slug',
            'rules' => 'trim|required|max_length[100]',
            'errors' => [
                'required' => 'Please enter cabin slug'
            ],
        ],
        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'
            ],
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],
    ];


    public $cruise_price_validation = [
        'selling_date' => [
            'label' => 'selling date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select selling date'
            ],
        ],
        'cruise_cabin_id' => [
            'label' => 'cabin cabin',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please select cabin '
            ],
        ],
        'twin_pax_price' => [
            'label' => 'twin pax price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter twin pax price'
            ],
        ],

        'single_pax_price' => [
            'label' => 'single pax price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter single pax price'
            ],
        ],

        'port_charges' => [
            'label' => 'port charges',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter port charges'
            ],
        ],

        'max_pax_stay' => [
            'label' => 'max pax stay',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please select max pax stay'
            ],
        ],

        'book_online' => [
            'label' => 'book online',
            'rules' => 'trim',
        ],
        
        'available_cabin' => [
            'label' => 'available cabin',
            'rules' => 'trim|required',
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'

            ],
        ],
    ];


    public $status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];
    public $cruise_ocean_validation = [

        'ocean_name' => [
            'label' => 'ocean name',
            'rules' => 'trim|required|max_length[100]',
            'errors' => [
                'required' => 'Please enter ocean name'
            ],
        ],
        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],

    ];


    public $cruise_port_validation = [

        'port_name' => [
            'label' => 'port name',
            'rules' => 'trim|required|max_length[100]',
            'errors' => [
                'required' => 'Please enter port name'
            ],
        ],

        'cruise_ocean_id' => [
            'label' => 'cruise ocean',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ocean'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],

    ];

    public $amendment_refund_validation = [

        'amendment_id' => [
            'label' => 'AmendmentId',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter AmendmentId'
            ],
        ],
        'charge' => [
            'label' => 'charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter charge',
                'numeric' => 'Please enter valid charge',
                'greater_than_equal_to' => 'Please enter valid charge'
            ],
        ],
        'service_charge' => [
            'label' => 'service charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge',
                'numeric' => 'Please enter service valid charge',
                'greater_than_equal_to' => 'Please enter service valid charge'
            ],
        ],
        'service_charge_gst' => [
            'label' => 'service charge gst',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge gst',
                'numeric' => 'Please enter service valid charge gst',
                'greater_than_equal_to' => 'Please enter service valid charge gst'
            ],
        ],


    ];

    public $EmailTicketValidation = [
        "email" => [
            'label' => 'email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ]
    ];
    public $amendment_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'admin_remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];
    public $refund_close_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'account_remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];

    public $cruise_markup_validation = [

        'agent_class.*' => [
            'label' => 'agent class ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'

            ],
        ],
        'markup_for' => [
            'label' => 'markup for ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup for'

            ],
        ],
       
        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'

            ],
        ],

        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'

            ],
        ],
        'departure_port_id' => [
            'label' => 'departure port',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select departure port'
            ],
        ],

        'cabin_id' => [
            'label' => 'cruise cabin',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise cabin'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'max_limit' => [
            'label' => 'max limit',
            'rules' => 'trim|permit_empty|numeric',
        ],

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];


    public $cruise_discount_validation = [

        'agent_class.*' => [
            'label' => 'agent class ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'

            ],
        ],
        'discount_for' => [
            'label' => 'discount for ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount for'

            ],
        ],
       
        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'

            ],
        ],

        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'

            ],
        ],
        'departure_port_id' => [
            'label' => 'departure port',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select departure port'
            ],
        ],

        'cabin_id' => [
            'label' => 'cruise cabin',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise cabin'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'max_limit' => [
            'label' => 'max limit',
            'rules' => 'trim|permit_empty|numeric',
        ], 
        'discount_type' => [
            'label' => 'discount type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount type'
            ],
        ],
 
        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];


}


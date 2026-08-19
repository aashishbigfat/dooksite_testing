<?php

namespace Modules\HotelExtranet\Config;

class Validation
{
    public $hotel_list_validation = [
        'hotel_name' => [
            'label' => 'hotel name',
            'rules' => 'required|max_length[90]',
            'errors' => [
                'required' => 'Please select hotel name'

            ],
        ],

        'hotel_city' => [
            'label' => 'processing time',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select hotel city'

            ],
        ],

        'hotel_property_type_id' => [
            'label' => 'hotel property type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select hotel property type'
            ],
        ],
        'hotel_star_rating' => [
            'label' => 'star rating',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select star rating'
            ],
        ],
        // 'hotel_promotion' => [
        //     'label' => 'hotel promotion',
        //     'rules' => 'required',
        //     'errors' => [
        //         'required' => 'Please enter hotel promotion'

        //     ],
        // ],

        'hotel_description' => [
            'label' => 'hotel description',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter hotel description'
            ],
        ],

        'check_in_time' => [
            'label' => 'checkin time',
            'rules' => 'max_length[12]',
            'errors' => [
                'required' => 'Please enter checkin time'
            ],
        ],



        'check_out_time' => [
            'label' => 'checkout time',
            'rules' => 'max_length[12]',
            'errors' => [
                'required' => 'Please enter checkout time'
            ],
        ],



        'latitude' => [
            'label' => 'latitude',
            'rules' => 'permit_empty|regex_match[/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]',
            'errors' => [
                'required' => 'Please enter latitude'
            ],
        ],

        'longitude' => [
            'label' => 'longitude',
            'rules' => 'permit_empty|regex_match[/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]',
            'errors' => [
                'required' => 'Please enter longitude'
            ],
        ],

        'review_rating' => [
            'label' => 'review rating ',
            'rules' => 'permit_empty|decimal|max_length[3]|regex_match[/^(?=.*[1-4])(?!0\d)([0-4]{1,4})(\.[0-9]{1,9})$/]',
            'errors' => [
                'required' => 'Please enter review rating',
                'max_length' => 'Please Enter Valid rating Ex. 3.2',
                'regex_match' => 'Please Enter Valid rating Ex. 3.2',
            ],
        ],

        'review_url' => [
            'label' => 'review url',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter review url'
            ],
        ],

        'address' => [
            'label' => 'Address',
            'rules' => 'max_length[254]',
            'errors' => [
                'required' => 'Please enter address'
            ],
        ],

        'state' => [
            'label' => 'State',
            'rules' => 'max_length[80]',
            'errors' => [
                'required' => 'Please enter state'
            ],
        ],

        'city_name' => [
            'label' => 'city name',
            'rules' => 'max_length[80]',
            'errors' => [
                'required' => 'Please enter city name'
            ],
        ],

        'postal_code' => [
            'label' => 'postal code',
            'rules' => 'max_length[15]',
            'errors' => [
                'required' => 'Please enter postal code'
            ],
        ],

        'country_name' => [
            'label' => 'country name',
            'rules' => 'max_length[80]',
            'errors' => [
                'required' => 'Please enter country name'
            ],
        ],


        'location_area' => [
            'label' => 'location area',
            'rules' => 'max_length[110]',
            'errors' => [
                'required' => 'Please enter location area'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
        'trading_hotel' => [
            'label' => ' Trading Hotel',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  trading hotel'
            ],
        ],

        'hotel_images' => [
            'label' => 'Image',
            'rules' => 'uploaded[hotel_images]|max_size[hotel_images,1024]|mime_in[hotel_images,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],

        ],
    ];

    public $status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];
    public $hotel_property_type = [

        'property_type' => [
            'label' => 'property type',
            'rules' => 'required|max_length[80]',
            'errors' => [
                'required' => 'Please enter property type'
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

    public $room_gallery_validation = [
        'image_title' => [
            'label' => 'image title',
            'rules' => 'trim',
        ],
        'room_gallery' => [
            'label' => 'Image',
            'rules' => 'uploaded[room_gallery]|max_size[room_gallery,1024]|mime_in[room_gallery,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],

    ];


    public $hotel_amenity = [

        'amenity_title' => [
            'label' => 'amenity title',
            'rules' => 'required|max_length[255]',
            'errors' => [
                'required' => 'Please enter amenity title'
            ],
        ],

        'amenity_type' => [
            'label' => 'amenity type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select amenity type'
            ],
        ],
        'amenity_icon' => [
            'label' => 'Image',
            'rules' => 'uploaded[amenity_icon]|max_size[amenity_icon,256]|max_dims[amenity_icon,64,64]|mime_in[amenity_icon,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_dims' => 'Image width and height should 64x64',
                'max_size' => 'Image size should not be more than 256kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
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


    public $hotel_room = [

        'room_title' => [
            'label' => 'room title',
            'rules' => 'required|max_length[254]',
            'errors' => [
                'required' => 'Please enter room title'
            ],
        ],

        'min_stay' => [
            'label' => 'minimum stay',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter minimum stay'
            ],
        ],
        'room_quantity' => [
            'label' => 'room quantity',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter room quantity'
            ],
        ],

        'occupancy_type' => [
            'label' => 'occupancy type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select occupancy type'
            ],
        ],

        'room_amenities.*' => [
            'label' => 'room amenities',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select room amenities'
            ],
        ],

        'room_description' => [
            'label' => 'room description',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter room description'
            ],
        ],

        'room_cancellation' => [
            'label' => 'room cancellation',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter room cancellation'
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

    public $hotel_addon = [

        'service_name' => [
            'label' => 'service name',
            'rules' => 'required|max_length[255]',
            'errors' => [
                'required' => 'Please enter service name'
            ],
        ],

        'price' => [
            'label' => 'price',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter price'
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
    public $room_price = [

        'start_date' => [
            'label' => 'start date',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please enter start date'
            ],
        ],

        'end_date' => [
            'label' => 'end date',
            'rules' => 'required|max_length[25]',
            'errors' => [
                'required' => 'Please enter end date'
            ],
        ],
        'adult_price' => [
            'label' => 'adult price',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter adult price'
            ],
        ],

        'child_price' => [
            'label' => 'child price',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter child price'
            ],
        ],
        'mon' => [
            'label' => 'monday',
            'rules' => 'permit_empty|numeric',
        ],
        'tue' => [
            'label' => 'tuesday',
            'rules' => 'permit_empty|numeric',
        ],
        'wed' => [
            'label' => 'wednesday',
            'rules' => 'permit_empty|numeric',
        ],
        'thu' => [
            'label' => 'thursday',
            'rules' => 'permit_empty|numeric',
        ],
        'fri' => [
            'label' => 'friday',
            'rules' => 'permit_empty|numeric',
        ],
        'sat' => [
            'label' => 'saturday',
            'rules' => 'permit_empty|numeric',
        ],
        'sun' => [
            'label' => 'sunday',
            'rules' => 'permit_empty|numeric',
        ],
    ];
}

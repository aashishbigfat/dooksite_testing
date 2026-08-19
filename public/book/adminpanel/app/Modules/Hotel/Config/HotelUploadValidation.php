<?php
namespace Modules\Hotel\Config;

class HotelUploadValidation
{
   
    public $hotel_validation=[
                                'bussiness_type' => [
                                    'label' => 'Bussiness Type',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select bussiness type'
                                    ],
                                ],
                                'agent_info' => [
                                    'label' => 'Agent Name',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select agent name'
                                    ],
                                ],
                                'customer_info' => [
                                    'label' => 'Customer Name',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select customer name'
                                    ],
                                ],
                                'supplier' => [
                                    'label' => 'Issue Supplier',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select issue supplier'
                                    ],
                                ],
                                'hotel_city' => [
                                    'label' => 'Hotel City',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select hotel city'
                                    ],
                                ],
                                'hotel_name' => [
                                    'label' => 'Hotel Name',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please enter hotel name'
                                    ],
                                ],
                                'hotel_address' => [
                                    'label' => 'Hotel Address',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please enter hotel address'
                                    ],
                                ],
                                'hotel_star_rating' => [
                                    'label' => 'Hotel Address',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select star rating'
                                    ],
                                ],
                                'check_in_date' => [
                                    'label' => 'Check-in',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select check in date'
                                    ],
                                ],
                                'check_out_date' => [
                                    'label' => 'Check-out',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select check out date'
                                    ],
                                ],
                                'check_out_date' => [
                                    'label' => 'Check-out',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select check out date'
                                    ],
                                ],
                                'hotel_policy' => [
                                    'label' => 'Check-out',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please enter hotel policy'
                                    ],
                                ],
                                'dial_code' => [
                                    'label' => 'Dial Code',
                                    'rules' => 'required|numeric',
                                    'errors' => [
                                        'numeric' => 'Please select dial code'
                                    ],
                                ],
                                'contact_number' => [
                                    'label' => 'Contact Number',
                                    'rules' => 'required|numeric|min_length[7]|max_length[15]',
                                    'errors' => [
                                        'numeric' => 'Please enter valid mobile number',
                                        'required' => 'Please enter mobile number'
                                    ],
                                ],
                                'email_id' => [
                                    'label' => 'Email Id',
                                    'rules' => 'required|valid_email',
                                    'errors' => [
                                        'required' => 'Please enter  email',
                                        'valid_email' => 'Please enter valid email'
                                    ],
                                ]
                             ];


    public function pax_validation($data)
    {  
        
        $booking_validation = [];
        foreach ($data['room'] as $key=> $requestParameter) {   
            $booking_validation["room.$key.room_name"] = [
                'label' => 'Room Name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter room name.'
                ],
            ];
            $booking_validation["room.$key.room_amenities"] = [
                'label' => 'Room Amenities',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter room amenities.'
                ],
            ];
            $booking_validation["room.$key.room_cancellation_policy"] = [
                'label' => 'Room Cancellation Policy',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter cancellation policy.'
                ],
            ];

            $booking_validation["room.$key.room_price"] = [
                'label' => 'Room Price',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter room price.'
                ],
            ]; 
            $booking_validation["room.$key.tax"] = [ 
                'label' => 'Tax',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter tax.'
                ], 
            ]; 
            $booking_validation["room.$key.othercharge"] = [ 
                'label' => 'Other Charge',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter other charge.'
                ], 
            ]; 
            $booking_validation["room.$key.markup_type"] = [ 
                'label' => 'Markup Type',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select markup type.'
                ], 
            ]; 
            $booking_validation["room.$key.markup"] = [ 
                'label' => 'Markup',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter markup.'
                ], 
            ]; 
            $booking_validation["room.$key.discount"] = [ 
                'label' => 'Discount',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please enter discount.'
                ], 
            ]; 

            if(isset($requestParameter['pax']))
            {
                    foreach($requestParameter['pax'] as $paxkey=>$paxitem)
                    {
                            foreach($paxitem as $key2 => $item) {
                                $booking_validation["room.$key.pax.$paxkey.$key2.title"] = [
                                    'label' => 'Title',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please select title.'
                                    ], 
                                ];
                                $booking_validation["room.$key.pax.$paxkey.$key2.first_name"] = [
                                    'label' => 'First Name',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please enter first name.'
                                    ], 
                                ];
                                $booking_validation["room.$key.pax.$paxkey.$key2.last_name"] = [
                                    'label' => 'Last Name',
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => 'Please enter last name.'
                                    ], 
                                ];
            
                                if(isset($item['pan_number']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.pan_number"] = [
                                        'label' => 'PAN Number',
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => 'Please enter pan number.'
                                        ], 
                                    ];
                                }
                                if(isset($item['age']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.age"] = [
                                        'label' => 'Age',
                                        'rules' => 'required|numeric',
                                        'errors' => [
                                            'required' => 'Please enter age.'
                                        ], 
                                    ];
                                }
                                if(isset($item['nationality']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.nationality"] = [
                                        'label' => 'Nationality',
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => 'Please select nationality.'
                                        ], 
                                    ];
                                }
                                if(isset($item['passport_number']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.passport_number"] = [
                                        'label' => 'Passport Number',
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => 'Please enter passport number.'
                                        ], 
                                    ];
                                }
                                if(isset($item['issue_date']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.issue_date"] = [
                                        'label' => 'Issue date',
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => 'Please select issue date.'
                                        ], 
                                    ];
                                }
                                if(isset($item['expiry_date']))
                                {
                                    $booking_validation["room.$key.pax.$paxkey.$key2.expiry_date"] = [
                                        'label' => 'Expiry date',
                                        'rules' => 'required',
                                        'errors' => [
                                            'required' => 'Please select expiry date.'
                                        ], 
                                    ];
                                }


                            }
                    }
            }
        } 
        return $booking_validation;
    }
       

}
<?php

namespace Modules\Dashboard\Config;


class Validation
{


    public $customer_validation = [
        'title' => [
            'label' => 'title',
            'rules' => 'trim|required|max_length[10]',
            'errors' => [
                'required' => 'Please select title'
            ],
        ],

        'first_name' => [
            'label' => 'first name',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter first name'
            ],
        ],


        'mobile_no' => [
            'label' => 'mobile number',
            'rules' => 'trim|required|numeric|min_length[10]|max_length[18]',
            'errors' => [
                'numeric' => 'Please enter valid mobile number'
            ],
        ],
        
        'profile_pic' => [
            'label' => 'Image',
            'rules' => 'uploaded[profile_pic]|max_size[profile_pic,1024]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png, '
            ],
        ],
        'address' => [
            'label' => 'address',
            'rules' => 'trim',
        ],
        'city' => [
            'label' => 'city',
            'rules' => 'trim',
        ],

        'state' => [
            'label' => 'state',
            'rules' => 'trim',
        ],
        'country' => [
            'label' => 'country',
            'rules' => 'trim',
        ],
        'pin_code' => [
            'label' => 'pin code',
            'rules' => 'trim',
        ],
    ];

    public $customer_password_change =[
        'old_password' => [
            'label' => 'password',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter old password'
            ],
        ],
        'password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'errors' => [
                'required' => 'Please enter password',
                'min_length' => 'Password must be at least 8 digits',
                'max_length' => 'Password must be at less than 16 digits',
                'regex_match' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, one special character.'
            ],
        ],
    ];



   

}


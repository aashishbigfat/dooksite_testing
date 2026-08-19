<?php

namespace Modules\PaymentSetting\Config;

class Validation
{

    public $adduser = [
        'first_name' => [
            'label' => 'First Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter first name',
            ],
        ],
        'last_name' => [
            'label' => 'Last Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter last name',
            ],
        ],
        'login_email' => [
            'label' => 'Email ID',
            'rules' => 'required|valid_email|is_unique[super_admin_users.login_email]',
            'errors' => [
                'required' => 'Please enter email id',
                'valid_email' => 'Please enter a valid email id.',
                'is_unique' => 'Email ID already exists.Please choose a different email id.'
            ],
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Please enter password',
                'min_length' => 'Password must be at least 6 digits'
            ],
        ],
        'mobile_isd' => [
            'label' => 'Dial Code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select dial code',
            ],
        ],
        'mobile_no' => [
            'label' => 'Mobile Number',
            'rules' => 'required|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter mobile number',
            ],
        ],
        'whatsapp_no' => [
            'label' => 'Whatsapp Number',
            'rules' => 'permit_empty|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter Whatsapp number',
            ],
        ],
        'designation' => [
            'label' => 'Designation',
            'rules' => 'required|alpha_space',
            'errors' => [
                'required' => 'Please enter designation',
            ],
        ],
        'job_joining_date' => [
            'label' => 'Joining Date',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter joining date',
            ],
        ],
        'pin_code' => [
            'label' => 'Zip Code',
            'rules' => 'permit_empty|numeric|min_length[3]|max_length[8]',
            'errors' => [
                'required' => 'Please enter your Zip Code',
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
}

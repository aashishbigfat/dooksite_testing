<?php
namespace Modules\Login\Config;

class Validation
{
    public $webpartner_validation = [
        'company_name' => [
            'label' => 'Company Name',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter company name',
            ],
        ],

        'first_name' => [
            'label' => 'first name',
            'rules' => 'trim|required|alpha',
            'errors' => [
                'required' => 'Please enter first name',
            ],
        ],

        'last_name' => [
            'label' => 'last name',
            'rules' => 'trim|required|alpha',
            'errors' => [
                'required' => 'Please enter last name',
            ],
        ],


        'pan_card' => [
            'label' => 'PAN Card Scan Copy',
            'rules' => 'uploaded[pan_card]|max_size[pan_card,1024]|mime_in[pan_card,image/jpg,image/jpeg,image/png,application/pdf]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png, pdf'
            ],
        ],


        'pan_name' => [
            'label' => 'Pan Name',
            'rules' => 'permit_empty|trim|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter Pan Name',
            ],
        ],
        'pan_number' => [
            'label' => 'Pan Number',
            'rules' => 'trim|trim|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|is_unique[web_partner.pan_number]',
            'errors' => [
                'required' => 'Please enter Pan Number',
            ],
        ],
        'company_gst_no' => [
            'label' => 'GST Number',
            'rules' => 'trim|permit_empty|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
            'errors' => [
                'required' => 'Please enter GST Number',
            ],
        ],
        'address' => [
            'label' => 'Address',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter street / address',
            ],
        ],
        'terms_conditions' => [
            'label' => 'terms conditions',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select terms conditions',
            ],
        ],
        'country' => [
            'label' => 'Country',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please select country',
            ],
        ],
        'state' => [
            'label' => 'State',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter state',
            ],
        ],
        'city' => [
            'label' => 'City',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter city',
            ],
        ],
        'pincode' => [
            'label' => 'Zip Code',
            'rules' => 'trim|required|numeric|min_length[4]|max_length[6]',
            'errors' => [
                'required' => 'Please enter zip code',
            ],
        ],



        'login_email' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[admin_users.login_email]',
            'errors' => [
                'required' => 'Please enter  email',
                'is_unique'=>'Email already exists please use another email'
            ],
        ],

        'user_password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[8]',
            'errors' => [
                'required' => 'Please enter password'
            ],
        ],

        'confirm_password' => [
            'label' => 'confirm password',
            'rules' => 'trim|required|matches[user_password]',
            'errors' => [
                'required' => 'Please enter confirm password'
            ],
        ],

        'user_mobile_no' => [
            'label' => 'mobile no',
            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|is_unique[admin_users.mobile_no]',
            'errors' => [
                'required' => 'Please enter mobile no',
                'is_unique'=>'Mobile already exists please use another mobile no'
            ],
        ],
    ];
}

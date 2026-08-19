<?php

namespace Modules\Pages\Config;

class Validation
{

    public $query_validation = [

        'name' => [
            'label' => 'name',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter Name'
            ],
        ],
        'captchagenerate' => [
            'label' => 'captcha code',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter captcha code'
            ],
        ],



        'email' => [
            'label' => 'email',
            'rules' => 'trim|required|valid_email|max_length[50]',
            'errors' => [
                'required' => 'Please enter email',
                'valid_email' => 'Please enter valid email',
            ],
        ],



        'subject' => [
            'label' => 'subject',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter subject'
            ],
        ],

        'message' => [
            'label' => 'message',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter message'
            ],
        ],

    ];

    public $newsletter_validation = [ 

        'email' => [
            'label' => 'email',
            'rules' => 'trim|required|valid_email|is_unique[newsletter.email]|max_length[50]',
            'errors' => [
                'required' => 'Please enter email',
                'valid_email' => 'Please enter valid email',
                'is_unique' => 'Thanks for subscibing. Now, get all travel info & deals at your fingertips!', 
            ],
        ],
    ];
}

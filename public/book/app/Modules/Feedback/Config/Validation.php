<?php

namespace Modules\Feedback\Config;

class Validation
{
    public $feedback_validation = [
        'name' => [
            'label' => 'Name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter  name'

            ],
        ],

        'phone' => [
            'label' => 'Mobile No',
            'rules' => 'trim|required|min_length[10]|max_length[10]',
            'errors' => [
                'required' => 'Please enter  mobile no'

            ],
        ],

        // 'feedback_date' => [
        //     'label' => 'Feedback Date',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select feedback date'

        //     ],
        // ],

        // 'status' => [
        //     'label' => 'Status',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select feedback status'

        //     ],
        // ],

        'description' => [
            'label' => 'Feedback Description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter feedback description'
            ],
        ],

        'email' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email'

            ],
        ],
        'image' => [
            'label' => 'Image',
            'rules' => 'uploaded[image]|max_size[image,1024]|mime_in[image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],

        ],
    ];
}

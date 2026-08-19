<?php
namespace Modules\Notification\Config;


class Validation
{
    public $notification_validation = [
        'title' => [
            'label' => 'title',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter title'

            ],
        ],



        'status' => [
            'label' => 'status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select status'

            ],
        ],

        'description' => [
            'label' => 'description',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter  description'
            ],
        ],


    ];

    public $status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

}


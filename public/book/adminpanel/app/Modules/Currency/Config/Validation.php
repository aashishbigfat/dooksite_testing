<?php
namespace Modules\Currency\Config;

class Validation
{
    public $currency_validation = [
        // 'country' => [
        //     'label' => 'country',
        //     'rules' =>'trim|required',
        //     'errors' => [
        //         'required' => 'Please enter country'

        //     ],
        // ],


        // 'currency' => [
        //     'label' => 'currency',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please enter currency'

        //     ],
        // ],

        // 'currency_name' => [
        //     'label' => 'currency Name',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select Currency Name'

        //     ],
        // ],


        'convertion_rate' => [
            'label' => 'convertion rate',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convertion Rate'

            ],
        ],

        // 'currency_symbol' => [
        //     'label' => 'currency_symbol',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select Currency Symbol'

        //     ],
        // ],



        'decimal_point' => [
            'label' => 'Decimal point',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please select Decimal Point'

            ],
        ],


        // 'status' => [
        //     'label' => 'status',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select status'

        //     ],
        // ],


     

       
    ];



    public $currency_validation_update = [
        


        'convertion_rate' => [
            'label' => 'convertion rate',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convertion Rate'

            ],
        ],

      


       
    ];



    public $currency_status = [
        


        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'

            ],
        ],

      


       
    ];



   

}

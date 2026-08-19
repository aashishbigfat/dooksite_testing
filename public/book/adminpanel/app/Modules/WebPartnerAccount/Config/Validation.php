<?php
namespace Modules\WebPartnerAccount\Config;

class Validation
{
    public $web_partner_account =[
        'amount' => [
            'label' => 'amount',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Please enter  amount'
            ],
        ],
        'action_type' => [
            'label' => 'action type',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select  action type'
            ],
        ],
        'booking_reference_number' => [
            'label' => 'booking reference number',
            'rules' => 'required_with[service]',
            'errors' => [
                'required' => 'Please enter  booking reference number'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ]
        ];
}

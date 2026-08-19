<?php

namespace Modules\ConvenienceFee\Config;


class Validation
{
 

    public function convenience_fee_validation($data)
    {
        $convenience_fee_validation = [];

        $convenience_fee_validation['convenience_fee_for'] = [
            'label' => 'Convenience Fee For',
            'rules' => 'required|in_list[B2B,B2C]',
            'errors' => [
                'required' => 'Please select convenience type',
                'in_list' => 'Invalid convenience fee type'
            ],
        ];

        $convenience_fee_validation['payment_gateway'] = [
            'label' => 'Payment Gateway',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter payment gateway'
            ],
        ];

        if (isset($data['service']) && $data['service'] && !empty($data['service'])) {
            $convenience_fee_validation['service.*'] = [
                'label' => 'Service',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter service'
                ],
            ];
        }

        if (isset($data['agent_class_id']) && $data['agent_class_id'] && !empty($data['agent_class_id']) && $data['convenience_fee_for'] == 'B2B') {
            $convenience_fee_validation['agent_class_id.*'] = [
                'label' => 'Agent Class',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select agent class'
                ],
            ];
        }

        $convenience_fee_validation['min_amount'] = [
            'label' => 'Minimum Amount',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'required' => 'Please enter minimum amount',
                'numeric' => 'Minimum amount must be a number',
                'greater_than' => 'Minimum amount must be greater than 0'
            ],
        ];

        $convenience_fee_validation['max_amount'] = [
            'label' => 'Maximum Amount',
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'required' => 'Please enter maximum amount',
                'numeric' => 'Maximum amount must be a number',
                'greater_than' => 'Maximum amount must be greater than 0 and minimum amount'
            ],
        ];

        return $convenience_fee_validation;
    }
}

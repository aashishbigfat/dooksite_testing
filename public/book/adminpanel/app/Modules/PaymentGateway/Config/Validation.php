<?php

namespace Modules\PaymentGateway\Config;

class Validation
{
    public function updateGateway($id = null)
    {
        $update_gateway_validation = [];

        $update_gateway_validation['payment_gateway'] = [
            'label' => "Payment Gateway",
            'rules' => "required|is_unique[web_partner_payment_gateway_mode_activation.payment_gateway,id,".$id. "]",
            'errors' => [
                "is_unique" => "Payment Gateway already exists"
            ]
        ];
        $update_gateway_validation['payment_mode.*'] = [
            'label' => "Payment Mode",
            'rules' => 'required',
        ];
        $update_gateway_validation['status'] = [
            'label' => "Status",
            'rules' => 'required|in_list[active,inactive]',
        ];
        
        return  $update_gateway_validation;
    }


}
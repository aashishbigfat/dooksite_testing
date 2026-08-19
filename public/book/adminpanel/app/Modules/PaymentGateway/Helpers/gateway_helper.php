<?php

/*************** Gateway Names Starts ***************/
if(!function_exists('payment_gatways')) {
    function payment_gateways() {
        $Gateways = [
            'HDFC',
            'CASHFREE',
            'CCAVENUE',
            'ICICI',
            'EASEBUZZ',
            'PHONEPE'
        ];
        return $Gateways;
    }
}
/*************** Gateway Names Ends ***************/
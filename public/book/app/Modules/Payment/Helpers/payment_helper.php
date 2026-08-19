<?php
/* get Flight Time */
if (!function_exists('get_flight_time')) {
    function get_flight_time($var)
    {
        list($dt, $tm) = explode('T', $var);
        $tm = substr($tm, 0, 5);
        return $tm;
    }
}
/* get Flight Date */
if (!function_exists('get_flight_date')) {
    function get_flight_date($var)
    {
        list($dt, $tm) = explode('T', $var);
        return date("d M", strtotime($dt));
    }
}
/* get convert To Hours Mins from  Duration (in Minutes) */
if (!function_exists('get_convertToHoursMinsfromMinDuration')) {
    function get_convertToHoursMinsfromMinDuration($minutes)
    {
        return $hours = intdiv($minutes, 60) . ' h ' . ($minutes % 60) . ' m ';
    }
}

if (!function_exists('get_razorpay_payment_mode')) {
    function get_razorpay_payment_mode($value)
    {
        switch ($value) {
            case "Card":
                $payment_mode = "card";
                break;
            case "NetBanking":
                $payment_mode = "netbanking";
                break;
            case "UPI":
                $payment_mode = "upi";
                break;
            case "MobileWalllet":
                $payment_mode = "wallet";
                break;
            default:
                $payment_mode = "";
        }
        return $payment_mode;
    }
}
function calculate_convenience_fee($data, $payment_type, $amount)
{
    $totalfare = 0;
    $value = isset($data[$payment_type . '_value']) ? $data[$payment_type . '_value'] : 0;
    $type = isset($data[$payment_type . '_type']) ? $data[$payment_type . '_type'] : "";
    if ($type == 'fixed') {
        $value = round_value($value);
        $totalfare = round_value(($amount + $value));
    } else {
        $value = round_value((($amount * $value) / 100));
        $totalfare = round_value($amount + $value);
    }

    return array('totalfare' => $totalfare, 'conveniencefee' => $value);
}

/**
 * ------------------------------------------
 * Identification of Payment Mode
 * ------------------------------------------
 */

if (!function_exists('get_payment_mode')) {
    function get_payment_mode($value)
    {
        switch ($value) {
            case "RuPayCreditCard":
                $payment_mode = "CreditCard";
                break;
            case "MastercardCreditCard":
                $payment_mode = "CreditCard";
                break;
            case "AmericanExpressCreditCard":
                $payment_mode = "CreditCard";
                break;

            case "VisaCreditCard":
                $payment_mode = "CreditCard";
                break;


            case "CRDC":
                $payment_mode = "CreditCard";
                break;
            case "DBCRD":
                $payment_mode = "DebitCard";
                break;
            case "NBK":
                $payment_mode = "NetBanking";
                break;
            case "MOBP":
                $payment_mode = "MobileWallet";
                break;
            case "UPI":
                $payment_mode = "UPI";
                break;
            default:
                $payment_mode = "Online";
        }

        return $payment_mode;
    }


}
if (!function_exists('get_card_name')) {
    function get_card_name($value)
    {
        switch ($value) {
            case "visa_credit_card":
                $payment_mode = "Visa";
                break;
            case "american_express_credit_card":
                $payment_mode = "AMEX";
                break;
            case "mastercard_credit_card":
                $payment_mode = "MasterCard";
                break;

            case "rupay_credit_card":
                $payment_mode = "RuPay";
                break;
            default:
                $payment_mode = "";

        }

        return $payment_mode;
    }
}
/**
 * ------------------------------------------
 * Identification of Cash Free Payment Mode
 * ------------------------------------------
 */

if (!function_exists('get_cashfree_payment_mode')) {
    function get_cashfree_payment_mode($value)
    {
        switch ($value) {
            case "CRDC":
                $payment_mode = "card";
                break;
            case "DBCRD":
                $payment_mode = "card";
                break;
            case "NBK":
                $payment_mode = "nb";
                break;
            case "MOBP":
                $payment_mode = "wallet";
                break;
            case "UPI":
                $payment_mode = "upi";
                break;
            case "wallet":
                $payment_mode = "wallet";
                break;
            default:
                $payment_mode = "";
        }

        return $payment_mode;
    }
    if (!function_exists('get_phonepay_payment_mode')) {
        function get_phonepay_payment_mode($value)
        {
            switch ($value) {
                case "Card":
                    $payment_mode = "CARD";
                    break;
                case "NetBanking":
                    $payment_mode = "NET_BANKING";
                    break;
                case "UPI":
                    $payment_mode = "UPI_QR";
                    break;
                case "MobileWalllet":
                    $payment_mode = "UPI_INTENT";
                    break;
                default:
                    $payment_mode = "";
            }
            return $payment_mode;
        }
    }
}


if (!function_exists('calculate_gateway_cfee')) {
    function calculate_gateway_cfee($fee, $type, $amount)
    {

        if ($type === 'fixed') {
            return $fee;
        } else if ($type === 'percentage') {
            return $amount * $fee / 100;
        }
        return $fee;
    }
}

if (!function_exists('get_easebuzz_payment_mode')) {
    function get_easebuzz_payment_mode($value)
    {
        switch ($value) {
            case "CRDC":
                $payment_mode = "CC";
                break;
            case "DBCRD":
                $payment_mode = "DC";
                break;
            case "NBK":
                $payment_mode = "NB";
                break;
            case "UPI":
                $payment_mode = "UPI";
                break;
            case "WLT":
                $payment_mode = "MW";
                break;
            default:
            $payment_mode = "CC,DC";
        }
        return $payment_mode;
    }

}

 

if (!function_exists('get_coupon_info_amount')) {
    function get_coupon_info_amount($couponJson, $totalAmount) { 
        if (!empty($couponJson)) { 
            $couponData = json_decode($couponJson, true); 
            if (is_array($couponData) && isset($couponData['couponAmount'])) { 
                $couponAmount = $couponData['couponAmount'] ?? 0;  
                $finalAmount = ($couponAmount > 0 && $couponAmount <= $totalAmount) ? max(0, $totalAmount - $couponAmount) : $totalAmount;
                if($finalAmount == 0 && $finalAmount > 0){
                   $finalAmount =  $couponAmount;
                }
                return [ 
                    'couponApplied' => true,
                    'finalAmount' => abs($finalAmount),
                ];
            }
        } 
        return [
            'couponApplied' => false,
            'finalAmount' => abs($totalAmount)
        ];
    }
}

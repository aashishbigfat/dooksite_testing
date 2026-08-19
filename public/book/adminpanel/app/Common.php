<?php


use CodeIgniter\I18n\Time;
use App\Models\CommonModel;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */





function gettingCountryCodeWithCountryName()
{
    $CommonModel = new CommonModel();
    return $CountryCodeWithCountryName = $CommonModel->gettingCountryCodeWithCountryName();
}
/**
 * --------------------------------------------------------
 *  TDS Calaculate
 * --------------------------------------------------------
 */
if (!function_exists('tds_calculate')) {
    function tds_calculate($value)
    {
        $tdsvalue = 0;
        $tds_apply = 5; // TDS Value
        if ($value) {
            $tdsvalue = ($value * $tds_apply) / 100;
            $tdsvalue = round_value($tdsvalue);
        }
        return $tdsvalue;
    }
}
/**
 * -------------------------------------
 * strtotime To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('custom_date_format')) {
    function custom_date_format($strtotime)
    {
        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        return $date_obj->format('d M Y');
    }
}

/**
 * -------------------------------------
 * Generate Refund Confirmation Number
 * -------------------------------------
 */
if (!function_exists('GenerateRefundConfirmationNumber')) {
    function GenerateRefundConfirmationNumber($service, $confirmationPrefix, $confirmationCounter)
    {
        $format = "y";
        $date = date_create(Time::now());
        if (date_format($date, "m") >= 4) {
            $financial_year = (date_format($date, $format)) . (date_format($date, $format) + 1);
        } else {
            $financial_year = (date_format($date, $format) - 1) . date_format($date, $format);
        }
        return $confirmationPrefix . "/" . $financial_year . "/" . $confirmationCounter;
    }
}

/* get Flight booking availability */
if (!function_exists('checkbookingflighttime')) {
    function checkbookingflighttime($bookdatetime, $service = null)
    {
        $timezone = admin_cookie_data()['admin_comapny_detail']['timezone'];
        date_default_timezone_set($timezone);
        $current = Time::parse(Time::now($timezone), $timezone);
        $bookdatetime = Time::parse(date("y-m-d H:i:s", $bookdatetime), $timezone);
        $diff = $bookdatetime->difference($current);
        $timeInSecond = $diff->getSeconds();
        $WaitingTime = 0;
        $WaitingMessage = "";
        $remainingSecond = 180;
        $Minute = 0;
        $Second = 0;
        $remainingSecond = $remainingSecond - $timeInSecond;
        if ($timeInSecond > 0 && ($timeInSecond != 0 && $timeInSecond < 180)) {
            $WaitingTime = 1;
            $Minute = intdiv($remainingSecond, 60);
            $Second = ($remainingSecond % 60);
            $timemessage = $Minute . ' Minute ' . ($Second) . ' Second ';
            $WaitingMessage = "Please Wait " . $timemessage . " Minute, Booking Under Process";
        }
        return array("WaitingTime" => $WaitingTime, "WaitingMessage" => $WaitingMessage, "Minute" => $Minute, "Second" => $Second);
    }
}

/**
 * --------------------------------------------------------
 *  Change Booking Source
 * --------------------------------------------------------
 */
if (!function_exists('service_booking_source')) {
    function service_booking_source($value)
    {
        switch ($value) {
            case 'Back_Office':
                return 'Agent Booking';
                break;
            case 'Api':
                return 'Api';
                break;
            case 'Wl_b2b':
                return 'B2B';
                break;
            case 'Wl_b2c':
                return 'B2C';
                break;
        }
    }
}

/**
 * -------------------------------------
 * Generate Confirmation Number
 * -------------------------------------
 */
if (!function_exists('GenerateConfirmationNumber')) {
    function GenerateConfirmationNumber($service, $confirmationPrefix, $confirmationCounter)
    {
        $format = "y";
        $date = date_create(Time::now());
        if (date_format($date, "m") >= 4) {
            $financial_year = (date_format($date, $format)) . (date_format($date, $format) + 1);
        } else {
            $financial_year = (date_format($date, $format) - 1) . date_format($date, $format);
        }
        return $confirmationPrefix . "/" . $financial_year . "/" . $confirmationCounter;
    }
}


/**
 * -------------------------------------
 * Check Taxable or Non Taxable Invoice
 * -------------------------------------
 */
if (!function_exists('checkTaxableNonTaxableINV')) {
    function checkTaxableNonTaxableINV($InvoiceAmountData, $WebpartnerInfo, $service, $INVTYPE)
    {
        $WebpartnerGSTInfo = isset($WebpartnerInfo['gst_number']) ? $WebpartnerInfo['gst_number'] : '';
        $taxableInvoce = 0;
        $taxableValue = 0; 
        if ($INVTYPE == 'INV') {
            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'hotel') {
                unset($InvoiceAmountData['couponAmount']);
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'bus') {
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'holiday') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'hajj') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'umrah') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'activities') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'tourguide') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'visa') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'car') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            }
        } else if ($INVTYPE == 'RFND') {
            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "holiday") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "hajj") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "umrah") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "activities") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "tourguide") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "visa") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "car") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "bus") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "hotel") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($WebpartnerGSTInfo != '')) {
                    if ($WebpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            }
        }
        return $taxableInvoce;
    }
}

/**
 * -------------------------------------
 * get Taxable or Non Taxable Invoice Suffix
 * -------------------------------------
 */
if (!function_exists('getTaxableNonTaxableINVSuffix')) {
    function getTaxableNonTaxableINVSuffix($INVType, $TaxableINV, $service)
    {
        $InvoceSuffix = array("TaxablePrfix" => '', "NONTaxablePrfix" => '');

        if ($INVType == 'INV') {
            switch ($service) {
                case "flight":
                    $InvoceSuffix = array("TaxablePrfix" => 'FG', "NONTaxablePrfix" => 'FW');
                    break;
                case "hotel":
                    $InvoceSuffix = array("TaxablePrfix" => 'HG', "NONTaxablePrfix" => 'HW');
                    break;
                case "holiday":
                    $InvoceSuffix = array("TaxablePrfix" => 'HYG', "NONTaxablePrfix" => 'HYW');
                    break;
                case "bus":
                    $InvoceSuffix = array("TaxablePrfix" => 'BG', "NONTaxablePrfix" => 'BW');
                    break;
                case "car":
                    $InvoceSuffix = array("TaxablePrfix" => 'CG', "NONTaxablePrfix" => 'CW');
                    break;
                case "cruise":
                    $InvoceSuffix = array("TaxablePrfix" => 'CUG', "NONTaxablePrfix" => 'CUW');
                    break;
                case "visa":
                    $InvoceSuffix = array("TaxablePrfix" => 'VG', "NONTaxablePrfix" => 'VW');
                    break;
                case "hajj":
                    $InvoceSuffix = ["TaxablePrfix" => 'HJG', "NONTaxablePrfix" => 'HJW'];
                    break;
                case "umrah":
                    $InvoceSuffix = ["TaxablePrfix" => 'UG', "NONTaxablePrfix" => 'UW'];
                    break;
                case "tourguide":
                    $InvoceSuffix = ["TaxablePrfix" => 'TG', "NONTaxablePrfix" => 'TW'];
                    break;
                case "activities":
                    $InvoceSuffix = ["TaxablePrfix" => 'AG', "NONTaxablePrfix" => 'AW'];
                    break;
            }
        } elseif ($INVType == 'RFND') {
            switch ($service) {
                case "flight":
                    $InvoceSuffix = array("TaxablePrfix" => 'GG', "NONTaxablePrfix" => 'GW');
                    break;
                case "hotel":
                    $InvoceSuffix = array("TaxablePrfix" => 'HG', "NONTaxablePrfix" => 'HW');
                    break;
                case "holiday":
                    $InvoceSuffix = array("TaxablePrfix" => 'HYG', "NONTaxablePrfix" => 'HYW');
                    break;
                case "bus":
                    $InvoceSuffix = array("TaxablePrfix" => 'BG', "NONTaxablePrfix" => 'BW');
                    break;
                case "car":
                    $InvoceSuffix = array("TaxablePrfix" => 'CG', "NONTaxablePrfix" => 'CW');
                    break;
                case "cruise":
                    $InvoceSuffix = array("TaxablePrfix" => 'CUG', "NONTaxablePrfix" => 'CUW');
                    break;
                case "visa":
                    $InvoceSuffix = array("TaxablePrfix" => 'VG', "NONTaxablePrfix" => 'VW');
                    break;
                case "hajj":
                    $InvoceSuffix = ["TaxablePrfix" => 'HJG', "NONTaxablePrfix" => 'HJW'];
                    break;
                case "umrah":
                    $InvoceSuffix = ["TaxablePrfix" => 'UG', "NONTaxablePrfix" => 'UW'];
                    break;
                case "tourguide":
                    $InvoceSuffix = ["TaxablePrfix" => 'TG', "NONTaxablePrfix" => 'TW'];
                    break;
                case "activities":
                    $InvoceSuffix = ["TaxablePrfix" => 'AG', "NONTaxablePrfix" => 'AW'];
                    break;
            }
        }
        return $InvoceSuffix;
    }
}


/**
 * -------------------------------------
 * strtotime To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('datetime_utc_to_ist')) {
    function datetime_utc_to_ist($datetime, $format = null)
    {
        if ($format) {
            $newformat = $format;
        } else {
            $newformat = 'Y-m-d';
        }

        $date = new DateTime($datetime, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $date->format($newformat);
    }
}

/**
 * -------------------------------------
 *Booking Date To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('display_custom_date_format')) {
    function display_custom_date_format($date, $time = null)
    {
        $newformat = 'd M Y';
        $strtotime = strtotime($date);
        if ($time) {
            $newformat = 'd M Y H:i:s';
            $date = str_replace("T", " ", $date);
            $strtotime = strtotime($date);
        }

        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        $dateTime = $date_obj->format('Y-m-d\TH:i:s');
        return datetime_utc_to_ist($dateTime, $newformat);
    }
}

/**
 * -------------------------------------
 * Get Common String Format
 * -------------------------------------
 */
if (!function_exists('get_uc_text_format')) {
    function get_uc_text_format($text)
    {
        return ucfirst(strtolower($text));
    }
}
/**
 * -------------------------------------
 * Get change_money_format
 * -------------------------------------
 */
if (!function_exists('change_money_format')) {

    function change_money_format($number)
    {
        return number_format($number);
    }
}


/**
 * -------------------------------------
 * Custom Money format
 * -------------------------------------
 */
if (!function_exists('custom_money_format')) {

    function custom_money_format($number)
    {
        $decimal = (string) ($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $delimiter .= ',';
            }
            $delimiter .= $money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if ($decimal != '0') {
            $result = $result . $decimal;
        }

        return $number;
        /*   return $result; */
    }
}
if (!function_exists('limitTextChars')) {
    function limitTextChars($content = false, $limit = false, $stripTags = false, $ellipsis = false)
    {
        if ($content && $limit) {
            $content = ($stripTags ? strip_tags($content) : $content);
            $ellipsis = ($ellipsis ? "..." : $ellipsis);
            $content = mb_strimwidth($content, 0, $limit, $ellipsis);
        }
        return $content;
    }
}

/**
 * --------------------------------------------------------
 *  Value in decimal places
 * --------------------------------------------------------
 */
if (!function_exists('round_value')) {
    function round_value($value, $places = 2)
    {
        return round($value, $places);
    }
}

/**
 * --------------------------------------------------------
 *  GST Calaculate
 *  $service - Flight,Hotel,Bus etc
 *  $user_state_code - Auth User State code (nature of supply )
 *  $admin_state_code - Super admin State code  (nature of supply )
 *  $amount - Apply on GST
 * --------------------------------------------------------
 */
if (!function_exists('gst_calculate')) {
    function gst_calculate($service, $user_state_code, $admin_state_code, $amount)
    {
        $value = 0;
        $gst = 18;

        $value = ($amount * $gst) / 100;

        $CGSTAmount = 0;
        $CGSTRate = 0;
        $IGSTAmount = 0;
        $IGSTRate = 0;
        $SGSTAmount = 0;
        $SGSTRate = 0;
        if ($user_state_code) {
            if ($admin_state_code == $user_state_code) {
                $CGSTRate = round_value($gst / 2);
                $SGSTRate = round_value($gst / 2);
                $CGSTAmount = round_value($value / 2);
                $SGSTAmount = round_value($value / 2);
            } else {
                $IGSTRate = $gst;
                $IGSTAmount = $value;
            }
        } else {
            $IGSTRate = $gst;
            $IGSTAmount = $value;
        }
        return array(
            'CGSTAmount' => $CGSTAmount,
            'CGSTRate' => $CGSTRate,
            'IGSTAmount' => $IGSTAmount,
            'IGSTRate' => $IGSTRate,
            'SGSTAmount' => $SGSTAmount,
            'SGSTRate' => $SGSTRate,
            'TaxableAmount' => $amount,
            'TotalGSTAmount' => $value
        );
    }
}

/**
 * -------------------------------------
 * Generate Invoice Number
 * -------------------------------------
 */

if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($InvoiceData)
    {
        $digitPrefix = '00000000';
        $counterLength = "-" . strlen($InvoiceData['couter']);
        $digitPrifix = substr($digitPrefix, 0, $counterLength);
        $updateData = array();
        $InvoiceNumber = $InvoiceData['prefix'] . $InvoiceData['financial_year'] . $digitPrifix . $InvoiceData['couter'];
        if ($InvoiceData['IsTaxableInvoice'] == 1) {
            $updateData['taxable_couter'] = $InvoiceData['couter'] + 1;
        } else {
            $updateData['nontaxable_couter'] = $InvoiceData['couter'] + 1;
        }
        return array("updateData" => $updateData, "InvoiceNumber" => $InvoiceNumber);
    }
}

/**
 * -------------------------------------
 * Reference Number
 * -------------------------------------
 */


if (!function_exists('reference_number')) {
    function reference_number($bookingid, $service = null, $isDomestic = null, $action_type = null)
    {
        $prefix = "GN";
        if ($service == "GST") {
            if ($isDomestic) {
                $prefix = "DOM";
            } else {
                $prefix = "GNUM";
            }
        }
        $financial_year = get_financial_year();
        return $prefix . '/' . $financial_year . '/' . $bookingid;
    }
}
/**
 * -------------------------------------
 * Get Financial Year
 * -------------------------------------
 */

if (!function_exists('get_financial_year')) {
    function get_financial_year()
    {
        $format = "y";
        $date = date_create(Time::now());
        if (date_format($date, "m") >= 4) {
            $financial_year = (date_format($date, $format)) . (date_format($date, $format) + 1);
        } else {
            $financial_year = (date_format($date, $format) - 1) . date_format($date, $format);
        }
        return $financial_year;
    }
}
/**
 * -------------------------------------
 * GET Whitelable Business
 * -------------------------------------
 */

if (!function_exists('get_active_whitelable_business')) {
    function get_active_whitelable_business()
    {
        $active_business_type = array();
        if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
            $whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
            if (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active") {
                $active_business_type['B2B'] = "B2B";
            }
            if (isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") {
                $active_business_type['B2C'] = "B2C";
            }
        }
        return $active_business_type;
    }
}
/**
 * -------------------------------------
 * GET Whitelable Business
 * -------------------------------------
 */

if (!function_exists('get_active_whitelable_service')) {
    function get_active_whitelable_service()
    {
        $active_service_type = array();
        if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
            $whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
            if (isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") {
                array_push($active_service_type, "Flight");
            }
            if (isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") {
                array_push($active_service_type, "Hotel");
            }
            if (isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") {
                array_push($active_service_type, "Bus");
            }
            if (isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") {
                array_push($active_service_type, "Holiday");
            }
            if (isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") {
                array_push($active_service_type, "Car");
            }
            if (isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") {
                array_push($active_service_type, "Visa");
            }
            if (isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") {
                array_push($active_service_type, "Cruise");
            }
            if (isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") {
                array_push($active_service_type, "Activities");
            }
            if (isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") {
                array_push($active_service_type, "TourGuide");
            }
            if (isset($whitelabel_setting_data['biketour_module']) && $whitelabel_setting_data['biketour_module'] == "active") {
                array_push($active_service_type, "BikeTour");
            }
            if (isset($whitelabel_setting_data['umrah_module']) && $whitelabel_setting_data['umrah_module'] == "active") {
                array_push($active_service_type, "Umrah");
            }
            if (isset($whitelabel_setting_data['hajj_module']) && $whitelabel_setting_data['hajj_module'] == "active") {
                array_push($active_service_type, "Hajj");
            }
            /*   if (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active") {
                  array_push($active_service_type, "Make_Payment");
              } */
        }
        return $active_service_type;
    }
}


if (!function_exists('dashboard_class_tpes')) {
    function dashboard_class_tpes($className)
    {
        switch ($className) {
            case "1":
                echo $classtype = "tile-red";
                break;
            case "2":
                echo $classtype = "tile-green";
                break;
            case "3":
                echo $classtype = "tile-aqua";
                break;
            case "4":
                $classtype = "tile-blue";
                break;
            case "5":
                $classtype = "tile-pink";
                break;
            case "6":
                $classtype = "tile-black";
                break;
            case "6":
                $classtype = "tile-black";
                break;
            default:
                $classtype = "d-none";
        }
        return $classtype;
    }
}


/**
 * -------------------------------------
 * Whitelabel domain name
 * -------------------------------------
 */

if (!function_exists('getWLurl')) {

    function getWLurl($input_d)
    {
        $input_d = trim($input_d, '/');
        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input_d)) {
            $input_d = 'http://' . $input_d;
        }
        $urlParts = parse_url($input_d);
        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);
        return $domain;
    }
}


/**
 * ----------------------------------------------
 * Color Class and use view in this function created by abhay
 * ----------------------------------------------
 */

if (!function_exists('getStatusClass')) {

    function getStatusClass($status)
    {
        switch ($status) {
            case 'Confirmed':
                return 'successful-status';
            case 'Cancelled':
                return 'cancelled-status';
            case 'Failed':
                return 'failed-status';
            case 'Successful':
                return 'successful-status';
            case 'approved':
                return 'successful-status';
            case 'requested':
                return 'requested-status';
            case 'rejected':
                return 'rejected-status';
            case 'Open':
                return 'opne-status';
            case 'Close':
                return 'close-status';
            case 'Processing':
                return 'processing-status';
            default:
                return 'processing-status';
        }
    }
}


if (!function_exists('generatePaymentOptions')) {
    function generatePaymentOptions($selected_modes = null)
    {
        $options = array(
            'upi' => 'UPI',
            'credit_card' => 'Credit Card',
            'rupay_credit_card' => 'Rupay Credit Card',
            'visa_credit_card' => 'Visa Credit Card',
            'mastercard_credit_card' => 'Mastercard Credit Card',
            'american_express_credit_card' => 'American Express Credit Card',
            'debit_card' => 'Debit Card',
            'net_banking' => 'Net Banking',
            'cash_card' => 'Cash',
            'mobile_wallet' => 'Mobile Wallet'
        );


        if (empty($selected_modes)) {
            return array_keys($options);
        }

        $html = '';
        foreach ($options as $key => $label) {
            $selected = in_array($key, $selected_modes) ? 'selected' : '';
            $html .= '<option value="' . $key . '" ' . $selected . '>' . ucwords($label) . '</option>';
        }
        return $html;
    }
}




if (!function_exists('log_error_details')) {
    function log_error_details($e, $customMessage = '')
    {
        $errorMessage = sprintf(
            "[%s] %s | File: %s | Line: %d | Message: %s",
            date('Y-m-d H:i:s'),
            $customMessage ?: 'Application Error',
            $e->getFile(),
            $e->getLine(),
            $e->getMessage()
        );

        log_message('error', $errorMessage);
    }
}
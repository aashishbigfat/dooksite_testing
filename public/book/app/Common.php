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
 * --------------------------------------------------------
 *  TDS Calaculate
 * --------------------------------------------------------
 */
if (!function_exists('tds_calculate')) {
    function tds_calculate($value)
    {
        $tdsvalue = 0;
        $tds_apply = 5;  // TDS Value
        if ($value) {
            $tdsvalue = ($value * $tds_apply) / 100;
            $tdsvalue = round_value($tdsvalue);
        }
        return $tdsvalue;
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
    function checkTaxableNonTaxableINV($InvoiceAmountData, $GstNumber, $service, $INVTYPE)
    {
        $AgentpartnerGSTInfo = (isset($GstNumber)) ? $GstNumber : '';
        $taxableInvoce = 0;
        $taxableValue = 0;
        if ($INVTYPE == 'INV') {
            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'hotel') {
                unset($InvoiceAmountData['couponAmount']);
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'bus') {
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'holiday') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'hajj') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'umrah') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'activities') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'tourguide') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'visa') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'car') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            }
        } else if ($INVTYPE == 'RFND') {
            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "holiday") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "hajj") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "umrah") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "activities") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "tourguide") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "visa") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "car") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "bus") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == "hotel") {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
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
 * Car Extranet  Image data Function
 * -------------------------------------
 */


if (!function_exists('car_image')) {
    function car_image($car_type)
    {
        switch ($car_type) {
            case "Compact-SUV (4+1 seater)":
                $image = "sedan.png";
                break;
            case "SUV (6+1 seater)":
                $image = "xylo.png";
                break;
            case "Sedan (4+1 seater)":
                $image = "sedan.png";
                break;
            case "hatchback (4+1 seater)":
                $image = "hatchback.png";
                break;
            case "Luxury (4+1 seater)":
                $image = "xylo.png";
                break;
            case "MUV (9+1 seater)":
                $image = "xylo.png";
                break;
            default:
                $image = "sedan.png";
                break;
        }
        return $image;
    }
}

/**
 * -------------------------------------
 * Meta Information data Function Created. by Abhay
 * -------------------------------------
 */

if (!function_exists('static_meta_information')) {
    function static_meta_information($service, $key)
    {
        $meta_information = array(
            'Flight' => array(
                "Index" => array(
                    'title' => "Flight, Cheap Air Tickets, Hotels, Holiday, Trains Package Booking " . web_partner_details['company_name'] . ".",
                    'keyword' => "flight, flights, flight booking,flight tickets,Flight reservation,International air travel,Budget flights,Last-minute flight deals,Business class flights,Economy class tickets,One-way flights,Round-trip bookings,Flight promotions,Online flight check-in,Red-eye flights,Flight status updates,Direct flights,Connecting flights,Flight search engine,Flight booking app,Best airfare rates,Family vacation flights,Group flight discounts,Student airfares " . web_partner_details['company_name'] . ".",
                    'description' => "Flight booking, cheap air tickets of domestic & international airlines with " . web_partner_details['company_name'] . " in India. Get the best travel deals for hotels, holidays, trains, and air tickets. Enjoy deals on domestic flights booking around the world. Book air tickets online to your favorite destination in India.",
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => "Flight, Cheap Air Tickets, Hotels, Holiday, Trains Package Booking " . web_partner_details['company_name'] . ".",
                    'keyword' => "flight, flights, flight booking,flight tickets,Flight reservation,International air travel,Budget flights,Last-minute flight deals,Business class flights,Economy class tickets,One-way flights,Round-trip bookings,Flight promotions,Online flight check-in,Red-eye flights,Flight status updates,Direct flights,Connecting flights,Flight search engine,Flight booking app,Best airfare rates,Family vacation flights,Group flight discounts,Student airfares " . web_partner_details['company_name'] . ".",
                    'description' => "Flight booking, cheap air tickets of domestic & international airlines with " . web_partner_details['company_name'] . " in India. Get the best travel deals for hotels, holidays, trains, and air tickets. Enjoy deals on domestic flights booking around the world. Book air tickets online to your favorite destination in India.",
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => "Flight, Cheap Air Tickets, Hotels, Holiday, Trains Package Booking " . web_partner_details['company_name'] . ".",
                    'keyword' => "flight, flights, flight booking,flight tickets,Flight reservation,International air travel,Budget flights,Last-minute flight deals,Business class flights,Economy class tickets,One-way flights,Round-trip bookings,Flight promotions,Online flight check-in,Red-eye flights,Flight status updates,Direct flights,Connecting flights,Flight search engine,Flight booking app,Best airfare rates,Family vacation flights,Group flight discounts,Student airfares " . web_partner_details['company_name'] . ".",
                    'description' => "Flight booking, cheap air tickets of domestic & international airlines with " . web_partner_details['company_name'] . " in India. Get the best travel deals for hotels, holidays, trains, and air tickets. Enjoy deals on domestic flights booking around the world. Book air tickets online to your favorite destination in India.",
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Hotel' => array(
                "Index" => array(
                    'title' => "Online Hotel Booking ,Cheap, Luxury, Hotel Bookings Worldwide " . web_partner_details['company_name'] . ".",
                    'keyword' => "India travel, travel in India, hotel booking, botel booking, book hotels, book botels, accommodation reservations, travel lodging, online hotel booking, accommodation deals, hotel deals, discount hotels, cheap hotels, international hotel deals, best hotel deals, discount hotel reservation, luxury accommodations, budget stays, travel planning, explore India, vacation bookings, online reservations, affordable lodging " . web_partner_details['company_name'] . ".",
                    'description' => "Now Book Domestic & International Hotels at  " . web_partner_details['company_name'] . ".Travelers can book cheap, budget,luxury Chain hotels at great prices with the best discount ever.  " . web_partner_details['company_name'] . " offers Safe, Clean, Sanitized and Hygienic Hotels Free Wifi. customized holiday packages and special deals on Hotel Bookings",
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Bus' => array(
                "Index" => array(
                    'title' => "Online Bus Booking - Book online AC, Non AC, Volvo, Sleeper & Luxury Buses with " . web_partner_details['company_name'] . ".",
                    'keyword' => "Bus, bus tickets, bus booking, volvo bus, luxury, Sleeper, AC Bus, Volvo, Bus Travel Services, Schedule, bus stops, buses ner me, bus online, buses, bus ticket booking " . web_partner_details['company_name'] . ".",
                    'description' => "Make Online Bus Ticket Bookings across India with Yatra.com and get great discounts. Book Volvo, luxury, semi deluxe, Volvo A/c Sleeper and other buses online. Find bus routes, price, schedule and bus stops near you. " . web_partner_details['company_name'] . ".",
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => "Online Bus Booking - Book online AC, Non AC, Volvo, Sleeper & Luxury Buses with " . web_partner_details['company_name'] . ".",
                    'keyword' => "Bus, bus tickets, bus booking, volvo bus, luxury, Sleeper, AC Bus, Volvo, Bus Travel Services, Schedule, bus stops, buses ner me, bus online, buses, bus ticket booking " . web_partner_details['company_name'] . ".",
                    'description' => "Make Online Bus Ticket Bookings across India with Yatra.com and get great discounts. Book Volvo, luxury, semi deluxe, Volvo A/c Sleeper and other buses online. Find bus routes, price, schedule and bus stops near you. " . web_partner_details['company_name'] . ".",
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => "Online Bus Booking - Book online AC, Non AC, Volvo, Sleeper & Luxury Buses with " . web_partner_details['company_name'] . ".",
                    'keyword' => "Bus, bus tickets, bus booking, volvo bus, luxury, Sleeper, AC Bus, Volvo, Bus Travel Services, Schedule, bus stops, buses ner me, bus online, buses, bus ticket booking " . web_partner_details['company_name'] . ".",
                    'description' => "Make Online Bus Ticket Bookings across India with Yatra.com and get great discounts. Book Volvo, luxury, semi deluxe, Volvo A/c Sleeper and other buses online. Find bus routes, price, schedule and bus stops near you. " . web_partner_details['company_name'] . ".",
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Visa' => array(
                "Index" => array(
                    'title' => 'Visa',
                    'keyword' => 'Visa Keywords',
                    'description' => 'Visa Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => 'Visa',
                    'keyword' => 'Visa Keywords',
                    'description' => 'Visa Description',
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Holiday' => array(
                "Index" => array(
                    'title' => 'Holiday',
                    'keyword' => 'Holiday Keywords',
                    'description' => 'Holiday Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
            'BikeTour' => array(
                "Index" => array(
                    'title' => 'BikeTour',
                    'keyword' => 'BikeTour Keywords',
                    'description' => 'BikeTour Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
            'Car' => array(
                "Index" => array(
                    'title' => 'Car',
                    'keyword' => 'Car Keywords',
                    'description' => 'Car Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => 'Car',
                    'keyword' => 'Car Keywords',
                    'description' => 'Car Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => 'Car',
                    'keyword' => 'Car Keywords',
                    'description' => 'Car Description',
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Cruise' => array(
                "Index" => array(
                    'title' => 'cruise',
                    'keyword' => 'cruise Keywords',
                    'description' => 'cruise Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => 'cruise',
                    'keyword' => 'cruise Keywords',
                    'description' => 'cruise Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => 'cruise',
                    'keyword' => 'cruise Keywords',
                    'description' => 'cruise Description',
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Activities' => array(
                "Index" => array(
                    'title' => 'Activities',
                    'keyword' => 'Activities Keywords',
                    'description' => 'Activities Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => 'Activities',
                    'keyword' => 'Activities Keywords',
                    'description' => 'Activities Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => 'Activities',
                    'keyword' => 'Activities Keywords',
                    'description' => 'Activities Description',
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Tourguide' => array(
                "Index" => array(
                    'title' => 'Tourguide',
                    'keyword' => 'Tourguide Keywords',
                    'description' => 'Tourguide Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Result" => array(
                    'title' => 'Tourguide',
                    'keyword' => 'Tourguide Keywords',
                    'description' => 'Tourguide Description',
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => 'Tourguide',
                    'keyword' => 'Tourguide Keywords',
                    'description' => 'Tourguide Description',
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Contactus' => array(
                "Index" => array(
                    'title' => 'Contactus',
                    'keyword' => 'Contactus Keywords',
                    'description' => 'Contactus Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
            'Blog' => array(
                "Index" => array(
                    'title' => 'Blog',
                    'keyword' => 'Blog Keywords',
                    'description' => 'Blog Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
        );


        if (isset($meta_information[$service][$key])) {
            return $meta_information[$service][$key];
        } else {
            return '';
        }
    }
}

function getResultFareType()
{
    return array('RegularFare' => 'Regular Fare', 'StudentFare' => 'Student Fares', 'MarineFare' => 'Marine Fares', 'SeniorFare' => 'Senior Citizen', 'MilitaryFare' => 'Armed Forces');
}

if (!function_exists('time_picker')) {
    function time_picker()
    {
        $start = new DateTime(date(DATE_ATOM, strtotime('12:00am')));
        $end = new DateTime(date(DATE_ATOM, strtotime('11:59pm')));
        $interval = new DateInterval('PT5M');
        $start->sub($interval);

        $time_slots = [];
        while ($start->add($interval) <= $end) {
            if ($start->format('H') == 00) {
                $min = $start->format('i');
                $time_slots[$start->format('H:i')] = "12:" . $min . ' ' . 'AM';
            } else {
                $time_slots[$start->format('H:i')] = $start->format('H:i A');
            }
        }

        return $time_slots;
    }
}


if (!function_exists('check_inventory_source')) {
    function check_inventory_source($web_partner_id, $supplier_id = null) {
        if (!empty($web_partner_id)) {
            if ($web_partner_id && $supplier_id != 0 || $supplier_id != null ) {
                return "supplier";
            }
            return "web_partner";
        } else {
            return "super_admin";
        }
    }
}
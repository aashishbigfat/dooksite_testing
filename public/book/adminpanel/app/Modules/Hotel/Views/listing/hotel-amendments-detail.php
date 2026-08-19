<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <section class="cart_information p0">
                <div class="container-fluid p0">
                    <div class="sale_bar">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-lg-0">
                                <h5 class="m-0"> Hotel Booking Details (<?php echo $AmendmentInfo['booking_ref_number']; ?>) </h5>
                            </div>
                            <div class="col-md-8 text-end">
                                <a class="badge badge-wt" href="<?php echo site_url('/hotel/confirmation/') . $AmendmentInfo['booking_ref_number']; ?>">Booking Summary</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-12 col-lg-12">
                            <div class="cart_info">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendment" aria-expanded="true" aria-controls="collapseamendment">
                                            <span class="acordian_heading">Amendment Information : 1</span>
                                        </button>
                                        <div id="collapseamendment" class="accordion-collapse collapse show" aria-labelledby="headingamendment" data-bs-parent="#accordionExample">
                                            <div class="accordion-body cart-details-borderline">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Id :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AmendmentInfo['id']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Status :<span class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo ucfirst($AmendmentInfo['amendment_status']); ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo str_replace("_", " ", ucfirst($AmendmentInfo['amendment_type'])); ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Remark:<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AmendmentInfo['remark_from_user']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Confirmation Number:<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AmendmentInfo['confirmation_no']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($AmendmentInfo['created']); ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($AmendmentInfo['AgentInfo']) && !empty($AmendmentInfo['AgentInfo'])) {
                                        $AgentInfo = $AmendmentInfo['AgentInfo'];
                                    ?>
                                        <div class="accordion-item">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAgentInfo" aria-expanded="true" aria-controls="collapseAgentInfo">
                                                <span class="acordian_heading">Company Info </span>
                                            </button>
                                            <div id="collapseAgentInfo" class="accordion-collapse collapse show" aria-labelledby="collapseWebPAgentInfo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Company name :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['company_name']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Company Id
                                                                    :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['company_id']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['login_email']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Mobile Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['mobile_no']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($AmendmentInfo['CustomerInfo']) && !empty($AmendmentInfo['CustomerInfo'])) {
                                        $CustomerInfo = $AmendmentInfo['CustomerInfo']; ?>
                                        <div class="accordion-item">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCustomerInfo" aria-expanded="true" aria-controls="collapseCustomerInfo">
                                                <span class="acordian_heading">Customer Info </span>
                                            </button>
                                            <div id="collapseCustomerInfo" class="accordion-collapse collapse show" aria-labelledby="collapseWebPCustomerInfo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title"> Customer ID :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $CustomerInfo['customer_id']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Customer Name
                                                                    :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $CustomerInfo['first_name'] . " " . $CustomerInfo['last_name']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $CustomerInfo['email_id']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Mobile Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $CustomerInfo['mobile_no']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendmentDetail" aria-expanded="true" aria-controls="collapseamendmentDetail">
                                            <span class="acordian_heading">Amendment Detail </span>
                                        </button>
                                        <div id="collapseamendmentDetail" class="accordion-collapse collapse show" aria-labelledby="headingamendmentDetail" data-bs-parent="#accordionExample">
                                            <div class="accordion-body cart-details-borderline">

                                                <form action="<?php echo site_url('hotel/hotel-amendment-cancellation-charge'); ?>" method="post" tts-form="true" name="cancellation_charge_update">
                                                    <?php
                                                    $amendment_charges = array();
                                                    $charge = 0;
                                                    $service_charge = 0;

                                                    $refund = 0;
                                                    $service_charge_gst = 0;
                                                    $TDSReturnIdentifier = "no";
                                                    $TDSReturnIdentifierChecked = "";
                                                    if ($AmendmentInfo['amendment_charges'] != Null) {
                                                        $amendment_charges = json_decode($AmendmentInfo['amendment_charges'], true);
                                                        $charge = $amendment_charges['Charge'];
                                                        $service_charge = $amendment_charges['ServiceCharge'];
                                                        $refund = $amendment_charges['Refund'];
                                                        $TDSReturnIdentifier = isset($amendment_charges['TDSReturnIdentifier']) ? $amendment_charges['TDSReturnIdentifier'] : "no";
                                                        $TDSReturnIdentifierChecked = $TDSReturnIdentifier == "yes" ? "checked" : "";
                                                        $service_charge_gst = $amendment_charges['GST']['TotalGSTAmount'];
                                                    }

                                                    $couponAmount = isset($FareBreakUp['FareBreakup']['Promocode']['Value']) ? $FareBreakUp['FareBreakup']['Promocode']['Value'] : 0;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12 passenger_faredetail">
                                                            <div class="row">
                                                                <div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                    <p class="mg_right-50">Published Fare</p>
                                                                    <p class="price-width-left " id="publishedFare">
                                                                        <?php echo defaultCurrency; ?> <?php echo $FareBreakUp['FareBreakup']['PublishedPrice']['Value'] - $couponAmount; ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                    <p class="mg_right-50">Offered Fare</p>
                                                                    <p class="price-width-left " id="offeredFare" agentOfferedFare="<?php echo $FareBreakUp2['OfferedFare']['Value'] - $couponAmount; ?>">
                                                                        <?php echo defaultCurrency; ?> <?php echo $FareBreakUp['OfferedFare']['Value'] -$couponAmount; ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                    <p class="mg_right-50">Agent
                                                                        Commission
                                                                    </p>
                                                                    <p class="price-width-left " id="agent_commission" AgentCommission="<?php echo $FareBreakUp2['FareBreakup']['CommEarned']['Value']; ?>">
                                                                        <?php echo defaultCurrency; ?> <?php echo $FareBreakUp['FareBreakup']['CommEarned']['Value']; ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                    <p class="mg_right-50">GST Amount</p>
                                                                    <p class="price-width-left " id="airline_gst_amount" airlineGSTAmount="<?php echo $FareBreakUp2['TotalGST']; ?>">
                                                                        <?php echo defaultCurrency; ?> <?php echo $FareBreakUp['TotalGST']; ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                    <p class="mg_right-50">TDS</p>
                                                                    <p class="price-width-left " id="tds" TDS="<?php echo $FareBreakUp2['FareBreakup']['TDS']['Value']; ?>">
                                                                        <?php echo defaultCurrency; ?> <?php echo $FareBreakUp['FareBreakup']['TDS']['Value']; ?>
                                                                    </p>
                                                                </div>
                                                                <input type="hidden" name="amendment_id" value="<?php echo dev_encode($AmendmentInfo['id']); ?>">
                                                                <?php if ($AmendmentInfo['amendment_type'] == "cancellation" || $AmendmentInfo['amendment_type'] == "full_refund") {
                                                                ?>

                                                                    <div class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                        <div class="form-group row">
                                                                            <label for="charge" class="col-sm-6 col-form-label">Cancellation
                                                                                Charge</label>
                                                                            <div class="col-sm-6">
                                                                                <input class="form-control" type="text" name="charge" value="<?php echo $charge; ?>" id="charge" oninput='getFlightRefundCharges(event)'>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-xs-6 col-6 padd-left-amendment">


                                                                        <div class="form-group row">
                                                                            <label for="service_charge" class="col-sm-6 col-form-label">Cancellation
                                                                                Service Charge</label>
                                                                            <div class="col-sm-6">
                                                                                <input class="form-control" type="text" name="service_charge" value="<?php echo $service_charge; ?>" id="service_charge" oninput='getFlightRefundCharges(event)'>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-sm-4 col-xs-6 col-6 padd-left-amendment">

                                                                        <div class="form-group row">
                                                                            <label for="service_charge_gst" class="col-sm-6 col-form-label">Cancellation
                                                                                Charge GST</label>
                                                                            <div class="col-sm-6">
                                                                                <input class="form-control" type="text" name="service_charge_gst" value="<?php echo $service_charge_gst; ?>" id="service_charge_gst" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                        <div class="form-group row">
                                                                            <label for="refund" class="col-sm-6 col-form-label">Refund
                                                                                Amount</label>
                                                                            <div class="col-sm-6">
                                                                                <input class="form-control" type="text" name="refund" value="<?php echo $refund; ?>" id="refund" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($AmendmentInfo['booking_source'] == 'Wl_b2b') :  ?>
                                                                        <div class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                            <label class="price-width-left text-right form-check-label"><input class="form-check-input" type="checkbox" name="tdsreturn" value="yes" id="tdsreturn" onclick='getFlightRefundCharges(event)' <?php echo $TDSReturnIdentifierChecked; ?>>TDS
                                                                                Return</label>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php } ?>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4"></div>
                                                                <div class="col-md-8">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="padd-left-amendment">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" id="currencyRateCheckbox" name="current_currency_rate_refund" type="checkbox" value="yes" <?php echo (isset($AmendmentInfo['currency_rate']) && $AmendmentInfo['currency_rate']) ? 'checked="checked"' : ''; ?>> if change booking currency rate
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <?php

                                                                        $currencyData = session()->get('currencyinfo');

                                                                        if (isset($currencyData[$AmendmentInfo["booking_currency"]])) {
                                                                            $CurrencySymbol = $currencyData[$AmendmentInfo["booking_currency"]]['currency_symbol'];
                                                                        } else {
                                                                            $CurrencySymbol = '₹';
                                                                        }

                                                                        ?>
                                                                        <div class="col-sm-6">
                                                                            <div class="padd-left-amendment">
                                                                                <?php
                                                                                // Initialize the currency rate based on the amendment details
                                                                                $CurrencyAmounts = 1;
                                                                                if (isset($amendmentDetail['refund_currency_rate']) && $amendmentDetail['refund_currency_rate'] !== NULL) {
                                                                                    $CurrencyAmounts = $amendmentDetail['refund_currency_rate'];
                                                                                } else {
                                                                                    $CurrencyAmounts = $convertion_rate; // Fallback to conversion rate
                                                                                }
                                                                                ?>

                                                                                <label for="">Current currency rate</label>
                                                                                <input type="text" id="currentCurrencyRate" title="Please enter a positive integer" class="form-control" name="current_currency_rate" value="<?php echo htmlspecialchars($CurrencyAmounts); ?>" placeholder="current currency rate" <?php echo (isset($AmendmentInfo['currency_rate']) && $AmendmentInfo['currency_rate']) ? '' : 'readonly'; ?>>
                                                                                <input type="hidden" class="form-control" name="currency_rate" value="<?php echo $AmendmentInfo['currency_rate']; ?>">
                                                                                <input type="hidden" class="form-control" name="booking_currency" value="<?php echo $AmendmentInfo['booking_currency']; ?>">
                                                                                <input type="hidden" class="form-control" name="currency_symbol" value="<?php echo $CurrencySymbol; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <?php if ($AmendmentInfo['refund_status'] != "Close" && ($AmendmentInfo['amendment_type'] == "cancellation" || $AmendmentInfo['amendment_type'] == "full_refund")) { ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button class="btn btn-info pull-right" type="submit">
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cart_info">
                                <div class="accordion" id="hotelbookingaccordionpanels">
                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            Booking Basic Detail
                                        </button>

                                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                            <div class="accordion-body cart-details-borderline">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Booking ID : <span class="cart_info-field--detail"> <?php echo $AmendmentInfo['booking_ref_number']; ?></span>
                                                        </p>
                                                    </div>
                                                    <?php if ($AmendmentInfo['confirmation_no']) { ?>
                                                        <div class="col-md-3">
                                                            Hotel Confirmation No: <?php echo $AmendmentInfo['confirmation_no']; ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Amount : <span class="cart_info-field--detail"><?php echo defaultCurrency; ?> <?php echo $AmendmentInfo['total_price']; ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title"> Booking Status : <span class="cart_info-field--detail"><?php echo $AmendmentInfo['booking_status']; ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Channel Type : <span class="cart_info-field--detail"><?php echo $AmendmentInfo['booking_channel']; ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Created On : <span class="cart_info-field--detail"><?php echo date_created_format($AmendmentInfo['created']); ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Booked By : <span class="cart_info-field--detail"><?php echo ($AmendmentInfo['request_for'] == "Wl_B2B") ? $AmendmentInfo['agent_name'] : $AmendmentInfo['customer_name']; ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Supplier :<span class="cart_info-field--detail"><span> &nbsp;<a href="javascript:void(0)" class=""><?php echo $AmendmentInfo['api_supplier']; ?></a></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Issue Supplier :<span class="cart_info-field--detail"><span> &nbsp;<a href="javascript:void(0)" class=""><?php echo $AmendmentInfo['issue_supplier']; ?></a></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <?php if ($AmendmentInfo['last_cancellation_date'] != "") { ?>
                                                        <div class="col-md-3">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Last Cancellation Date
                                                                    :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""> <?php echo $AmendmentInfo['last_cancellation_date'] != "" ? display_custom_date_format($AmendmentInfo['last_cancellation_date'], true) : ""; ?></a></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Assign User Name
                                                                :<span class="cart_info-field--detail"><span> &nbsp;<?php if ($AmendmentInfo['webpartner_assign_user'] != NULL && $AmendmentInfo['webpartner_assign_user'] != '' && $AmendmentInfo['webpartner_assign_user'] == super_admin_cookie_data()['super_admin_user_details']['id']) { ?>
                                                                        <?php echo $AmendmentInfo['assign_user_name']; ?>
                                                                    <?php } else if ($AmendmentInfo['webpartner_assign_user'] != NULL && $AmendmentInfo['webpartner_assign_user'] != '' && $AmendmentInfo['assign_user'] != super_admin_cookie_data()['super_admin_user_details']['id']) { ?>
                                                                        <?php echo $AmendmentInfo['assign_user_name']; ?>
                                                                        <?php if ($AmendmentInfo['booking_status'] == "Failed" || $AmendmentInfo['booking_status'] == "Processing") { ?>
                                                                            <a class="lead_assignbtn re_aassign" href="<?php echo site_url('/hotel/assign-update-hotel-ticket/') . $ticketData = dev_encode($AmendmentInfo['booking_ref_number']); ?>"> ReAssign</a>
                                                                        <?php } ?>
                                                                        <?php } else {
                                                                                                                        if ($AmendmentInfo['booking_status'] == "Failed" || $AmendmentInfo['booking_status'] == "Processing") { ?>
                                                                            <a class="lead_assignbtn aassign" href="<?php echo site_url('/hotel/assign-update-hotel-ticket/') . $ticketData = dev_encode($AmendmentInfo['booking_ref_number']); ?>"> Assign</a>
                                                                    <?php }
                                                                                                                    } ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="cart_info-field--title">Booking Source : <span class="cart_info-field--detail"><?php echo service_booking_source($AmendmentInfo['booking_source']); ?></span>
                                                        </p>
                                                    </div>



                                                    <?php if (whitelabel['multi_currency'] == 'active'): ?>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Booking Currency :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AmendmentInfo['booking_currency']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Booking Currency Rate :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AmendmentInfo['currency_rate']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $ConversionAmount = show_booking_currency_amounts($AmendmentInfo['total_price'], $AmendmentInfo['booking_currency'], $AmendmentInfo['currency_rate']);
                                                        ?>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Booking Currency Conversion Amount :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $ConversionAmount; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
  
                                                    <div class="col-md-3">
                                                        <a href="<?php echo site_url('/hotel/confirmation/') . $AmendmentInfo['booking_ref_number']; ?>" target="_blank">Booking Summary</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <?php
                                    $paymentInfo = array();

                                    if (isset($AmendmentInfo['paymentInfo']) && $AmendmentInfo['paymentInfo']) {

                                        $paymentInfo = $AmendmentInfo['paymentInfo'];
                                    }

                                    ?>
                                    <?php if (!empty($paymentInfo) && is_array($paymentInfo)) { ?>
                                        <div class="accordion-item">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                <span class="acordian_heading">Payment Process</span>
                                                <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php echo count($paymentInfo); ?></span></span></span>
                                            </button>
                                            <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                <div class="accordion-body cart-details-borderline">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="table-active">
                                                                        <tr>
                                                                            <th>Sl.No.</th>
                                                                            <th>Booking Ref. No.</th>
                                                                            <th>Invoice No.</th>
                                                                            <th>Credit Note No.</th>
                                                                            <th>Date</th>
                                                                            <th>Payments Type/Transaction id</th>
                                                                            <th>Debit</th>
                                                                            <th>Credit</th>
                                                                            <th>Balance</th>
                                                                            <th style="white-space:unset">Remark</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if (!empty($paymentInfo) && is_array($paymentInfo)) {

                                                                            foreach ($paymentInfo as $key => $data) {

                                                                                $prefix_booking_ref_number = '';


                                                                        ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php echo $key + 1; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php $extra_parm = json_decode($data['extra_param'], true);
                                                                                        if (isset($extra_parm['booking_ref_number'])) {
                                                                                            $book_ref_no = explode(',', $extra_parm['booking_ref_number']);
                                                                                            if ($data['transaction_type'] == "credit" && count($book_ref_no) > 1) {
                                                                                                echo '<a href="' . site_url('hotel/details/' . $book_ref_no[0]) . '">' . $book_ref_no[0] . '</a>,';
                                                                                                echo '<a href="' . site_url('hotel/details/' . $book_ref_no[1]) . '">' . $book_ref_no[1] . '</a>';
                                                                                            } else {
                                                                                                echo '<a href="' . site_url('hotel/details/' . $extra_parm['booking_ref_number']) . '">' . $extra_parm['booking_ref_number'] . '</a>';
                                                                                            }
                                                                                        } else {
                                                                                            echo '------';
                                                                                        }
                                                                                        ?>
                                                                                    </td>

                                                                                    <td><?php echo ($data['action_type'] == "booking") ? $data['invoice_number'] : '-'; ?></td>
                                                                                    <td><?php echo ($data['action_type'] == "refund") ? $data['invoice_number'] : '-'; ?></td>
                                                                                    <td>
                                                                                        <?php
                                                                                        echo date_created_format($data['created']);
                                                                                        ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php
                                                                                        $transaction_id = '';

                                                                                        $payment_mode = 'Wallet';

                                                                                        if (isset($AmendmentInfo['PaymentModeinfo']) && ($data['action_type'] == "booking" || $data['action_type'] == "deduct")) {


                                                                                            $payment_mode = $AmendmentInfo['PaymentModeinfo']['payment_mode'];

                                                                                            $transaction_id = "/" . $AmendmentInfo['PaymentModeinfo']['order_id'];
                                                                                        }


                                                                                        echo $payment_mode != "" ? "<b></b> " . $payment_mode . $transaction_id . "<br/>" : ""; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo $data['currency_symbol']; ?> <?php echo custom_money_format($data['debit']); ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo $data['currency_symbol']; ?> <?php echo custom_money_format($data['credit']); ?>
                                                                                    </td>
                                                                                    <td><?php echo $data['currency_symbol']; ?> <?php echo custom_money_format(round_value($data['balance'])); ?></td>
                                                                                    <td style="white-space:unset">
                                                                                        <a href="javascript:void(0);" view-data-modal="true" data-controller='webpartneraccounts' data-href="<?php echo site_url('/webpartneraccounts/view-remark/') . dev_encode($data['id']) ?>">View</a>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        } else {

                                                                            echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                                                                        } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }   ?>

                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsefour" aria-expanded="true" aria-controls="panelsStayOpen-collapsefour">
                                            Hotel Details
                                        </button>
                                        <div id="panelsStayOpen-collapsefour" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingfour">
                                            <div class="accordion-body cart-details-borderline">
                                                <div class="row">
                                                    <?php
                                                    $HotelRoomsDetails = json_decode($AmendmentInfo['hotel_rooms_details'], true);

                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <div class="flightLeftWrapper">
                                                            <div class="flightBookDetail">
                                                                <div class="flightPoint hotelpoint">
                                                                    <div class="row align-items-center ">
                                                                        <div class="col-lg-12 col-md-12 col-12 d-flex justify-content-between">
                                                                            <div>
                                                                                <h5><?php echo $AmendmentInfo['hotel_name']; ?></h5>
                                                                                <p class="m0">
                                                                                    <a href="javascript:voide(0);"><span class="d-block"><i class="fa fa-map-marker"></i> <?php echo $AmendmentInfo['address1']; ?>
                                                                                        </span></a>
                                                                                </p>
                                                                            </div>
                                                                            <div class="text-end">
                                                                                <p class="partialRef text-danger m-0">
                                                                                    <span>
                                                                                        <?php for ($star = 1; $star <= $AmendmentInfo['star_rating']; $star++) { ?>
                                                                                            <i class="fa fa-star"></i>
                                                                                        <?php } ?>
                                                                                    </span>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="hoteldetail">
                                                                    <div class="row align-items-center m0">
                                                                        <div class="col-lg-12 col-md-12 col-12 p0">
                                                                            <div class="my-3">
                                                                                <ul class="d-flex align-items-center justify-content-between">
                                                                                    <li>
                                                                                        <h4 class="m0">Check-in</h4>
                                                                                        <h6 class="m0"><?php echo date('M,Y', strtotime($AmendmentInfo['check_in_date'])) ?> </h6>
                                                                                        <p class="m0"><?php echo date('d,D', strtotime($AmendmentInfo['check_in_date'])) ?></p>
                                                                                    </li>
                                                                                    <li>
                                                                                        <h4 class="m0">Nights</h4>
                                                                                        <h6 class="m0"><?php echo $night = $AmendmentInfo['no_of_nights']; ?></h6>
                                                                                    </li>
                                                                                    <li class="text-end">
                                                                                        <h4 class="m0">Check-out</h4>
                                                                                        <h6 class="m0"><?php echo date('M,Y', strtotime($AmendmentInfo['check_out_date'])) ?> </h6>
                                                                                        <p class="m0"><?php echo date('d,D', strtotime($AmendmentInfo['check_out_date'])) ?></p>
                                                                                    </li>
                                                                                </ul>
                                                                                <ul class="mt-3 d-flex align-items-center justify-content-between mb-3">
                                                                                    <li>
                                                                                        <h4 class="m0">ROOMS &
                                                                                            GUESTS</h4>
                                                                                        <h6 class="m0"><?php echo $AmendmentInfo['no_of_rooms'];
                                                                                                        $guest = json_decode($AmendmentInfo['room_guests'], true);
                                                                                                        ?>
                                                                                            Room <?php echo $roomGuests = getNoguest($guest); ?>
                                                                                            Guest
                                                                                        </h6>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <?php

                                                                        if ($AmendmentInfo['booking_source'] == 'Wl_b2b') {
                                                                            $web_partner_fare_break_up = json_decode($AmendmentInfo['agent_fare_break_up'], true);
                                                                        } else {
                                                                            $web_partner_fare_break_up = json_decode($AmendmentInfo['customer_fare_break_up'], true);
                                                                        }

                                                                        // $web_partner_fare_break_up = json_decode($AmendmentInfo['web_partner_fare_break_up'], true); //pr($web_partner_fare_break_up);exit; 


                                                                        ?>
                                                                        <?php if ($HotelRoomsDetails) {
                                                                            foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {
                                                                                $fare = $web_partner_fare_break_up[$roomKey]; ?>
                                                                                <div class="col-lg-12 col-md-12 col-12 " style="background: #f6f6f6; padding: 10px; margin-bottom: 10px;">
                                                                                    <h5>
                                                                                        Room <?php echo $roomKey + 1; ?></h5>
                                                                                    <ul class=" d-flex align-items-center justify-content-between mb-3 border-top border-bottom py-2">
                                                                                        <li>
                                                                                            <h6 class="m0">Room
                                                                                                Type</h6>
                                                                                        </li>
                                                                                        <li>
                                                                                            <h6 class="m0">
                                                                                                Amenities</h6>
                                                                                        </li>
                                                                                        <li>
                                                                                            <h6 class="m0"> Guests</h6>
                                                                                        </li>
                                                                                    </ul>
                                                                                    <ul class="mt-3 d-flex align-items-center justify-content-between mb-3">
                                                                                        <li>
                                                                                            <span><?php echo $HotelRooms['RoomTypeName']; ?></span>
                                                                                        </li>
                                                                                        <li>
                                                                                            <span><?php if ($HotelRooms['Amenities']) { ?> Incl : </b><?php foreach ($HotelRooms['Amenities'] as $Amenities) { ?>
                                                                                                        <span>
                                                                                                            <?php echo $Amenities; ?>,
                                                                                                        </span>
                                                                                                <?php }
                                                                                                                                                    } ?>
                                                                                            </span>
                                                                                        </li>
                                                                                        <li>
                                                                                            <?php foreach ($HotelRooms['HotelPassenger'] as $HotelPassenger) { ?>
                                                                                                <span>
                                                                                                    <b> <?php echo $HotelPassenger['PaxType'] == 1 ? "Adult" : "Child"; ?> </b> : <?php echo $HotelPassenger['Title'] . " " . $HotelPassenger['FirstName'] . " " . $HotelPassenger['LastName']; ?>
                                                                                                </span>
                                                                                            <?php } ?>
                                                                                        </li>
                                                                                    </ul>
                                                                                    <?php ?>
                                                                                    <?php if (isset($HotelRooms['CancellationPolicies']) && !empty($HotelRooms['CancellationPolicies'])) { ?>
                                                                                        <h6>Cancellation Policy</h6>
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-bordered">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">
                                                                                                            Cancelled on or
                                                                                                            After
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            Cancelled on or
                                                                                                            Before
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            Cancelled on or
                                                                                                            Charges
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <?php foreach ($HotelRooms['CancellationPolicies'] as $item) { ?>
                                                                                                        <tr>
                                                                                                            <td> <?php echo date('d-M-Y', strtotime($item['FromDate'])); ?></td>
                                                                                                            <td><?php echo date('d-M-Y', strtotime($item['ToDate'])); ?></td>
                                                                                                            <td>
                                                                                                                <?php if ($item['ChargeType'] == 1) { ?>
                                                                                                                    <span>
                                                                                                                        Rs. <?php echo $item['Charge']; ?></span><?php } ?>
                                                                                                                <?php if ($item['ChargeType'] == 2) { ?>
                                                                                                                    <span>
                                                                                                                        Rs. <?php echo $item['Charge']; ?>%</span><?php } ?>
                                                                                                                <?php if ($item['ChargeType'] == 3) { ?>
                                                                                                                    <span>
                                                                                                                        Rs. <?php echo $item['Charge']; ?> Night(s) charge</span><?php } ?>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    <?php } ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    <?php } else { ?>
                                                                                        <ul class="mt-3 d-flex align-items-center justify-content-between mb-3">
                                                                                            <li>
                                                                                                <h6>Room Type</h6>
                                                                                            </li>
                                                                                            <li>
                                                                                                <h6>Cancellation
                                                                                                    Policy</h6>
                                                                                            </li>
                                                                                        </ul>
                                                                                        <hr>
                                                                                        <ul class="mt-3 d-flex align-items-center justify-content-between mb-3">
                                                                                            <li>
                                                                                                <h6><?php echo $HotelRooms['RoomTypeName']; ?></h6>
                                                                                            </li>
                                                                                            <li>
                                                                                                <?php echo $HotelRooms['CancellationPolicy']; ?>
                                                                                            </li>
                                                                                        </ul>
                                                                                    <?php } ?>
                                                                                    <div class="col-md-12 passenger_faredetail">
                                                                                        <div class="row">
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0 ">
                                                                                                <p class="mb-0"><strong>Published
                                                                                                        Price</strong>
                                                                                                </p>
                                                                                                <p class="price-width-left">
                                                                                                    <?php echo defaultCurrency; ?> <?php echo $fare['PublishedPrice'] - ($fare['GST']['CGSTAmount'] + $fare['GST']['IGSTAmount'] + $fare['GST']['SGSTAmount']);
                                                                                                        ?>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0">
                                                                                                <p class="mb-0"><strong>Agent
                                                                                                        Commission</strong>
                                                                                                </p>
                                                                                                <p class="price-width-left">
                                                                                                    <?php echo defaultCurrency; ?> <?php echo $fare['AgentCommission']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0">
                                                                                                <p class="mb-0"><strong>Discount</strong>
                                                                                                </p>
                                                                                                <p class="price-width-left">
                                                                                                    <?php echo defaultCurrency; ?> <?php echo $fare['Discount']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0">
                                                                                                <p class="mb-0"><strong>GST
                                                                                                        Amount</strong>
                                                                                                </p>
                                                                                                <p class="price-width-left">
                                                                                                    <?php echo defaultCurrency; ?> <?php echo $fare['GST']['CGSTAmount'] + $fare['GST']['IGSTAmount'] + $fare['GST']['SGSTAmount']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0">
                                                                                                <p class="mb-0"><strong>TDS</strong>
                                                                                                </p>
                                                                                                <p class="price-width-left">
                                                                                                    <?php echo defaultCurrency; ?> <?php echo $fare['TDS']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-md-2 amendment_leftpad col-6 p0">
                                                                                                <p class="mb-0"><b>Total Amount</b></p>
                                                                                                <p class="price-width-left">
                                                                                                    <b> <?php echo defaultCurrency; ?> <?php echo $fare['OfferedPrice'] + $fare['TDS']; ?> </b>
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                        <?php }
                                                                        } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <?php if(!empty($FareBreakUpSupplir)) { ?>  
                                            <div class="accordion-item">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                    <span class="acordian_heading">Supplier Fare Breakup : </span>
                                                </button>
                                                <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body cart-details-borderline">
                                                         
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <?php foreach ($FareBreakUpSupplir['FareBreakup'] as $fare) { ?>
                                                                    <tr>
                                                                        <th scope="row"><?php echo $fare['LabelText']; ?>:
                                                                        </th>
                                                                        <td><?php echo defaultCurrency; ?> <?php echo ($fare['Value']); ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <tr>
                                                                    <th scope="row"><?php echo $FareBreakUpSupplir['TotalAmount']['LabelText']; ?>
                                                                        :
                                                                    </th>
                                                                    <th scope="row"><?php echo defaultCurrency; ?> <?php echo ($FareBreakUpSupplir['TotalAmount']['Value']); ?>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div> 
                                        <?php } ?>





                                        <div class="accordion-item">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                <span class="acordian_heading">Fare Breakup : </span>
                                            </button>
                                            <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                                <div class="accordion-body cart-details-borderline">
                                                    <div class="table-responsive">
                                                        <?php
                                                        if ($FareBreakUp) { ?>
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th scope="row"><?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>
                                                                        :
                                                                    </th>
                                                                    <td><?php echo defaultCurrency; ?>  <?php echo ($FareBreakUp['WebPMarkUp']['Value']); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row"><?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>
                                                                        :
                                                                    </th>
                                                                    <td><?php echo defaultCurrency; ?>  <?php echo ($FareBreakUp['WebPDiscount']['Value']); ?>
                                                                    </td>
                                                                </tr>

                                                                <?php if(isset($FareBreakUp['AgentMarkUp'])){ ?>
                                                                    <tr>
                                                                    <th scope="row"><?php echo $FareBreakUp['AgentMarkUp']['LabelText']; ?>
                                                                        :
                                                                    </th>
                                                                    <td><?php echo $AmendmentInfo['booking_currency']; ?> <?php echo ($FareBreakUp['AgentMarkUp']['Value']); ?>
                                                                    </td>
                                                                </tr>
                                                               <?php } ?>
                                                               <?php if(isset($FareBreakUp['AgentDiscount'])){ ?>
                                                                <tr>
                                                                    <th scope="row"><?php echo $FareBreakUp['AgentDiscount']['LabelText']; ?>
                                                                        :
                                                                    </th>
                                                                    <td><?php echo $AmendmentInfo['booking_currency']; ?> <?php echo ($FareBreakUp['AgentDiscount']['Value']); ?>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>

                                                            </table>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                                                <tr>
                                                                    <th scope="row"><?php echo $fare['LabelText']; ?>:
                                                                    </th>
                                                                    <td><?php echo defaultCurrency; ?>  <?php echo ($fare['Value']); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                                                    :
                                                                </th>
                                                                <th scope="row"><?php echo defaultCurrency; ?>  <?php echo ($FareBreakUp['TotalAmount']['Value']); ?>
                                                                </th>
                                                            </tr>


                                                            
                                                        </table>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th>Service Description</th>
                                                                    <th>Taxable Value</th>
                                                                    <th>
                                                                        CGST@ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                                                        %
                                                                    </th>
                                                                    <th>
                                                                        SGST@ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                                                        %
                                                                    </th>
                                                                    <th>
                                                                        IGST@<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                                                        %
                                                                    </th>
                                                                    <th>Total</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Service Charges</th>
                                                                    <th><?php echo ($FareBreakUp['GSTDetails']['TaxableAmount']); ?></th>
                                                                    <th><?php echo ($FareBreakUp['GSTDetails']['CGSTAmount']); ?></th>
                                                                    <th> <?php echo ($FareBreakUp['GSTDetails']['SGSTAmount']); ?></th>
                                                                    <th> <?php echo ($FareBreakUp['GSTDetails']['IGSTAmount']); ?></th>
                                                                    <th> <?php echo ($FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']); ?></th>
                                                                </tr>
                                                            </table>
                                                    <?php }
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div id="" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingsix">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    function getFlightRefundCharges(evt) {
        var flightgst = 18;
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        /*  if ((charCode > 31) && (charCode <= 48 || charCode > 57)) { */
        var charge = parseFloat(document.getElementById("charge").value);
        var serviceCharge = parseFloat(document.getElementById("service_charge").value);


        var tds = 0;
        if ($("#tdsreturn").prop('checked') == true) {
            var tds = parseFloat(document.getElementById("tds").getAttribute('TDS'));
        }
        var offeredFare = parseFloat(document.getElementById("offeredFare").getAttribute('agentOfferedFare'));

        var agent_commission = parseFloat(document.getElementById("agent_commission").getAttribute('AgentCommission'));
        var agent_discount = 0;
        /*      var agent_discount = parseFloat(document.getElementById("discount").getAttribute('Discount')); */


        var TotalpaxFare = parseFloat((offeredFare + tds)).toFixed(2);
        var serviceChargeGst = calculate_hotel_gst(serviceCharge, flightgst);
        var totalRefundAmount = parseFloat((charge + serviceCharge + serviceChargeGst));


        var refund = (TotalpaxFare - totalRefundAmount).toFixed(2);

        if (!isNaN(serviceChargeGst)) {
            document.getElementById("service_charge_gst").value = serviceChargeGst;
        } else {
            document.getElementById("service_charge_gst").value = 0;
        }
        if (!isNaN(refund)) {
            if (refund < 0) {
                $("[data-message]").addClass('error_popup').html("Please check refund amount value is negative.");
            } else {
                $("[data-message]").removeClass('error_popup').html("");
            }
            document.getElementById("refund").value = parseFloat(refund);
        } else {
            document.getElementById("refund").value = 0;
        }
        /* } */
    }

    function calculate_hotel_gst(serviceCharge, flightgst) {
        var returnval = Math.round(((serviceCharge * flightgst) / 100), 2);
        return returnval;
    }
</script>


<script>
    $(document).ready(function() {
        // Initialize the readonly state based on the checkbox's initial state
        if ($('#currencyRateCheckbox').is(':checked')) {
            $('#currentCurrencyRate').attr('readonly', 'readonly'); // Make readonly if checked
        } else {
            $('#currentCurrencyRate').removeAttr('readonly'); // Editable if unchecked
        }

        $('#currencyRateCheckbox').change(function() {
            if ($(this).is(':checked')) {
                $('#currentCurrencyRate').attr('readonly', 'readonly'); // Make readonly if checked
            } else {
                $('#currentCurrencyRate').removeAttr('readonly'); // Editable if unchecked
            }
        });
    });
</script>
<main class="inner-main-section" role="main">
    <section class="gray-block">
        <div class="container">
            <div class="">
                <div style="width: 850px;height: auto;border: 1px solid #ccc;margin: 0px auto;padding: 20px;background: #ffff;">
                    <div id="print_ticket">
                        <table style="width: 100%; padding: 5px 10px;">
                            <tbody>
                            <tr style="width: 100%;">
                                <td style=" width: 49%; height: 48px;">

                                </td>
                                <td style=" width: 50%; height: auto;text-align: left;padding-bottom: 20px;"><b> Invoice</b></td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%;padding: 0px 0px 40px 10px;">
                            <tbody>
                            <tr>
                                <td style="width: 60%;padding: 3px;">

                                    <p style="margin-bottom: 0px;line-height: 20px;margin-top: 20px;"><b>Name : <?php echo $SuperAdminCompanyName; ?></b></p>
                                    <p style="margin-bottom: 0px;line-height: 20px;"><b>Address :</b> <?php echo $SuperAdminAddress; ?>, <?php echo $SuperAdminCity; ?><?php if ($SuperAdminState != "") {
                                            echo "," . $SuperAdminState;
                                        } ?><?php if ($SuperAdminCountry != "") {
                                            echo "," . $SuperAdminCountry;
                                        } ?><?php if ($SuperAdminPincode != "") {
                                            echo "," . $SuperAdminPincode;
                                        } ?>
                                    </p>



                                    <?php if ($SuperAdminState != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">State : <?php echo $SuperAdminState; ?></p>
                                    <?php } ?>
                                    <?php if ($SuperAdminSupportNo != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">Phone : <?php echo $SuperAdminSupportNo; ?></p>
                                    <?php } ?>
                                    <?php if ($SuperAdminSupportEmail != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">Email : <?php echo $SuperAdminSupportEmail; ?></p>
                                    <?php } ?>
                                    <?php if ($SuperAdminPanNo != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;"><b>PAN Number : <?php echo $SuperAdminPanNo; ?></b></p>
                                    <?php } ?>
                                    <?php if ($SuperAdminGstNo != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;"><b>GST Number : <?php echo $SuperAdminGstNo; ?></b></p>
                                    <?php } ?>

                                </td>
                                <td style="width: 40%; padding:3px;">

                                    <p style="margin-bottom: 0px;line-height: 20px;"><b>Name : <?php echo $CompanyName; ?></b></p>
                                    <p style="margin-bottom: 0px;line-height: 20px;">Address : <?php echo $Address; ?>,
                                        <?php echo $City; ?><?php if ($State != "") {
                                            echo "," . $State;
                                        } ?><?php if ($Country != "") {
                                            echo "," . $Country;
                                        } ?><?php if ($Pincode != "") {
                                            echo "," . $Pincode;
                                        } ?></p>
                                    <?php if ($State != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">State : <?php echo $State; ?></p>
                                    <?php } ?>
                                    <?php if ($State != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">Phone : <?php echo $SupportNo; ?></p>
                                    <?php } ?>
                                    <?php if ($SupportEmail != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;">Email : <?php echo $SupportEmail; ?></p>
                                    <?php } ?>
                                    <?php if ($PanNo != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;"><b>PAN Number : <?php echo $PanNo; ?></b></p>
                                    <?php } ?>
                                    <?php if ($GstNo != "") { ?>
                                        <p style="margin-bottom: 0px;line-height: 20px;"><b>GST Number : <?php echo $GstNo; ?></b></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;margin-top: 10px;  color: #fff;">

                            <tbody>
                            <tr style="border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Confirmation Number :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $InvoiceNumber; ?></td>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Invoice Date :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $InvoiceDate; ?> </td>
                            </tr>

                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Booking Reference number :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $BookingRefNumber; ?> </td>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Booking Date :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $BookingDate; ?> </td>
                            </tr>


                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Ticket Number :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $TicketNo; ?> </td>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Travel PNR :</th>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $TravelOperatorPnr; ?> </td>
                            </tr>




                            </tbody>
                        </table>


                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;margin-top: 10px;  color: #fff;">

                            <tbody>
                            <tr style="border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;text-align: left;"> Origin</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Destination</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Date Of Journey</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Booking Date</th>
                            </tr>

                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $OriginCity; ?></td>

                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $DestinationCity; ?> </td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $DateOfJourney; ?> </td>

                                <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $BookingDate; ?> </td>
                            </tr>

                            </tbody>
                        </table>
                        <h6 style="margin-top: 13px;">Passenger Details</h6>
                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;  color: #fff;">

                            <tbody>
                            <tr>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Customer Name</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Age</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Gender</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Seat Name</th>
                            </tr>
                            <?php if ($TravelersInfo) {
                                foreach ($TravelersInfo as $travelers) { ?>
                                    <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                        <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name']; ?></td>
                                        <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $travelers['age']; ?></td>
                                        <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  ucfirst($travelers['gendar']); ?>
                                        </td>
                                        <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $travelers['seat_name']; ?></td>
                                    </tr>
                                <?php }
                            } ?>

                            </tbody>
                        </table>
                        <h6 style="margin-top: 13px;">Contact Details</h6>
                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;  color: #fff;">

                            <tbody>
                            <tr>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;text-align: left;"> Email</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Contact Number</th>
                            </tr>
                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $PassengerEmail; ?></td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $PassengerContactNumber; ?></td>
                            </tr>

                            </tbody>
                        </table>




                        <?php if ($FareBreakUp) { ?>
                            <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">
                                <h6 style="margin-top: 13px;">PAYMENT DETAILS</h6>
                                <tr>
                                    <th rowspan="<?php echo count($FareBreakUp['FareBreakup']) + 2; ?>" style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border:1px solid #ccc">
                                        <p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">This is an electronic ticket.</p>
                                        <p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Please carry a positive identification for check in.</p>
                                    </th>
                                </tr>
                                <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                    <tr>
                                        <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php echo $fare['LabelText']; ?>:</td>
                                        <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $fare['Value']; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</td>
                                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?></td>
                                </tr>
                            </table>

                            <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">

                                    <tr>
                                        <th style="padding: 5px 7px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Description</th>
                                        <th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Taxable Value</th>
                                        <th style="padding: 5px 7px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?> %</th>
                                        <th style="padding: 5px 7px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>%</th>
                                        <th style="padding: 5px 7px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?> %</th>
                                        <th style="padding: 5px 7px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Total</th>
                                    </tr>


                                    <tr>
                                        <th style="padding: 5px 7px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Charges</th>
                                        <th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></th>
                                        <th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?></th>
                                        <th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?></th>
                                        <th style="padding: 5px 7px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                        <th style="padding: 5px 7px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                    </tr>

                                </table>
                            <?php }
                        } ?>





                        <h5 style="margin-bottom:8px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; font-weight: bold; ">Terms & Conditions.</h5>
                        <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid transparent;">
                            <tr>

                                <td>
                                    <ul style="padding: 0; margin: 0;">
                                        <li style="list-style: disc;margin-left: 11px;">
                                            IMP : This is computer generated invoice signature not required
                                        </li>
                                        <li style="list-style: disc;margin-left: 11px;">
                                            IMP : All Cases & Disputes are subject to New Delhi Jurisdiction
                                        </li>
                                        <li style="list-style: disc;margin-left: 11px;">
                                            IMP :  Refunds & Cancellations are subject to Airlines approval
                                        </li>
                                        <li style="list-style: disc;margin-left: 11px;">
                                            IMP : Service charges as included above are to be collected from the customers on our behalf
                                        </li>
                                        <li style="list-style: disc;margin-left: 11px;">
                                            IMP :  Kindly check all details carefully to avoid un-necessary complications
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
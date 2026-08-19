<main class="inner-main-section" role="main">
    <section class="gray-block">
        <div class="container">
            <div class="">
                <div style="width: 850px;height: auto;border: 1px solid #ccc;margin: 0px auto;padding: 20px;background: #ffff;">
                    <div id="print_ticket">
                        <table style="width: 100%; padding: 5px 10px; ">
                            <tbody>
                            <tr style="width: 100%;">
                                <td style="height: auto;text-align: center;padding-bottom: 20px;"><b> Bus Ticket</b></td>
                            </tr>
                            </tbody>
                        </table>

                        <table style=" text-align: left; float: left; width:50%;  border-collapse: collapse; ">
                            <tbody style="border: 0">
                            <tr style="border: 0">
                                <td style="height:142px;border: 0">

                                    <img  src  =  "<?php echo  $CompanyLogo;  ?>"  alt  =  "<?php echo  $CompanyName; ?>" style="width: 250px;height: 80px;object-fit: contain;">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style=" text-align: left;  width:50%;   border-collapse: collapse;">
                            <tbody style="border: 0">
                            <tr style="border: 0">
                                <td style="height:142px;border: 0">

                                    <p style="margin:0px 0 ; font-size:13px; line-height: 1.4; font-weight: bold;text-align: right;">Name :  <?php echo  $CompanyName; ?></p>
                                    <p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Address : <?php echo  $Address; ?>,
                                        <?php echo  $City; ?>, <?php echo  $State; ?>,
                                        <?php echo  $Country; ?>, <?php echo  $Pincode; ?></p>
                                    <p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">State : <?php echo  $State; ?></p>
                                    <?php if ($SupportNo != "") { ?>
                                        <p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Phone : <?php echo  $SupportNo; ?></p>
                                    <?php } ?>
                                    <?php if ($SupportEmail != "") { ?>
                                        <p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Email : <?php echo  $SupportEmail; ?></p>
                                    <?php  } ?>
                                    <?php if ($GstNo != "") { ?>
                                        <p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right; font-weight: bold;">GST Number : <?php echo $GstNo; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;margin-top: 10px;  color: #fff;">

                            <tbody>
                                <tr style="border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Reference Number</th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Ticket Number</th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Travel PNR</th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Booking Status</th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Total Price</th>
                                </tr>

                                <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $RefrenceNumber; ?></td>

                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $TicketNo; ?> </td>
                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $TravelOperatorPnr; ?> </td>

                                    <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $BookingStatus; ?> </td>
                                    <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $TotalPrice; ?> </td>
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
                        <h6 style="margin-top: 13px;">Transport Details</h6>
                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;  color: #fff;">

                            <tbody>
                                <tr>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;"> Bus Name/Type</th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;text-align: left;">Departure Date/Time </th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7; text-align: left;">Boarding Point Details</th>
                                </tr>
                                <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><b>Bus Name : </b><?php echo  $BusName; ?>
                                        <br /><b>Bus Type : </b><?php echo  $BusType; ?>
                                    </td>
                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $DepartureTime; ?></td>
                                    <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                        <?php echo    isset($BoardingPoints['CityPointLocation']) ? "<b>  Location : </b>" . $BoardingPoints['CityPointLocation'] . "<br/>" : ""; ?>
                                        <?php echo    isset($BoardingPoints['CityPointLandmark']) ? "<b>  Landmark : </b>" . $BoardingPoints['CityPointLandmark'] . "<br/>" : ""; ?>
                                        <?php echo    isset($BoardingPoints['CityPointAddress']) ? "<b>  Address : </b>" . $BoardingPoints['CityPointAddress'] . "<br/>" : ""; ?>
                                        <?php echo    isset($BoardingPoints['CityPointContactNumber']) ? "<b>  Contact Number : </b>" . $BoardingPoints['CityPointContactNumber'] . "<br/>" : ""; ?>
                                        <?php echo    isset($BoardingPoints['CityPointTime']) ? "<b> Boarding   Time : </b>" . str_replace("T", " ", $BoardingPoints['CityPointTime']) . "<br/>" : ""; ?>



                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <h6 style="margin-top: 13px;">Cancellation Details</h6>
                        <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;  color: #fff;">

                            <tbody>
                                <tr>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;text-align: left;"> Cancellation Time </th>
                                    <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;text-align: left;"> Cancellation Charges</th>
                                </tr>
                                <?php if ($CancellationPolicies) {
                                    foreach ($CancellationPolicies as $CancellationPolicy) { ?>
                                <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo $CancellationPolicy['PolicyString']; ?> </td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php if($CancellationPolicy['CancellationChargeType']=="2") { echo  $CancellationPolicy['CancellationCharge']. "%";  } else {  echo  "Rs " .$CancellationPolicy['CancellationCharge']; } ?>
														</td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
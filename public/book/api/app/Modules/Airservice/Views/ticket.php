<style>
    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>
<div style="width: 100%; height: auto;  margin: auto; " >
    <center style="width: 850px; background: #fff; margin: auto;border: 1px solid #cccccc; font-family: 'Poppins', sans-serif; padding: 20px" >
        <table style="text-align: left; float: left;">
            <tr>
                <td style=" width: 50%; height: auto;"></td>
                <td style=" width: 50%; height: auto;text-align: left;padding-bottom: 20px;"><b>E-Ticket</b></td>
            </tr>
            <tr>
                <td style=" width: 252px; height: 49px;">
                    <img src="<?php echo $CompanyLogo; ?>" alt="<?php echo $CompanyName; ?> logo" style="width:auto; height:55px;"></td>
                <td>
                    <p style="  margin: 0px; color: #000; padding-left: 85px;   text-align: left;"><br/>
                        <b>Name: </b><?php echo $CompanyName; ?> <br/>
                        <b>Address : </b><?php echo $Address; ?>
                        , <?php echo $City; ?>,
                        <?php echo $State; ?>
                        ,<?php echo $Country; ?>
                        <?php echo $Pincode; ?>
                        <br/>
                        <?php if ($SupportEmail != "") { ?>
                            <b>Email : </b><?php echo $SupportEmail; ?>
                            <br/>
                        <?php } ?>
                        <?php if ($SupportNo != "") { ?>
                            <b>Phone :</b><?php echo $SupportNo; ?>
                            <br/>
                        <?php } ?>
                        <?php if ($GstNo != "") { ?>
                            <b>GST Number :</b><?php echo $GstNo; ?>
                            <br/>
                        <?php } ?>
                    </p>
                </td>
            </tr>
        </table>

        <table style="text-align: left; width: 100%; float: left; border-top: 1px solid #ddd">

            <tr>
                <td style="border-right: 1px solid #ddd; width: 50%;">
                    <p style="  margin: 0px; color: #000;    text-align: left;"><br/>
                        <b>Booking Time: </b><?php echo $BookingDate; ?> <br/>
                        <b>Booking Id : </b><?php echo $BookingRefNumber; ?><br/>
                        <b>Booking Status : </b><?php echo $Bookingstatus; ?>
                    </p><br/>
                </td>
                <td>
                    <?php
                    foreach ($TicketInvoiceData as $key => $Trips) {
                        if ($Trips['Segments']) {
                            $journey = "";
                            if (count($TicketInvoiceData) == 2) {
                                if ($key == "OB") {
                                    $journey = "Onward";
                                } else if ($key == "IB") {
                                    $journey = "Return";
                                }
                            }

                            ?>
                            <table style="width: 100%">
                                <?php foreach ($Trips['Segments'] as $trips) {
                                    foreach ($trips as $segmentIndicatorkey => $segment) { ?>
                                        <tr>
                                            <td style="width: 50%;">
                                                <img src="<?php echo root_url . 'uploads/airline-images/' . $segment['Airline']['AirlineCode'] . '.png' ?>" width="30">
                                                <?php echo $segment['Airline']['AirlineName']; ?>
                                            </td>
                                            <td style="width: 50%;">
                                                <b><?php echo $Pnr; ?></b> <br>
                                                Airline PNR
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </table>
                            <?php

                        }
                    } ?>
                </td>
            </tr>
        </table>


        <?php //pr($TicketInvoiceData);die;
        foreach ($TicketInvoiceData as $key => $Trips) {
            if ($Trips['Segments']) {
                $journey = "";
                if (count($TicketInvoiceData) == 2) {
                    if ($key == "OB") {
                        $journey = "Onward";
                    } else if ($key == "IB") {
                        $journey = "Return";
                    }
                }

                ?>

                <table style=" text-align: left; width:100%; border-collapse: collapse; ">
                    <tr style="border: 1px solid #000000; width: 100%;">
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #000000;border-top: 1px solid #000000;background: #000000;color: white;">
                            Flight Detail
                        </th>

                        <th style="padding: 5px 7px;text-align: right;font-weight: bold;font-size:12px;border-bottom:1px solid #000000;border-top: 1px solid #000000;background: #000000;color: white; width: 80%;">
                            *Please verify flight timings & terminal info with the airlines
                        </th>
                    </tr>
                </table>
                <table style=" text-align: left; width:100%; border-collapse: collapse; ">

                    <tr style="border: 1px solid #000000;">
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Flight
                        </th>

                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Fare Type
                        </th>

                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Class
                        </th>



                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Departure
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Arrival
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                        Duration
                        </th>
                    </tr>
                    <?php $CheckInBaggage='';$CabinBaggage=''; foreach ($Trips['Segments'] as $trips) {
                        foreach ($trips as $segmentIndicatorkey => $segment) {
                            $CheckInBaggage = $segment['CheckInBaggage'];
                            $CabinBaggage = $segment['CabinBaggage'];

                            ?>
                            <tr style="border: 1px solid #000000;">
                                <td style=" padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">
                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;"> <?php echo $segment['Airline']['AirlineCode']; ?>
                                        -<?php echo $segment['Airline']['FlightNumber']; ?>
                                    </p>
                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;"><?php echo $segment['Airline']['AirlineName']; ?></p>
                                </td>

                                <td style=" padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">
                                    <?php echo 'NA' ?>
                                </td>


                                <td style=" padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">
                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;">
                                        <?php echo $segment['CabinClass']; ?></p>
                                </td>




                                <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">

                                    <p style="margin:0px 0 ; font-weight: bold; font-size:12px; text-align: left; line-height: 1.4;"><?php echo get_flight_time($segment['Origin']['DepartTime']); ?>
                                        , <?php echo get_flight_date($segment['Origin']['DepartTime']); ?> </p>

                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;"><?php echo $segment['Origin']['CityName']; ?>
                                        ,
                                        <?php echo $segment['Origin']['AirportName']; ?>
                                    </p>

                                    <?php if ($segment['Origin']['Terminal'] != "") { ?> <p
                                            style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;">
                                        Terminal - <?php echo $segment['Origin']['Terminal']; ?></p> <?php } ?>
                                </td>
                                <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">

                                    <p style="margin:0px 0 ; font-weight: bold; font-size:12px; text-align: left; line-height: 1.4;"><?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?>
                                        , <?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?></p>

                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;"><?php echo $segment['Destination']['CityName']; ?>
                                        ,
                                        <?php echo $segment['Destination']['AirportName']; ?>
                                    </p>

                                    <?php if ($segment['Destination']['Terminal'] != "") { ?><p
                                            style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;">
                                        Terminal - <?php echo $segment['Destination']['Terminal']; ?></p><?php } ?>
                                </td>
                                <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">
                                    <p style="margin:0px 0 ; font-size:12px; text-align: left; line-height: 1.4;">  <?php echo get_convertToHoursMinsfromMinDuration($segment['Duration']); ?></p>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            <?php }
        } ?>



        <?php foreach ($TicketInvoiceData as $key => $TravelersInfo) {
            if ($TravelersInfo['TravelersInfo']) {
                $journey = "";
                if (count($TicketInvoiceData) == 2) {
                    if ($key == "OB") {
                        $journey = "Onward";
                    } else if ($key == "IB") {
                        $journey = "Return";
                    }
                }

                ?>
                <br/>



                <table style=" text-align: left; width:100%; border-collapse: collapse; ">
                    <tr style="border: 1px solid #000000; width: 100%;">
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #000000;color: white;">
                            <?php echo $journey; ?>
                            Passenger Details
                        </th>

                        <th style="padding: 5px 7px;text-align: right;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #000000;color: white; width: 30%;">

                        </th>
                    </tr>
                </table>
                <table style=" text-align: left;  width:100%; border-collapse: collapse;">
                    <tr style="border: 1px solid #000000;">
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Sr.
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Name & FF
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Sector
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            PNR & Ticket No
                        </th>

                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Baggage<br>
                            <small>Check-in | Cabin</small>
                        </th>

                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Meal
                        </th>
                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                       Addon Baggage
                        </th>

                        <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
                            Document
                        </th>
                    </tr>
                    <?php  foreach ($TravelersInfo['TravelersInfo'] as $pax_key=>$Travelers) { ?>
                        <tr style="border: 1px solid #000000;">
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo $pax_key+1; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;">
                                <?php echo $Travelers['title'] . " " . $Travelers['first_name'] . " " . $Travelers['last_name']; ?>(<?php echo $Travelers['pax_type'][0]; ?>)<br>
                               <!-- <?php /*$date_of_birth = explode('T',$Travelers['date_of_birth']) ;echo date('d/m/Y',strtotime($date_of_birth[0]));*/?><br/>
                                <?php /*if ($Travelers['passport_number']){*/?>
                                    PP: <?php /*echo $Travelers['passport_number'].',';*/?>
                                <?php /*}*/?>
                                N: <?php /*echo $Travelers['country_code'];*/?>
                                <?php /*if ($Travelers['passport_number']){*/?>
                                    ID: <?php /*echo $Travelers['passport_issue_date'];*/?><br/>
                                    ED: <?php /*echo $Travelers['passport_expiry'];*/?><br/>
                                --><?php /*}*/?>
                            </td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo $Sector; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo $Pnr.' '.'('.$Travelers['ticket_number'].')'; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo $CheckInBaggage .'|'.$CabinBaggage ; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: Bold; font-size:13px;"><?php  $mealInfo  =  json_decode($Travelers['meal'],true);  if($mealInfo) { foreach($mealInfo as $meal){ echo  $meal['Origin']."-".$meal['Destination'].":"; echo  $meal['Code']." ( QTY : ".$meal['Quantity']." )<br/>";}} else{ echo "NA";} ?> </td>
			                <td   style="  padding: 5px 7px; text-align: left; font-weight: Bold; font-size:13px;"><?php   $baggageInfo  =  json_decode($Travelers['baggage'],true); if($baggageInfo) {  foreach($baggageInfo as $baggage) { $BaggageAirlineDescription =  isset($baggage['AirlineDescription'])?substr($baggage['AirlineDescription'],0,5):'';   echo $baggage['Origin']."-".$baggage['Destination'].":";echo $BaggageAirlineDescription; }} else{ echo "NA";} ?> </td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo ''; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php }
        } ?>
<?php  foreach($TicketInvoiceData as $key=>$BarCodeInfoInfo) { if($BarCodeInfoInfo['BarCodeInfo'])
		{ 
			$journey  =  "";
			if(count($TicketInvoiceData)==2)
			{
             if($key=="OB"){
				$journey =  "Onward";
			 }
             else if($key=="IB"){
				$journey =  "Return";
			 }
			}
			
			?>
			<br/>
			<h5 style="text-align: left;text-transform: uppercase;font-size: 15px;font-weight: 600;padding: 9px 5px;margin: 0px 0px 0px 0px;display: flex;background: #ffffff;color: black;"><?php echo  $journey; ?> Passenger BarCode Details</h5>
		<table style=" text-align: left;  width:100%; border-collapse: collapse;">
			  <tr style="border: 1px solid #dddddd;">
			    <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">Passenger Name</th>
			    <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">Segment</th>
			    <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">Bar Code</th>
			     </tr>
			  <?php foreach($BarCodeInfoInfo['BarCodeInfo'] as $Travelers) { ?>
			  <tr style="border: 1px solid #dddddd;">
			     <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo  $Travelers['Name']; ?> </td>
			     <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php foreach($Travelers['JourneyInfo'] as $JourneyInfo){ foreach($JourneyInfo as $segemnt) { echo $segemnt['Sector']."<br/>";  }} ?></td>
			     <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo  $Travelers['BarCode']; ?></td>
			         </tr>
			  <?php } ?>
		</table>
		<?php  }}?>
        <?php if ($FareBreakUp) { ?>
                <br/>

            <table style=" text-align: left; width:100%; border-collapse: collapse; ">
                <tr style="border: 1px solid #000000; width: 100%;">
                    <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #000000;background: #000000;color: white;">
                        Payment Details
                    </th>

                    <th style="padding: 5px 7px;text-align: right;font-weight: bold;font-size:12px;border-bottom:1px solid #000000;background: #000000;color: white; width: 30%;"></th>
                </tr>
            </table>

            <table style=" text-align: left;  width:100%; border-collapse: collapse; padding:10px ;" >
                <tr style="border: 1px solid #000000;">
                    <th  rowspan="<?php echo count($FareBreakUp['FareBreakup'])+2;?>" style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border:1px solid #000000">
                        <p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">This is an electronic ticket.</p>
                        <p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Please carry a positive identification for check in.</p>
                    </th>
                </tr>
                <?php foreach($FareBreakUp['FareBreakup'] as $fare) { ?>
                    <tr style="border: 1px solid #000000;">
                        <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #000000"><?php  echo  $fare['LabelText'];?>:</td>
                        <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #000000">₹ <?php  echo  $fare['Value'];?></td>
                    </tr>
                <?php }  ?>
                <tr style="border: 1px solid #000000;">
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #000000; font-weight: bold;"><?php  echo  $FareBreakUp['TotalAmount']['LabelText'];?>:</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #000000; font-weight:bold;">₹ <?php  echo  $FareBreakUp['TotalAmount']['Value'];?></td>
                </tr>
            </table>
        <?php } ?>

        <?php if (0) { ?>
            <h5 style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; font-weight: 600;">
                Rules and Conditions / Flight Note</h5>
            <table style=" text-align: left;  width:100%; border-collapse: collapse; padding:10px ;">
                <?php foreach ($TicketInvoiceData as $FareRuleData) {
                    if ($FareRuleData['FareRule']) {
                        foreach ($FareRuleData['FareRule'] as $FareRule) { ?>
                            <tr style="border: 1px solid #000000;">
                                <th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:12px; border-bottom:1px solid #ccc;"><?php echo $FareRule['Origin'] . "-" . $FareRule['Destination']; ?> </th>
                            </tr>
                            <tr style="border: 1px solid #000000;">
                                <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:12px;"><?php echo $FareRule['FareRuleDetail']; ?>
                                </td>
                            </tr>
                        <?php }
                    }
                } ?>
            </table>
        <?php } ?>
        <br/>
        <table style=" text-align: left; width:100%; border-collapse: collapse; ">
            <tr style="border: 1px solid #000000; width: 100%;">
                <th style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #000000;color: white;">
                    Important Information
                </th>

                <th style="padding: 5px 7px;text-align: right;font-weight: bold;font-size:12px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #000000;color: white; width: 30%;">

                </th>
            </tr>
        </table>
        <table style=" text-align: left;  width:100%; padding:10px; border: 0">
            <tbody style="border: 0">
            <tr style="border: 0">
                <td style="border: 0">1. You should carry a print-out of your booking and present for check-in.</td>
            </tr>

            <tr style="border: 0">
                <td style="border: 0">2. Date &amp; Time is calculated based on the local time of city/destination.</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">3. Use the Reference Number for all Correspondence with us.</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">4. Use the Airline PNR for all Correspondence directly with the Airline</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">5. For departure terminal please check with airline first.</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">6. Please CheckIn atleast 2 hours prior to the departure for domestic flight and 3
                    hours prior to the departure of international flight.
                </td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">7. For rescheduling/cancellation within 4 hours of departure time contact the
                    airline directly
                </td>
            </tr>

            </tbody>
        </table>

    </center>
</div>
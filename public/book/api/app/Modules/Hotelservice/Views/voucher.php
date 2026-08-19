<main class="inner-main-section" role="main">
    <section class="gray-block">
       <div class="container">
        <div class="row justify-content-center">
            <div style="width: 850px;height: auto;border: 1px solid #ccc;margin: 0px auto;padding: 20px;background: #ffff;">
                <div id="print_ticket">
                    <table style="width: 100%; padding: 5px 10px;">
                        <tbody>
                            <tr style="width: 100%;">
                                <td style="height: auto;text-align: left;padding-bottom: 20px;"><b> Hotel Vouchers</b></td>
                            </tr>
                        </tbody>
                    </table>
            
                    <table style="width: 100%;padding: 0px 0px 40px 10px;">
                        <tbody>
                            <tr>
                                <td style="width: 50%;padding: 3px;">
                                    <p style="margin-bottom: 0px;line-height: 20px;margin-top: 20px;"><?php echo  $CompanyName; ?></p>
                                    <p style="margin-bottom: 0px;line-height: 20px;"><?php echo  $Address; ?>, <?php echo  $City; ?>, <?php echo  $State; ?>,
                                    <?php echo  $Country; ?>, <?php echo  $Pincode; ?>
                                    </p>
                                    <?php  if( $SupportNo) { ?> <p style="margin-bottom: 0px;line-height: 20px;">Email : <?php echo  $SupportNo; ?></p><?php  } ?>
                                    <?php  if( $SupportEmail) { ?><p style="margin-bottom: 0px;line-height: 20px;">Phone :<?php echo  $SupportEmail; ?></p><?php  } ?>
                                </td>
                                <td style="width: 50%;padding: 3px;">
                                    <p style="margin-bottom: 0px;line-height: 20px;"><b>Hotel  Details</b></p>
                                    <p style="margin-bottom: 0px;line-height: 20px;"> <?php echo  $HotelName; ?> </p>
                                    <p style="margin-bottom: 0px;line-height: 20px;">
                                        <span><?php echo  $Address1; ?></span>
                                        <?php if($Address2) { ?><span><?php echo  $Address2; ?></span><?php } ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            
                    <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;margin-top: 10px;  color: #fff;">
            
                        <tbody>
                            <tr style="border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">Reference No</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> Confirmation No</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">Hotel Booking Status</th>
                            </tr>
            
                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $RefrenceNumber; ?></td>
            
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $ConfirmationNo; ?> </td>
            
                                <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $BookingStatus; ?> </td>
                            </tr>
            
                        </tbody>
                    </table>
            
                    <h6 style="margin-top: 10px;">Passenger Details</h6>
                    <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;  color: #fff;">
            
                        <tbody>
                            <tr>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">Customer Name:</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">Check In Date:</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> Check Out Date:</th>
                                <th style="padding: 5px 5px; color: #000;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> Nights:</th>
                            </tr>
            
                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $LeadPassenger ?></td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> <?php echo  $CheckInDate ?></td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $CheckOutDate ?>
                                </td>
                                <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $NoOfNights ?></td>
                            </tr>
            
                        </tbody>
                    </table>
                    <h6 style="margin-top: 10px;">Hotel Details</h6>
                    <table style="width: 100%;   border: 1px solid #C7C7C7;padding: 5px 10px;border-bottom: 1px solid #C7C7C7;  border-collapse: collapse;margin-top: 10px;  color: #fff; margin-bottom: 15px;">
            
                        <tbody>
                            <tr style="border: 1px solid #C7C7C7;padding: 5px 5px;border-bottom: 1px solid #C7C7C7;">
                                <th style="padding: 5px 5px; color: #000;border-bottom: 1px solid #C7C7C7; width: 10%;"> S.No</th>
                                <th style="padding: 5px 5px; color: #000;border-bottom: 1px solid #C7C7C7; width: 34%;"> Room Type</th>
                                <th style="padding: 5px 5px; color: #000;border-bottom: 1px solid #C7C7C7; width: 11%;">Room</th>
                                <th style="padding: 5px 5px; color: #000;border-bottom: 1px solid #C7C7C7; width: 11%;"> Guest</th>
                                <th style="padding: 5px 5px; color: #000;border-bottom: 1px solid #C7C7C7;width: 34%;"> Guests Type</th>
                            </tr>
                           <?php  if($HotelRoomsDetails) { foreach($HotelRoomsDetails as $roomKey=>$HotelRoomsDetail) { ?>
                            <tr style="padding: 5px 12px;border-bottom: 1px solid #C7C7C7;">
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  ($roomKey+1); ?></td>
            
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"><?php echo  $HotelRoomsDetail['RoomTypeName']; ?> <?php if($HotelRoomsDetail['Amenities']) { ?>  <br>Incl : </b><?php foreach ($HotelRoomsDetail['Amenities'] as $Amenities) {  ?> <span>  
                                    <?php echo  $Amenities; ?>,
                                </span> 
                            <?php } }  ?>
                            </td>
            
                                <td style="color: #333;  padding: 1px 10px;border-bottom: 1px solid #C7C7C7;"> <span style="display: inline-flex;"><?php echo  ($roomKey+1); ?> </span> </td>
                                <td style="color: #333;  padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> <?php echo $HotelRoomsDetail['AdultCount']+$HotelRoomsDetail['ChildCount']; ?></td>
            
                                <td style="color: #333;  padding: 1px 10px;padding: 5px 12px;border-bottom: 1px solid #C7C7C7;"> 
                                    <p style="margin-bottom: 0px;line-height: 20px;">
                                      <?php if($HotelRoomsDetail['AdultCount']) { ?>  <span> <?php echo $HotelRoomsDetail['AdultCount']; ?> Adult(s) </span> <?php } ?>
                                      <?php if($HotelRoomsDetail['ChildCount']) { ?> <span>,<?php echo $HotelRoomsDetail['ChildCount']; ?> Child(s) </span> <?php }  ?>
                                    </p>
                                    <p style="margin-bottom: 0px;line-height: 20px;">
                                    <?php foreach ($HotelRoomsDetail['HotelPassenger'] as $HotelPassenger) {  ?>
                                        <span>
                                        <b> <?php echo  $HotelPassenger['PaxType']==1? "Adult":"Child"; ?> </b> : <?php echo  $HotelPassenger['Title']." ".$HotelPassenger['FirstName']." ".$HotelPassenger['LastName']; ?>
                                        </span>
                                        <?php } ?>
                                   </p>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <table style="width:100%;border-collapse:collapse;  border:1px solid #C7C7C7;  padding: 5px 12px; margin-bottom: 15px;">
                        <tbody>
                            <tr>
                                <td style="border:1px solid #C7C7C7;padding:4px 12px">
                                    <h3 style="padding:2px 0;font-size:14px;">
                                        Remarks</h3>
                                    <div>
                                        Please note that while your booking had been confirmed and is guaranteed, the rooming list with your name may not be adjusted in the hotel's reservation system until closer to arrival.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="border:1px solid #C7C7C7;padding:4px 12px">
                                    <h3 style="padding:2px 0;font-size:14px;">Hotel Policies</h3>
                                    <div><?php echo $HotelPolicyDetail  ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #C7C7C7;padding:4px 10px">
                                    <h3 style="padding:2px 0;margin:2px 0;font-size:14px;">
                                        Support Contact Details:
                                    </h3>
                                    <?php  if( $SupportNo) { ?><p style="margin-bottom: 0px;line-height: 20px;">Mobile No. : <?php echo  $SupportNo; ?> </p> <?php } ?>
                                    <?php  if( $SupportEmail) { ?>  <p style="margin-bottom: 0px;line-height: 20px;">Email Id   :<?php echo  $SupportEmail; ?> </p><?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
 </section>
</main>        
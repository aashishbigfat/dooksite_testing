<div class="page-content">
   <div class="table_title">
      <section class="cart_information">
         <div class="container">
            <div class="sale_bar">
               <div class="tts_row">
                  <div class="tts-col-6">
                     <h3> Hotel Amendment Details (<?php echo  $AmendmentInfo['booking_ref_number']; ?>)</h3>
                  </div>
                  <div class="tts-col-6 text_right">
                     <a class="btn btn-sm btn-info text-white" href="<?php echo site_url('/hotel/confirmation/') .     $AmendmentInfo['booking_ref_number']; ?>">Booking Summary</a>
                  </div>
               </div>
            </div>
            <div class="sale_bar">
               <div class="tts_row container">
               <div class="row">
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Id :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $AmendmentInfo['id'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Status :<span class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo  ucfirst($AmendmentInfo['amendment_status']);?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo str_replace("_"," ",ucfirst($AmendmentInfo['amendment_type']));?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Company Remark:<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $AmendmentInfo['remark_from_web_partner'];?></span></span></p>
									      </div>
									   </div>
                              <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Remark:<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $AmendmentInfo['remark_from_user'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Confirmation Number:<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $AmendmentInfo['confirmation_no'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($AmendmentInfo['created']); ?></span></span></p>
									      </div>
									   </div>
									</div>
               </div>
            </div>
            <div class="sale_bar">
               <div class="tts_row container">
                  <div class="row">
                     <?php
                     $HotelRoomsDetails = json_decode($AmendmentInfo['hotel_rooms_details'], true);
                     ?>
                     <div class="col-lg-9 col-md-12 col-12">
                        <div class="flightLeftWrapper">
                           <div class="flightBookDetail">
                              <div class="flightPoint hotelpoint">
                                 <div class="row align-items-center ">
                                    <div class="col-lg-12 col-md-12 col-12 d-flex align-items-center justify-content-between">
                                       <div>
                                          <h4><?php echo $AmendmentInfo['hotel_name']; ?>
                                             <a href="javascript:voide(0);"><span class="d-block"><i class="fa fa-map-marker"></i> <?php echo $AmendmentInfo['address1']; ?>
                                                </span></a>
                                          </h4>
                                       </div>
                                       <div class="text-end">
                                          <p class="partialRef text-danger">
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
                              <div class="hoteldetail p-2">
                                 <div class="row align-items-center ">
                                    <div class="col-lg-12 col-md-12 col-12 ">
                                       <div class="my-3">
                                          <ul class="d-flex align-items-center justify-content-between">
                                             <li>
                                                <h6>Check-in</h6>
                                                <h3><?php echo date('M,Y', strtotime($AmendmentInfo['check_in_date'])) ?> </h3>
                                                <h4><?php echo date('d,D', strtotime($AmendmentInfo['check_out_date'])) ?></h4>
                                             </li>
                                             <li>
                                                <h6>Nights</h6>
                                                <h5><?php echo $night = $AmendmentInfo['no_of_nights']; ?></h5>
                                             </li>
                                             <li class="text-end">
                                                <h6>Check-out</h6>
                                                <h3><?php echo date('M,Y', strtotime($AmendmentInfo['check_out_date'])) ?> </h3>
                                                <h4><?php echo date('d,D', strtotime($AmendmentInfo['check_out_date'])) ?></h4>
                                             </li>
                                          </ul>
                                          <ul class="mt-3 d-flex align-items-center justify-content-between">
                                             <li>
                                                <h6>ROOMS & GUESTS</h6>
                                                <h3><?php echo $AmendmentInfo['no_of_rooms'];
                                                      $guest = json_decode($AmendmentInfo['room_guests'], true);
                                                      ?>
                                                   Room <?php echo $roomGuests = getNoguest($guest); ?>
                                                   Guest
                                                </h3>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12 ">
                                       <?php if ($HotelRoomsDetails) { ?>
                                          <ul class="mt-3 d-flex align-items-center justify-content-between">
                                             <li>
                                                <h6>Room Type</h6>
                                             </li>
                                             <li>
                                                <h6>Amenities</h6>
                                             </li>
                                             <li>
                                                <h6> Guests</h6>
                                             </li>
                                          </ul>
                                          <hr>
                                          <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {
                                          ?>
                                             <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                <li>
                                                   <h6><?php echo $HotelRooms['RoomTypeName']; ?></h6>
                                                </li>
                                                <li>
                                                   <h6><?php if ($HotelRooms['Amenities']) { ?> Incl : </b><?php foreach ($HotelRooms['Amenities'] as $Amenities) { ?>
                                                            <span>
                                                               <?php echo $Amenities; ?>,
                                                            </span>
                                                      <?php }
                                                                                                         } ?>
                                                   </h6>
                                                </li>
                                                <li>
                                                   <?php foreach ($HotelRooms['HotelPassenger'] as $HotelPassenger) { ?>
                                                      <span>
                                                         <b> <?php echo $HotelPassenger['PaxType'] == 1 ? "Adult" : "Child"; ?> </b> : <?php echo $HotelPassenger['Title'] . " " . $HotelPassenger['FirstName'] . " " . $HotelPassenger['LastName']; ?>
                                                      </span>
                                                   <?php } ?>
                                                </li>
                                             </ul>
                                             <hr>
                                       <?php
                                          }
                                       } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="flightBookDetail">
                              <p>Cancellation Policy</p>
                              <div class="col-lg-12 col-md-12 col-12 ">
                                 <?php if ($HotelRoomsDetails) { ?>
                                    <ul class="mt-3 d-flex align-items-center justify-content-between">
                                       <li>
                                          <h6>Room Type</h6>
                                       </li>
                                       <li>
                                          <h6>Cancellation Policy</h6>
                                       </li>
                                    </ul>
                                    <hr>
                                    <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {
                                    ?>
                                       <ul class="mt-3 d-flex align-items-center justify-content-between">
                                          <li>
                                             <h6><?php echo $HotelRooms['RoomTypeName']; ?></h6>
                                          </li>
                                          <li>
                                             <?php echo $HotelRooms['CancellationPolicy']; ?>
                                          </li>
                                       </ul>
                                       <hr>
                                 <?php
                                    }
                                 } ?>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-12 col-12">
                        <div class="flightRightWrapper">
                           <p class="flightFare">Fare Summary</p>
                           <div class="flightFareTypes">
                              <div id="accordion">
                                 <div class="card card1">
                                    <div class="card-header card_header">
                                       <div class="row ">
                                          <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li>Published Price</li>
                                             </ul>
                                             <ul>
                                                <li>₹ <?php echo $publishedFare; ?></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card-header card_header">
                                       <div class="row ">
                                          <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li>Offered Price</li>
                                             </ul>
                                             <ul>
                                                <li>₹ <?php echo $offeredFare; ?></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card-header card_header">
                                       <div class="row ">
                                          <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li>Discount (-)</li>
                                             </ul>
                                             <ul>
                                                <li>₹ <?php echo $CommEarned; ?></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <?php if(0): ?>
                                    <div class="card-header card_header">
                                       <div class="row ">
                                          <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li>TDS (+)</li>
                                             </ul>
                                             <ul>
                                                <li>₹ <?php echo $TDS = 0; ?></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($couponAmount): ?>
                                    <div class="card-header card_header">
                                       <div class="row ">
                                          <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li>PromoCode Discount (-)</li>
                                             </ul>
                                             <ul>
                                                <li>₹ <?php echo $couponAmount; ?></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="card-header card_header">
                                       <div class="row">
                                          <div class="col-md-12 d-flex align-items-center justify-content-between">
                                             <ul>
                                                <li><strong>Total Amount</strong></li>
                                             </ul>
                                             <ul>
                                                <li>
                                                   <span><strong>₹ <?php echo ($offeredFare + $TDS - $couponAmount); ?></strong></span>
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
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
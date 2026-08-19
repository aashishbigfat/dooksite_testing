<section class="content" ng-app="HotelApp" ng-controller="HotelCtrl">
   <div class="container">
      <div class="row">
         <?php $wl_customer_id = '';
         if (isset($blockResponse['Error']['ErrorCode']) && $blockResponse['Error']['ErrorCode'] == 0) {
            $hotelInfo = $hotelInfoResponse['Result'];
            $HotelRoomsDetails = $blockResponse['Result']['HotelRoomsDetails'];

            $isPANMandatory = array();
            $isPassportMandatory = array();
            $PANMandatory = 0;
            $PassportMandatory = 0;

            if (isset(session()->get('wl_customer')['id'])) {
               $wl_customer_id = session()->get('wl_customer')['id'];

               $email = session()->get('wl_customer')['email_id'];
               $mobile_no = session()->get('wl_customer')['mobile_no'];
            } else {
               $wl_customer_id = '';
               $email = "";
               $mobile_no = "";
            }
         ?>
            <div class="col-lg-9 col-md-12 col-12">
               <form action="<?php echo site_url('hotel/validate-travellers'); ?>" method="post" method="post" tts-form="true" name="hotel-booking">
                  <div class="hotel-details">
                     <div class="card">
                        <div class="card-body">
                           <h6 class="mb-0">Review Your Hotel Details</h6>
                        </div>
                     </div>
                     <div class="card">
                        <div class="d-flex align-items-center justify-content-between card-header bg-transparent">
                           <div>
                              <h6 class="mb-0">
                                 <?php echo get_uc_text_format($hotelInfo['HotelName']); ?>, <?php echo get_uc_text_format($hotelInfo['CountryName']); ?>
                              </h6>
                              <a href="javascript:voide(0);" class="farerule-btn"><i class="fa fa-map-marker"></i> <?php echo get_uc_text_format($hotelInfo['Address']); ?></a>
                           </div>
                           <?php if (isset($hotelInfo['HotelReviewRatings']) && $hotelInfo['HotelReviewRatings'] != "") { ?>
                              <div>
                                 <p class="partialRef">
                                    <?php if (isset($hotelInfo['HotelReviewUrl']) && $hotelInfo['HotelReviewUrl'] != "") { ?>
                                       <a href="<?php echo $hotelInfo['HotelReviewUrl']; ?>" target="_blank">Review
                                          Rating <?php echo $hotelInfo['HotelReviewRatings']; ?> </a>
                                    <?php } else { ?>
                                       <a href="javascript:void(0);">Review
                                          Rating <?php echo $hotelInfo['HotelReviewRatings']; ?> </a>
                                    <?php } ?>
                                 </p>
                              </div>
                           <?php } ?>
                           <div class="partialRef text-warning">
                              <span><?php for ($star = 1; $star <= $hotelInfo['StarRating']; $star++) { ?> <i class="fa fa-star"></i> <?php } ?></span>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-lg-3 col-md-3 col-12">
                                 <?php if (isset($hotelInfo['Images']) and $hotelInfo['Images'][0] != "unknown") { ?>
                                    <img src="<?php echo $hotelInfo['Images'][0] ?>" class="img-fluid">
                                 <?php } else { ?>
                                    <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                                 <?php } ?>
                              </div>
                              <div class="col-lg-9 col-md-9 col-12 ">
                                 <div class="row">
                                    <div class="col-md-3 col-4">
                                       <h6>Check-in</h6>
                                       <?php echo date('M,Y', strtotime($searchRequest['CheckInDate'])) ?>
                                       <span><?php echo date('d,D', strtotime($searchRequest['CheckInDate'])) ?></span>
                                    </div>
                                    <div class="col-md-2 text-center col-4">
                                       <h6>Nights</h6>
                                       <span><?php echo $night = getDateDiffrence($searchRequest['CheckInDate'], $searchRequest['CheckOutDate']); ?></span>
                                    </div>
                                    <div class="col-md-3 text-end col-4">
                                       <h6>Check-out</h6>
                                       <?php echo date('M,Y', strtotime($searchRequest['CheckOutDate'])) ?>
                                       <span><?php echo date('d,D', strtotime($searchRequest['CheckOutDate'])) ?></span>
                                    </div>
                                    <div class="col-md-4 text-end">
                                       <h6>Rooms & Guests</h6>
                                       <?php echo $searchRequest['NoOfRooms']; ?> Room <?php echo $roomGuests = getNoguest($searchRequest['RoomGuests']) ?> Guest
                                    </div>
                                 </div>

                                 <?php if ($HotelRoomsDetails) { ?>
                                    <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) { ?>
                                       <div class="row d-flex justify-content-between mt-3">
                                          <div class="col-md-4 col-4">
                                             <h6>Room Type</h6>
                                             <span><?php echo get_uc_text_format($HotelRooms['RoomTypeName']); ?></span>
                                          </div>
                                          <div class="col-md-4 text-center col-4">
                                             <h6>No.of Guests</h6>
                                             <span><?php echo $searchRequest['RoomGuests'][$roomKey]['Adult']; ?> Adult <?php echo $searchRequest['RoomGuests'][$roomKey]['Child'] > 0 ? " / " . $searchRequest['RoomGuests'][$roomKey]['Child'] . " Child " : ""; ?></span>
                                          </div>
                                          <div class="col-md-4 text-end col-4">
                                             <h6>Cancellation Policy</h6>
                                             <a href="javascript:void(0);" class="" onclick='OpenHotelCommonModal("<?php echo $roomKey ?>","CancellationPolicy")'><span>Cancellation Policy</span></a>
                                          </div>
                                       </div>

                                 <?php $isPANMandatory[] = $HotelRooms['IsPANMandatory'];
                                       $isPassportMandatory[] = $HotelRooms['IsPassportMandatory'];
                                    }
                                    $isPANMandatory = array_unique($isPANMandatory);
                                    $isPassportMandatory = array_unique($isPassportMandatory);
                                    $PANMandatory = $isPANMandatory[0] == 1 ? 1 : 0;
                                    $PassportMandatory = $isPassportMandatory[0] == 1 ? 1 : 0;
                                 } ?>



                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card">
                        <div class="card-header bg-transparent">
                           <div>
                              <h6 class="mb-0">Traveller Details</h6>
                              <span>Please make sure you enter the Name as per your Government photo id.</span>
                           </div>
                        </div>
                       
                        <div class="card-body">
                        <div class="card-header fw-bold my-3 d-flex align-items-center justify-content-between gap-3">
                              <input type="button" id="fillAll" class="form-control" value="Fill All" disabled>
                              <input type="button" id="reset" class="form-control bg-danger text-white" value="Reset">
                           </div>

                          
                           <?php foreach ($searchRequest['RoomGuests'] as $roomGuestKey => $roomGuest) { ?>
                              <div class="card shadow-none">
                                 <div class="card-header fw-bold">
                                    Room <?php echo $roomkey = $roomGuestKey + 1; ?>
                                 </div>
                                 <div class="card-body pt-0">
                                    <input type="hidden" name="ResultIndex" value="<?php echo $_GET['rindex'] ?>">
                                    <input type="hidden" name="SearchTokenId" value="<?php echo $_GET['token'] ?>">
                                    <input type="hidden" name="hcode" value="<?php echo $_GET['hcode'] ?>">
                                    <input type="hidden" name="rtype" value="<?php echo $_GET['rtype'] ?>">
                                    <input type="hidden" name="pancard_requird" value="<?php echo $PANMandatory; ?>">
                                    <input type="hidden" name="passport_requird" value="<?php echo $PassportMandatory; ?>">

                                    <?php for ($adult = 1; $adult <= $roomGuest['Adult']; $adult++) { ?>
                                       <div class="row align-items-center mt-3 gy-3">
                                          <div class="col-lg-1 col-12">
                                             Adult <?php echo $adult; ?>
                                          </div>
                                          <div class="col-lg-2 col-12">
                                             <select class="form-select form-control" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][title]" data-validation="required" data-validation-error-msg="Title is required">
                                                <option value="">Title</option>
                                                <option value="Mr">Mr</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Mrs">Mrs</option>
                                             </select>
                                          </div>
                                          <div class="col-lg-3 col-12">
                                             <input type="text" class="form-control" placeholder="First Name" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][first_name]" data-validation="required alphanumeric" data-validation-error-msg-required="First Name is required" data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                          </div>
                                          <div class="col-lg-3 col-12">
                                             <input type="text" class="form-control" placeholder="Last Name" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][last_name]" data-validation="required alphanumeric" data-validation-error-msg-required="Last Name is required" data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                          </div>
                                          <?php if ($PANMandatory) { ?>
                                             <div class="col-lg-3 col-12 ">
                                                <input type="text" class="form-control" placeholder="PAN Card" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][pancard]" data-validation="required alphanumeric" data-validation-error-msg-required="PAN Card is required" data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                             </div>
                                          <?php } ?>
                                       </div>
                                       <?php if ($PassportMandatory) { ?>
                                          <div class="row align-items-center">
                                             <div class="col-lg-2 col-12 ">
                                                Passport Detail
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Number" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_no]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Number is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Issue Date" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_issue_date]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Issue Date is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date" hotel_pass_issue="true" readonly>
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Expire Date" name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_expire_date]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Expire Date is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date" hotel_pass_expiry="true" readonly>
                                             </div>
                                          </div>
                                    <?php }
                                       //  /*   if ($adult != $roomGuest['Adult']) { */
                                       //       echo "<hr>";
                                       //    /* } */
                                    } ?>
                                    <?php for ($child = 1; $child <= $roomGuest['Child']; $child++) { ?>
                                       <div class="row align-items-center mt-3 gy-3">
                                          <div class="col-lg-1 col-12">Child <?php echo $child; ?></div>
                                          <div class="col-lg-2 col-12 ">
                                             <select class="form-select form-control" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][title]" data-validation="required" data-validation-error-msg="Title is required">
                                                <option value="">Title</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Master" selected>Master</option>
                                             </select>
                                          </div>
                                          <div class="col-lg-3 col-12">
                                             <input type="text" class="form-control" placeholder="First Name" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][first_name]" data-validation="required alphanumeric" data-validation-error-msg-required="First Name is required" data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                          </div>
                                          <div class="col-lg-2 col-12">
                                             <input type="text" class="form-control" placeholder="Last Name" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][last_name]" data-validation="required alphanumeric" data-validation-error-msg-required="Last Name is required" data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                          </div>
                                          <?php if ($PANMandatory) { ?>
                                             <div class="col-lg-2 col-12">
                                                <input type="text" class="form-control" placeholder="PAN Card" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][pancard]" data-validation="required alphanumeric" data-validation-error-msg-required="PAN Card is required" data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                             </div>
                                          <?php } ?>
                                          <div class="col-lg-2 col-12">
                                             <input type="text" class="form-control" placeholder="Age" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][age]" data-validation="required number" data-validation-error-msg-required="Age is required" data-validation-error-msg-number="Please enter a valid Age" value="<?php echo "Age- " . $roomGuest['ChildAge'][($child - 1)]; ?> Yr" readonly>
                                          </div>
                                       </div>
                                       <?php if ($PassportMandatory) { ?>
                                          <div class="row">
                                             <div class="col-lg-2 col-12 ">
                                                Passport Detail
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Number" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_no]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Number is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Issue Date" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_issue_date]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Issue Date is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date" hotel_pass_issue="true" readonly>
                                             </div>
                                             <div class="col-lg-3 col-12">
                                                <input type="text" class="form-control" placeholder="Passport Expire Date" name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_expire_date]" data-validation="required alphanumeric" data-validation-error-msg-required="Passport Expire Date is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date" hotel_pass_expiry="true" readonly>
                                             </div>
                                          </div>
                                    <?php }
                                       // if ($child != $roomGuest['Child']) {
                                       //    echo "<hr>";
                                       // }
                                    } ?>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                     </div>
                     <div class="card">
                        <div class="card-header bg-transparent">
                           <h6 class="mb-0">Contact Details</h6>
                           <span><i class="fa-solid fa-envelope"></i> Your ticket and flight details will be shared here</span>
                        </div>
                        <div class="card-body">
                           <div class="row gy-3 align-items-center">
                              <div class="col-lg-4">
                                 <select class="form-select form-control select_search" name="dial_code" data-validation="required" data-validation-error-msg="Dial Code is required">
                                    <option value="">Dial Code</option>
                                    <?php if ($dial_code) {
                                       foreach ($dial_code as $code) { ?>
                                          <option value="<?php echo $code['phonecode']; ?>" <?php if ($code['phonecode'] == 91) {
                                                                                                echo "selected";
                                                                                             } ?>><?php echo $code['name']; ?> ( <?php echo $code['phonecode']; ?>) </option>
                                    <?php }
                                    } ?>
                                 </select>
                              </div>
                              <div class="col-lg-4">
                                 <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number" data-validation="required number length" data-validation-length="7-15" value="<?php echo $mobile_no ?>" data-validation-error-msg-required="Please enter Mobile Number" data-validation-error-msg-number="Please enter a valid Mobile Number" data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                              </div>
                              <div class="col-lg-4">
                                 <input type="text" class="form-control" name="email" placeholder="Email" data-validation="required email" value="<?php echo $email ?>" data-validation-error-msg-required="Please enter Email" data-validation-error-msg-email="Please enter a valid Email" />
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card">
                        <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                           <div>
                              <h6 class="mb-0">Use GSTIN for this booking (Optional) </h6>
                           </div>
                           <button class="btn btn-danger btn-sm shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-bus-gst" aria-expanded="false" aria-controls=" collapse-bus-gst" onclick="gst_detail(this,'add_gst_detail')">ADD </button>
                        </div>
                        <div class="collapse" id="collapse-bus-gst">
                           <div class="card-body">
                              <p>Claim credit of GST charges. Your taxes may get updated post submitting your GST details.</p>
                              <div class="row gy-3 align-items-center">
                                 <input type="hidden" name="add_gst_detail" id="add_gst_detail" value="false">
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <input type="text" class="form-control" name="gst[number]" placeholder="GST Number" data-validation="required alphanumeric" data-validation-error-msg-required="Please enter GST Number" data-validation-error-msg-alphanumeric="Please enter a valid GST Number">
                                 </div>
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <input type="text" class="form-control" name="gst[name]" placeholder="Registered Company Name" data-validation="required" data-validation-error-msg-required="Please enter Company Name" />
                                 </div>
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <input type="text" class="form-control" name="gst[phone]" placeholder="Registered Contact No" data-validation="required number" data-validation-length="7-15" data-validation-error-msg-required="Please enter Contact No" data-validation-error-msg-number="Please enter a valid Contact No" data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                                 </div>
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <input type="text" class="form-control" name="gst[email]" placeholder="Registered Email" data-validation="required email" data-validation-error-msg-required="Please enter Email" data-validation-error-msg-email="Please enter a valid Email" />
                                 </div>
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <input type="text" class="form-control" name="gst[address]" placeholder="Registered Address" data-validation="required" data-validation-error-msg-required="Please enter Address" />
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php if (!$processedbutton) { ?>
                        <div class="flightGstNumber">
                           <h6 class="text-center text-danger"> Please contact to administrator. </h6>
                        </div>
                     <?php } ?>
                     <?php if ($processedbutton) { ?>
                        <div class="continuePayment text-end">
                           <button type="submit" class="payment-btn">Continue Payment</button>
                        </div>
                     <?php  } ?>
                  </div>
               </form>
            </div>
            <div class="col-lg-3 col-md-12 col-12">
               <div class="sticky-top">
                  <div class="card " id="accordion">
                     <div class="card-header bg-transparent p-3">
                        <h6 class="mb-0"> Fare Summary</h6>
                     </div>
                     <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2" ng-repeat="(key, fare) in FareInfo.FareBreakup">
                           <span>{{ fare.LabelText }}</span>
                           <span ng-if="key === 'Taxes'">{{ CurrencySymbol }} {{ fare.Value}}</span>
                           <span ng-if="key === 'ServiceAndOtherCharge'">{{ CurrencySymbol }} {{ fare.Value }}</span>
                           <span ng-if="key === 'Discount'">{{ CurrencySymbol }} {{ fare.Value }}</span>
                           <span ng-if="key !== 'ServiceAndOtherCharge' && key !== 'Taxes' &&  key !== 'Discount'">{{ CurrencySymbol }} {{ fare.Value }}</span>
                        </div>
                     </div>
                     <div class="card-footer bg-transparent p-3">
                        <div class="d-flex align-items-center justify-content-between">
                           <span><strong>Pay Amount</strong></span>
                           <span><strong>{{ CurrencySymbol }} {{ FareInfo.TotalAmount.Value}}</strong></span>
                        </div>
                     </div>
                  </div>
                  <?php if ($b2c_coupon): ?>
                     <!------------------- Start For Coupon List -------------->
                     <div class="card">
                        <div class="card-header bg-transparent p-3">
                           <h6 class="mb-0"> Promo Code</h6>
                        </div>
                        <div class="card-body">
                           <div class="promo-list promocodediv">
                              <form id="promocodeform">
                                 <label class="form-label">Enter Your Promo Code</label>
                                 <div class="form-group d-flex">
                                    <input type="text" ng-model="CouponCode" class="form-control rounded-0 rounded-start" name="couponCode" placeholder="Apply Promo Code">

                                    <button type="submit" class="btn btn-danger rounded-0 rounded-end applyCode">Apply</button>
                                 </div>
                                 <span id="couponMessage"></span>
                              </form>
                           </div>
                           <ul class="promo-options">
                              <?php foreach ($couponlist as $key => $item) : ?>
                                 <li class="promoList">
                                    <div class="form-check">
                                       <input class="form-check-input fcoupon" type="radio" value="<?= $item['code'] ?>" id="coupon_id<?= $key ?>" onclick="SelectCouponCode(this.value)">
                                       <label class="form-check-label" for="coupon_id<?= $key ?>">
                                          <?= $item['code'] ?>
                                       </label>
                                       <p class="mb-0 text-muted"><?= $item['coupon_desc'] ?></p>
                                    </div>
                                 </li>
                              <?php endforeach; ?>
                           </ul>
                        </div>
                     </div>
                     <!------------------- End For Coupon List -------------->
                  <?php endif; ?>
               </div>
            </div>
         <?php } else { ?>
            <div class="col-md-12">
               <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
               <h5 class="mt-4"><?php echo $blockResponse['Error']['ErrorMessage']; ?></h5>
               <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">SELECT ANOTHER HOTEL</a>
            </div>
         <?php } ?>
      </div>
   </div>
</section>
<!------Start Hotel Common modal------>
<div class="modal fade" id="HotelCommonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content modal_content">
         <div class="modal-header modal_header">
            <h5 class="modal-title">Cancellation Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="hotel-main">
               <div class="row">
                  <div class="col-md-12" contentContainer="true">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!------End Hotel Common modal------>
<!-- error Modal -->
<div class="modal fade" id="error-modal" tabindex="-1" aria-labelledby="error-modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <strong tts-error-message="true"></strong>
         </div>
      </div>
   </div>
</div>
<!-- error Modal -->
<script>
   function OpenHotelCommonModal(roomkey, type) {
      document.cookie = "roomkey = " + roomkey;
      var cancellationPolicy = "<?php $roomkey = isset($_COOKIE['roomkey']) ? $_COOKIE['roomkey'] : '';
                                 echo isset($HotelRoomsDetails[$roomkey]['CancellationPolicy']) ? $HotelRoomsDetails[$roomkey]['CancellationPolicy'] : ''; ?>";
      $("#HotelCommonModal").modal('show');
      $("[contentContainer]").html(cancellationPolicy);
   }
</script>


<script>
   $(document).ready(function() {
      $('#fillAll').prop('disabled', true); 
      function checkFirstRow() {
         var title = $('select[name="pax[1][Adult][1][title]"]').val();
         var firstName = $('input[name="pax[1][Adult][1][first_name]"]').val();
         var lastName = $('input[name="pax[1][Adult][1][last_name]"]').val();
         var pancard = $('input[name="pax[1][Adult][1][pancard]"]').val();

         if (title && firstName && lastName || pancard) {
            $('#fillAll').prop('disabled', false);
         } else {
            $('#fillAll').prop('disabled', true);
         }
      } 
      $('input, select').on('input change', function() {
         checkFirstRow();
      });

      $('#fillAll').click(function() {
         var title = $('select[name="pax[1][Adult][1][title]"]').val();
         var firstName = $('input[name="pax[1][Adult][1][first_name]"]').val();
         var lastName = $('input[name="pax[1][Adult][1][last_name]"]').val();
         var pancard = $('input[name="pax[1][Adult][1][pancard]"]').val();
         $('.row').each(function() {
            if ($(this).find('input, select').length > 0) {
               $(this).find('select[name*="title"]').val(title);
               $(this).find('input[name*="first_name"]').val(firstName);
               $(this).find('input[name*="last_name"]').val(lastName);
               $(this).find('input[name*="pancard"]').val(pancard);
            }
         });
      }); 
      $('#reset').click(function() { 
         $('.row').each(function() {
               $(this).find('select[name*="title"]').val('');
               $(this).find('input[name*="first_name"]').val('');
               $(this).find('input[name*="last_name"]').val('');
               $(this).find('input[name*="pancard"]').val('');
         });  
         $('#fillAll').prop('disabled', true);
      });

   });
</script>

<?php if ($b2c_coupon): ?>
   <script>
      let CouponCode = '';
      let CouponID = '';
      let url = "<?php echo site_url(); ?>"

      function SelectCouponCode(value) {
         CouponCode = value;
         let request = {
            'couponCode': CouponCode,
            'SearchTokenId': "<?php echo $_GET['token']; ?>",
            'rindex': "<?php echo $_GET['rindex']; ?>",
            'hcode': "<?php echo $_GET['hcode']; ?>",
            'CouponId': CouponID
         }
         $('.applyCode').attr('disabled', true).text('Loading...')
         let callUrl = url + 'hotel/promocode';
         callcoupon(request, callUrl)
      }

      $(document).on("submit", '#removepromocodeform', function(e) {
         e.preventDefault();
         $('.removeCode').attr('disabled', true).text('Loading...')
         let fd = {
            'SearchTokenId': "<?php echo $_GET['token']; ?>",
            'rindex': "<?php echo $_GET['rindex']; ?>",
            'CouponId': CouponID
         }
         let callUrl = url + 'hotel/remove-promocode';

         $.ajax({
            url: callUrl, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: fd,
            success: function(response) {
               if (response.StatusCode == 0) {
                  $('.removeCode').attr('disabled', false).text('Apply')
                  $("input[name=couponCode]").val()
                  $('.promocodediv').html('<form id="promocodeform"><label class="form-label">Enter Your Promo Code</label> <div class="form-group d-flex"><input type="text" ng-model="CouponCode" class="form-control rounded-0 rounded-start" name="couponCode" placeholder="Apply Promo Code"> <button type="submit" class="btn btn-danger rounded-0 rounded-end applyCode">Apply</button> </div> <span id="couponMessage"></span> </div> </form>')
                  $('.fcoupon').prop('checked', false);
                  $('#couponMessage').text(response.Message).addClass('text-success')
                  $('#accordion').html(response.FareBreakUpData)
               }
               if (response.StatusCode == 1) {
                  $('#couponMessage').text(response.Message).addClass('text-danger')
               }

            }
         });
         return false;
      });

      $(document).on('submit', '#promocodeform', function(e) {
         e.preventDefault();
         CouponCode = $("input[name=couponCode]").val();
         let request = {
            'couponCode': CouponCode,
            'SearchTokenId': "<?php echo $_GET['token']; ?>",
            'rindex': "<?php echo $_GET['rindex']; ?>",
            'hcode': "<?php echo $_GET['hcode']; ?>",
            'CouponId': CouponID
         }
         $('.applyCode').attr('disabled', true).text('Loading...')
         let callUrl = url + 'hotel/promocode';
         callcoupon(request, callUrl)
      })



      function callcoupon(request, callUrl) {
         let form = $('#promocodeform')
         $.ajax({
            url: callUrl, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: request,
            success: function(response) {
               $('.applyCode').attr('disabled', false).text('Apply')
               if (response.StatusCode == 1) {
                  $('#couponMessage').text(response.ErrorMessage).css('color', '#FF0000')
               }
               if (response.StatusCode == 0 || response.StatusCode == 2) {
                  CouponID = response.CouponID;
                  $('.promocodediv').html('<form id="removepromocodeform"> <div class="mb-3"> <label class="form-label">Enter Your Promo Code</label> <div class="d-flex"> <input type="text" ng-model="CouponCode" class="form-control rounded-0 rounded-start" name="couponCode" placeholder="Apply Promo Code"><input type="hidden" name="couponId" value="' + CouponID + '"> <button type="submit" class="btn btn-danger rounded-0 rounded-end removeCode">Remove</button> </div> <span id="couponMessage"></span> </div> </form>')
                  $("input[name=couponCode]").val(request['couponCode'])
                  $('#couponMessage').text(response.Message).addClass('text-success')
                  $('#accordion').html(response.FareBreakUpData)
               }
            }
         });
      }
   </script>
<?php endif; ?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
   var appHotel = angular.module('HotelApp', []);
   appHotel.controller('HotelCtrl', function($scope) {
      $scope.FareInfo = JSON.parse('<?= json_encode($FareBreakup); ?>');
      console.log($scope.FareInfo);
      $scope.CurrencySymbol = '<?php echo $CurrencySymbol; ?>';
   });
</script>
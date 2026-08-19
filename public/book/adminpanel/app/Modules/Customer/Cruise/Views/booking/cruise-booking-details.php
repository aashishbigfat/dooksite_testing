<div class="page-content">
   <div class="table_title">
      <section class="cart_information p0">
         <?php //pr($bookingDetail);?>
         <div class="container-fluid p0">
            <div class="sale_bar">
               <div class="row align-items-center">
                  <div class="col-md-4 mb-3 mb-lg-0">
                     <h5 class="m0"> Cruise Booking Details (<?php echo  $bookingDetail['booking_ref_number']; ?>)</h5>
                  </div>
                  <div class="col-md-8 text-md-right">
                     <a class  =  "badge badge-wt" href="<?php echo site_url('/cruise/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token']))); ?>">Booking Summary</a>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 col-12 col-lg-12">
                  <div class="cart_info">
                     <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           <span class="acordian_heading">Cart Information : <?php echo $bookingDetail['booking_ref_number']; ?></span>
                           </button>
                           <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="row">
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Amount :<span class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo $bookingDetail['total_price']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Booking Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_status']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Payment Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['payment_status']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Channel Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo (isset($bookingDetail['booking_channel']))?$bookingDetail['booking_channel']:''; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($bookingDetail['created']); ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <a href="<?php echo site_url('/cruise/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token'])));; ?>" class="">Booking Summary</a>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Booking User :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""><?php echo $bookingDetail['staff_name']; ?></a></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-xs-6 col-6">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Booking Source :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""><?php echo service_booking_source($bookingDetail['booking_source']); ?></a></span></span>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                           <span class="acordian_heading">Booking Details</span>
                           </button>
                           <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                       <div class="tts-holiday-review-details">
                                          <div class="my-3">
                                             <ul class="d-flex align-items-center justify-content-between">
                                                <li>
                                                   <h6>Cruise Line</h6>
                                                   <h6><?php echo $bookingDetail['cruise_line_name']?> </h6>
                                                </li>
                                                <li>
                                                   <h6>Ship Name</h6>
                                                   <h6><?php echo $bookingDetail['ship_name'] ?> </h6>
                                                </li>
                                                <li>
                                                   <h6>No. of Travellers</h6>
                                                   <h6><?php echo $bookingDetail['no_of_travellers']?> </h6>
                                                </li>
                                                <li>
                                                   <h6>Travel Date</h6>
                                                   <h6><?php echo $bookingDetail['sailing_date']?> </h6>
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <hr/>
                                    <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                                       <div class="table-responsive travelimp__thanku--responsivewrap">
                                          <h6>Passenger Details</h6>
                                          <table class="table table-bordered table-hover travelimp__thanku--tablewrap">
                                             <thead class="table-active">
                                                <tr>
                                                   <th>Sr.</th>
                                                   <th>Name</th>
                                                   <th>Dob</th>
                                                   <th>Gender</th>
                                                   <th>Passport Number</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php if ($bookingDetail['travellers']) {
                                                   foreach ($bookingDetail['travellers'] as $key => $traveller) {
                                                   	if ($traveller['lead_pax'] == 1) {
                                                   		$bookingDetail['email'] = $traveller['email_id'];
                                                   		$bookingDetail['mobile_no'] = $traveller['mobile_number'];
                                                   	}
                                                   	?>
                                                <tr>
                                                   <td><?php echo $key + 1; ?></td>
                                                   <td>
                                                      <?php echo $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'] ?>
                                                   </td>
                                                   <td>
                                                      <?php echo $traveller['dob']; ?>
                                                   </td>
                                                   <td>
                                                    <?php echo $traveller['gendar'] ?>
                                                   </td>
                                                   <td>
                                                      <?php echo $traveller['passport_no'] ?> 
													</td>
                                                </tr>
                                                <?php }
                                                   } ?>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                           <span class="acordian_heading">Payment Process</span>
                           <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php echo count($bookingDetail['paymentInfo'])?></span></span></span>
                           </button>
                           <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="table-responsive">
                                          <table class="table table-bordered ">
                                             <thead>
                                                <tr>
                                                   <th>Ref. Number</th>
                                                   <th>Remark</th>
                                                   <th>Credit</th>
                                                   <th>Debit</th>
                                                   <th>Type</th>
                                                   <th>Created</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                   if (!empty($bookingDetail['paymentInfo']) && is_array($bookingDetail['paymentInfo'])) {
                                                   	foreach ($bookingDetail['paymentInfo'] as $data) {
                                                   		?>
                                                <tr>
                                                   <td> <?php echo $data['acc_ref_number']; ?></td>
                                                   <td><?php echo $data['remark']; ?></td>
                                                   <td> ₹ <?php echo $data['credit']; ?></td>
                                                   <td> ₹ <?php echo $data['debit']; ?></td>
                                                   <td><?php echo ucfirst($data['action_type']); ?></td>
                                                   <td>
                                                      <?php echo date_created_format($data['created']); ?>
                                                   </td>
                                                </tr>
                                                <?php }
                                                   } else {
                                                   	echo "<tr> <td colspan='6' class='text_center'><b>No Data Found</b></td></tr>";
                                                   } ?>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                           <span class="acordian_heading">Fare Breakup </span>
                           </button>
                           <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="table-responsive">
                                    <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                                       if ($FareBreakUp) { ?>
                                    <table class="table table-bordered table-hover">
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>:</th>
                                          <td>₹ <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?></td>
                                       </tr>
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>:</th>
                                          <td>₹ <?php echo $FareBreakUp['WebPDiscount']['Value']; ?></td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                       <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                       <tr>
                                          <th><?php echo $fare['LabelText']; ?>:</th>
                                          <td>₹ <?php echo $fare['Value']; ?></td>
                                       </tr>
                                       <?php } ?>
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</th>
                                          <td>₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?></td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="table-responsive">
                                    <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                    <table class="table table-bordered table-hover">
                                       <tr>
                                          <th>Service Charges</th>
                                          <th>Taxable Value</th>
                                          <th>CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?> %</th>
                                          <th>SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>%</th>
                                          <th>IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?> %</th>
                                          <th>Total</th>
                                       </tr>
                                       <tr>
                                          <th>Service Charges</th>
                                          <th><?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></th>
                                          <th><?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                       </tr>
                                    </table>
                                    <?php }
                                       } ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                    
                     <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        <span class="acordian_heading">User Information </span>
                        </button>
                        <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                           <div class="accordion-body cart-details-borderline">
                              
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Contact's Email:<span class="cart_info-field--detail">
                                             <span><?php echo $bookingDetail['email']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Pax contact:<span class="cart_info-field--detail"><span> <?php echo $bookingDetail['mobile_no']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                              
                           </div>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <div id="" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingsix">
                           <?php if(isset($UpdateVoucher)&&$UpdateVoucher) { ?>
                                 <form name="web-partner" tts-form='true'
                                    action="<?php echo site_url('cruise/cruise-update-voucher-info'); ?>"
                                    method="POST" id="cruise-update-Voucher">
                                    <div class="row ">
                                       <div class  = "col-md-12">
                                          <h6 class=  "tts-flight-upload-border"></h6>
                                       </div>
                                    </div>
                                    <div class="row ">
                                       <div class="col-md-2">
                                          <div class="form-group">
                                             <label>Issue Supplier *</label>
                                             <select class="form-control" name="supplier">
                                                <option value="" selected>Select</option>
                                                <?php  if($cruiseSupplier) { foreach($cruiseSupplier as $supplier) { ?>
                                                <option value="<?php echo $supplier['supplier_name'];?>" <?php if (isset($bookingDetail['issue_supplier']) && strtolower($bookingDetail['issue_supplier'])==strtolower($supplier['supplier_name'])) {
                                                   echo  "selected";
                                                   
                                                   } ?>><?php echo  $supplier['supplier_name'];?></option>
                                                <?php } } ?>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-2">
                                          <div class="form-group">
                                             <label>Booking Status *</label>
                                             <select class="form-control" name="booking_status" placeholder="Booking Status">
                                                <option value="Confirmed" <?php echo  $bookingDetail['booking_status']=="Confirmed"?"selected":"";?>>Confirmed</option>
                                                <option value="Cancelled" <?php echo  $bookingDetail['booking_status']=="Cancelled"?"selected":"";?>>Cancelled</option>
                                                <option value="Processing" <?php echo  $bookingDetail['booking_status']=="Processing"?"selected":"";?>>Processing</option>
                                                <option value="Failed" <?php echo  $bookingDetail['booking_status']=="Failed"?"selected":"";?>>Failed</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-2">
                                          <div class="form-group">
                                             <label>Payment Status *</label>
                                             <select class="form-control" name="payment_status" placeholder="Payment Status">
                                                <option value="Successful" <?php echo  $bookingDetail['payment_status']=="Successful"?"selected":"";?>>Successful</option>
                                                <?php if($bookingDetail['payment_status']!="Successful") { ?>
                                                <option value="Failed" <?php echo  $bookingDetail['payment_status']=="Failed"?"selected":"";?>>Failed</option>
                                                <option value="Processing" <?php echo  $bookingDetail['payment_status']=="Processing"?"selected":"";?>>Processing</option>
                                                <?php } ?>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-2">
                                          <div class="form-group">
                                             <label>Confirmation Number *</label>
                                             <input class="form-control" name="confirmation_number" placeholder="Confirmation Number" value  =  "<?php echo ($bookingDetail['confirmation_no']);  ?>">
                                          </div>
                                       </div>
                                       <div class="col-md-2">
                                          <div class="form-group form-check">
                                             <label class="form-check-label">
                                             <input class="form-check-input" type="checkbox" value="yes"  name  =  "refundbookingamount">Refund Booking Amount
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-md-2">
                                          <div class="form-group form-check">
                                             <label class="form-check-label">
                                             <input class="form-check-input" type="checkbox" value="yes"  name  =  "deductbookingamount"   <?php if($bookingDetail['payment_status']!="Successful") { echo  "checked"; } ?> >Deduct Booking Amount
                                             </label>
                                          </div>
                                       </div>
                                       <input type  =  "hidden" name  =  "cruise_booking_id" value  =  "<?php echo dev_encode($bookingDetail['id']);  ?>">
                                       <input type  =  "hidden" name  =  "booking_ref_number" value  =  "<?php echo dev_encode($bookingDetail['booking_ref_number']);  ?>">
                                       <div class="col-md-12">
                                          <div class="form-group">
                                             <label>Note</label>
                                             <textarea class="form-control" name="remark" placeholder="Note" rows="3"></textarea>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12 text-md-right">
                                          <button class="btn btn-primary" type="submit" >Update</button>
                                       </div>
                                    </div>
                                 </form>
                           <?php } ?>
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
<div id="cruise-raise-amendment" class="modal">
   <div class="top-model-content">
      <form action="<?php echo site_url('cruise/raise-amendment'); ?>" method="post" tts-form="true"
         name="form_change_status">
         <div class="modal-header">
            <span class="close" onclick="ttsclosemodel(this)">&times;</span>
            <h5>AMENDMENTS</h5>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="tts-col-12">
                  <div class="form-group">
                     <select class="form-control" name="amendment_status">
                        <option value="">Amendment Status</option>
                        <option value="rejected">Rejected</option>
                        <option value="success">Success</option>
                     </select>
                  </div>
                  <input type="hidden" name="booking_ref_number"
                     value="<?php echo $bookingDetail['booking_ref_number']; ?>">
               </div>
               <div class="tts-col-12">
                  <div class="form-group">
                     <label>Remark</label>
                     <textarea class="form-control" name="remark" rows="3" cols="15"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <div class="top-model-footer">
            <div class="row">
               <div class="tts-col-12">
                  <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
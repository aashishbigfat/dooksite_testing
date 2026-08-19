<?php
if ($details) { ?>
   <div class="modal-header">
      <h5 class="modal-title"><? echo $title . ' '; ?>Details</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
   </div>
   <div class="modal-body">
      <div class="row m0">
         <div class="col-md-3">
            <div class="vi_mod_dsc">
               <span>Company ID </span>
               <span class="primary"> <?php echo $details['company_id']; ?> </span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="vi_mod_dsc">
               <span>Admin</span>
               <span class="primary"> <?php echo $details['company_name']; ?></span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="vi_mod_dsc">
               <span>Login Email </span>
               <span class="primary"> <?php echo $details['login_email'] ?> </span>
            </div>
         </div>
         <div class="col-md-3">
            <div class="vi_mod_dsc">
               <span>Payment Status </span>
               <span class="primary"> <?php echo $details['payment_status']; ?> </span>
            </div>
         </div>
      </div>
      <ul class="tabs" id="tts_transaction_detail" role="tablist">
         <li class="tab-link current " role="presentation" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Transaction Details</li>
         <li class="tab-link" role="presentation" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Request Logs </li>
         <li class="tab-link" role="presentation" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Response Logs</li>
      </ul>
      <div class="tab-content" id="tts_transaction_detail">
         <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div id="tts_transaction_detail" class="tab-content current p0 mt-3">
               <h6 class="viewld_h5"><?php echo 'Transaction Details'; ?></h6>
               <div class="table-responsive">
                  <table class="table table-bordered">
                     <tbody class="lead_details">
                        <tr>
                           <th scope="row"><span class=" item-text-head">Transaction Id</span></th>
                           <td><span class="item-text-value"><?php echo $details['transaction_id']; ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Order Id</span></th>
                           <td>
                              <span class="item-text-value"><?php echo $details['order_id']; ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Booking Ref. No.</span></th>
                           <td><span class="item-text-value"><?php if ($details['service'] == "flight") {
                                                                  $bookingids =   explode(",", $details['booking_ref_no']);
                                                                  if (!is_array($bookingids)) {
                                                                     $bookingids = array($bookingids);
                                                                  }
                                                                  foreach ($bookingids as $bookingid) {  ?> <a href="<?php echo site_url('/flight/details/') . $details['booking_prefix'] . $bookingid; ?>" target="_blank"><?php echo $details['booking_prefix'] . $bookingid; ?></a> <?php }
                                                                                                                                                                                                                                                                                 } else {
                                                                                                                                                                                                                                                                                    echo  $details['booking_ref_no'];
                                                                                                                                                                                                                                                                                 } ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Service</span></th>
                           <td><span class="item-text-value"><?php echo str_replace("_", " ", $details['service']); ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Amount</span></th>
                           <td><span class="item-text-value"><i class="fa fa-inr" aria-hidden="true"></i><?php echo custom_money_format($details['amount']); ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Convenience Fee</span></th>
                           <td><span class="item-text-value"><i class="fa fa-inr" aria-hidden="true"></i><?php echo custom_money_format($details['convenience_fee']); ?></span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Payment Status</span></th>
                           <td><span class="item-text-value"><?php echo $details['payment_status']; ?></span></td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Customer Name</span></th>
                           <td><span class="item-text-value"><?php echo $details['customer_name']; ?></span></td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Customer Email</span></th>
                           <td>
                              <span class="item-text-value">
                                 <?php
                                 echo $details['email_id'];

                                 ?>
                              </span>
                           </td>
                        </tr>
                        <tr>
                           <th scope="row"><span class=" item-text-head">Customer Mobile</span></th>
                           <td><span class="item-text-value"><?php echo $details['mobile_number']; ?></span></td>
                        </tr>
                        <tr>
                           <th scope="row"><span class="item-text-head">Created </span></th>
                           <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <?php if ($details['first_name'] || $details['login_email']) { ?>
                  <h6 class="viewld_h5"><?php echo 'Admin Staff User'; ?></h6>
                  <div class="table-responsive">
                     <table class="table table-bordered">
                        <tbody class="lead_details">
                           <?php if ($details['login_email']) { ?>
                              <tr>
                                 <th scope="row"><span class=" item-text-head">Email</span></th>
                                 <td>
                                    <span class="item-text-value"><?php echo $details['login_email']; ?></span>
                                 </td>
                              </tr>
                           <?php } ?>
                           <?php if ($details['first_name']) { ?>
                              <tr>
                                 <th scope="row"><span class=" item-text-head">Staff Name</span></th>
                                 <td>
                                    <span class="item-text-value"><?php echo $details['first_name'] . ' ' . $details['last_name']; ?></span>
                                 </td>
                              </tr>
                           <?php } ?>
                           <?php if ($details['web_partner_remark']) { ?>
                              <tr>
                                 <th scope="row"><span class=" item-text-head">Staff Remark</span></th>
                                 <td><span class="item-text-value"><?php echo $details['web_partner_remark']; ?></span></td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               <?php } ?>
            </div>
         </div>
         <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <div id="tts_request_log" class="tab-content p0">
               <h6 class="viewld_h5"><?php echo 'Request Logs'; ?></h6>
               <div class="table-responsive">
                  <table class="table table-bordered">
                     <tbody class="lead_details">
                        <?php
                        $payment_request = json_decode($details['payment_request'], true);

                        if (!empty($payment_request) && is_array($payment_request)) {
                           foreach ($payment_request as $key => $data) {
                        ?>
                              <tr>
                                 <th scope="row"><span class="item-text-head"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span></th>
                                 <td>
                                    <?php
                                    if (is_array($data) || is_object($data)) {
                                       // Display nested data in a sub-table
                                    ?>
                                       <table class="table table-bordered">
                                          <tbody>
                                             <?php
                                             foreach ($data as $keyone => $item) {
                                             ?>
                                                <tr>
                                                   <th scope="row"><span class="item-text-head"><?php echo ucwords(str_replace('_', ' ', $keyone)); ?></span></th>
                                                   <td><span class="item-text-value">
                                                         <?php
                                                         if (is_array($item) || is_object($item)) {
                                                            // echo '<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>';
                                                         } else {
                                                            echo  $item;
                                                         }
                                                         ?>
                                                      </span>
                                                   </td>
                                                </tr>
                                             <?php
                                             }
                                             ?>
                                          </tbody>
                                       </table>
                                    <?php
                                    } else {
                                       echo '<span class="item-text-value">' . $data . '</span>';
                                    }
                                    ?>
                                 </td>
                              </tr>
                           <?php
                           }
                        } else {
                           ?>
                           <tr>
                              <td colspan="2">No data available</td>
                           </tr>
                        <?php
                        }
                        ?>
                     </tbody>
                  </table>

               </div>
            </div>
         </div>
         <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
            <div id="tts_response_log" class="tab-content p0">
               <div class="col-md-12 p0">
                  <h6 class="viewld_h5"><?php echo 'Response Logs'; ?></h6>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered">


                     <tbody class="lead_details">
                        <?php
                        $payment_response = json_decode($details['payment_response'], true);

                        if (!empty($payment_response) && is_array($payment_response)) {
                           foreach ($payment_response as $key => $data) {
                        ?>
                              <tr>
                                 <th scope="row"><span class="item-text-head"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span></th>
                                 <td>
                                    <?php
                                    if (is_array($data) || is_object($data)) {
                                       // Display nested data in a sub-table
                                    ?>
                                       <table class="table table-bordered">
                                          <tbody>
                                             <?php
                                             foreach ($data as $keyone => $item) {
                                             ?>
                                                <tr>
                                                   <th scope="row"><span class="item-text-head"><?php echo ucwords(str_replace('_', ' ', $keyone)); ?></span></th>
                                                   <td><span class="item-text-value">
                                                         <?php
                                                         if (is_array($item) || is_object($item)) {
                                                            // echo '<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>';
                                                         } else {
                                                            echo  $item;
                                                         }
                                                         ?>
                                                      </span>
                                                   </td>
                                                </tr>
                                             <?php
                                             }
                                             ?>
                                          </tbody>
                                       </table>
                                    <?php
                                    } else {
                                       echo '<span class="item-text-value">' . $data . '</span>';
                                    }
                                    ?>
                                 </td>
                              </tr>
                           <?php
                           }
                        } else {
                           ?>
                           <tr>
                              <td colspan="2">No data available</td>
                           </tr>
                        <?php
                        }
                        ?>
                     </tbody>

                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <div class="row">
         <div class="col-md-12">
         </div>
      </div>
   </div>
<?php } else {
   echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>
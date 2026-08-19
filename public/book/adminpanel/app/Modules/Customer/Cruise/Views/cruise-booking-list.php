<div class="content ">
<div class="page-content">
   <div class="table_title">
      <div class="sale_bar">
         <div class="row align-items-center">
            <div class="col-md-4">
               <h5 class="m-0"> Cruise Booking List</h5>
            </div>
            <div class="col-md-8 tex-end">
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <!----------Start Search Bar ----------------->
               <form class="row g-3 mb-3" action="<?php echo site_url('cruise'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
               
                  <?php $markup_used_for = get_active_whitelable_business();  ?>
                  <?php if ($markup_used_for) : ?>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label class="form-label">Business Type </label>
                              <select class="form-select" agent-customer="true" name="booking_source" onchange ='checkbookingSource(this.value)'>
                              <option value="">Select</option>
                        
                                    <?php
                                    $LoopOutSite = array(); // Initialize
                                    foreach ($markup_used_for as $key => $data) {
                                       $LoopOutSite[] = $key; ?>
                                       <option value="<?php echo $key ?>" <?php if(isset($search_bar_data['booking_source'])) { if($search_bar_data['booking_source']==$key) { echo "selected"; } } ?>><?php echo $key ?></option>
                                    <?php } ?>
                              </select>
                           </div>
                        </div>
                  <?php endif ?>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label class="form-label">Select key to search by *</label>
                           <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                              <option value="">Please select</option>
                              <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                 echo "selected";
                                 } ?>>Booking Ref Number</option>
                              <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                 echo "selected";
                                 } ?>>First Name</option>
                              <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                                 echo "selected";
                                 } ?>>Last Name</option> 
                              <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                 echo "selected";
                                 } ?>>Booking Status</option>
                              <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                 echo "selected";
                                 } ?>>Payment Status</option>
                              <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                 echo "selected";
                                 } ?>>Date Range</option>
                           </select>
                        </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                        } ?>">
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           } else {
                           echo "Value";
                           } ?> 
                        </label>
                        <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                           echo $search_bar_data['value'];
                           } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           echo "disabled";
                           } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           } else {
                               echo 'tts-validatation="Required"';
                           } ?> tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                           echo $search_bar_data['from_date'];
                           } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                           echo $search_bar_data['to_date'];
                           } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                      
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa-solid fa-search"></i></button>
                     </div>
                  </div>
                  

                  <? if (isset($search_bar_data['key'])) : ?>
                  <div class="col-md-2 align-self-center">
                                <div class="search-reset-btn">
                                <a href="<?php echo site_url('cruise'); ?>">Reset Search</a>
                                </div>
                            </div>
                            <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <table class="table table-bordered table-hover">
                  <thead class="table-active">
                     <tr>
                        <th>Ref No.</th>
                        <th>Web Partner Id</th>
                        <th>Passenger Name</th>
                        <th>Cruise Line</th>
                        <th>Ship Name</th>
                        <th>Departure Post</th>
                        <th>Sailing Date</th>
                        <th>Booking Status</th>
                        <th>Price</th>
                        <th>Payment Status</th>
                        <th>Type</th>
                        <th>Booking Source </th>
                        <th>Assign</th>
                        <th>Created</th>
                        <?php if (permission_access("CarExtranet", "edit_car_discount")) { ?>
                        <th>Action</th>
                        <?php }?>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (!empty($list) && is_array($list)) {
                           $count=1;
                            foreach ($list as $data) {
                        
                                if ($data['booking_status'] == 'Confirmed') {
                                    $class = 'active-status';
                                } else {
                                    $class = 'inactive-status';
                                }
                                if ($data['payment_status'] == 'Successful') {
                                    $payment_class = 'active-status';
                                } else {
                                    $payment_class   = 'inactive-status';
                                }
                                $ticketData = dev_encode(json_encode(array("BookingId" => $data['id'], "BookingToken" => $data['tts_search_token'])));
                        
                                ?>
                     <tr>
                     <td> <a href="<?=site_url('cruise/cruise-booking-details/'.$data['booking_ref_number'])?>"><?php echo $data['booking_ref_number']; ?></a></td>
                        <td><?php echo $data['web_partner_id']; ?></td>
                        <td>
                           <?php
                              $name = ucfirst($data['title']).' '.ucfirst($data['first_name']).' '.ucfirst($data['last_name']);
                              
                              echo $name;
                              
                              ?>
                        </td>
                        <td><?php echo $data['cruise_line_name']; ?></td>
                        <td><?php echo $data['ship_name']; ?></td>
                        <td><?php echo $data['departure_port']; ?></td>
                        <td><?php echo $data['sailing_date']; // echo timestamp_to_date($data['sailing_date']); ?></td>
                        <td>
                           <span class="<?php echo $class ?>">
                           <?php echo ucfirst($data['booking_status']); ?>
                           </span>
                        </td>
                        <td><?php echo $data['total_price']; ?></td>
                        <td>
                           <span class="<?php echo $payment_class ?>">
                           <?php echo ucfirst($data['payment_status']); ?>
                           </span>
                        </td>
                        <td>
                        <span><?php echo $data['is_manual'] == 1 ? "" : "Online"; ?></span>
                           <br/>
                           <?php if ($data['is_manual'] == 1 && $data['update_ticket_by'] != null) {
                              $updateByinfo = json_decode($data['update_ticket_by'], true);
                              
                              if (is_array($updateByinfo)) {
                              
                                  echo  "Manual <br/>".$updateByinfo['first_name'] . " " . $updateByinfo['last_name'] ;
                              
                              }
                              
                              ?>
                           <?php } ?>
                        </td>
                        <td> <?php echo service_booking_source($data['booking_source'] ?? ""); ?> </td>
                        <td>
                        <?php if ($data['webpartner_assign_user'] != NULL && $data['webpartner_assign_user'] != '' && $data['webpartner_assign_user'] == admin_cookie_data()['admin_user_details']['web_partner_id']) { ?>
                           <?php echo $data['assign_user_name']; ?><br/>
                           <?php } else if ($data['webpartner_assign_user'] != NULL && $data['webpartner_assign_user'] != '' && $data['webpartner_assign_user'] != admin_cookie_data()['admin_user_details']['web_partner_id']) { ?>
                           <?php echo $data['assign_user_name']; ?><br/>
                           <?php  if ($data['booking_status'] == "Failed" || $data['booking_status'] == "Processing") { ?>
                           <a class="lead_assignbtn re_aassign"
                              href="<?php echo site_url('/cruise/assign-update-cruise-ticket/') . $ticketData = dev_encode($data['booking_ref_number']); ?>"
                              > ReAssign</a>
                           <?php } ?>
                           <?php } else {
                              if ($data['booking_status'] == "Failed" || $data['booking_status'] == "Processing") { ?>
                             <a class="lead_assignbtn aassign flightdisablelinks"
                                href="<?php echo site_url('/cruise/assign-update-cruise-ticket/') . $ticketData = dev_encode($data['booking_ref_number']); ?>"
                              id='countdown<?php echo $count;?>'> Assign</a>
                              <?php $timer_info=checkbookingflighttime($data['created'],'Cruise');  //pr($timer_info); ?>
                              <script>setTimeout(() => { countdown('countdown<?php echo $count;?>', <?php echo $timer_info['Minute'];?>, <?php echo $timer_info['Second'];?>); }, 100);</script>
                           <div ></div>
                           <?php }
                              } ?>
                           <?php $count++;?>
                        </td>
                        <td>
                           <?php echo date_created_format($data['created']); ?>
                        </td>
                        <?php if (permission_access("CarExtranet", "edit_car_discount")) { ?>
                        <td>
                        <a href="<?php echo site_url('cruise/confirmation/' . $ticketData); ?>">View</a>
                           <?php if (($data['webpartner_assign_user'] != NULL && $data['webpartner_assign_user'] != '' && $data['webpartner_assign_user'] == admin_cookie_data()['admin_user_details']['web_partner_id'] && $data['webpartner_assign_user'] == admin_cookie_data()['admin_user_details']['web_partner_id']) || admin_cookie_data()['admin_user_details']['primary_user'] == 1) { ?>
                           <a href="<?php echo site_url('/cruise/get-update-cruise-voucher-info/') . $ticketData = $data['booking_ref_number']; ?>"
                              target="<?php echo target ?>"><i class="tts-icon eye"> Edit <br/></i></a>
                           <?php } ?>
                        </td>
                        <?php }?>
                     </tr>
                     <?php }
                        } else {
                        
                            echo "<tr> <td colspan='15' class='text-center'><b>No Booking Found</b></td></tr>";
                        
                        } ?>
                  </tbody>
               </table>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                     of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found 
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager) : ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row">
               <div class="col-md-4">
                  <h5 class="m-0"> <?php echo $title; ?> List</h5>
               </div>
               <div class="col-md-8 text-end">
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <!----------Start Search Bar ----------------->
            <form action="<?php echo site_url('online-transaction'); ?>" method="GET" class="tts-dis-content" name="newsletter-search" onsubmit="return searchvalidateForm()">
               <div class="row align-items-center">
                  <div class="col-md-3 mb-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by </label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'newsletter-search')" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="transaction_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'transaction_id') {
                              echo "selected";
                              } ?>>Transaction Id
                           </option>
                           <option value="order_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'order_id') {
                              echo "selected";
                              } ?>>Order Id
                           </option>
                           <option value="service" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'service') {
                              echo "selected";
                              } ?>>Service
                           </option>
                           <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                              echo "selected";
                              } ?>>Payment Status
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                        } ?>">
                  </div>
                  <div class="col-md-2 mb-3">
                     <div class="form-group form-mb-20">
                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . "";
                           } else {
                           echo "Value";
                           } ?> </label>
                        <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                           echo $search_bar_data['value'];
                           } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           echo "disabled";
                           } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           } else {
                               /*  echo 'tts-validatation="Required"'; */
                           } ?> tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2 mb-3">
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                           echo $search_bar_data['from_date'];
                           } else {
                           echo date('d M Y');
                           }  ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2 mb-3">
                     <div class="form-group form-mb-20">
                        <label>To Date</label>
                        <input type="hidden" name="export_excel" value="0">
                        <input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                           echo $search_bar_data['to_date'];
                           } else {
                           echo date('d M Y');
                           }  ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group form-mb-20">
                        <label for=""> Status</label>
                        <select name="status" id="" class="form-select">
                           <option value="" <?=(isset($search_bar_data['status']) && $search_bar_data['status'] == '')?'selected':''?>>Select</option>
                           <option value="Successful" <?=(isset($search_bar_data['status']) && $search_bar_data['status'] == 'Successful')?'selected':''?>>Successful</option>
                           <option value="Failed" <?=(isset($search_bar_data['status']) && $search_bar_data['status'] == 'Failed')?'selected':''?>>Failed</option>
                           <option value="Processing" <?=(isset($search_bar_data['status']) && $search_bar_data['status'] == 'Processing')?'selected':''?>>Processing</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group form-mb-20">
                        <label for=""> Service</label>
                        <select name="service" id="" class="form-select">
                           <option value="" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == '')?'selected':''?>>Select</option>
                           <option value="flight" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == 'flight')?'selected':''?>>Flight</option>
                           <option value="hotel" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == 'hotel')?'selected':''?>>Hotel</option>
                           <option value="bus" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == 'bus')?'selected':''?>>Bus</option>
                           <option value="visa" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == 'visa')?'selected':''?>>Visa</option>
                           <option value="Make_Payment" <?=(isset($search_bar_data['service']) && $search_bar_data['service'] == 'Make_Payment')?'selected':''?>>Make Payment</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 mb-3 align-self-end">
                     <div class="form-group form-mb-20">
                        <label></label>
                        <button type="submit" class="badge badge-md badge-primary badge_search" onclick="noExportExcel()">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <div class="col-md-1 mb-3 align-self-end text-end">
                     <div class="form-group form-mb-20">
                        <button type="submit" class="btn_excel" onclick="exportExcel()"><img src="<?php echo site_url('webroot/img/excel.svg'); ?>" class="img_fluid"></button>
                     </div>
                  </div>
                  <div class="col-md-3 mb-3">
                     <? if (isset($search_bar_data['key'])) : ?>
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('online-transaction'); ?>">Reset Search</a>
                     </div>
                     <? endif ?>
                  </div>
                  
               </div>
            </form>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <table class="table table-bordered table-hover">
                  <thead class="table-active">
                     <tr>
                        <th>Transaction Id</th>
                        <th>Order Id</th>
                        <th>Booking Ref No</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Convenience Fee</th>
                        <?php if (permission_access("OnlineTransaction", "OnlineTransaction_status")) { ?>
                        <th>Status</th>
                        <?php } ?>
                        <th>Remark/Staff</th>
                        <th>Company/User</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Payment Source</th>
                        <th>Created Date</th>
                        <?php if (permission_access("OnlineTransaction", "OnlineTransaction_remark")) { ?>
                        <th>Action</th>
                        <?php } ?>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $data) { 
                              $ShowTitle  =  "";
                              $ShowLable  =  "";
                              if($data['payment_source']=="Wl_b2c"){
                                 $ShowLable  =  "Customer Name";
                                 $ShowTitle  =  $data['customer_name'];  
                              }
                              else if($data['payment_source']=="Wl_b2b")
                              {
                                 $ShowLable  =  "Company Name";
                                 $ShowTitle  =  $data['company_name'];
                              }
                           
                                if ($data['payment_status'] == 'Successful') {
                                    $class = 'active-status';
                                } else {
                                    $class = 'inactive-status';
                                } ?>
                     <tr>
                        <td>
                           <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='online-transaction' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('online-transaction/transaction-details/') . dev_encode($data['id']); ?>"><?php echo $data['transaction_id']; ?></a>
                        </td>
                        <td><?php echo $data['order_id']; ?></td>
                        <td>
                           <?php if ($data['service'] == "flight") {
                              $bookingids =   explode(",", $data['booking_ref_number']);
                              if (!is_array($bookingids)) {
                                  $bookingids = array($bookingids);
                              }
                              foreach ($bookingids as $bookingid) {  ?> <a href="<?php echo site_url('/flight/details/') . $bookingid; ?>"><?php echo $bookingid; ?></a> <?php }
                              } else {
                                  echo  $data['booking_ref_number'];
                              } ?>
                        </td>
                        <td><?php echo ucwords(str_replace("_", " ", $data['service'])); ?></td>
                        <td>
                           <i class="fa fa-inr" aria-hidden="true"></i><?php echo custom_money_format($data['amount']); ?></a>
                        </td>
                        <td><i class="fa fa-inr" aria-hidden="true"></i><?php echo custom_money_format($data['convenience_fee']); ?></td>
                        <?php if (permission_access("OnlineTransaction", "OnlineTransaction_status")) { ?>
                        <td>
                            
                           <span class="<?php echo $class ?>" <?php if ($data['payment_status'] != 'Successful') { ?> onclick='payment_status_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo $ShowTitle; ?>","<?php echo $ShowLable; ?>")' <?php } ?>>
                           <?php echo ucfirst($data['payment_status']); ?>
                           </span>
                        </td>
                  <?php  } ?>
                        <td>
                           <?php if ($data['web_partner_remark']) { ?>
                           <b>Remark: </b><?php echo ucfirst($data['web_partner_remark']); ?> <br />  
                           <?php } ?>
                           <?php if ($data['Staffname']) { ?>
                           <b>Staff: </b><?php echo ucfirst($data['Staffname']); ?><br />
                           
                           <?php } ?>
                        </td>
                        <td><?php echo $data['company_name']; ?><br /><?php echo $data['login_email']; ?>
                        </td>
                        <td><?php echo $data['customer_name']; ?></td>
                        <td><?php echo $data['email_id']; ?></td> 
                        <td><?php echo $data['mobile_number']; ?></td>
                        <td> <?php echo service_booking_source($data['payment_source'] ?? ""); ?> </td>
                        <td><?php echo date_created_format($data['created']); ?></td>
  
                        <?php if (permission_access("OnlineTransaction", "OnlineTransaction_remark")) { ?>
                        <td>  
                           <a href="javascript:void(0);" onclick='payment_status_remark_change_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo $ShowTitle; ?>","<?php echo $ShowLable; ?>")'>
                           Edit Remark
                           </a>
                        </td>
                        <?php  } ?>
                     </tr>   
 
                     <?php }
                        } else {
                            echo "<tr> <td colspan='15' class='text-center'><b>No Online Transaction Found</b></td></tr>";
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
<div id="payment_status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Transaction Status Change</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('online-transaction/transaction-status-change'); ?>" method="post" tts-form="true" name="form_password_change">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <p><samp class="tts_agent_label"></samp> <strong class="company_name tts_agent_company"></strong></p>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <select class="form-control" name="payment_status">
                           <option value="" selected>Select Transaction Status</option>
                           <option value="Successful">Successful</option>
                           <option value="Failed">Failed</option>
                           <option value="Processing">Processing</option>
                        </select>
                        <input type="hidden" name="checkedvalue">
                     </div>
                  </div>
                  <input type="hidden" name="payment_id" class="payment_id">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Remark*</label>
                        <textarea class="form-control" name="web_partner_remark" rows="3" cols="15"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Change Status</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="payment_status_remark_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Transaction Remark Change</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('online-transaction/transaction-status-remark-change'); ?>" method="post" tts-form="true" name="form_password_change">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <p><samp class="tts_agent_label"></samp> : <strong class="company_name tts_agent_company"></strong></p>
                     </div>
                  </div>
                  <input type="hidden" name="payment_id" class="payment_id">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Remark*</label>
                        <textarea class="form-control" name="web_partner_remark" rows="3" cols="15"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Change Status</button>
            </div>
         </form>
      </div>
   </div>
</div>
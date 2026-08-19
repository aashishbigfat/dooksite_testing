<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0">Hotel List</h5>
               </div>
               <div class="col-md-8 text-md-end">
               <?php if (permission_access("HotelExtranet", "add_hotelExtranet")) { ?>
                  <a href="<?php echo site_url("hotel-extranet/add-hotel"); ?>" class="badge badge-wt">
                  <i class="fa-solid fa-add"></i>  Add Hotel</a>
                  <?php } ?>
                  <?php if (permission_access("HotelExtranet", "status_hotelExtranet")) { ?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                  <i class="fa-solid fa-exchange"></i> Change Status
                  </button>
                  <?php } ?>
                  <?php if (permission_access("HotelExtranet", "delete_hotelExtranet")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formcarextranetinfolist')"><i class="fa-solid fa-trash"></i>
                  Delete
                  </button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row mb_10">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('hotel-extranet/hotel-list'); ?>" method="GET"
                  class="row"
                  name="hotel-extranet-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Select key to search by *</label>
                        <select name="key" class="form-control"
                           onchange="tts_searchkey(this,'hotel-extranet-search')"
                           tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="hotel_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'hotel_name') {
                              echo "selected";
                              } ?> >Hotel Name
                           </option>
                           <option value="destination" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'destination') {
                              echo "selected";
                              } ?> >Hotel City
                           </option>
                           <option value="property_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'property_type') {
                              echo "selected";
                              } ?> >Property Type
                           </option>
                           <option value="company_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'company_id') { echo "selected"; } ?>>Supplier Id </option>
                           <option value="company_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'company_name') { echo "selected"; } ?>>Supplier Name </option> 

                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                              } ?>>Date Range
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                        } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           } else {
                           echo "Value";
                           } ?> </label>
                        <input type="text" name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              } ?>"
                           class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                                  echo 'tts-validatation="Required"';
                              } ?> tts-error-msg="Please enter value"/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                           value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              } ?>"
                           placeholder="Select From Date" class="form-control"
                           tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label class="form-label">To Date</label>
                        <input type="text" data-searchbar-to="true" name="to_date"
                           value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              } ?>"
                           placeholder="Select To Date" class="form-control"
                           tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <?php if (isset($search_bar_data['key'])): ?>
                  <div class="col-md-3 mb-3">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('hotel-extranet/hotel-list'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <?php endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <?php $trash_uri = "hotel-extranet/remove-hotel"; ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formcarextranetinfolist">
               <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                        <?php if (permission_access("HotelExtranet", "delete_hotelExtranet") || permission_access("HotelExtranet", "status_hotelExtranet")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                           </th>
                           <?php } ?>
                           <th>Hotel Name</th>
                           <th>City</th>
                           <!-- <th>Suppliers</th> -->
                           <th>Property Type</th>
                           <th>Star Rating</th>
                           <th>Status</th>
                           <th>Created Date</th>
                           <th>Modified Date</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($list) && is_array($list)) {
                               foreach ($list as $data) {
                                  
                                   if ($data['status'] == 'active') {
                                       $status_class = 'active-status';
                                   } else {
                                       $status_class = 'inactive-status';
                                   }
                                   ?>
                        <tr>
                        <?php if (permission_access("HotelExtranet", "delete_hotelExtranet") || permission_access("HotelExtranet", "status_hotelExtranet")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                 value="<?php echo $data['id']; ?>"/></label>
                           </td>
                        <?php } ?>
                           <td>
                              <?php echo $data['hotel_name']; ?>
                           </td>
                           <td>
                              <?php echo $data['hotel_city']; ?>
                           </td>
                           <?php if(0) {  ?>
                           <td>
                              <?php
                                 if ($data['company_name']) {
                                       echo ucfirst($data['company_name']) . ' ( ' . $data['company_id'] . ')';
                                 }
                                 ?> 
                              </td>
                              <?php } ?>
                           <td><?php echo $data['property_type']; ?></td>
                           <td>
                              <?php echo $data['hotel_star_rating']; ?>
                           </td>
                           <td>
                              <span class="<?php echo $status_class ?>">
                              <?php echo ucfirst($data['status']); ?>
                              </span>
                           </td>
                           <td><?php echo date_created_format($data['created']); ?></td>
                           <td><?php  if (isset($data['modified'])) {  echo date_created_format($data['modified']);  } else {  echo '-';  } ?> 
                           </td>
                           <?php if (permission_access("HotelExtranet", "edit_hotelExtranet") || permission_access("HotelExtranet", "list_addon") || permission_access("HotelExtranet", "list_room")) { ?>
                           <td>  
                             
                                 <button class="actbtn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                 </button>
                                 <?php json_encode(array('hotel_id'=>$data['id'],'supplier_id'=>$data['supplier_id'])); ?>
                                 <div class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo site_url('/hotel-extranet/edit-hotel/') . dev_encode($data['id']); ?>">Edit Hotel</a></li>
                                   <?php if(0){ ?>
                                    <li><a class="dropdown-item" href="<?php echo site_url('/hotel-extranet/addon-list/') . dev_encode($data['id']); ?>">Hotel Addon List</a></li>
                                   <?php } ?>
                                    <li><a class="dropdown-item" href="<?php echo site_url('/hotel-extranet/room-list/') . dev_encode(json_encode(array('hotel_id'=>$data['id'],'supplier_id'=>$data['supplier_id']))); ?>">Room List</a></li>
                                 </div>
                              
                           </td>
                           <?php } ?>
                        </tr>
                        <?php }
                           } else {
                               echo "<tr> <td colspan='13' class='text-center'><b>No Hotel Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
               </div>
            </form>
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
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
   <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title">Change Status</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo site_url('hotel-extranet/hotel-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
         <div class="modal-body">
            <div class="row">
               <div class="tts-col-12">
                  <div class="form-group form-mb-20">
                     <select class="form-control" name="status">
                        <option value="" selected="selected">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                     </select>
                     <input type="hidden" name="checkedvalue">
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-primary" type="submit" value="Save">Save</button>
         </div>
      </form>
   </div>
</div>
<div class="content ">
   <div class="page-content">
      <?php echo view('\Modules\HotelExtranet\Views\menu-bar'); ?>
      <div class="page-content-area">
         <div class="card-body">
            
               
                  
                     <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                           <h5 class="m-0">Amenity List</h5>
                        </div>
                        <div class="col-md-8 text-md-end">
                           <?php if (permission_access("HotelExtranet", "add_amenity")) {  ?>
                           <button class="badge badge-wt" view-data-modal="true" data-controller='cruise'
                              data-href="<?php echo site_url('hotel-extranet/add-amenity-template') ?>">
                           <i class="fa-solid fa-add"></i> Add Amenity
                           </button>
                           <?php }  ?>
                           <?php if (permission_access("HotelExtranet", "status_amenity")) {  ?>
                           <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                           <i class="fa-solid fa-exchange"></i> Change Status
                           </button>
                           <?php }  ?>
                           <?php if (permission_access("HotelExtranet", "delete_amenity")) {  ?>
                           <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                              onclick="confirm_delete('formgallerylist')"><i class="fa-solid fa-trash"></i>
                           Delete
                           </button>
                           <?php } ?>
                        </div>
                     </div>
                  
                  
                     
                        <!----------Start Search Bar ----------------->
                     <form action="<?php echo site_url('hotel-extranet/amenity-list'); ?>" method="GET" name="cruise-ocean-search" onsubmit="return searchvalidateForm()">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group form-mb-20">
                                 <label>Select key to search by *</label>
                                 <select name="key" class="form-select"
                                    onchange="tts_searchkey(this,'cruise-ocean-search')"
                                    tts-validatation="Required"
                                    tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="amenity_title" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amenity_title') {
                                       echo "selected";
                                       } ?>>Amenity Title
                                    </option>
                                    <option value="amenity_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amenity_type') {
                                       echo "selected";
                                       } ?>>Amenity Type
                                    </option>
                                    <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                       echo "selected";
                                       } ?>>Date Range
                                    </option>
                                 </select>
                              </div>
                              <input type="hidden" name="key-text"
                                 value="<?php if (isset($search_bar_data['key-text'])) {
                                    echo trim($search_bar_data['key-text']);
                                    } ?>">
                           </div>
                           <div class="col-md-3">
                              <div class="form-group form-mb-20">
                                 <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
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
                                 <label>From Date</label>
                                 <input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {echo $search_bar_data['from_date'];} ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="form-group form-mb-20">
                                 <label>To Date</label>
                                 <input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) { echo $search_bar_data['to_date'];} ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
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
                                 <a href="<?php echo site_url('hotel-extranet/amenity-list'); ?>">Reset
                                 Search</a>
                              </div>
                           </div>
                           <?php endif ?>
                        </div>   
                     </form>
                     
                  
                  
                     
                        
                        <?php $trash_uri = "hotel-extranet/remove-amenity"; ?>
                        <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formgallerylist">
                           <div class="table-responsive">
                              <table class="table table-bordered table-hover">
                                 <thead class="table-active">
                                    <tr>
                                       <?php if (permission_access("HotelExtranet", "delete_amenity") || permission_access("HotelExtranet", "status_amenity")) { ?>
                                       <th><label><input type="checkbox" name="check_all"
                                          id="selectall"/></label>
                                       </th>
                                       <?php } ?>
                                       <th>Amenity Icon</th>
                                       <th>Amenity Title</th>
                                       <th>Amenity Type</th>
                                       <th>Status</th>
                                       <!-- <th>Suppliers</th> -->
                                       <th>Created Date</th>
                                       <th>Modified Date</th>
                                       <?php if (permission_access("HotelExtranet", "edit_amenity")) {  ?>
                                       <th>Action</th>
                                       <?php } ?>
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
                                       <?php if (permission_access("HotelExtranet", "delete_amenity") || permission_access("HotelExtranet", "status_amenity")) { ?>
                                       <td>
                                          <label><input type="checkbox" name="checklist[]"
                                             class="checkbox"
                                             value="<?php echo $data['id']; ?>"/></label>
                                       </td>
                                       <?php } ?>
                                       <td>
                                          <img src="<?php echo root_url . "uploads/hotel/" . $data['amenity_icon']; ?>"
                                             alt="<?php echo $data['amenity_icon']; ?>" height="64" width="64">
                                       </td>
                                       <td>
                                          <?php echo $data['amenity_title']; ?>
                                       </td>
                                       <td>
                                          <?php echo ucfirst($data['amenity_type']); ?>
                                       </td>
                                       <td>
                                          <span class="<?php echo $status_class ?>">
                                          <?php echo ucfirst($data['status']); ?>
                                          </span>
                                       </td>
                                      <?php if(0){?>
                                       <td>
                                             <?php
                                                if ($data['company_name']) {
                                                      echo ucfirst($data['company_name']) . ' ( ' . $data['company_id'] . ')';
                                                }
                                                ?> 
                                             </td>
                                       <?php } ?>
                                       <td><?php echo date_created_format($data['created']); ?></td>
                                       <td>
                                          <?php
                                             if (isset($data['modified'])) {
                                                 echo date_created_format($data['modified']);
                                             } else {
                                                 echo '-';
                                             }
                                             ?>
                                       </td>
                                       <?php if (permission_access("HotelExtranet", "edit_amenity")) {  ?>
                                       <td>
                                          <a href="javascript:void(0);" view-data-modal="true"
                                             data-controller='cruise'
                                             data-id="<?php echo dev_encode($data['id']); ?>"
                                             data-href="<?php echo site_url('hotel-extranet/edit-amenity-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                                       </td>
                                       <?php } ?>
                                    </tr>
                                    <?php }
                                       } else {
                                           echo "<tr> <td colspan='11' class='text-center'><b>No data found</b></td></tr>";
                                       } ?>
                                 </tbody>
                              </table>
                           </div>
                        </form>
                        
                        <div class="row pagiantion_row align-items-center">
                           <div class="col-md-6 mb-3 mb-lg-0">
                              <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                 of <?= $pager->getPageCount() ?>,
                                 total <?= $pager->getTotal() ?> records found 
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
         <form action="<?php echo site_url('hotel-extranet/amenity-status-change'); ?>" method="post" tts-form="true"
            name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <select class="form-select" name="status">
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
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
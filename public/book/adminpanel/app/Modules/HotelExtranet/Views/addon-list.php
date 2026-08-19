<div class="content ">
   <div class="page-content">
      <div class="table_title">
                     <div class="sale_bar">
                        <div class="row align-items-center">
                           <div class="col-md-4 ">
                              <h5 class="m0"><?php echo $hotel['hotel_name']?> Addon List</h5>
                           </div>
                           <div class="col-md-8 text-md-end">
                              <?php if (permission_access("HotelExtranet", "add_addon")) {  ?>
                              <button class="badge badge-wt" view-data-modal="true" data-controller='cruise'
                                 data-href="<?php echo site_url('hotel-extranet/add-addon-template/').dev_encode($hotel_id) ?>">
                              <i class="fa-solid fa-add "></i> Add Addon
                              </button>
                              <?php }  ?>
                              <?php if (permission_access("HotelExtranet", "status_addon")) {  ?>
                              <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                              <i class="fa-solid fa-exchange"></i> Change Status
                              </button>
                              <?php }  ?>
                              <?php if (permission_access("HotelExtranet", "delete_addon")) {  ?>
                              <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                 onclick="confirm_delete('formgallerylist')"><i class="fa-solid fa-trash"></i>
                              Delete
                              </button>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
      <div class="page-content-area">
         <div class="card-body">
               <div class="col-md-12">
                  <div class="setting-content">
                     <div class="col-md-12">
                        
                           <?php $trash_uri = "hotel-extranet/remove-addon"; ?>
                           <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formgallerylist">
                              <div class="table-responsive">
                              <table class="table table-bordered table-hover">
                                 <thead class="table-active">
                                    <tr>
                                       <?php if (permission_access("HotelExtranet", "delete_addon") || permission_access("HotelExtranet", "status_addon")) { ?>
                                       <th><label><input type="checkbox" name="check_all"
                                          id="selectall"/></label>
                                       </th>
                                       <?php } ?>
                                       <th>Service Name</th>
                                       <th>Price</th>
                                       <th>Status</th>
                                       <th>Created Date</th>
                                       <th>Modified Date</th>
                                       <?php if (permission_access("HotelExtranet", "edit_addon")) {  ?>
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
                                       <?php if (permission_access("HotelExtranet", "delete_addon") || permission_access("HotelExtranet", "status_addon")) { ?>
                                       <td>
                                          <label><input type="checkbox" name="checklist[]"
                                             class="checkbox"
                                             value="<?php echo $data['id']; ?>"/></label>
                                       </td>
                                       <?php } ?>
                                       <td>
                                          <?php echo ucfirst($data['service_name']); ?>
                                       </td>
                                       <td>
                                          <?php echo $data['price']; ?>
                                       </td>
                                       <td>
                                          <span class="<?php echo $status_class ?>">
                                          <?php echo ucfirst($data['status']); ?>
                                          </span>
                                       </td>
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
                                       <?php if (permission_access("HotelExtranet", "edit_addon")) {  ?>
                                       <td>
                                          <a href="javascript:void(0);" view-data-modal="true"
                                             data-controller='cruise'
                                             data-id="<?php echo dev_encode($data['id']); ?>"
                                             data-href="<?php echo site_url('hotel-extranet/edit-addon-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
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
</div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">Change Status</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('hotel-extranet/addon-status-change'); ?>" method="post" tts-form="true"
            name="form_change_status">
            
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
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
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
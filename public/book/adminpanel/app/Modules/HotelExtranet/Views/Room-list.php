<div class="content ">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-body">
            <div class="">
               <div class="col-md-12 settings-panel">
                  <div class="page-actions-panel">
                     <div class="row slign-items-center">
                        <div class="col-md-4">
                           <h5 class="m0"><?php echo $hotel['hotel_name']?> Room List</h5>
                        </div>
                        <div class="col-md-8 text-end">
                           <a href="<?php echo site_url("hotel-extranet/hotel-list"); ?>" class="badge badge-wt"> <i class="fa fa-list"></i> Hotel List</a>
                           <?php if (permission_access("HotelExtranet", "add_room")) {  ?>

                              
                           <button class="badge badge-wt" view-data-modal="true" data-controller='cruise'
                              data-href="<?php echo site_url('hotel-extranet/add-room-template/').dev_encode(json_encode(array('hotel_id'=>$hotel_id,'supplier_id'=>$supplier_id))); ?>">
                           <i class="fa fa-add"></i> Add Room
                           </button>
                           <?php }  ?>
                           <?php if (permission_access("HotelExtranet", "status_room")) {  ?>
                           <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                           <i class="fa fa-exchange"></i> Change Status
                           </button>
                           <?php }  ?>
                           <?php if (permission_access("HotelExtranet", "delete_room")) {  ?>
                           <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                              onclick="confirm_delete('formgallerylist')"><i class="fa fa-trash"></i>Delete
                           </button>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="setting-content">
                     <div class="col-md-12">
                        
                        <?php $trash_uri = "hotel-extranet/remove-room"; ?>
                        <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formgallerylist">
                           <div class="table-responsive">
                              <table class="table table-bordered tsble-hover">
                                 <thead class="table-active">
                                    <tr>
                                       <?php if (permission_access("HotelExtranet", "delete_room") || permission_access("HotelExtranet", "status_room")) { ?>
                                       <th><label><input type="checkbox" name="check_all"
                                          id="selectall"/></label>
                                       </th>
                                       <?php } ?>
                                       <th>Room Title</th>
                                       <th>Occupancy Type</th>
                                       <th>Min Stay</th>
                                       <th>Room Quantity</th>
                                       <th>Status</th>
                                       <th>Gallery</th>
                                       <th>Room Price</th>
                                       <th>Room Availability</th>
                                       <th>Created Date</th>
                                       <th>Modified Date</th>
                                       <?php if (permission_access("HotelExtranet", "edit_room")) {  ?>
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
                                       <?php if (permission_access("HotelExtranet", "delete_room") || permission_access("HotelExtranet", "status_room")) { ?>
                                       <td>
                                          <label><input type="checkbox" name="checklist[]"
                                             class="checkbox"
                                             value="<?php echo $data['id']; ?>"/></label>
                                       </td>
                                       <?php } ?>
                                       <td>
                                          <?php echo ucfirst($data['room_title']); ?>
                                       </td>
                                       <td>
                                          <?php echo $data['occupancy_type']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['min_stay']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['room_quantity']; ?>
                                       </td>
                                       <td>
                                          <span class="<?php echo $status_class ?>">
                                          <?php echo ucfirst($data['status']); ?>
                                          </span>
                                       </td>
                                       <td>
                                          <a href="javascript:void(0);" view-data-modal="true"
                                             data-controller='HotelExtranet'
                                             data-id="<?php echo dev_encode($data['id']); ?>"
                                             data-href="<?php echo site_url('/hotel-extranet/room-gallery/') . dev_encode($data['id']); ?>">Room Gallery</i></a>
                                       </td>
                                       <td>
                                          <a href="<?php echo site_url('/hotel-extranet/room-price-list/') . dev_encode($data['id']); ?>">Room Price</a>
                                       </td>
                                       <td>
                                          <a href="<?php echo site_url('/hotel-extranet/get-room-availability?key') . dev_encode($data['id']) . "&year=".date('Y'); ?>">Room
                                          Availability
                                          </a>
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
                                       <?php if (permission_access("HotelExtranet", "edit_room")) {  ?>
                                       <td>
                                          <a href="javascript:void(0);" view-data-modal="true"
                                             data-controller='HotelExtranet'
                                             data-id="<?php echo dev_encode($data['id']); ?>"
                                             data-href="<?php echo site_url('hotel-extranet/edit-room-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
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
                          
                              
                           
                        
                        <div class="row pagiantion_row">
                                 <div class="col-md-6 align-items-center">
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
         <form action="<?php echo site_url('hotel-extranet/room-status-change'); ?>" method="post" tts-form="true"
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
               <button class="btn btn-primary" type="submit" value="Save">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
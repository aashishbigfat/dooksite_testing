<div class="content ">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-body">
            <div class="">
               <div class="col-md-12 settings-panel">
                  <div class="page-actions-panel">
                     <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-lg-0">
                           <span>Room Price List</span>
                        </div>
                        <div class="col-md-8 text-end">
                           <?php if (permission_access("HotelExtranet", "add_room_price")) {  ?>
                           <button class="badge badge-wt" view-data-modal="true" data-controller='cruise'
                              data-href="<?php echo site_url('hotel-extranet/add-room-price-template/').dev_encode($room_id) ?>">
                           <i class="fa fa-add"></i> Add Room Price
                           </button>
                           <?php }  ?>
                           <?php if (permission_access("HotelExtranet", "delete_room_price")) {  ?>
                           <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                              onclick="confirm_delete('formgallerylist')"><i class="fa fa-trash"></i>
                           Delete
                           </button>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="setting-content">
                     <div class="col-md-12"> 
                        <?php $trash_uri = "hotel-extranet/remove-room-price"; ?>
                        <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formgallerylist">
                           <div class="table-responsive">
                              <table class="table table-bordered table-hover">
                                 <thead class="table-active">
                                    <tr>
                                       <?php if (permission_access("HotelExtranet", "delete_room_price")) { ?>
                                       <th><label><input type="checkbox" name="check_all"
                                          id="selectall"/></label>
                                       </th>
                                       <?php } ?>
                                       <th>Room Title</th>
                                       <th>Start Date</th>
                                       <th>End Date</th>
                                       <th>Adult Price</th>
                                       <th>Child Price</th>
                                       <th>Mon</th>
                                       <th>Tue</th>
                                       <th>Wed</th>
                                       <th>Thu</th>
                                       <th>Fri</th>
                                       <th>Sat</th>
                                       <th>Sun</th>
                                       <th>Created Date</th>
                                       <th>Modified Date</th>
                                       <?php if (permission_access("HotelExtranet", "edit_room_price")) {  ?>
                                       <th>Action</th>
                                       <?php } ?>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       if (!empty($list) && is_array($list)) { 
                                          foreach ($list as $data) { 
                                             ?>
                                    <tr>
                                       <?php if (permission_access("HotelExtranet", "delete_room_price")) { ?>
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
                                          <?php echo timestamp_to_date($data['start_date']) ; ?>
                                       </td>
                                       <td>
                                          <?php echo  timestamp_to_date($data['end_date']); ?>
                                       </td>
                                       <td>
                                          <?php echo $data['adult_price']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['child_price']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['mon']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['tue']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['wed']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['thu']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['fri']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['sat']; ?>
                                       </td>
                                       <td>
                                          <?php echo $data['sun']; ?>
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
                                       <?php if (permission_access("HotelExtranet", "edit_room_price")) {  ?>
                                       <td>
                                          <a href="javascript:void(0);" view-data-modal="true"
                                             data-controller='HotelExtranet'
                                             data-id="<?php echo dev_encode($data['id']); ?>"
                                             data-href="<?php echo site_url('hotel-extranet/edit-room-price-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                                       </td>
                                       <?php } ?>
                                    </tr>
                                    <?php }
                                       } else {
                                       
                                           echo "<tr> <td colspan='16' class='text-center'><b>No data found</b></td></tr>";
                                       
                                       } ?>
                                 </tbody>
                              </table>
                           </div>   
                        </form>
                          
                        
                     
                              <div class="row align-items-center">
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
</div>
</div>
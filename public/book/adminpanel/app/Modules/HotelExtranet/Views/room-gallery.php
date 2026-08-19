<div class="modal-header">
        <h5 class="modal-title">Add Room Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
   <form action="<?php echo site_url('hotel-extranet/add-room-gallery/') . dev_encode($room_id); ?>" method="post"
      tts-form="true" name="add_class"
      enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Image Title *</label>
                  <input class="form-control" type="text" name="image_title" placeholder="Image Title">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Image  </label>
                  <input class="form-control" type="file" name="room_gallery" placeholder="Image">
               </div>
            </div>
            <div class="col-md-4" style="margin-top: 18px;">
               <div class="form-group form-mb-20 ">
                  <label>&nbsp;</label>
                  <input class="btn btn-primary" type="submit" value="Save">
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="content ">
      <div class="page-actions-panel">
         <div class="row align-items-center">
            <div class="col-md-4">
               <h5 class="m0">Room Gallery List</h5>
            </div>
            <div class="col-md-8 text-end">
            <?php if (permission_access("HotelExtranet", "delete_room_gallery")) {?>
               <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                  onclick="confirm_delete('formlist')"><i class="fa-solid fa-trash"></i> Delete
               </button>
               <?php } ?>
            </div>
         </div>
      </div>
      <div class="page-content mt-3">
         <div class="page-content-area">
            <div class="card-body" style="padding: unset;">
               <div class="table-responsive table_box_shadow " tts-table-html="true">
                  <?php $trash_uri = "hotel-extranet/remove-room-gallery"; ?>
                  <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                     id="formlist">
                     <table class="table table-bordered">
                        <thead class="table-active">
                           <tr>
                           <?php if (permission_access("HotelExtranet", "delete_room_gallery")) { ?>
                                       <th><label><input type="checkbox" name="check_all"
                                          id="selectall"/></label>
                                       </th>
                                       <?php } ?>
                              <th>Image</th>
                              <th>Title</th>
                              <th>Created</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if (!empty($list) && is_array($list)) {
                              
                                  foreach ($list as $data) {
                              
                              
                              
                                      ?>
                           <tr>
                           <?php if (permission_access("HotelExtranet", "delete_room_gallery")) { ?>
                              <td>
                                 <label><input type="checkbox" name="checklist[]"
                                    class="checkbox"
                                    value="<?php echo $data['id']; ?>"/></label>
                              </td>
                              <?php } ?>
                              <td>
                                 <img src="<?php echo root_url . "uploads/hotel/thumbnail/" . $data['room_gallery']; ?>"
                                    alt="<?php echo $data['room_gallery']; ?>"   class="tts-blog-image">
                              </td>
                              <td>
                                 <?php echo ucfirst($data['image_title']); ?>
                              </td>
                              <td>
                                 <?php echo date_created_format($data['created']); ?>
                              </td>
                           </tr>
                           <?php }
                              } else {
                              
                                  echo "<tr> <td colspan='11' class='text_center'><b>No Image Found</b></td></tr>";
                              
                              } ?>
                        </tbody>
                     </table>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>


<div class="modal-header">
        <h5 class="modal-title" >Add Hotel Room </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>

   <form action="<?php echo site_url('hotel-extranet/edit-room/') . dev_encode($id); ?>" method="post"
      tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Title *</label>
                  <input class="form-control" type="text" name="room_title" placeholder="Room Title" value="<?php echo $details['room_title']?>">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Quantity *</label>
                  <input class="form-control" type="text" name="room_quantity" placeholder="Room Quantity" value="<?php echo $details['room_quantity']?>">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Occupancy Type *</label>
                  <select class="form-select" name="occupancy_type" placeholder="Occupancy Type">
                     <option value="Single" <?php if ($details['occupancy_type'] == 'Single') { echo "selected"; } ?>>Single</option>
                     <option value="Double" <?php if ($details['occupancy_type'] == 'Double') { echo "selected"; } ?>>Double</option>
                     <option value="Triple" <?php if ($details['occupancy_type'] == 'Triple') { echo "selected"; } ?>>Triple</option>
                     <option value="Quad" <?php if ($details['occupancy_type'] == 'Quad') { echo "selected"; } ?>>Quad</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Minimum Stay *</label>
                  <select class="form-select" name="min_stay" placeholder="Minimum Stay">
                     <option value="1" <?php if ($details['min_stay'] == 1) { echo "selected"; } ?>>1</option>
                     <option value="2" <?php if ($details['min_stay'] == 2) { echo "selected"; } ?>>2</option>
                     <option value="3" <?php if ($details['min_stay'] == 3) { echo "selected"; } ?>>3</option>
                     <option value="4" <?php if ($details['min_stay'] == 4) { echo "selected"; } ?>>4</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Select Room Amenities *</label>
                  <select class="form-control select_search" name="room_amenities[]" multiple="multiple">
                     <?php if ($amenity) {
                        foreach ($amenity as $data) { ?>
                     <option value="<?php echo $data['id'] ?>" <?php if ($details['room_amenities']){ if(in_array($data['id'],$details['room_amenities'])){ echo 'selected';}}?>>
                        <?php echo $data['amenity_title']; ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-select" name="status" placeholder="Status">
                     <option value="active" <?php if ($details['status'] == 'active') { echo "selected"; } ?>>Active</option>
                     <option value="inactive" <?php if ($details['status'] == 'inactive') { echo "selected"; } ?>> Inactive </option>
                  </select>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Room Description *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="room_description" rows="3"
                     placeholder="Room Description"><?php echo $details['room_description']?></textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Room Cancellation *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="room_cancellation" rows="3"
                     placeholder="Room Cancellation"><?php echo $details['room_cancellation']?></textarea>
               </div>
            </div>
         </div>
        
      </div>
       <div class="modal-footer">
             <button class="btn btn-primary" type="submit" >Save</button>
         </div>
   </form>

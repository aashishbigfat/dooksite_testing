<div class="modal-header">
   <h5 class="modal-title" >Add Hotel Room</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

   <form action="<?php echo site_url('hotel-extranet/add-room/') . dev_encode(json_encode(array('hotel_id'=>$hotel_id,'supplier_id'=>$supplier_id))); ?>" method="post"
      tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Title *</label>
                  <input class="form-control" type="text" name="room_title" placeholder="Room Title">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Quantity *</label>
                  <input class="form-control" type="text" name="room_quantity" placeholder="Room Quantity">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Occupancy Type *</label>
                  <select class="form-select" name="occupancy_type" placeholder="Occupancy Type">
                     <option value="Single" selected>Single</option>
                     <option value="Double">Double</option>
                     <option value="Triple">Triple</option>
                     <option value="Quad">Quad</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Minimum Stay *</label>
                  <select class="form-select" name="min_stay" placeholder="Minimum Stay">
                     <option value="1" selected>1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Select Room Amenities *</label>
                  <select class="form-select select_search" name="room_amenities[]" multiple="multiple">
                     <?php if ($amenity) {
                        foreach ($amenity as $data) { ?>
                     <option value="<?php echo $data['id'] ?>">
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
                     <option value="active" selected>Active</option>
                     <option value="inactive"> Inactive</option>
                  </select>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Room Description *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="room_description" rows="3"
                     placeholder="Room Description"></textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Room Cancellation *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="room_cancellation" rows="3"
                     placeholder="Room Cancellation"></textarea>
               </div>
            </div>
         </div>
         
      </div>
      <div class="modal-footer">
            <button class="btn btn-primary" type="submit" value="Save">Save</button>
         </div>
   </form>

<div class="modal-header">
        <h5 class="modal-title" >Edit Amenity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>


   <form action="<?php echo site_url('hotel-extranet/edit-amenity/' . dev_encode($id))  ; ?>" method="post" tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Amenity Title *</label>
                  <input class="form-control" type="text" name="amenity_title" value="<?php echo $details['amenity_title']?>" placeholder="Amenity Type">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Icon</label>
                  <small>(Allowed type png jpg svg 64x64) *</small>
                  <input class="form-control" type="file" name="amenity_icon" placeholder=" Image">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Amenity Type *</label>
                  <select class="form-select" name="amenity_type" placeholder="Amenity Type">
                     <option value="hotel" <?php if ($details['amenity_type'] == "hotel") {
                        echo "selected";
                        
                        } ?>>Hotel</option>
                     <option value="room" <?php if ($details['amenity_type'] == "room") {
                        echo "selected";
                        
                        } ?>>Room</option>
                     <option value="both"  <?php if ($details['amenity_type'] == "both") {
                        echo "selected";
                        
                        } ?>>Both</option>
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-select" name="status" placeholder="Status">
                     <option value="active" <?php if ($details['status'] == "active") {
                        echo "selected";
                        
                        } ?>>Active
                     </option>
                     <option value="inactive" <?php if ($details['status'] == "inactive") {
                        echo "selected";
                        
                        } ?>> Inactive
                     </option>
                  </select>
               </div>
            </div>
         </div>
         
      </div>
      <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
         </div>
   </form>

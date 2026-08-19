
<div class="modal-header">
        <h5 class="modal-title" >Edit Property Type </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>
   <form action="<?php echo site_url('hotel-extranet/edit-property-type/' . dev_encode($id))  ; ?>" method="post" tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Property Type *</label>
                  <input class="form-control" type="text" name="property_type" value="<?php echo $details['property_type']?>" placeholder="Property Type">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-control" name="status" placeholder="Status">
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
            <button class="btn btn-primary" type="submit" >Save</button>
         </div>
   </form>

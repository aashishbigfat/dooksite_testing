
<div class="modal-header">
        <h5 class="modal-title" >Edit Hotel Addon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>

   <form action="<?php echo site_url('hotel-extranet/edit-addon/').dev_encode($id); ?>" method="post" tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Service Name *</label>
                  <input class="form-control" type="text" name="service_name" value="<?php echo $details['service_name'];?>" placeholder="Service Name">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Price *</label>
                  <input class="form-control" type="text" name="price" placeholder="Price" value="<?php echo $details['price'];?>">
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
            <button class="btn btn-primary" type="submit">save</button>
         </div>
   </form>

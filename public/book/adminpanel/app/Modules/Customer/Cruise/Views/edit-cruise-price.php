
<div class="modal-header">
                <h5 class="modal-title">Edit Cruise Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
  <div class="vewmodelhed">
   <form action="<?php echo site_url('cruise/edit-cruise-price/'). dev_encode($id); ?>" method="post" tts-form="true" name="edit_cruise_price"
      enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Selling Date *</label>
                  <input class="form-control" type="text" name="selling_date" value="<?php echo $details['selling_date']; ?>" nolim-calendor="true" placeholder="Selling Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Cruise Cabin *</label>
                  <select class="form-control" name="cruise_cabin_id" placeholder="Cruise Cabin">
                     <option value='' selected>Select Cruise Cabin</option>
                     <?php if ($cruise_cabin) {
                        foreach ($cruise_cabin as $data) {
                        
                            ?>
                     <option value="<?php echo $data['id']?>" <?php if ($details['cruise_cabin_id'] == $data['id']) {
                        echo "selected";
                        
                        } ?>><?php echo $data['cabin_name']?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Single Pax Price *</label>
                  <input class="form-control" type="text" name="single_pax_price" value="<?php echo $details['single_pax_price']; ?>"  placeholder="Single Pax Price">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Twin Pax Price *</label>
                  <input class="form-control" type="text" name="twin_pax_price" value="<?php echo $details['twin_pax_price']; ?>"  placeholder="Twin Pax Price">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Port Charges *</label>
                  <input class="form-control" type="text" name="port_charges" value="<?php echo $details['port_charges']; ?>"  placeholder="Port Charges">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Available Cabin *</label>
                  <input class="form-control" type="text" name="available_cabin" value="<?php echo $details['available_cabin']; ?>"  placeholder="Available Cabin">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Max Pax Stay *</label>
                  <select class="form-control" name="max_pax_stay" placeholder="Max Pax Stay">
                     <option value="">Select Max Pax Stay</option>
                     <option value="1"<?php if ($details['max_pax_stay'] == 1) {
                        echo "selected";
                        
                        } ?>>1</option>
                     <option value="2" <?php if ($details['max_pax_stay'] == 2) {
                        echo "selected";
                        
                        } ?>>2</option>
                     <option value="3" <?php if ($details['max_pax_stay'] == 3) {
                        echo "selected";
                        
                        } ?>>3</option>
                     <option value="4" <?php if ($details['max_pax_stay'] == 4) {
                        echo "selected";
                        
                        } ?>>4</option>
                     <option value="5" <?php if ($details['max_pax_stay'] == 5) {
                        echo "selected";
                        
                        } ?>>5</option>
                     <option value="6" <?php if ($details['max_pax_stay'] == 6) {
                        echo "selected";
                        
                        } ?>>6</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
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
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label class="mt20">
                  <input type="checkbox" name="book_online" value="yes" class="Lead" <?php if ($details['book_online'] == 'yes') {
                     echo 'checked';
                     
                     } ?>>Book Online
                  </label>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
</div>
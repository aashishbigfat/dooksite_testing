<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo 'Hotel Discount '; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('hotel/edit-admin-hotel-discount/') . dev_encode($id); ?>" method="post" tts-form="true" name="edit_visa_markup">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group">
               <label>Discount For *</label>
               <select class="form-select" name="discount_for" tts-markup-used-for="true">
               <option value="">Please Select</option>
                  <?php $markup_used_for = get_active_whitelable_business();
                  foreach ($markup_used_for as $key => $data) {  ?>
                     <option value="<?php echo $key ?>" <?php if ($key == $details['discount_for']) {
                                                            echo "selected";
                                                         } ?>><?php echo $key ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>

         <div class="col-md-4 <?php echo ($details['discount_for'] == 'B2B') ? '' : 'd-none'; ?>" tts-agent-class="true">
            <div class="form-group">
               <label>Agent Class *</label>
               <select class="form-select select_search" name="agent_class[]" placeholder="hotel Type" multiple="multiple">

                  <?php foreach ($agent_class_list as $key => $data) {  ?>
                     <option value="<?php echo $data['id'] ?>" <?php if (in_array($data['id'], $details['agent_class'])) {
                                                                  echo "selected";
                                                               } ?>><?php echo $data['class_name'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Region Type *</label>
               <select class="form-select select_search" name="region_type[]" multiple="multiple">
                  <option value="domestic" <?php if (in_array("domestic", $details['region_type'])) {
                                                echo "selected";
                                             } ?>>Domestic</option>
                  <option value="international" <?php if (in_array("international", $details['region_type'])) {
                                                   echo "selected";
                                                } ?>>International</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Value *</label>
               <input class="form-control" type="text" value="<?php echo $details['value']; ?>" name="value" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Extra Discount *</label>
               <input class="form-control" type="text" name="extra_discount" value="<?php echo $details['extra_discount']; ?>" placeholder="Extra Discount">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Max Limit </label>
               <input class="form-control" type="text" value="<?php echo $details['max_limit']; ?>" name="max_limit" placeholder="Max Limit">
            </div>
         </div>
         <div class="col-md-4">
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
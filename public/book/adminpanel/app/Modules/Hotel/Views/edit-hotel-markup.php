
<div class="modal-header">
   <h5 class="modal-title" >Edit <?php echo 'Hotel Markup '; ?></h5>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('hotel/edit-admin-hotel-markup/') . dev_encode($id); ?>" method="post" tts-form="true"
   name="edit_visa_markup">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group">
               <label>Markup For *</label>
               <select class="form-select" name="markup_for" tts-markup-used-for="true">
               <option value="">Please Select</option>
                     <?php $markup_used_for = get_active_whitelable_business();
                     foreach ($markup_used_for as $key => $data) {  ?>
                        <option value="<?php echo $key ?>" <?php if($key == $details['markup_for']){ echo "selected";} ?>><?php echo $key ?></option>
                     <?php } ?>
               </select>
            </div>
         </div>   
        
         <div class="col-md-4 <?php echo ($details['markup_for'] == 'B2B') ? '' : 'd-none'; ?>" tts-agent-class="true">
            <div class="form-group">
               <label>Agent Class *</label>
               <select class="form-select select_search" name="agent_class[]" placeholder="Flight Type" multiple="multiple">
                  
                     <?php  foreach ($agent_class_list as $key => $data) {  ?>  
                        <option value="<?php echo $data['id'] ?>" <?php if(in_array($data['id'], $details['agent_class'])){ echo "selected";} ?> ><?php echo $data['class_name'] ?></option>
                     <?php } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Region Type *</label>
               <select class="form-select select_search" name="region_type[]"  multiple="multiple">
                  <option value="domestic" <?php if (in_array("domestic", $details['region_type'])) {echo "selected";} ?> >Domestic</option>
                  <option value="international" <?php if (in_array("international", $details['region_type'])) {echo "selected";} ?>>International</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Display Markup *</label>
               <select class="form-select" name="display_markup" placeholder="Display Markup">
                  <option value="in_tax" <?php if ($details['display_markup'] == "in_tax") {
                     echo "selected";
                     
                     } ?>>In Tax</option>
                  <option value="in_service_charge" <?php if ($details['display_markup'] == "in_service_charge") {
                     echo "selected";
                     
                     } ?> >In Service Charge</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Hotel Markup Type *</label>
               <select class="form-select" name="hotel_markup_type" placeholder="Hotel Markup Type">
                  <option value="per_night" <?php if ($details['hotel_markup_type'] == "per_night") {
                     echo "selected";
                     
                     } ?>>Per Night</option>
                  <option value="per_room" <?php if ($details['hotel_markup_type'] == "per_room") {
                     echo "selected";
                     
                     } ?>>Per Room</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Star Rating *</label>
               <select class="form-select select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                  <?php  for($rating=1;$rating<=5;$rating++){ ?>
                     <option value="<?php echo  $rating;?>" <?php  echo  in_array($rating,$details['star_rating'])?"selected":"";?>><?php echo  $rating;?> Star</option>
                 <?php   } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Value *</label>
               <input class="form-control" type="text" value="<?php echo $details['value'];?>" name="value" placeholder="Value">
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
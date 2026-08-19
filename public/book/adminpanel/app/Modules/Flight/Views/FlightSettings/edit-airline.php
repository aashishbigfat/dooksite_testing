<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo 'Airline'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('flightsettings/edit-airline/' . dev_encode($id)); ?>" method="post" tts-form="true"
   name="edit_airport" enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Airline Code * </label>
               <input class="form-control" type="text" name="airline_code"
                  value="<?php echo $details['airline_code'] ?>" placeholder="Airline Code">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Airline Image <span class="fw-bold">(Only Png images Allowed)</span></label>
               <input class="form-control" type="file" name="images" placeholder="Airline Image">
            </div>
            <div class="clearfix">
               <p>Minimum resolution Image size- <code>48x48</code></p>
            </div>
            <div class="form-group form-mb-20">
               <img src="<?php echo root_url . "uploads/airline-images/" . $details['airline_code'] . ".png"; ?>"
                  alt="<?php echo $details['airline_code']; ?>" class="tts-airline-image" width="40">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label> Airline Name * </label>
               <input class="form-control" type="text" name="airline_name"
                  value="<?php echo $details['airline_name'] ?>" placeholder="Airline Name">
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label> Airline Contact No </label>
               <input class="form-control" type="text" name="airline_contact_no"
                  value="<?php echo $details['airline_contact_no'] ?>" placeholder="Airline Contact No">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label class="mt20">
                  <input type="checkbox" name="islcc" value="true" class="Lead" <?php if ($details['islcc'] == "true") {
                     echo 'checked';
                  } ?>>ISLCC
               </label>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>
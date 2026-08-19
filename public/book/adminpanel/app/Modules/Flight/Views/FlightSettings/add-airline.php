<div class="modal-header">
   <h5 class="modal-title">Add <?php echo 'Airline'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('flightsettings/add-airline'); ?>" method="post" tts-form="true" name="add_feedback"
   enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Airline Code * </label>
               <input class="form-control" type="text" name="airline_code" placeholder="Airline Code">
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
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label> Airline Name * </label>
               <input class="form-control" type="text" name="airline_name" placeholder="Airline Name">
            </div>

         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label> Airline Contact No </label>
               <input class="form-control" type="text" name="airline_contact_no" placeholder="Airline Contact No">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label class="mt20">
                  <input type="checkbox" name="islcc" value="true" class="Lead">ISLCC
               </label>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>
<div class="modal-header">
   <h5 class="modal-title">Add <?php echo 'Web Check In'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('web-check-in/add-web-check-in'); ?>" method="post" tts-form="true"
   name="add_feedback" enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Airline Name* </label>
               <input class="form-control" type="text" tts-get-airline="true" name="airline_name"
                  placeholder="Airline Name">
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>URL</label>
               <input class="form-control" type="text" name="url" placeholder="URL">
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Image *<span class="fw-bold">(upload max 500 kb image size allowed)</span></label>
               <input class="form-control" type="file" name="image" placeholder="Image">
               <p>Image resolution size- <code>200x200</code></p>
            </div>
           
         </div>
 
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>
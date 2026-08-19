<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo 'Web Check In'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('web-check-in/edit-web-check-in/' . dev_encode($id)); ?>" method="post"
   tts-form="true" name="edit_airport" enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Airline Name * </label>
               <input class="form-control" type="text" tts-get-airline="true"
                  value="<?php echo $details['airline_code'] . '-' . $details['airline_name'] ?>" name="airline_name"
                  placeholder="Airline Code">
            </div>
         </div>

         <div class="col-6">
            <div class="form-group">
               <label>URL</label>
               <input class="form-control" type="text" name="url" placeholder="URL"
                  value="<?php echo $details['url']; ?>">
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Image * <span class="fw-bold">(upload max 500 kb image size allowed)</span></label>
               <input class="form-control" type="file" name="image" placeholder="Airline Image">
               <p>Image resolution size- <code>200x200</code></p>
            </div>
           
            <div class="form-group form-mb-20">
               <img src="<?php echo root_url . "uploads/web-check-in-images/thumbnail/" . $details['image']; ?>"
                  alt="<?php echo $details['airline_code']; ?>" class="tts-airline-image" width="40">
            </div>
         </div>
         
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>
<div class="modal-header">
   <h5 class="modal-title" >Add Room Price</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

   <form action="<?php echo site_url('hotel-extranet/add-room-price/') . dev_encode($room_id); ?>" method="post"
      tts-form="true"
      name="add_car_city">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Start Date *</label>
                  <input class="form-control" type="text" start-date="true" name="start_date" placeholder="Start Date">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>End Date *</label>
                  <input class="form-control" type="text" end-date="true" name="end_date" placeholder="End Date">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Extra Adult Price *</label>
                  <input class="form-control" type="text" name="adult_price" placeholder="Adult Price">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Extra Child Price *</label>
                  <input class="form-control" type="text" name="child_price" placeholder="Child Price">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Monday</label>
                  <input class="form-control" type="text" name="mon" id="dayPrice" placeholder="Monday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Tuesday</label>
                  <input class="form-control" type="text" name="tue" placeholder="Tuesday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Wednesday</label>
                  <input class="form-control" type="text" name="wed" placeholder="Wednesday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Thursday</label>
                  <input class="form-control" type="text" name="thu" placeholder="Thursday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Friday</label>
                  <input class="form-control" type="text" name="fri" placeholder="Friday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> Room Price For Saturday</label>
                  <input class="form-control" type="text" name="sat" placeholder="Saturday">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Room Price For Sunday</label>
                  <input class="form-control" type="text" name="sun" placeholder="Sunday">
               </div>
            </div>
         </div>
         
      </div>
      <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
         </div>
   </form>

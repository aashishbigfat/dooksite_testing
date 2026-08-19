<div class="modal-header">
   <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Car Coupon'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('coupon/add-coupon-car'); ?>" method="post" tts-form="true"
      name="add_flight_discount">
      <div class="modal-body">
         <div class="row align-items-center">

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Code *</label>
                  <input class="form-control" type="text" name="code" placeholder="Coupon Code"
                     value="<?php echo 'CODE' . substr(uniqid(), -8) . substr(time(), -1); ?>">
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>From Booking Date *</label>
                  <input class="form-control" type="text" package-from-date="true" name="travel_date_from"
                     placeholder="From Date" readonly>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>To Booking Date *</label>
                  <input class="form-control" type="text" package-from-date="true" name="travel_date_to"
                     placeholder="To Date" readonly>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Type *</label>
                  <select class="form-select" name="coupon_type" placeholder="Coupon Type">
                     <option value="fixed" selected>Fixed</option>
                     <option value="percent">Percent</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Value *</label>
                  <input class="form-control decimal" type="text" name="value" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Use Limit *</label>
                  <input class="form-control numeric" type="text" name="use_limit" placeholder="Coupon Use Limit">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Minimum Order Value *</label>
                  <input class="form-control decimal" type="text" name="minm_order" placeholder="Minimum Order Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Maximum Order Value *</label>
                  <input class="form-control decimal" type="text" name="maxm_order" placeholder="Maximum Order Value">
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Minm Cars *</label>
                  <input class="form-control numeric" type="text" name="minm_car" placeholder="Minm Cars">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Maxm Cars *</label>
                  <input class="form-control numeric" type="text" name="maxm_car" placeholder="Maxm Cars">
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Show On List *</label>
                  <select class="form-select" name="coupon_visible" placeholder="Show Coupan Code">
                     <option value="0" selected>No</option>
                     <option value="1"> Yes</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Max Limit </label>
                  <input class="form-control decimal" type="text" name="max_limit" placeholder="Coupon Max Limit">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-select" name="status" placeholder="Status">
                     <option value="active" selected>Active</option>
                     <option value="inactive"> Inactive</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Valid From Date *</label>
                  <input class="form-control" type="text" package-from-date="true" name="valid_from"
                     placeholder="Valid From Date" readonly>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Valid To Date *</label>
                  <input class="form-control" type="text" package-from-date="true" name="valid_to"
                     placeholder="Valid To Date" readonly>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Text </label>
                  <input class="form-control" type="text" name="coupon_desc" placeholder="Coupon Text">
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
</div>
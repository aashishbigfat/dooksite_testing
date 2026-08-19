<div class="modal-header">
   <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Hotel Coupon'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('coupon/add-coupon-hotel'); ?>" method="post" tts-form="true" name="add_flight_discount">
      <div class="modal-body">
         <div class="row align-items-center">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Region Type *</label>
                  <select class="form-select select_search" name="region_type[]" placeholder="Region Type" multiple="multiple">
                     <option value="1" selected>Domestic</option>
                     <option value="0">International</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Star Rating *</label>
                  <select class="form-select select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                     <option value="4">4</option>
                     <option value="3">3</option>
                     <option value="2">2</option>
                     <option value="1" selected>1</option>
                     <option value="0">0</option>
                  </select>
               </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Coupon Code *</label>
                    <input class="form-control" type="text" name="code" placeholder="Coupon Code" value="<?php echo 'CODE'. substr(uniqid(), -8) . substr(time(), -1); ?>">
                </div>
            </div>
           
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Check In Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="check_in_date_from" placeholder="Check In Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Check Out Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="check_out_date_to" placeholder="Check Out Date">
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
                  <input class="form-control" type="text" name="value" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupon Use Limit *</label>
                  <input class="form-control" type="text" name="use_limit" placeholder="Coupon Use Limit">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Show On List*</label>
                  <select class="form-select" name="coupon_visible" placeholder="Show Coupan Code">
                     <option value="0" selected>No</option>
                     <option value="1"> Yes</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Coupan Max Limit *</label>
                  <input class="form-control" type="text" name="max_limit" placeholder="Coupon Max Limit">
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
                  <input class="form-control" type="text" nolim-calendor="true" name="valid_from" placeholder="Valid From Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Valid To Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="valid_to" placeholder="Valid To Date">
               </div>
            </div>
            <div class="col-md-12">
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
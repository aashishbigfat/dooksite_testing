<div class="modal-header">
   <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Flight Coupon'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('coupon/add-coupon'); ?>" class="autocomplete" method="post" tts-form="true" name="add_flight_discount">
      <div class="modal-body">
         <div class="row align-items-center">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Flight Type *</label>
                  <select class="form-select select_search" name="is_domestic[]" placeholder="Flight Type" multiple="multiple">
                     <option value="1" selected>Domestic</option>
                     <option value="0">International</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Airline Code * </label>
                  <input class="form-control" type="text" tts-get-airline="true" value="ANY-Any Airline" name="airline_code" placeholder="Airline Code">
               </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Coupon Code *</label>
                    <input class="form-control" type="text" name="code" placeholder="Coupon Code" value="<?php echo 'CODE'. substr(uniqid(), -8) . substr(time(), -1); ?>">
                </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>From Airport *</label>
                  <input class="form-control tts-autocomplete" type="text" tts-get-airport="true" name="from_airport_code" placeholder="From Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                     <input type="checkbox" name="" tts-from-any="true" value="ANY" class="Lead">From Any Airport
                  </label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>To Airport * </label>
                  <input class="form-control" type="text" tts-get-airport="true" name="to_airport_code" placeholder="To Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                     <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead">To Any Airport
                  </label>
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Journey Type *</label>
                  <select class="form-select select_search" name="journey_type[]" placeholder="Journey Type" multiple="multiple">
                     <option value="oneway" selected>Oneway</option>
                     <option value="round-trip">Round Trip</option>
                     <option value="multicity">MultiCity</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>From Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_from" placeholder="From Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>To Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_to" placeholder="To Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Cabin Class *</label>
                  <select class="form-select select_search" name="cabin_class[]" placeholder="Cabin Class" multiple="multiple">
                     <option value="Economy">Economy</option>
                     <option value="PremiumEconomy">Premium Economy</option>
                     <option value="Business">Business</option>
                     <option value="First">First</option>
                     <option value="PremiumBusiness">Premium Business</option>
                  </select>
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
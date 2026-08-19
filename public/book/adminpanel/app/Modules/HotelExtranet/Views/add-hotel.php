<style>
   .ms-options {
      z-index: 99999 !important;
   }
</style>
<div class="content">
   <div class="page-content">

      <div class="sale_bar">
         <div class="row">
            <div class="col-md-4">
               <h5 class="m-0">Add Hotel</h5>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <form name="web-partner" tts-form='true' action="<?php echo site_url('hotel-extranet/add-hotel-save'); ?>" method="POST" id="web-partner">
               <div class="row">
                  <div class="col-md-12">
                     <h6 class="view_head">Add Hotel</h6>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Hotel Name *</label>
                        <input class="form-control" type="text" name="hotel_name" placeholder="Hotel Name">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Hotel City *</label>
                        <input class="form-control" type="text" tts-get-city="true" name="hotel_city" placeholder="Hotel City">
                        <input type="hidden" name="city_id" tts-city-id="true">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Select Hotel Amenities *</label>
                        <select class="form-select select_search" name="hotel_amenities[]" multiple="multiple">
                           <?php if ($amenity) {
                              foreach ($amenity as $data) { ?>
                                 <option value="<?php echo $data['id'] ?>">
                                    <?php echo $data['amenity_title']; ?>
                                 </option>
                           <?php }
                           } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Select Property Type *</label>
                        <select class="form-select abhay_select_search" name="hotel_property_type_id">
                           <option value="" selected>Select Property Type</option>
                           <?php if ($property_lists) {
                              foreach ($property_lists as $data) { ?>
                                 <option value="<?php echo $data['id'] ?>">
                                    <?php echo $data['property_type']; ?>
                                 </option>
                           <?php }
                           } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Select Star Rating *</label>
                        <select class="form-select abhay_select_search" name="hotel_star_rating">
                           <option value="" selected>Select Star Rating</option>
                           <option value="1">1 Star</option>
                           <option value="2">2 Star</option>
                           <option value="3">3 Star</option>
                           <option value="4">4 Star</option>
                           <option value="5">5 Star</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label"> Image * </label>
                        <input class="form-control" type="file" name="hotel_images" placeholder=" Image">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Checkin time </label>
                        <select class="form-select abhay_select_search" name="check_in_time">
                           <option value="" selected>Checkin time</option>
                           <?php
                           for ($i = 1; $i <= 24; $i++) {

                           ?>
                              <option value="<?php echo $i . ':' . '00' ?>"><?php echo $i . ':' . '00' ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Checkout Time </label>
                        <select class="form-select abhay_select_search" name="check_out_time">
                           <option value="" selected>Checkout time</option>
                           <?php
                           for ($i = 1; $i <= 24; $i++) {

                           ?>
                              <option value="<?php echo $i . ':' . '00' ?>"><?php echo $i . ':' . '00' ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                           <option value="active" selected>Active</option>
                           <option value="inactive"> Inactive</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Review Rating Out of 5</label>
                        <input class="form-control" type="text" name="review_rating" placeholder="Ex 3.2">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Review Url </label>
                        <input class="form-control" type="text" name="review_url" placeholder="Review Url">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Latitude </label>
                        <input class="form-control" type="text" name="latitude" placeholder="Latitude">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Longitude </label>
                        <input class="form-control" type="text" name="longitude" placeholder="Longitude">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Address *</label>
                        <input class="form-control" type="text" name="address" placeholder="Address">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">State *</label>
                        <input class="form-control" type="text" name="state" placeholder="State">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">City Name *</label>
                        <input class="form-control" type="text" name="city_name" placeholder="City Name">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Postal Code *</label>
                        <input class="form-control" type="text" name="postal_code" placeholder="Postal Code">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Country Name *</label>
                        <input class="form-control" type="text" name="country_name" placeholder="Country Name">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Location Area *</label>
                        <input class="form-control" type="text" name="location_area" placeholder="Location Area">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Trending Hotel Show *</label>
                        <select class="form-control" name="trading_hotel" placeholder="Trending Hotel Showatus">
                           <option value="" selected>Select Trending Hotel Show</option>
                           <option value="No">No</option>
                           <option value="Yes"> Yes</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group form-mb-20">
                        <label class="mt20">
                           <input type="checkbox" name="pan_required" value="1" class="Lead">PAN Card Require
                        </label>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group form-mb-20">
                        <label class="mt20">
                           <input type="checkbox" name="passport_required" value="1" class="Lead">Passport
                           Require
                        </label>
                     </div>
                  </div>
               </div>
               <div class="form-group form-mb-20">
                  <label class="form-label">Hotel Promotion </label>
                  <textarea class="form-control" type="textarea" name="hotel_promotion" rows="3" placeholder="Hotel Promotion"></textarea>
               </div>
               <div class="form-group form-mb-20">
                  <label class="form-label">Hotel Description *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="hotel_description" rows="3" placeholder="Hotel Description"></textarea>
               </div>
               <div class="row">
                  <div class="col-md-12 text-md-right">
                     <button class="btn btn-primary" type="submit">Save</button>
                  </div>
               </div>
            </form>
         </div>
      </div>

   </div>
</div>
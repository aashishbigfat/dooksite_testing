<div class="modal-header">
   <h5 class="modal-title">Seat Allocations</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
   </button>
</div>
<form action="<?= site_url('private-fare/edit-return-international-seat-allocation/') . dev_encode($details['id']); ?>"
   method="post" tts-form="true" name="add_blogs">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-12">
            <h4 class="search__form__section__title">Allocation info</h4>
         </div>


         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label>Onward Date *</label>
               <input type="text" class="form-control" value="<?= $details['date']; ?>" praveen-from-date="true"
                  name="date" placeholder="Onward (Date)">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label for="">Reurn Date *</label>
               <input type="text" class="form-control" praveen-to-date="true" name="date_return"
                  placeholder="Reurn (Date)" value="<?= $details['date_return']; ?>">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label for="seats-available">Seats Available *</label>
               <input type="text" class="form-control" name="available_seats"
                  value="<?= $details['available_seats']; ?>" id="seats-available" placeholder="Seats Available">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label for="pnr">Airline PNR</label>
               <input type="text" class="form-control" id="pnr" name="pnr" value="<?= $details['pnr']; ?>"
                  placeholder="Airline PNR">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label for="booking-class">Booking Class *</label>
               <input type="text" id="booking-class" class="form-control" value="<?= $details['booking_class'] ?>"
                  name="booking_class" placeholder="Booking Class" value="">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label>Cabin Class *</label>
               <select class="form-control " name="cabin_class" id="cabin-class">
                  <option value="Economy" <?php if ($details['cabin_class'] == 'Economy') {
                     echo 'selected';
                  } ?>>Economy
                  </option>
                  <option value="PremiumEconomy" <?php if ($details['cabin_class'] == 'PremiumEconomy') {
                     echo 'selected';
                  } ?>>Premium Economy</option>
                  <option value="Business" <?php if ($details['cabin_class'] == 'Business') {
                     echo 'selected';
                  } ?>>Business
                  </option>
                  <option value="PremiumBusiness" <?php if ($details['cabin_class'] == 'PremiumBusiness') {
                     echo 'selected';
                  } ?>>Premium Business</option>
                  <option value="First" <?php if ($details['cabin_class'] == 'First') {
                     echo 'selected';
                  } ?>>First</option>
               </select>
            </div>
         </div>

         <div class="col-md-2 mt-4">
            <div class="form-group mb-3">
               <label class="form-label" for="commercial_flight">
                  <input type="radio" class="form-check-input me-2" name="flight_type" id="commercial_flight"
                     value="commercial" <?= !empty($details['flight_type']) && $details['flight_type'] == 'commercial' ? 'checked' : ''; ?>>
                  Commercial
               </label>
            </div>
         </div>

         <div class="col-md-2 mt-4">
            <div class="form-group mb-3">
               <label class="form-label" for="group_flight">
                  <input type="radio" class="form-check-input me-2" name="flight_type" id="group_flight" value="group"
                     <?= !empty($details['flight_type']) && $details['flight_type'] == 'group' ? 'checked' : ''; ?>>
                  Group
               </label>
            </div>
         </div>

         <div class="col-md-2 mt-4" id="is_deal">
            <div class="form-group mb-3">
               <label class="form-label" for="is_deal_ip">
                  <input type="checkbox" class="form-check-input me-2" name="is_deal" id="is_deal_ip" value="1"
                     <?= !empty($details['is_deal']) ? 'checked' : ''; ?>>
                  Is Deal
               </label>
            </div>
         </div>

         <div class="col-md-3" id="closing_date_ip">
            <div class="form-group form-mb-20">
               <label class="form-label d-flex" for="closing_date">
                  Closing Date *
               </label>
               <input type="text" name="closing_date" class="form-control" id="closing_date" placeholder="Closing Date"
                  value="<?= $details['closing_date']; ?>">
            </div>
         </div>

         <div class="col-md-3" id="advance_amount_ip">
            <div class="form-group form-mb-20">
               <label class="form-label d-flex" for="advance_amount">
                  Advance Amount *
               </label>
               <input type="text" name="advance_amount" class="form-control decimal" id="advance_amount"
                  placeholder="Advance Amount" value="<?= $details['advance_amount']; ?>">
            </div>
         </div>

         <div class="col-md-3" id="minm_seats_ip">
            <div class="form-group form-mb-20">
               <label class="form-label d-flex" for="minm_seats">
                  Minm. Required Seats *
               </label>
               <input type="text" name="minm_seats" class="form-control numeric" id="minm_seats"
                  placeholder="Minm. Required Seats" value="<?= $details['minm_seats']; ?>">
            </div>
         </div>

         <div class="col-md-3" id="moq_ip">
            <div class="form-group form-mb-20">
               <label class="form-label d-flex" for="moq">
                  MOQ *
               </label>
               <input type="text" name="moq" class="form-control numeric" id="moq" placeholder="MOQ"
                  value="<?= $details['moq']; ?>">
            </div>
         </div>

         <div class="col-md-12">
            <div class="row">
               <div class="col-md-12">
                  <div class="row align-items-center">
                     <div class="col-md-4">
                        <span class="rate-plan__details__label">Adult</span>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Base Fare *</label>
                           <input type="text" class="form-control" value="<?= $details['adult_base_fare'] ?>"
                              name="adult_base_fare" placeholder="Base Fare">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Tax *</label>
                           <input type="text" class="form-control" value="<?= $details['adult_tax'] ?>" name="adult_tax"
                              placeholder="Tax">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="row align-items-center">
                     <div class="col-md-4">
                        <span class="rate-plan__details__label">Child</span>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Base Fare *</label>
                           <input type="text" class="form-control" value="<?= $details['child_base_fare'] ?>"
                              name="child_base_fare" placeholder="Base Fare">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Tax *</label>
                           <input type="text" class="form-control" value="<?= $details['child_tax'] ?>" name="child_tax"
                              placeholder="Tax">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="row align-items-center">
                     <div class="col-md-4">
                        <span class="rate-plan__details__label">Infant</span>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Base Fare *</label>
                           <input type="text" class="form-control" value="<?= $details['infant_base_fare'] ?>"
                              name="infant_base_fare" placeholder="Base Fare">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label for="from-date">Tax *</label>
                           <input type="text" class="form-control" value="<?= $details['infant_tax'] ?>"
                              name="infant_tax" placeholder="Tax">
                        </div>
                     </div>
                  </div>
               </div>
               <?php
               $timePicker = time_picker();

               $oneway_key = 0;


               foreach ($segment_detail as $tripkey => $segment) {
                  echo '<div class="col-md-12">
            <h5 class="search__form__section__title">Trip ' . ($tripkey + 1) . '</h5>
         </div>';
                  $onward_stops = count($segment);
                  for ($i = 0; $i < $onward_stops; $i++) {
                     $origin_airport_code = null;
                     $destination_airport_code = null;
                     $airline_code = null;
                     $origin_terminal = null;
                     $destination_terminal = null;
                     $flight_number = null;
                     $departure_time = null;
                     $arrival_time = null;
                     $is_next_day_arrival = null;
                     if (isset($segment_detail[$tripkey][$i]['origin_airport_code'])) {

                        $origin_airport_code = $segment_detail[$tripkey][$i]['origin_airport_code'];

                     }

                     if (isset($segment_detail[$tripkey][$i]['destination_airport_code'])) {

                        $destination_airport_code = $segment_detail[$tripkey][$i]['destination_airport_code'];

                     }

                     if (isset($segment_detail[$tripkey][$i]['airline_code'])) {

                        $airline_code = $segment_detail[$tripkey][$i]['airline_code'];

                     }

                     if (isset($segment_detail[$tripkey][$i]['origin_terminal'])) {

                        $origin_terminal = $segment_detail[$tripkey][$i]['origin_terminal'];

                     }

                     if (isset($segment_detail[$tripkey][$i]['destination_terminal'])) {

                        $destination_terminal = $segment_detail[$tripkey][$i]['destination_terminal'];

                     }



                     if (isset($segment_detail[$tripkey][$i]['flight_number'])) {

                        $flight_number = $segment_detail[$tripkey][$i]['flight_number'];

                     }



                     if (isset($segment_detail[$tripkey][$i]['departure_time'])) {

                        $departure_time = $segment_detail[$tripkey][$i]['departure_time'];

                     }



                     if (isset($segment_detail[$tripkey][$i]['arrival_time'])) {

                        $arrival_time = $segment_detail[$tripkey][$i]['arrival_time'];

                     }



                     if (isset($segment_detail[$tripkey][$i]['is_next_day_arrival'])) {

                        $is_next_day_arrival = $segment_detail[$tripkey][$i]['is_next_day_arrival'];

                     }





                     ?>
                     <div class="col-md-12">
                        <h5 class="search__form__section__title">Segment Information</h5>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label>Origin Airport Code *</label>
                           <input class="form-control" type="text" placeholder="Origin Airport Code"
                              value="<?= $origin_airport_code; ?>" disabled>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label>Destination Airport Code *</label>
                           <input class="form-control" type="text" placeholder="Destination Airport Code"
                              value="<?= $destination_airport_code; ?>" disabled>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group form-mb-20">
                           <label>Airline Code *</label>
                           <input class="form-control" type="text" placeholder="Airline Code" value="<?= $airline_code; ?>"
                              disabled>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Flight Number </label>
                           <input class="form-control" type="text" name="onward[<?= $tripkey; ?>][<?= $i; ?>][flight_number]"
                              placeholder="Flight Number" value="<?= $flight_number; ?>">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Departure Time</label>
                           <select class="form-control" name="onward[<?= $tripkey; ?>][<?= $i; ?>][departure_time]">
                              <option value="" selected>Departure Time *</option>
                              <?php foreach ($timePicker as $key => $time) { ?>
                                 <option value="<?= $time; ?>" <?php if ($time == $departure_time) {
                                      echo 'selected';
                                   } ?>><?= $time; ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Arrival Time</label>
                           <select class="form-control" name="onward[<?= $tripkey; ?>][<?= $i; ?>][arrival_time]">
                              <option value="" selected>Arrival Time *</option>
                              <?php foreach ($timePicker as $key => $time) { ?>
                                 <option value="<?= $time; ?>" <?php if ($time == $arrival_time) {
                                      echo 'selected';
                                   } ?>><?= $time; ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Is Arrival Next Day?</label>
                           <select class="form-control" name="onward[<?= $tripkey; ?>][<?= $i; ?>][is_next_day_arrival]"
                              placeholder="Is Arrival Next Day?">
                              <option value="No" <?php if ($is_next_day_arrival == 'No') {
                                 echo 'selected';
                              } ?>>No</option>
                              <option value="Yes" <?php if ($is_next_day_arrival == 'Yes') {
                                 echo 'selected';
                              } ?>>Yes</option>
                           </select>
                        </div>
                     </div>
                  <?php }
               } ?>
               <div class="col-md-12">
                  <div class="row mt-4">
                     <div class="col-md-6">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="1" name="rate_plan_check"
                              id="rate-plan-check" checked>
                           <label class="form-check-label" for="rate-plan-check">
                              Enable and apply for all rate plans in the range
                           </label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="1" name="seat_check" id="seat-check"
                              checked>
                           <label class="form-check-label" for="seat-check">
                              Enable and apply for all seats remaining in the range
                           </label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="1" name="pnr_check" id="pnr-check"
                              checked>
                           <label class="form-check-label" for="pnr-check">
                              Enable and apply for Airline PNR
                           </label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="1" name="segment_check"
                              id="segment-check" checked>
                           <label class="form-check-label" for="segment-check">
                              Enable and apply for all segments in the range
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <div class="form__buttons text-md-right">
               <button type="submit" class="btn btn-primary">
                  Update Seat Allocation
               </button>
            </div>
         </div>
</form>


<script>
   $("#closing_date").datepicker({
      dateFormat: "d-M-y",
      minDate: 0,
      maxDate: $("[praveen-from-date]").datepicker("getDate"),
      beforeShow: function () {
         var dateString = $('[praveen-from-date]').val();
         var newdate = dateString.split(" ").join("-");
         var newdate = new Date(newdate);
         $(this).datepicker("option", "maxDate", newdate);
      }
   });

   function toggleFields() {
      if ($("#group_flight").is(":checked")) {
         $("#closing_date_ip, #advance_amount_ip, #minm_seats_ip").show();
      } else {
         $("#closing_date_ip, #advance_amount_ip, #minm_seats_ip").hide();
      }
   }

   toggleFields();

   $("input[name='flight_type']").change(function () {
      toggleFields();
   });
</script>
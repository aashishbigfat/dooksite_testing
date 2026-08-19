<?php
$Date = date("Y/m/d");
$origin = "Delhi (DEL), India";
$destination = "Mumbai (BOM), India";
$originAirportCode = "DEL";
$originCity = "Delhi";
$destinationAirportCode = "BOM";
$destinationCity = "Mumbai";
$departdate = date(DateFormat, strtotime($Date . ' + 1 days'));;
$returndate = "";
if ($searchData['journeytype'] == 'Oneway' || $searchData['journeytype'] == 'Roundtrip') {
   $origin = $searchData['origin'];
   $destination = $searchData['destination'];
   $departdate = $searchData['departdate'];
   $returndate = $searchData['returndate'];
   $originAirportCode = AirportCode($origin);
   $destinationAirportCode = AirportCode($destination);
   $originCity = AirportCity($origin);
   $destinationCity = AirportCity($destination);
}
?>
<div class="card">
   <div class="card-body">
      <div class="banner-form">
         <!----radio----->
         <div class="flight-search d-flex align-items-center justify-content-between flex-wrap mb-2">
            <div class="d-flex align-items-center flex-wrap">
               <div class="form-check d-flex align-items-center me-3">
                  <input class="form-check-input mt-0" type="radio" name="flightjtype" id="oneway" value="Oneway" onclick="checkflightJourneytype('Oneway')" checked="">
                  <label class="search-check-label form-check-label ms-2" for="oneway">Oneway</label>
               </div>
               <div class="form-check d-flex align-items-center me-3">
                  <input class="form-check-input mt-0" type="radio" name="flightjtype" id="roundtrip" value="Roundtrip" onclick="checkflightJourneytype('Roundtrip')">
                  <label class="search-check-label form-check-label ms-2" for="roundtrip">Round Trip</label>
               </div>
               <div class="form-check d-flex align-items-center me-3">
                  <input class="form-check-input mt-0" type="radio" name="flightjtype" id="multiway" value="Multicity" onclick="checkflightJourneytype('Multicity')">
                  <label class="search-check-label form-check-label ms-2" for="multiway">Multi City</label>
               </div>
            </div>
            <h6 class="fw-medium mb-0">Millions of cheap flights. One simple search</h6>
         </div>
         <!----radio----->

         <form action="<?php echo site_url("flight/search"); ?>" type="get" class="tts__form_wrapper" name="flight-form" flight-oneway-roundtrip-form="true">
            <input type="hidden" value="<?php echo $searchData['journeytype']; ?>" name="journeytype">
            <input type="hidden" class="form-check-input" name="direct_flight" value="<?php echo $searchData['direct_flight']; ?>">
            <input type="hidden" name="preferred_carriers" value="<?php echo $searchData['preferred_carriers']; ?>" id="PreferredCarriers">
            <input type="hidden" name="result_fare_type" resultFareType="true" value="<?= $searchData['result_fare_type'] ?>">
            <div class="normal-trip">
               <div class="d-lg-flex">
                  <div class="d-flex form-info">
                     <div class="form-item dropdown">
                        <label class="form-label">From Airport</label>
                        <input type="text" class="form-control tts__input__input" placeholder="Origin" value="<?php echo $originCity; ?>" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from airport">
                        <input type="hidden" name="origin" value="<?php echo $origin; ?>">
                        <div class="flight_text_p">[<?php echo  $originAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$originAirportCode]) ? $OriginDestinationAirportDetail[$originAirportCode] : ""; ?></div>
                     </div>
                     <div class="form-item dropdown ps-2 ps-sm-3">
                        <label class="form-label ">To Airport</label>
                        <input type="text" class="form-control  tts__input__input pl-md-4" name="destination" placeholder="Destination" value="<?php echo $destinationCity; ?>" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport">
                        <input type="hidden" name="destination" value="<?php echo $destination; ?>">
                        <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode]) ? $OriginDestinationAirportDetail[$destinationAirportCode] : ""; ?></div>
                        <span class="way-icon badge badge-primary rounded-pill translate-middle tts__interchange__arrow" alt="arrows" swape-city="true"><i class="fa-solid fa-arrow-right-arrow-left"></i></span>
                     </div>
                     <div class="form-item">
                        <label class="form-label">Depart</label>
                        <input type="text" class="form-control tts__input__input tts-cursor-pointer" name="departdate" placeholder="Depart Date" value="<?php echo $departdate; ?>" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" readonly>
                        <div class="flight_text_p"><?php echo date('l', strtotime($departdate)); ?></div>
                     </div>
                    
                        <div class="form-item round-drip" onclick="selectroundtripDate('Roundtrip')">
                           <label class="form-label">Return</label>
                           <input type="text" class="form-control tts__input__input flight-return-date-disable tts-cursor-pointer" name="returndate" readonly placeholder="" value="<?php echo $returndate; ?>" data-validation="required" data-validation-error-msg="Please select return date" flight-return-date="true" readonly>
                           <div class="flight_text_p">
                              <?php if ($returndate) {
                                 echo date('l', strtotime($returndate));
                              } ?>
                           </div>
                        </div>
                    
                     <div class="form-item dropdown">
                        <div id="select_flight_pax" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" role="menu">
                           <label class="form-label f">Travellers and cabin class</label>
                           <h5>
                              <span data-total-pax="true"><?php echo $searchData['adults'] + $searchData['child'] + $searchData['infant']; ?></span>
                              <span class="fw-normal fs-14">Persons</span>
                           </h5>
                           <div class="flight_text_p" flight-cabin-class="true"><?php echo $searchData['cabinclass']; ?></div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end dropdown-xl" aria-labelledby="select_flight_pax">
                           <h5 class="mb-3">Select Travelers & Class</h5>
                           <div class="mb-3 border info-item pb-1">
                              <h6 class="fs-16 fw-medium mb-2">Travellers</h6>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Adults <span class="text-default fw-normal">( 12+ Yrs )</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-adult-pre="true">
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-adult-count="true"><?php echo $searchData['adults']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['adults']; ?>" name="adults" adult-input="true">
                                             <span class="input-group-btn float-end" data-adult-next="true">
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Children <span class="text-default fw-normal">(2y - 12y)</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-child-pre>
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-child-count="true"><?php echo $searchData['child']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['child']; ?>" name="child" child-input="true">
                                             <span class="input-group-btn float-end" data-child-next>
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Infant <span class="text-default fw-normal">(below 2y)</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-infant-pre>
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-infant-count="true"><?php echo $searchData['infant']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['infant']; ?>" name="infant" infant-input="true">
                                             <span class="input-group-btn float-end" data-infant-next>
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="border info-item pb-1">
                              <h6 class="fs-16 fw-medium mb-2">Cabin Class</h6>
                              <div class="d-flex align-items-center flex-wrap">
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="any1" name="cabinclass" value="Any" onclick="changeCabinclass('Any' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Any' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="any1">Any</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="economy1" name="cabinclass" value="Economy" onclick="changeCabinclass('Economy','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Economy' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="economy1">Economy </label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="business1" name="cabinclass" value="Business" onclick="changeCabinclass('Business','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Business' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="business1">Business </label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="premiumeconomy1" name="cabinclass" value="PremiumEconomy" onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumEconomy' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="premiumeconomy1">Premium Economy</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="premiumbusiness1" name="cabinclass" value="PremiumBusiness" onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumBusiness' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="premiumbusiness1">Premium Business</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="first1" name="cabinclass" value="First" onclick="changeCabinclass('First' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'First' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="first1">First</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <button type="submit" class="btn btn-primary search-btn rounded" onclick="return checkFlightSearchValidation('flight-form');">Search</button>
               </div>
            </div>

         </form>
         <form action="<?php echo site_url("flight/search"); ?>" type="get" class="tts__form_wrapper" name="flight-multi-form" flight-multicity-form="true" style="display: none;">
            <input type="hidden" value="Multicity" name="journeytype">
            <input type="hidden" class="form-check-input" name="direct_flight" value="<?php echo $searchData['direct_flight'] ?>">
            <input type="hidden" name="preferred_carriers" value="<?php echo $searchData['preferred_carriers']; ?>" id="PreferredCarriers">
            <input type="hidden" name="result_fare_type" resultFareType="true" value="<?= $searchData['result_fare_type'] ?>">
            <div class="multi-trip" multicity-addmore>
               <div class="d-lg-flex">
                  <div class="d-flex form-info" data-journey-key="0">
                     <div class="form-item">
                        <label class="form-label">From Airport</label>
                        <input type="text" class="form-control tts__input__input" placeholder="Origin" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from airport" value="<?php echo $originCity; ?>" data-key="0">
                        <input type="hidden" name="search_data[0][origin]" value="<?php echo $origin; ?>">
                        <div class="flight_text_p">[<?php echo  $originAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$originAirportCode]) ? $OriginDestinationAirportDetail[$originAirportCode] : ""; ?></div>
                     </div>
                     <div class="form-item">
                        <label class="form-label ">To Airport</label>
                        <input type="text" class="form-control  tts__input__input pl-md-4" placeholder="Destination" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport" value="<?php echo $destinationCity; ?>" data-key="0">
                        <input type="hidden" name="search_data[0][destination]" value="<?php echo $destination; ?>">
                        <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode]) ? $OriginDestinationAirportDetail[$destinationAirportCode] : ""; ?></div>
                     </div>
                     <div class="form-item">
                        <label class="form-label">Depart</label>
                        <input type="text" class="form-control tts__input__input tts-cursor-pointer" name="search_data[0][departdate]" placeholder="Depart Date" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" value="<?php echo $departdate; ?>" data-key="0" readonly>
                        <div class="flight_text_p"><?php echo date('l', strtotime($departdate)); ?></div>
                     </div>
                     <div class="form-item dropdown">
                        <div id="select_flight_pax" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" role="menu">
                           <label class="form-label f">Travellers and cabin class</label>
                           <h5>
                              <span data-total-pax="true"><?php echo $searchData['adults'] + $searchData['child'] + $searchData['infant']; ?></span>
                              <span class="fw-normal fs-14">Persons</span>
                           </h5>
                           <div class="flight_text_p" flight-cabin-class="true"><?php echo $searchData['cabinclass']; ?></div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end dropdown-xl" aria-labelledby="select_flight_pax">
                           <h5 class="mb-3">Select Travelers & Class</h5>
                           <div class="mb-3 border info-item pb-1">
                              <h6 class="fs-16 fw-medium mb-2">Travellers</h6>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Adults <span class="text-default fw-normal">( 12+ Yrs )</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-adult-pre="true">
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-adult-count="true"><?php echo $searchData['adults']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['adults']; ?>" name="adults" adult-input="true">
                                             <span class="input-group-btn float-end" data-adult-next="true">
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Children <span class="text-default fw-normal">(2y - 12y)</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-child-pre>
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-child-count="true"><?php echo $searchData['child']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['child']; ?>" name="child" child-input="true">
                                             <span class="input-group-btn float-end" data-child-next>
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="mb-3">
                                       <label class="form-label mb-2">Infant <span class="text-default fw-normal">(below 2y)</span></label>
                                       <div class="custom-increment">
                                          <div class="input-group align-items-center">
                                             <span class="input-group-btn float-start" data-infant-pre>
                                                <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus" data-field="">
                                                   <span><i class="fa-solid fa-minus"></i></span>
                                                </button>
                                             </span>
                                             <span class="input-number" data-infant-count="true"><?php echo $searchData['infant']; ?></span>
                                             <input class="input-number" type="hidden" value="<?php echo $searchData['infant']; ?>" name="infant" infant-input="true">
                                             <span class="input-group-btn float-end" data-infant-next>
                                                <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                                                   <span><i class="fa-solid fa-add"></i></span>
                                                </button>
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 border info-item pb-1">
                              <h6 class="fs-16 fw-medium mb-2">Travellers</h6>
                              <div class="d-flex align-items-center flex-wrap">
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="any" name="cabinclass" value="Any" onclick="changeCabinclass('Any' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Any' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="any">Any</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="economy" name="cabinclass" value="Economy" onclick="changeCabinclass('Economy','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Economy' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="economy">Economy </label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="business" name="cabinclass" value="Business" onclick="changeCabinclass('Business','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Business' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="business">Business </label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="premiumeconomy" name="cabinclass" value="PremiumEconomy" onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumEconomy' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="premiumeconomy">Premium Economy</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="premiumbusiness" name="cabinclass" value="PremiumBusiness" onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumBusiness' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="premiumbusiness">Premium Business</label>
                                 </div>
                                 <div class="form-check me-3 mb-3">
                                    <input class="form-check-input" type="radio" id="first" name="cabinclass" value="First" onclick="changeCabinclass('First' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'First' ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="first">First</label>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
                  <button type="submit" class="btn btn-primary search-btn rounded" onclick="return checkFlightSearchValidation('flight-multi-form');">Search </button>
               </div>
               <div class="d-lg-flex mt-3">
                  <div class="d-flex form-info " data-journey-key="1">
                     <div class="form-item">
                        <label class="form-label">From Airport</label>
                        <input type="text" class="form-control tts__input__input tts__input__input1" placeholder="Origin" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from airport" value="<?php echo $destinationCity; ?>" data-key="1">
                        <input type="hidden" name="search_data[1][origin]" value="<?php echo $destination; ?>">
                        <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode]) ? $OriginDestinationAirportDetail[$destinationAirportCode] : ""; ?></div>
                     </div>
                     <div class="form-item">
                        <label class="form-label ">To Airport</label>
                        <input type="text" class="form-control  tts__input__input pl-md-4 tts__input__input1" placeholder="Destination" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport" data-key="1">
                        <input type="hidden" name="search_data[1][destination]" value="<?php echo $destination; ?>">
                        <div class="flight_text_p"></div>
                     </div>
                     <div class="form-item dropdown">
                        <label class="form-label">Depart</label>
                        <input type="text" class="form-control tts__input__input tts-cursor-pointer tts__input__input1 tts__input__input2" name="search_data[1][departdate]" placeholder="Depart Date" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" data-key="1" readonly>
                        <div class="flight_text_p"></div>
                     </div>
                  </div>
                  <button type="button" class="btn btn-primary search-btn rounded addcity-btn" style="min-width: 150px;" add-mult-city="true">Add City </button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>



<script>
   $(function() {
      if (flightsearchData != '') {
         setTimeout(function() {
            var type = flightsearchData.journeytype.toString();
            if (type == 'Multicity') {
               checkflightJourneytype(type);
               var modify_info = flightsearchData;
               var OriginDestinationAirportDetail = '<?php echo json_encode($OriginDestinationAirportDetail); ?>';
               var modify_search_info_count = Number(Object.keys(modify_info.search_data).length) - 2;
               for ($msi = 1; $msi <= modify_search_info_count; $msi++) {
                  $("[add-mult-city]").trigger('click');
               }
               form_fill_modify_search('[flight-multicity-form]', modify_info, OriginDestinationAirportDetail);
            } else {
               checkflightJourneytype(type);
            }
         }, 2000);
      }
   });
</script>
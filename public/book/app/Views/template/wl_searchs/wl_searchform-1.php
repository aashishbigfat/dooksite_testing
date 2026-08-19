<?php
$router = service('router');
$class_name = $router->controllerName();

$classparm = explode("\\", $class_name);
$controller = end($classparm);


?>
<div class="search-form">
   <div class="hero-banner">
      <?php if (isset($slider_list) && !empty($slider_list)) : ?>
         <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
               <?php foreach ($slider_list as $key => $slider) : ?>
                  <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>" style="background-image:url(<?php echo root_url . 'uploads/sliders/' . $slider['slider_image'] ?>);">
                     <!-- <img src="<?php echo root_url . 'uploads/sliders/' . $slider['slider_image'] ?>" alt="<?= (!empty($slider['slider_text1'])) ? $slider['slider_text1'] : '' ?>" width="100%" height="400px"> -->
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif; ?>
   </div>
   <div class="search_form_section">
      <div class="container">
         <div class="row">
            <div class="col-md-12 m-auto">
               <div class="page-content search_form_box">
                  <div class="tts_product_box">
                     <div class="searchtabslist">
                        <ul class=" search_tabs">
                           <?php if (whitelabel['flight_module'] == 'active') { ?>
                              <li class="tab-link ">
                                 <a href="<?php echo site_url('flight') ?> " class="<?php echo active_tab("Flight"); ?> <?php echo active_tab("Home"); ?> flights">
                                    <span><i class="fa-solid fa-plane-departure"></i> Flight </span>
                                 </a>
                              </li>
                           <?php } ?>
                           <?php if (whitelabel['hotel_module'] == 'active') { ?>
                              <li class="tab-link ">
                                 <a href="<?php echo site_url('hotel') ?> " class="<?php echo active_tab("Hotel"); ?>">
                                    <span> <i class="fa-solid fa-building"></i> Hotel </span>
                                 </a>
                              </li>
                           <?php } ?>

                          
                        </ul>
                     </div>
                     <?php if (($controller == 'Flight' || $controller == 'Home') && (whitelabel['flight_module'] == 'active')) { ?>
                        <div id="flight" class="tab-content <?php echo active_tab("Flight"); ?> <?php echo active_tab("Home"); ?>">
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
                           <div class="row align-items-center">
                              <div class="tab-pane fade show active">
                                 <form action="<?php echo site_url("flight/search"); ?>" type="get" class="tts__form_wrapper" name="flight-form" flight-oneway-roundtrip-form="true">
                                    <input type="hidden" value="Oneway" name="journeytype">
                                    <ul class="row flight_search_border">
                                       <li class="col-lg-3 col-md-6 col-6 from">
                                          <label class="form-label">From </label>
                                          <input type="text" class="form-control tts__input__input tts-cursor-pointer" placeholder="Origin" value="Delhi" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from ">
                                          <input type="hidden" name="origin" value="Delhi (DEL), India">
                                          <div class="flight_text_p">[DEL] Indira Gandhi International</div>
                                          <span class="tts__interchange__arrow" alt="arrows" swape-city="true">
                                             <svg width="20" height="15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.11 6.739a.842.842 0 0 1-.842-.842V4.844a.21.21 0 0 0-.21-.211H4.543a1.264 1.264 0 1 1 0-2.527h8.515a.21.21 0 0 0 .21-.21V.841A.843.843 0 0 1 14.544.12l4.212 2.528a.842.842 0 0 1 0 1.444L14.544 6.62a.843.843 0 0 1-.433.12ZM.409 10.26l4.212-2.527a.842.842 0 0 1 1.276.723v1.053c0 .116.095.21.21.21h8.516a1.264 1.264 0 1 1 0 2.528H6.108a.21.21 0 0 0-.21.21v1.053a.842.842 0 0 1-1.277.722L.409 11.705a.842.842 0 0 1 0-1.445Z" fill="#000000"></path>
                                             </svg>
                                          </span>
                                       </li>
                                       <li class="col-lg-3 col-md-6 col-6  to">
                                          <div class="position-relative">
                                             <label class="form-label">To </label>
                                             <input type="text" class="form-control  tts__input__input tts-cursor-pointer" placeholder="Destination" value="Mumbai" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport">
                                             <input type="hidden" name="destination" value="Mumbai (BOM), India">
                                             <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                          </div>
                                       </li>
                                       <li class="col-lg-3 col-md-12 col-12 depart">
                                          <div class="position-relative">
                                             <label class="form-label"><i class="fa-solid fa-calendar-days"></i> Depart <i class="fa-solid fa-angle-down"></i></label>
                                             <input type="text" class="form-control tts__input__input tts-cursor-pointer" name="departdate" placeholder="Depart" readonly value="<?php $Date = date("Y/m/d");
                                                                                                                                                                                 echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true">
                                             <div class="flight_text_p">
                                                <?php echo date('l', strtotime($Date . ' + 1 days')); ?>
                                             </div>
                                          </div>
                                       </li>
                                       <li class="col-lg-3 col-md-12 col-12 return">
                                          <div class="position-relative" onclick="selectroundtripDate('Roundtrip')">
                                             <label class="form-label"><i class="fa-solid fa-calendar-days"></i> Return <i class="fa-solid fa-angle-down"></i></label>
                                             <input type="text" class="form-control tts__input__input flight-return-date-disable tts-cursor-pointer" name="returndate" readonly placeholder="" data-validation="required" data-validation-error-msg="Please select return date" flight-return-date="true">
                                             <div class="flight_text_p">Book a round trip to save more </div>
                                          </div>
                                       </li>
                                       <li class="col-lg-2 col-md-8 col-12 travellers">
                                          <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                             <label class="form-label">Travellers & Class </label>
                                             <div id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                <h6 class="date" data-total-pax="true"> 1
                                                   <span>Traveller(s)</span>
                                                </h6>
                                                <p class="cabinclass" flight-cabin-class="true"> Any</p>
                                             </div>
                                             <div class=" tts__dropdown__menu__right p-3 dropdown-menu" aria-labelledby="select_flight_pax" style="width: 300px;">
                                                <div class="row mb-3">
                                                   <div class="col-md-12 col-12">
                                                      <div class="tts__traveller__select mt-0 d-flex align-items-center">
                                                         <h5>Adult</h5>
                                                         <div class="GwMit">
                                                            <span class="tts__counter" data-adult-pre="true">-</span>
                                                            <span class="tts_traveller__counter_span" data-adult-count="true">1</span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="1" name="adults" adult-input="true">
                                                            <span class="tts__counter" data-adult-next="true">+</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-12 ">
                                                      <div class="tts__traveller__select mt-2 d-flex align-items-center">
                                                         <h5>Children <span class="tts__traveller__limit">(2y - 12y)</span>
                                                         </h5>
                                                         <div class="GwMit">
                                                            <span class="tts__counter" data-child-pre>-</span>
                                                            <span class="tts_traveller__counter_span" data-child-count="true">0</span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="0" name="child" child-input="true">
                                                            <span class="tts__counter" data-child-next>+</span>
                                                         </div>

                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-12 ">
                                                      <div class="tts__traveller__select mt-2 d-flex align-items-center">
                                                         <h5>Infant <span class="tts__traveller__limit">(below 2y)</span>
                                                         </h5>
                                                         <div class="GwMit">
                                                            <span class="tts__counter" data-infant-pre>-</span>
                                                            <span class="tts_traveller__counter_span" data-infant-count="true">0</span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="0" name="infant" infant-input="true">
                                                            <span class="tts__counter" data-infant-next>+</span>
                                                         </div>

                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <h5>Choose travel class</h5>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Any" class="mr-1" onclick="changeCabinclass('Any' ,'flight-cabin-class')" checked> Any</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Economy" class="mr-1" onclick="changeCabinclass('Economy','flight-cabin-class')">
                                                         Economy
                                                      </label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Business" class="mr-1" onclick="changeCabinclass('Business','flight-cabin-class')">
                                                         Business
                                                      </label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="PremiumEconomy" class="mr-1" onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')">
                                                         Premium Economy</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="PremiumBusiness" class="mr-1" onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')">
                                                         Premium
                                                         Business</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="First" class="mr-1" onclick="changeCabinclass('First' ,'flight-cabin-class')">
                                                         First</label>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </li>
                                       <li class="col-lg-1 col-md-4 col-12  btnarea">
                                          <button type="submit" class="oneway_btn oneway_search_btn btn" onclick="return checkFlightSearchValidation('flight-form');">Search </button>
                                       </li>
                                    </ul>
                                    <input type="hidden" class="form-check-input" name="direct_flight" value="0">
                                    <input type="hidden" name="preferred_carriers" id="PreferredCarriers">
                                    <input type="hidden" name="result_fare_type" resultFareType="true" value="RegularFare">
                                 </form>
                                 <form action="<?php echo site_url("flight/search"); ?>" type="get" class="tts__form_wrapper" name="flight-multi-form" flight-multicity-form="true" style="display: none;">
                                    <input type="hidden" value="Multicity" name="journeytype">
                                    <input type="hidden" class="form-check-input" name="direct_flight" value="0">
                                    <div multicity-addmore class="flight-mult-city">
                                       <ul class="row flight_search_border " data-journey-key="0">
                                          <li class="col-lg-3 col-md-6 col-6 from">
                                             <div class="position-relative">
                                                <label class="form-label">From </label>
                                                <input type="text" class="form-control tts__input__input " placeholder="Origin" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from " value="Delhi" data-key="0">
                                                <input type="hidden" name="search_data[0][origin]" value="Delhi (DEL), India">
                                                <div class="flight_text_p">[DEL] Indira Gandhi International</div>
                                             </div>
                                          </li>
                                          <li class="col-lg-3 col-md-6 col-6 to">
                                             <div class="position-relative">
                                                <label class="form-label">To </label>
                                                <input type="text" class="form-control  tts__input__input" placeholder="Destination" tts-flight-destination="true" data-validation="required" value="Mumbai" data-validation-error-msg="Please select to airport" data-key="0">
                                                <input type="hidden" name="search_data[0][destination]" value="Mumbai (BOM), India">
                                                <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                             </div>
                                          </li>
                                          <li class="col-lg-3 col-md-12 col-12 depart">
                                             <div class="position-relative">
                                                <label class="form-label"><i class="fa-solid fa-calendar-days"></i> Depart
                                                   <i class="fa-solid fa-angle-down"></i></label>
                                                <input type="text" class="form-control tts__input__input tts-cursor-pointer" name="search_data[0][departdate]" placeholder="Depart Date" data-validation="required" data-validation-error-msg="Please select departure date" value="<?php $Date = date("Y/m/d");
                                                                                                                                                                                                                                                                                    echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>" flight-departure-date="true" data-key="0" readonly>
                                                <div class="flight_text_p">
                                                   <?php echo date('l', strtotime($Date . ' + 1 days')); ?>
                                                </div>
                                             </div>
                                          </li>
                                          <li class="col-lg-2 col-md-12 col-12 travellers">
                                             <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                                <label class="form-label">Travellers & Class </label>
                                                <div id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                   <h6 class="date" data-total-pax="true"> 1
                                                      <span>Traveller(s)</span>
                                                   </h6>
                                                   <p class="cabinclass" flight-cabin-class="true"> Any</p>
                                                </div>
                                                <div class=" tts__dropdown__menu__right p-3 dropdown-menu" aria-labelledby="select_flight_pax" style="width: 300px;">
                                                   <div class="row mb-3">
                                                      <div class="col-md-12 col-12">
                                                         <div class="tts__traveller__select mt-0 d-flex align-items-center">
                                                            <h5>Adult</h5>
                                                            <div class="GwMit">
                                                               <span class="tts__counter" style="margin-right: -5px;" data-adult-pre="true">-</span>
                                                               <span class="tts_traveller__counter_span" data-adult-count="true">1</span>
                                                               <input class="form-control tts_traveller__counter" type="hidden" value="1" name="adults" adult-input="true">
                                                               <span class="tts__counter" data-adult-next="true">+</span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 col-12">
                                                         <div class="tts__traveller__select mt-0 d-flex align-items-center">
                                                            <h5>Children <span class="tts__traveller__limit">(2y -
                                                                  12y)</span></h5>
                                                            <div class="GwMit">
                                                               <span class="tts__counter" style="margin-right: -5px;" data-child-pre>-</span>
                                                               <span class="tts_traveller__counter_span" data-child-count="true">0</span>
                                                               <input class="form-control tts_traveller__counter" type="hidden" value="0" name="child" child-input="true">
                                                               <span class="tts__counter" data-child-next>+</span>
                                                            </div>

                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 col-12">
                                                         <div class="tts__traveller__select mt-0 d-flex align-items-center">
                                                            <h5>Infant <span class="tts__traveller__limit">(below
                                                                  2y)</span></h5>
                                                            <div class="GwMit">
                                                               <span class="tts__counter" style="margin-right: -5px;" data-infant-pre>-</span>
                                                               <span class="tts_traveller__counter_span" data-infant-count="true">0</span>
                                                               <input class="form-control tts_traveller__counter" type="hidden" value="0" name="infant" infant-input="true">
                                                               <span class="tts__counter" data-infant-next>+</span>
                                                            </div>

                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="Any" class="mr-1" onclick="changeCabinclass('Any' ,'flight-cabin-class')" checked> Any</label>
                                                      </div>
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="Economy" class="mr-1" onclick="changeCabinclass('Economy','flight-cabin-class')">
                                                            Economy
                                                         </label>
                                                      </div>
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="Business" class="mr-1" onclick="changeCabinclass('Business','flight-cabin-class')">
                                                            Business
                                                         </label>
                                                      </div>
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="PremiumEconomy" class="mr-1" onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')">
                                                            Premium Economy</label>
                                                      </div>
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="PremiumBusiness" class="mr-1" onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')">
                                                            Premium
                                                            Business</label>
                                                      </div>
                                                      <div class="col-12">
                                                         <label class="tts__inputradio_label">
                                                            <input type="radio" name="cabinclass" value="First" class="mr-1" onclick="changeCabinclass('First' ,'flight-cabin-class')">
                                                            First</label>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </li>

                                       </ul>
                                       <ul class="row  align-items-center border-top-0 flight_search_border" data-journey-key="1">
                                          <li class="col-lg-3 col-md-6 col-6 from">
                                             <div class="position-relative">
                                                <label class="form-label">From</label>
                                                <input type="text" class="form-control tts__input__input tts__input__input1" name="search_data[1][origin]" placeholder="Origin" tts-flight-origin="true" value="Mumbai" data-validation="required" data-validation-error-msg="Please select from" data-key="1">
                                                <input type="hidden" name="search_data[1][origin]" value="Mumbai (BOM), India">
                                                <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                             </div>
                                          </li>
                                          <li class="col-lg-3 col-md-6 col-6  to">
                                             <div class="position-relative">
                                                <label class="form-label">To</label>
                                                <input type="text" class="form-control  tts__input__input tts__input__input1" placeholder="Destination" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport" data-key="1">
                                                <input type="hidden" name="search_data[1][destination]">
                                                <div class="flight_text_p"></div>
                                             </div>
                                          </li>
                                          <li class="col-lg-3 col-md-12 col-12 depart">
                                             <div class="position-relative">
                                                <label class="form-label"> <i class="fa-solid fa-calendar-days"></i> Depart
                                                   <i class="fa-solid fa-angle-down"></i></label>
                                                <input type="text" class="form-control tts__input__input tts-cursor-pointer tts__input__input1 tts__input__input2 " name="search_data[1][departdate]" placeholder="Depart Date" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" data-key="1" readonly>
                                                <div class="flight_text_p"></div>
                                             </div>
                                          </li>
                                          <li class="col-lg-1 col-md-3 col-12  btnarea search-flight">
                                             <button type="submit" class="oneway_btn oneway_search_btn btn" onclick="return checkFlightSearchValidation('flight-multi-form');"> Search
                                             </button>
                                             <button type="button" class="add-city btn" add-mult-city="true">Add City
                                             </button>
                                          </li>
                                       </ul>
                                    </div>
                                    <input type="hidden" name="preferred_carriers" id="PreferredCarriers">
                                    <input type="hidden" name="result_fare_type" resultFareType="true" value="RegularFare">
                                 </form>
                              </div>
                           </div>
                           <!-- <div class="makeflex">
                              <span>Select A Fare Type</span>
                              <div class="search_filters">
                                 <div class="form-check form-check-inline form-check-border"><input type="checkbox" name="Nonstop" id="direct_flight" class="form-check-input" check-direct-flight="true"><label for="direct_flight" class="form-check-label">Non-Stop</label></div>
                                 <span class="blackBorder"></span>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckbox6E" value="6E" airline-filter="true"><label class="form-check-label" for="inlineCheckbox6E">Indigo</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxUK" value="UK" airline-filter="true"><label class="form-check-label" for="inlineCheckboxUK">Vistara</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxAI" value="AI" airline-filter="true"><label class="form-check-label" for="inlineCheckboxAI">Air India</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxSG" value="SG" airline-filter="true"><label class="form-check-label" for="inlineCheckboxSG">Spice jet</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxI5" value="I5" airline-filter="true"><label class="form-check-label" for="inlineCheckboxI5">Air Asia</label></div>
                              </div>
                           </div> -->

                           <div class="makeflex mt-3">
                              <span>Select A Fare Type</span>
                              <div class="form-check form-check-inline form-check-border">
                                 <input type="checkbox" name="Nonstop" id="direct_flight" class="form-check-input" check-direct-flight="true">
                                 <label for="direct_flight" class="form-check-label">Non-Stop</label>
                              </div>
                              <?php $resultFareTypes = getResultFareType(); ?>
                              <div class="search_filters">
                                 <?php foreach ($resultFareTypes as $resultkey => $resultFareType) { ?>
                                    <div class="form-check form-check-inline">
                                       <input type="radio" class="form-check-input" name="resultFareType" value="<?php echo $resultkey; ?>" result-fare-type-filter="true" id="resultFareType<?php echo $resultkey; ?>" <?= ($resultkey == 'RegularFare') ? 'checked' : '' ?>>
                                       <label class="form-check-label" for="resultFareType<?php echo $resultkey; ?>"><?php echo $resultFareType; ?></label>
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                           <div class="makeflex mt-1">
                              <span>Preferred Carriers</span>
                              <div class="search_filters">
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckbox6E" value="6E" airline-filter="true"><label class="form-check-label" for="inlineCheckbox6E">Indigo</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxUK" value="UK" airline-filter="true"><label class="form-check-label" for="inlineCheckboxUK">Vistara</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxAI" value="AI" airline-filter="true"><label class="form-check-label" for="inlineCheckboxAI">Air India</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxSG" value="SG" airline-filter="true"><label class="form-check-label" for="inlineCheckboxSG">Spice jet</label></div>
                                 <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" id="inlineCheckboxI5" value="I5" airline-filter="true"><label class="form-check-label" for="inlineCheckboxI5">Air Asia</label></div>
                              </div>
                           </div>

                        </div>
                     <?php } ?>
                     <?php if (($controller == 'Hotel') && (whitelabel['hotel_module'] == 'active')) { ?>
                        <div id="hotel" class="tab-content  <?php echo active_tab("Hotel"); ?>">
                           <div class="flight-search d-flex align-items-center justify-content-between flex-wrap mb-2">
                              <h6 class="fw-medium mb-0">Book Domestic and International Hotels</h6>
                           </div>
                           <form action="<?php echo site_url('hotel/hotel-result') ?>" class="tts__form_wrapper" name="hotelform" type="get">
                              <ul class="row flight_search_border">
                                 <li class="col-lg-2 col-md-6 col-6 cityHotel">
                                    <div class="position-relative">
                                       <label class="form-label">Enter your Destination or Property</label>
                                       <input type="text" class="form-control tts__input__input" placeholder="CITY" value="Goa, India" data-validation="required" name="location" data-validation-error-msg="Please select city" tts-hotel-location="true">

                                       <input type="hidden" name="cityDom" cityDom="true" value="Goa_119805_IN" data-validation="required">
                                       <input type="hidden" name="room" hotel-total-selected-rooms="true" value="1" data-validation="required">
                                    </div>
                                 </li>
                                 <li class="col-lg-2 col-md-6 col-6 checkin">
                                    <div class="position-relative">
                                       <label class="form-label">Check-In</label>
                                       <input type="text" class="form-control tts__input__input" placeholder="Check-In" value="<?php $Date = date("Y/m/d");
                                                                                                                                 echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>" data-validation="required" data-validation-error-msg="Please select check in date" hotel-check-in-date="true" name="checkIn">
                                       <div class="flight_text_p">
                                          <?php echo date('l', strtotime($Date . ' + 1 days')); ?>
                                       </div>
                                    </div>
                                 </li>
                                 <li class="col-lg-2 col-md-12 col-12 checkout">
                                    <div class="position-relative">
                                       <label class="form-label">Check-Out</label>
                                       <input type="text" class="form-control tts__input__input" placeholder="Check-Out" data-validation="required" data-validation-error-msg="Please select check out date" value="<?php $Date = date("Y/m/d");
                                                                                                                                                                                                                     echo date(DateFormat, strtotime($Date . ' + 2 days')); ?>" hotel-check-out-date="true" name="checkOut">
                                       <div class="flight_text_p">
                                          <?php echo date('l', strtotime($Date . ' + 2 days')); ?>
                                       </div>
                                    </div>
                                 </li>
                                 <li class="col-lg-2 col-md-12 col-12 roomsGuests">
                                    <div class="position-relative tts__dropdown__wrapper">
                                       <label class="form-label">Rooms & Guests</label>
                                       <div class="tts__traveller_select form-select" id="select_hotel_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                          <span class="tts__traveller_select_tts_span" tts-hotel-guest-info="true">2</span> <span class="guest-text">Guest</span>
                                          <span class="tts__traveller_select_tts_span" tts-hotel-rooms-info="true">1</span> <span class="rooms-text">Rooms
                                          </span>
                                       </div>
                                       <div class="tts__dropdown__menu__right p-3 dropdown-menu" aria-labelledby="select_hotel_pax" hotel-room-dropdown="true" style="width:300px">
                                          <div class="row">
                                             <div class="col-12">
                                                <div class="tts__traveller__select__room">
                                                   <h5>Star Rating</h5>
                                                   <select class="form-select" name="rating">
                                                      <option value="0">Show All</option>
                                                      <option value="1">1 Star or less</option>
                                                      <option value="2">2 Star or less</option>
                                                      <option value="3">3 Star or less</option>
                                                      <option value="4">4 Star or less</option>
                                                      <option value="5">5 Star or less</option>
                                                      <option value="6">1 Star or More</option>
                                                      <option value="7" selected>2 Star or More</option>
                                                      <option value="8">3 Star or More</option>
                                                      <option value="9">4 Star or More</option>
                                                      <option value="10">5 Star or More</option>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row mt-3">
                                             <div class="col-12">
                                                <div class="tts__traveller__select__room">
                                                   <h5>Room 1</h5>
                                                   <div class="row ">
                                                      <div class="col-md-12 col-12 ">
                                                         <div class="tts__traveller__select d-flex align-items-center">
                                                            <h5>Adults(12y +)</h5>
                                                            <div class="GwMit border-0">
                                                               <select class="form-select tts__input__select__room" name="adult_1" onchange="get_hotel_adt(this)">
                                                                  <option value="1">1</option>
                                                                  <option value="2" selected>2</option>
                                                                  <option value="3">3</option>
                                                                  <option value="4">4</option>
                                                                  <option value="5">5</option>
                                                               </select>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 col-12 mt-3">
                                                         <div class="tts__traveller__select d-flex align-items-center">
                                                            <h5>Children <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-html="true" title="(Age 12y and below)"></i></h5>
                                                            <div class="GwMit border-0">
                                                               <select class="form-select tts__input__select__room" name="child_1" onchange="add_child_age('1',this.value);">
                                                                  <option value="0" selected>0</option>
                                                                  <option value="1">1</option>
                                                                  <option value="2">2</option>
                                                                  <option value="3">3</option>
                                                                  <option value="4">4</option>
                                                               </select>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 col-12 mt-3">
                                                         <div class="row" add-room-child-age-element-1="true">
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-12" append-extra-hotel-room="true">
                                             </div>
                                             <div class="col-12 mt-3 d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0);" class="tts__add__room" add-extra-hotel-room-event="true" onclick="add_room()">Add
                                                   Room</a>
                                                <a href="javascript:void(0);" class="tts__remove__room hide" remove-extra-hotel-room-event="true" onclick="remove_room()">Remove Room</a>
                                             </div>
                                          </div>
                                          <div class="row mt-3">
                                             <div class="col-sm-12">
                                                <button type="button" class="btn btn-outline-secondary tts__close_dropdown" hotel-room-dropdown-event="true">Done</button>

                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li class="col-lg-2 col-md-6 country">
                                    <div class="position-relative">
                                       <label class="form-label">Country</label>
                                       <?php $CountryCodes = gettingCountryCodeWithCountryName() ?>
                                       <select class="form-select tts__input__select tts__traveller_select" name="nationalitycode">
                                          <?php if ($CountryCodes) {
                                             foreach ($CountryCodes as $CountryCode) { ?>
                                                <option value="<?php echo $CountryCode['CountryCode']; ?>" <?php echo $CountryCode['CountryCode'] == "IN" ? "selected" : ""; ?>>
                                                   <?php echo $CountryCode['CountryName']; ?>
                                                </option>
                                             <?php }
                                          } else { ?>
                                             <option Value="IN">India</option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 </li>
                                 <li class="col-lg-2 col-md-6  col-12 btnarea">
                                    <button type="submit" class="oneway_btn oneway_search_btn btn" onclick="return checkHotelSearchValidation();">Search</button>
                                 </li>
                              </ul>
                           </form>
                        </div>
                     <?php } ?>

                    
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--------End Search Bar --------------->
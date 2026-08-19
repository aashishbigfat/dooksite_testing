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

?>

<?php } ?>

<!-- Modal -->

<div class="search-wrapper">
    <div id="flight" class="tab-content current">
        <div class="tab-pane fade show active ">
            <div class="flight-search">
                <div class="search-form">
                    <form action="<?php echo site_url("flight/search"); ?>" type="get" class="tts__form_wrapper" name="flight-form" flight-oneway-roundtrip-form="true">
                        <div class="flight-type">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="oneway" name="flightjtype" value="Oneway" onclick="checkflightJourneytype('Oneway')" <?php echo $searchData['journeytype'] == 'Oneway' ? "checked" : ""; ?>>
                                <label class="form-check-label" for="oneway">One Way</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="roundtrip" name="flightjtype" value="Roundtrip" onclick="checkflightJourneytype('Roundtrip')" <?php echo $searchData['journeytype'] == 'Roundtrip' ? "checked" : ""; ?>>
                                <label class="form-check-label" for="roundtrip">Round Way</label>
                            </div>

                        </div>
                        <div class="flight-search-wrapper">
                            <div class="flight-search-content">
                                <input type="hidden" value="<?php echo $searchData['journeytype']; ?>" name="journeytype">
                                <input type="hidden" class="form-check-input" name="direct_flight" value="<?php echo $searchData['direct_flight']; ?>">
                                <input type="hidden" name="preferred_carriers" value="<?php echo $searchData['preferred_carriers']; ?>" id="PreferredCarriers">
                                <input type="hidden" name="result_fare_type" resultFareType="true" value="<?= $searchData['result_fare_type'] ?>">
                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>From Airport</label>
                                            <div class="form-group-icon">
                                                <input type="text" class="form-control " placeholder="Origin" value="<?php echo $originCity; ?>" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from airport">
                                                <input type="hidden" name="origin" value="<?php echo $origin; ?>">
                                                <i class="fal fa-plane-departure"></i>
                                            </div>
                                            <div class="flight_text_p">[<?php echo  $originAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$originAirportCode]) ? $OriginDestinationAirportDetail[$originAirportCode] : ""; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="search-form-swap" alt="arrows" swape-city="true"><i class="far fa-repeat"></i> </div>
                                            <label>To Airport</label>
                                            <div class="form-group-icon">
                                                <input type="text" class="form-control  " name="destination" placeholder="Destination" value="<?php echo $destinationCity; ?>" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport">
                                                <input type="hidden" name="destination" value="<?php echo $destination; ?>">
                                                <i class="fal fa-plane-arrival"></i>
                                            </div>
                                            <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode]) ? $OriginDestinationAirportDetail[$destinationAirportCode] : ""; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="search-form-date">
                                                <div class="search-form-journey">
                                                    <label>Journey Date</label>
                                                    <div class="form-group-icon">
                                                        <input type="text" class="form-control  tts-cursor-pointer" name="departdate" placeholder="Depart Date" value="<?php echo $departdate; ?>" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" readonly>
                                                        <i class="fal fa-calendar-days"></i>
                                                    </div>
                                                    <div class="flight_text_p"><?php echo date('l', strtotime($departdate)); ?></div>
                                                </div>
                                                <div class="search-form-journey" onclick="selectroundtripDate('Roundtrip')">
                                                    <label>Return Date</label>
                                                    <input type="text" class="form-control  flight-return-date-disable tts-cursor-pointer" name="returndate" readonly placeholder="" value="<?php echo $returndate; ?>" data-validation="required" data-validation-error-msg="Please select return date" flight-return-date="true" readonly>
                                                    <div class="flight_text_p return-day-name"><?php if ($returndate) {
                                                                                                    echo date('l', strtotime($returndate));
                                                                                                } ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group dropdown passenger-box">
                                            <div class="passenger-class" id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                <label>Travellers & Class</label>
                                                <div class="form-group-icon">
                                                    <div class="passenger-total">
                                                        <span class="passenger-total-amount" data-total-pax="true"> <?php echo $searchData['adults'] + $searchData['child'] + $searchData['infant']; ?></span>
                                                        Passenger

                                                    </div>
                                                    <i class="fal fa-user-plus"></i>
                                                </div>
                                                <p class="passenger-class-name" flight-cabin-class="true"> <?php echo $searchData['cabinclass']; ?></p>
                                            </div>

                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="select_flight_pax">
                                                <div class="dropdown-item">
                                                    <div class="passenger-item">
                                                        <div class="passenger-info">
                                                            <h6>Adult</h6>
                                                            <p>12+ Years</p>
                                                        </div>
                                                        <div class="passenger-qty">
                                                            <button type="button" class="minus-btn" data-adult-pre="true"><i class="far fa-minus"></i></button>
                                                            <span class="qty-amount passenger-adult" data-adult-count="true"><?php echo $searchData['adults']; ?></span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="<?php echo $searchData['adults']; ?>" name="adults" adult-input="true">
                                                            <button type="button" class="plus-btn" data-adult-next="true"><i class="far fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="passenger-item">
                                                        <div class="passenger-info">
                                                            <h6>Children</h6>
                                                            <p>(2y - 12y)</p>
                                                        </div>
                                                        <div class="passenger-qty">
                                                            <button type="button" class="minus-btn" data-child-pre><i class="far fa-minus"></i></button>
                                                            <span class="qty-amount passenger-adult" data-child-count="true"><?php echo $searchData['child']; ?></span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="<?php echo $searchData['child']; ?>" name="child" child-input="true">
                                                            <button type="button" class="plus-btn" data-child-next><i class="far fa-plus"></i></button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="passenger-item">
                                                        <div class="passenger-info">
                                                            <h6>Infant <span class="tts__traveller__limit">(below 2y)</span></h6>
                                                        </div>
                                                        <div class="passenger-qty">
                                                            <button type="button" class="minus-btn" data-infant-pre><i class="far fa-minus"></i></button>
                                                            <span class="qty-amount passenger-adult" data-infant-count="true"><?php echo $searchData['infant']; ?></span>
                                                            <input class="form-control tts_traveller__counter" type="hidden" value="<?php echo $searchData['infant']; ?>" name="infant" infant-input="true">
                                                            <button type="button" class="plus-btn" data-infant-next><i class="far fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dropdown-item">
                                                    <h6 class="mb-3 mt-2">Cabin Class</h6>
                                                    <div class="passenger-class-info">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="any" value="Any" onclick="changeCabinclass('Any' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Any' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="any">Any </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="economy" value="Economy" onclick="changeCabinclass('Economy','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Economy' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="economy">Economy</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="business" value="Business" onclick="changeCabinclass('Business','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Business' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="business">Business</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="premiumEconomy" value="PremiumEconomy" onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumEconomy' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="premiumEconomy">Premium Economy</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="premiumBusiness" value="PremiumBusiness" onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumBusiness' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="premiumBusiness">Premium Business</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" name="cabinclass" id="First" value="First" onclick="changeCabinclass('First' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'First' ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="First">First</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit" class="theme-btn" onclick="return checkFlightSearchValidation('flight-form');"> Search <span>Flights</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
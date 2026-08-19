<div class="hotal-filter-card d-flex align-items-center justify-content-between mb-3 p-2">
    <div class="placeoneway-flights">
        <ul class="modify_list">
            <li class="">
                <h2><i class="fas fa-city"></i> <?php echo $searchData['location']; ?></h2>
                
            </li>
            <li class="">
                <h3 class="">Check-In <span class="d-block"><?php echo $searchData['checkIn']; ?></h3>
            </li>
            <li>
                <h3 class="">Nights <span class="d-block"><strong class=""><?php echo $nights = getDateDiffrence($searchData['checkIn'], $searchData['checkOut']); ?></strong></span></h3>
            </li>
            <li>
                <h3 class="">Check-Out<span class="d-block"><?php echo $searchData['checkOut']; ?></span></h3>
            </li>
            <li>
                <h3 class="">ROOMS & GUESTS<span class="d-block"><?php $Guest =  adult_child_count($searchData);
                                                                    echo   $Guest['total_p'] . " Guest"; ?>, <?php echo   $searchData['room'] . " Rooms"; ?></span></h3>
            </li>
        </ul>
    </div>
 <!--    <ul class="modify_list">
        <li class="">
            <h2><i class="fas fa-city"></i> <?php echo $searchData['location']; ?></h2>
        </li>
        <li class="">
            <h3 class="">Check-In <span class="d-block"><?php echo $searchData['checkIn']; ?></h3>
        </li>
        <li>
            <h3 class="">Nights <span class="d-block"><strong class=""><?php echo $nights = getDateDiffrence($searchData['checkIn'], $searchData['checkOut']); ?></strong></span></h3>
        </li>
        <li>
            <h3 class="">Check-Out<span class="d-block"><?php echo $searchData['checkOut']; ?></span></h3>
        </li>
        <li>
            <h3 class="">ROOMS & GUESTS<span class="d-block"><?php $Guest =  adult_child_count($searchData);
                                                                echo   $Guest['total_p'] . " Guest"; ?>, <?php echo   $searchData['room'] . " Rooms"; ?></span></h3>
        </li>
    </ul> -->

    <ul class="modify_list1">
       <!--  <li class="">
            <button type="button" class="btn btn-link border text-black" data-toggle="tooltip" data-placement="top" title="Share">
                <i class="fa fa-share-alt"></i>
            </button>
        </li> -->
        <li>
            <button type="button" class="btn btn-link border" data-bs-toggle="modal" data-bs-target="#staticBackdrop">modify search
                <!-- <span  class="text"></span>   -->
            </button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content modal_content">
                        <div class="modal-header modal_header">

                            <h5 class="modal-title">Book Domestic and International Hotels</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal_body">
                            <div class="">
                                <div id="hotel" class="tab-content  current">
                                    <div class="tts-col-12">
                                        <form action="<?php echo site_url('hotel/hotel-result') ?>" class="tts__form_wrapper" name="hotelform">
                                            <div class="row no-gutters flight_search_border align-items-center">
                                                <div class="col-sm-3">
                                                    <div class="position-relative">
                                                        <span class="tts__input__label">CITY</span>
                                                        <input type="text" class="form-control pt-3 tts__input__input" placeholder="CITY" value="<?php echo $searchData['location']; ?>" data-validation="required" name="location" data-validation-error-msg="Please select city" tts-hotel-location="true">
                                                        <input type="hidden" name="cityDom" cityDom="true" value="<?php echo $searchData['cityDom']; ?>" data-validation="required">
                                                        <input type="hidden" name="room" hotel-total-selected-rooms="true" data-validation="required" value="<?php echo $searchData['room']; ?>">
                                                        <!-- <input type="hidden" name="rating"  data-validation="required" value="<?php echo $searchData['rating']; ?>"> -->
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="position-relative">
                                                        <span class="tts__input__label">Check-In</span>
                                                        <input type="text" class="form-control pt-3 tts__input__input" placeholder="Check-In" value="<?php echo $searchData['checkIn']; ?>" data-validation="required" data-validation-error-msg="Please select check in date" hotel-check-in-date="true" name="checkIn">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="position-relative">
                                                        <span class="tts__input__label">Check-Out</span>
                                                        <input type="text" class="form-control pt-3 tts__input__input" placeholder="Check-Out" data-validation="required" data-validation-error-msg="Please select check out date" value="<?php echo $searchData['checkOut']; ?>" hotel-check-out-date="true" name="checkOut">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="position-relative tts__dropdown__wrapper">
                                                        <span class="tts__input__label">ROOMS & GUESTS</span>
                                                        <div class="pt-3 p-2 tts__traveller_select" id="select_hotel_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                            <span class="pl-1"><span class="tts__traveller_select_tts_span" tts-hotel-guest-info ="true"> <?php $Guest =  adult_child_count($searchData);
                                                                                                            echo   $Guest['total_p'] ; ?></span> Guest, <span class="tts__traveller_select_tts_span" tts-hotel-rooms-info = "true"><?php echo   $searchData['room']; ?> </span>Rooms</span>
                                                        </div>
                                                        <div class="tts__dropdown__menu__right p-3 dropdown-menu" aria-labelledby="select_hotel_pax" hotel-room-dropdown="true">
                                                        <div class  =  "row">
                                                            <div class  = "col-12">
                                                            <div class="tts__traveller__select__room my-2">
                                                                        <h6>Star Rating</h6>
                                                            <select  class  =  "form-control" name="rating">
                                                                            <option value="0" <?php echo  $searchData['rating'] == 0 ? "selected" : ""; ?>>Show All</option>
                                                                            <option value="1" <?php echo  $searchData['rating'] == 1 ? "selected" : ""; ?>>1 Star or less</option>
                                                                            <option value="2" <?php echo  $searchData['rating'] == 2 ? "selected" : ""; ?>>2 Star or less</option>
                                                                            <option value="3" <?php echo  $searchData['rating'] == 3 ? "selected" : ""; ?>>3 Star or less</option>
                                                                            <option value="4" <?php echo  $searchData['rating'] == 4 ? "selected" : ""; ?>>4 Star or less</option>
                                                                            <option value="5" <?php echo  $searchData['rating'] == 5 ? "selected" : ""; ?>>5 Star or less</option>
                                                                            <option value="6" <?php echo  $searchData['rating'] == 6 ? "selected" : ""; ?>>1 Star or More</option>
                                                                            <option value="7" <?php echo  $searchData['rating'] == 7 ? "selected" : ""; ?>>2 Star or More</option>
                                                                            <option value="8" <?php echo  $searchData['rating'] == 8 ? "selected" : ""; ?>>3 Star or More</option>
                                                                            <option value="9"  <?php echo  $searchData['rating'] == 9 ? "selected" : ""; ?>>4 Star or More</option>
                                                                            <option value="10" <?php echo  $searchData['rating'] == 10 ? "selected" : ""; ?>>5 Star or More</option>
                                                                        </select>
                                                            </div>  
                                                            </div>  
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="tts__traveller__select__room my-2">
                                                                        <h6>Room 1</h6>
                                                                        <div class="row px-2">
                                                                            <div class="col-6 px-2">
                                                                                <span>Adults(12y +)</span>
                                                                                <select class="form-control tts__input__select__room" name="adult_1" onchange="get_hotel_adt(this)">
                                                                                    <option value="1" <?php echo  $searchData['adult_1'] == 1 ? "selected" : ""; ?>>1</option>
                                                                                    <option value="2" <?php echo  $searchData['adult_1'] == 2 ? "selected" : ""; ?>>2</option>
                                                                                    <option value="3" <?php echo  $searchData['adult_1'] == 3 ? "selected" : ""; ?>>3</option>
                                                                                    <option value="4" <?php echo  $searchData['adult_1'] == 4 ? "selected" : ""; ?>>4</option>
                                                                                    <option value="5" <?php echo  $searchData['adult_1'] == 5 ? "selected" : ""; ?>>5</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 px-2">
                                                                                <span>Children <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-html="true" title="(Age 12y and below)"></i></span>
                                                                                <select class="form-control tts__input__select__room" name="child_1" onchange="add_child_age('1',this.value);">
                                                                                    <option value="0" <?php echo  $searchData['child_1'] == 0 ? "selected" : ""; ?>>0</option>
                                                                                    <option value="1" <?php echo  $searchData['child_1'] == 1 ? "selected" : ""; ?>>1</option>
                                                                                    <option value="2" <?php echo  $searchData['child_1'] == 2 ? "selected" : ""; ?>>2</option>
                                                                                    <option value="3" <?php echo  $searchData['child_1'] == 3 ? "selected" : ""; ?>>3</option>
                                                                                    <option value="4" <?php echo  $searchData['child_1'] == 4 ? "selected" : ""; ?>>4</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-12 px-2">
                                                                                <div class="row" add-room-child-age-element-1="true">
                                                                                    <?php if ($searchData['child_1'] > 0) { ?>
                                                                                        <?php for ($childcount = 1; $childcount <= $searchData['child_1']; $childcount++) { ?>
                                                                                            <div class="col-6">
                                                                                                <span>Age</span>
                                                                                                <select class="form-control tts__input__select__room" name="age_1_<?php echo  $childcount; ?>">
                                                                                                    <?php for ($childAge = 1; $childAge <= 12; $childAge++) { ?>
                                                                                                        <option value="<?php echo  $childAge;  ?>" <?php echo  $searchData['age_1_' . $childcount] == $childAge ? "selected" : ""; ?>><?php echo  $childAge;  ?></option>
                                                                                                    <?php }  ?>
                                                                                                </select>
                                                                                            </div>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" append-extra-hotel-room="true">
                                                                    <?php if ($searchData['room'] > 1) {
                                                                        for ($rooms = 2; $rooms <= $searchData['room']; $rooms++) { ?>
                                                                            <div class="tts__traveller__select__room my-2" remove-extra-hotel-room-<?php echo $rooms;  ?>="true">
                                                                                <h6>Room <?php echo $rooms;  ?></h6>
                                                                                <div class="row px-2">
                                                                                    <div class="col-6 px-2">
                                                                                        <span>Adults(12y +)</span>
                                                                                        <select class="form-control tts__input__select__room" name="adult_<?php echo $rooms;  ?>" onchange="get_hotel_adt(this)">
                                                                                            <option value="1" <?php echo  $searchData['adult_' . $rooms] == 1 ? "selected" : ""; ?>>1</option>
                                                                                            <option value="2" <?php echo  $searchData['adult_' . $rooms] == 2 ? "selected" : ""; ?>>2</option>
                                                                                            <option value="3" <?php echo  $searchData['adult_' . $rooms] == 3 ? "selected" : ""; ?>>3</option>
                                                                                            <option value="4" <?php echo  $searchData['adult_' . $rooms] == 4 ? "selected" : ""; ?>>4</option>
                                                                                            <option value="5" <?php echo  $searchData['adult_' . $rooms] == 5 ? "selected" : ""; ?>>5</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-6 px-2">
                                                                                        <span>Children <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-html="true" title="(Age 12y and below)"></i></span>
                                                                                        <select class="form-control tts__input__select__room" name="child_<?php echo $rooms;  ?>" onchange="add_child_age('<?php echo $rooms;  ?>',this.value);">
                                                                                            <option value="0" <?php echo  $searchData['child_' . $rooms] == 0 ? "selected" : ""; ?>>0</option>
                                                                                            <option value="1" <?php echo  $searchData['child_' . $rooms] == 1 ? "selected" : ""; ?>>1</option>
                                                                                            <option value="2" <?php echo  $searchData['child_' . $rooms] == 2 ? "selected" : ""; ?>>2</option>
                                                                                            <option value="3" <?php echo  $searchData['child_' . $rooms] == 3 ? "selected" : ""; ?>>3</option>
                                                                                            <option value="4" <?php echo  $searchData['child_' . $rooms] == 4 ? "selected" : ""; ?>>4</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-12 px-2">
                                                                                        <div class="row" add-room-child-age-element-<?php echo $rooms;  ?>="true">
                                                                                            <?php if ($searchData['child_' . $rooms] > 0) { ?>
                                                                                                <?php for ($childcount = 1; $childcount <= $searchData['child_' . $rooms]; $childcount++) { ?>
                                                                                                    <div class="col-6">
                                                                                                        <span>Age</span>
                                                                                                        <select class="form-control tts__input__select__room" name="age_<?php echo $rooms . '_' . $childcount;  ?>">
                                                                                                            <?php for ($childAge = 1; $childAge <= 12; $childAge++) { ?>
                                                                                                                <option value="<?php echo  $childAge;  ?>" <?php echo  $searchData['age_' . $rooms . '_' . $childcount] == $childAge ? "selected" : ""; ?>><?php echo  $childAge;  ?></option>
                                                                                                            <?php }  ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                            <?php }
                                                                                            } ?>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                </div>
                                                                <div class="col-12">
                                                                    <a href="javascript:void(0);" class="tts__add__room <?php echo $searchData['room'] < 5 ? "" : "hide" ?>" add-extra-hotel-room-event="true" onclick="add_room()">add room</a>
                                                                    <a href="javascript:void(0);" class="tts__add__room <?php echo $searchData['room'] > 1 ? "" : "hide" ?>" remove-extra-hotel-room-event="true" onclick="remove_room()">remove room</a>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 ml-auto">
                                                                    <div class="d-flex justify-content-end">
                                                                        <button type="button" class="oneway_btn btn px-2 py-0 tts__close_dropdown" hotel-room-dropdown-event="true">done
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 p0">
                                                    <div class="position-relative">
                                                        <span class="tts__input__label">Country</span>
                                                        <?php $CountryCodes    = gettingCountryCodeWithCountryName() ?>
                                                        <select class="form-control tts__input__select" name="nationalitycode">
                                                            <?php if ($CountryCodes) {
                                                                foreach ($CountryCodes as  $CountryCode) { ?>
                                                                    <option value="<?php echo $CountryCode['CountryCode']; ?>" <?php echo $CountryCode['CountryCode'] == $searchData["nationalitycode"] ? "selected" : ""; ?>><?php echo $CountryCode['CountryName']; ?></option>
                                                                <?php }
                                                            } else { ?>
                                                                <option Value="IN">India</option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1 p0">
                                                    <button type="submit" class="oneway_btn btn" onclick="return checkHotelSearchValidation();">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </li>
    </ul>
</div>
<!-- <div class="hotal-filter-card p-2 mb-3">
   <ul class="modify_list row">
      <li class="col-md-2 col-4 d-none d-lg-block">
         <h5><i class="fas fa-city"></i> <?php echo $searchData['location']; ?></h5>
      </li>
      <li class="col-md-2 col-4 d-none d-lg-block">
         <h5>Check-In <span class="d-block"><?php echo $searchData['checkIn']; ?></h5>
      </li>
      <li class="col-md-2 col-4 d-none d-lg-block">
         <h5>Nights <span class="d-block"><strong><?php echo $nights = getDateDiffrence($searchData['checkIn'], $searchData['checkOut']); ?></strong></span></h5>
      </li>
      <li class="col-md-2 col-6 d-none d-lg-block">
         <h5>Check-Out<span class="d-block"><?php echo $searchData['checkOut']; ?></span></h5>
      </li>
      <li class="col-md-2 col-6 mb-0 mb-lg-0 d-none d-lg-block">
         <h5>Rooms & Guests<span class="d-block"><?php $Guest =  adult_child_count($searchData);
                                                   echo   $Guest['total_p'] . " Guest"; ?>, <?php echo   $searchData['room'] . " Rooms"; ?></span></h5>
      </li>
      <li class="col-md-2 col-12 d-none d-md-block button d-none d-lg-block">
         <button type="button" class="btn modifysearch" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Modify Search
         </button>
      </li>
   </ul>
   <div class="modify-search-xs text-center d-block d-lg-none">
      <h3><i class="fa-solid fa-filter" ng-click="showHideMobileFilter('show')"></i> <?php echo $searchData['location']; ?> <i class="fa-solid fa-edit right-icon" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></i></h3>
      <span>Check In</span> <b><?php echo $searchData['checkIn']; ?></b> | <span>Check-Out</span> <b> <?php echo $searchData['checkOut']; ?></b></br>
      <span>Nights</span> <b><?php echo $nights = getDateDiffrence($searchData['checkIn'], $searchData['checkOut']); ?></b> | <span>Rooms & Guests</span> <b> <?php echo   $searchData['room'] . " Rooms"; ?>, <?php $Guest =  adult_child_count($searchData);
                                                                                                                                                                                                               echo   $Guest['total_p'] . " Guest"; ?></b>
   </div>
</div> -->
<!-- Modal -->

<div class="card">
   <div class="card-body">
      <div class="banner-form">
         <form action="<?php echo site_url('hotel/hotel-result') ?>" class="tts__form_wrapper" name="hotelform">
            <div class="d-lg-flex">
               <div class="d-flex form-info">
                  <div class="form-item">
                     <label class="form-label">City</label>
                     <input type="text" class="form-control tts__input__input" placeholder="CITY" value="<?php echo $searchData['location']; ?>" data-validation="required" name="location" data-validation-error-msg="Please select city" tts-hotel-location="true">
                     <input type="hidden" name="cityDom" cityDom="true" value="<?php echo $searchData['cityDom']; ?>" data-validation="required">
                     <input type="hidden" name="room" hotel-total-selected-rooms="true" data-validation="required" value="<?php echo $searchData['room']; ?>">
                     <!-- <input type="hidden" name="rating"  data-validation="required" value="<?php echo $searchData['rating']; ?>"> -->
                  </div>
                  <div class="form-item">
                     <label class="form-label">Check-In</label>
                     <input type="text" class="form-control tts__input__input" placeholder="Check-In" value="<?php echo $searchData['checkIn']; ?>" data-validation="required" data-validation-error-msg="Please select check in date" hotel-check-in-date="true" name="checkIn">
                     <div class="flight_text_p">Friday</div>
                  </div>
                  <div class="form-item">
                     <label class="form-label">Check-Out</label>
                     <input type="text" class="form-control tts__input__input" placeholder="Check-Out" data-validation="required" data-validation-error-msg="Please select check out date" value="<?php echo $searchData['checkOut']; ?>" hotel-check-out-date="true" name="checkOut">
                     <div class="flight_text_p">Saturday</div>
                  </div>
                  <div class="form-item dropdown">
                     <div id="select_hotel_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        <label class="form-label">Rooms & Guest</label>
                        <h5>
                           <span tts-hotel-guest-info="true"><?php $Guest = adult_child_count($searchData);
                                                               echo   $Guest['total_p']; ?></span>
                           <span class="fw-normal">Guest</span>
                           <span tts-hotel-rooms-info="true"><?php echo   $searchData['room']; ?> </span>
                           <span class="fw-normal">Rooms</span>
                        </h5>
                     </div>
                     <div class="dropdown-menu dropdown-menu-end dropdown-xl" aria-labelledby="select_hotel_pax" hotel-room-dropdown="true">
                        <div class="mb-3 border info-item">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Star Rating</label>
                                    <select name="rating">
                                       <option value="0" <?php echo  $searchData['rating'] == 0 ? "selected" : ""; ?>>Show All</option>
                                       <option value="1" <?php echo  $searchData['rating'] == 1 ? "selected" : ""; ?>>1 Star or less</option>
                                       <option value="2" <?php echo  $searchData['rating'] == 2 ? "selected" : ""; ?>>2 Star or less</option>
                                       <option value="3" <?php echo  $searchData['rating'] == 3 ? "selected" : ""; ?>>3 Star or less</option>
                                       <option value="4" <?php echo  $searchData['rating'] == 4 ? "selected" : ""; ?>>4 Star or less</option>
                                       <option value="5" <?php echo  $searchData['rating'] == 5 ? "selected" : ""; ?>>5 Star or less</option>
                                       <option value="6" <?php echo  $searchData['rating'] == 6 ? "selected" : ""; ?>>1 Star or More</option>
                                       <option value="7" <?php echo  $searchData['rating'] == 7 ? "selected" : ""; ?>>2 Star or More</option>
                                       <option value="8" <?php echo  $searchData['rating'] == 8 ? "selected" : ""; ?>>3 Star or More</option>
                                       <option value="9" <?php echo  $searchData['rating'] == 9 ? "selected" : ""; ?>>4 Star or More</option>
                                       <option value="10" <?php echo  $searchData['rating'] == 10 ? "selected" : ""; ?>>5 Star or More</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!---Room 1-->
                        <div class="mb-3 border info-item">
                           <h6 class="fw-bold mb-3">Room 1</h6>
                           <div class="row">
                              <div class="col-md-12 col-12 ">
                                 <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <label class="form-label text-gray-9">Adults(12y +)</label>
                                    <div class="custom-increment">
                                       <select name="adult_1" onchange="get_hotel_adt(this)">
                                          <option value="1" <?php echo  $searchData['adult_1'] == 1 ? "selected" : ""; ?>>1</option>
                                          <option value="2" <?php echo  $searchData['adult_1'] == 2 ? "selected" : ""; ?>>2</option>
                                          <option value="3" <?php echo  $searchData['adult_1'] == 3 ? "selected" : ""; ?>>3</option>
                                          <option value="4" <?php echo  $searchData['adult_1'] == 4 ? "selected" : ""; ?>>4</option>
                                          <option value="5" <?php echo  $searchData['adult_1'] == 5 ? "selected" : ""; ?>>5</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 col-12">
                                 <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label text-gray-9">Children (Age 12y and below)</label>
                                    <div class="custom-increment">
                                       <select name="child_1" onchange="add_child_age('1',this.value);">
                                          <option value="0" <?php echo  $searchData['child_1'] == 0 ? "selected" : ""; ?>>0</option>
                                          <option value="1" <?php echo  $searchData['child_1'] == 1 ? "selected" : ""; ?>>1</option>
                                          <option value="2" <?php echo  $searchData['child_1'] == 2 ? "selected" : ""; ?>>2</option>
                                          <option value="3" <?php echo  $searchData['child_1'] == 3 ? "selected" : ""; ?>>3</option>
                                          <option value="4" <?php echo  $searchData['child_1'] == 4 ? "selected" : ""; ?>>4</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 col-12">
                                 <div class="row" add-room-child-age-element-1="true">

                                    <?php if ($searchData['child_1'] > 0) { ?>
                                       <?php for ($childcount = 1; $childcount <= $searchData['child_1']; $childcount++) { ?>
                                          <div class="col-3">
                                             <div class="mt-3 d-flex align-items-center justify-content-between">
                                                <label class="form-label text-gray-9">Age</label>
                                                <div class="custom-increment">
                                                   <select name="age_1_<?php echo  $childcount; ?>">
                                                      <?php for ($childAge = 1; $childAge <= 12; $childAge++) { ?>
                                                         <option value="<?php echo  $childAge;  ?>" <?php echo  $searchData['age_1_' . $childcount] == $childAge ? "selected" : ""; ?>><?php echo  $childAge;  ?></option>
                                                      <?php }  ?>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                    <?php }
                                    } ?>

                                 </div>
                              </div>
                           </div>
                        </div>
                        <!---Room 2-->
                        <div append-extra-hotel-room="true">
                           <?php if ($searchData['room'] > 1) {
                              for ($rooms = 2; $rooms <= $searchData['room']; $rooms++) { ?>
                                 <div class="mb-3 border info-item tts__traveller__select__room" remove-extra-hotel-room-<?php echo $rooms;  ?>="true">
                                    <h6 class="fw-bold mb-3">Room <?php echo $rooms;  ?></h6>
                                    <div class="row">
                                       <div class="col-md-12 col-12">
                                          <div class="mb-3 d-flex align-items-center justify-content-between">
                                             <label class="form-label text-gray-9">Adults(12y +)</label>
                                             <div class="custom-increment">
                                                <select name="adult_<?php echo $rooms;  ?>" onchange="get_hotel_adt(this)">
                                                   <option value="1" <?php echo  $searchData['adult_' . $rooms] == 1 ? "selected" : ""; ?>>1</option>
                                                   <option value="2" <?php echo  $searchData['adult_' . $rooms] == 2 ? "selected" : ""; ?>>2</option>
                                                   <option value="3" <?php echo  $searchData['adult_' . $rooms] == 3 ? "selected" : ""; ?>>3</option>
                                                   <option value="4" <?php echo  $searchData['adult_' . $rooms] == 4 ? "selected" : ""; ?>>4</option>
                                                   <option value="5" <?php echo  $searchData['adult_' . $rooms] == 5 ? "selected" : ""; ?>>5</option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-12 col-12">
                                          <div class="d-flex align-items-center justify-content-between">
                                             <label class="form-label text-gray-9">Children (Age 12y and below)</label>
                                             <div class="custom-increment">
                                                <select name="child_<?php echo $rooms;  ?>" onchange="add_child_age('<?php echo $rooms;  ?>',this.value);">
                                                   <option value="0" <?php echo  $searchData['child_' . $rooms] == 0 ? "selected" : ""; ?>>0</option>
                                                   <option value="1" <?php echo  $searchData['child_' . $rooms] == 1 ? "selected" : ""; ?>>1</option>
                                                   <option value="2" <?php echo  $searchData['child_' . $rooms] == 2 ? "selected" : ""; ?>>2</option>
                                                   <option value="3" <?php echo  $searchData['child_' . $rooms] == 3 ? "selected" : ""; ?>>3</option>
                                                   <option value="4" <?php echo  $searchData['child_' . $rooms] == 4 ? "selected" : ""; ?>>4</option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-12 col-12">
                                          <div class="row" add-room-child-age-element-<?php echo $rooms;  ?>="true">
                                             <?php if ($searchData['child_' . $rooms] > 0) { ?>
                                                <?php for ($childcount = 1; $childcount <= $searchData['child_' . $rooms]; $childcount++) { ?>
                                                   <div class="col-3">
                                                      <div class="mt-3 d-flex align-items-center justify-content-between">
                                                         <label class="form-label text-gray-9">Age</label>
                                                         <div class="custom-increment">
                                                            <select name="age_<?php echo $rooms . '_' . $childcount;  ?>">
                                                               <?php for ($childAge = 1; $childAge <= 12; $childAge++) { ?>
                                                                  <option value="<?php echo  $childAge;  ?>" <?php echo  $searchData['age_' . $rooms . '_' . $childcount] == $childAge ? "selected" : ""; ?>><?php echo  $childAge;  ?></option>
                                                               <?php }  ?>
                                                            </select>
                                                         </div>
                                                      </div>
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
                         <!---Button-->
                        <div class="d-flex justify-content-between">
                           <button type="button" class="btn btn-danger btn-sm shadow-none rounded-pill tts__close_dropdown" hotel-room-dropdown-event="true">Done</button>
                           <div>
                              <a href="javascript:void(0);" class="rounded-pill btn btn-success btn-sm shadow-none me-2 tts__add__room <?php echo $searchData['room'] < 5 ? "" : "hide" ?>" add-extra-hotel-room-event="true" onclick="add_room()">Add room</a>
                              <a href="javascript:void(0);" class="rounded-pill btn btn-danger btn-sm shadow-none tts__add__room <?php echo $searchData['room'] > 1 ? "" : "hide" ?>" remove-extra-hotel-room-event="true" onclick="remove_room()">Remove Room</a>
                           </div>
                        </div>
                        
                     </div>
                  </div>
                  <div class="form-item">
                     <label class="form-label">Country</label>
                     <?php $CountryCodes    = gettingCountryCodeWithCountryName() ?>
                     <select class="tts__traveller_select form-select form-control" name="nationalitycode">
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
               <button type="submit" class="btn btn-primary search-btn rounded" onclick="return checkHotelSearchValidation();">Search</button>
            </div>
         </form>
      </div>
   </div>
</div>
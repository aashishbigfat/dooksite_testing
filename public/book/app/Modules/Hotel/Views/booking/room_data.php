<div ng-if="HotelRoomErrorCode==0">
   <div class="d-flex align-items-center justify-content-between d-none d-md-flex">
      <h5>Rooms :</h5>
   </div>
</div>
<div ng-if="HotelRoomErrorCode!=0">
   <div class="text-center">
      <div class="loader mt-3 mb-3">
         <div role="status" class="spinner-grow text-primary">
            <span class="sr-only">Loading...</span>
         </div>
         <div role="status" class="spinner-grow text-secondary">
            <span class="sr-only">Loading...</span>
         </div>
         <div role="status" class="spinner-grow text-danger">
            <span class="sr-only">Loading...</span>
         </div>
         <div role="status" class="spinner-grow text-dark">
            <span class="sr-only">Loading...</span>
         </div>
      </div>
      <h5> Fetching Room Please Wait... </h5>
   </div>
</div>

<?php if(0){ ?>

<div ng-if="InfoSourceval=='FixedCombination'">
   <div ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,RoomData) in HotelRoomData.slice(1)">
      <div class="row align-items-center">
         <div class="col-md-12" ng-repeat="(key1,Rooms) in RoomData">
            <div class="card shadow-none">
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-9">
                        <h5 class="card-title">{{Rooms.RoomTypeName}}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                           <a href="javascript:void(0);" class="room-link" ng-click="RoomDescription(Rooms)">Room Information</a>
                           <a href="javascript:void(0);" class="room-link text-muted ng-binding">Cancellation Date {{Rooms.LastCancellationDate| date:'MMM d, y'}}</a>
                           <div ng-if="Rooms.Inclusion.length!=0">
                              <span ng-repeat="(key,item) in Rooms.Inclusion"><i class="fa fa-check text-success me-1"></i><span> {{item}}</span> </span>
                           </div>

                        </div>
                        <div>
                           <span class="text-danger d-block" ng-if="Rooms.IsPANMandatory==true">Pan Card Applicable</span>
                           <span class="text-danger d-block" ng-if="Rooms.IsPassportMandatory==true">Passport Applicable</span>
                        </div>
                     </div>
                     <div class="col-lg-3">
                        <div class="room-price text-end">
                           <p class="fs-13 fw-medium mb-0">Starts From</p>
                           <h5 class="text-dark ng-binding"><span class="ng-binding">{{CurrencySymbol}}</span> {{HotelRoomPriceData[key]}}</h5>
                           <a class="btn btn-primary btn-sm" href="javascript:void(0);" ng-click="ContinueRoom(RoomData,'fixed')"> Book Now </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div ng-if="InfoSourceval=='OpenCombination'">
   <div class="hotal-filter-card my-3 p-3" ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,RoomData) in HotelRoomData.slice(1)">
      <div class="row">
         <div class="col-md-12">
            <h4> Room {{key+1}}</h4>
         </div>
      </div>
      <div class="row" ng-repeat="(key1,Rooms) in RoomData">
         <div class="col-md-9">
            <div class="position-relative">
               <h6>
                  {{Rooms.RoomTypeName}}
                  <span class="d-block"><a href="javascript:void(0);" class="fs-12" ng-click="RoomDescription(Rooms)">Room Information</a></span>
               </h6>
               <div ng-if="Rooms.Inclusion.length!=0">
                  <div class="mb-2" ng-repeat="(key,item) in Rooms.Inclusion">
                     <i class="fa fa-check text-success"></i>
                     <span> {{item}}</span>
                  </div>
               </div>
               <!--   <span class="text-danger">
                  Cancellation Date {{Rooms.LastCancellationDate| date:'MMM d, y'}}
                  </span><br /> -->
               <span class="text-danger" ng-if="Rooms.IsPANMandatory==true">
                  PAN CARD APPLICABLE
               </span><br />
               <span class="text-danger" ng-if="Rooms.IsPassportMandatory==true">
                  Passport APPLICABLE
               </span>
            </div>
         </div>
         <div class="col-md-3 text-end">
            <div class="position-relative">
               <div class="d-flex align-items-center justify-content-end">
                  <h5 class="me-2 m-0"><span>{{CurrencySymbol}}</span> {{HotelRoomPriceData[key][key1]}}</h5>
                  <a href="javascript:void(0);">
                     <span class="badge badge_bg">
                        <input type="radio" name="room{{key+1}}" ng-if="key1==0" ng-click="openCombinationEvent(Rooms,key)" checked>
                        <input type="radio" name="room{{key+1}}" ng-if="key1!=0" ng-click="openCombinationEvent(Rooms,key)">
                     </span>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php } ?>



<div ng-if="InfoSourceval=='FixedCombination'">
   <div class="hotal-filter-card my-3 p-3" ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,RoomData) in HotelRoomData">
      <div class="row align-items-center">
         <div class="col-md-9" ng-repeat="(key1,Rooms) in RoomData">
            <div class="row">
               <div class="col-md-8">
                  <h6 class="m-0">{{Rooms.RoomTypeName}}</h6>
                  <a href="javascript:void(0);" class="fs-12" ng-click="RoomDescription(Rooms)">Room Information</a>
               </div>
               <div class="col-md-4">
                  <div ng-if="Rooms.Inclusion.length!=0">
                     <div ng-repeat="(key,item) in Rooms.Inclusion">
                        <i class="fa fa-check text-success"></i>
                        <span> {{item}}</span>
                     </div>
                  </div>
                  <span class="text-danger d-block" ng-if="Rooms.IsPANMandatory==true">Pan Card Applicable</span>
                  <span class="text-danger d-block" ng-if="Rooms.IsPassportMandatory==true">Passport Applicable</span>
               </div>

               <!--   <span class="text-danger">
                  Cancellation Date {{Rooms.LastCancellationDate| date:'MMM d, y'}}
                  </span><br /> -->

            </div>
         </div>
         <div class="col-md-3 text-end">
            <div class="position-relative">
               <div class="d-flex align-items-center justify-content-end">
                  <h5 class="me-2 m-0"> {{CurrencySymbol}} {{HotelRoomPriceData[key]}}</h5>
                  <a class="btn btn-sm badge_bg" href="javascript:void(0);" ng-click="ContinueRoom(RoomData,'fixed')"> Book Now </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div ng-if="InfoSourceval=='OpenCombination'">
    <div class="hotal-filter-card my-3 p-3" ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,RoomData) in HotelRoomData">
        <div class="row">
            <div class="col-md-12">
                <h4> Room {{key+1}}</h4>
            </div>
        </div>
        <div class="row" ng-repeat="(key1,Rooms) in RoomData">
            <div class="col-md-9">
                <div class="position-relative">
                    <h6>
                        {{Rooms.RoomTypeName}}
                        <span class="d-block"><a href="javascript:void(0);" class="fs-12" ng-click="RoomDescription(Rooms)">Room Information</a></span>
                    </h6>
                    <div ng-if="Rooms.Inclusion.length!=0">
                        <div class="mb-2" ng-repeat="(key,item) in Rooms.Inclusion">
                            <i class="fa fa-check text-success"></i>
                            <span> {{item}}</span>
                        </div>
                    </div>
                    <!--   <span class="text-danger">
                  Cancellation Date {{Rooms.LastCancellationDate| date:'MMM d, y'}}
                  </span><br /> -->
                    <span class="text-danger" ng-if="Rooms.IsPANMandatory==true">
                        PAN CARD APPLICABLE
                    </span><br />
                    <span class="text-danger" ng-if="Rooms.IsPassportMandatory==true">
                        Passport APPLICABLE
                    </span>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <div class="position-relative">
                    <div class="d-flex align-items-center justify-content-end">
                        <h5 class="me-2 m-0"><span> {{CurrencySymbol}} </span> {{HotelRoomPriceData[key][key1]}}</h5>
                        <a href="javascript:void(0);">
                            <span class="badge badge_bg">
                                <input type="radio" name="room{{key+1}}" ng-if="key1==0" ng-click="openCombinationEvent(Rooms,key)" checked>
                                <input type="radio" name="room{{key+1}}" ng-if="key1!=0" ng-click="openCombinationEvent(Rooms,key)">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--Room Description -->
<div class="modal fade" id="hotel_room_description" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style="width: 100% !important;">
         <div class="modal-header" style="background-color: unset;color: unset;border-radius: unset;">
            <h5 class="modal-title" id="hotel_room_description">Room Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body pt-0">
            <div class="row">
               <div class="col-md-12">
                  <h5>Description</h5>
                  <p ng-bind-html="hotelroomselect['RoomDescription']| safeHtml"></p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <h5>Amenities</h5>
                  <ul class="amenities-hotel-list">
                     <li ng-repeat="item in hotelroomselect['Amenity']">{{item}}</li>
                  </ul>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <h5>Cancellation Policy</h5>
                  <p>{{hotelroomselect['CancellationPolicy']}}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
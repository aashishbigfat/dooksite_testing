<!---------------flight--list-----end---------->
<section class="flight_list_asp-btm" ng-if="loading==false && HotelRoomErrorCode==0 && InfoSourceval=='OpenCombination'">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 d-flex align-items-center justify-content-between"
                ng-repeat="(roomkey,room) in openCombinationData">
                <div class="ars-listair d-flex align-items-center">
                            <div class="asp-totalam select-flightsname"> {{room.RoomTypeName}}<span
                                    class="bottom-gridcode ">{{room.RatePlanName}}</span></div>
                </div>
              
                <div>
                    <h5 class="whitecolor">{{room['CurrencySymbol']}} {{room['TotalPrice']}}</h5>
                </div>
            </div>
         
            <div class="col-sm-4 d-flex align-items-center justify-content-end">
                <ul class="ars-list">
                    <!-- <li>
                        <h3 class="mb-0">₹
                            {{(selectedOnwardFlight['FareInfo']['Fare']['PublishedPrice']+selectedReturnFlight['FareInfo']['Fare']['PublishedPrice']).toFixed(2)}}
                        </h3>
                    </li> -->
                    <li>
                        <button type="button" class="btn round_trip_book"
                        ng-click="ContinueRoom(openCombinationData,'open')">BOOK</button>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</section>
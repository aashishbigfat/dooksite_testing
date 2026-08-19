<div class="row" ng-if="HotelRoomErrorCode==0">
    <div class="col-md-12">
        <div class="d-flex align-items-center justify-content-between border-top border-bottom d-none d-md-flex">
            <h5 class="">Rooms :</h5>
        </div>
    </div>
</div>
<div class="row" ng-if="HotelRoomErrorCode!=0">
    <div class="col-md-12">
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
            <h5>Fetching Room Please Wait...</h5>
        </div>
    </div>
</div>
<div ng-if="InfoSourceval=='FixedCombination'">
    <div class="hotal-filter-card my-3 p-2" ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,RoomData) in HotelRoomData">
        <div class="row">
            <div class="col-md-5" ng-repeat="(key1,Rooms) in RoomData">
                <div class="position-relative">
                    <h5 class="m-0 fs_16">{{Rooms.RoomTypeName}}</h5>
                    <p class="mb-0"><a href="javascript:void(0);" class="fs-12" ng-click="RoomDescription(Rooms)">Room Information</a></p>
                    <span class="text-danger" ng-if="Rooms.IsPANMandatory==true">PAN CARD APPLICABLE ,</span>
                    <span class="text-danger" ng-if="Rooms.IsPassportMandatory==true">Passport APPLICABLE </span>
                </div>
            </div>

            <div class="col-md-3" ng-repeat="(key1,Rooms) in RoomData">
                <div class="position-relative">
                    <div ng-if="Rooms.Inclusion.length!=0">
                        <div class="mb-0" ng-repeat="(key,item) in Rooms.Inclusion">
                            <span ng-if="key==0">
                                <i class="fa fa-check text-success"></i>
                                <span> {{item}}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-end">
                <div class="position-relative">
                    <div class="d-flex align-items-center justify-content-end">
                        <h5 class="me-2">₹ {{HotelRoomPriceData[key]}}</h5>
                        <a href="javascript:void(0);" ng-click="ContinueRoom(RoomData,'fixed')">
                            <span class="badge badge_bg">
                                Book Now
                            </span>
                        </a>
                    </div>
                    <div>
                        <a href="javascript:void(0);" class="fs-12 text-danger" ng-click="cancellation_policy(RoomData)">
                            Free cancellation till: {{RoomData[0].LastCancellationDate| date:'dd-MMM-y'}}
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m0" id="hotel_room_description">Room Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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

<!-- Modal -->
<div class="modal fade" id="cancellation_policy_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex align-items-center justify-content-between mb-3 float-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Cancelled on or After</th>
                            <th scope="col">Cancelled on or Before</th>
                            <th scope="col">Cancelled on or Charges</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(key,item) in canncelpolicyselect['CancellationPolicies']">
                            <td>{{item['FromDate'] | date:'dd-MMM-y'}}</td>
                            <td>{{item['ToDate'] | date:'dd-MMM-y'}}</td>
                            <td>
                                <span ng-if="item['ChargeType']=='1'">Rs. {{item['Charge']}}</span>
                                <span ng-if="item['ChargeType']=='2'">{{item['Charge']}}%</span>
                                <span ng-if="item['ChargeType']=='3'">{{item['Charge']}} Night(s) charge</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                No Show will attract full cancellation charge unless otherwise specified.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                Early check out will attract full cancellation charge unless otherwise specified.Show Room Description
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

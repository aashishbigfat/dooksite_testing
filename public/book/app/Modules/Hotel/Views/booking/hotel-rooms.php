<!-- Hotel  Info Detail   page starts here -->
<style>
    #tts-harish-map {
        height: 500px;
    }
</style>
<div class="content" ng-app="MyApp" ng-controller="myCtrl">
    <div class="container">
        <div class="tts-api-error-msg" ng-if="loading==true">
            <?php echo  view('Modules/Hotel/Views\booking/hotel-room-loading-page.php') ?>
        </div>
        <div class="row" ng-if="loading==false && ErrorCode==0">
            <div class="col-12 d-flex align-items-center justify-content-between flex-wrap mb-2">
                <div class="mb-2">
                    <div class="d-flex gap-2 align-items-center">
                        <h5 class="mb-1 d-flex align-items-center flex-wrap">{{HotelInfoResult.HotelName}}</h5>
                        <span class="text-warning">
                            <i class="fal fa-star" ng-if="HotelInfoResult.StarRating>=1"></i>
                            <i class="fal fa-star" ng-if="HotelInfoResult.StarRating>=2"></i>
                            <i class="fal fa-star" ng-if="HotelInfoResult.StarRating>=3"></i>
                            <i class="fal fa-star" ng-if="HotelInfoResult.StarRating>=4"></i>
                            <i class="fal fa-star" ng-if="HotelInfoResult.StarRating>=5"></i>
                        </span>
                    </div>
                    <p><i class="far fa-location-dot text-danger"></i> {{HotelInfoResult.Address}}</p>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="border-bottom pb-4 mb-4">
                    <div class="service-wrap mb-4">
                        <div class="slider-wrap">
                            <!-- Main Image Slider -->
                            <div class="owl-carousel service-carousel nav-center mb-4" id="large-img">
                                <div class="service-img" ng-repeat="(key, item) in HotelInfoResult.Images track by key">
                                    <a href="javascript:void(0);">
                                        <img ng-src="{{item}}" alt="{{HotelInfoResult.ImagesALTTAGS[key] || 'Default Alt Text'}}" class="img-fluid">
                                    </a>
                                </div>
                            </div>

                            <!-- See All Button -->
                            <a ng-repeat="(key, item) in HotelInfoResult.Images track by key" ng-if="key !== 0"
                                href="{{item}}" data-fancybox="gallery" data-caption="{{HotelInfoResult.HotelName}}"
                                class="btn view-btn see-all-btn">
                                <i class="fa fa-image me-1"></i> See All
                            </a>
                        </div>

                        <!-- Thumbnail Navigation -->
                        <div class="owl-carousel slider-nav-thumbnails nav-center" id="small-img">
                            <div ng-repeat="(key, item) in HotelInfoResult.Images track by key">
                                <a href="javascript:void(0);">
                                    <img ng-src="{{item}}" alt="{{HotelInfoResult.ImagesALTTAGS[key] || 'Default Alt Text'}}" class="img-fluid">
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php echo  view('Modules/Hotel/Views\booking/room_data.php') ?>
                </div>
                <div class="border-bottom pb-2 mb-4" ng-if="HotelInfoResult.Description!=NULL">
                    <h5 class="mb-3 fs-18">About the Hotel</h5>
                    <p id="about_hotel">{{HotelInfoResult.Description | html_filter:"about_hotel"}}</p>
                </div>
                <div class="border-bottom pb-2 mb-4" ng-if="HotelInfoResult.HotelAmenities && HotelInfoResult.HotelAmenities.length!=0">
                    <h5 class="mb-3 fs-18">Popular Amenities</h5>
                    <div class="row">
                        <div class="col-sm-6 col-lg-4" ng-repeat="hotelAmenities in  HotelInfoResult.HotelAmenities">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">
                                    <img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid rounded tts_hotel_amenities_icon">
                                </span>
                                <p>{{hotelAmenities.Name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom pb-2 mb-4" ng-if="HotelInfoResult.HotelFacilities.length!=0">
                    <h5 class="mb-3 fs-18">Amenities & Info</h5>
                    <div class="row" ng-bind-html="HotelFacilities| safeHtml"></div>
                </div>
                <div class="border-bottom pb-2 mb-4" ng-if="HotelInfoResult.HotelPolicy!=NULL">
                    <h5 class="mb-3 fs-18">About Hotel Policy</h5>
                    <p id="hotel_policy">{{HotelInfoResult.HotelPolicy | html_filter:"hotel_policy"}}</p>
                </div>
                <div class="border-bottom pb-2 mb-4" ng-if="HotelInfoResult.Attractions.length!=0 && HotelInfoResult.Attractions!=null">
                    <h5 class="mb-3 fs-18">Nearby Attractions</h5>
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <ul class="amenities-hotel-list">
                                <li ng-repeat="(key,Attractions) in HotelInfoResult.Attractions">
                                    <span class="avatar">
                                        <i class="fa-solid fa-hand-point-right"></i> </span>
                                    <p id="attractions{{key}}"> {{Attractions.Value|html_filter_with_class:"attractions"+key}}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ************************************************ -->

            <div class="col-xl-4">
                <!-- Main Room Data -->
                <div ng-if="loading==false && HotelRoomErrorCode==0 && InfoSourceval=='FixedCombination'">
                    <div class="card shadow-none sticky-top" ng-if="HotelRoomData && HotelRoomData.length>0" ng-repeat="(key,Rooms) in HotelRoomData[0] | limitTo:1 ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="card-title">{{Rooms.RoomTypeName}}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                        <a href="javascript:void(0);" class="room-link" ng-click="RoomDescription(Rooms)">Room Information</a>
                                        <a href="javascript:void(0);" class="room-link text-muted">Cancellation Date {{Rooms.LastCancellationDate| date:'MMM d, y'}}</a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                        <div ng-if="Rooms.Inclusion.length!=0">
                                            <span ng-repeat="(key,item) in Rooms.Inclusion">{{item}}</span>
                                        </div>
                                        <div>
                                            <span class="text-danger d-block" ng-if="Rooms.IsPANMandatory==true">Pan Card Applicable</span>
                                            <span class="text-danger d-block" ng-if="Rooms.IsPassportMandatory==true">Passport Applicable</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="fs-13 fw-medium mb-1">Starts From</p>
                                            <h5 class="text-dark mb-0"><span>{{CurrencySymbol}}</span> {{HotelRoomPriceData[key]}}</h5>
                                        </div>
                                        <a class="btn btn-primary" href="javascript:void(0);" ng-click="ContinueRoom(RoomData,'fixed')"> Book Now </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none">
                    <div class="card-body">
                        <ul class="gallery-checkin list-unstyled">
                            <li class="Checkin border rounded p-3 mb-3 w-100 d-flex align-items-center justify-content-between">
                                <label class="openPopup d-flex align-items-center">
                                    <span class="flex-shrink-0 fs-3 fal fa-calendar me-2"></span> Check In
                                </label>
                                <h6 class="mb-0"><?php echo date('d', strtotime($searchRequest['CheckInDate'])) ?> <span class=""><?php echo date('M,Y', strtotime($searchRequest['CheckInDate'])) ?></span></h6>
                                <p class="text-muted mb-0"><?php echo date('l', strtotime($searchRequest['CheckInDate'])) ?></p>
                            </li>
                            <li class="nights border rounded p-3 mb-3 w-100 d-flex align-items-center justify-content-center">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="flex-shrink-0 fs-3 fal fa-moon me-2"></i> <?php echo $night = getDateDiffrence($searchRequest['CheckInDate'], $searchRequest['CheckOutDate']); ?> Night
                                </h6>
                            </li>
                            <li class="Checkin border rounded p-3 mb-3 w-100 d-flex align-items-center justify-content-between">
                                <label class="openPopup d-flex align-items-center"><span class="flex-shrink-0 fs-3 fal fa-calendar me-2"></span> Check Out </label>
                                <h6 class="mb-0"><?php echo date('d', strtotime($searchRequest['CheckOutDate'])) ?> <span><?php echo date('M,Y', strtotime($searchRequest['CheckOutDate'])) ?></span></h6>
                                <p class="text-muted mb-0"><?php echo date('l', strtotime($searchRequest['CheckOutDate'])) ?></p>
                            </li>
                            <li class="roomsGuests border rounded p-3 w-100 d-flex align-items-center justify-content-between">
                                <label class="d-flex align-items-center"><span class="flex-shrink-0 fs-3 fal fa-bed me-2"></span> Rooms & guests</label>
                                <h6 class="mb-0">
                                    <?php echo $searchRequest['NoOfRooms']; ?>
                                    <span>Room</span>
                                    <?php echo $roomGuests = getNoguest($searchRequest['RoomGuests']) ?>
                                    <span>Guest </span>
                                </h6>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card shadow-none">
                    <div class="card-body pb-0">
                        <h5 class="mb-3 fs-18">Why Book With Us</h5>
                        <div class="py-1">
                            <p class="d-flex align-items-center mb-3"><i class="fal fa-medal me-2"></i>Expertise and Experience</p>
                            <p class="d-flex align-items-center mb-3"><i class="fal fa-bars me-2"></i>Tailored Services</p>
                            <p class="d-flex align-items-center mb-3"><i class="fal fa-message-minus me-2"></i>Comprehensive Planning</p>
                            <p class="d-flex align-items-center mb-3"><i class="fal fa-user me-2"></i>Client Satisfaction</p>
                            <p class="d-flex align-items-center mb-3"><i class="fal fa-headset me-2"></i>24/7 Support</p>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none" ng-if="HotelInfoResult.Latitude!=null && HotelInfoResult.Longitude!=null">
                    <div class="card-body pb-0" ng-init="loadScript()">
                        <div id="tts-harish-map"> </div>
                    </div>
                </div>
            </div>
            <!-- ************************************************ -->
        </div>


        <!---old code---->

        <?php if (0) { ?>
            <div class="row ng-cloak d-none" ng-if="loading==false && ErrorCode==0">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex align-items-center">
                                <h2 class="tts__flight__result__page_title"> {{HotelInfoResult.HotelName}}</h2>
                                <div>
                                    <i class="fa fa-star" ng-if="HotelInfoResult.StarRating>=1"></i>
                                    <i class="fa fa-star" ng-if="HotelInfoResult.StarRating>=2"></i>
                                    <i class="fa fa-star" ng-if="HotelInfoResult.StarRating>=3"></i>
                                    <i class="fa fa-star" ng-if="HotelInfoResult.StarRating>=4"></i>
                                    <i class="fa fa-star" ng-if="HotelInfoResult.StarRating>=5"></i>
                                </div>
                            </div>
                            <h3 class="tts__flight__result__page_text"><i class="fa fa-map-marker me-2"></i>{{HotelInfoResult.Address}}</h3>

                        </div>
                    </div>
                    <div class="hotal-filter-card">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav justify-content-md-center">
                                    <li class="nav-item active">
                                        <a class="nav-link">Photos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " aria-current="page" href="#">Rooms & Rates </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"> Hotel Amenities </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"> Nearby Attractions </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"> Map </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-box my-3">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-interval="5000">
                            <div class="row">
                                <div class="col-lg-6 col-md-9">
                                    <div class="carousel-inner">
                                        <div class="carousel-item  active" ng-repeat="(key,item) in HotelInfoResult.Images" ng-if="key==0">
                                            <img class="d-block" ng-src="{{item}}" alt="{{HotelInfoResult.HotelName}}">
                                        </div>
                                        <div class="carousel-item" ng-repeat="(key,item) in HotelInfoResult.Images" ng-if="key!=0">
                                            <img class="d-block" ng-src="{{item}}" alt="{{HotelInfoResult.HotelName}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 carousel-indicators--thumbnails--column">
                                    <ol class="carousel-indicators--thumbnails">
                                        <li data-bs-target="#carouselExampleIndicators" ng-repeat="(key,item) in HotelInfoResult.Images" ng-if="key==0" data-bs-slide-to="{{key}}" class="active">
                                            <div class="">
                                                <img class=" d-block" ng-src="{{item}}" alt="{{HotelInfoResult.HotelName}}" onerror="angular.element(this).scope().imgError(this)">
                                            </div>
                                        </li>
                                        <li data-bs-target="#carouselExampleIndicators" ng-repeat="(key,item) in HotelInfoResult.Images" ng-if="key!=0" data-bs-slide-to="{{key}}">
                                            <div class="">
                                                <img class="d-block" ng-src="{{item}}" alt="{{HotelInfoResult.HotelName}}" onerror="angular.element(this).scope().imgError(this)">
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-lg-4 col-md-12 mt-3 mt-lg-0">
                                    <div class="section-one">
                                        <ul class="gallery-checkin">
                                            <li class="Checkin">
                                                <label class="openPopup"><span class="fa fa-calendar"></span> check in <span class="fa fa-down"></span></label>
                                                <h6 class=""><?php echo date('d', strtotime($searchRequest['CheckInDate'])) ?> <span class=""><?php echo date('M,Y', strtotime($searchRequest['CheckInDate'])) ?></span></h6>
                                                <p class=""><?php echo date('l', strtotime($searchRequest['CheckInDate'])) ?></p>
                                            </li>

                                            <li class="nights">
                                                <h5 class="">
                                                    <?php echo $night = getDateDiffrence($searchRequest['CheckInDate'], $searchRequest['CheckOutDate']); ?> Night<!---->
                                                </h5>
                                            </li>

                                            <li class="Checkin">
                                                <label class="openPopup"><span class="fa fa-calendar"></span> check out <span class="fa fa-down"></span></label>
                                                <h6 class=""><?php echo date('d', strtotime($searchRequest['CheckOutDate'])) ?> <span class=""><?php echo date('M,Y', strtotime($searchRequest['CheckOutDate'])) ?></span></h6>
                                                <p class=""><?php echo date('l', strtotime($searchRequest['CheckOutDate'])) ?></p>
                                            </li>
                                            <li class="roomsGuests">
                                                <label class="">Rooms &amp; guests</label>
                                                <h6 class="date">
                                                    <?php echo $searchRequest['NoOfRooms']; ?>
                                                    <span class="">
                                                        Room
                                                    </span>
                                                    <?php echo $roomGuests = getNoguest($searchRequest['RoomGuests']) ?>
                                                    <span class="">
                                                        Guest
                                                    </span>
                                                </h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="row section-two" ng-if="HotelInfoResult.HotelAmenities && HotelInfoResult.HotelAmenities.length!=0">
                                        <ul class="inclusion">
                                            <li ng-repeat="hotelAmenities in  HotelInfoResult.HotelAmenities">
                                                <span><img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid rounded tts_hotel_amenities_icon"></span>{{hotelAmenities.Name}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo  view('Modules/Hotel/Views\booking/room_data.php') ?>
                    <div class="hotal-filter-card my-3 amenities-wrapper p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" ng-if="HotelInfoResult.HotelAmenities && HotelInfoResult.HotelAmenities.length!=0">
                                        <h3>Amenities & Info</h3>
                                        <ul class="amenities-list d-flex align-items-center p-3">
                                            <li ng-repeat="hotelAmenities in  HotelInfoResult.HotelAmenities">
                                                <span class="ak-beer"><img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid rounded tts_hotel_amenities_icon"></span>{{hotelAmenities.Name}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-12 mb-2" ng-if="HotelInfoResult.HotelFacilities.length!=0">
                                        <h3>Amenities & Info</h3>
                                        <ul class="amenities-hotel-list" ng-bind-html="HotelFacilities| safeHtml"></ul>
                                    </div>


                                    <div class="col-md-12 mb-2" ng-if="HotelInfoResult.Description!=NULL">
                                        <h3>ABOUT THE HOTEL</h3>
                                        <p id="about_hotel">{{HotelInfoResult.Description | html_filter:"about_hotel"}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotal-filter-card my-3 amenities-wrapper p-3" ng-if="HotelInfoResult.HotelPolicy!=NULL">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>ABOUT Hotel Policy</h3>
                                <p id="hotel_policy">{{HotelInfoResult.HotelPolicy | html_filter:"hotel_policy"}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="hotal-filter-card my-3 amenities-wrapper p-3" ng-if="HotelInfoResult.Attractions.length!=0 && HotelInfoResult.Attractions!=null">
                        <div class="row">
                            <h3>Nearby Attractions</h3>
                            <hr>
                            <div class="col-md-12">
                                <ul class="amenities-hotel-list">
                                    <li ng-repeat="(key,Attractions) in HotelInfoResult.Attractions"> <i class="fa fa-check"></i>
                                        <span id="attractions{{key}}"> {{Attractions.Value|html_filter_with_class:"attractions"+key}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>





                    <div class="hotal-filter-card my-3 p-3" ng-if="HotelInfoResult.Latitude!=null && HotelInfoResult.Longitude!=null">
                        <div class="row" ng-init="loadScript()">
                            <div class="col-md-12" id="tts-harish-map">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-12 tts-api-error-msg  ng-cloak" ng-if="loading==false && ErrorCode!=0">
                <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
                <h5 class="mt-4"> {{errormessage}}</h5>
                <p class="mb-2">Sorry,No hotel Room found for your selected date. Please change your dates / search criteria.</p>
                <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 tts-api-error-msg  ng-cloak" ng-if="loading==false && HotelRoomErrorCode==0 && InfoSourceval=='OpenCombination'">
                <?php echo view('Modules/Hotel/Views/booking\open_combination_bottom_view.php'); ?>
            </div>
        </div>

    </div>
    <!------Start BlockRoom modal------>
    <div class="modal fade" id="BlockRoom" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal_content">
                <div class="modal-header modal_header">
                    <h5 class="modal-title" ng-if="blockroomloading==true">Confirming Your Hotel</h5>
                    <h5 class="modal-title" ng-if="blockroomloading==false">{{Blockroomtitle}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="hotel-main">
                        <!----- start loading ---->
                        <div class="row" ng-if="blockroomloading==true">
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
                                    <h5> Please Wait... </h5>
                                </div>
                            </div>
                        </div>
                        <!----- end loading -->
                        <div class="row" ng-if="blockroomloading==false">
                            <div class="col-md-12" ng-if="BlockRoomErrorCode!=0">
                                <div class="text-center">
                                    <h5 class="mt-4"> {{blockroomerrormessagee}}</h5>
                                </div>
                            </div>
                            <div class="col-md-12" ng-if="BlockRoomErrorCode==0 && GetConfirmShowBox==1">
                                <div class="text-center" id="blockhtmldata">
                                    {{Blockhtmldata|html_filter:"blockhtmldata"}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------End BlockRoom modal------>
    </div>
    <!-- Hotel Room  page ends here -->



    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    <script>
        let url = "<?php echo  site_url(); ?>"
        const app = angular.module("MyApp", []);
        app.controller("myCtrl", function($scope, $http) {
            $scope.loading = true;
            $scope.blockroomloading = true;
            $scope.HotelFacilities = '';
            $scope.hotelroomselect = [];
            $scope.CurrencySymbol = '';
            $scope.CurrencyCode = '';
            $http({
                method: "GET",
                url: url + 'hotel/get-hotel-info?' + '<?php echo http_build_query($_GET); ?>'
            }).then(function success(response) {
                $scope.loading = false;
                $scope.ErrorCode = response.data.Error.ErrorCode;
                $scope.ErrorMessage = response.data.Error.ErrorMessage;
                if ($scope.ErrorCode == 0) {
                    $scope.HotelInfoResult = response.data.Result;
                    var latitude = $scope.HotelInfoResult.Latitude;
                    var longitude = $scope.HotelInfoResult.Longitude;



                    var Facilities = "";
                    if ($scope.HotelInfoResult.HotelFacilities) {
                        $scope.HotelInfoResult.HotelFacilities.forEach(function(value, key) {
                            let a = value.split(',');
                            if (a) {
                                a.forEach(function(v, k) {
                                    Facilities += '<div class="col-sm-6 col-lg-4"><div class="d-flex align-items-center mb-3"><span class="avatar"><i class="fa-solid fa-hand-point-right"></i></span> ' + v + '</div></div>';
                                });
                            }
                        });
                    }
                    $scope.HotelFacilities = Facilities;


                    $scope.initialize = function() {
                        $scope.mapOptions = {
                            zoom: 18,
                            center: new google.maps.LatLng(latitude, longitude)
                        };
                        $scope.map = new google.maps.Map(document.getElementById('tts-harish-map'), $scope.mapOptions);

                        $scope.Marker = new google.maps.Marker({
                            position: new google.maps.LatLng(latitude, longitude),
                            map: $scope.map,
                            title: $scope.HotelInfoResult['HotelName']
                        });

                        var contentString = '<div id="content">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h3 id="thirdHeading" class="thirdHeading">' + $scope.HotelInfoResult['HotelName'] + '</h3>' +
                            '<div id="bodyContent">' +
                            '<p>' + $scope.HotelInfoResult['Address'] + ' ' + $scope.HotelInfoResult['Address1'] + '</p>' +
                            '</div>' +
                            '</div>';

                        var infoWindow = new google.maps.InfoWindow();
                        infoWindow.setContent(contentString);
                        infoWindow.open($scope.map, $scope.Marker);
                        google.maps.event.addListener($scope.Marker, 'click', function() {
                            infoWindow.setContent(contentString);
                            infoWindow.open($scope.map, $scope.Marker);
                        });
                    }
                    $scope.openInfoWindow = function(e, selectedMarker) {
                        e.preventDefault();
                        google.maps.event.trigger(selectedMarker, 'click');
                    }

                    $scope.loadScript = function() {
                        var headID = document.getElementsByTagName("head")[0];
                        var newScript = document.createElement('script');
                        newScript.type = 'text/javascript';
                        newScript.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB_CuOFHUtZhKKJUA_xlRJiodvGo6aCeNA';
                        headID.appendChild(newScript);
                        setTimeout(function() {
                            $scope.initialize();
                        }, 500);
                    }

                    $http({
                        method: "GET",
                        url: url + 'hotel/get-room-info?' + '<?php echo http_build_query($_GET); ?>'
                    }).then(function success(hotelroomresponse) {
                        var request = '<?php echo  json_encode($_GET); ?>';
                        request = angular.fromJson(request);
                        $scope.HotelRoomErrorCode = hotelroomresponse.data.Error.ErrorCode;
                        $scope.HotelRoomErrorMessage = hotelroomresponse.data.Error.ErrorMessage;
                        $scope.HotelRoomResult = hotelroomresponse.data.Result;
                        if ($scope.HotelRoomErrorCode == 0) {
                            $scope.CurrencySymbol = $scope.HotelRoomResult.CurrencySymbol;
                            $scope.CurrencyCode = $scope.HotelRoomResult.CurrencyCode;

                            $scope.HotelRoomData = $scope.HotelRoomResult.FinalRoomData;
                            $scope.HotelRoomPriceData = $scope.HotelRoomResult.FinalPriceDataRoomData;
                            $scope.InfoSourceval = $scope.HotelRoomResult.InfoSource;

                            $scope.openCombinationData = $scope.HotelRoomResult.OpenCombinationData;

                            var seletedRoomIndex = {};
                            $scope.openCombinationEvent = function(item, roomNumber) {
                                $scope.openCombinationData[roomNumber] = item;
                            }


                            carouselFunction();


                            $scope.ContinueRoom = function(item, type) {
                                seletedRoomIndex.HotelName = $scope.HotelInfoResult.HotelName;
                                seletedRoomIndex.ResultIndex = request['rindex'];
                                seletedRoomIndex.HotelCode = request.hcode;
                                seletedRoomIndex.SearchTokenId = request.token;
                                seletedRoomIndex.NoOfRooms = item.length;
                                seletedRoomIndex.HotelRoomsDetails = item;
                                var BlockRoomModal = document.getElementById("BlockRoom");
                                if (BlockRoomModal !== null) {
                                    new bootstrap.Modal(BlockRoomModal).show();
                                }
                                $http({
                                    method: "POST",
                                    url: url + 'hotel/get-blockroom-info',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    data: $.param(seletedRoomIndex)
                                }).then(function success(blockroomresponse) {
                                    $scope.blockroomloading = false;
                                    $scope.BlockRoomErrorCode = blockroomresponse.data.Error.ErrorCode;
                                    $scope.BlockRoomErrorMessage = blockroomresponse.data.Error.ErrorMessage;
                                    if ($scope.BlockRoomErrorCode == 0) {
                                        $scope.Blockhtmldata = blockroomresponse.data.PriceChange;
                                        $scope.GetConfirmShowBox = blockroomresponse.data.GetConfirmShowBox;
                                        $scope.Blockroomtitle = blockroomresponse.data.Blockroomtitle;
                                        $scope.RedirectUrl = blockroomresponse.data.RedirectUrl;
                                        if ($scope.GetConfirmShowBox == 0) {
                                            $("#BlockRoom").modal('hide');
                                            new bootstrap.Modal(BlockRoomModal).hide();
                                            window.location.href = $scope.RedirectUrl;
                                        }
                                    } else {
                                        $scope.blockroomloading = false;
                                        $scope.blockroomerrormessagee = $scope.HotelRoomErrorMessage;
                                    }
                                }, function error(hotelroomresponse) {
                                    $scope.blockroomloading = false;
                                    $scope.blockroomerrormessagee = "Something went wrong";
                                });

                            }
                        } else {
                            $scope.hotelroomerrormessagee = $scope.HotelRoomErrorMessage;
                        }
                    }, function error(hotelroomresponse) {
                        $scope.hotelroomerrormessagee = "Something went wrong";
                    });

                } else {
                    $scope.errormessage = $scope.ErrorMessage;
                }
            }, function error(response) {
                $scope.loading = false;
                $scope.errormessage = "Something went wrong";
            });

            /** Image no found Start **/
            $scope.imgError = function(thisval) {
                thisval.src = "<?php echo site_url(); ?>webroot/img/no-photo.png";
            }
            /** Image no found End **/

            $scope.RoomDescription = function(item) {
                var myModal = new bootstrap.Modal(document.getElementById('hotel_room_description'));
                $scope.hotelroomselect = item;
                myModal.show()
            }
        })
        app.filter('html_filter', function() {
            return function(input, element) {
                return $("#" + element).html(input.replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
            };
        });
        app.filter('html_filter_with_class', function() {
            return function(input, element) {
                return $("." + element).html(input.replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
            };
        });

        app.filter('safeHtml', function($sce) {
            return function(val) {
                return $sce.trustAsHtml(val);
            };
        });
    </script>

    <script>
        function carouselFunction() {
            if ($('.service-carousel').length > 0) {
                $(document).ready(function() {
                    var sync1 = $("#large-img");
                    var sync2 = $("#small-img");
                    var slidesPerPage = 5; // Number of thumbnails per page
                    var syncedSecondary = true;
                    // Initialize large image carousel
                    sync1.owlCarousel({
                        items: 1,
                        slideSpeed: 2000,
                        smartSpeed: 2000,
                        nav: true,
                        autoplay: false,
                        dots: false,
                        loop: true,
                        navText: [
                            '<i class="fa-solid fa-chevron-left"></i>',
                            '<i class="fa-solid fa-chevron-right"></i>'
                        ],
                        responsiveRefreshRate: 200
                    }).on('changed.owl.carousel', syncPosition);

                    // Initialize small thumbnail carousel
                    sync2.owlCarousel({
                        items: slidesPerPage,
                        margin: 10,
                        dots: false,
                        nav: true,
                        smartSpeed: 2000,
                        slideBy: slidesPerPage,
                        responsiveRefreshRate: 100,
                        navText: [
                            '<i class="fa-solid fa-chevron-left"></i>',
                            '<i class="fa-solid fa-chevron-right"></i>'
                        ]
                    }).on('initialized.owl.carousel', function() {
                        sync2.find(".owl-item").eq(0).addClass("current");
                    }).on('changed.owl.carousel', syncPosition2);

                    function syncPosition(event) {
                        let count = event.item.count - 1;
                        let current = Math.round(event.item.index - (event.item.count / 2) - 0.5);

                        current = current < 0 ? count : current > count ? 0 : current;

                        sync2.find(".owl-item").removeClass("current").eq(current).addClass("current");

                        let onscreen = sync2.find('.owl-item.active').length - 1;
                        let start = sync2.find('.owl-item.active').first().index();
                        let end = sync2.find('.owl-item.active').last().index();

                        if (current > end) {
                            sync2.trigger('to.owl.carousel', [current, 100, true]);
                        }
                        if (current < start) {
                            sync2.trigger('to.owl.carousel', [current - onscreen, 100, true]);
                        }
                    }

                    function syncPosition2(event) {
                        if (syncedSecondary) {
                            let number = event.item.index;
                            sync1.trigger('to.owl.carousel', [number, 100, true]);
                        }
                    }

                    sync2.on("click", ".owl-item", function() {
                        let number = $(this).index();
                        sync1.trigger('to.owl.carousel', [number, 300, true]);
                    });

                    // Scroll to a specific image when the "See All" button is clicked
                    // Fancybox Config
                    $('[data-fancybox="gallery"]').fancybox({
                        buttons: [
                            "slideShow",
                            "thumbs",
                            "zoom",
                            "fullScreen",
                            "close"
                        ],
                        loop: true,
                        transitionEffect: "fade",
                        protect: true,

                    });
                });
            }
        }
    </script>
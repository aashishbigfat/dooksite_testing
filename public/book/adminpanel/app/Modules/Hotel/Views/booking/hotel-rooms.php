
<!-- Hotel  Info Detail   page starts here -->
<style>
    #tts-harish-map {
        height: 500px;
    }
    .first_room_amenity{
        list-style: circle;
        margin-left: 17px;
    }
</style>
<div class="tts__flight__result__page py-3" ng-app="MyApp" ng-controller="myCtrl">
    <div class="container">
        <div class="row">
            <div class="col-12 tts-api-error-msg" ng-if="loading==true">
                <?php echo  view('Modules/Hotel/Views\booking/hotel-room-loading-page.php') ?>
            </div>
        </div>
        <div class="row  ng-cloak" ng-if="loading==false && ErrorCode==0">

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
                            <ul class="nav justify-content-center">
                                <li class="nav-item active">
                                    <a class="nav-link">Photos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:void(0);"  onclick="scrolldiv('hotel_room_rates')">Rooms & Rates </a>
                                </li>
                                <li class="nav-item" ng-if="HotelInfoResult.HotelFacilities.length!=0">
                                    <a class="nav-link" href="javascript:void(0);" onclick="scrolldiv('hotel_amenities')"> Hotel Amenities </a>
                                </li>
                                <li class="nav-item" ng-if="HotelInfoResult.HotelPolicy!=NULL">
                                    <a class="nav-link" href="javascript:void(0);" onclick="scrolldiv('hotel_policy')">Policy </a>
                                </li>
                                <li class="nav-item" ng-if="HotelInfoResult.Attractions.length!=0 && HotelInfoResult.Attractions!=null">
                                    <a class="nav-link" href="javascript:void(0);" onclick="scrolldiv('hotel_attractions')"> Nearby Attractions </a>
                                </li>
                                <li class="nav-item" ng-if="HotelInfoResult.Latitude!=null && HotelInfoResult.Longitude!=null">
                                    <a class="nav-link" href="javascript:void(0);" onclick="scrolldiv('hotel_map')"> Map </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="gallery-box my-3">
                    <div  id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-interval="5000">
                            <div class="row"> 
                                <div class="col-md-7 ">
                                      <div   class="carousel-inner">
                                        <div class="carousel-item  active"  ng-repeat="(key,item) in HotelInfoResult.Images"  ng-if = "key==0">
                                          <img class="d-block " src="{{item}}"  alt="{{HotelInfoResult.HotelName}}" >
                                        </div>
                                        <div class="carousel-item"  ng-repeat="(key,item) in HotelInfoResult.Images"  ng-if = "key!=0">
                                          <img class="d-block" src="{{item}}"  alt="{{HotelInfoResult.HotelName}}">
                                        </div>
                                      </div>
                                </div>
                                 <div class="col-md-1 carousel-indicators--thumbnails--column">
                                    <ol class="carousel-indicators--thumbnails">
                                        <li data-bs-target="#carouselExampleIndicators" ng-repeat="(key,item) in HotelInfoResult.Images"  ng-if = "key==0" data-bs-slide-to="{{key}}" class="active"> 
                                            <div class="">
                                                <img class=" d-block" src="{{item}}" alt="{{HotelInfoResult.HotelName}}" onerror="angular.element(this).scope().imgError(this)">
                                            </div>
                                        </li>
                                        <li data-bs-target="#carouselExampleIndicators" ng-repeat="(key,item) in HotelInfoResult.Images"  ng-if = "key!=0" data-bs-slide-to="{{key}}"> 
                                            <div class="">
                                                <img class="d-block" src="{{item}}" alt="{{HotelInfoResult.HotelName}}" onerror="angular.element(this).scope().imgError(this)">
                                            </div>
                                        </li>
                                      </ol>
                                </div>
                                <div class="col-md-4">
                                    <div class="row section-one">
                                        <ul class="gallery-checkin">
                                            <li class="Checkin">
                                               <label  class="openPopup"><span class="fa fa-calendar"></span> check in <span  class="fa fa-down"></span></label>
                                               <h6 class=""><?php echo date('d', strtotime($searchRequest['CheckInDate'])) ?> <span class=""><?php echo date('M,Y', strtotime($searchRequest['CheckInDate'])) ?></span></h6>
                                               <p  class=""><?php echo date('l', strtotime($searchRequest['CheckInDate'])) ?></p>
                                            </li>
                                            
                                               <li class="nights">
                                                   <h5 class="">
                                                   <?php echo $night = getDateDiffrence($searchRequest['CheckInDate'], $searchRequest['CheckOutDate']); ?> Night<!---->
                                                   </h5>
                                                </li>
                                            
                                            <li class="Checkin">
                                               <label  class="openPopup"><span class="fa fa-calendar"></span> check out <span  class="fa fa-down"></span></label>
                                               <h6 class=""><?php echo date('d', strtotime($searchRequest['CheckOutDate'])) ?> <span class=""><?php echo date('M,Y', strtotime($searchRequest['CheckOutDate'])) ?></span></h6>
                                               <p  class=""><?php echo date('l', strtotime($searchRequest['CheckOutDate'])) ?></p>
                                            </li>
                                            <li class="roomsGuests">
                                               <label  class="">Rooms &amp; guests</label>
                                               <h6  class="date">
                                               <?php echo $searchRequest['NoOfRooms']; ?>
                                                  <span  class="">
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
                                    <div class="row section-two p-0">
                                        <div class="row" ng-if="InfoSourceval=='FixedCombination'">
                                            <div class="col-md-12 mb-1">
                                                <h5>{{HotelRoomData[0][0]['RoomTypeName']}}</h5>
                                                <p class="mb-0">{{HotelRoomData[0][0]['RoomTypeName']}}</p>
                                                <a href="javascript:void(0);" class="fs-12 text-danger" ng-click="cancellation_policy(HotelRoomData[0])">Free cancellation till: {{HotelRoomData[0][0].LastCancellationDate| date:'dd-MMM-y'}} </a>
                                                <div class="hot-amenity">
                                                    <span class="float-start"><i class="fa fa-check text-success"></i> {{HotelRoomData[0][0]['Amenities'][0]}}</span>
                                                    <span class="float-end"><a href="javascript:void(0)" onclick="scrolldiv('hotel_room_rates')">Check other Rooms</a></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="first_room_amenity">
                                                <div  ng-repeat="(a,inclusion) in HotelRoomData[0][0]['Amenity']">
                                                    <li class="free" ng-if="a<3"> {{inclusion}}</li>
                                                </div>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="price text-end"> 
                                                        <h5>₹ {{HotelRoomPriceData[0]}}</h5>
                                                        <button class="badge badge_bg"  ng-click="ContinueRoom(HotelRoomData[0],'fixed')">Book Now</button>
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                </div>
                <div id="hotel_room_rates">
                <?php echo  view('Modules/Hotel/Views\booking/room_data.php') ?>
                </div>
                <div class="hotal-filter-card my-3 amenities-wrapper p-3" id="hotel_amenities">
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
                <div class="hotal-filter-card my-3 amenities-wrapper p-3" id="hotel_attractions" ng-if="HotelInfoResult.Attractions.length!=0 && HotelInfoResult.Attractions!=null">
                    <div class="row">
                        <h3>Nearby Attractions</h3>
                        <hr>
                        <div class="col-md-12">
                          <ul class="amenities-hotel-list" ng-bind-html="HotelAttractions| safeHtml"></ul>
                        </div>
                    </div>
                </div>
                <div class="hotal-filter-card my-3 p-3"  id="hotel_map" ng-if="HotelInfoResult.Latitude!=null && HotelInfoResult.Longitude!=null">
                    <div class="row" ng-init="loadScript()">
                        <div class="col-md-12" id="tts-harish-map">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 tts-api-error-msg  ng-cloak" ng-if="loading==false && ErrorCode!=0">
                <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
                <h5 class="mt-4"> {{errormessage}}</h5>
                <p class="mb-2">Sorry,No hotel Room found for your selected date. Please change your dates / search criteria.</p>
                <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
            </div>
        </div>
    </div>
    <!------Start BlockRoom modal------>
<div class="modal fade" id="BlockRoom" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal_content">
            <div class="modal-header modal_header">
                <h5 class="modal-title" ng-if= "blockroomloading==true">Confirming Your Hotel</h5>
                <h5 class="modal-title" ng-if= "blockroomloading==false">{{Blockroomtitle}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="hotel-main">
                    <!----- start loading ---->
                    <div class="row" ng-if= "blockroomloading==true">
                        <div class="col-md-12">
                            <div class="text-center">
                                    <div class="loader mt-3 mb-3">
                                        <div role="status" class="spinner-grow text-primary">
                                            <span class="sr-only">Loading...</span></div>
                                        <div role="status" class="spinner-grow text-secondary">
                                            <span class="sr-only">Loading...</span></div>
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
                    <div class="row" ng-if= "blockroomloading==false">
                        <div class="col-md-12" ng-if= "BlockRoomErrorCode!=0">
                            <div class="text-center">
                            <h5 class="mt-4"> {{blockroomerrormessagee}}</h5>
                            </div>
                        </div>
                        <div class="col-md-12" ng-if= "BlockRoomErrorCode==0 && GetConfirmShowBox==1">
                            <div class="text-center" id  = "blockhtmldata">
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
        $scope.HotelFacilities ='';
        $scope.HotelAttractions ='';
        $scope.hotelroomselect=[];
        $scope.canncelpolicyselect=[];
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



                var Facilities="";
                if($scope.HotelInfoResult.HotelFacilities) {
                    $scope.HotelInfoResult.HotelFacilities.forEach(function(value , key) {
                    let a= value.split(',');
                    if(a){
                            a.forEach(function(v, k) {
                            Facilities+='<li>'+ v +'</li>';
                            });
                        }
                    });
                }
                $scope.HotelFacilities=Facilities;

                var Attractions="";
                if($scope.HotelInfoResult.Attractions) {
                    $scope.HotelInfoResult.Attractions.forEach(function(value , key) {
                    if(value['Value']){
                             Attractions+='<li>'+ value['Value'] +'</li>';
                        }
                    });
                }
                $scope.HotelAttractions=Attractions;


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

                    var contentString = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h5 id="thirdHeading" class="thirdHeading">'+$scope.HotelInfoResult['HotelName']+'</h5>'+
                        '<div id="bodyContent">'+
                        '<p>'+$scope.HotelInfoResult['Address']+' '+$scope.HotelInfoResult['Address1']+'</p>'+
                        '</div>'+
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
                        $scope.HotelRoomData = $scope.HotelRoomResult.FinalRoomData;
                        $scope.HotelRoomPriceData = $scope.HotelRoomResult.FinalPriceDataRoomData;
                        $scope.InfoSourceval = $scope.HotelRoomResult.InfoSource;
                        var seletedRoomIndex = {};
                        $scope.ContinueRoom = function(item, type) {
                            seletedRoomIndex.HotelName = $scope.HotelInfoResult.HotelName;
                            seletedRoomIndex.ResultIndex = request['rindex'];
                            seletedRoomIndex.HotelCode = request.hcode;
                            seletedRoomIndex.SearchTokenId = request.token;
                            seletedRoomIndex.NoOfRooms = item.length;
                            seletedRoomIndex.HotelRoomsDetails = item;
                            var BlockRoomModal =document.getElementById("BlockRoom");
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
                                    if($scope.GetConfirmShowBox==0){
                                        $("#BlockRoom").modal('hide');
                                        new bootstrap.Modal(BlockRoomModal).hide();
                                        window.location.href =  $scope.RedirectUrl;
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

        $scope.RoomDescription=function(item){
            var myModal = new bootstrap.Modal(document.getElementById('hotel_room_description'));
            $scope.hotelroomselect=item;
            myModal.show()
        }

        $scope.cancellation_policy=function(item){
            var myModal = new bootstrap.Modal(document.getElementById('cancellation_policy_modal'));
            $scope.canncelpolicyselect=item[0];
            myModal.show()
        }
    })
    app.filter('html_filter', function() {
        return function(input, element) {
            return $("#" + element).html(input.replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
        };
    });

    app.filter('safeHtml', function ($sce) {
    return function (val) {
        return $sce.trustAsHtml(val);
        };
    });

  
  function scrolldiv(id) {
                window.scrollTo(0, 
          findPosition(document.getElementById(id)));
            }
function findPosition(obj) {
    var currenttop = 0;
    if (obj.offsetParent) {
        do {
            currenttop += obj.offsetTop-100;
        } while ((obj = obj.offsetParent));
        return [currenttop];
    }
}
</script>
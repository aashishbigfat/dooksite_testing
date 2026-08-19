<main ng-app="hotelApp" ng-controller="hotelCtrl" scroll>
    <div class="container" ng-if="loading==true">
        <?php echo view('Modules/Hotel/Views\booking\loading-page.php'); ?>
    </div>
    <div class="tts__flight__result__page py-3 ng-cloak" ng-if="loading==false">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php echo view('Modules/Hotel/Views\booking\modify_search'); ?>
                </div>
                <!-- filter starts here -->
                <div class="col-sm-3" ng-if="ErrorCode==0">
                    <?php echo view('Modules/Hotel/Views\booking\hotel_filter'); ?>
                </div>
                <!-- filter ends here -->
                <!-- hotel result starts here -->
                <div class="col-sm-9" ng-if="ErrorCode==0">
                    <div class="row">
                        <!----col-sm-12--->
                        <div class="col-sm-12 mt-3">
                            <div class="tts__show__result p-2">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="tts__showing__result__heading"><b>Showing Result {{FilteredResultCount}} of {{ TotalResult }} Hotels</b>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-end tts__showing__price__filter">
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="text-muted mr-2">SORT BY:</span>
                                                    {{topsortingtext}}
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button class="dropdown-item" type="button" ng-click="orderby('PublishedPrice',false,$event)">Price - Low to
                                                        High
                                                    </button>
                                                    <button class="dropdown-item" type="button" ng-click="orderby('PublishedPrice',true,$event)">Price - High to
                                                        Low
                                                    </button>
                                                    <button class="dropdown-item" type="button" ng-click="orderby('StarRating',false,$event)">Star Rating - Low to High
                                                    </button>
                                                    <button class="dropdown-item" type="button" ng-click="orderby('StarRating',true,$event)">Star Rating - High to Low
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-check form-switch float-right pt-2">
                                            <input class="form-check-input" type="checkbox" role="switch" ng-model="shownetfare" id="net-fare">
                                            <label class="form-check-label" for="net-fare">Show Net
                                                Fare</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!----col-sm-12--->
                        <!----col-sm--12-->
                        <div class="col-sm-12 mt-3">
                            <div class="hotal-filter-card">
                                <ul class="nav justify-content-between align-items-center d-flex">
                                    <li class="nav-item active">
                                        <a class="nav-link disabled">SORT BY</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" ng-click="orderby('HotelName')">Hotel Name
                                            <span class="fa" ng-show="field === 'HotelName'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" ng-click="orderby('StarRating')">RATING
                                            <span class="fa" ng-show="field === 'StarRating'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" ng-click="orderby('PublishedPrice')" class="me-2">PRICE
                                            <span class="fa" ng-show="field === 'PublishedPrice'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                        </a>
                                    </li>

                                </ul>

                            </div>
                            <div class="hotal-filter-card" ng-repeat="(key,hotel)  in Results | orderBy:field:reverse | limitTo :limit">
                                <div class="col-md-12 mt-3 mb-3" id  =  "hoteCode{{hotel.HotelCode}}">
                                    <div class="hotel_box mb-3">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-4">
                                            <img ng-if="hotel.HotelPicture=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="<?php echo site_url(); ?>webroot/img/no-photo.png" alt="{{hotel.HotelName}}" class="image">
                                                <img ng-if="hotel.HotelPicture!=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="{{ hotel.HotelPicture}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotel.HotelName}}" class="img-fluid rounded" style="height:200px; width:100%; object-fit:cover">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="hotel-box-body text-left">
                                                    <h5 class="hotel-title">{{hotel.HotelName}}
                                                        <span>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=1"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=2"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=3"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=4"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=5"></i>
                                                        </span>
                                                        <a  ng-if = "hotel.HotelReviewUrl && hotel.HotelReviewUrl!=''"  href =  "{{hotel.HotelReviewUrl}}" class  = "fs-12">Review Rating {{hotel.HotelReviewRatings}}</a>
                                                        <a  ng-if = "hotel.HotelReviewUrl && hotel.HotelReviewUrl==''"  href =  "javascript:void(0)" class  = "">Review Rating  {{hotel.HotelReviewRatings}}</a>
                                                    </h5>
                                                  <br/>
                                                    <a href=""><i class="fa fa-map-marker"></i> {{hotel.HotelAddress|rtrim}}</a>
                                                    <ul class="d-flex align-items-center my-2" ng-if="hotel.HotelAmenities && hotel.HotelAmenities.length>0"> 
                                                            <li ng-repeat  =  "hotelAmenities in hotel.HotelAmenities| limitTo :5"> <img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid rounded tts_hotel_amenities_icon">{{hotelAmenities.Name}} </li>
                                                        </ul>
                                                        <span  ng-if="hotel.HotelPromotion!=''" class="badge bg-filter mt-1 ml-0 tts_white_space text-start">{{ hotel.HotelPromotion |trusthtml }}</span>
                                                    <span class=" mt-1 ml-0 text-justify d-flex" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{hotel.HotelDescription|trusthtml}}" ng-if="hotel.HotelDescription!='' || hotel.HotelDescription!=Null">{{ hotel.HotelDescription | limitTo: hoteldescriptionlimitstep|trusthtml }} {{hotel.HotelDescription.length < hoteldescriptionlimitstep ? '' : '...'}} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end">
                                            <div class="float-right" ng-if  =  "superAdminAccess==1">
                                                        <a class  =  "badge badge_bg text-end"  href  =  "javascript:void(0);" ng-click  =  "blockHotel(this)"   id  =  "blockHotelButton{{hotel.HotelCode}}">Block</a>
                                                    </div>
                                                <div class="hotal_price">
                                                    <div class="d-block">
                                                        <h4 class="tts__flight__price"> ₹ {{hotel.Price.PublishedPrice}}</< /h4>
                                                            <h4 class="tts__flight__price text-success" ng-if="shownetfare"> ₹ {{hotel.Price.OfferedPrice}} </h4>
                                                    </div>
                                                  
                                                    <div class="float-right">
                                                        <a href="<?php echo site_url('hotel/hotel-rooms');?>?rindex={{hotel.ResultIndex}}&token={{token}}&hcode={{hotel.HotelCode}}" target="_blank">
                                                            <span class="badge badge_bg text-end">Select Room</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----Filter NoHotel ------------>
                    <div class="no-hotel tts-api-error-msg mt-4" ng-if="FliterNoHotel">
                        <p class="mt-4">No hotel found that matches your filter criteria.Reset your filter </p>
                        <button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll('all')">Reset All Filter</button>
                    </div>
                    <!-----Filter NoHotel ------------>
                </div>
                <!-- hotel result end here -->
                <!-------------API Error  ------------>
                <div class="row m0">
                    <div class="col-12 tts-api-error-msg " ng-if="ErrorCode!=0">
                        <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
                        <h5 class="mt-4"> {{errormessage}}</h5>
                        <p class="mb-2">Sorry,No hotels found for your selected date. Please change your dates / search criteria.</p>
                        <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
                    </div>
                </div>
                <!-------------API Error  ------------>
            </div>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
    let url = "<?php echo  site_url(); ?>"
    let limitStep = 20;
    var hoteldescriptionlimitstep = 160;
    var app = angular.module('hotelApp', []);
    app.controller('hotelCtrl', function($scope, $http) {
        $scope.loading = true;
        $scope.topsortingtext = 'Price - Low to High';
        $scope.shownetfare = false;
        $scope.FliterNoHotel = false;
        $scope.superAdminAccess = 0;
        $scope.CityName = "";
        $scope.CityId = "";
        $http({
            method: "GET",
            url: url + "hotel/get-hotel-lists?" + "<?php echo http_build_query($_GET); ?>"
        }).then(function mySuccess(response) {
            $scope.loading = false;
            $scope.ErrorCode = response.data.Error.ErrorCode;
            $scope.superAdminAccess = response.data.superAdminAccess;
            $scope.CityName = response.data.CityName;
            $scope.CityId = response.data.CityId;
            $scope.ErrorMessage = response.data.Error.ErrorMessage;
            if ($scope.ErrorCode == 0) {
                $scope.Results = response.data.Result;
                $scope.FilterResults = response.data.Result;
                $scope.limit = limitStep;
                $scope.FilteredResultCount = $scope.Results.length;
                $scope.hoteldescriptionlimitstep = hoteldescriptionlimitstep;
                $scope.token = response.data.SearchTokenId
                $scope.TotalResult = response.data.TotalResults;
                $scope.MinPrice = response.data.Price.min;
                $scope.MaxPrice = response.data.Price.max;
                $scope.LocationType = response.data.LocationType;
                $scope.HotelNameList = response.data.HotelName;
                $scope.StarRatingType = response.data.StarRatingType;
                setTimeout(function() {
                    $scope.Priceslider();
                    $scope.HotelNameAutocomplete();
                }, 100);
            } else {
                $scope.errormessage = $scope.ErrorMessage;
            }
        }, function myError(response) {
            $scope.errormessage = "Something went wrong";
        });

             /** Block  Hotel start **/
            $scope.blockHotel = function (event) {
            let text = "You Want to Block This Hotel \nEither Press OK or Cancel.";
            if (confirm(text) == true) {
               let hotelInfo = {"HotelCode":event.hotel.HotelCode,"HotelName":event.hotel.HotelName,"CityName": $scope.CityName,"CityId": $scope.CityId};
               $("#blockHotelButton"+event.hotel.HotelCode).attr('disabled',true).text('Wait..');
               $http({
                        method: "POST",
                        url: url + 'hotel/block-hotel',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: $.param(hotelInfo)
                    }).then(function mySuccess(blockhotel) {
                        if (blockhotel.data.StatusCode== 0) {
                        $("#hoteCode"+event.hotel.HotelCode).remove();
                        }
                        else{
                            alert("Error occured");
                        }
                    }, function myError(blockhotel) {
                        alert("Error occured");
                    });
            } 
        }
        /** Block  Hotel End **/
        /** Image no found Start **/
          $scope.imgError = function(thisval) {
            thisval.src = "<?php echo site_url(); ?>webroot/img/no-photo.png";
        }
        /** Image no found End **/

      /**Filter Code Start Here*/
      $scope.orderby = function(field, order = null, event = null) {
            if (order === null) {
                $scope.reverse = ($scope.field === field) ? !$scope.reverse : true;
                $scope.field = field;
            } else {
                $scope.reverse = order;
                $scope.field = field;
                $scope.topsortingtext = event.target.innerHTML;
            }
        }
        $scope.multiFilter = function(array, filters) {
            var filterKeys = Object.keys(filters);
            return array.filter((item) => {
                return filterKeys.every(key => !!~filters[key].indexOf(item[key]));
            });
        }
        let FilterHotelNameList = [];
        $scope.doFilter = function(type, event, key) {
           
            if (type == "LocationType") {
                if (event.target.checked) {
                    $scope.LocationType[key]['isChecked'] = true;
                } else {
                    $scope.LocationType[key]['isChecked'] = false;
                }
            }
            if (type == "StarRatingType") {
                if (event.target.checked) {
                    $scope.StarRatingType[key]['isChecked'] = true;
                } else {
                    $scope.StarRatingType[key]['isChecked'] = false;
                }
            }

						if(type=='HotelName')
						{
                            FilterHotelNameList = [];
							  if(FilterHotelNameList!=="" || FilterHotelNameList!==null) {
							    FilterHotelNameList.push(key);
								
							  } else {
								FilterHotelNameList=[];
							  }
						}
            let filters = [];
            let filteredResult = [];
            let LocationType = [];
            let StarRatingType = [];
           
            LocationType = $scope.checkedfilter($scope.LocationType);
            StarRatingType = $scope.checkedfilter($scope.StarRatingType);

            if (LocationType.length !== 0) {
                filters['HotelLocation'] = LocationType;
            }
            if (StarRatingType.length !== 0) {
                filters['StarRating'] = StarRatingType;
            }
            if (FilterHotelNameList.length !== 0) {
                filters['HotelName'] = FilterHotelNameList;
            }
            if (type == 'clear') {
                filters = [];
            }
            filteredResult = $scope.multiFilter($scope.FilterResults, filters);
            if (filteredResult.length == 0) {
                $scope.FliterNoHotel = true;
            } else {
                $scope.FliterNoHotel = false;
            }
            $scope.FilteredResultCount = filteredResult.length;
            $scope.Results = filteredResult;
            $scope.limit = limitStep;
            if(type=='HotelName')
						{
            $scope.$digest();
                        }
        }

        $scope.checkedfilter = function(array) {
            let response = [];
            array.forEach(function(value, key) {
                if (value.isChecked) {
                    response.push(value.label);
                }
            });
            return response;
        }
        $scope.resetfilter = function(array) {
            let response = [];
            array.forEach(function(value, key) {
                value.isChecked = false;
                response.push(value);
            });
            return response;
        }
        $scope.clearFilterAll = function(type) {
            if (type == 'all') {
                $scope.resetfilter($scope.StarRatingType);
                $scope.resetfilter($scope.LocationType);
                FilterHotelNameList = [];
                angular.element($("[hotel_name_search_text]").val(''));
                angular.element($(".price-range").slider("values", 0, $scope.MinPrice));
                angular.element($(".price-range").slider("values", 1, $scope.MaxPrice));
                angular.element($(".left-pricel").val($scope.MinPrice));
                angular.element($(".right-price").val($scope.MaxPrice));
                $scope.Priceslider();
            }
            $scope.doFilter('clear', '', '');
            $scope.limit = limitStep;
        }
        $scope.Priceslider = function() {
            $ = jQuery.noConflict();
            price_min = $scope.MinPrice;
            price_max = $scope.MaxPrice;
            var step=0.01;
            $(".price-range").slider({
                range: true,
                min: price_min,
                max: price_max,
                step: parseFloat(step),
                values: [price_min, price_max],
                slide: function(event, ui) {
                    $(".left-price").val(ui.values[0].toString());
                    $(".right-price").val(ui.values[1].toString());
                },
                stop: function(event, ui) {
                    var min = ui.values[0];
                    var max = ui.values[1];
                    let filters = [];
                    let filteredResult = [];
                    let LocationType = [];
                    let StarRatingType = [];
                    LocationType = $scope.checkedfilter($scope.LocationType);
                    StarRatingType = $scope.checkedfilter($scope.StarRatingType);
                    if (LocationType.length !== 0) {
                        filters['HotelLocation'] = LocationType;
                    }
                    if (StarRatingType.length !== 0) {
                        filters['StarRating'] = StarRatingType;
                    }
                    if (FilterHotelNameList.length !== 0) {
                    filters['HotelName'] = FilterHotelNameList;
                    }
                    var filtered = $scope.multiFilter($scope.FilterResults, filters);
                    angular.forEach(filtered, function(item) {
                        if (item.Price.PublishedPrice >= min && item.Price.PublishedPrice <= max) {
                            filteredResult.push(item);
                        }
                    });
                    $scope.FilteredResultCount = filteredResult.length;
                    if (filteredResult.length == 0) {
                        $scope.FliterNoHotel = true;
                    } else {
                        $scope.FliterNoHotel = false;
                    }

                    $scope.limit = limitStep;
                    $scope.Results = filteredResult;
                    $scope.$evalAsync();
                }
            });
            $(".left-price").val($(".price-range").slider("values", 0).toString());
            $(".right-price").val($(".price-range").slider("values", 1).toString());
        }
        $scope.HotelNameAutocomplete = function() {
            $("[hotel_name_search_text]").autocomplete({
								 source: $scope.HotelNameList,
								 minLength: 2,
								 open: function() { 
										$(".ui-autocomplete").css({"max-height": 250,"max-width": 229,"overflow":"scroll","overflow-x": "hidden","z-index": 99999});
									},
								 select: function(event, ui) { 							
									angular.element(document.querySelectorAll('input[hotel_name_search_text=true]')).scope().doFilter('HotelName','',ui.item.label);
								  }
							});	

        }
        /**Filter Code End Here*/

    });

    app.directive("scroll", function($window) {
        return function(scope, element) {
            angular.element($window).bind("scroll", function() {
                var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
                var body = document.body,
                    html = document.documentElement;
                var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
                windowBottom = windowHeight + window.pageYOffset + 1000;
                if (windowBottom >= docHeight) {
                    scope.limit += limitStep;
                }
                scope.$apply();
            });
        };
    });

    app.filter('rtrim', function() {
        return function(value) {
            return value.replace(/, \W+/g, '');
        }
    });
    app.filter("trusthtml", ['$sce', function($sce) {
        return function(htmlCode) {
            return $sce.trustAsHtml(htmlCode);
        }
    }]);
</script>
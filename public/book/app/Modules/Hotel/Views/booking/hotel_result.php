<main ng-app="hotelApp" ng-controller="hotelCtrl" scroll>
    <div class="container" ng-if="loading==true">
        <?php echo view('Modules/Hotel/Views\booking\loading-page.php'); ?>
    </div>
    <section class="content ng-cloak " ng-if="loading==false">
        <div class="container">
            <?php echo view('Modules/Hotel/Views\booking\modify_search'); ?>
            <div class="row">
                <!-- filter starts here -->
                <div class="col-xl-3 col-lg-3 theiaStickySidebar" filter-content-action="true" ng-if="ErrorCode==0">
                    <?php echo view('Modules/Hotel/Views\booking\hotel_filter'); ?>
                </div>
                <!-- filter ends here -->
                <div class="col-xl-9 col-lg-9" ng-if="ErrorCode==0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <h6 class="mb-3">Showing Result {{FilteredResultCount}} of {{ TotalResult }} Hotels</h6>
                    </div>
                    <div class="sorting-list mb-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <a href="javascript:void(0);">Sort By</a>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <a href="javascript:void(0);" ng-click="orderby('HotelName')"><span>Hotel Name</span>
                                                    <span class="fa" ng-show="field === 'HotelName'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                </a>
                                            </div>
                                            <div class="text-center">
                                                <a href="javascript:void(0);" ng-click="orderby('StarRating')"><span>Rating</span>
                                                    <span class="fa" ng-show="field === 'StarRating'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0);" ng-click="orderby('PublishedPrice')" class="me-2"><span>Price</span>
                                                    <span class="fa" ng-show="field === 'PublishedPrice'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-list">
                        <div class="row justify-content-center">
                            <div class="col-md-12" ng-repeat="(key,hotel)  in Results | orderBy:field:reverse | limitTo :limit">
                                <div class="hotel-item">
                                    <div class="hotel-img">
                                        <img ng-if="hotel.HotelPicture=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="<?php echo site_url(); ?>webroot/img/no-photo.png" alt="{{hotel.HotelName}}" class="img-fluid">
                                        <img ng-if="hotel.HotelPicture!=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="{{ hotel.HotelPicture}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotel.HotelName}}" class="img-fluid">
                                        <div class="Recommended" ng-if="hotel.RecommendHotel">Recommended</div>
                                    </div>
                                    <div class="hotel-content">
                                        <h6 class="text-truncate hotel-title" title="{{hotel.HotelName}}">{{hotel.HotelName}}</h6>
                                        <p title="{{hotel.HotelAddress|rtrim}}"><i class="far fa-location-dot text-danger"></i> {{hotel.HotelAddress|rtrim}}</p>
                                        <div class="hotel-rate">
                                            <p class="text-warning">
                                                <i class="fa fa-star" ng-if="hotel.StarRating>=1"></i>
                                                <i class="fa fa-star" ng-if="hotel.StarRating>=2"></i>
                                                <i class="fa fa-star" ng-if="hotel.StarRating>=3"></i>
                                                <i class="fa fa-star" ng-if="hotel.StarRating>=4"></i>
                                                <i class="fa fa-star" ng-if="hotel.StarRating>=5"></i>
                                            </p>
                                            <a ng-if="hotel.HotelReviewUrl && hotel.HotelReviewUrl!=''" href="{{hotel.HotelReviewUrl}}">Review Rating {{hotel.HotelReviewRatings}}</a>
                                            <a ng-if="hotel.HotelReviewUrl && hotel.HotelReviewUrl==''" href="javascript:void(0)">Review Rating {{hotel.HotelReviewRatings}}</a>
                                        </div>
                                        <!-- <span class="hotel-price-amount" ng-if="hotel.RecommendHotel">
                                            <span class="hotel-price-type">Promoted Hotel</span> 
                                            {{hotel.RecommendHotel}}
                                        </span> -->

                                        <p data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{hotel.HotelDescription|trusthtml}}" ng-if="hotel.HotelDescription!='' || hotel.HotelDescription!=Null">{{ hotel.HotelDescription | limitTo: hoteldescriptionlimitstep|trusthtml }} {{hotel.HotelDescription.length < hoteldescriptionlimitstep ? '' : '...'}} </p>
                                        <ul class="hotel-amenities" ng-if="hotel.HotelAmenities && hotel.HotelAmenities.length>0">
                                            <h6 class="mb-0">Facillities :</h6>
                                            <li ng-repeat="hotelAmenities in hotel.HotelAmenities| limitTo :5"> 
                                                <img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid tts_hotel_amenities_icon">
                                                <span>{{hotelAmenities.Name}} </span>
                                            </li>
                                        </ul>
                                        
                                        <div class="hotel-bottom">
                                            <div class="hotel-price">
                                                <span class="hotel-price-amount">{{CurrencySymbol}} {{hotel.Price.OfferedPrice}} <span class="hotel-price-type">/Per Night</span></span>
                                                <span class="hotel-price-amount" ng-if="shownetfare">{{CurrencySymbol}} {{hotel.Price.OfferedPrice}} <span class="hotel-price-type">/Per Night</span></span>
                                               
                                            </div>

                                            <div class="hotel-text-btn">
                                                <a href="<?php echo site_url('hotel/hotel-rooms'); ?>?rindex={{hotel.ResultIndex}}&token={{token}}&hcode={{hotel.HotelCode}}" target="_blank">Select Room <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-sm-12 mt-2">
                            <div class="hotal-filter-card mt-3" ng-repeat="(key,hotel)  in Results | orderBy:field:reverse | limitTo :limit">
                                <div class="col-lg-12 col-md-12">
                                    <div class="hotel_box">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 mb-lg-0 mb-3">
                                                <img ng-if="hotel.HotelPicture=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="<?php echo site_url(); ?>webroot/img/no-photo.png" alt="{{hotel.HotelName}}" class="image">
                                                <img ng-if="hotel.HotelPicture!=='http://images.cdnpath.com/Images/HotelNA.jpg'" ng-src="{{ hotel.HotelPicture}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotel.HotelName}}" class="img-fluid rounded" style="height:150px; width:100%; object-fit:cover">
                                            </div>
                                            <div class="col-lg-7 col-md-8 col-12 mb-lg-0 mb-3">
                                                <div class="hotel-box-body text-left">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h5 class="hotel-title" title="{{hotel.HotelName}}">{{hotel.HotelName}}</h5>
                                                        <span>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=1"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=2"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=3"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=4"></i>
                                                            <i class="fa fa-star" ng-if="hotel.StarRating>=5"></i>
                                                        </span>
                                                        <a ng-if="hotel.HotelReviewUrl && hotel.HotelReviewUrl!=''" href="{{hotel.HotelReviewUrl}}">Review Rating {{hotel.HotelReviewRatings}}</a>
                                                        <a ng-if="hotel.HotelReviewUrl && hotel.HotelReviewUrl==''" href="javascript:void(0)">Review Rating {{hotel.HotelReviewRatings}}</a>
                                                    </div>
                                                    <a title="{{hotel.HotelAddress|rtrim}}"><i class="fa fa-map-marker"></i> {{hotel.HotelAddress|rtrim}}</a>
                                                    <ul class="d-flex align-items-center my-2" ng-if="hotel.HotelAmenities && hotel.HotelAmenities.length>0">
                                                        <li ng-repeat="hotelAmenities in hotel.HotelAmenities| limitTo :5"> <img ng-if="hotelAmenities.Icon!==''" ng-src="{{hotelAmenities.Icon}}" onerror="angular.element(this).scope().imgError(this)" alt="{{hotelAmenities.Name}}" class="img-fluid rounded tts_hotel_amenities_icon">{{hotelAmenities.Name}} </li>
                                                    </ul>
                                                    <span ng-if="hotel.HotelPromotion!=''" class="badge bg-filter mt-1 ml-0 tts_white_space text-start">{{ hotel.HotelPromotion |trusthtml }}</span>
                                                    <span class="d-block" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{hotel.HotelDescription|trusthtml}}" ng-if="hotel.HotelDescription!='' || hotel.HotelDescription!=Null">{{ hotel.HotelDescription | limitTo: hoteldescriptionlimitstep|trusthtml }} {{hotel.HotelDescription.length < hoteldescriptionlimitstep ? '' : '...'}} </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-12 text-lg-end">
                                                <div class="hotal_price d-flex justify-content-between flex-lg-column h-100">
                                                    <div>
                                                        <h4 class="tts__flight__price">{{CurrencySymbol}} {{hotel.Price.OfferedPrice}} </h4>
                                                        <h4 class="tts__flight__price text-success" ng-if="shownetfare"> {{CurrencySymbol}} {{hotel.Price.OfferedPrice}} </h4>
                                                        <div class="small">Per person</div>
                                                    </div>
                                                    <div>
                                                        <a class="btn flight-book" href="<?php echo site_url('hotel/hotel-rooms'); ?>?rindex={{hotel.ResultIndex}}&token={{token}}&hcode={{hotel.HotelCode}}" target="_blank">
                                                            Select Room
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-----Filter NoHotel ------------>
                    <div class="no-hotel tts-api-error-msg mt-4" ng-if="FliterNoHotel">
                        <p class="mt-4">No hotel found that matches your filter criteria.Reset your filter </p>
                        <button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll('all')">Reset All Filter</button>
                    </div>
                    <!-----Filter NoHotel ------------>
                </div>
                <!-- hotel result end here -->
                <!-------------API Error  ------------>

                <div class="col-12 ">
                    <div class="tts-api-error-msg" ng-if="ErrorCode!=0">
                        <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
                        <h5 class="mt-4"> {{errormessage}}</h5>
                        <p class="mb-2">Sorry,No hotels found for your selected date. Please change your dates / search criteria.</p>
                        <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
                    </div>
                </div>

                <!-------------API Error  ------------>
            </div>
        </div>
    </section>
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
        $scope.CurrencySymbol = '';
        $scope.CurrencyCode = '';
        $http({
            method: "GET",
            url: url + "hotel/get-hotel-lists?" + "<?php echo http_build_query($_GET); ?>"
        }).then(function mySuccess(response) {
            $scope.loading = false;
            $scope.ErrorCode = response.data.Error.ErrorCode;
            $scope.ErrorMessage = response.data.Error.ErrorMessage;
            if ($scope.ErrorCode == 0) {
                $scope.Results = response.data.Result;
                $scope.FilterResults = response.data.Result;
                $scope.CurrencySymbol = response.data.CurrencySymbol;
                $scope.CurrencyCode = response.data.CurrencyCode;
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

            if (type == 'HotelName') {
                FilterHotelNameList = [];
                if (FilterHotelNameList !== "" || FilterHotelNameList !== null) {
                    FilterHotelNameList.push(key);

                } else {
                    FilterHotelNameList = [];
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
            if (type == 'HotelName') {
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
            var step = 0.01;
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
                    $(".ui-autocomplete").css({
                        "max-height": 250,
                        "max-width": 229,
                        "overflow": "scroll",
                        "overflow-x": "hidden",
                        "z-index": 99999
                    });
                },
                select: function(event, ui) {
                    angular.element(document.querySelectorAll('input[hotel_name_search_text=true]')).scope().doFilter('HotelName', '', ui.item.label);
                }
            });

        }
        /**Filter Code End Here*/
        $scope.showHideMobileFilter = function(type) {
            if (type == 'show') {
                $("[filter-content-action]").addClass('mobile-sidebar');
            } else {
                $("[filter-content-action]").removeClass('mobile-sidebar');
            }
        }

        $scope.showHideMobilemodify = function(type) {
            if (type == 'show') {
                $("[modify-content-action]").addClass('mobile-sidebar');
            } else {
                $("[modify-content-action]").removeClass('mobile-sidebar');
            }
        }
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
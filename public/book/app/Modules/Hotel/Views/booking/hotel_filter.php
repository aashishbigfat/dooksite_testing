<div class="close-btn" ng-click="showHideMobileFilter('hide')">
    <i class="fa fa-close"></i>
</div>
<div class="tts__filter booking-sidebar">
    <div class="widget filters">
        <h2 class="title"><i class="fa-solid fa-filter"></i> Filter Results</h2>
        <span><a href="javascript:void(0);" ng-click="clearFilterAll('all')" class="tts__fliter__reset">Reset All</a></span>
    </div>
    <div class="widget">
        <div class="filters-wrap">
            <h2 class="widget-title">Price</h2>
            <div class="price_filter">
                <div class="price-range price-filter"></div>
            </div>
            <div class="price_slider_amount">
                <input type="text" class="left-price" readonly>
                <input type="text" class="right-price text-end" readonly>
            </div>
        </div>
    </div>
    <div class="widget">
        <h2 class="widget-title">Hotel Name</h2>
        <input type="text" class="form-control form-control1" placeholder="Hotel Name" hotel_name_search_text="true">
    </div>
    <div class="widget">
        <h2 class="widget-title">Star Rating</h2>
        <div class="d-flex align-items-center " ng-repeat="(key, item) in StarRatingType">
            <label class="me-2 d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" type="checkbox" id="StarRatingType{{key}}" ng-click="doFilter('StarRatingType',$event,item.key);" ng-model="item.isChecked">
                <i class="fa fa-star" ng-if="item.label>=1"></i>
                <i class="fa fa-star" ng-if="item.label>=2"></i>
                <i class="fa fa-star" ng-if="item.label>=3"></i>
                <i class="fa fa-star" ng-if="item.label>=4"></i>
                <i class="fa fa-star" ng-if="item.label>=5"></i>
                <b ng-if="item.label==0">No Rating</b>
            </label>
        </div>
    </div>
    <div class="widget">
        <h2 class="widget-title">Locations</h2>
        <div class="form-check" ng-repeat="(key, item) in LocationType">
            <label class="form-check-label" for="LocationType{{key}}">
                <input class="form-check-input" type="checkbox" id="LocationType{{key}}" ng-click="doFilter('LocationType',$event,item.key);" ng-model="item.isChecked"> {{item.label}}
            </label>
        </div>
    </div>
</div>
<div class="hotal-filter">
<div class="d-flex justify-content-between p-2">
        <span><b>Filter Results</b></span>
        <span><a href="javascript:void(0);"  ng-click="clearFilterAll('all')" class="tts__fliter__reset">Reset All</a></span>
    </div>
    <div class="tts__filter__heading p-2">
        <span><b>Price</b></span>
    </div>
    <style>
        p.tts-price-range input[type=text] {
            border: none;
            width: 50%;
            color: #111;
            float: left;
            font-size: 13px;
            box-shadow: unset!important;
            outline: 0;
            font-weight: 500;
        }
        div.price-filter {
            margin-top: 25px;
            margin-bottom: 10px;
            border: none!important;
            max-height: 6px;
            background-color: #e6e2e2;
            max-width: 92%;
            margin-left: 8px;
        }
    </style>
    <div class="p-2">
            <p class="tts-price-range">
                <input type="text" class="left-price" readonly>
                <input type="text" class="right-price text-end" readonly>
            </p>
            <div class="price-range price-filter"></div>
    </div>
    <label  class="form-label"> Hotel Name</label>
    <input type="text" class="form-control form-control1"  placeholder="Hotel Name" hotel_name_search_text =  "true">
    <hr>
    <label  class="form-label"> Star Rating</label>
        <div class="d-flex align-items-center "   ng-repeat="(key, item) in StarRatingType">
            <label class="hotel-filter-rating me-2">
                <input class="form-check-input me-2" type="checkbox" type="checkbox" id="StarRatingType{{key}}"  ng-click="doFilter('StarRatingType',$event,item.key);" ng-model="item.isChecked">
                <i class="fa fa-star" ng-if = "item.label>=1"></i>
                <i class="fa fa-star"  ng-if = "item.label>=2"></i>
                <i class="fa fa-star"  ng-if = "item.label>=3"></i>
                <i class="fa fa-star"  ng-if = "item.label>=4"></i>
                <i class="fa fa-star"  ng-if = "item.label>=5"></i>
                <b ng-if = "item.label==0">No Rating</b>
            </label>
        </div>  
         <hr>
        <label class="form-label"> Locations </label>
            <div class="form-check"    ng-repeat="(key, item) in LocationType">
                <label class="form-check-label" for="LocationType{{key}}" >
                <input class="form-check-input" type="checkbox" id="LocationType{{key}}"  ng-click="doFilter('LocationType',$event,item.key);" ng-model="item.isChecked">   {{item.label}}
                  </label>      
            </div>
            
       
</div>
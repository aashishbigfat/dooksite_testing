<div class="close-btn" ng-click="showHideMobileFilter('hide')">
    <i class="fa fa-close"></i>
</div>
<div class="widget" ng-if="FlightFilter.length!=1">
    <nav>
        <div class="nav nav-tabs border-0 justify-content-center pt-2" id="nav-tab" role="tablist">
            <button class="btn btn-outline-dark btn-sm me-3 active" id="nav-filterob-tab" data-bs-toggle="tab" data-bs-target="#nav-filterob" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Outbound</button>
            <button class="btn btn-outline-dark btn-sm" id="nav-filterib-tab" data-bs-toggle="tab" data-bs-target="#nav-filterib" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Inbound</button>
        </div>
    </nav>
</div>
<div class="tts__filter booking-sidebar">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-filterob" role="tabpanel" aria-labelledby="nav-filterob-tab">
            <div class="widget filters">
                <h2 class="title"><i class="fa-solid fa-filter"></i> Filter Results</h2>
                <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(0,'all')">Reset All</a></span>
            </div>
            <div class="widget">
                <div class="filters-wrap">
                    <h2 class="widget-title">Price</h2>
                    <div class="price_filter">
                        <div class="price-range price-filter"></div>
                    </div>
                    <div class="price_slider_amount">
                        <input type="text" class="left-price" readonly="">
                        <input type="text" class="right-price text-end" readonly="">
                    </div>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Fare Type</h2>
                <div class="form-check d-flex align-items-center ps-0 mb-2" ng-repeat="(key,item) in FlightFilter[0]['FareType']">
                    <input type="checkbox" class="form-check-input ms-0 mt-0" name="fare_type" id="fareType-{{key}}" value="{{item['value']}}" ng-click="doFilter(0,'FareType',$event,item);" ng-model="item.isChecked">
                    <label class="form-check-label ms-2" for="fareType-{{key}}">{{item['label']}}</label>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Stop</h2>
                <div class="form-check d-flex align-items-center ps-0 mb-2" ng-repeat="(key,item) in FlightFilter[0]['Stop']">
                    <input type="checkbox" class="form-check-input ms-0 mt-0" name="stop" id="stop-{{key}}" value="{{item['value']}}" ng-click="doFilter(0,'Stop',$event,item);" ng-model="item.isChecked">
                    <label class="form-check-label ms-2" for="stop-{{key}}">{{item['label']}}</label>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Departure Times</h2>
                <div class="flight-time">
                    <div class="form-check" ng-repeat="(key,item) in FlightFilter[0]['DepartTime']">
                        <label class="form-check-label" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="form-check-input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(0,'DepartTime',$event,item);" ng-model="item.isChecked" />
                            <!--    <img ng-if="item['icon']" src="{{item['icon']}}"> {{item['label']}} -->
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}" alt="{{item['label']}}"> {{item['label']}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Arrival Times</h2>
                <div class="flight-time">
                    <div class="form-check" ng-repeat="(key,item) in FlightFilter[0]['ArrivalTime']">
                        <label class="form-check-label" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="form-check-input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(0,'ArrivalTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}">
                            {{item['label']}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Airlines</h2>
                <div class="form-check" ng-repeat="(key,item) in FlightFilter[0]['Airline']">
                    <input type="checkbox" class="form-check-input" name="airline" id="airline-{{key}}" value="{{item['value']}}" ng-click="doFilter(0,'Airline',$event,key);" ng-model="item.isChecked">
                    <label class="form-check-label " for="airline-{{key}}">{{item['label']}}
                    <span class="airline-logo">
                            <img ng-src="{{'<?php echo site_url('uploads/airline-images/'); ?>' + item['value'].trim() + '.png' }}" alt="{{ item['value'] }}">
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-----Inbound------>
        <div class="tab-pane fade" id="nav-filterib" role="tabpanel" aria-labelledby="nav-filterib-tab">
            <div class="widget filters">
                <h2 class="title">Filter Results</h2>
                <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(1,'all')">Reset All</a></span>
            </div>
            <div class="widget">
                <div class="filters-wrap">
                    <h2 class="widget-title">Price</h2>
                    <div class="price_filter">
                        <div class="price-range-return price-filter"></div>
                    </div>
                    <div class="price_slider_amount">
                        <input type="text" class="left-price-return" readonly />
                        <input type="text" class="right-price-return text-end" readonly />
                    </div>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Fare Type</h2>
                <div class="form-check d-flex align-items-center ps-0 mb-2" ng-repeat="(key,item) in FlightFilter[1]['FareType']">
                    <input type="checkbox" class="form-check-input ms-0 mt-0" name="fare_type" id="fareType1-{{key}}" value="{{item['value']}}" ng-click="doFilter(1,'FareType',$event,item);" ng-model="item.isChecked">
                    <label class="form-check-label ms-2" for="fareType1-{{key}}">{{item['label']}}</label>
                </div>
            </div>

            <div class="widget">
                <h2 class="widget-title">Stop</h2>
                <div class="form-check d-flex align-items-center ps-0 mb-2" ng-repeat="(key,item) in FlightFilter[1]['Stop']">
                    <input type="checkbox" class="form-check-input ms-0 mt-0" name="stop" id="stop1-{{key}}" value="{{item['value']}}" ng-click="doFilter(1,'Stop',$event,item);" ng-model="item.isChecked">
                    <label class="form-check-label ms-2" for="stop1-{{key}}">{{item['label']}}</label>
                </div>
            </div>

            <div class="widget">
                <h2 class="widget-title">Departure Times</h2>
                <div class="flight-time">
                    <div class="form-check" ng-repeat="(key,item) in FlightFilter[1]['DepartTime']">
                        <label class="form-check-label" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="form-check-input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'DepartTime',$event,item);" ng-model="item.isChecked" />
                            <!--    <img ng-if="item['icon']" src="{{item['icon']}}"> {{item['label']}} -->
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}" alt="{{item['label']}}"> {{item['label']}}
                        </label>
                    </div>
                </div>
            </div>

            <div class="widget">
                <h2 class="widget-title">Arrival Times</h2>
                <div class="flight-time">
                    <div class="form-check" ng-repeat="(key,item) in FlightFilter[1]['ArrivalTime']">
                        <label class="form-check-label" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="form-check-input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'ArrivalTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}">
                            {{item['label']}}
                        </label>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h2 class="widget-title">Airlines</h2>
                <div class="form-check" ng-repeat="(key,item) in FlightFilter[1]['Airline']">
                    <input type="checkbox" class="form-check-input" name="airline" id="airline1-{{key}}" value="{{item['value']}}" ng-click="doFilter(1,'Airline',$event,key);" ng-model="item.isChecked">
                    <label class="form-check-label w-100" for="airline1-{{key}}">{{item['label']}}
                        <span class="airline-logo">
                            <img ng-src="{{'<?php echo site_url('uploads/airline-images/'); ?>' + item['value'].trim() + '.png' }}" alt="{{ item['value'] }}">
                        </span>
                    </label>

                </div>
            </div>
            <!-- <div class="widget">
                <h2 class="widget-title">Fare Type</h2>
                <ul class="airlines-cat-list">
                    <li ng-repeat="(key,item) in FlightFilter[1]['FareType']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="fare_type" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'FareType',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div> -->
            <!-- <div class="widget">
                <h2 class="widget-title">Stop</h2>
                <ul class="airlines-cat-list">
                    <li ng-repeat="(key,item) in FlightFilter[1]['Stop']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="stop" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'Stop',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div> -->
            <!-- <div class="widget">
                <h2 class="widget-title">Departure Times</h2>
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['DepartTime']">
                        <label class="tts__checkbox__label tts__flight__timing" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'DepartTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div> -->
            <!-- <div class="widget">
                <h2 class="widget-title">Arrival Times</h2>
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['ArrivalTime']">
                        <label class="tts__checkbox__label tts__flight__timing" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'ArrivalTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" ng-src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div> -->
            <!-- <div class="widget">
                <h2 class="widget-title">Airlines</h2>
                <ul class="airlines-cat-list">
                    <li ng-repeat="(key,item) in FlightFilter[1]['Airline']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="airline" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'Airline',$event,key);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                            <span class="float-end {{airlineLogoClass}} size-18 x{{item['value']}}"></span>
                        </label>
                    </li>
                </ul>
            </div> -->
        </div>
    </div>
</div>
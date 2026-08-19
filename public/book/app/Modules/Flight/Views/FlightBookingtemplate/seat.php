<style>
    .selectedseat {
        color: #4caf50 !important;
        background: #4caf502b !important;
    }

    .flightboxmain_seatmain {
        margin: 23px 0;
    }

    .seatmapdiv {
        display: flex;
        font-size: 15px;
        justify-content: space-between;
        font-weight: 500;
    }

    .flightboxmain_seatmain1 {
        cursor: pointer;
        position: relative;
        flex-direction: column-reverse;
        width: 33px;
        height: 31px;
        padding: 0;
        margin-right: 5px;
        margin-bottom: 20px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-weight: 500;
        border: 1px solid;
    }

    .flightboxmain_seatmain1::before,
    .seatlayoutbox ul li::before {
        /*content: "";
        display: block;
        width: 26px;
        height: 56px;
        border: 1px solid;
        margin: 11px auto 0;
        border-bottom: none;
        border-radius: 5px 5px 0 0;
        transform: none;*/
    }

    .flightboxmain_seatmain2 {
        color: #2196f3;
    }

    .flightboxmain_seatmain3 {
        color: #f44336;
    }

    .flightboxmain_seatmain4 {
        color: #4caf50;
    }

    .innerrow h2 {
        font-size: 13px;
        text-transform: capitalize;
        border: 1px solid #ccc;
        color: #9e9e9e;
        padding: 0 11px;
        font-weight: 500;
        border-radius: 5px;
        margin-top: 12px;
        margin-bottom: 12px;
    }

    .switchfilterbox {
        box-shadow: 0 1px 12px 0 rgba(0, 0, 0, 0.12);
        border: 1px solid #f3f3f3;
        background-color: #fff;
        margin: 20px 0px;
        position: relative;
        float: left;
    }

    .depart_ssr_seat {
        background: #2196f30f;
        float: left;
        width: 100%;
        padding: 20px;
    }

    .adi-full {
        width: 100%;
        float: left;
    }

    .seatlayoutbox {
        display: flex;
        padding-top: 20px;
        flex-wrap: nowrap;
    }

    .flightbox {
        margin: 0 auto;
        position: relative;
    }

    .bgProperties {
        background-repeat: no-repeat;
        background-position: center;
        display: inline-block;
        background-size: cover;
    }

    .iconflFront {
        width: 100%;
        height: 232px;
    }

    .flightboxplan {
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        border: 0 solid #ccc;
        width: 100%;
        border-top: 0;
        margin: -11px 0;
        padding: 20px;
        background: #fff;
    }

    .flightdetails {
        font-size: 16px;
    }

    .baggagesegement ul {
        margin: 0;
        float: left;
        width: 100%;
        border-bottom: 1px solid #ccc;
        padding: 0;
        list-style: none;
    }

    .seatrow {
        display: flex;
        border-bottom: 0 !important;
        padding: 0px;
    }

    .baggagesegement ul li {
        width: auto;
        float: left;
        text-align: center;
        padding: 0 0 11px;
    }

    .seatlayoutbox ul li {
        cursor: pointer;
        position: relative;
        flex-direction: column-reverse;
        width: 33px;
        height: 31px;
        padding: 0;
        margin-right: 5px;
        margin-bottom: 15px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-weight: 500;
        background: #03a9f408;
        border: 1px solid;
        color: #2196f3;
    }

    .flightboxmain_seatmain1::before,
    .seatlayoutbox ul li::before {
        /* content: "";
        display: block;
        width: 26px;
        height: 56px;
        border: 1px solid;
        margin: 11px auto 0;
        border-bottom: none;
        border-radius: 5px 5px 0 0;
        transform: none;*/
    }

    .seatlayoutbox ul li::after {
        position: relative;
        content: attr(data-letter);
        font-family: tahoma;
        top: -25px;
    }

    .booked {
        background: #f443361c !important;
        cursor: not-allowed !important;
        color: #f44336 !important;
        border: 1px solid !important;
    }

    .segmetsetnum {
        position: absolute;
        /*top: -19px;*/
        color: #000;
        font-weight: 500;
        padding: 10px;
    }

    .seatdetails-tooltip {
        box-shadow: rgb(0 0 0 / 14%) 0 2px 2px 0, rgb(0 0 0 / 12%) 0 1px 5px 0, rgb(0 0 0 / 20%) 0 3px 1px -2px;
        font-weight: 500;
        margin-top: 23px;
        width: max-content;
        display: none;
        z-index: 10;
        border-radius: 5px;
        background: #000;
        position: absolute;
        top: 9px;
        font-size: 12px;
        color: #ffff;
        padding: 10px;
    }

    .seatdetails {
        box-shadow: rgb(0 0 0 / 14%) 0 2px 2px 0, rgb(0 0 0 / 12%) 0 1px 5px 0, rgb(0 0 0 / 20%) 0 3px 1px -2px;
        font-size: 14px;
        color: #000;
        font-weight: 700;
        margin-top: 23px;
        width: 240px;
        z-index: 10;
        border-radius: 5px;
        background: #fff;
        position: absolute;
        top: 9px;
    }

    .seat-header {
        padding: 10px;
        background: #000;
        color: #fff;
    }

    .seat-body {
        background: #fff;
        box-shadow: 0 4px 26px 0 rgba(60, 60, 60, 0.5);
    }

    .seat-body .seat-li {
        padding: 12px 18px;
        border-bottom: 1px solid #eeeeee;
        cursor: pointer;
    }

    .seat-body .seat-li i {
        margin-right: 5px;
        color: #e2e2e2;
        font-size: 18px;
    }

    /*.gallery {
        margin-left: 40px;
    }*/
    .gallery {
        margin-right: 25px !important;
    }

    .iconflTail {
        width: 100%;
        height: 385px;
        margin-top: -2px;
    }

    .seatinfo .seat-body:after {
        content: "";
        border-top: 14px solid #fff;
        border-left: 11px solid transparent;
        border-right: 11px solid transparent;
        position: absolute;
        bottom: -13px;
        left: 50%;
        transform: translateX(-50%);
    }

    .blankrow li,
    li.blankseat {
        visibility: hidden;
        width: 26px;
    }

    .blankrow li span,
    li.blankseat span {
        display: none;
    }

    .recline {
        padding-top: 10px;
    }

    .pax-seat-assign {
        color: green !important;
    }

    .tooltrip-close {
        background: red;
        color: #fff;
        border: 0px;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        padding: 0;
    }


    @media (max-width:480px) {
        .seatlayoutbox {
            flex-direction: column;
        }

        .seatdetails,
        .iconflFront,
        .flightboxplan {
            width: 300px;
        }


        .seatlayoutbox ul li {
            width: 22px;
            height: 22px;
            margin-bottom: 5px;
            margin-right: 5px;
            font-size: 8px;
        }

        .gallery {
            margin: 0;
        }
    }
</style>

<div class="bg-light p-0 p-lg-3">
    <nav class="nav nav-pills nav-justified">
        <div ng-repeat="(jkey, SegmentSeats)  in SeatData;" class="d-flex">
            <a class="nav-link" aria-current="page" href="javascript:void(0);" ng-repeat="(segkey, Segment)  in SegmentSeats;" ng-class="{'active':jkey+''+segkey==seatactivetab}" ng-click="selectseattab(jkey,segkey,Segment)"><span ng-if="jkey==0">Departure</span> <span ng-if="jkey==1">Return</span> : {{Segment.Origin}}-{{Segment.Destination}}</a>
        </div>
    </nav>

    <div ng-repeat="(jkey, SegmentSeats) in SeatData">
        <div class="seatlayoutbox seatlayoutbox_departure ng-scope" ng-repeat="(segkey, Segment)  in SegmentSeats;">
            <div class="flightboxmain" ng-if="seatactivetab==jkey+''+segkey">
                <div class="flightboxmain_seatmain">
                    <div class="seatmapdiv flightboxmain_seatmain2">
                        <div class="flightboxmain_seatmain1"></div>
                        <span>Available Seat</span>
                    </div>
                    <div class="seatmapdiv flightboxmain_seatmain3">
                        <div class="flightboxmain_seatmain1"></div>
                        <span>Booked Seat</span>
                    </div>
                    <div class="seatmapdiv flightboxmain_seatmain4">
                        <div class="flightboxmain_seatmain1"></div>
                        <span>Selected Seat</span>
                    </div>
                </div>
            </div>
            <div class="flightbox" ng-if="seatactivetab==jkey+''+segkey">
                <div class="seatdetails" ng-style="tootipstyle" style="display: none;" ng-click="$event.stopPropagation();">
                    <div class="seatinfo">
                        <div class="row seat-header">
                            <div class="col-5 text-start">Seat {{clickedseat['Code']}}</div>
                            <div class="col-5 text-end">{{CurrencySymbol}} {{clickedseat['Price'] | number:2}}</div>
                            <div class="col-2 text-end"><button type="button" class="tooltrip-close" ng-click="CLOSETootip()"> <i class="fa fa-times" aria-hidden="true"></i> </button></div>
                        </div>
                        <div class="seat-body row">

                            <div class="p-0" ng-repeat="(paxkey,detail) in travellerJson[jkey]">
                                <div class="col-12 seat-li" ng-repeat="(paxcountkey,paxdetail) in detail" ng-click="selectseat($event,clickedseat,paxdetail,Segment,jkey,segkey,paxkey,paxcountkey)">

                                    <span><i class="fa fa-check-circle" aria-hidden="true" ng-class="{'pax-seat-assign':paxdetail[Segment.Origin+'-'+Segment.Destination]['Code']}"></i>{{paxkey}}-{{paxcountkey}}</span>
                                    <span class="float-end" ng-if="paxdetail[Segment.Origin+'-'+Segment.Destination]['Code']"> {{paxdetail[Segment.Origin+'-'+Segment.Destination]['Code']}} | {{CurrencySymbol}} {{paxdetail[Segment.Origin+'-'+Segment.Destination]['Price'] | number:2 }}</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div><span class="bgProperties iconflFront" style="background-image: url('../webroot/img/flightSmallFront.png');"></span></div>
                <div class="flightboxplan">
                    <h1 class="flightdetails ng-binding">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{Segment.Origin}} to {{Segment.Destination}}</font>
                        </font>
                    </h1>
                    <div class="flgtrow mb-3">
                        <span class="leftwing"></span>
                        <div class="innerrow">
                            <h2>Front Entrance</h2>
                        </div>
                        <span class="rightwing"></span>
                    </div>
                    <div class="flgtrow ng-scope" ng-repeat="segmentul in Segment.ul">
                        <span class="leftwing"></span>
                        <div class="innerrow">
                            <ul class="seatrow">
                                <li ng-repeat="segmentli in segmentul"
                                    class="{{segmentli.SeatClass}} {{segmentli.uid}}"
                                    ng-class="{'selectedseat': paxseatselected[Segment.Origin+'-'+Segment.Destination].includes(segmentli.Code)}"
                                    ng-click="seatClicked($event,segmentli)">

                                    <span class="segmetsetnum">{{segmentli.Code}}</span>
                                    <div class="seatdetails-tooltip" style="display: none;" ng-click="$event.stopPropagation();">
                                        {{segmentli.Code}} | {{CurrencySymbol}} {{segmentli.Price | number:2}}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flgtrow">
                        <span class="leftwing"></span>
                        <div class="innerrow">
                            <h2>Back Entrance</h2>
                        </div>
                        <span class="rightwing"></span>
                    </div>
                </div>
                <div><span class="bgProperties iconflTail" style="background-image: url('../webroot/img/flightSmallTail.png');"></span></div>
            </div>
        </div>
    </div>
</div>
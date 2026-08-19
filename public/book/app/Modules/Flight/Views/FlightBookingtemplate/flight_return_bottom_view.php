<style>
    /* Flight List Section */
    .flight-roundtrip-bottom-area {
        background: #D3E2DD;
        position: sticky;
        bottom: 0;
        width: 100%;
        left: 0;
        z-index: 1;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    /* Airline Logo */
   .flight-roundtrip-bottom-area  .airline-logo {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-right: 10px;
        padding: 5px;
    }

   .flight-roundtrip-bottom-area  .airline-logo img{
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    /* Flight Info */
    .flight-roundtrip-bottom-area .asp-totalam {
        font-size: 14px;
        font-weight: 600;
        color: #333333;
    }

    .flight-roundtrip-bottom-area .bottom-gridcode {
        font-size: 12px;
        color: #333333;
        display: block;
    }

    /* Flight Details */
    .flight-roundtrip-bottom-area .ars-list {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 15px;
    }

    .flight-roundtrip-bottom-area .ars-list li {
        text-align: center;
    }

    .flight-roundtrip-bottom-area .ars-list p {
        font-size: 16px;
        font-weight: bold;
        color: #333333;
        margin: 0;
    }

    .flight-roundtrip-bottom-area .ars-list span {
        font-size: 14px;
        color: #333333;
    }

    /* Arrow Icon */
    .flight-roundtrip-bottom-area .ars-list i {
        font-size: 18px;
        color: #000000;
    }

    /* Price Display */
    .flight-roundtrip-bottom-area h5.whitecolor {
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
        transition: 0.3s ease-in-out;
    }

    .flight-roundtrip-bottom-area h5.whitecolor:hover {
        color: #218838;
        transform: scale(1.1);
    }

    /* Total Price */
    .flight-roundtrip-bottom-area h3.mb-0 {
        font-size: 20px;
        font-weight: bold;
        color: #b50000;
    }

    .flight-roundtrip-bottom-area .round_trip_book {
        padding: 8px 18px;
        font-size: 14px;
        font-weight: bold;
        color: var(--tts-buttton-txt);
        background: var(--tts-buttton-bg);
        border: none;
        border-radius: 5px;
        transition: 0.3s ease-in-out;
    }

    .flight-roundtrip-bottom-area .round_trip_book:hover {
        color: var(--tts-buttton-txt1);
        background: var(--tts-buttton-bg1);
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .flight-roundtrip-bottom-area .ars-list {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

    }
</style>
<!---------------flight--list-----end---------->
<section class="flight_list_asp-btm flight-roundtrip-bottom-area" ng-if="ErrorCode==0 && loading==false">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 d-flex align-items-center justify-content-between" ng-repeat="(segmentkey,trip) in selectedOnwardFlight['SegementInfo']">
                <div class="ars-listair d-flex align-items-center">
                    <div class="airline-logo">
                        <img ng-src="{{'<?php echo site_url('uploads/airline-images/'); ?>' + trip.Airlinecode.trim() + '.png' }}" alt="{{ trip.AirlineName }}">
                    </div>
                    <div class="asp-totalam select-flightsname"> {{trip.AirlineName}}
                        <span class="bottom-gridcode ">{{trip.AirlineCodeFlightNumberString}}</span>
                    </div>
                </div>
                <ul class="ars-list">
                    <li>
                        <p>{{trip.DepartTime}}</p>
                        <span class="graycolor">{{trip.DepartureCity}}</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </li>
                    <li>
                        <p> {{trip.ArrivalTime}} </p>
                        <span class="graycolor">{{trip.ArrivalCity}}</span>
                    </li>
                </ul>
                <div>
                    <h5 class="whitecolor">{{CurrencySymbol}} {{selectedOnwardFlight['FareInfo']['Fare']['PublishedPrice']}}</h5>
                </div>
            </div>
            <div class="col-sm-4 d-flex align-items-center justify-content-between" ng-repeat="(segmentkey,trip) in selectedReturnFlight['SegementInfo']">
                <div class="ars-listair d-flex align-items-center">
                    <div class="airline-logo">
                        <img ng-src="{{'<?php echo site_url('uploads/airline-images/'); ?>' + trip.Airlinecode.trim() + '.png' }}" alt="{{ trip.AirlineName }}">
                    </div>

                    <div class="asp-totalam select-flightsname"> {{trip.AirlineName}}
                        <span class="bottom-gridcode ">{{trip.AirlineCodeFlightNumberString}}</span>
                    </div>
                </div>
                <ul class="ars-list">
                    <li>
                        <p>{{trip.DepartTime}}</p>
                        <span class="graycolor">{{trip.DepartureCity}}</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </li>
                    <li>
                        <p> {{trip.ArrivalTime}} </p>
                        <span class="graycolor">{{trip.ArrivalCity}}</span>
                    </li>
                </ul>
                <div>
                    <h5 class="whitecolor">{{CurrencySymbol}} {{selectedReturnFlight['FareInfo']['Fare']['PublishedPrice']}}</h5>
                </div>
            </div>
            <div class="col-sm-4 d-flex align-items-center justify-content-end">
                <ul class="ars-list">
                    <li>
                        <h3 class="mb-0">{{CurrencySymbol}}
                            {{(selectedOnwardFlight['FareInfo']['Fare']['PublishedPrice']+selectedReturnFlight['FareInfo']['Fare']['PublishedPrice']).toFixed(2)}}
                        </h3>
                    </li>
                    <li>
                        <button type="button" class="btn round_trip_book"
                            ng-click="getConfirmationInfo('confirm')">BOOK</button>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</section>
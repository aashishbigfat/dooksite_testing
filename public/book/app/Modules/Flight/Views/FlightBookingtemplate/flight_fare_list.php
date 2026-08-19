<div class="row">
  <div class="col-md-4 mb-3" ng-repeat="(key, fare) in FareListDataForModal.FareList ">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0 text-danger">{{CurrencySymbol}} {{fare.Fare.OfferedPrice}} </h5>
          <span class="badge bg-warning text-dark">{{fare.IsRefundable ? 'Refundable' : 'Non-Refundable'}}</span>
        </div>
        <div class="pricecontainer">
          <h5 class="text-muted">{{fare.CabinClass}}</h5>
          <p>( <small class="text-muted">{{fare.FareType}}</small> )</p>
        </div>
        <hr>
        <p><strong>Baggage</strong></p>
        <ul class="list-unstyled mb-2">
          <li><i class="fa-solid fa-circle-check text-success"></i> {{fare.SeatBaggage[0][0].Cabin || 'N/A'}} Cabin Baggage</li>
          <li><i class="fa-solid fa-circle-check text-success"></i> {{fare.SeatBaggage[0][0].CheckIn || 'N/A'}} Check-In Baggage</li>
        </ul>
        <p><strong>Flexibility</strong></p>
        <ul class="list-unstyled mb-2">
          <li><i class="fa-solid fa-circle-minus text-danger"></i> Cancellation fees apply <small class="text-muted">( as per airline )</small></li>
          <li><i class="fa-solid fa-circle-minus text-danger"></i> Date change fees apply <small class="text-muted">( as per airline )</small></li>
        </ul>
        <p><strong>Seats, Meals & More</strong></p>
        <ul class="list-unstyled">
          <li><i class="fa-solid fa-circle-check text-success"></i> Free Seats <small class="text-muted">( as per airline )</small></li>
          <li><i class="fa-solid fa-circle-check text-success"></i> Complimentary Meals <small class="text-muted">( as per airline  )</small> </li>
        </ul>
        <hr>
        <div class="text-center">
          <button class="btn btn-outline-primary btn-sm button-17 rounded-pill w-100 flight-book" name="search_result_{{FareListIndexOfResult}}" value="{{key}}" ng-click="getConfirmationInfo(FareListIndexOfResult,'confirm',$event); hideModal()">Book Now</button>
        </div>
      </div>
    </div>
  </div>
</div>
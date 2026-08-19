@foreach ($destinationData as $destinationData)
 <div class="col-lg-4 col-md-6 animate-fade-up delay-100 mt-4" data-category="asia adventure cultural">
    <div class="destination-card1">
      <img src="{{ $destinationData->image }}" alt="{{ $destinationData->dest_name }}" class="destination-image"/>

      <div class="destination-tags">
        @foreach ($destinationData->experiences as $index => $experience)
        <span class="destination-tag">{{ $experience }}</span>
        @endforeach
       <!--  <span class="destination-tag">Cultural</span>
        <span class="destination-tag">Nature</span>
        <span class="destination-tag">Mountains</span> -->
      </div>

      <div class="destination-static">
        <div class="destination-static-info">
          <div class="destination-static-name">{{ $destinationData->dest_name }}</div>
          <div class="destination-tours-count">{{$destinationData->total_departure}} Tours</div>
        </div>
        <a href="#" class="destination-see-details">See Details</a>
      </div>

      <div class="destination-overlay1">
        <div class="destination-overlay-tags">
             @foreach ($destinationData->experiences as $index => $experience)
          <span class="destination-tag">{{ $experience }}</span>
          @endforeach
   <!--        <span class="destination-tag">Cultural</span>
          <span class="destination-tag">Nature</span>
          <span class="destination-tag">Mountains</span> -->
        </div>
        <div class="destination-overlay-content">
          <h3 class="destination-overlay-name">{{ $destinationData->dest_name }}</h3>
          <!-- <p class="destination-description">
            Kazakhstan's cultural capital nestled in the magnificent
            Tian Shan mountains
          </p> -->
        </div>
        <div class="destination-overlay-footer d-flex justify-content-center">
          <a href="{{ url('/')}}/destinations/{{$destinationData->slug_url}}" target="_blank" class="destination-overlay-button">View Details</a>
        </div>
      </div>
    </div>
 </div>
@endforeach
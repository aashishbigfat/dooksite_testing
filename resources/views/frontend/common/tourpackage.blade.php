@foreach($departures as $departure)
  <div class="tour-card">
      <a href="{{ $departure->slug1 === 'group-tours' ? route('frontend.agentdeparture', ['slug' => $departure->slug2, 'id' => $departure->slug3]) : url($departure->slug1.'/'.$departure->slug2.'/'.$departure->slug3) }}" target="_blank">
    <div class="tour-image">
      <img
        src="{{ $departure->image }}"
        alt="{{ ucwords(strtolower($departure->title)) }}"
      />
      @if($departure->featured)
      <div class="best-selling">BEST SELLING</div>
       @endif
    </div>
    <div class="tour-content">
      <h3 class="tour-title">
       {{ ucwords(strtolower($departure->title)) }}
      </h3>
      <div class="tour-duration">
        <i class="fas fa-clock"></i>
        {{ $departure->no_of_nights }}
      </div>
      <div class="tour-features">
         @foreach($departure->inclusions as $inclusion)
          @if($inclusion->icon)
          <img src="{{ $inclusion->icon }}" alt="{{ $inclusion->name }}" class="feature-icon">
          @endif
          @endforeach
      </div>
      <div class="tour-highlights">
        <h4>Tours Highlights</h4>
        <div class="highlights-grid">
           @foreach($departure->poi_names as $poiNames)
                     <div class="highlight-item">{{ $poiNames }}</div>
                    @endforeach
         
        </div>
      </div>
      <div class="tour-price">
        <div class="price-info">

          <div class="current-price">@if($departure->price !== null)
                        ₹ {{ $departure->price }}
                        @else
                        
                    @endif</div>
        </div>
        <div class="tour-actions">
          <button class="view-btn">View Details</button>
          @php
                $whatsappMessage = urlencode("Hi! I'm interested in the " . $departure->country_name . " tour package. Can you provide more details?");
            @endphp

            <a
                href="https://api.whatsapp.com/send?phone=918368513675&text={{ $whatsappMessage }}"
                class="tour-whatsapp-btn"
                target="_blank"
            >
    
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </a>
  </div>
 @endforeach 
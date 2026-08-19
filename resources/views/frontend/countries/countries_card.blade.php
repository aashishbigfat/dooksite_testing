<div class="countries-grid" id="countriesGrid">
  @foreach($countries as $country)
    <div class="country-card">
        <img src="{{$country->image}}" 
             alt="{{ $country->countryName }}" 
             class="country-image">
        <div class="country-content">
            <div class="country-name">{{ $country->countryName }}</div>
            <div class="country-buttons">
              @if($country->about_country_slug_url != "")
                <a href="{{ route('frontend.about_country', $country->about_country_slug_url) }}" class="country-btn" title="About {{ $country->country_name }}" target="_blank">About</a>
                @endif
               @if($country->country_attraction_slug_url != "")
                <a href="{{ url('/')}}/{{$country->country_attraction_slug_url}}" class="country-btn" target="_blank">Attractions</a>
                @endif
                <a href="{{ url('/')}}/{{$country->slug_url}}" class="country-btn" target="_blank">Packages</a>
            </div>
        </div>
    </div>
      @endforeach
  </div>
 
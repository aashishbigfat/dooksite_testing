 <div class="countries-grid" id="countriesGrid">
  @foreach($countries as $country)
      <div class="country-card">
        <a href="{{url('/')}}/{{$country->country_group_slug_url}}" target="_blank">
          <img src="{{$country->image}}" alt="{{ $country->countryName }}" class="country-image">
          <div class="country-content">
              <div class="country-name">{{ $country->countryName }}</div>
             
          </div>
        </a>
      </div>
      @endforeach
  </div>
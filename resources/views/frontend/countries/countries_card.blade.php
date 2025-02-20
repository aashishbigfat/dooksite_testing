<div class="row">
    @foreach ($countries as $country)
        <div class="col-md-3 mt-3">
            <div class="card shadow-sm rounded">
                <img class="card-img-top" src="{{ env('AWS_BUCKET_URL') . '/country/' . $country->image }}" alt="Card image cap">
                <div class="card-body">
                    <h6 class="pb-2">{{ $country->countryName }}</h6>
                    @if($country->about_country_slug_url != "")
                        <a href="{{ route('frontend.about_country', $country->about_country_slug_url) }}" class="btn btn-outline-secondary py-1" title="About {{ $country->country_name }}" target="_blank">About</a>
                    @endif
                    @if($country->country_attraction_slug_url != "")
                        <a href="{{ url('/')}}/{{$country->country_attraction_slug_url}}" class="btn btn-outline-danger py-1" target="_blank">Attractions</a>
                    @endif
                    <a href="{{ url('/')}}/{{$country->slug_url}}" class="btn btn-danger py-1" target="_blank">Packages</a>
                </div>
            </div>
        </div>
    @endforeach
</div>


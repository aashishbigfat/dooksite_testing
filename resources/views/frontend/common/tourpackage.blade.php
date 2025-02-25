  @foreach($departures as $departure)
   <div class="col-md-4 mb-4"> 
    <div class="card">
            <a href="{{ $departure->slug1 === 'group-tours' ? route('frontend.agentdeparture', ['slug' => $departure->slug2, 'id' => $departure->slug3]) : url($departure->slug1.'/'.$departure->slug2.'/'.$departure->slug3) }}" target="_blank">
            <img src="{{ $departure->image}}" class="card-img-top" alt="...">

            @if($departure->featured)
            <div class="best_selling_pack">
                <img src="{{ asset('assets/images/icons/Rectangle19435.png') }}" class="w-auto">
                <p class="best_sell_pack">BEST SELLING</p>
            </div>
            @endif

            <div class="card-body common_package">
                <h6>{{ ucwords(strtolower($departure->title)) }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p>{{ $departure->no_of_nights }}</p>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        @for($i = 0; $i < 5; $i++)
                            <span class="fa fa-star {{ $i < 5 ? 'checked' : '' }}"></span>
                            @endfor
                    </div>
                    <div class="col-md-12 d-flex">
                        @foreach($departure->inclusions as $inclusion)
                        @if($inclusion->icon)
                        <img src="{{ $inclusion->icon }}" alt="{{ $inclusion->name }}" class="inclusion_icon px-1">
                        @endif
                        @endforeach
                    </div>
                    <div class="col-md-12 hightlights mt-3">
                        <h6>Tours Highlights</h6>
                        <ul class="hightlights mb-0 p-1">
                            @foreach($departure->poi_names as $poiNames)
                            <li>{{ $poiNames }}</li>
                            @endforeach
                        </ul>
                        <p>
                            {{-- <small class="text-decoration-line-through">₹ {{ number_format($departure->price)
                                }}</small> --}}
                            @if($departure->price !== null)
                                <span>₹ {{ number_format($departure->price) }}</span>
                                @else
                                <span></span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </a>
    </div>
  </div>
 @endforeach
<div class="row">
@foreach ($top_destinations as $top_destinations)
        <div class="col-md-4 mt-3">
            <div class=" shadow-sm rounded  position-relative">
                 <div class="destination-sec-country ml-2">                                      
                    <div class="destination">
                      @foreach ($top_destinations->experiences as $index => $experience)
                        @if ($index % 5 == 0 && $index != 0)
                            </div> 
                            <div class="destination" style="margin-top: 30px;"> 
                        @endif

                        <p>{{ $experience }}</p>
                    @endforeach
                    </div>                                         
                </div> 
                <img class="card-img-top" src="{{ $top_destinations->image }}" alt="Card image cap">
                <div class=" dest_card">
                   <div class="row test align-items-center">
                    <div class="col-md-6">
                     <h5 class="m-0">{{ $top_destinations->destination_name }}<br><span>{{$top_destinations->total_dep}} Tours</span></h5> 
                      </div>
                       <div class="col-md-6 d-flex justify-content-end">                                
                        <a href="{{ url('/')}}/destinations/{{$top_destinations->slug_url}}" target="_blank" class="btn btn-danger py-1 fs-12">See Details</a>
                   </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

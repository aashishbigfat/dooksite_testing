@extends('frontend.layouts.master')
@push('title') {{$destination_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$destination_header->meta_keywords}}">
<meta name="description" content="{{$destination_header->meta_description}}">@endpush 

@section('content')
    <!-- home section -->
   <div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> / Destinations</p>
            <h4>Dream, explore, and discover with DOOK!</h4>
      
        <div id="destination-list">
       
            @include('frontend.destination.destination_card', ['top_destinations' => $top_destinations])

        </div>
           <!-- Pagination Links -->
            <div class="col-md-12 mt-4">
                    <ul style="list-style-type: none;" class="p-0 d-flex">
                        @if ($top_destinations->onFirstPage())
                           
                        @else
                            <!-- Add previous button -->
                            <li>
                                <a href="{{ $top_destinations->previousPageUrl() }}" class="border p-2 text-dark rounded">Previous</a>
                            </li>
                        @endif
                        
                        @foreach(range(1, $top_destinations->lastPage()) as $page)
                            <li>
                                <a href="{{ $top_destinations->url($page) }}" class="border p-2 text-dark rounded {{ $top_destinations->currentPage() == $page ? 'active' : '' }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach
                        
                        @if ($top_destinations->hasMorePages())
                            <li>
                                <a href="{{ $top_destinations->nextPageUrl() }}" class="border p-2 text-dark rounded">Next</a>
                            </li>
                        @endif
                    </ul>
                </div>
          </div>
          <hr>
           <div class="col-md-12">
             <h2 class="text-capitalize">Destinations Around The World with Dook</h2>
            <p>If it is a place worth visiting at least once in a lifetime, we surely cover it.</p>
              
                <div id="destination">
                    <div class="row">
                    @foreach ($destinationData as $destinationData)
                            <div class="col-md-4 mt-3">
                                <div class=" shadow-sm rounded position-relative">
                                    <div class="destination-sec-country ml-2">                                      
                                        <div class="destination">
                                            @foreach ($destinationData->experiences as $index => $experience)
                                                @if ($index % 5 == 0 && $index != 0)
                                                    </div> 
                                                    <div class="destination" style="margin-top: 30px;"> 
                                                @endif

                                                <p>{{ $experience }}</p>
                                            @endforeach
                                        </div>                                         
                                    </div> 
                                    <img class="card-img-top" src="{{ $destinationData->image }}" alt="Card image cap">
                                    <div class="dest_card">
                                       <div class="row test align-items-center">
                                        <div class="col-md-6">
                                         <h5 class="m-0">{{ $destinationData->dest_name }}<br><span>{{$destinationData->total_departure}} Tours</span></h5> 
                                          </div>
                                           <div class="col-md-6 d-flex justify-content-end">                                
                                            <a href="{{ url('/')}}/destinations/{{$destinationData->slug_url}}" target="_blank" class="btn btn-danger py-1 fs-12">See Details</a>
                                       </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <!-- Pagination Links -->
                <div class="col-md-12 mt-4">
                   
                </div>
          </div>
          <hr>
          <div class="row">
           
            <div class="col-md-12 country_about">             
              {!! $destination_header->description !!}
            </div>
           
          </div>
    </div>
</div>

    <!-- testimonial -->
  @include('frontend.common.testimonial')


@endsection

@extends('frontend.layouts.master')
@push('title') {{$countries->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$countries->meta_keywords}}">
<meta name="description" content="{{$countries->meta_description}}">@endpush
@section('content')
    <!-- home section -->
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{route('frontend.index')}}"class="text-danger">Countries </a> / {{$countries->countryName}}</p>
            </div>
            <div class="col-md-12">
                <div class="tour" id="Top">
                    <h6>Incredible journeys to {{$countries->countryName}} with DOOK!</h6>
                    <p class="color_gray">No matter your travel style, we have a {{$countries->countryName}} package tailored for you</p>
                   <div class="row">
                        @foreach($departures as $departure)
                            <div class="col-md-3 mb-4">          
                                @include('frontend.common.tourpackage')
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-12 mt-4">
                     {{--   <ul style="list-style-type: none;" class="p-0 d-flex">
                            @if ($departures->onFirstPage())
                               
                            @else
                                <!-- Add previous button -->
                                <li>
                                    <a href="{{ $departures->previousPageUrl() }}" class="border p-2 text-dark rounded">Previous</a>
                                </li>
                            @endif
                            
                            @foreach(range(1, $departures->lastPage()) as $page)
                                <li>
                                    <a href="{{ $departures->url($page) }}" class="border p-2 text-dark rounded {{ $departures->currentPage() == $page ? 'active' : '' }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endforeach
                            
                            @if ($departures->hasMorePages())
                                <li>
                                    <a href="{{ $departures->nextPageUrl() }}" class="border p-2 text-dark rounded">Next</a>
                                </li>
                            @endif
                        </ul> --}}
                    </div>

                    <hr>
                </div>
            </div>
          
         <div class="row" class="">
             <div class="heading mb-3 mt-3">
                <h5 class="m-0">{{$countries->countryName}} Tour Packages</h5>
                <p class="color_gray">Explore {{$countries->countryName}}  with DOOK</p>
            </div>
            <div class="col-12 wraptextWithImg country_about">             
              <picture class="wrap_img"><img src="{{$countries->image_1}}" alt="" class="img-fluid" loading="lazy"></picture>
              @php
              $wordLimit = 60;
              $words = explode(' ', $countries->text_1);
              $contentPreview = count($words) > $wordLimit ? implode(' ', array_slice($words, 0, $wordLimit)) . '...' : $countries->text_1;
              @endphp

                <p>{!! $contentPreview !!}</p>
                @if(count($words) > $wordLimit)
                    <button class="read-more-btn bg-transparent border-0 text-danger" data-bs-toggle="modal" data-bs-target="#readMoreModal">Read More</button>
                @endif
            </div>
            <div class="col-12 wraptextWithImg country_about">
              <picture class="wrap_img_right"><img src="{{$countries->image_2}}" alt="" class="img-fluid" loading="lazy"></picture>
             @php
              $wordLimit = 60;
              $words = explode(' ', $countries->text_2);
              $contentPreview = count($words) > $wordLimit ? implode(' ', array_slice($words, 0, $wordLimit)) . '...' : $countries->text_2;
            @endphp

            <p>{!! $contentPreview !!}</p>
            @if(count($words) > $wordLimit)
                <button class="read-more-btn bg-transparent border-0 text-danger" data-bs-toggle="modal" data-bs-target="#readMoreModal2">Read More</button>
            @endif
            </div>
            <div class="col-12 wraptextWithImg country_about">
              <picture class="wrap_img"><img src="{{$countries->image_3}}" alt="" class="img-fluid" loading="lazy"></picture>
              @php
              $wordLimit = 60;
              $words = explode(' ', $countries->text_3);
              $contentPreview = count($words) > $wordLimit ? implode(' ', array_slice($words, 0, $wordLimit)) . '...' : $countries->text_3;
            @endphp

            <p>{!! $contentPreview !!}</p>
            @if(count($words) > $wordLimit)
                <button class="read-more-btn bg-transparent border-0 text-danger" data-bs-toggle="modal" data-bs-target="#readMoreModal3">Read More</button>
            @endif
            </div>
            <div class="col-12 wraptextWithImg country_about">
               @php
                  $wordLimit = 200;
                  $words = explode(' ', $countries->text_4);
                  $contentPreview = count($words) > $wordLimit ? implode(' ', array_slice($words, 0, $wordLimit)) . '...' : $countries->text_4;
              @endphp

                <p>{!! $contentPreview !!}</p>
                @if(count($words) > $wordLimit)
                    <button class="read-more-btn bg-transparent border-0 text-danger" data-bs-toggle="modal" data-bs-target="#readMoreModal4">Read More</button>
                @endif
            </div>
          </div>                  
        </div>
    </div>
    <!-- testimonial -->
   @include('frontend.common.testimonial')
<!-- Modal -->
<!-- des box1 -->
<div class="modal fade " id="readMoreModal" tabindex="-1" aria-labelledby="readMoreModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-none">
       
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! $countries->text_1 !!}
      </div>
    </div>
  </div>
</div>

<!-- des box2 -->
<div class="modal fade " id="readMoreModal2" tabindex="-1" aria-labelledby="readMoreModal2Label" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-none">
       
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! $countries->text_2 !!}
      </div>
    </div>
  </div>
</div>

<!-- des box3 -->
<div class="modal fade " id="readMoreModal3" tabindex="-1" aria-labelledby="readMoreModal3Label" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-none">
       
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! $countries->text_3 !!}
      </div>
    </div>
  </div>
</div>

<!-- des box4 -->
<div class="modal fade " id="readMoreModal4" tabindex="-1" aria-labelledby="readMoreModal4Label" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-none">
       
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! $countries->text_4 !!}
      </div>
    </div>
  </div>
</div>

             
@endsection

@extends('frontend.layouts.master')
@push('title') {{$departure_header->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure_header->meta_keywords}}">
<meta name="description" content="{{$departure_header->meta_description}}">@endpush
@section('content')

<!-- home section -->
<div class="container">
  <div class="row mb-4">
    <div class="col-md-12 mt-4">
      <p class="color_gray"><a href="/" class="text-danger">Home</a> / Group Tour</p>
    </div>
    <div class="col-md-3">
      @include('frontend.common.package_filter')
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-6">
          <p class="color_gray">{{ $totalTours }} Tours Found</p>
        </div>
      </div>
      <div class="row">
        @foreach($departures as $departure)
        <div class="col-md-4 mb-4">          
            @include('frontend.common.tourpackage')
          </div>
          @endforeach
        <!-- pagination -->
        <div class="col-md-12 mt-4">
          <ul style="list-style-type: none;" class="p-0 d-flex pagination">
            @if ($departures->onFirstPage())
            <li><a href="#" class="border p-2 bg-danger text-white rounded mx-1 disabled">Prev</a></li>
            @else
            <li><a href="{{ $departures->previousPageUrl() }}"
                class="border p-2 bg-danger text-white rounded mx-1">Prev</a></li>
            @endif
            @php
            $currentPage = $departures->currentPage();
            $lastPage = $departures->lastPage();
            $pageRange = 2;
            @endphp
            @if ($currentPage > $pageRange + 1)
            <li><a href="{{ $departures->url(1) }}" class="border p-2 text-dark rounded mx-1">1</a></li>
            @if ($currentPage > $pageRange + 2)
            <li class="disabled"><a href="#" class="border p-2 text-dark rounded mx-1">...</a></li>
            @endif
            @endif
            @foreach (range(max(1, $currentPage - $pageRange), min($lastPage, $currentPage + $pageRange)) as $page)
            <li>
              <a href="{{ $departures->url($page) }}"
                class="border p-2 text-dark  rounded mx-1 {{ $departures->currentPage() == $page ? 'active' : '' }}">
                {{ $page }}
              </a>
            </li>
            @endforeach
            @if ($currentPage < $lastPage - $pageRange) @if ($currentPage < $lastPage - $pageRange - 1) <li
              class="disabled"><a href="#" class="border p-2 text-dark rounded mx-1">...</a></li>
              @endif
              <li><a href="{{ $departures->url($lastPage) }}" class="border p-2 text-dark rounded mx-1">{{ $lastPage
                  }}</a></li>
              @endif
              @if ($departures->hasMorePages())
              <li><a href="{{ $departures->nextPageUrl() }}"
                  class="border p-2 text-white bg-danger rounded mx-1">Next</a></li>
              @else
              <li><a href="#" class="border p-2 text-white bg-danger rounded mx-1 disabled">Next</a></li>
              @endif
          </ul>
        </div>


      </div>
    </div>

  </div>

</div>


@include('frontend.common.testimonial')
@endsection
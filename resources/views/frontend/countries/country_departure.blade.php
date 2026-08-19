@extends('frontend.layouts.master')
@push('title') {{$countries->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$countries->meta_keywords}}">
<meta name="description" content="{{$countries->meta_description}}">
<meta property="og:description" content="{{$countries->meta_description}}">
<meta name="twitter:description" content="{{$countries->meta_description}}">
@if($countries->slug_url == 'kyrgyzstan-tour-packages')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [{
        "@type": "Question",
        "name": "What is the ideal time to visit Kyrgyzstan?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "The best months to visit Kyrgyzstan are from June to September. During this period, the chances of rain are lesser, and you can enjoy more things."
        }
      },{
        "@type": "Question",
        "name": "How can I reach Kyrgyzstan by air?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "You can take an affordable flight from India to Bishkek. Many Indian airlines like IndiGo, Vistara, and Air India operate flights to Kyrgyzstan."
        }
      },{
        "@type": "Question",
        "name": "Do Indians need visas to travel to Kyrgyzstan?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes. Indian tourists need an eVisa to travel to Kyrgyzstan for tourist purposes. Tourists carrying an eVisa can stay up to 60 days."
        }
      },{
        "@type": "Question",
        "name": "What are the top places to visit in Kyrgyzstan?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "There are many beautiful places to visit in Kyrgyzstan, such as Ala-Archa Gorge, Issyk-Kul Lake, Altyn Arashan, Song Kol Lake, Jeti-Oguz Canyon, Holy Trinity Russian Orthodox Cathedral, and more."
        }
      },{
        "@type": "Question",
        "name": "Do you provide Kyrgyzstan tour packages?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes. Dook Travels provides numerous Kyrgyzstan holiday packages that you can choose from."
        }
      },{
        "@type": "Question",
        "name": "Are Kyrgyzstan tour packages customizable?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes. DMCs like Dook International offer tailor-made experiences based on your travel dates, preferences, group size, and budget."
        }
      },{
        "@type": "Question",
        "name": "How many days are ideal for exploring Kyrgyzstan?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "A 4–5 day trip is ideal for covering major sights like Bishkek, Issyk-Kul, Song-Kul, and nearby national parks. For a more immersive experience, consider 10–12 days."
        }
      },{
        "@type": "Question",
        "name": "Where can I book a 4 Nights 5 Days Kyrgyzstan tour package from India?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "You can book a 4 Nights 5 Days Kyrgyzstan tour package from Dook International, a trusted Indian travel company offering well-crafted itineraries with hotel stays, transfers, guided tours, and visa support. Visit www.dookinternational.com to book or customize your trip."
        }
      }]
    }
    </script>
@endif

@endpush

@section('content')
    <!-- home section -->
     <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <a href="{{route('frontend.countries')}}">Countries</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$countries->countryName}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$countries->title}}</h1>
          <p class="page-subtitle">
          {{$countries->subTitle}}
          </p>
        </div>
      </div>
    </section>
    <div class="container mb-4">
        <div class="row">
        <!--     <div class="col-md-12 mt-4">
                <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{route('frontend.countries')}}"class="text-danger">Countries </a> / {{$countries->countryName}}</p>
            </div> -->
            <div class="col-md-12">
                <div class="tour topheading" id="Top">
                   <!--  <h1 class="text-capitalize my-1">{{$countries->title}}</h1>
                    <p>{{$countries->subTitle}}</p> -->
                    <div class="tours-grid" id="tourPackages">
                @include('frontend.common.tourpackage')
            </div>
            
            @if($departures->hasMorePages())
                <div class="col-md-12 mt-4 text-center">
                    <div id="loader" class="loader">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Packages</button>
                </div>
            @endif

                    <hr>
                </div>
            </div>
      <div class="row">
    <div class="heading mb-3 mt-3 sectionHeading">
        <h2 class="m-1">{{$countries->countryName}} Tour Packages</h2>
        <p class="color_gray">Explore {{$countries->countryName}}  with DOOK</p>
    </div>

    <!-- Content Block 1 -->
    <div class="col-12 wraptextWithImg country_about">             
        <picture class="wrap_img">
            <img src="{{$countries->image_1}}" alt="{{$countries->countryName}}" class="img-fluid" loading="lazy">
        </picture>
        

        <!-- Full content (hidden initially) -->
        <p class="full-content" id="full-content-1">
            {!! $countries->text_1 !!}
        </p>

    </div>

    <!-- Content Block 2 -->
    <div class="col-12 wraptextWithImg country_about">
        <picture class="wrap_img_right">
            <img src="{{$countries->image_2}}" alt="{{$countries->countryName}}" class="img-fluid" loading="lazy">
        </picture>

        <!-- Full content (hidden initially) -->
        <p class="full-content" id="full-content-2">
            {!! $countries->text_2 !!}
        </p>

    </div>

    <!-- Content Block 3 -->
    <div class="col-12 wraptextWithImg country_about">
        <picture class="wrap_img">
            <img src="{{$countries->image_3}}" alt="{{$countries->countryName}}" class="img-fluid" loading="lazy">
        </picture>

        <!-- Full content (hidden initially) -->
        <p class="full-content" id="full-content-3">
            {!! $countries->text_3 !!}
        </p>
    </div>

    <!-- Content Block 4 -->
    <div class="col-12 wraptextWithImg country_about">
    

        <!-- Full content (hidden initially) -->
        <p class="full-content" id="full-content-4" style="display:none;">
            {!! $countries->text_4 !!}
        </p>

    </div>
</div>

    </div>
</div>
@if(isset($country_wise) && count($country_wise) > 0)
 <section>
  <div class="container">
  <div class="row">
    <div class="cities-section">
      <div class="section-header" onclick="toggleSection()" tabindex="0" role="button" aria-expanded="true"
        aria-controls="citiesContent">
        <h3 class="section-title">
          <i class="fas fa-map-marked-alt" style="color: rgba(255, 255, 255, 0.8)"></i>
          {{$countries->countryName}} Tour Packages From Popular Indian Cities
        </h3>
        <i class="fas fa-chevron-up toggle-icon" id="toggleIcon"></i>
      </div> 

      <div class="cities-content" id="citiesContent" role="region" aria-labelledby="section-title">
        <div class="cities-grid">
          @foreach($country_wise as $key => $country_wise) 
          <a href="{{$country_wise->slug}}" class="city-link " data-city="kerala">
            <i class="fas fa-palm-tree" style="margin-right: 8px; opacity: 0.7"></i>
            {{$country_wise->name}}
          </a>
         @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endif
    <!-- testimonial -->
   @include('frontend.common.testimonial')

             
<script>
        let page = 2;
        $('#loadMoreBtn').click(function() {
            $('#loader').show();
            $('#loadMoreBtn').hide();
            
            let urlParams = new URLSearchParams(window.location.search);
            urlParams.set("page", page);
    
            let url = "{{ url()->current() }}?" + urlParams.toString();
    
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#tourPackages').append(data.view);
                    page++;
                    if (!data.hasMorePages) {
                        $('#loadMoreBtn').hide();
                    }
                    $('#loader').hide();
                    if (data.hasMorePages) {
                        $('#loadMoreBtn').show();
                    }
                },
                error: function() {
                    alert('Error loading more packages');
                    $('#loader').hide();
                    $('#loadMoreBtn').show();
                }
            });
        });
    </script>

@endsection

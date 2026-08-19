@extends('frontend.layouts.master')
@push('title') {{$presentation_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$presentation_header->meta_keywords}}">
<meta name="description" content="{{$presentation_header->meta_description}}">@endpush 

@section('content')
<section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Presentation</span>
          </div>
        </div>
      </div>
    </section>
      <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0"> {{$presentation_header->title}}</h1>
         
        </div>
      </div>
    </section>
<section class="section_widget p-4">
 <div class="container mb-4">

	         <div class="row mt-4">
		        <div class="col-12 dookPresentation">
		          <ul class="list-unstyled">
		            @foreach($presentations as $presentation)
		              <li>
		                <a href="{{url('presentation')}}/{{$presentation->file}}" target="_blank" title="{{$presentation->title}}" style="display: block;text-align: center;"><p style="text-align: center;"><i class="fas fa-file-pdf ml-auto pr-3" style="font-size:2rem;"></i></p>
		                  <h3 class="text-dark">{{$presentation->title}}</h3>
		                </a>
		              </li>
		            @endforeach
		          </ul>
		        </div>

	    </div>
	</div>
</section>
   @include('frontend.common.testimonial')

@endsection

@extends('frontend.layouts.master')
@push('title') {{$about_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$about_header->meta_keywords}}">
<meta name="description" content="{{$about_header->meta_description}}">
<meta property="og:description" content="{{$about_header->meta_description}}">
<meta name="twitter:description" content="{{$about_header->meta_description}}">
@endpush 

@section('content')
<style type="text/css">
  .about_desc p{
    font-size: 1.1rem;
  line-height: 1.3;
  }
  .border-right {
  border-right: 1px solid gray;
}
</style>
  <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">About Us </span>
          </div>
        </div>
      </div>
    </section>
    <section class="section_widget p-4">
      <div class="container">
        <div class="row d-md-flex align-items-center">
           <div class="col-md-4">
            <picture>
              <img src="{{$about_header->banner_image}}" alt="about image" class="img-fluid w-100 d-flex align-items-center">
            </picture>
          </div>
          <div class="col-md-8 mt-4">
             <div class="sectionHeading ">
              <h1 class="text-capitalize page-title">{{$about_header->heading}}</h1>
            </div>
            <div class="text-justify about_desc">
              {!! $about_header->description !!}
            </div>
          </div>
         
        </div>
      </div>
    </section> 
    <section class="section_widget p-4 ">
      <div class="container mb-4 about-details">
        <div class="whatWeDo">
          <div class="top-section">
            <div class="row">
              <div class="col-md-7 border_box p-md-0 position-relative d-flex align-items-center">
                <div class="top-left blue-bg">
                  <div class="sec1 wow about_desc"><h4>{{$about_header->box1_title}}</h4>
                    {!! $about_header->box1_description !!}
                  </div>
                </div>
              </div>
              <div class="col-md-5 border_box p-md-0 position-relative">
                <div class="top-left blue-bg">
                  <img src="{{asset('assets/images/Why travel with dook/what we are.png')}}" class="w-100 h-100">
                </div>
              </div>
              {{--<div class="col-md-7">
                <div class="middle-right">
                  <div class="heading">
                    <div class="sec1 wow"><h4>{{$about_header->box2_title}}</h4>
                      {!! $about_header->box2_description !!}
                    </div>
                  </div>
                </div>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section_widget testimonial p-4 pt-5 pb-5 mb-5">
        <div class="btm-section">
            <div class="row d-flex justify-content-center">
              <div class="col-md-5 border-right d-flex align-items-center">
                <div class="blue-bg btm-left">
                  <div class="sec1 about_desc">
                    <h4>{{$about_header->box3_title}}</h4>
                   <p> {!! $about_header->box3_description !!} </p>
                  </div>
                </div>
              </div>
              <div class="col-md-5 d-flex align-items-center">
                <div class="blue-bg btm-right px-4">
                  <div class="sec1 about_desc">
                    <h4>{{$about_header->box4_title}}</h4>
                    <p> {!! $about_header->box4_description !!} </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
        <section class="section_widget pt-5 pb-5 mb-5 p-4">
        <div class="btm-section">
            <div class="row justify-content-center">
              <div class="col-md-12 d-flex text-center mb-4">
                <div class="blue-bg btm-left">
                  <div class="sec1 about_desc ">
                    <h4>Our Team</h4>
                   <p> Our team is a passionate, dynamic group dedicated to innovation and excellence. We bring together diverse talents from different fields to 
collaborate and create solutions that make a difference. With a shared vision, we tackle challenges head-on and constantly push the boundaries of what's possible." </p>
                  </div>
                </div>
              </div>
              <div class="col-md-12 d-flex align-items-center">
                <div class="blue-bg btm-right px-4">
                  <div class="sec1 about_desc">
                     <picture>
                      <img src="{{$about_header->image}}" alt="about image" class="img-fluid w-100 d-flex align-items-center">
                    </picture>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>

   @include('frontend.common.testimonial')

@endsection
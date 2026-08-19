@extends('frontend.layouts.master')
@push('title') {{$contact_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$contact_header->meta_keywords}}">
<meta name="description" content="{{$contact_header->meta_description}}">
<meta property="og:description" content="{{$contact_header->meta_description}}">
<meta name="twitter:description" content="{{$contact_header->meta_description}}">
@endpush 

@section('content')
<style>
	.exquire_popup{
		display: none !important;
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
            <span class="breadcrumb-current">Contact Us </span>
          </div>
        </div>
      </div>
    </section>

<section class="section_widget p-4">
 <div class="container mb-4">
<!--       <div class="row">
          <div class="col-md-12">
              <p class="color_gray"><a href="/" class="text-danger">Home</a> / Contact Us </p>
          </div>
     	</div> -->
     	<div class="row">
     		<div class="col-md-9">
     			   <div class="tour topheading" id="Top">
                 <h1 class="text-capitalize my-1">Contact Information</h1>
		        	   <p>Fill up the form and our Team will get back to you within 24 hours</p>
           </div>
          <div class="row">
          	<div class="col-md-12">
          		 <div class="contact_form">
           	<h4>Contact Us</h4>
           	<form id="commonInquiryForm">
            @csrf
             <input type="hidden" name="type" id="typeM" value="cu">
		          <input type="hidden" name="url" id="url" value="{{ url()->current() }}">

		          <input type="hidden" name="fullurl" id="fullurl" value="{{ url()->full() }}">

		          <input type="hidden" name="pkg_id" id="pkg_id"
		            value="@if(isset($departure->dep_dook_ref_id)){{$departure->dep_dook_ref_id}}@endif">

		          <input type="hidden" name="duration" id="duration"
		            value="@if(isset($departure->no_of_nights)){{$departure->no_of_nights}}@endif">

		          <input type="hidden" name="destinations" id="destinations"
		            value="@if(isset($dest_array)){{ implode(',',$dest_array) }}@endif">

		          <input type="hidden" name="pg_region" id="pg_region"
		            value="@if(isset($region->region_name)){{$region->region_name}}@endif">
		          <input type="hidden" name="pg_country" id="pg_country"
		            value="@if(isset($comonInquiryCountry)){{$comonInquiryCountry}}@endif">
		          <input type="hidden" name="destination" id="destination"
		            value="@if(isset($destination->dest_name)){{$destination->dest_name}}@endif">
		          <input type="hidden" name="browserName" id="browserName">
		        
		         <input type="hidden" name="dep_type" id="dep_type" value="@if(isset($departure->destinations[0]->country)){{ $departure->destinations[0]->country }}@elseif(isset($departures[0]->dep_type)){{ $departures[0]->dep_type }}@elseif(isset($departures[0]->slug1)){{ $departures[0]->slug1 }}@endif">
		        
		          <input type="hidden" name="min_country_data" id="min_country_data"
		            value="{{ isset($min_country_data) ? json_encode($min_country_data) : '' }}">

		           <input type="hidden" name="fixed_departure" id="fixed_departure" value="{{ (isset($requestedDeparture['DepartureDateWithPrice']) && count($requestedDeparture['DepartureDateWithPrice']) > 0)?'yes':'no' }}">

		          <input type="hidden" name="form_type" id="form_type" value="{{ isset($form_type)?$form_type:'' }}">

			        <div class="row"> 
			            <div class="col-md-6 col-12">
			                <label for="name" class="form-label pop_up">Name*</label>
			                <input type="text" class="form-control" id="name" name="name" required>
			            </div>
			            <div class="col-md-6 col-12">
			                <label for="email" class="form-label pop_up">Email*</label>
			                <input type="email" class="form-control" id="email" name="email" required>
			            </div>
			            <div class="col-md-6 col-12">
			                <label for="mobile" class="form-label pop_up">Mobile*</label>
			                <input type="number" class="form-control" id="mobile" name="mobile" required>
			            </div>
			            <div class="col-md-6 col-12">
			                <label for="date" class="form-label pop_up">Date*</label>
			                <input type="date" class="form-control" id="date" name="travel_date" required>
			            </div>
			            <div class="col-md-6 col-12">
			                <label for="travellers" class="form-label pop_up">No of travellers*</label>
			                <input type="number" class="form-control" id="no_of_traveler" name="no_of_traveler" required>
			            </div>
			             <div class="col-md-6 col-12"> 
			              <label for="travellers" class="form-label pop_up">Destination*</label>               
			                @if (isset($departure->destinations))
			                <div class="col">
			                    @if ($departure->destinations->count() == 1) 
			                    <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control"
			                      value="{{ $departure->destinations[0]->dest_name }}">
			                    @else
			                    <select name="destinations_name" class="form-control" >
			                      @forelse ($departure->destinations as $index => $destination)
			                      <option value="{{ $destination->dest_name }}" {{ $index==0 ? 'selected' : '' }}>{{ $destination->dest_name
			                        }}</option>
			                      @empty
			                      <option value="No Destination">No Destinations</option>
			                      @endforelse
			                    </select>
			                    @endif
			                
			                </div>
			               @elseif (isset($requestedDeparture['Destination']) && count ($requestedDeparture['Destination']) > 0)
			              <div class="col">
			               @if (is_array($requestedDeparture['Destination']) && count($requestedDeparture['Destination']) == 1)
			                <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control"
			                  value="{{$requestedDeparture['Destination'][0]}}">

			                @else
			                <select name="destinations_name" id="" class="form-control">
			                  @forelse ($requestedDeparture['Destination'] as $index => $destination)
			                  <option value="{{ $destination }}" {{ $index==0 ? 'selected' : '' }}>{{ $destination
			                    }}</option>
			                  @empty
			                  <option value="No Destination">No Destinations</option>
			                  @endforelse
			                </select>
			                @endif
			              </div>
			              @elseif (isset($country_destination_name))
			              <div class="col">
			                <select name="destinations_name" class="form-control">
			                  @if (count($country_destination_name) == 1)
			                  @foreach ($country_destination_name as $destination)
			                  <option value="{{ $destination }}" selected>{{ $destination }}</option>
			                  @endforeach
			                  @else
			                  @forelse ($country_destination_name as $destination)
			                  <option value="{{ $destination }}" {{ ($destination==$dest_for_select) ? 'selected' : '' }}>{{
			                    $destination }}
			                  </option>
			                  @empty
			                  <option value="No Destination">No Destinations</option>
			                  @endforelse
			                  @endif
			                </select>
			              </div>
			              @elseif (isset($region_destination_name) && count($region_destination_name) > 0)
			              <div class="col">
			                <select name="destinations_name" class="form-control">
			                  @if (count($region_destination_name) == 1)
			                  @foreach ($region_destination_name as $destination)
			                  <option value="{{ $destination }}" selected>{{ $destination }}</option>
			                  @endforeach
			                  @else
			                  @forelse ($region_destination_name as $destination)
			                  <option value="{{ $destination }}">{{ $destination }}</option>
			                  @empty
			                  <option value="No Destination">No Destinations</option>
			                  @endforelse
			                  @endif
			                </select>
			              </div>
			              @elseif (isset($destination_name_from_destination_page))
			              <div class="col">
			                <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control"
			                  value="{{ $destination_name_from_destination_page }}">
			              </div>
			              @elseif (isset($departure_destination_name))
			              <div class="col">
			                <select name="destinations_name" class="form-control">
			                  @if (count($departure_destination_name) == 1)
			                  @foreach ($departure_destination_name as $destination)
			                  <option value="{{ $destination }}" selected>{{ $destination }}</option>
			                  @endforeach
			                  @else
			                  @forelse ($departure_destination_name as $destination)
			                  <option value="{{ $destination }}">{{ $destination }}</option>
			                  @empty
			                  <option value="No Destination">No Destinations</option>
			                  @endforelse
			                  @endif
			                </select>
			              </div>
			              @elseif (isset($common_inquiry_destination_name))
			              <div class="col">
			                  <select name="destinations_name" class="form-control">
			                      @if (count($common_inquiry_destination_name) == 1)
			                          @foreach ($common_inquiry_destination_name as $destination)
			                              <option value="{{ $destination }}" selected>{{ $destination }}</option>
			                          @endforeach
			                      @else
			                          @forelse ($common_inquiry_destination_name as $destination)
			                              <option value="{{ $destination }}">{{ $destination }}</option>
			                          @empty
			                              <option value="No Destination">No Destinations</option>
			                          @endforelse
			                      @endif
			                  </select>
			              </div>
			               @elseif (isset($common_inquiry_region_name) && count($common_inquiry_region_name) > 0)
			                  <div class="col">
			                      <select name="destinations_name" class="form-control">
			                          @if (count($common_inquiry_region_name) == 1)
			                              @foreach ($common_inquiry_region_name as $destination)
			                                  <option value="{{ $destination }}" selected>{{ $destination }}</option>
			                              @endforeach
			                          @else
			                              @forelse ($common_inquiry_region_name as $destination)
			                                  <option value="{{ $destination }}">{{ $destination }}</option>
			                              @empty
			                                  <option value="No Destination">No Destinations</option>
			                              @endforelse
			                          @endif
			                      </select>
			                  </div>
			              @else
			              <div class="col">
			                <select name="destinations_name" class="form-control">
			                  <!-- <option>Select</option> -->
			                   <option value="Almaty, Kazakhstan">Almaty, Kazakhstan</option>
				                  <option value="Baku, Azerbaijan">Baku, Azerbaijan</option>
				                  <option value="Bishkek, Kyrgyzstan">Bishkek, Kyrgyzstan</option>
				                  <option value="Tashkent, Uzbekistan">Tashkent, Uzbekistan</option>
				                  <option value="Yerevan, Armenia ">Yerevan, Armenia </option>
				                  <option value="Tbilisi, Georgia">Tbilisi, Georgia</option>
				                  <option value="Moscow, Russia">Moscow, Russia</option>
				                  <option value="Istanbul, Turkey">Istanbul, Turkey</option>
				                  <option value="St Petersburg, Russia">St Petersburg, Russia</option>
				                  <option value="Europe">Europe</option>                  
				                  <option value="Ho Chi Minh, Vietnam">Ho Chi Minh, Vietnam</option>
				                  <option value="Hanoi, Vietnam">Hanoi, Vietnam</option>
				                  <option value="Bali, Indonesia">Bali, Indonesia</option>
				                  <option value="Singapore">Singapore</option>
				                  <option value="Dubai, UAE">Dubai, UAE</option>
				                  <option value="India's Golden Triangle - Delhi | Agra | Jaipur">India's Golden Triangle - Delhi | Agra | Jaipur</option>
				                  <option value="other">Other</option>
			                </select>
			              </div>
			              @endif
			            </div>
			            <div class="col-md-12 col-12 mt-3 d-flex justify-content-center">
			                 <button type="submit" class="btn btn-danger w-50" >Submit</button>
			            </div>
			        </div>
            </form>
           </div>
          	</div>
          </div>
     		</div>
     	 <div class="col-md-3">
          @include('frontend.common.bookwithconfidence')
         </div>
     	</div>
     	<div class="row">
     		  @foreach($address as $addres)
              <div class="col-md-6 d-flex mb-3">
              	<div class="card p-3 contact_card">
              		  <address><h4>{{$addres->title}}</h4>
                  {!! $addres->address !!}</address>
              	</div>
              </div>
              @endforeach
     	</div>
</section>
   @include('frontend.common.testimonial')

@endsection

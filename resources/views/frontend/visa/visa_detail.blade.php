@extends('frontend.layouts.master')
@push('title') {{$visaDes->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$visaDes->meta_keywords}}">
<meta name="description" content="{{$visaDes->meta_description}}">@endpush
@section('content')

<!-- home section -->
<div class="container">
    <div class="row mt-4">
        <div class="col-md-12 header-sticky">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="/" class="text-danger">Tours</a>/
                {{ $visaDes->title }}</p>
                 <ul class="nav nav-tabs shadow-sm p-3 mb-5 bg-white rounded">            
                    <li><a href="#1" id="s1" >Visa Types</a></li>
		              @if($visaDes->required_documents != "" || $visaDes->required_documents != null)
		              <li><a href="#2" id="s2">Documents</a></li>
		              @endif
		              @if($visaDes->eligibility_criteria != "" || $visaDes->eligibility_criteria != null)
		              <li><a href="#3" id="s3">Eligibility Criteria</a></li>
		              @endif
		              @if($visaDes->exemptions != "" || $visaDes->exemptions != null)
		              <li><a href="#4" id="s4">Exemptions</a></li>
		              @endif
		              @if($visaDes->general_information != "" || $visaDes->general_information != null)
		              <li><a href="#5" id="s5">Guidelines</a></li>
		              @endif
		              @if($visaDes->faqs != "" || $visaDes->faqs != null)
		              <li><a href="#6" id="s6">FAQs</a></li>
		              @endif
		              @if($visaDes->visa_process != "" || $visaDes->visa_process != null)
		              <li><a href="#7" id="s7"> Process</a></li>
		              @endif
                </ul>

        </div>
        <div class="col-md-9 mb-5">
        	  <div class="row visa-infoContent" id="1">
		        @foreach($visas as $key => $visa)
		          <div class="col-md-12 p-2">
		          <div class="card-box d-inline-block">
		            <div class="card-boxHeader mb-2">
		              <h4 class="m-0">{{$visa->visiting_country}} {{$visa->visa_category}} Visa</h4>
		            </div>
		            <div class="row">
		              <div class="col-md-3">
		              	<h6 class="mb-0">Passport Issuing Country</h6>
		                <p>{{$visa->phCountry}}</p>
		              </div>
		              <div class="col-md-2">
		              	<h6 class="mb-0">Country of Residence</h6>
		                <p>{{$visa->residence_country}}</p>
		              </div>
		              <div class="col-md-2">
			             <h6 class="mb-0">Visiting Country</h6>
			             <p>{{$visa->visiting_country}}</p>
		          	  </div>
		          	  <div class="col-md-2">
			              <h6 class="mb-0">Visa Required</h6>
			              <p>{{$visa->visa_required}}</p>
			          </div>
			          <div class="col-md-2">
			              <h6 class="mb-0">Visa On Arrival</h6>
			              <p>{{$visa->visa_arrival}}</p>
			          </div>
			          <div class="col-md-3">
			              <h6 class="mb-0">Visa Type</h6>
			              <p>{{$visa->visa_type}}</p>
		          	  </div>
		          	  <div class="col-md-2">
			              <h6 class="mb-0">Fees</h6>
			              <p>{{$visa->fees}}</p>
			          </div>
			          <div class="col-md-2">
			              <h6 class="mb-0">Processing time</h6>
			              <p>{{$visa->processing_time}}</p>
			          </div>
			          <div class="col-md-2">
			              <h6 class="mb-0">Stay period</h6>
			              <p>{{$visa->stay_period}}</p>
			          </div>
			          <div class="col-md-2">
			              <h6 class="mb-0">Validity</h6>
			              <p>{{$visa->validity}}</p>
		          	  </div>
		            </div>
		          </div>
		         </div>
		        @endforeach
		      </div>
		      <div class="row mt-4">
		        <div class="col-12">
		          @if($visaDes->required_documents != "" || $visaDes->required_documents != null)
		          <div class="visaData mt-4" id="2">
		            <h5> Documents Required for {{$visaDes->visiting_country}} Visa</h5>
		            <div class="">
		              {!! $visaDes->required_documents !!}
		            </div>
		          </div>
		          @endif
		          @if($visaDes->eligibility_criteria != "" || $visaDes->eligibility_criteria != null)
		          <div class="visaData mt-4" id="3">
		            <h5> {{$visaDes->visiting_country}} Visa Eligibility Criteria</h5>
		            <div class="">
		              {!! $visaDes->eligibility_criteria!!}
		            </div>
		          </div>
		          @endif
		          @if($visaDes->exemptions != "" || $visaDes->exemptions != null)
		          <div class="visaData mt-4" id="4">
		            <h5> {{$visaDes->visiting_country}} Visa Exemptions</h5>
		            <div class="">
		              {!! $visaDes->exemptions!!}
		            </div>
		          </div>
		          @endif
		          @if($visaDes->general_information != "" || $visaDes->general_information != null)
		          <div class="visaData mt-4" id="5">
		            <h5> {{$visaDes->visiting_country}} Visa General Information</h5>
		            <div class="">
		              {!! $visaDes->general_information!!}
		            </div>
					</div>
		          @endif
		          @if($visaDes->faqs != "" || $visaDes->faqs != null)
		      		<div class="visaData mt-4" id="5">
		            <h5> {{$visaDes->visiting_country}} Visa FAQs</h5>
		            <div class="">
		              {!! $visaDes->faqs !!}
		            </div>
		          </div>
		          @endif
		          @if($visaDes->visa_process != "" || $visaDes->visa_process != null)
		          <div class="visaData mt-4" id="7">
		            <h5> {{$visaDes->visiting_country}} Visa Application Process</h5>
		            <div class="">
		              {!! $visaDes->visa_process!!}
		            </div>
		          </div>
		          @endif
		        </div>
		      </div>
        </div>  
        <div class="col-md-3">
	    	  @include('frontend.common.visa_search')	
          <div class="shadow p-3 mb-3 bg-white rounded">
              <h6 class="px-2">Book With Confidence</h6>
              <p class="color_gray"><img src="{{asset('assets/images/icons/thumbs-up.png')}}" alt="" class="px-2"> No-hassle best price guarantee</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> Customer care available 24/7</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/star.png')}}" alt="" class="px-2"> Hand-picked Tours & Activities</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/crosshair.png')}}" alt="" class="px-2"> Free Travel Insureance</p>
  
            </div>
  
            <div class="shadow p-3  bg-white rounded">
              <h6 class="px-2">Need Help?</h6>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> +911140001000</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mailbox.png')}}" alt="" class="px-2"> sales@dooktravels.com</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/chat.png')}}" alt="" class="px-2"> +918368513675</p>
            </div>
         </div>
    </div>
</div>

</div>
<style type="text/css">
  .BannerCenterSection{margin-top: 55px;}
  .VisaTabs{}
</style>
@include('frontend.common.testimonial')
<script>
   $(document).ready(function(){
      var value = "<?php echo $visaDes->phCountryIso2; ?>";
      var slugVisiting = "<?php echo $visaDes->slug_url; ?>";
      $('#residence_country').val(value);
      $('#residence_country').select2();
      
      getVisitingCountry();
      
      $('#residence_country').on("change", function (e){
        getVisitingCountry();
      });

      // Visiting Country
      $('#visiting_country').select2().on("change", function (e){
        var slug = $("#visiting_country").val();
        if(slug != null && slug !== "") {
          window.location = "{{url('/')}}/" + slug;
        }
      });

      function getVisitingCountry(){
        var slugVisiting = "<?php echo $visaDes->slug_url; ?>";
        $('.loaderBg').css('display', 'block');
        var country_iso_2 = $("#residence_country").val();
        if(country_iso_2 != null && country_iso_2 !== ""){
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('frontend.dependency_country_list') }}",
            method: "GET",
            data: {iso_2: country_iso_2},
            success: function(data){
              $('.loaderBg').css('display', 'none');
              if(data){
                $("#visiting_country").empty();
                $("#visiting_country").append('<option value="">Visiting Country</option>');
                $.each(data, function(key, value){
                  var selectedVal = (slugVisiting === value.slug_url) ? 'selected' : '';
                  $("#visiting_country").append('<option value="'+value.slug_url+'" '+selectedVal+'>'+value.country_name+'</option>');
                });
                $('#visiting_country').select2();
              } else {
                $("#visiting_country").empty();
                $("#visiting_country").append('<option value="">No visiting countries available</option>');
              }
            }
          });
        }
      }

      $(".VisaTabs").click(function(){
        $('.VisaTabs').removeClass('active');
        $(this).addClass('active');
      });
    });
</script>
@endsection

@extends('frontend.layouts.master')
@push('title') Best Visa Experts - Tourist Visa Consultants Online @endpush
@push('meta_tag')
<meta name="keywords" content="Visa Consultant, Visa Expert, Best Visa Consultants, Tourist Visa Online, Tourist Visa Consultants, Visa Consultancy Services, Apply for Tourist Visa Online">
<meta name="description" content="Best Visa Experts and Consultants - Looking for Tourist Visa Consultants? Apply for Tourist Visa Online & Get Visa Consultancy Services at Dook!">@endpush
@section('content')

<!-- home section -->
<div class="container">
    <div class="row mt-4">
    	  <p class="color_gray"><a href="/" class="text-danger">Home</a> / Visa</p>
        <div class="col-md-9 mb-5">
        	<div class="card-boxHeader">
        	<h3>We make visa process easier for you</h3>
        	<p>We make your urge to travel worthwhile with our visa expertise, robust methods and easy processes. Select your destination to know about the visa requirements.</p>
           </div>
		  <h3 class="mb-4">Visa for Popular Countries</h3>
		  <div class="row g-3 mt-3">
		    <!-- Start of each card -->
		      @foreach($v_destinations as $v_destination)
		    <div class="col-md-4 col-6 mt-2 mb-2">
		       <div class="d-flex align-items-center visa-card">
		      	 <div class="visa-card-img-container">
		        <img src="{{$v_destination->flag}}" alt="{{$v_destination->dest_name}}" class=" visa-card-img">
		       </div>
		        <div class="flex-grow-1 px-2">
		          <div class="fw-bold">{{$v_destination->dest_name}}</div>
		          <div class="text-muted">{{$v_destination->country}}</div>
		          <div class="text-muted d-lg-none d-lg-block"> <a href="{{$selected_country_slug}}{{$v_destination->visa_url}}" class="p-0 btn btn-link text-danger fw-bold text-nowrap" target="_blank">Enquire Now</a></div>
		        </div>
		         <a href="{{$selected_country_slug}}{{$v_destination->visa_url}}" class="btn btn-link text-danger fw-bold text-nowrap d-lg-block d-none" target="_blank">Enquire Now</a>
		      </div>
		    </div>
		    @endforeach
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
    <hr>
    <div class="row mb-5">	
		<div class="col-md-9">
			<h4>How to apply for your visa with Dook?</h4>
			<p>Obtaining a visa can be a daunting task. However, if you hand over your trust in our hands, we will fulfil all your visa needs quickly and conveniently. Dook has the right knowledge, expertise and efficient visa procedures to help you travel to your next dream destination with ease
			and a satisfied mind.</p>
			<span><b>Below are the simple steps briefing about the procedure to apply for a visa through Dook:</b></span>
		</div>
		<div class="col-md-12 mt-4">
			<div class="row" style="background-image: url('assets/images/visa/visa.png')">
				<div class="col-md-3 col-6">
					<div class="row">
						<div class="col-md-12 d-flex justify-content-center">
							<img src="{{asset('assets/images/visa/submit.png')}}">
						</div>
						<div class="col-md-12 d-flex justify-content-center mt-3">
							<p>Submit the Required Documents</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="row">
						<div class="col-md-12 d-flex justify-content-center">
							<img src="{{asset('assets/images/visa/fee.png')}}">
						</div>
						<div class="col-md-12 d-flex justify-content-center mt-3">
							<p>Pay the Fee Online</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="row">
						<div class="col-md-12 d-flex justify-content-center">
							<img src="{{asset('assets/images/visa/verification.png')}}">
						</div>
						<div class="col-md-12 d-flex justify-content-center mt-3">
							<p>Document Verification by Dook</p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="row">
						<div class="col-md-12 d-flex justify-content-center">
							<img src="{{asset('assets/images/visa/receive.png')}}">
						</div>
						<div class="col-md-12 d-flex justify-content-center mt-3">
							<p>Receive Your Visa</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="col-md-12">
			<div class="sectionHeading">
	          <h2>Why choose Dook?</h2>
	          <p>Get your visa processing done just in time</p>
	          <p>Making the complex visa process a smooth task for you, we, at Dook, aspire to be your first choice for visa consultation and all your travelling needs. With several years of industry experience, expertise, our process-driven methodology and a highly motivated team, we work round the
            clock with a clear intent of becoming the most trusted visa consultant for you. We are guided by nothing but our commitment towards professionalism, ethics and our strong beliefs in providing our clients with the utmost satisfaction throughout their journey, from India to the desired
            destination.</p>
          <p class=""><strong>The following are some of the core strengths that we believe set us apart from amongst the top visa consultation companies in the market today:</strong></p>
	        </div>
	        <div class="row g-3 mt-2">
	        	<div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
			           <img src="{{asset('assets/images/visa/procedures.png')}}" alt="Channelized Visa Procedures" style="height: 45px;">
			        </div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Channelized Visa Procedures</div>
			        </div>
			      </div>
			    </div>
			    <div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
			           <img src="{{asset('assets/images/visa/service.png')}}" alt="Personalized & Reliable Services" style="height: 45px;">
			        </div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Personalized & Reliable Services</div>
			        </div>
			      </div>
			    </div>
			    <div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
			           <img src="{{asset('assets/images/visa/expert.png')}}" alt="Team of Versatile Experts" style="height: 45px;">
			        </div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Team of Versatile Experts</div>
			        </div>
			      </div>
			    </div>
			    <div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
     			        <img src="{{asset('assets/images/visa/pick.png')}}" alt="Pick Up & Drop of Documents" style="height: 45px;">
					</div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Pick Up & Drop of Documents</div>
			        </div>
			      </div>
			    </div>
			      <div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
			           <img src="{{asset('assets/images/visa/visa update.png')}}" alt="Abreast with Latest Visa Updates" style="height: 45px;">
			        </div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Abreast with Latest Visa Updates</div>
			        </div>
			      </div>
			    </div>
			     <div class="col-md-4 col-6 mt-2 mb-2">
			       <div class="d-flex align-items-center visa-card1">
			       	<div class="visa-card-img-container1">
			            <img src="{{asset('assets/images/visa/vector.png')}}" alt="Utmost Client Satisfaction" style="height: 45px;">
			        </div>
			        <div class="flex-grow-1">
			          <div class="fw-bold">Utmost Client Satisfaction</div>
			        </div>
			      </div>
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
      var iso2 = "<?php echo $country_codeV; ?>";
      $('#residence_country').val(iso2);
      $('#residence_country').select2();
      getVisitingCountry();
      
      $('#residence_country').on("change", function (e){
        getVisitingCountry();
      });

      $('#visiting_country').select2().on("change", function (e){
        
        var slug = $("#visiting_country").val();
        if(slug != null){
            window.location = "{{url('/')}}/"+slug;
        }
      });
    }); 

    function getVisitingCountry(){
      var iso2 = "<?php echo $country_codeV; ?>"; console.log(iso2);
      var country_iso_2 = $("#residence_country").val();
      if(country_iso_2 == null){
        var country_iso2 = iso2;
      }else{
        var country_iso2 = country_iso_2;
      }
      if(country_iso2 != null){
        $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          url:"{{ route('frontend.dependency_country_list') }}",
          method:"GET",
          data:{iso_2:country_iso_2},
          success:function(data){
            $('.loaderBg').css('display','none');
            if(data){
                $("#visiting_country").empty();
                $("#visiting_country").append('<option value="">Visiting Country</option>');
                $.each(data,function(key,value){
                    $("#visiting_country").append('<option value="'+value.slug_url+'">'+value.country_name+'</option>');
                });
           
            }else{
               $("#state").empty();
            }
          }
        });
      }
    }
  </script>
@endsection

@extends('frontend.layouts.master')
@push('title') Best Visa Experts - Tourist Visa Consultants Online @endpush
@push('meta_tag')
<meta name="keywords"
	content="Visa Consultant, Visa Expert, Best Visa Consultants, Tourist Visa Online, Tourist Visa Consultants, Visa Consultancy Services, Apply for Tourist Visa Online">
<meta name="description"
	content="Best Visa Experts and Consultants - Looking for Tourist Visa Consultants? Apply for Tourist Visa Online & Get Visa Consultancy Services at Dook!">
	<meta property="og:description" content="Best Visa Experts and Consultants - Looking for Tourist Visa Consultants? Apply for Tourist Visa Online & Get Visa Consultancy Services at Dook!">
<meta name="twitter:description" content="Best Visa Experts and Consultants - Looking for Tourist Visa Consultants? Apply for Tourist Visa Online & Get Visa Consultancy Services at Dook!">
@endpush
@push('head_script')
  <link href="{{asset('assets/select2.min.css')}}" rel="stylesheet" />
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
            <span class="breadcrumb-current">Visa</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0"> Visa Consultation Services for International Travel from India</h1>
          <p class="page-subtitle">We urge you to travel worldwide with our expert visa services, which include robust methods and easy	processes. Pick your destination and know the visa requirements.
          </p>
        </div>
      </div>
    </section>

<div class="container">
	<div class="row mt-4">
		<!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> / Visa</p> -->
		<div class="col-md-12">
		<!-- 	<div class=" text-center">
				<h1 class="section-title"> We make visa process easier for you</h1>
				<p class="section-subtitle ">We urge you to travel worldwide with our expert visa services, which include robust methods and easy
					processes. Pick your destination and know the visa requirements.</p>
			</div> -->
			<div class="row justify-content-center mb-4">
				@include('frontend.common.visa_search')
			</div>
		</div>
		<hr>
		<div class="col-md-9 mb-5 mt-4">
			<div class="heading">
				<h2 class="m-0">Visa for Popular Countries</h2>
			</div>
			<div class="row g-3 mt-3">
				<!-- Start of each card -->
				@foreach($v_destinations as $v_destination)
				<div class="col-md-4 col-6 mt-2 mb-2 d-flex justify-content-center">
					<div class="d-flex align-items-center visa-card">
						<div class="visa-card-img-container">
							<img src="{{$v_destination->flag}}" alt="{{$v_destination->dest_name}}" class="visa-card-img" />
						</div>
						<div class="flex-grow-1 visa_detail">
							<div class="fw-bold">{{$v_destination->dest_name}}</div>
							<div class="text-muted">{{$v_destination->country}}</div>
							<div class="text-muted d-lg-none d-lg-block"> <a
									href="{{$selected_country_slug}}{{$v_destination->visa_url}}"
									class="p-0 btn btn-link text-danger fw-bold text-nowrap" target="_blank">Enquire
									Now</a></div>
						</div>
						<a href="{{$selected_country_slug}}{{$v_destination->visa_url}}"
							class="btn btn-link text-danger fw-bold text-nowrap d-lg-block d-none"
							target="_blank">Enquire Now</a>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="col-md-3 mt-4">

			@include('frontend.common.bookwithconfidence')
		</div>
	</div>
	<hr>
	<div class="row mb-5">
		<div class="col-md-9">
			<div class="heading mb-3 mt-3">
				<h2 class="m-0">How to apply for your visa with Dook?</h2>
			</div>
			<p>Obtaining a visa can be a daunting task. However, if you hand over your trust in our hands, we will
				fulfil all your visa needs quickly and conveniently. Dook has the right knowledge, expertise and
				efficient visa procedures to help you travel to your next dream destination with ease
				and a satisfied mind.</p>
			<span><b>Below are the simple steps briefing about the procedure to apply for a visa through
					Dook:</b></span>
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
			<div class="heading mb-3 mt-3">
				<h2 class="m-0">Why choose Dook?</h2>
				<p>Get your visa processing done just in time</p>
				<p>Making the complex visa process a smooth task for you, we, at Dook, aspire to be your first choice	for visa consultation and all your travelling needs. With several years of industry experience,	expertise, our process-driven methodology and a highly motivated team, we work round the clock with a clear intent of becoming the most trusted visa consultant for you. We are guided by	nothing but our commitment towards professionalism, ethics and our strong beliefs in providing our clients with the utmost satisfaction throughout their journey, from India to the desired	destination.</p>
				<p class=""><strong>The following are some of the core strengths that we believe set us apart from amongst the top visa consultation companies in the market today:</strong></p>
			</div>
			<div class="row g-3 mt-2">
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/procedures.png')}}" alt="Channelized Visa Procedures"
								style="height: 45px;">
						</div>
						<div class="flex-grow-1">
							<div class="fw-bold">Channelized Visa Procedures</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/service.png')}}"
								alt="Personalized & Reliable Services" style="height: 45px;">
						</div>
						<div class="flex-grow-1">
							<div class="fw-bold">Personalized & Reliable Services</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/expert.png')}}" alt="Team of Versatile Experts"
								style="height: 45px;">
						</div>
						<div class="flex-grow-1">
							<div class="fw-bold">Team of Versatile Experts</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/pick.png')}}" alt="Pick Up & Drop of Documents"
								style="height: 45px;">
						</div>
						<div class="flex-grow-1">
							<div class="fw-bold">Pick Up & Drop of Documents</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/visa update.png')}}"
								alt="Abreast with Latest Visa Updates" style="height: 45px;">
						</div>
						<div class="flex-grow-1">
							<div class="fw-bold">Abreast with Latest Visa Updates</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-6 mt-2 mb-2">
					<div class="d-flex align-items-center visa-card1">
						<div class="visa-card-img-container1">
							<img src="{{asset('assets/images/visa/Vector.png')}}" alt="Utmost Client Satisfaction"
								style="height: 45px;">
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
	.BannerCenterSection {
		margin-top: 55px;
	}

	.VisaTabs {}
</style>
@include('frontend.common.testimonial')
<script>
	$(document).ready(function () {
		var iso2 = "<?php echo $country_codeV; ?>";
		$('#residence_country').val(iso2);
		$('#residence_country').select2();
		getVisitingCountry();

		$('#residence_country').on("change", function (e) {
			getVisitingCountry();
		});

		$('#visiting_country').select2().on("change", function (e) {

			var slug = $("#visiting_country").val();
			if (slug != null) {
				window.location = "{{url('/')}}/" + slug;
			}
		});
	});

	function getVisitingCountry() {
		var iso2 = "<?php echo $country_codeV; ?>"; console.log(iso2);
		var country_iso_2 = $("#residence_country").val();
		if (country_iso_2 == null) {
			var country_iso2 = iso2;
		} else {
			var country_iso2 = country_iso_2;
		}
		if (country_iso2 != null) {
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: "{{ route('frontend.dependency_country_list') }}",
				method: "GET",
				data: { iso_2: country_iso_2 },
				success: function (data) {
					$('.loaderBg').css('display', 'none');
					if (data) {
						$("#visiting_country").empty();
						$("#visiting_country").append('<option value="">Visiting Country</option>');
						$.each(data, function (key, value) {
							$("#visiting_country").append('<option value="' + value.slug_url + '">' + value.country_name + '</option>');
						});

					} else {
						$("#state").empty();
					}
				}
			});
		}
	}
</script>
@endsection
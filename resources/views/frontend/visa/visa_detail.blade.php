@extends('frontend.layouts.master')
@push('title') {{$visaDes->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$visaDes->meta_keywords}}">
<meta name="description" content="{{$visaDes->meta_description}}">
<meta property="og:description" content="{{$visaDes->meta_description}}">
<meta name="twitter:description" content="{{$visaDes->meta_description}}">
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
            <a href="{{route('frontend.visa-consultation-services')}}">Visa</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$visaDes->visiting_country}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$visaDes->visiting_country}} Visa</h1>
          <p class="page-subtitle">
         Apply for your {{$visaDes->visiting_country}} Visa with Dook for a hassle-free experience
          </p>
        </div>
      </div>
    </section>

<div class="container">
	<div class="row mt-4">
		<div class="col-md-12 ">
			<!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a
					href="{{route('frontend.visa-consultation-services')}}">Visa</a>/ {{$visaDes->visiting_country}}
				Visa</p>

			<div class=" text-center">
				<h1 class="section-title">{{$visaDes->visiting_country}} Visa</h1>
				<p class="section-subtitle ">Apply for your {{$visaDes->visiting_country}} Visa with Dook for a hassle-free experience</p>
			</div> -->
			<div class="row justify-content-center mb-4">
				@include('frontend.common.visa_search')
			</div>
			<hr>
		</div>
		<div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
			<ul class="nav nav-tabs product_detail d-flex" style="border-bottom:none">
				<li class="active"><a href="#first">Visa Types</a></li>
				@if($visaDes->required_documents != "" || $visaDes->required_documents != null)
				<li><a href="#second">Documents</a></li>
				@endif
				@if($visaDes->eligibility_criteria != "" || $visaDes->eligibility_criteria != null)
				<li><a href="#third">Eligibility Criteria</a></li>
				@endif
				@if($visaDes->exemptions != "" || $visaDes->exemptions != null)
				<li><a href="#fourth">Exemptions</a></li>
				@endif
				@if($visaDes->general_information != "" || $visaDes->general_information != null)
				<li><a href="#five">Guidelines</a></li>
				@endif
				@if($visaDes->faqs != "" || $visaDes->faqs != null)
				<li><a href="#six">FAQs</a></li>
				@endif
				@if($visaDes->visa_process != "" || $visaDes->visa_process != null)
				<li><a href="#seventh"> Process</a></li>
				@endif
			</ul>
		</div>

		<div class="col-md-9 mb-5">
			<div class="row visa-infoContent" id="first">
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
					<div class="visaData mt-4" id="second">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> Documents Required for {{$visaDes->visiting_country}} Visa</h2>
						</div>
						<div class="">
							{!! $visaDes->required_documents !!}
						</div>
					</div>
					@endif
					@if($visaDes->eligibility_criteria != "" || $visaDes->eligibility_criteria != null)
					<div class="visaData mt-4" id="third">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> {{$visaDes->visiting_country}} Visa Eligibility Criteria</h2>
						</div>
						<div class="">
							{!! $visaDes->eligibility_criteria!!}
						</div>
					</div>
					@endif
					@if($visaDes->exemptions != "" || $visaDes->exemptions != null)
					<div class="visaData mt-4" id="fourth">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> {{$visaDes->visiting_country}} Visa Exemptions</h2>
						</div>
						<div class="">
							{!! $visaDes->exemptions!!}
						</div>
					</div>
					@endif
					@if($visaDes->general_information != "" || $visaDes->general_information != null)
					<div class="visaData mt-4" id="six">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> {{$visaDes->visiting_country}} Visa General Information</h2>
						</div>
						<div class="">
							{!! $visaDes->general_information!!}
						</div>
					</div>
					@endif
					@if($visaDes->faqs != "" || $visaDes->faqs != null)
					<div class="visaData mt-4" id="six">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> {{$visaDes->visiting_country}} Visa FAQs</h2>
						</div>
						<div class="">
							{!! $visaDes->faqs !!}
						</div>
					</div>
					@endif
					@if($visaDes->visa_process != "" || $visaDes->visa_process != null)
					<div class="visaData mt-4" id="seventh">
						<div class="heading mb-3 mt-3">
							<h2 class="m-0 text-dark"> {{$visaDes->visiting_country}} Visa Application Process</h2>
						</div>
						<div class="">
							{!! $visaDes->visa_process!!}
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-3">

			@include('frontend.common.bookwithconfidence')
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
	document.addEventListener("DOMContentLoaded", function () {
		document.querySelectorAll(".nav-tabs a").forEach(function (tab) {
			tab.addEventListener("click", function (event) {
				event.preventDefault();
				let target = document.querySelector(this.getAttribute("href"));

				if (target) {
					// Remove active class from all tabs
					document.querySelectorAll(".nav-tabs li").forEach(function (li) {
						li.classList.remove("active");
					});

					// Add active class to the clicked tab
					this.parentElement.classList.add("active");

					// Smooth scroll to the section
					window.scrollTo({
						top: target.offsetTop - 180, // Adjust for navbar height if needed
						behavior: "smooth"
					});

					// Show the corresponding section
					document.querySelectorAll(".tab-pane").forEach(function (pane) {
						pane.classList.remove("show", "active");
					});

					target.classList.add("show", "active");
				}
			});
		});
	});
</script>
<script>
	$(document).ready(function () {
		var value = "<?php echo $visaDes->phCountryIso2; ?>";
		var slugVisiting = "<?php echo $visaDes->slug_url; ?>";
		$('#residence_country').val(value);
		$('#residence_country').select2();

		getVisitingCountry();

		$('#residence_country').on("change", function (e) {
			getVisitingCountry();
		});

		// Visiting Country
		$('#visiting_country').select2().on("change", function (e) {
			var slug = $("#visiting_country").val();
			if (slug != null && slug !== "") {
				window.location = "{{url('/')}}/" + slug;
			}
		});

		function getVisitingCountry() {
			var slugVisiting = "<?php echo $visaDes->slug_url; ?>";
			$('.loaderBg').css('display', 'block');
			var country_iso_2 = $("#residence_country").val();
			if (country_iso_2 != null && country_iso_2 !== "") {
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
								var selectedVal = (slugVisiting === value.slug_url) ? 'selected' : '';
								$("#visiting_country").append('<option value="' + value.slug_url + '" ' + selectedVal + '>' + value.country_name + '</option>');
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

		// $(".VisaTabs").click(function(){
		//   $('.VisaTabs').removeClass('active');
		//   $(this).addClass('active');
		// });
	});
</script>
@endsection
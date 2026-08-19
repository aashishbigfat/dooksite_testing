@extends('frontend.layouts.master')
@push('title') {{$review_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$review_header->meta_keywords}}">
<meta name="description" content="{{$review_header->meta_description}}">@endpush 

@section('content')
<section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Review</span>
          </div>
        </div>
      </div>
    </section>
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$review_header->title}}</h1>
         
        </div>
      </div>
    </section>

<section class="section_widget p-4">
 <div class="container mb-4">
      
 	<div class="row">
 		<div class="col-md-9">
 			  <div class="row" id="tourPackages">
              @include('frontend.common.review_card')
           </div>
	          <div id="departuresLoadMoreBtn">
	            @if($reviews->hasMorePages())
	                <div class="col-md-12 mt-4 text-center">
	                    <div id="loader1" class="loader" style="display:none;">
	                        <div class="spinner-border text-danger" role="status">
	                            <span class="sr-only">Loading...</span>
	                        </div>
	                    </div>
	                    <button id="loadMoreDeparturesBtn" class="btn btn-danger">Load More</button>
	                </div>
	            @endif
	        </div>
 		</div>
 		<div class="col-md-3">
 			<div class="shadow p-3 mb-3 rounded book_with_confidence m-3" style="background: #F8F8F8;">
 				<div class="border-bottom p-2"><h5>ReviewUs</h5></div>
					<form id="reviewFormSubmit" style="position: sticky; top: 120px; z-index: 12">
                <div class="row">
                  <div class="col-12 mt-2">
                    <div class="form-group mb-2">
                    	<label style="font-size: 13px;" class="color_gray mb-1">Full Name*</label>
                      <input type="text" placeholder="*Name" name="name" id="name" value="" class="form-control" style="height: 30px;">
                      <span class="spanColor name_error"></span>
                    </div>
                    <div class="form-group mb-2">
                    	<label style="font-size: 13px;" class="color_gray mb-1">Mobile No*</label>
                      <input type="text" placeholder="*Mobile" name="mobile" id="mobile" value="" class="form-control" style="height: 30px;">
                      <span class="spanColor mobile_error" id="mobile_error"></span>
                      <span class="spanColor" id="mobile_errors"></span>
                    </div>
                    <div class="form-group mb-2">
                    	<label style="font-size: 13px;" class="color_gray mb-1">Email ID*</label>
                      <input type="email" placeholder="*Email" name="email" id="email" value="" class="form-control" style="height: 30px;">
                       <span class="spanColor" id="email_error"></span>
                        <span class="spanColor" id="email_errors"></span>
                    </div>
                    <div class="form-group mb-2">
                    	<label style="font-size: 13px;" class="color_gray mb-1">Your Review</label>
                      <textarea name="description" placeholder="*Write your review..." rows="4" class="form-control"></textarea>
                      <span class="spanColor description_error"></span>
                    </div>
                    <div class="form-group mb-2">
                     <fieldset class="rating">
        						    <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
        						    <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
        						    <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
        						    <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
        						    <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
        						    <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
        						    <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
        						    <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
        						    <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
        						    <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
        						</fieldset>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12">
                    <div class="form-group text-center">
                      <div class="enquiry-submit" ><button type="button" id="reviewSubmit" class="btn btn-danger">Submit</button>
                        <span class="text-success d-block" id="message" style="margin-right: 10px"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
           </div>
             @include('frontend.common.bookwithconfidence')
 		</div>
 	</div>

 </div>
</section>

   @include('frontend.common.testimonial')

<script type="text/javascript">
	let departurePage = 2;
	$('#loadMoreDeparturesBtn').click(function() {
	    $('#loader1').show();
	    $('#loadMoreDeparturesBtn').hide();
	    
	    $.ajax({
	        url: "{{ url()->current() }}?page=" + departurePage,
	        type: "GET",
	        success: function(data) {
	            $('#tourPackages').append(data.reviews);
	            departurePage++;
	            if (!data.hasMoreDepartures) {
	                $('#loadMoreDeparturesBtn').hide();
	                $('#noMoreDeparturesMsg').show();
	            } else {
	                $('#loadMoreDeparturesBtn').show();
	            }

	            // Hide the loader
	            $('#loader1').hide();
	        },
	        error: function() {
	            alert('Error loading more packages');
	            $('#loader1').hide();
	            $('#loadMoreDeparturesBtn').show();
	        }
	    });
	});
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#reviewSubmit').click(function (e) {
      e.preventDefault();
      
      var name = $('#name').val();
      if (name == "") {
          $(".name_error").html('This field is required!');
          $("input#name").focus();
          return false;
      }
      
      var mobile = $('#mobile').val();
      if (mobile == "") {
          $(".mobile_error").html('This field is required!');
          $("input#mobile").focus();
          return false;
      }
      
      var email = $('#email').val();
      if (email == "") {
          $(".email_error").html('This field is required!');
          $("input#email").focus();
          return false;
      }
      
      var description = $('#description').val();
      if (description == "") {
          $(".description_error").html('This field is required!');
          $("textarea#description").focus();
          return false;
      }
      
      $('#reviewSubmit').prop('disabled', true);
      $('#reviewSubmit').html('Please wait...');
      
      var formDatas = new FormData(document.getElementById('reviewFormSubmit'));
      
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: "{{ route('frontend.reviews_store') }}",
        data: formDatas,
        contentType: false,
        processData: false,
        success: function (data) {
          $('#message').html("<span class='successmsg'>Review submitted successfully!</span>");
          
          // Reset form fields
          $('#reviewFormSubmit')[0].reset();
          
          // Clear error messages
          $('.name_error, .mobile_error, .email_error, .description_error').html('');
          
          $('#reviewSubmit').prop('disabled', false);
          $('#reviewSubmit').html('Submit');
        },
        error: function () {
            $('#message').html("<span class='errormsg'>Something went wrong!</span>");
            $('#reviewSubmit').prop('disabled', false);
            $('#reviewSubmit').html('Submit');
        }
      });
    });

    ////// Check validation
    jQuery(document).ready(function() {
      jQuery('#email').keyup(function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
        if (!re) {
            jQuery("#email_error").hide();
            jQuery('#email_errors').html('Please enter a valid email.');
        } else {
            jQuery('#email_errors').hide();
        }
      });
      
      jQuery("#mobile").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && e.which != 43 && e.which != 107 && (e.which < 48 || e.which > 57)) {
          // Display error message
          jQuery("#mobile_errors").html("Digits Only").show().fadeOut(3000);
          return false;
        }
      });
    });
  });
</script>



@endsection

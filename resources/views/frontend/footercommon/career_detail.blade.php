@extends('frontend.layouts.master')
@push('title') {{$career_detail->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$career_detail->meta_keywords}}">
<meta name="description" content="{{$career_detail->meta_description}}">@endpush 

@section('content')
<style>
	ul li {
	  color: gray;
	  font-size: 14px;
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
            <a href="{{route('frontend.careers')}}">Career</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$career_detail->title}}</span>
          </div>
        </div>
      </div>
    </section>
    
<section class="section_widget p-4">
 <div class="container mb-4">
      <div class="row">
         <!--  <div class="col-md-12">
              <p class="color_gray"><a href="/" class="text-danger">Home</a> / <a href="{{route('frontend.careers')}}">Career</a> / {{$career_detail->title}} </p>
          </div> -->
     
	        <div class="row">
		        <div class="col-md-8">
		        	<div class="sectionHeading card rounded p-3">
						<div class="row">
							<div class="col-md-5">
							 <h5>{{$career_detail->title}}</h5>
						        <p> {{$career_detail->role}}</p>
							</div>
							<div class="col-md-7">
							  <div class="row">
									<div class="col-md-4">
									  <p> Location: <br><strong>India</strong></p>
									</div>
									<div class="col-md-4">
										<p> No. of Positions :<br> <strong>{!! $career_detail->position !!}</strong></p>
									</div>
									<div class="col-md-4">
										<p> Experience : <br><strong>Minimum {!! $career_detail->exp !!} Years</strong></p>
									</div>
					         </div>
						   </div>
			 	       </div>
			        </div>
		                   
		  
		              <p>
		                {!! $career_detail->description !!}
		              </p>
		 
		        </div>
		        <div class="col-md-4">
		           <div class="shadow p-3 mb-3 rounded book_with_confidence m-3" style="background: #F8F8F8;">
		            <div class="border-bottom p-2"><h5>Apply Now</h5></div> 
		            <p class="mt-4">💼 Looking to join our team? Send your resume to <b>hr@dooktravels.com</b></p>
		             {{-- <form id="finalSubmitJob" enctype="multipart/form-data">

		                <input type="hidden" name="country_code" id="career_country_code" value="">

		                <input type="hidden" name="url" id="url" value="{{url()->current()}}">
		                <input type="hidden" name="title" value="{{$career_detail->title}}">
		                <input type="hidden" name="role" value="{{$career_detail->role}}">
		                <div class="form-group">
		                	<label style="font-size: 13px;" class="color_gray mb-1">Full Name*</label>
		                  <input type="text" placeholder="*Name" name="name" id="name" value="" class="form-control" style="height: 30px;">
		                  <span class="spanColor name_error"></span>
		                </div>
		                <div class="form-group">
		                	<label style="font-size: 13px;" class="color_gray mb-1">Mobile*</label>
		                  <input type="text" placeholder="*Mobile" name="mobile" id="mobile" value="" class="form-control" style="height: 30px;">
		                  <span class="spanColor mobile_error" id="mobile_error"></span>
		                  <span class="spanColor" id="mobile_errors"></span>
		                </div>
		                <div class="form-group">
		                	<label style="font-size: 13px;" class="color_gray mb-1">Email*</label>
		                  <input type="email" placeholder="*Email" name="email" id="email" value="" class="form-control" style="height: 30px;">
		                   <span class="spanColor" id="email_error"></span>
		                    <span class="spanColor" id="email_errors"></span>
		                </div>
		                <div class="form-group mt-2">
		                	<label style="font-size: 13px;" class="color_gray mb-1">Upload Resume*</label>
		                  <div class="form-control">
		                  	
		                    <label for="formFileSm" class="formFileSm_label" style="float: left;"><i aria-hidden="true" class="fa fa-upload"></i>Choose File</label>
		                    <input type="file" name="resume" id="formFileSm" hidden="hidden" class="form-control" accept="application/pdf,application/msword,.doc, .docx"> 
		                    <span id="file-chosen">No file chosen</span>
		                    <input type="hidden" name="typeName" id="typeName">
		                  </div>
		                 
		                    
		                </div>
		                 <div class="mt-2"><button type="button" name="submit" class="btn btn-danger" id="jobResumeSubmit" style="height:35px;width: 100%;">Submit</button></div>
		                <div id="message"></div>
		              </form> --}}
		            </div>
		          </div>
		        </div>
		      </div>
	    </div>
	</div>
</section>
   @include('frontend.common.testimonial')
<script type="text/javascript">
  $('input[type="file"]').change(function(e) {
    var geekss = e.target.files[0].name;
    var type = e.target.files[0].type;
    //alert(type);
    $("#file-chosen").html(geekss);
    $("#typeName").html(type);
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#jobResumeSubmit').click(function (e) {
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
      $('.loaderBg').css('display','block');
      var formDatas = new FormData(document.getElementById('finalSubmitJob'));
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: "{{ route('frontend.resume_store') }}",
        data: formDatas,
        contentType: false,
        processData: false,
        success: function (data) {
          $('.loaderBg').css('display','none');
          if(data.error == false){
            $('#message').html("<span class='sussecmsg text-success text-center'>"+data.message+"</span>");
            $('#name').val('');
            $('#mobile').val('');
            $('#email').val('');
            // window.location.reload();
          }else{
            $('#message').html("<span class='sussecmsg text-danger text-center'>"+data.message+"</span>");
          }
          
        },
        errors: function (data) {
          console.log('j')
          $('.loaderBg').css('display','none');
            $('#message').html("<span class='sussecmsg text-danger text-center'>"+data.message+"</span>");
        }
      });
    });
  });

  ////// check validation
jQuery(document).ready(function() {
  jQuery('#email').keyup(function() {
    var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
    if(!re) {
        jQuery("#email_error").hide();
        jQuery('#email_errors').html('Please enter valid email.');
        
    } else {
        jQuery('#email_errors').hide();
        
    }
  })
  jQuery("#mobile").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
      //display error message
      jQuery("#mobile_errors").html("Digits Only").
      show().fadeOut(3000);
      return false;
    }
  });
});
</script>
@endsection

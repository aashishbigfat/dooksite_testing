<!-- home section -->
<nav class="navbar navbar-expand-md bg-body-tertiary navbar-light bg-white sticky-top">
  <div class="container-xl">
  	  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  <a class="navbar-brand" href="{{route('frontend.index')}}">
			<img src="{{asset('assets/images/logo.png')}}" />
		</a>
    <div class="col-md-3 d-flex justify-content-end d-lg-none d-block">
			<p><a class="agent_btn btn border-danger border-2 text-danger" href="javascript:void(0)" tabindex="-1" aria-disabled="true">Agent Connect</a></p>
	<p class="mx-2"><a class="agent_btn btn btn-danger border-2 text-white" href="javascript:void(0)" tabindex="-1" aria-disabled="true">Login</a></p>
		</div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <div class="row mx-4">
    <div class="col-md-12">
      <div class="row">
		  <div class="col-md-8">
			<div class="contact-info d-md-flex" style="">
			        <p class="p-0 m-0"><a class="nav-link an" aria-current="page" href="javascript:void(0)"><i class="fa fa-phone"></i> +911140001000</a> </p>
			      <p class="p-0 m-0"><a href="mailto:sales@dooktravels.com" class="nav-link an"><i class="fa fa-envelope text-danger mx-2"></i>sales@dooktravels.com</a></p>
			      </div>
			</div>
		  <div class="col-md-4 justify-content-end d-flex">
			   <ul class="social_icons">
					<li class="facebook">
						<a href="https://www.facebook.com/dooktravels"><i class="fa fa-facebook"
								aria-hidden="true"></i></a>
					</li>
					<li class="youtube">
						<a href="https://www.youtube.com/user/explorebug"><i class="fa fa-youtube-play"></i></a>
					</li>
					<li class="twitter">
						<a href="https://twitter.com/dooktravels"><i class="fa fa-twitter-square"></i></a>
					</li>
					<li class="instagram">
						<a href="https://www.instagram.com/dooktravels/"><i class="fa fa-instagram"></i></a>
					</li>
				</ul>
			  </div>
		   </div>
		 </div>
       <div class="col-md-12" style="border-top: 2px solid red;" >
	     	<div class="row mt-2">
	     		<div class="col-md-9">
	     			 <ul class="navbar-nav ">
				        <li class="nav-item"> <a class="nav-link active" aria-current="page" href="{{route('frontend.index')}}">Home</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="javascript:void(0)" id="navbarDropdown" role="button"
								data-bs-toggle="dropdown" aria-expanded="false">Tours <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<li><a class="dropdown-item" href="{{url('international-tour-packages')}}">International Tours</a></li>
								<li><a class="dropdown-item" href="{{url('domestic-tour-packages')}}">Domastic Tours</a></li>				
							</ul>
						</li>
						<li class="nav-item"><a class="nav-link" href="{{url('group-tours')}}" tabindex="-1" aria-disabled="true">Group Tours</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
								aria-expanded="false"> Experience <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li>
									<hr class="dropdown-divider" />
								</li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</li>
						<li class="nav-item"><a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Visa</a></li>
						<li class="nav-item"> <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Blog</a></li>
				      </ul>
	     		</div>
	     		<div class="col-md-3 d-lg-block d-none">
	     			<div class="d-flex justify-content-end">
	     			<p><a class="agent_btn btn border-danger border-2 text-danger"
						href="javascript:void(0)" tabindex="-1" aria-disabled="true">Agent Connect</a></p>
				<p class="mx-2"><a class="agent_btn btn btn-danger border-2 text-white" href="javascript:void(0)"
						tabindex="-1" aria-disabled="true">Login</a></p>
					</div>
	     		</div>
	     	</div>
	      </div>
       </div>
	</div>
  </div>
</nav>
@extends('layouts.app')
@section('title', 'Registration')

@section('content')
<section class=" position-relative">
  <picture class="homeBannerimg">
    <img src="https://adm.dookinternational.com/dook/images/landing/BAY2T1671519116.jpg" class="home_banner img-fit" alt="">
    <div class="bannerOverlay"></div>
  </picture>
  <div class="BannerCenterSection TopMargin">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="banner-text text-white">
            <h1>Registration</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<main class="mt-5">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registration</li>
      </ol>
    </nav>
  </div>
  <section class="section_widget mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-xl-8 m-auto">
          <div class="contact-usContainer d-md-flex">
            <div class="addressPart">
              <div class="contactHeading"><h2>New Registration</h2>
                <p> </p></div>
              <p class="margin-0 contactInfo"><i class="fas fa-phone"></i> <a href="tel: +91-11-4000-1000"> +91-11-4000-1000</a></p>
              <p class="margin-0 contactInfo"><i class="fab fa-whatsapp"></i> <a href="https://web.whatsapp.com/send?phone=8368513675&amp;text=I'm%20interested" target="_blank">+91 8368513675</a></p>
              <p class="margin-0 contactInfo"><i class="fas fa-envelope"></i> <a href="mailto:sales@dooktravels.com"> sales@dooktravels.com</a></p>
              <ul class="list-inline list-social-icons">
                <li class="list-inline-item"><a href="https://www.facebook.com/dooktravels" class="social-icon" rel="nofollow"><i class="fab fa-facebook"></i></a></li>
                <li class="list-inline-item"><a href="https://twitter.com/dooktravels" class="social-icon" rel="nofollow"><i class="fab fa-twitter"></i></a></li>
                <li class="list-inline-item"><a href="https://www.instagram.com/dooktravels/" class="social-icon" rel="nofollow"><i class="fab fa-instagram"></i></a></li>
                <li class="list-inline-item"><a href="https://www.youtube.com/user/explorebug" class="social-icon" rel="nofollow"><i class="fab fa-youtube"></i></a></li>
              </ul>
            </div>
            <div class="formPart">
              <form action="" class="aa-login-form" id="frmRegistration">
             
                <div class="row">

                  <div class="col-md-12">
                    <span class="spanColor name_error"></span>
                    <div class="form-group">
                      
                      <label for="">Name<span>*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                    <div id="name_error" class="field_error"></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <span class="spanColor" id="email_error"></span>
                      <span class="spanColor" id="email_errors"></span>
                    <div class="form-group">
                      <label for="">Email<span>*</span></label>
                    <input type="email" name="email" placeholder="Email" required class="form-control">
                    <div id="email_error" class="field_error"></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <span class="spanColor mobile_error" id="mobile_error"></span>
                    <span class="spanColor" id="mobile_errors"></span>
                    <div class="form-group">
                     <label for="">Password<span>*</span></label>
                    <input type="password" placeholder="Password" name="password" class="form-control">
                    <div id="password_error" class="field_error"></div> 
                    </div>
                  </div>
                    <div class="col-md-12">
                    <span class="spanColor mobile_error" id="mobile_error"></span>
                    <span class="spanColor" id="mobile_errors"></span>
                    <div class="form-group">
                      <label for="">Mobile<span>*</span></label>
                    <input type="text" name="mobile" placeholder="Mobile" required class="form-control">
                    <div id="mobile_error" class="field_error"></div>
                    </div>
                  </div>
                
                
                </div>
                <div class="row">
                  <div class="col-12 text-end">
                       <button type="submit" class="btn btn-primary button-roundRed" id="btnRegistration">Register</button>    
                    @csrf     
                  </div>
                </div>
              </form>
               <div id="thank_you_msg" class="field_error"></div>
            </div>
          </div>
      
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
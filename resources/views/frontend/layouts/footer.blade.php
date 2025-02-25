 <!-- section footer -->
   <?php 
        $footer = DB::table('footer_settings')->first();
        ?>
 <section class="bg-dark p-3 footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 footer_just_call d-flex justify-content-center align-items-center">
                <h1>New to tour travels? No Problem <img src="{{asset('assets/images/icons/phone-call.png')}}" /> Just call us!<b> {{$footer->phone}}</b></h1>
            </div>
            <div class="col-md-4 foot_abut">
                <p class="mt-4">  {!! $footer->about !!}</p>
                <div class="row">
                    <div class="col-md-6 pb-2 col-6">
                        <a class="" aria-current="page" href="tel:{{$footer->phone}}"><img src="{{asset('assets/images/icons/phone.png')}}" /> {{$footer->phone}}</a>
                    </div>
                    <div class="col-md-6 pb-2 col-6">
                        <a class="" aria-current="page" href="https://api.whatsapp.com/send?phone={{$footer->mobile}}"><img src="{{asset('assets/images/icons/message-circle.png')}}" /> +{{$footer->mobile}}</a>
                    </div>
                    <div class="col-md-12 pb-2 col-8">
                        <a class="" aria-current="page" href="mailto:{{$footer->email}}"><img src="{{asset('assets/images/icons/mail.png')}}" /> {{$footer->email}}</a>
                    </div>
                    <div class="col-md-8 pb-2 col-12">
                        <a class="" aria-current="page" href="#"><img src="{{asset('assets/images/icons/map-pin.png')}}" /> {!! $footer->address !!}</a>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-danger mt-2"> View All Addresses</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 foot_abut mt-3">
                <h6 class="text-white">Quick Links</h6>
                <div class="row mt-4">
                    <div class="col-md-6 col-6">
                        <ul class="p-0">
                            <li><a href="{{route('frontend.regions')}}">Region</a></li>
                            <li><a href="{{route('frontend.countries')}}">Countries</a></li>
                            <li><a href="{{route('frontend.destinations')}}">Destinations</a></li>
                            <li><a href="{{route('frontend.experiences')}}">Experiences</a></li>
                            <li><a href="{{route('frontend.activities')}}">Activities</a></li>
                            <li><a href="{{route('frontend.international-tour-packages')}}">International Tours</a></li>
                            <li><a href="{{route('frontend.domestic-tour-packages')}}">Domestic Tours</a></li>
                            <li><a href="{{route('frontend.group-tours')}}">Group Tours</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-6">
                        <ul class="p-0">
                            <li><a href="{{route('frontend.visa-consultation-services')}}">Visa</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="#">Review Us</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Piresentations</a></li>
                            <li><a href="#">Pirivacy Piolicy</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
           <div class="col-md-4 foot_abut">
            <div class="row">
                <div class="col-md-12 col-4">
                        <h6 class="text-white">Connect with us</h6>
                        <ul class="social_icons p-0 mt-4">
                            <li class="facebook">
                                <a href="https://www.facebook.com/dooktravels"><i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
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
                <div class="col-md-12 col-7">
                        <div class="newsletter pt-2">
                            <h6 class="pb-2">Subscribe to Newsletter</h6>
                            <input placeholder="Enter Email"><button class="btn btn-danger">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-md-12 mt-4 border-top">
                 <div class="row">
                     <div class="col-md-6 pt-4 col-6">
                         <p class="text-white p-0 m-0">Copyright © <script>
                                        new Date().getFullYear() > document.write(new Date().getFullYear());
                                    </script> , Dook International</p>
                     </div>
                     <div class="col-md-6 pt-4 d-flex justify-content-end col-6">
                        <p class="text-white p-0 m-0">All rights reserved.Terms & Conditions & Privacy Policy</p>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>

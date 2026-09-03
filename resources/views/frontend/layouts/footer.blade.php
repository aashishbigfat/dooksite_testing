 <!-- section footer -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 footer_links">
                <h3 class="mt-4">Popular Countries</h3>
                 <ul class="listInlinecoma p-0" style="text-align: justify;">
                    @foreach(countries() as $key => $country)
                    <li><a href="{{url('/')}}/{{$country->slug}}" class=""
                            target="_blank">{{$country->name}}</a></li>
                    @endforeach
                </ul>
                <h3 class="mt-4">Popular Destinations</h3>
                 <ul class="listInlinecoma p-0" style="text-align: justify;">
                    @foreach(destinations() as $key => $destination)
                    <li><a href="{{url('destinations')}}/{{$destination->slug}}" class=""
                            target="_blank">{{$destination->name}}</a></li>
                    @endforeach
                </ul>
                <h3 class="mt-4">Popular Experiences</h3>
                 <ul class="listInlinecoma p-0" style="text-align: justify;">
                        @foreach(experiences() as $key => $experience)
                        <li><a href="{{url('/')}}/{{$experience->slug}}" class=""
                                target="_blank">{{$experience->name}}</a></li>
                        @endforeach
                    </ul>
            </div>
        </div>
    </div>
   <?php 
        $footer = DB::table('footer_settings')->first();
        ?>
         <!-- Footer -->
    <footer class="footer">
      <!-- Top Banner -->
      <div class="footer-top-banner">
        <div class="container-xl">
          <div class="phone-icon">
            <i class="fas fa-phone"></i>
          </div>
          <h4 class="text-white">
            Want to Travel the World? Get in Touch with Dook at <span style="color:white !important">{{$footer->phone}}</span>
          </h4>
        </div>
      </div>

      <!-- Main Footer Content -->
      <div class="footer-content">
        <div class="container-xl">
          <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="footer-section">
                <p class="footer-description">
                  {!! $footer->about !!}
                </p>
                <ul class="footer-contact-info">
                  <li> <a class="" aria-current="page" href="tel:{{$footer->phone}}" target="_blank">
                    <i class="fas fa-phone"></i>
                    <span class="text-white">{{$footer->phone}}</span> </a>
                  </li>
                  <li> <a class="" aria-current="page" href="https://api.whatsapp.com/send?phone={{$footer->mobile}}" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    <span class="text-white">+{{$footer->mobile}}</span> </a>
                  </li>
                  <li><a class="" aria-current="page" href="mailto:{{$footer->email}}" target="_blank">
                    <i class="fas fa-envelope"></i>
                    <span class="text-white">{{$footer->email}}</span></a>
                  </li>
                     <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="text-white">Reg Office: 44, 2nd Floor, Regal Building, Connaught Place, New Delhi-110001</span
                    >
                  </li>
                  <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="text-white">{!! $footer->address !!}</span
                    >
                  </li>
                  <li> <a href="{{route('frontend.contact_us')}}" class="view-addresses-btn">View All Addresses</a></li>
                </ul>
              </div>
            </div>

            <!-- Quick Links Column 1 -->
            <div class="col-lg-2 col-md-6 mb-4 col-6">
              <div class="footer-section">
                <h5>Quick Links</h5>
                <ul class="footer-links">
                  <li><a href="{{route('frontend.regions')}}" target="_blank">Region</a></li>
                  <li><a href="{{route('frontend.countries')}}" target="_blank">Countries</a></li>
                  <li><a href="{{route('frontend.destinations')}}" target="_blank">Destinations</a></li>
                  <li><a href="{{route('frontend.experiences')}}" target="_blank">Experiences</a></li>
                  <li><a href="{{route('frontend.activities')}}" target="_blank">Activities</a></li>
                  <li><a href="{{route('frontend.international-tour-packages')}}" target="_blank">International Tours</a></li>
                  <li><a href="{{route('frontend.domestic-tour-packages')}}" target="_blank">Domestic Tours</a></li>
                  <li><a href="{{route('frontend.group-tours')}}" target="_blank">Group Tours</a></li>
                </ul>
              </div>
            </div>

            <!-- Quick Links Column 2 -->
            <div class="col-lg-2 col-md-6 mb-4 col-6">
              <div class="footer-section">
                <ul class="footer-links" style="margin-top: 57px">
                   <li><a href="{{route('frontend.visa-consultation-services')}}" target="_blank">Visa</a></li>
                    <li><a href="{{route('frontend.contact_us')}}" target="_blank">Contact Us</a></li>
                    <li><a href="{{route('frontend.faqs')}}">FAQs</a></li>
                    <li><a href="{{route('frontend.reviews')}}">Review Us</a></li>
                    <li><a href="{{route('frontend.careers')}}">Careers</a></li>
                    <li><a href="{{route('frontend.presentations')}}">Presentations</a></li>
                    <li><a href="{{route('frontend.privacy_policy')}}" target="_blank">Privacy Policy</a></li>
                    <li><a href="{{route('frontend.terms_conditions')}}" target="_blank">Terms & Conditions</a></li>
                </ul>
              </div>
            </div>

            <!-- Connect With Us -->
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="footer-section connect-section">
                <h5>Connect with us</h5>
               <div class="social-media-icons">
                  <a href="https://www.facebook.com/dooktravels" target="_blank" class="facebook" aria-label="Follow us on Facebook" title="Facebook">
                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                  </a>
                  <a href="https://www.youtube.com/user/explorebug" target="_blank" class="youtube" aria-label="Watch us on YouTube" title="YouTube">
                    <i class="fab fa-youtube" aria-hidden="true"></i>
                  </a>
                  <a
                    href="https://twitter.com/dooktravels" target="_blank" class="twitter" aria-label="Follow us on Twitter" title="Twitter">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                  </a>
                  <a
                    href="https://www.instagram.com/dooktravels/" target="_blank" class="instagram" aria-label="Follow us on Instagram" title="Instagram">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                  </a>
                </div>

                  <!-- Office Video Card -->
                <div class="office-video-card" onclick="openVideoModal()">
                  <img
                    src="{{asset('assets/wtt.webp')}}"
                    alt="World Trade Tower Noida Office"
                    class="video-thumbnail"
                  />
                  <div class="video-overlay">
                   <a href="https://www.youtube.com/shorts/yPI8dKZvmJE" target="_blank" aria-label="Watch Our Office"> <div class="play-button">
                      <i class="fas fa-play"></i>
                    </div> 
                  </a>
                    <h4 class="video-title">Tour Our Office</h4>
                    <p class="video-subtitle">World Trade Tower, Noida</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <div class="container-xl">
          <div class="row">
            <div class="col-lg-6 col-12">
              <p class="copyright-text">
                Copyright © <script> new Date().getFullYear() > document.write(new Date().getFullYear());</script> , Dook International
              </p>
            </div>
            <div class="col-lg-6 col-12">
              <div class="footer-bottom-links">
                <span style="color: #999; font-size: 14px"
                  >All rights reserved.</span
                >
                <a href="{{route('frontend.terms_conditions')}}">Terms & Conditions</a>
                <span style="color: #999; font-size: 14px">&</span>
                <a href="{{route('frontend.privacy_policy')}}">Privacy Policy</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  <!-- WhatsApp Float Button -->
    <a href="https://api.whatsapp.com/send?phone=+918368513675&text=Hi%20there%20!%20%E2%9C%88%EF%B8%8F%20I%20am%20thinking%20about%20a%20trip%20--%20need%20some%20ideas%20%F0%9F%8C%8D" class="whatsapp-float" target="_blank" alt="WhatsApp Us: 918130014536" aria-label="Contact via WhatsApp"><span class="visually-hidden">Chat on WhatsApp</span>
      <i class="fab fa-whatsapp"></i>
    </a>
  <!-- Enquire Now Button -->
    <button class="enquire-btn" onclick="openEnquiryModal()">
      <i class="fas fa-envelope"></i>
      ENQUIRE NOW
    </button>

   <!-- Enquiry Modal -->
    <div class="enquiry-modal" id="enquiryModal">
      <div class="modal-content">
        <div class="modal-header">
          <i class="fas fa-envelope"></i>
          <div>
            <h3>Send us a Query</h3>
            <p>
              Ready to venture out into the world? Fill the form below and start
              your brand new journey with us
            </p>
          </div>
          <button class="modal-close" onclick="closeEnquiryModal()" aria-label="Close modal">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <form id="commonInquiryForm">
            @csrf
             <input type="hidden" name="type" id="typeM" value="APL">
              <input type="hidden" name="url" id="url" value="{{ url()->current() }}">

              <input type="hidden" name="fullurl" id="fullurl" value="{{ url()->full() }}">

              <input type="hidden" name="pkg_id" id="pkg_id"
                value="@if(isset($departure->dep_dook_ref_id)){{$departure->dep_dook_ref_id}}@endif">

              <input type="hidden" name="duration" id="duration"
                value="@if(isset($departure->no_of_nights)){{$departure->no_of_nights}}@endif">

           <!--    <input type="hidden" name="destinations" id="destinations"
                value="@if(isset($dest_array)){{ implode(',',$dest_array) }}@endif"> -->

              <input type="hidden" name="pg_region" id="pg_region"
                value="@if(isset($region->region_name)){{$region->region_name}}@endif">
              <input type="hidden" name="pg_country" id="pg_country"
                value="@if(isset($comonInquiryCountry)){{$comonInquiryCountry}}@endif">
              <!-- <input type="hidden" name="destination" id="destination"
                value="@if(isset($destination->dest_name)){{$destination->dest_name}}@endif"> -->
              <input type="hidden" name="browserName" id="browserName">
            
             <input type="hidden" name="dep_type" id="dep_type" value="@if(isset($departure->destinations[0]->country)){{ $departure->destinations[0]->country }}@elseif(isset($departures[0]->dep_type)){{ $departures[0]->dep_type }}@elseif(isset($departures[0]->slug1)){{ $departures[0]->slug1 }}@endif">
            
              <input type="hidden" name="min_country_data" id="min_country_data"
                value="{{ isset($min_country_data) ? json_encode($min_country_data) : '' }}">

                <input type="hidden" name="fixed_departure" id="fixed_departure" value="{{ (isset($departure->departure_dates) && count($departure->departure_dates) > 0)?'yes':'no' }}">

              <input type="hidden" name="form_type" id="form_type" value="{{ isset($form_type)?$form_type:'' }}">

            <div class="form-row">
              <div class="form-group">
                <label for="name">Name*</label>
                <div class="input-with-icon name-input">
                  <input type="text" id="name" name="name" placeholder="Enter your full name" required />
                </div>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <div class="input-with-icon email-input">
                  <input type="email" id="email" name="email" placeholder="Enter your email"  />
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="mobile">Mobile*</label>
                <div class="input-with-icon phone-input">
                  <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" minlength="10" maxlength="10" pattern="[0-9]{10}" required />
                </div>
              </div>
              <div class="form-group">
                <label for="departureDates">Date*</label>
                 @if(isset($departure->departure_dates) && count($departure->departure_dates) > 0)
                  <select id="departureDates" name="travel_date" required>
                      @foreach ($departure->departure_dates as $date => $data)
                          <option value="{{ $date }}"
                              data-price="{{ $data['price'] ?? 0 }}"
                              data-inclusions="{{ json_encode($data['inclusions']) }}">
                              {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                          </option>
                      @endforeach
                  </select>
              @else
                  <input
                      type="date"
                      id="departureDates"
                      name="travel_date"
                      required
                      style="cursor: pointer; width: 100%;"
                      class="form-control"
                  />
              @endif               
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="no_of_traveler">No of travellers</label>
                <input type="number" id="no_of_traveler" name="no_of_traveler" min="1" max="999" maxlength="3"  />
               
              </div>
              <div class="form-group">
                <label for="destination">Destination*</label>
                <div class="input-with-icon destination-input">
                 @if (isset($departure->destinations))
                <div class="col">
                    @if ($departure->destinations->count() == 1) 
                    <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control enquiry"
                      value="{{ $departure->destinations[0]->dest_name }}">
                    @else
                    <select name="destinations_name" id="destination">
                      @forelse ($departure->destinations as $index => $destination)
                      <option value="{{ $destination->dest_name }}" {{ $index==0 ? 'selected' : '' }}>{{ $destination->dest_name
                        }}</option>
                      @empty
                      <option value="No Destination">No Destinations</option>
                      @endforelse
                    </select>
                    @endif
                
                </div>
               @elseif (isset($departure->Destination) && count ($departure->Destination) > 0)
              <div class="col">
                @if (!empty($departure->Destination) && count($departure->Destination) === 1)
                    @php $destination = $departure->Destination[0]; @endphp
                    <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control enquiry"
                        value="{{ $destination['dest_name'] }}">
                @elseif (!empty($departure->Destination) && count($departure->Destination) > 1)
                    <select name="destinations_name" id="destination">
                        @foreach ($departure->Destination as $destination)
                            <option value="{{ $destination['dest_name'] }}">
                                {{ $destination['dest_name'] }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="text" name="destinations_name" placeholder="No destinations available" readonly class="form-control enquiry">
                @endif
              </div>
              @elseif (isset($country_destination_name))
              <div class="col">
                <select name="destinations_name" id="destination">
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
                <select name="destinations_name" id="destination">
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
                <input type="text" name="destinations_name" placeholder="destination*" readonly class="form-control enquiry"
                  value="{{ $destination_name_from_destination_page }}">
              </div>
              @elseif (isset($departure_destination_name))
              <div class="col">
                <select name="destinations_name" id="destination">
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
                  <select name="destinations_name" id="destination">
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
                      <select name="destinations_name" id="destination">
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
                <select name="destinations_name" id="destination">
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
              </div>
            </div>
             <button type="submit" class="submit-btn" id="submitBtn">
                <div class="loading-spinner"></div>
                <div class="success-checkmark"></div>
                <span class="btn-text text-white">Submit Enquiry</span>
            </button>

            <div class="progress-container" id="progressContainer">
                <div class="progress-bar" id="progressBar"></div>
            </div>

            <div class="status-message" id="statusMessage"></div>
          </form>
        </div>
      </div>
    </div>

 @php
  if(isset($_COOKIE['login_email']) && isset($_COOKIE['login_pwd'])){
    $login_email=$_COOKIE['login_email'];
    $login_pwd=$_COOKIE['login_pwd'];
    $is_remember="checked='checked'";
  } else{
    $login_email='';
    $login_pwd='';
    $is_remember="";
  }  
  @endphp 
 <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4" style="border-radius: 20px;height: 470px;">
                <div id="popup_login">
                   <div class="modal-header" style="background: #f4f4f7;">         
                            <div class="col-md-2 col-3">
                                <img src="{{asset('assets/images/Group8098876.png')}}" alt="user" class="w-100 h-100" />
                            </div>
                            <div class="col-md-9 col-9">
                                <h5 class="modal-title text-dark" id="exampleModalLabel">Login Or Register</h5>                         
                            </div>                          
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                    <!-- Modal Body -->
                    <div class="modal-body">                    
                         {{-- LOGIN FORM TEMPORARILY DISABLED FOR MAINTENANCE (commented out 2026-09-01, not deleted) --}}
                         {{--
                         <form class="aa-login-form" id="frmLogin">
                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                    <img src="{{asset('assets/images/mail.png')}}" alt="mail">
                                </span>
                                 <input type="email" id="email_address" placeholder="Enter Email" name="str_login_email" required value="{{$login_email}}" class="form-control ">
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                    <img src="{{asset('assets/images/lock.png')}}" alt="lock">
                                </span>
                               <input type="password" id="password" placeholder="Password" name="str_login_password" required value="{{$login_pwd}}" class="form-control">
                                <span class="input-group-text">
                                    <i class="fa fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input type="checkbox" id="rememberme" name="rememberme" {{$is_remember}}>
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                      <div id="login_msg"></div>
                                </div>
                               <button onclick="forgot_password()" class="form-check-label btn btn-sucess" for="rememberMe" style="color: black;">Forgot
                                    Password?</button>
                            </div>
                            <button type="submit" class="btn btn-danger w-100"  id="btnLogin">Login</button>

                                 <div class="text-center mt-4">
                                Don't have an account? <a href="{{url('registration')}}" class="text-danger"><b>Register now!</b></a>
                                 </div>
                        @csrf
                        </form>
                        --}}
                        <div class="text-center py-5">
                            <p class="text-dark mb-0" style="font-size: 18px;">Our Website is currently under maintenance. For any enquiry or assistance, please drop us an email at sales@dooktravels.com or call us at +91 83685 13675.</p>
                        </div>
                        </div>                   
                    </div>              
                      <div id="popup_forgot" style="display:none;">
                           <div class="modal-header" style="background: #f4f4f7;">
                                    <div class="col-md-2 col-3">
                                        <img src="{{asset('assets/images/Group8098876.png')}}" alt="user" class="w-100 h-100" />
                                    </div>
                                    <div class="col-md-9 col-9">
                                        <h5 class="modal-title " id="exampleModalLabel">Forgot Password</h5>                         
                                    </div>                          
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                            <!-- Modal Body -->
                            <div class="modal-body">
                              <form class="aa-login-form" id="frmForgot">
                                 <div class="input-group mb-4">
                                 <span class="input-group-text">
                                <img src="{{asset('assets/images/mail.png')}}" alt="">
                                </span>
                                <input type="email" id="email_addr" placeholder="Email" name="str_forgot_email" required class="form-control" placeholder="Enter Email">
                                   </div>
                                <button class="btn btn-danger w-100" type="submit" id="btnForgot">Submit</button>
                                <br><br>
                                <div id="forgot_msg"></div>
                               
                                <div class="aa-register-now">
                                  Login Form?<button onclick="show_login_popup()" class="text-danger btn btn-sucess"> Login now!</button
                                  >
                                </div>
                                @csrf
                              </form>
                            </div>
                        </div>                   
                   </div>    
                </div>          
            </div>
        </div>
  <!-- Scroll to Top -->
    <div class="scroll-top">
      <i class="fas fa-arrow-up"></i>
    </div>
   <script type="text/javascript">
     // ===== COUNTER ANIMATION =====
document.addEventListener("DOMContentLoaded", function () {
  const animateCounter = (element) => {
    const target = parseInt(element.getAttribute("data-target"));
    const suffix = element.textContent.replace(/[\d,]/g, "");
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;

    const updateCounter = () => {
      current += increment;
      if (current < target) {
        element.textContent = Math.floor(current) + suffix;
        requestAnimationFrame(updateCounter);
      } else {
        element.textContent = target + suffix;
      }
    };

    updateCounter();
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const statNumber = entry.target.querySelector(".stat-number");
          if (statNumber && !statNumber.classList.contains("animated")) {
            statNumber.classList.add("animated");
            animateCounter(statNumber);
          }
        }
      });
    },
    { threshold: 0.3 }
  );

  document.querySelectorAll(".stat-card").forEach((card) => {
    observer.observe(card);
  });

  // ===== SCROLL TO TOP BUTTON =====
  const scrollTopBtn = document.querySelector(".scroll-top");
  if (scrollTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        scrollTopBtn.classList.add("show");
      } else {
        scrollTopBtn.classList.remove("show");
      }
    });

    scrollTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // ===== MOBILE SUBMENU TOGGLE =====
  const tourLink = document.getElementById("tourLink");
  const tourNavItem = document.getElementById("tourNavItem");

  if (tourLink && tourNavItem) {
    tourLink.addEventListener("click", function (e) {
      if (window.innerWidth <= 991) {
        e.preventDefault();
        tourNavItem.classList.toggle("active");
      }
    });
  }

  // ===== ACCORDION FUNCTIONALITY =====
  const accordionContainer = document.querySelector(".accordion-container");
  let hoverTimeout;

  if (accordionContainer) {
    // Desktop hover with debounce
    accordionContainer.addEventListener(
      "mouseenter",
      function (e) {
        const card = e.target.closest(".experience-card");
        if (card && window.innerWidth > 991) {
          clearTimeout(hoverTimeout);
          hoverTimeout = setTimeout(() => {
            const cards = document.querySelectorAll(".experience-card");
            cards.forEach((c) => c.classList.remove("active"));
            card.classList.add("active");
          }, 50);
        }
      },
      true
    );

    // Mobile - all cards visible
    if (window.innerWidth <= 991) {
      const cards = document.querySelectorAll(".experience-card");
      cards.forEach((card) => card.classList.add("active"));
    }
  }

  // ===== LAZY LOADING IMAGES =====
  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const bgElement = entry.target;
            if (bgElement.dataset.bg) {
              bgElement.style.backgroundImage = `url('${bgElement.dataset.bg}')`;
              bgElement.style.transition = "opacity 0.5s ease-in-out";
              bgElement.style.opacity = "1";
              observer.unobserve(bgElement);
            }
          }
        });
      },
      {
        rootMargin: "100px",
        threshold: 0.01,
      }
    );

    document
      .querySelectorAll(".experience-card:not(.active) .card-bg")
      .forEach((bg) => {
        const bgUrl = bg.style.backgroundImage.match(
          /url\(['"]?([^'"]+)['"]?\)/
        );
        if (bgUrl && bgUrl[1]) {
          bg.dataset.bg = bgUrl[1];
          bg.style.backgroundImage = "none";
          bg.style.opacity = "0";
          imageObserver.observe(bg);
        }
      });
  }

  // ===== SECTION REVEAL ANIMATION =====
  const experienceSection = document.querySelector(".experiences-section");
  if (experienceSection && "IntersectionObserver" in window) {
    const sectionObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
          }
        });
      },
      {
        threshold: 0.1,
      }
    );

    experienceSection.style.opacity = "0";
    experienceSection.style.transform = "translateY(30px)";
    experienceSection.style.transition =
      "opacity 0.8s ease-out, transform 0.8s ease-out";
    sectionObserver.observe(experienceSection);
  }

  // ===== MOBILE MENU BODY SCROLL =====
  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.querySelector(".navbar-collapse");

  if (navbarToggler && navbarCollapse) {
    navbarCollapse.addEventListener("show.bs.collapse", function () {
      if (window.innerWidth <= 991) {
        document.body.style.overflow = "hidden";
      }
    });

    navbarCollapse.addEventListener("hide.bs.collapse", function () {
      document.body.style.overflow = "";
    });
  }
});

// ===== TAB SWITCHING & SEARCH ANIMATION =====
document.addEventListener("DOMContentLoaded", function () {
  const tabButtons = document.querySelectorAll(".tab-btn");
  const formContents = document.querySelectorAll(".form-content");
  const toursSearchWrapper = document.querySelector(".tours-search-wrapper");
  const headerWrapper = document.getElementById("headerWrapper");
  const headerSearchContainer = document.getElementById("headerSearchContainer");
  const tourSearchInput = document.getElementById("searchKeyword");
  const headerSearchInput = document.getElementById("headerSearchInput");

  // Animation elements
  const searchClone = document.getElementById("searchClone");
  const flightPath = document.getElementById("flightPath");
  const particlesContainer = document.getElementById("particlesContainer");

  let activeTab = "tours";
  let isScrolled = false;

  // ===== CREATE PARTICLES =====
  function createParticles() {
    if (!particlesContainer) return;
    
    for (let i = 0; i < 10; i++) {
      setTimeout(() => {
        const particle = document.createElement("div");
        particle.className = "particle";
        particle.style.left = `${Math.random() * 60 - 30}px`;
        particle.style.animationDelay = `${i * 0.1}s`;
        particlesContainer.appendChild(particle);

        setTimeout(() => {
          particle.remove();
        }, 1000);
      }, i * 100);
    }
  }

  // ===== TAB SWITCH =====
  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const tabId = button.dataset.tab;

      tabButtons.forEach((btn) => btn.classList.remove("active"));
      formContents.forEach((content) => content.classList.remove("active"));
      button.classList.add("active");
      const tabContent = document.getElementById(tabId);
      if (tabContent) {
        tabContent.classList.add("active");
      }

      activeTab = tabId;
    });
  });

  // ===== SCROLL ANIMATION =====
  if (toursSearchWrapper && headerWrapper && searchClone && tourSearchInput && headerSearchInput) {
    window.addEventListener(
      "scroll",
      () => {
        const scrollPosition = window.scrollY;

        if (scrollPosition > 300 && !isScrolled) {
          isScrolled = true;

          const searchBox = toursSearchWrapper.querySelector(".tours-search-box");
          if (searchBox) {
            const rect = searchBox.getBoundingClientRect();

            // Animate clone
            searchClone.style.bottom = `${window.innerHeight - rect.bottom}px`;
            const cloneInput = searchClone.querySelector(".tours-search-input");
            if (cloneInput) {
              cloneInput.value = tourSearchInput.value;
            }
            searchClone.classList.add("animating");
            if (flightPath) {
              flightPath.classList.add("show");
            }

            createParticles();

            toursSearchWrapper.classList.add("fly-to-header");
            headerWrapper.classList.add("fixed-header");

            setTimeout(() => {
              if (headerSearchContainer) {
                headerSearchContainer.classList.add("show");
              }
              headerSearchInput.value = tourSearchInput.value;
            }, 800);

            setTimeout(() => {
              searchClone.classList.remove("animating");
              if (flightPath) {
                flightPath.classList.remove("show");
              }
            }, 1200);
          }
        } else if (scrollPosition <= 300 && isScrolled) {
          isScrolled = false;

          toursSearchWrapper.classList.remove("fly-to-header");
          headerWrapper.classList.remove("fixed-header");
          if (headerSearchContainer) {
            headerSearchContainer.classList.remove("show");
          }
          searchClone.classList.remove("animating");
          if (flightPath) {
            flightPath.classList.remove("show");
          }
        }
      },
      { passive: true }
    );

    // ===== SEARCH SYNC =====
    tourSearchInput.addEventListener("input", (e) => {
      headerSearchInput.value = e.target.value;
    });

    headerSearchInput.addEventListener("input", (e) => {
      tourSearchInput.value = e.target.value;
    });
  }

  // ===== QUICK LINKS =====
  document.querySelectorAll(".quick-link-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const destination = btn.textContent.trim();
      if (tourSearchInput) {
        tourSearchInput.value = destination;
      }
      if (headerSearchInput) {
        headerSearchInput.value = destination;
      }
    });
  });

  // ===== PLACES & EXPERIENCE TABS =====
  const placeTabButtons = document.querySelectorAll(".places-tab");

  placeTabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const tabId = button.dataset.tab;

      // Get parent menu (Places or Experience)
      const parentMenu =
        button.closest(".places-mega-menu") ||
        button.closest(".experience-mega-menu");

      if (!parentMenu) return;

      // Remove active from tabs in this menu only
      parentMenu
        .querySelectorAll(".places-tab")
        .forEach((btn) => btn.classList.remove("active"));

      // Remove active from content in this menu only
      parentMenu
        .querySelectorAll(".tab-content")
        .forEach((content) => content.classList.remove("active"));

      // Activate current button + tab content
      button.classList.add("active");
      const tabContent = parentMenu.querySelector(`#${tabId}Tab`);
      if (tabContent) {
        tabContent.classList.add("active");
      }
    });
  });
});

// ===== MOBILE MENU =====
document.addEventListener("DOMContentLoaded", function () {
  const mobileToggle = document.getElementById("mobileToggle");
  const mainNav = document.getElementById("mainNav");
  const mobileOverlay = document.getElementById("mobileOverlay");

  function toggleMobileMenu() {
    if (!mainNav || !mobileOverlay) return;
    
    const isActive = mainNav.classList.toggle("active");
    mobileOverlay.classList.toggle("active");
    document.body.style.overflow = isActive ? "hidden" : "";
  }

  if (mobileToggle) {
    mobileToggle.addEventListener("click", toggleMobileMenu);
  }
  
  if (mobileOverlay) {
    mobileOverlay.addEventListener("click", toggleMobileMenu);
  }

  // Mobile submenu for Places
  const placesLink = document.getElementById("placesLink");
  const placesNavItem = document.getElementById("placesNavItem");

  if (placesLink && placesNavItem) {
    placesLink.addEventListener("click", (e) => {
      if (window.innerWidth <= 991) {
        e.preventDefault();
        placesNavItem.classList.toggle("active");
      }
    });
  }

  // Mobile submenu for Experience
  const experienceLink = document.getElementById("experienceLink");
  const experienceNavItem = document.getElementById("experienceNavItem");

  if (experienceLink && experienceNavItem) {
    experienceLink.addEventListener("click", (e) => {
      if (window.innerWidth <= 991) {
        e.preventDefault();
        experienceNavItem.classList.toggle("active");
      }
    });
  }

  // Prevent menu close when clicking inside mega menus
  const placesMegaMenu = document.querySelector(".places-mega-menu");
  const tourMegaMenu = document.querySelector(".tour-mega-menu");
  const experienceMegaMenu = document.querySelector(".experience-mega-menu");

  if (placesMegaMenu) {
    placesMegaMenu.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  if (tourMegaMenu) {
    tourMegaMenu.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  if (experienceMegaMenu) {
    experienceMegaMenu.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  // Close mobile menu on window resize
  window.addEventListener("resize", () => {
    if (window.innerWidth > 991 && mainNav && mobileOverlay) {
      mainNav.classList.remove("active");
      mobileOverlay.classList.remove("active");
      document.body.style.overflow = "";
    }
  });
});

// ===== FLIGHT FORM =====
document.addEventListener("DOMContentLoaded", function () {
  // Trip Type Switching
  const tripTypeRadios = document.querySelectorAll('input[name="tripType"]');
  const singleTripForm = document.getElementById("singleTripForm");
  const multiCityForm = document.getElementById("multiCityForm");
  const returnField = document.querySelector(".return-field");
  const returnInput = document.querySelector(".return-input");
  const returnNote = document.querySelector(".return-note");

  tripTypeRadios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
      if (!singleTripForm || !multiCityForm) return;
      
      if (e.target.value === "multicity") {
        singleTripForm.style.display = "none";
        multiCityForm.style.display = "block";
      } else {
        singleTripForm.style.display = "block";
        multiCityForm.style.display = "none";

        if (returnInput && returnNote) {
          if (e.target.value === "roundtrip") {
            returnInput.value = "23 Oct 25";
            returnInput.placeholder = "";
            returnNote.textContent = "Thursday";
          } else {
            returnInput.value = "";
            returnInput.placeholder = "Return Date";
            returnNote.textContent = "Book a round trip to save more";
          }
        }
      }
    });
  });

  // Swap Button
  const swapBtn = document.querySelector(".swap-btn");
  if (swapBtn) {
    swapBtn.addEventListener("click", () => {
      const fromInputs = document.querySelectorAll(".form-input.airport-code");
      const fromDetails = document.querySelectorAll(".form-input.airport-detail");

      if (fromInputs.length >= 2 && fromDetails.length >= 2) {
        // Swap values
        const tempValue = fromInputs[0].value;
        const tempDetail = fromDetails[0].textContent;

        fromInputs[0].value = fromInputs[1].value;
        fromDetails[0].textContent = fromDetails[1].textContent;

        fromInputs[1].value = tempValue;
        fromDetails[1].textContent = tempDetail;
      }
    });
  }

  // Add Flight Button
  const addFlightBtn = document.querySelector(".add-flight-btn");
  if (addFlightBtn) {
    addFlightBtn.addEventListener("click", () => {
      // Animation feedback
      addFlightBtn.style.transform = "scale(0.95)";
      setTimeout(() => {
        addFlightBtn.style.transform = "";
      }, 200);
    });
  }
});

// ===== POPUP & AUTHENTICATION =====
document.addEventListener("DOMContentLoaded", function () {
  // Show enquiry modal after 7 seconds (once per session)
  if (!sessionStorage.getItem("popupShown")) {
    setTimeout(function () {
      if (typeof openEnquiryModal === 'function') {
        openEnquiryModal();
      }
      sessionStorage.setItem("popupShown", "true");
    }, 7000);
  }

  // Password toggle
  const togglePassword = document.getElementById("togglePassword");
  const passwordField = document.getElementById("password");
  
  if (togglePassword && passwordField) {
    togglePassword.addEventListener("click", function () {
      if (passwordField.type === "password") {
        passwordField.type = "text";
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
      } else {
        passwordField.type = "password";
        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");
      }
    });
  }

  // Clear package inputs on homepage
  if (window.location.pathname === "/") {
    const pkgInput = document.getElementById("pkg_id");
    const durationInput = document.getElementById("duration");

    if (pkgInput) {
      pkgInput.value = "";
    }

    if (durationInput) {
      durationInput.value = "";
    }
  }
});

// Authentication functions
function forgot_password() {
  if (typeof jQuery !== 'undefined') {
    jQuery("#popup_forgot").show();
    jQuery("#popup_login").hide();
  }
}

function show_login_popup() {
  if (typeof jQuery !== 'undefined') {
    jQuery("#popup_forgot").hide();
    jQuery("#popup_login").show();
  }
}

// Login form submission
if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(function () {
    jQuery("#frmLogin").submit(function (e) {
      jQuery("#login_msg").html("");
      e.preventDefault();
      jQuery.ajax({
        url: "/login_process",
        data: jQuery("#frmLogin").serialize(),
        type: "post",
        success: function (result) {
          if (result.status === "error") {
            jQuery("#login_msg").html(result.msg);
          }

          if (result.status === "success") {
            window.location.href = window.location.href;
          }
        },
      });
    });

    // Forgot password form
    jQuery("#frmForgot").submit(function (e) {
      jQuery("#forgot_msg").html("Please wait...");
      e.preventDefault();
      jQuery.ajax({
        url: "/frontend/forgot_password",
        data: jQuery("#frmForgot").serialize(),
        type: "post",
        success: function (result) {
          console.log(result);
          jQuery("#forgot_msg").html(result.msg);
        },
      });
    });

    // Registration form
    jQuery("#frmRegistration").submit(function (e) {
      e.preventDefault();
      jQuery(".field_error").html("");
      jQuery.ajax({
        url: "/registration_process",
        data: jQuery("#frmRegistration").serialize(),
        type: "post",
        success: function (result) {
          if (result.status === "error") {
            jQuery.each(result.error, function (key, val) {
              jQuery("#" + key + "_error").html(val[0]);
            });
          }

          if (result.status === "success") {
            jQuery("#frmRegistration")[0].reset();
            jQuery("#thank_you_msg").html(result.msg);
          }
        },
      });
    });
  });
}


// ===== CHRISTMAS LIGHTS =====
document.addEventListener("DOMContentLoaded", function () {
  const lightsContainer = document.getElementById("lights-container");
  if (!lightsContainer) return;

  const numLights = Math.floor(window.innerWidth / 50);

  function createLights() {
    lightsContainer.innerHTML = "";
    const spacing = window.innerWidth / (numLights + 1);

    for (let i = 0; i < numLights; i++) {
      const light = document.createElement("div");
      light.className = "light-bulb";
      light.style.left = `${spacing * (i + 1)}px`;

      light.innerHTML = `
        <div class="light-socket"></div>
        <div class="light-glow"></div>
      `;

      lightsContainer.appendChild(light);
    }
  }

  createLights();

  window.addEventListener("resize", () => {
    createLights();
  });

});
   </script>
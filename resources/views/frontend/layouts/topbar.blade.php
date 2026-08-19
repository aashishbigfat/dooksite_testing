<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>
<!-- Top Bar -->
<div class="top-bar">
  <div class="container">
    <div class="top-bar-content">
      <div class="contact-info">
        <a href="tel:+911140001000" class="contact-item">
          <img style="width: 20px;" src="{{asset('assets/images/flag.png')}}" alt="India flag">
          <span>011-40001000</span>
        </a>
        <a href="mailto:sales@dooktravels.com" class="contact-item">
          <i class="fas fa-envelope text-white"></i>
          <span>sales@dooktravels.com</span>
        </a>
      </div>
      <div class="social-links">
        <img src="{{asset('assets/13year-1.webp')}}" alt="13 years of expertise" style="width: 100px;">
        <a href="https://www.facebook.com/dooktravels" class="social-link" aria-label="Facebook" target="_blank">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.youtube.com/user/explorebug" class="social-link" aria-label="YouTube" target="_blank">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="https://twitter.com/dooktravels" class="social-link" aria-label="Twitter" target="_blank">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://www.instagram.com/dooktravels/" class="social-link" aria-label="Instagram"
          target="_blank.scroll-top">
          <i class="fab fa-instagram"></i>
        </a>
      </div>
    </div>
  </div>
</div>
<!-- Main Header -->
<header class="main-header" id="headerWrapper">
  <div class="container">
    <div class="header-container">
      <!-- Logo -->
      <a href="/" class="logo">
        <img src="{{asset('assets/images/logo.png')}}" style="width:60px;height: 60px;" alt="Dook International">
        <!-- <img src="{{asset('assets/images/200x200.png')}}" style="width:60px;height: 60px;"> -->
      </a>
      <!-- Navigation -->
      <ul class="main-nav mb-0 px-0" id="mainNav">
        <li class="nav-item">
          <a href="/" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="{{url('about-us')}}" class="nav-link" target="_blank">About</a>
        </li>
        <li class="nav-item">
          <a href="{{url('group-tours')}}" class="nav-link" target="_blank">Group Tour</a>
        </li>
        <li class="nav-item" id="tourNavItem">
          <a class="nav-link" href="#" id="tourLink">
            Tour <i class="fas fa-chevron-down"></i>
          </a>
          <!-- Tour Mega Menu -->
          <div class="tour-mega-menu p-0">
            <div class="mega-menu-header">
              <h3 class="mega-menu-title">Explore Our Tours</h3>
              <p class="mega-menu-subtitle">
                Choose your perfect travel experience
              </p>
            </div>

            <div class="tour-cards-grid" style="padding:1rem">
              <!-- International Tours -->
              <div class="tour-type-card" data-tour="international">
                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&h=600&fit=crop"
                  alt="International Tours" class="card-image" />

                <div class="card-badge">
                  <i class="fas fa-star"></i> Popular
                </div>

                <div class="card-content">
                  <div class="tabsss" style="display: flex;gap: 1rem;align-items: center;">
                    <div class="card-icon">
                      <i class="fas fa-globe-asia"></i>
                    </div>
                    <h4 class="card-title">International Tours</h4>
                  </div>
                  <div class="card-stats">
                    <div class="stat-item">
                      <i class="fas fa-map-marked-alt"></i>
                      <span>50+ Countries</span>
                    </div>
                    <div class="stat-item">
                      <i class="fas fa-suitcase"></i>
                      <span>200+ Tours</span>
                    </div>
                  </div>
                  <a href="{{url('international-tour-packages')}}" class="card-btn" target="_blank">
                    Explore Now <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <!-- Domestic Tours -->
              <div class="tour-type-card" data-tour="domestic">
                <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=800&h=600&fit=crop"
                  alt="Domestic Tours" class="card-image" />

                <div class="card-badge">
                  <i class="fas fa-fire"></i> Trending
                </div>

                <div class="card-content">
                  <div class="tabsss" style="display: flex;gap: 1rem;align-items: center;">
                    <div class="card-icon">
                      <i class="fas fa-flag"></i>
                    </div>
                    <h4 class="card-title">Domestic Tours</h4>
                  </div>
                  <div class="card-stats">
                    <div class="stat-item">
                      <i class="fas fa-map-marker-alt"></i>
                      <span>100+ Places</span>
                    </div>
                    <div class="stat-item">
                      <i class="fas fa-route"></i>
                      <span>150+ Tours</span>
                    </div>
                  </div>
                  <a href="{{url('domestic-tour-packages')}}" class="card-btn" target="_blank">
                    Explore Now <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </li>

        <li class="nav-item" id="placesNavItem">
          <a href="#" class="nav-link" id="placesLink">
            Places <i class="fas fa-chevron-down"></i>
          </a>

          <!-- Places Mega Menu -->
          <div class="places-mega-menu">
            <!-- Header -->
            <div class="mega-menu-header">
              <h3 class="mega-menu-title">Explore Destinations</h3>
              <p class="mega-menu-subtitle">
                Browse our curated collection of travel destinations
                worldwide
              </p>
            </div>

            <!-- Tabs -->
            <div class="places-tabs">
              <button class="places-tab active" data-tab="regions">
                <i class="fas fa-globe"></i>
                Regions
              </button>
              <button class="places-tab" data-tab="countries">
                <i class="fas fa-flag"></i>
                Countries
              </button>
              <button class="places-tab" data-tab="destinations">
                <i class="fas fa-map-marker-alt"></i>
                Destinations
              </button>
            </div>

            <!-- Regions Tab -->
            <div class="tab-content active" id="regionsTab">
              <div class="content-layout">
                <div>
                  <div class="regions-grid">
                    <div class="region-section">
                      <!-- <h4>Main Regions</h4> -->
                      <ul class="region-list">
                        <li>
                          <a href="{{url('/')}}/{{mega_regions_europe()->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i>
                            <span>{{mega_regions_europe()->region_name}}</span>
                          </a>
                        </li>
                        <li>
                          <a href="{{url('/')}}/{{mega_regions_africa()->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i>
                            <span>{{mega_regions_africa()->region_name}}</span>
                          </a>
                        </li>

                        @foreach(getMegaRegions()->take(1) as $region)
                        <li><a href="{{url('/')}}/{{$region->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i><span>{{ $region->region_name }}</span>
                          </a>
                        </li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Popular Regions</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaRegions()->skip(1)->take(3) as $region)
                        <li>
                          <a href="{{url('/')}}/{{$region->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i>
                            <span>{{ $region->region_name }}</span>
                          </a>
                        </li>
                        @endforeach

                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Other Regions</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaRegions()->skip(6)->take(3) as $region)
                        <li>
                          <a href="{{url('/')}}/{{$region->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i>
                            <span>{{ $region->region_name }}</span>
                          </a>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <div class="view-all-container">
                    <a href="{{route('frontend.regions')}}" class="view-all-btn">
                      View All Regions
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <!-- Featured Card -->
                <div>
                  <div class="featured-card">
                    <div class="featured-image-wrapper">
                      <img src="{{asset('assets/wgkZu1747203177.jpg')}}" alt="Almaty Delights" class="featured-image" />
                      <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Popular
                      </div>
                    </div>
                    <div class="featured-content">
                      <h3 class="featured-title">Almaty Delights</h3>
                      <div class="featured-meta">
                        <div class="meta-item">
                          <i class="fas fa-map-marker-alt"></i>
                          kazakhstan
                        </div>
                        <div class="meta-item">
                          <i class="fas fa-star"></i>
                          5.0 Rating
                        </div>
                      </div>
                      <div class="featured-price">
                        <span class="price-label">Starting from</span>
                        <span class="price-value">₹81990</span>
                      </div>
                      <a href="https://www.dookinternational.com/kazakhstan/almaty-package/0000691" class="book-btn">
                        Book Now
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Countries Tab -->
            <div class="tab-content" id="countriesTab">
              <div class="content-layout">
                <div>
                  <div class="regions-grid">
                    <div class="region-section">
                      <!-- <h4>Europe</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuCountries()->take(3) as $country)
                        <li>
                          <a href="{{url('/')}}/{{$country->slug_url}}" class="region-item">
                            <i class="fas fa-chevron-right"></i>
                            <span>{{ $country->name }}</span>
                          </a>
                        </li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Central Asia</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuCountries()->skip(3)->take(3) as $country)
                        <li> <a href="{{url('/')}}/{{$country->slug_url}}" class="region-item"> <i
                              class="fas fa-chevron-right"></i><span>{{ $country->name }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Southeast Asia</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuCountries()->skip(6)->take(3) as $country)
                        <li> <a href="{{url('/')}}/{{$country->slug_url}}" class="region-item"> <i
                              class="fas fa-chevron-right"></i><span>{{ $country->name }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <div class="view-all-container">
                    <a href="{{route('frontend.countries')}}" class="view-all-btn">
                      View All Countries
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div>
                  <div class="featured-card">
                    <div class="featured-image-wrapper">
                      <img src="https://images.unsplash.com/photo-1555881400-74d7acaacd8b?w=400&h=180&fit=crop"
                        alt="Georgia Explorer" class="featured-image" />
                      <div class="featured-badge">
                        <i class="fas fa-fire"></i>
                        Trending
                      </div>
                    </div>
                    <div class="featured-content">
                      <h3 class="featured-title">Georgia Explorer</h3>
                      <div class="featured-meta">
                        <div class="meta-item">
                          <i class="fas fa-map-marker-alt"></i>
                          Tbilisi & Batumi
                        </div>
                        <div class="meta-item">
                          <i class="fas fa-star"></i>
                          4.8 Rating
                        </div>
                      </div>

                      <div class="featured-price">
                        <span class="price-label">Starting from</span>
                        <span class="price-value">₹93,299</span>
                      </div>
                      <a href="https://www.dookinternational.com/group-tours/tbilisi-batumi/2000062" class="book-btn">
                        Book Now
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Destinations Tab -->
            <div class="tab-content" id="destinationsTab">
              <div class="content-layout">
                <div>
                  <div class="regions-grid">
                    <div class="region-section">
                      <!-- <h4>Top Cities</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuDestinations()->take(3) as $destination)
                        <li><a href="{{url('destinations')}}/{{$destination->slug_url}}" class="region-item"> <i
                              class="fas fa-chevron-right"></i><span> {{ $destination->name }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Nature & Adventure</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuDestinations()->skip(3)->take(3) as $destination)
                        <li><a href="{{url('destinations')}}/{{$destination->slug_url}}" class="region-item"> <i
                              class="fas fa-chevron-right"></i><span> {{ $destination->name }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <!-- <h4>Heritage Sites</h4> -->
                      <ul class="region-list">
                        @foreach(getMegaMenuDestinations()->skip(6)->take(3) as $destination)
                        <li><a href="{{url('destinations')}}/{{$destination->slug_url}}" class="region-item"> <i
                              class="fas fa-chevron-right"></i><span> {{ $destination->name }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <div class="view-all-container">
                    <a href="{{route('frontend.destinations')}}" class="view-all-btn">
                      View All Destinations
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div>
                  <div class="featured-card">
                    <div class="featured-image-wrapper">
                      <img src="https://images.unsplash.com/photo-1547448415-e9f5b28e570d?w=400&h=180&fit=crop"
                        alt="Azerbaijan Journey" class="featured-image" />
                      <div class="featured-badge">
                        <i class="fas fa-crown"></i>
                        Premium
                      </div>
                    </div>
                    <div class="featured-content">
                      <h3 class="featured-title">4 Nights Baku Tour Package</h3>
                      <div class="featured-meta">
                        <div class="meta-item">
                          <i class="fas fa-map-marker-alt"></i>
                          Baku
                        </div>
                        <div class="meta-item">
                          <i class="fas fa-star"></i>
                          4.9 Rating
                        </div>
                      </div>
                      <div class="featured-price">
                        <span class="price-label">Starting from</span>
                        <span class="price-value">₹54,999</span>
                      </div>
                      <a href="https://www.dookinternational.com/azerbaijan/baku-4-nights---land-of-fire-tour-package/000988"
                        class="book-btn">
                        Book Now
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item" id="experienceNavItem">
          <a href="#" class="nav-link" id="experienceLink">
            Experience <i class="fas fa-chevron-down"></i>
          </a>

          <!-- Experience Mega Menu -->
          <div class="experience-mega-menu">
            <!-- Header -->
            <div class="mega-menu-header">
              <h3 class="mega-menu-title">Unique Experiences</h3>
              <p class="mega-menu-subtitle">
                Discover extraordinary travel experiences and activities
              </p>
            </div>

            <!-- Tabs -->
            <div class="places-tabs">
              <button class="places-tab active" data-tab="experiences">
                <i class="fas fa-star"></i>
                Experiences
              </button>
              <button class="places-tab" data-tab="activities">
                <i class="fas fa-heart"></i>
                Services
              </button>

            </div>

            <!-- Experiences Tab -->
            <div class="tab-content active" id="experiencesTab">
              <div class="content-layout">
                <div>
                  <div class="regions-grid">
                    <div class="region-section">
                      <ul class="region-list">

                        @foreach(getMegaExperience()->take(3) as $getMegaExperience)
                        <li><a href="{{url('/')}}/{{$getMegaExperience->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaExperience->experience_name
                              }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <ul class="region-list">
                        @foreach(getMegaExperience()->skip(3)->take(3) as $getMegaExperience)
                        <li><a href="{{url('/')}}/{{$getMegaExperience->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaExperience->experience_name
                              }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <ul class="region-list">
                        @foreach(getMegaExperience()->skip(6)->take(3) as $getMegaExperience)
                        <li><a href="{{url('/')}}/{{$getMegaExperience->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaExperience->experience_name
                              }}</span></a></li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <div class="view-all-container">
                    <a href="{{route('frontend.experiences')}}" class="view-all-btn">
                      View All Experiences
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <!-- Featured Card -->
                <div>
                  <div class="featured-card">
                    <div class="featured-image-wrapper">
                      <img src="{{asset('assets/3RVCm1748414408.jpg')}}" alt="Russia" class="featured-image" />
                      <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Popular
                      </div>
                    </div>
                    <div class="featured-content">
                      <h3 class="featured-title">Russia Northern Lights Tour</h3>
                      <div class="featured-meta">
                        <div class="meta-item">
                          <i class="fas fa-map-marker-alt"></i>
                          Russia
                        </div>
                        <div class="meta-item">
                          <i class="fas fa-star"></i>
                          5.0 Rating
                        </div>
                      </div>
                      <div class="featured-price">
                        <span class="price-label">Starting from</span>
                        <span class="price-value">₹2,49,900</span>
                      </div>
                      <a href="https://www.dookinternational.com/russia/moscow-saintpetersburg-murmansk-7-nights-8-days/0000360"
                        class="book-btn">
                        Book Now
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Activities Tab -->
            <div class="tab-content" id="activitiesTab">
              <div class="content-layout">
                <div>
                  <div class="regions-grid">
                    <div class="region-section">
                      <ul class="region-list">
                        @foreach(getMegaeServices()->take(3) as $getMegaeServices)
                        <li> <a href="{{url('/')}}/{{$getMegaeServices->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaeServices->experience_name }}</span></a>
                        </li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <ul class="region-list">
                        @foreach(getMegaeServices()->skip(3)->take(3) as $getMegaeServices)
                        <li> <a href="{{url('/')}}/{{$getMegaeServices->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaeServices->experience_name }}</span></a>
                        </li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="region-section">
                      <ul class="region-list">
                        @foreach(getMegaeServices()->skip(6)->take(3) as $getMegaeServices)
                        <li> <a href="{{url('/')}}/{{$getMegaeServices->slug_url}}" class="region-item"><i
                              class="fas fa-chevron-right"></i><span>{{ $getMegaeServices->experience_name }}</span></a>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <div class="view-all-container">
                    <a href="{{route('frontend.experiences')}}" class="view-all-btn">
                      View All Services
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div>
                  <div class="featured-card">
                    <div class="featured-image-wrapper">
                      <img src="{{asset('assets/5YI1Q1748859290.jpg')}}" alt="Beach Paradise" class="featured-image" />
                      <div class="featured-badge">
                        <i class="fas fa-fire"></i>
                        Trending
                      </div>
                    </div>
                    <div class="featured-content">
                      <h3 class="featured-title">MICE Tour Packages</h3>
                      <div class="featured-meta">
                        <!-- <div class="meta-item">
                              <i class="fas fa-map-marker-alt"></i>
                              Maldives
                            </div> -->
                        <div class="meta-item">
                          <i class="fas fa-star"></i>
                          4.9 Rating
                        </div>
                      </div>
                      <a href="https://www.dookinternational.com/corporate-mice-tours" class="book-btn">
                        Book Now
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </li>
        <li class="nav-item">
          <a href="{{route('frontend.visa-consultation-services')}}" class="nav-link" target="_blank">Visa</a>
        </li>
        <li class="nav-item">
          <a href="{{route('frontend.blog')}}" class="nav-link" target="_blank">Blog</a>
        </li>
        @if(request()->is('/'))
        <div class="header-search-container" id="headerSearchContainer">
          <form id="commonSearchForm" action="{{url('search')}}?searchKeyword=">
            <div class="header-search-box">
              <div class="search-icon-wrapper">
                <i class="fas fa-search"></i>
              </div>
              @if(isset($keyword))
              <input id="searchKeyword" type="text" class="header-search-input" placeholder="Search destinations..."
                name="searchKeyword" required />
              @else
              <input id="searchKeyword" type="text" class="header-search-input" placeholder="Search destinations..."
                name="searchKeyword" required />
              @endif
              <button class="btn-search-header" type="submit">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </form>
        </div>
        @else

        <div
          style="margin-right: 1rem;flex: 1;max-width: 400px;transform: translateY(-10px) scale(0.95);transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);top: 14px;position: relative;">
          <form id="commonSearchForm" action="{{url('search')}}?searchKeyword=">
            <div class="header-search-box "
              style="border-color: rgba(217, 69, 69, 0.2);box-shadow: 0 8px 32px rgba(217, 69, 69, 0.15);">
              <div class="search-icon-wrapper">
                <i class="fas fa-search"></i>
              </div>
              @if(isset($keyword))
              <input id="searchKeyword" type="text" class="header-search-input" placeholder="Search destinations..."
                name="searchKeyword" required />
              @else
              <input id="searchKeyword" type="text" class="header-search-input" placeholder="Search destinations..."
                name="searchKeyword" required />
              @endif
              <button class="btn-search-header">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </form>
        </div>
        @endif
        <!-- Mobile Header Buttons -->
        <li>
          <div class="header-buttons">
            <!-- <a href="https://agent.dookinternational.com/login" class="btn-header btn-outline" target="_blank">
                <i class="fas fa-user-tie"></i>
                Agent Connect
              </a> -->
            @if(session()->has('FRONT_USER_LOGIN')!=null)
            <ul class="navbar-nav m-auto">
              <li class="nav-item dropdown">
                <button class="nav-link MenuDosier dropdown-toggle MainMenuTabs" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false" style="color: #dc3545 !important;font-size: 14px;">Hello, {{
                  session('FRONT_USER_NAME') }}</button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <!-- <li><a class="dropdown-item" href="{{url('/order')}}">My Order</a></li> -->
                  <li><a class="dropdown-item" href="{{url('/logout')}}">Logout</a></li>
                </ul>
              </li>
            </ul>
            @else
            <button class="btn-header btn-primary" tabindex="-1" aria-disabled="true" data-bs-toggle="modal"
              data-bs-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Login</button>
            @endif
          </div>
        </li>
      </ul>

      <!-- Mobile Toggle -->
      <button class="mobile-toggle" id="mobileToggle" aria-label="Menu">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>
</header>
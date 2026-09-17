{{-- Standalone page: uses its own top bar, navbar and footer instead of the site layout. --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="author" content="Dook International">
  <title>CIS &amp; Caucasus DMC for European Travel Partners | Dook International</title>
  <meta name="description" content="Your B2B consolidator for the CIS, Central Asia and Caucasus. Dook International's own regional offices support tailor-made FIT, group and MICE programmes for European travel professionals.">
  <meta name="theme-color" content="#d71921">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="CIS &amp; Caucasus DMC for European Travel Partners | Dook International">
  <meta property="og:image" content="https://www.dookinternational.com/assets/images/logo.png">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@dooktravels">
  <meta name="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="CIS &amp; Caucasus DMC for European Travel Partners | Dook International">
  <meta name="twitter:image" content="https://www.dookinternational.com/assets/images/logo.png">
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
  {{-- Same font loading as the rest of the site (frontend/layouts/header.blade.php). --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="preload" href="{{ asset('assets/images/b2b-partnerships/samarkand.webp') }}" as="image" fetchpriority="high">
  <link rel="stylesheet" href="{{ asset('assets/css/b2b-partnerships.css') }}?v={{ filemtime(public_path('assets/css/b2b-partnerships.css')) }}">
  <script src="{{ asset('assets/js/b2b-partnerships.js') }}?v={{ filemtime(public_path('assets/js/b2b-partnerships.js')) }}" defer></script>
  @verbatim
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"TravelAgency","name":"Dook International","url":"https://www.dookinternational.com/","email":"sales@dooktravels.com","description":"B2B destination management and consolidation for the CIS, Central Asia and Caucasus.","areaServed":["Uzbekistan","Kazakhstan","Kyrgyzstan","Georgia","Armenia","Azerbaijan","Tajikistan","Turkmenistan"]}</script>
  @endverbatim

  {{-- Same analytics as the rest of the site (frontend/layouts/header.blade.php). --}}
  <script>
    window.addEventListener('load', function () {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
      var gtm = document.createElement('script');
      gtm.async = true;
      gtm.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-KXXVMQ9';
      document.head.appendChild(gtm);
    });
  </script>
  <script>
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref=bwt";
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "t818i462wr");
  </script>
</head>
<body class="b2b-page">
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KXXVMQ9" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<a class="b2b-skip-link" href="#b2b-main">Skip to content</a>
<svg class="b2b-icon-defs" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><defs>
  <symbol id="b2b-i-arrow" viewBox="0 0 24 24"><path d="M4 12h15M13 5l7 7-7 7"/></symbol>
  <symbol id="b2b-i-check" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></symbol>
  <symbol id="b2b-i-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></symbol>
  <symbol id="b2b-i-building" viewBox="0 0 24 24"><path d="M3 21h18M5 21V3h10v18M15 9h4v12M8 7h4M8 11h4M8 15h4M9 21v-3h2v3"/></symbol>
  <symbol id="b2b-i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/></symbol>
  <symbol id="b2b-i-people" viewBox="0 0 24 24"><circle cx="9" cy="7" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 4a3 3 0 0 1 0 6M18 14a5 5 0 0 1 3 4v3"/></symbol>
  <symbol id="b2b-i-route" viewBox="0 0 24 24"><circle cx="5" cy="5" r="2"/><circle cx="19" cy="19" r="2"/><path d="M7 5h8a4 4 0 0 1 0 8H9a4 4 0 0 0 0 8h5"/></symbol>
  <symbol id="b2b-i-case" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V3h8v4M3 12h18M10 12v3h4v-3"/></symbol>
  <symbol id="b2b-i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></symbol>
  <symbol id="b2b-i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
</defs></svg>

<div class="b2b-topbar"><div class="b2b-container b2b-topbar-inner"><span>FOR EUROPEAN TRAVEL PROFESSIONALS</span><a href="mailto:sales@dooktravels.com"><svg class="b2b-icon"><use href="#b2b-i-mail"/></svg>sales@dooktravels.com</a></div></div>
<header class="b2b-header">
  <div class="b2b-container b2b-nav-row">
    <a class="b2b-brand" href="{{ url('/') }}" aria-label="Dook International home"><img src="{{ asset('assets/images/logo.png') }}" alt="Dook International" width="196" height="197"><span>DESTINATION EXPERTISE.<br><strong>PARTNERSHIP THAT GOES FURTHER.</strong></span></a>
    <nav aria-label="Main navigation" id="b2b-main-nav"><a href="#b2b-why-dook">Why Dook</a><a href="#b2b-destinations">Destinations</a><a href="#b2b-offices">Our offices</a><a href="#b2b-services">Services</a></nav>
    <div class="b2b-nav-actions"><a class="b2b-button b2b-button-small" href="#b2b-enquire" data-cta="header">Become a partner <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a><button class="b2b-menu-toggle" aria-controls="b2b-main-nav" aria-expanded="false" aria-label="Open navigation"><span></span><span></span><span></span></button></div>
  </div>
</header>

<main id="b2b-main">
  <section class="b2b-hero" aria-labelledby="b2b-hero-title">
    <img class="b2b-hero-image" src="{{ asset('assets/images/b2b-partnerships/samarkand.webp') }}" alt="The illuminated madrasahs of Registan Square at night in Samarkand, Uzbekistan" width="1600" height="900" fetchpriority="high">
    <div class="b2b-hero-shade"></div>
    <div class="b2b-container b2b-hero-content">
      <p class="b2b-eyebrow b2b-light"><span></span> YOUR B2B DESTINATION PARTNER</p>
      <h1 id="b2b-hero-title">CIS &amp; Caucasus.<br>Our home ground.<br><span>Your next opportunity.</span></h1>
      <p class="b2b-hero-description">Grow your destination portfolio with a specialist consolidator that has its own offices on the ground. One Dook partnership for your FIT, group and MICE programmes.</p>
      <div class="b2b-hero-actions"><a class="b2b-button" href="#b2b-enquire" data-cta="hero">Request a B2B proposal <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a><a class="b2b-hero-link" href="#b2b-offices">Meet your local network <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a></div>
      <div class="b2b-hero-reassurance"><span><svg class="b2b-icon"><use href="#b2b-i-check"/></svg>Our own regional offices</span><span><svg class="b2b-icon"><use href="#b2b-i-check"/></svg>Tailored B2B quotations</span></div>
    </div>
    <div class="b2b-image-caption"><svg class="b2b-icon"><use href="#b2b-i-pin"/></svg><span>SAMARKAND, UZBEKISTAN<br><small>Extraordinary destinations. Local expertise.</small></span></div>
  </section>

  <div class="b2b-container b2b-proof-strip" aria-label="Dook regional presence">
    <div><strong>6<span> offices</span></strong><p>Our own regional presence</p></div>
    <div><strong>8<span> destinations</span></strong><p>A focused regional portfolio</p></div>
    <div><strong>FIT <span>to</span> MICE</strong><p>Programmes for every brief</p></div>
    <div><strong>1<span> partner</span></strong><p>From planning to ground delivery</p></div>
  </div>

  <section class="b2b-section b2b-why-section" id="b2b-why-dook" aria-labelledby="b2b-why-title">
    <div class="b2b-container">
      <div class="b2b-section-intro"><div><p class="b2b-eyebrow">THE DOOK ADVANTAGE</p><h2 id="b2b-why-title">Regional scale.<br><span class="b2b-red">Real local presence.</span></h2></div><p>You bring the client relationship. We bring destination knowledge, our regional office network and the ground arrangements that turn your brief into a travel programme.</p></div>
      <div class="b2b-advantage-grid">
        <article><span class="b2b-number">01</span><h3>Closer to the destination.</h3><p>Dook offices across the region connect itinerary planning with local knowledge, hotel coordination and on-trip operations.</p></article>
        <article><span class="b2b-number">02</span><h3>One consolidated brief.</h3><p>Hotels, transfers, guides, experiences and group arrangements come together in one tailored B2B proposal.</p></article>
        <article><span class="b2b-number">03</span><h3>Built around your agency.</h3><p>Align the programme, commercial terms, branding and traveller communication with our team before you confirm.</p></article>
      </div>
      <div class="b2b-trade-line"><span>YOUR CLIENTS. YOUR AMBITION. OUR DESTINATION EXPERTISE.</span><a href="#b2b-enquire">Let's build your next programme <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a></div>
    </div>
  </section>

  <section class="b2b-office-section b2b-section" id="b2b-offices" aria-labelledby="b2b-office-title">
    <div class="b2b-container b2b-office-layout">
      <div class="b2b-office-intro"><p class="b2b-eyebrow b2b-light">OUR OWN OFFICES. YOUR LOCAL ADVANTAGE.</p><h2 id="b2b-office-title">We don't just<br>know the region.<br><span>We're here.</span></h2><p>From the Silk Road to the Caucasus, work with a destination partner with Dook offices in the cities that anchor your programmes.</p><a class="b2b-text-link b2b-light" href="{{ route('frontend.contact_us') }}" target="_blank" rel="noopener">View our published office details <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a><div class="b2b-office-footnote"><svg class="b2b-icon"><use href="#b2b-i-building"/></svg><span>6 Dook offices across our core regional network</span></div></div>
      <div class="b2b-office-directory">
        <div class="b2b-directory-heading"><h3>A network you can work with.</h3><p>Select an office to see its address.</p></div>
        <div class="b2b-office-list">
          <details><summary><span class="b2b-office-country">UZBEKISTAN</span><span class="b2b-office-city">Tashkent</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook Travels LLC</strong><address>C-13, Building No. 14, Office 02, Tashkent, Uzbekistan</address></div></details>
          <details><summary><span class="b2b-office-country">KAZAKHSTAN</span><span class="b2b-office-city">Almaty</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook Travels Kazakhstan LLP</strong><address>Aiteke bi, house 123/24-37, Almalinsky district, Almaty, Kazakhstan</address></div></details>
          <details><summary><span class="b2b-office-country">KYRGYZSTAN</span><span class="b2b-office-city">Bishkek</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook International LLC</strong><address>Office 312, 107 Kievskaya Street, Bishkek 720001, Kyrgyz Republic</address></div></details>
          <details><summary><span class="b2b-office-country">AZERBAIJAN</span><span class="b2b-office-city">Baku</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook International LLC</strong><address>115, 57, 3rd Floor, Hazi Aslanov Street, Nasimi District, Baku, Azerbaijan</address></div></details>
          <details><summary><span class="b2b-office-country">GEORGIA</span><span class="b2b-office-city">Tbilisi</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook International LLC</strong><address>Apartment N-11, N 78k, Tsamebuli Avenue, Tbilisi, Georgia</address></div></details>
          <details><summary><span class="b2b-office-country">ARMENIA</span><span class="b2b-office-city">Yerevan</span><svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><div class="b2b-office-address"><strong>Dook International LLC</strong><address>Tamanyan Street 3, b/44 apartment, Yerevan 0001, Armenia</address></div></details>
        </div>
      </div>
    </div>
  </section>

  <section class="b2b-section b2b-destinations-section" id="b2b-destinations" aria-labelledby="b2b-destination-title">
    <div class="b2b-container">
      <div class="b2b-section-intro"><div><p class="b2b-eyebrow">CIS · CENTRAL ASIA · CAUCASUS</p><h2 id="b2b-destination-title">One region.<br>A world of possibilities.</h2></div><p>Heritage cities, mountain landscapes and distinctive cultures. Build single-country programmes or ask us to shape a practical multi-country itinerary.</p></div>
      <div class="b2b-destination-cards">
        <article class="b2b-destination-card"><div class="b2b-destination-photo"><img src="{{ asset('assets/images/b2b-partnerships/samarkand.webp') }}" alt="The tiled madrassas of Registan Square in Samarkand" width="1600" height="900" loading="lazy"><span class="b2b-photo-tag">SILK ROAD HERITAGE</span></div><div class="b2b-destination-content"><div class="b2b-card-overline">UZBEKISTAN</div><h3>Stories along the Silk Road.</h3><p>Tashkent · Samarkand · Bukhara · Khiva</p><div class="b2b-card-rule"></div><p class="b2b-card-detail">Culture, architecture and local flavours, with our own office in Tashkent.</p><button class="b2b-text-link" data-destination="Uzbekistan">Build a Silk Road programme <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button></div></article>
        <article class="b2b-destination-card"><div class="b2b-destination-photo"><img src="{{ asset('assets/images/b2b-partnerships/georgia.webp') }}" alt="Gergeti Trinity Church with Mount Kazbek near Stepantsminda, Georgia" width="1000" height="750" loading="lazy"><span class="b2b-photo-tag">CAUCASUS DISCOVERIES</span></div><div class="b2b-destination-content"><div class="b2b-card-overline">GEORGIA · ARMENIA · AZERBAIJAN</div><h3>A different kind of discovery.</h3><p>Tbilisi · Yerevan · Baku</p><div class="b2b-card-rule"></div><p class="b2b-card-detail">Country programmes rich in food, heritage and scenery. Dook offices in all three capitals.</p><button class="b2b-text-link" data-destination="Georgia,Armenia,Azerbaijan">Explore Caucasus programmes <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button></div></article>
        <article class="b2b-destination-card"><div class="b2b-destination-photo"><img src="{{ asset('assets/images/b2b-partnerships/kazakhstan.webp') }}" alt="Turquoise Big Almaty Lake beneath the mountains near Almaty, Kazakhstan" width="1000" height="750" loading="lazy"><span class="b2b-photo-tag">MOUNTAINS &amp; NATURE</span></div><div class="b2b-destination-content"><div class="b2b-card-overline">KAZAKHSTAN · KYRGYZSTAN</div><h3>Space to go further.</h3><p>Almaty · Charyn Canyon · Bishkek · Issyk-Kul</p><div class="b2b-card-rule"></div><p class="b2b-card-detail">City escapes and outdoor experiences, supported by Dook offices in Almaty and Bishkek.</p><button class="b2b-text-link" data-destination="Kazakhstan,Kyrgyzstan">Plan a nature-led itinerary <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button></div></article>
      </div>
      <div class="b2b-more-destinations"><div><span class="b2b-eyebrow">MORE OF THE REGION</span><p>Extend your destination portfolio.</p></div><button data-destination="Tajikistan"><strong>Tajikistan</strong><span>Dushanbe &amp; the Fann Mountains</span><svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button><button data-destination="Turkmenistan"><strong>Turkmenistan</strong><span>Ashgabat, Merv &amp; the desert</span><svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button></div>
      <p class="b2b-destination-note">All programmes are quoted for your dates and brief. Routing, entry requirements and service availability are confirmed before booking.</p>
    </div>
  </section>

  <section class="b2b-section b2b-services-section" id="b2b-services" aria-labelledby="b2b-services-title">
    <div class="b2b-container">
      <div class="b2b-section-intro"><div><p class="b2b-eyebrow">THE COMPLETE GROUND PROGRAMME</p><h2 id="b2b-services-title">All the moving parts.<br>One destination partner.</h2></div><p>Bring us a first enquiry, a group series or a complex incentive brief. We bring the destination services together around your requirements.</p></div>
      <div class="b2b-service-grid">
        <article><div class="b2b-service-icon"><svg class="b2b-icon"><use href="#b2b-i-building"/></svg></div><h3>Stay &amp; travel</h3><ul><li>Hotels across requested categories</li><li>Airport transfers &amp; private transport</li><li>Intercity and group transport</li></ul></article>
        <article><div class="b2b-service-icon"><svg class="b2b-icon"><use href="#b2b-i-route"/></svg></div><h3>Discover &amp; experience</h3><ul><li>Sightseeing &amp; local excursions</li><li>Guide and language coordination</li><li>Meals &amp; special requirements</li></ul></article>
        <article><div class="b2b-service-icon"><svg class="b2b-icon"><use href="#b2b-i-people"/></svg></div><h3>FIT, groups &amp; luxury</h3><ul><li>Tailor-made private programmes</li><li>Group series &amp; hotel blocks</li><li>Premium stays &amp; curated experiences</li></ul></article>
        <article><div class="b2b-service-icon"><svg class="b2b-icon"><use href="#b2b-i-case"/></svg></div><h3>MICE &amp; coordination</h3><ul><li>Meetings, incentives &amp; events</li><li>Multi-country itinerary planning</li><li>Entry guidance &amp; on-trip coordination</li></ul></article>
      </div>
      <div class="b2b-service-bottom"><p>From cultural journeys to corporate incentives, every proposal defines the services, inclusions and support agreed for your booking.</p><a href="#b2b-enquire" class="b2b-button b2b-button-outline" data-cta="services">Discuss your requirements <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a></div>
    </div>
  </section>

  <section class="b2b-section b2b-process-section" aria-labelledby="b2b-process-title">
    <div class="b2b-container"><div class="b2b-process-head"><p class="b2b-eyebrow">A CLEAR PATH FROM BRIEF TO BOOKING</p><h2 id="b2b-process-title">You sell the journey.<br>We help make it happen.</h2></div><ol class="b2b-process-grid"><li><span>01</span><h3>Share your brief</h3><p>Destination, dates, group size and your client's priorities. Start with what you know.</p></li><li><span>02</span><h3>Shape the proposal</h3><p>Review the itinerary and B2B quotation. Refine hotels, routing and experiences together.</p></li><li><span>03</span><h3>Confirm the details</h3><p>Agree services, payment terms, booking conditions and communication before confirmation.</p></li><li><span>04</span><h3>Travel with local support</h3><p>Our team coordinates the destination arrangements in your confirmed programme.</p></li></ol></div>
  </section>

  <section class="b2b-enquiry-section b2b-section" id="b2b-enquire" aria-labelledby="b2b-enquiry-title">
    <div class="b2b-container b2b-enquiry-layout">
      <div class="b2b-enquiry-intro"><p class="b2b-eyebrow">LET'S BUILD YOUR NEXT OPPORTUNITY</p><h2 id="b2b-enquiry-title">Your next great<br>programme<br><span class="b2b-red">starts here.</span></h2><p>Tell us what your clients have in mind. Our B2B team will help you shape the destination programme and the commercial proposal.</p><ul class="b2b-enquiry-benefits"><li><svg class="b2b-icon"><use href="#b2b-i-check"/></svg>Tailored itinerary and B2B quotation</li><li><svg class="b2b-icon"><use href="#b2b-i-check"/></svg>Hotel, transport and service options</li><li><svg class="b2b-icon"><use href="#b2b-i-check"/></svg>Local destination coordination</li></ul><div class="b2b-direct-contact"><h3>Prefer a conversation?</h3><a href="mailto:sales@dooktravels.com" data-contact="email"><svg class="b2b-icon"><use href="#b2b-i-mail"/></svg>sales@dooktravels.com</a><a href="https://wa.me/918368513675?text=Hello%20Dook%2C%20I%20would%20like%20to%20discuss%20a%20B2B%20CIS%20or%20Caucasus%20programme." target="_blank" rel="noopener" data-contact="whatsapp"><svg class="b2b-icon"><use href="#b2b-i-whatsapp"/></svg>WhatsApp our B2B team <svg class="b2b-icon b2b-arrow"><use href="#b2b-i-arrow"/></svg></a></div><p class="b2b-trade-only">For travel agents, tour operators, wholesalers and MICE planners across Europe.</p></div>
      <form id="b2b-quote-form" class="b2b-quote-form">
        <div class="b2b-form-heading"><div><span class="b2b-eyebrow">B2B PARTNERSHIPS</span><h3>Tell us about your programme.</h3></div><svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></div>
        <p class="b2b-required-note">Fields marked * are required.</p>
        <div class="b2b-form-grid">
          <label>Your name *<input name="name" autocomplete="name" placeholder="Full name" maxlength="100" required></label>
          <label>Agency / company *<input name="company_name" autocomplete="organization" placeholder="Company name" maxlength="160" required></label>
          <label>Business email *<input type="email" name="email" autocomplete="email" placeholder="you@company.com" maxlength="180" required></label>
          <label>Phone / WhatsApp *<input type="tel" name="mobile" autocomplete="tel" placeholder="Include country code, e.g. +44" minlength="7" maxlength="30" required></label>
          <label>Agency country *<input name="agency_country" autocomplete="country-name" placeholder="e.g. United Kingdom" maxlength="80" required></label>
          <label>Programme type<select name="travel_type"><option value="">Select a programme</option><option>FIT / tailor-made</option><option>Group / series</option><option>MICE / incentive</option><option>Luxury travel</option><option>Multi-country</option><option>Partnership enquiry</option></select></label>
        </div>
        <fieldset class="b2b-destinations-field"><legend>Destinations of interest *</legend><div class="b2b-destination-choices"><label><input type="checkbox" name="destinations" value="Uzbekistan"><span>Uzbekistan</span></label><label><input type="checkbox" name="destinations" value="Kazakhstan"><span>Kazakhstan</span></label><label><input type="checkbox" name="destinations" value="Kyrgyzstan"><span>Kyrgyzstan</span></label><label><input type="checkbox" name="destinations" value="Georgia"><span>Georgia</span></label><label><input type="checkbox" name="destinations" value="Armenia"><span>Armenia</span></label><label><input type="checkbox" name="destinations" value="Azerbaijan"><span>Azerbaijan</span></label><label><input type="checkbox" name="destinations" value="Tajikistan"><span>Tajikistan</span></label><label><input type="checkbox" name="destinations" value="Turkmenistan"><span>Turkmenistan</span></label><label><input type="checkbox" name="destinations" value="Please advise"><span>Help me choose</span></label></div><p id="b2b-destination-error" class="b2b-field-error" hidden>Please select a destination or “Help me choose”.</p></fieldset>
        <div class="b2b-form-grid"><label>Travel month <span class="b2b-optional">(optional)</span><input type="month" name="travel_date" min="{{ now()->format('Y-m') }}"></label><label>Number of travellers <span class="b2b-optional">(optional)</span><input type="number" name="no_of_traveler" min="1" max="10000" placeholder="e.g. 24"></label></div>
        <details class="b2b-extra-details"><summary>Add hotel, budget or itinerary preferences <span>+</span></summary><div class="b2b-form-grid"><label>Hotel category<select name="hotel_category"><option value="">Please advise</option><option>3 star</option><option>4 star</option><option>5 star</option><option>Mixed categories</option></select></label><label>Budget range<input name="budget_range" placeholder="Amount, currency & per person / total" maxlength="120"></label></div><label>Your brief<textarea name="comment" rows="3" maxlength="4000" placeholder="Routing, experiences, guide language, meals, accessibility needs or anything else we should know. Please don't include passport or payment details."></textarea></label></details>
        <label class="b2b-consent"><input type="checkbox" name="contact_consent" required><span>I agree to be contacted about this enquiry and have read Dook's <a href="{{ route('frontend.privacy_policy') }}" target="_blank" rel="noopener">Privacy Policy</a>. *</span></label>
        <div class="b2b-honeypot" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <button class="b2b-button b2b-form-submit" type="submit" disabled><span>Prepare my B2B enquiry</span><svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></button>
        <p class="b2b-form-note" id="b2b-form-mode-note">Prepare your brief, then send it to our team by email or WhatsApp.</p>
        <p class="b2b-form-status" role="status" id="b2b-form-status" hidden></p>
        <noscript><p>Please email your requirements to <a href="mailto:sales@dooktravels.com">sales@dooktravels.com</a> or <a href="https://wa.me/918368513675" target="_blank" rel="noopener">chat with our team on WhatsApp</a>.</p></noscript>
      </form>
    </div>
  </section>

  <section class="b2b-section b2b-faq-section" aria-labelledby="b2b-faq-title"><div class="b2b-container b2b-faq-layout"><div><p class="b2b-eyebrow">BEFORE WE BEGIN</p><h2 id="b2b-faq-title">A few good<br>questions.</h2><p>Have a more specific brief?<br><a class="b2b-text-link" href="mailto:sales@dooktravels.com">Talk to our B2B team <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a></p></div><div class="b2b-faq-list">
    <details><summary>Who is this partnership for?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>European travel agents, tour operators, wholesalers, group organisers and MICE planners looking for destination services in the CIS, Central Asia and Caucasus. We quote against your agency's requirements, whether you have one enquiry or a wider programme to develop.</p></details>
    <details><summary>Where does Dook have its own offices?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>Our core regional office network includes Tashkent, Almaty, Bishkek, Baku, Tbilisi and Yerevan. <a href="#b2b-offices">See the office directory</a> for local company names and addresses. Tajikistan and Turkmenistan are additional programme destinations; we do not list Dook offices there.</p></details>
    <details><summary>What does a B2B quotation include?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>Your proposed itinerary and a quotation for the requested services, which may include hotels, transfers, transport, guides, sightseeing and meals. Rates depend on dates, availability, group size and scope. Confirm inclusions, exclusions, payment currency and booking terms with the team before committing.</p></details>
    <details><summary>Can we customise or combine countries?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>Yes. Share your preferred pace, route, hotel category and experiences. Our team checks practical connections, entry requirements and service availability before proposing a multi-country route. Not every country combination is operationally suitable.</p></details>
    <details><summary>Can you support FIT, groups and MICE?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>We can prepare private and tailor-made journeys, group itineraries, luxury programmes and MICE proposals. For conferences, incentives or events, include delegate numbers, venue needs, dates and your objectives so the team can confirm the destination's operational scope.</p></details>
    <details><summary>How do you work with our agency and clients?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>We coordinate the proposal with your agency. Before booking, agree how rates, your markup, itinerary branding, traveller contact and destination communication will be handled. Your confirmed proposal should set out the service scope and responsibilities clearly.</p></details>
    <details><summary>What about guide languages, entry requirements and on-trip support?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>Tell us the guide languages and support your clients need. Availability and destination assistance are confirmed in your proposal. Entry information and visa assistance can be provided where offered; entry and visa decisions remain with the relevant authorities.</p></details>
    <details><summary>What should I include in the first brief?<svg class="b2b-icon"><use href="#b2b-i-plus"/></svg></summary><p>Destinations, approximate dates, adults and children, preferred hotel category, board basis and budget are a useful start. Add private or shared transport, guide languages, must-see experiences, meals and accessibility needs when known. An initial partnership enquiry is welcome too.</p></details>
  </div></div></section>

  <section class="b2b-closing-banner"><div class="b2b-container"><div><p>YOUR NEXT DESTINATION PARTNERSHIP</p><h2>Let's open up the region. Together.</h2></div><a class="b2b-button b2b-button-white" href="#b2b-enquire" data-cta="closing">Start the conversation <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a></div></section>
</main>

<footer><div class="b2b-container b2b-footer-main"><a class="b2b-footer-brand" href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="Dook International" width="196" height="197"><span>Your B2B destination partner.<br>CIS · Central Asia · Caucasus</span></a><div><strong>Regional expertise</strong><p>Uzbekistan · Kazakhstan · Kyrgyzstan<br>Georgia · Armenia · Azerbaijan<br>Tajikistan · Turkmenistan</p></div><div><strong>Let's work together</strong><a href="mailto:sales@dooktravels.com">sales@dooktravels.com</a><a class="b2b-whatsapp-icon-link" href="https://wa.me/918368513675?text=Hello%20Dook%2C%20I%20would%20like%20to%20discuss%20a%20B2B%20programme." target="_blank" rel="noopener" aria-label="Chat with Dook on WhatsApp" title="Chat with Dook on WhatsApp" data-contact="whatsapp"><svg class="b2b-icon" aria-hidden="true"><use href="#b2b-i-whatsapp"/></svg></a><a href="{{ route('frontend.contact_us') }}" target="_blank" rel="noopener">Contact &amp; office information</a></div></div><div class="b2b-container b2b-footer-bottom"><p>© <span id="b2b-year">{{ date('Y') }}</span> Dook International. A unit of Dook Travels Pvt. Ltd.</p><div><a href="{{ route('frontend.privacy_policy') }}" target="_blank" rel="noopener">Privacy Policy</a><a href="{{ route('frontend.terms_conditions') }}" target="_blank" rel="noopener">Terms &amp; Conditions</a><a href="#b2b-main">Back to top ↑</a></div></div><details class="b2b-container b2b-photo-credits"><summary>Photography credits</summary><p>Samarkand: <a href="https://commons.wikimedia.org/wiki/File:Registan_square_Samarkand,_Uzbekistan,_at_night.jpg" target="_blank" rel="noopener">Benjamin Goetzinger</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>. Georgia: <a href="https://commons.wikimedia.org/wiki/File:Gergeti_Trinity_Church_and_Mt._Kazbeg_01.jpg" target="_blank" rel="noopener">Braveheart</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>. Kazakhstan: <a href="https://commons.wikimedia.org/wiki/File:Big_Almaty_Lake.jpg" target="_blank" rel="noopener">Igors Jefimovs</a>, <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank" rel="noopener">CC BY 3.0</a>. Images cropped, resized and converted to WebP. Image adaptations retain their respective licences.</p></details></footer>

<div class="b2b-mobile-cta"><a class="b2b-button" href="#b2b-enquire">Request a B2B proposal <svg class="b2b-icon"><use href="#b2b-i-arrow"/></svg></a><a href="https://wa.me/918368513675?text=Hello%20Dook%2C%20I%20would%20like%20to%20discuss%20a%20B2B%20programme." aria-label="Contact Dook on WhatsApp" target="_blank" rel="noopener"><svg class="b2b-icon"><use href="#b2b-i-whatsapp"/></svg></a></div>
</body>
</html>

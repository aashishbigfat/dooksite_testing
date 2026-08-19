<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="Dook International">     
       <?php $current_route_name = Route::current()->getName(); ?>
        @if (
            $current_route_name == 'frontend.blog' ||
            $current_route_name == 'frontend.blogdetail' ||
            $current_route_name == 'frontend.post_category_wise'
        )
        <link rel="canonical" href="{{ url()->current() }}/" />
        @else
    <link rel="canonical" href="{{ url()->current() }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{URL::current()}}">
        <meta property="og:title" content="@stack('title')">
        <meta property="og:image" content="https://www.dookinternational.com/assets/images/logo.png">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@dooktravels" />
        <meta name="twitter:url" content="{{URL::current()}}">
        <meta name="twitter:title" content="@stack('title')">
        <meta name="twitter:image" content="https://www.dookinternational.com/assets/images/logo.png">
        @endif
        @stack('ogtags')
        @stack('meta_tag')
        <!-- Bootstrap CSS -->
        <title>@stack('title')</title>
        <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicon.ico')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" href="{{asset('assets/webfonts/fa-solid-900.woff2')}}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{asset('assets/webfonts/fa-brands-400.woff2')}}" as="font" type="font/woff2" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
        <link href="{{asset('assets/bootstrap.min.css')}}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{asset('assets/style.css')}}" />  
        @stack('head_script')

        <!-- Include jQuery first -->
        <script src="{{asset('assets/jquery-3.6.0.min.js')}}"></script>      
        @stack('head_script')

        <script type='application/ld+json'>
          {
          "@context": "http://schema.org",
          "@type": "Product",
          "@brand": "Dook International",
          "name": "Dook International Products Reviews Given by Customers, Travel Agents and Travel Agencies",
          "image": "https://www.dookinternational.com/assets/images/logo.png",
          "description": "We would like to thanks Dook International for such a wonderful trip. It was lifetime memorable trip and arrangement was so good like Hotels, Local Guide, Food, Sightseeing, Visa Support, Airlines, Attractions Included in Package, etc. We are very happy with the excellent services provided by Dook International. In future Dook will our preference for any CIS & European Countries Tour.",
          "aggregateRating": {
            "@type": "aggregateRating",
            "ratingValue": "4.3",
            "bestRating": "5",
            "reviewCount": "11200"
          }
        }
        </script>
        <!-- Google Tag Manager -->
         <script>
          window.addEventListener("load", function () {

            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
              'gtm.start': new Date().getTime(),
              event: 'gtm.js'
            });

            var gtm = document.createElement("script");
            gtm.async = true;
            gtm.src = "https://www.googletagmanager.com/gtm.js?id=GTM-KXXVMQ9";
            document.head.appendChild(gtm);

          });
          </script>
            <!-- End Google Tag Manager -->
      <script type="application/ld+json">
        {
        "@context": "https://www.schema.org",
        "@type": "Organization",
        "name": "Dook International",
        "url": "https://www.dookinternational.com",
        "sameAs": [
        "https://www.dookinternational.com/contact-us", 
        "https://www.facebook.com/dooktravels", 
        "https://x.com/dooktravels", 
        "https://www.instagram.com/dooktravels/", 
        "https://www.youtube.com/user/explorebug"
        ],
        "logo": "https://www.dookinternational.com/assets/images/logo.png",
        "description": "Dook International is a leading Destination Management Company operating in CIS countries and India. We offer Outbound travel from India to CIS countries covering Russia, Uzbekistan, Kazakhstan, Kyrgyzstan, Armenia, Georgia, Azerbaijan, Tajikistan, Turkmenistan, Belarus, Serbia, Bulgaria and Turkey. We have been operating in these sectors since last 15 years and established ourselves as market leaders.",
        "address": {
        "@type": "PostalAddress",
        "streetAddress": "3rd Floor, World Trade Tower, 304, Sector 16",
        "addressLocality": "Noida",
        "addressRegion": "Uttar Pradesh",
        "postalCode": "201301",
        "addressCountry": "IN"
        },
        "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+911140001000",
        "contactType": "Customer Service"
        }
        }
    </script>
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What destinations does Dook International offer tours to?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Dook International offers tours to CIS countries including Russia, Uzbekistan, Kazakhstan, Kyrgyzstan, Armenia, Georgia, Azerbaijan, Tajikistan, Turkmenistan, Belarus, Serbia, Bulgaria, and Turkey, as well as popular destinations in India."
      }
    },
    {
      "@type": "Question",
      "name": "How can I book a tour package with Dook International?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can book a tour package by visiting our website, selecting your preferred destination, and filling out the inquiry form. Our team will get in touch with you to finalize your booking."
      }
    },
    {
      "@type": "Question",
      "name": "Do you provide visa assistance for international travel?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, Dook International provides comprehensive visa assistance for all international destinations we cover, making your travel experience hassle-free."
      }
    },
    {
      "@type": "Question",
      "name": "Are group tours available?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we offer group tours for families, friends, and corporate groups. Special group discounts are available on select packages."
      }
    },
    {
      "@type": "Question",
      "name": "What types of travel packages are available?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "We offer a variety of travel packages including honeymoon tours, family tours, adventure tours, city breaks, and customized itineraries tailored to your needs."
      }
    },
    {
      "@type": "Question",
      "name": "Can I customize my travel itinerary?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, Dook International provides fully customizable travel itineraries to suit your preferences, budget, and travel style."
      }
    }
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://www.dookinternational.com/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "International Tour Packages",
      "item": "https://www.dookinternational.com/international-tour-packages"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "CIS Countries Tour",
      "item": "https://www.dookinternational.com/cis-countries-tour"
    }
  ]
}
</script>
    <script>
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref=bwt";
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "t818i462wr");
</script>
        @stack('css')
    </head>
    <body style="overflow-x: hidden;">
             <!-- ANIMATION VISUAL ELEMENTS -->
    <div class="flight-path" id="flightPath"></div>
    <div class="particles-container" id="particlesContainer"></div>

    <!-- SEARCH CLONE FOR ANIMATION -->
    <div class="search-clone" id="searchClone">
      <div class="tours-search-box">
        <div class="search-icon-wrapper-tours">
          <i class="fas fa-search"></i>
        </div>
        <input
          type="text"
          class="tours-search-input"
          placeholder="Search destinations, attractions, or activities..."
          readonly
        />
        <button class="btn-search-tours">
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </div>
        <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KXXVMQ9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  
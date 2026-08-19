@extends('frontend.layouts.master')
@push('title') Request Failed - Dook @endpush
@push('meta_tag')
    <meta name="keywords" content="Itinerary">
    <meta name="description" content="Request Failed">
@endpush

@section('content')

<!-- home section -->
<div class="container">
    <div class="row mb-4 mt-4 justify-content-center">
    	<div class="col-md-9">
    		<img src="{{asset('assets/images/Group 48098917.png')}}" class="w-100 h-100">
    	</div>
    	<div class="col-md-6 text-center">
        <h5>Thanks For Exploring With Us!</h5>
        <p>Thank you for trusting Dook with your Travel plans. Whether you relaxed on a beach,
explored vibrant cities, or embraced nature’s beauty, we’re so glad to have been part of your journey.</p>
		<a href="/" class="btn btn-danger">Continue</a>
		</div>
    </div>
</div>
<!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '434898591343587');
      fbq('track', 'PageView');
      fbq('track', 'CompleteRegistration');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=434898591343587&ev=PageView&noscript=1"
    /></noscript>
  <!-- End Facebook Pixel Code -->

  <!-- Event snippet for Submit lead form conversion page -->
    <script>
      gtag('event', 'conversion', {'send_to': 'AW-807561221/tLWGCNetiPMBEIXQiYED'});
    </script>
    <!-- Event snippet for Domestic_Submit lead form conversion page -->
<script>
  gtag('event', 'conversion', {'send_to': 'AW-568311685/2Sb0CNqd2eEBEIX__o4C'});
</script>
@include('frontend.common.testimonial')
@endsection

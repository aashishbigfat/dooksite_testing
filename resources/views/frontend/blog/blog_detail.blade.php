@extends('frontend.layouts.master')
@push('title') {{$post_detail['meta_title']}} @endpush
@push('meta_tag')
<meta name="keywords" content="{{$post_detail['meta_keywords']}}">
<meta name="description" content="{{$post_detail['meta_description']}}">@endpush
@section('content')
<style>
    .blog_date h4 {
  position: relative;
  left: 225px;
  z-index: 9999;
  margin-top: -29px;
  color: #fff;
  font-size: 14px;
}
.hightlights h6, p {
  font-size: 16px;
}
</style> 
<!-- home section -->
<div class="container mb-5">
    <div class="row mt-4 mb-4">
        <div class="col-md-12 header-sticky">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{route('frontend.blog')}}/" class="text-danger">Blog</a>/ {{$post_detail['title']}}</p>
                 <ul class="nav nav-tabs shadow-sm bg-white rounded tab mb-4"> 
                    <li><button class="tablinks" onclick="openCity(event, 'Latest')" id="defaultOpen">Latest Blog</button></li>
                    <li><button class="tablinks" onclick="openCity(event, 'Recent')">Recent Blog</button></li>
                </ul>
        </div>
        <div class="col-md-9">
			<div id="Latest" class="tabcontent">
		            <div class="row">
		              <div class="col-12 sectionHeading">
		                <h1>{{$post_detail['title']}}</h1>
		              </div>
		            </div>
		            
		            <div class="d-flex align-items-center justify-content-between mb-3">
		              <div><strong>Published:</strong>
		                <time datetime="2018-08-18">{{$post_detail['published_date']}}</time>
		              </div>
		              <div class="shareBlogPost">
		                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
		                <a class="a2a_button_facebook"></a>
		                <a class="a2a_button_twitter"></a>
		                <a class="a2a_button_linkedin"></a>
		                </div>
		                
		              </div>
		            </div>

		            <div class="row mb-5">
		              <div class="col-12 blogDetailContent text-justify">
		                <div>
		                  {!! $post_detail['description'] !!}
		                </div>
		              </div>
		              <div class="col-12">
		                <ul class="list-inline">
		                  <li class="list-inline-item">TAGS:</li>
		                  @foreach($post_detail['related_tags'] as $tag)
		                    <li class="list-inline-item tagBox"><a href="javascript:void(0);">{{$tag['name']}}</a></li>
		                  @endforeach
		                 
		                </ul>
		              </div>
		            </div>
			</div>
			<div id="Recent" class="tabcontent">
                  <h3>Recent Blog</h3>
			    <div class="row">
                    @include('frontend.common.recent_blog')
                </div>
			</div>                 
       </div>
       <div class="col-md-3 mt-2">
       	 <div class="shadow p-3 mb-3 bg-white rounded">
              <h5 class="px-2">Book With Confidence</h5>
              <p class="color_gray"><img src="{{asset('assets/images/icons/thumbs-up.png')}}" alt="" class="px-2"> No-hassle best price guarantee</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> Customer care available 24/7</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/star.png')}}" alt="" class="px-2"> Hand-picked Tours & Activities</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/crosshair.png')}}" alt="" class="px-2"> Free Travel Insureance</p>
  
            </div>
  
            <div class="shadow p-3  bg-white rounded">
              <h5 class="px-2">Need Help?</h5>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> +911140001000</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mailbox.png')}}" alt="" class="px-2"> sales@dooktravels.com</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/chat.png')}}" alt="" class="px-2"> +918368513675</p>
            </div>
       </div>
    </div>

</div>


<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
</script>
@endsection
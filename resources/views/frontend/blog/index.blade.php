@extends('frontend.layouts.master')
@push('title') Dook International Blog - Travel Blogs, Travel Stories, Travel Destinations @endpush
@push('meta_tag')
<meta name="keywords" content="Dook Blog, Dook International Blog, Dook Travels Blog, Travel Blog, Travel Articles, Travel Stories, Travel Tips, Travel News, Travel Updates">
<meta name="description" content="Follow Dook International Blog for Worldwide Travel Destinations & Attractions, Travel Stories, Travel Trends, Travel Tips and Guide, Travel Experiences!">@endpush
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
</style> 
<!-- home section -->
<div class="container mb-5">
    <div class="row mt-4 mb-5">
        <div class="col-md-12 header-sticky">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> / Blog</p>
                 <ul class="nav nav-tabs shadow-sm bg-white rounded tab mb-4"> 
                    <li><button class="tablinks" onclick="openCity(event, 'Latest')" id="defaultOpen">Latest Blog</button></li>
                    <li><button class="tablinks" onclick="openCity(event, 'Recent')">Recent Blog</button></li>
                </ul>
        </div>
        <div class="col-md-9">
			<div id="Latest" class="tabcontent">
                <h3>Latest Blog</h3>
                <div class="row">
			   @include('frontend.common.blog')
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
          <h4 class="px-2">Search Posts</h4>
         <div class="form-group">
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Keyword...">
              </div>
            <button type="submit" class="btn btn-danger w-100 mt-2">Search</button>  
        </div>
        <div class="shadow p-3 mb-3 bg-white rounded">
          <h4 class="px-2">Categories</h4>
         <div class="form-group">
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Tap Here and Select Country...">
              </div>
        </div>
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